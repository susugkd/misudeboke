<?php
namespace app\admin\controller\Cms;
use think\exception\ValidateException;
use app\admin\model\Cms\Content as ContentModel;
use app\admin\controller\Admin;

class Content extends Admin {


	/*
 	* @Description  数据列表
 	*/
	function index(){
		if (!$this->request->isPost()){
			return view('index');
		}else{
			$limit  = $this->request->post('limit', 20, 'intval');
			$page = $this->request->post('page', 1, 'intval');

			$where = [];
			$where['content.title'] = $this->request->post('title', '', 'serach_in');
			$where['content.class_id'] = $this->request->post('class_id', '', 'serach_in');
			$where['content.status'] = $this->request->post('status', '', 'serach_in');
			$where['content.position'] = $this->request->post('position', '', 'serach_in');

			$field = 'content_id,title,position,pic,class_id,status,create_time,sortid';

			$withJoin = [
				'catagory'=>explode(',','class_name'),
			];

			$res = ContentModel::where(formatWhere($where))->field($field)->withJoin($withJoin,'left')->order('sortid desc')->paginate(['list_rows'=>$limit,'page'=>$page])->toArray();

			$data['status'] = 200;
			$data['data'] = $res;
			$page == 1 && $data['sql_field_data'] = $this->getSqlField('class_id,position');
			return json($data);
		}
	}


	/*
 	* @Description  修改排序开关
 	*/
	function updateExt(){
		$postField = 'content_id,status,sortid';
		$data = $this->request->only(explode(',',$postField),'post',null);
		if(!$data['content_id']) $this->error('参数错误');

		ContentModel::update($data);

		return json(['status'=>200,'msg'=>'操作成功']);
	}

	/*
 	* @Description  添加
 	*/
	public function add(){
		$data = $this->request->post();
		$this->validate($data,\app\admin\validate\Cms\Content::class);
		$data['position'] = implode(',',$data['position']);
		$data['create_time'] = time();
		$data['keyword'] = implode(',',$data['keyword']);

		$res = ContentModel::create($data);
		if($res->content_id && empty($data['sortid'])){
			ContentModel::update(['sortid'=>$res->content_id,'content_id'=>$res->content_id]);
			$this->saveExtData(array_merge(['content_id'=>$res->content_id],$data));
		}

		return json(['status'=>200,'data'=>$res->content_id]);
	}


	/*
 	* @Description  修改
 	*/
	public function update(){
		$data = $this->request->post();

		$this->validate($data,\app\admin\validate\Cms\Content::class);

		$data['position'] = implode(',',$data['position']);
		$data['create_time'] = strtotime($data['create_time']);
		$data['keyword'] = implode(',',$data['keyword']);

		$res = ContentModel::update($data);
		if($res){
			$this->saveExtData($data);
		}
		return json(['status'=>200]);
	}


	/*
 	* @Description  修改信息之前查询信息的 勿要删除
 	*/
	function getInfo(){
		$id =  $this->request->post('content_id', '', 'serach_in');
		if(!$id) $this->error('参数错误');
		$field = 'content_id,title,class_id,pic,detail,status,position,jumpurl,create_time,author,keyword,description,sortid,views';
		$res = ContentModel::field($field)->find($id);

		$module_id = db('catagory')->where('class_id',$res['class_id'])->value('module_id');
		$extInfo = db("menu")->where('menu_id',$module_id)->find();
		if($extInfo){
			$extendInfo = db($extInfo['table_name'])->where(['content_id'=>$res['content_id']])->find();
		}

		if(!empty($module_id)){
			$fieldList = db('field')->where(['menu_id'=>$module_id])->select()->toArray();
			foreach($fieldList as $k=>$v){
				if(empty($v['datetime_config'])){
					$v['datetime_config'] = 'datetime';
				}
				if($v['type'] == 9 && in_array($v['datetime_config'],['datetime','date']) && $v['datatype'] == 'int'){
					if($extendInfo[$v['field']]){
						$extendInfo[$v['field']] = date('Y-m-d',$extendInfo[$v['field']]);
					}
				}
				if(in_array($v['type'],[3,5,10,18,22])){
					if($extendInfo[$v['field']]){
						$extendInfo[$v['field']] = explode(',',$extendInfo[$v['field']]);
					}else{
						$extendInfo[$v['field']] = [];
					}

				}
				if(in_array($v['type'],[14,16,21])){
					if($extendInfo[$v['field']]){
						$extendInfo[$v['field']] = json_decode($extendInfo[$v['field']],true);
					}else{
						$extendInfo[$v['field']] = [];
					}
				}
			}
		}

		$res['position'] = explode(',',$res['position']);
		$res['keyword'] = explode(',',$res['keyword']);

		$data['status'] = 200;
		$data['data'] = $res;
		$data['extendData'] = $extendInfo;
		$data['extendList'] = db("field")->field('title,field,type,item_config,datetime_config,datatype,other_config')->where('list_show',2)->where('menu_id',$module_id)->order('sortid asc')->select()->toArray();
		foreach($data['extendList'] as $k=>$v){
			if($v['type'] == 13 && !empty($v['other_config'])){
				$other_config = json_decode($v['other_config'],true);
				$upload_type = $other_config['upload_type'] ? (int)$other_config['upload_type'] : 2;
				$data['extendList'][$k]['upload_type'] = $upload_type;
			}
		}
		return json($data);
	}


	/*
 	* @Description  删除
 	*/
	function delete(){
		$idx =  $this->request->post('content_id', '', 'serach_in');
		if(!$idx) $this->error('参数错误');
		foreach(explode(',',$idx) as $v){
			$info = db('content')->field('b.module_id')->alias('a')->join('catagory b','a.class_id=b.class_id')->where('content_id',$v)->find();
			if($info['module_id']){
				$moduleTable = db('menu')->where('menu_id',$info['module_id'])->value('table_name');
				db($moduleTable)->where('content_id',$v)->delete();
			}
			ContentModel::destroy(['content_id'=>$v],true);
		}
		return json(['status'=>200,'msg'=>'操作成功']);
	}



	//设置推荐位
	public function setPosition(){
		$action_type = $this->request->post('action_type');
		$content_id = $this->request->post('content_id');
		$class_id = $this->request->post('class_id');

		if(empty($action_type)){
			$this->error('请选择操作');
		}

		if(empty($content_id)){
			$this->error('请选择操作的数据');
		}
		if($class_id && $action_type == '100'){
			ContentModel::where('content_id','in',$content_id)->update(['class_id'=>$class_id]);
		}else{
			ContentModel::where('content_id','in',$content_id)->update(['position'=>$action_type]);
		}

		return json(['status'=>200,'msg'=>'操作成功']);
	}

	/*
 	* @Description  获取定义sql语句的字段信息
 	*/
	function getFieldList(){
		return json(['status'=>200,'data'=>$this->getSqlField('class_id,position')]);
	}

	/*
 	* @Description  获取定义sql语句的字段信息
 	*/
	private function getSqlField($list){
		$data = [];
		if(in_array('class_id',explode(',',$list))){
			$data['class_ids'] = _generateSelectTree($this->query('select class_id,class_name,pid from cd_catagory'));
		}
		if(in_array('position',explode(',',$list))){
			$data['positions'] = $this->query('select position_id,title from cd_position');
		}
		return $data;
	}

	//获取拓展模型字段
	public function getExtend(){
		$class_id = $this->request->post('class_id');
		$classInfo = db("catagory")->field('module_id,upload_config_id')->where('class_id',$class_id)->find();

		$fieldList = db("field")->field('title,field,type,item_config,datetime_config,datatype,other_config')->where('list_show',2)->where('menu_id',$classInfo['module_id'])->order('sortid asc')->select()->toArray();

		foreach($fieldList as $k=>$v){
			if($v['type'] == 13 && !empty($v['other_config'])){
				$other_config = json_decode($v['other_config'],true);
				$upload_type = $other_config['upload_type'] ? (int)$other_config['upload_type'] : 2;
				$fieldList[$k]['upload_type'] = $upload_type;
			}
		}
		return json(['status'=>200,'data'=>$fieldList,'upload_config_id'=>$classInfo['upload_config_id']]);
	}


	//更新拓展表信息
	 private function saveExtData($data){
        $module_id = db('catagory')->where('class_id',$data['class_id'])->value('module_id');
		if(!empty($module_id)){
			$fieldList = db('field')->where(['menu_id'=>$module_id])->select();
			foreach($fieldList as $k=>$v){
				if(empty($v['datetime_config'])){
					$v['datetime_config'] = 'datetime';
				}
				if($v['type'] == 9 && in_array($v['datetime_config'],['datetime','date']) && $v['datatype'] == 'int'){
					$data[$v['field']] = strtotime($data[$v['field']]);
				}
				if(in_array($v['type'],[3,5,10,18,22])){
					$data[$v['field']] = implode(',',$data[$v['field']]);
				}
				if(in_array($v['type'],[14,16,21])){
					$data[$v['field']] = getItemData($data[$v['field']]);
				}
			}
			$extInfo = db("menu")->where('menu_id',$module_id)->find();
			if($extInfo){
				if(!db($extInfo['table_name'])->where('content_id',$data['content_id'])->find()){
					db($extInfo['table_name'])->insert($data);
				}else{
					db($extInfo['table_name'])->where(['content_id'=>$data['content_id']])->update($data);
				}
			}
		}

        return true;
    }


}

