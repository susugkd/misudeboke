<?php 
namespace app\admin\controller\Cms;
use think\exception\ValidateException;
use app\admin\model\Cms\Catagory as CatagoryModel;
use app\admin\controller\Admin;

class Catagory extends Admin {


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

			$field = 'class_id,class_name,type,status,sortid,module_id,pid';
			
			$withJoin = [
				'menu'=>explode(',','title'),
			];

			$res = CatagoryModel::where(formatWhere($where))->field($field)->withJoin($withJoin,'left')->order('sortid asc')->paginate(['list_rows'=>$limit,'page'=>$page])->toArray();

			$res['data'] = _generateListTree($res['data'],0,['class_id','pid']);

			$data['status'] = 200;
			$data['data'] = $res;
			$page == 1 && $data['sql_field_data'] = $this->getSqlField('pid');
			return json($data);
		}
	}


	/*
 	* @Description  修改排序开关
 	*/
	function updateExt(){
		$postField = 'class_id,status,sortid';
		$data = $this->request->only(explode(',',$postField),'post',null);
		if(!$data['class_id']) $this->error('参数错误');
		CatagoryModel::update($data);
		return json(['status'=>200,'msg'=>'操作成功']);
	}

	/*
 	* @Description  添加
 	*/
	public function add(){
		$postField = 'pid,class_name,subtitle,type,list_tpl,detail_tpl,pic,keyword,description,jumpurl,sortid,filepath,filename,module_id,upload_config_id';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Cms\Catagory::class);
		
		$data['keyword'] = implode(',',$data['keyword']);
		$res = CatagoryModel::create($data);
		if($res->class_id && empty($data['sortid'])){
			CatagoryModel::update(['sortid'=>$res->class_id,'class_id'=>$res->class_id]);
		}
		return json(['status'=>200,'data'=>$res->class_id]);
	}


	/*
 	* @Description  修改
 	*/
	public function update(){
		$postField = 'class_id,pid,class_name,subtitle,type,list_tpl,detail_tpl,pic,keyword,description,jumpurl,sortid,filepath,filename,module_id,upload_config_id';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Cms\Catagory::class);
		
		$data['keyword'] = implode(',',$data['keyword']);

		$res = CatagoryModel::update($data);
		return json(['status'=>200]);
	}


	/*
 	* @Description  修改信息之前查询信息的 勿要删除
 	*/
	function getInfo(){
		$id =  $this->request->post('class_id', '', 'serach_in');
		$type =  $this->request->post('type', '', 'serach_in');

		if(!$id && $type ==2) $this->error('参数错误');
		if($type == 1){
			$field = 'class_id,pid,type,list_tpl,detail_tpl,status,filepath,filename,module_id,upload_config_id';
		}else{
			$field = 'class_id,pid,class_name,subtitle,type,list_tpl,detail_tpl,pic,keyword,description,jumpurl,status,sortid,filepath,filename,module_id,upload_config_id';
		}
		if($id){
			$res = CatagoryModel::field($field)->find($id);
		}
		$res['keyword'] && $res['keyword'] = explode(',',$res['keyword']);
		
				
		$data['status'] = 200;
		$data['data'] = $res;
		$data['tplList'] = $this->tplList('index');
		$data['uploadConfigList'] = db("upload_config")->field('id,title')->select()->toArray();
		$data['moduleList'] = db('menu')->field('menu_id,title')->where('status',1)->select();
		return json($data);
	}


	/*
 	* @Description  删除
 	*/
	function delete(){
		$idx =  $this->request->post('class_id', '', 'serach_in');
		if(!$idx) $this->error('参数错误');
		CatagoryModel::destroy(['class_id'=>explode(',',$idx)],true);
		return json(['status'=>200,'msg'=>'操作成功']);
	}


	/*
 	* @Description  table拖动排序
 	*/
	function dragtable(){
		$postField = 'currentId,preId,nextId';
		$data = $this->request->only(explode(',',$postField),'post',null);
		if(!empty($data['preId'])){
			$pre = CatagoryModel::where('class_id',$data['preId'])->value('sortid');
		}
		if(!empty($data['nextId'])){
			$next = CatagoryModel::where('class_id',$data['nextId'])->value('sortid');
		}
		$current = CatagoryModel::where('class_id',$data['currentId'])->value('sortid');
		if($current > $pre){
			$sortid = $next;
		}else{
			$sortid = $pre;
		}
		if(empty($pre)){
			$pre = $next - 1;
			$sortid = $next;
		}
		if(empty($next)){
			$next = $pre + 1;
			$sortid = $pre;
		}
		try{
			if($current > $pre){
				CatagoryModel::where('sortid','between',[$pre+1,$current-1])->inc('sortid',1)->update();
			}
			if($current < $pre){
				CatagoryModel::where('sortid','between',[$current+1,$next-1])->dec('sortid',1)->update();
			}
			CatagoryModel::where('class_id',$data['currentId'])->update(['sortid'=>$sortid]);
		}catch(\Exception $e){
			abort(501,$e->getMessage());
		}
		return json(['status'=>200,'msg'=>'操作成功']);
	}

	/*
 	* @Description  获取定义sql语句的字段信息
 	*/
	function getFieldList(){
		return json(['status'=>200,'data'=>$this->getSqlField('pid')]);
	}

	/*
 	* @Description  获取定义sql语句的字段信息
 	*/
	private function getSqlField($list){
		$data = [];
		if(in_array('pid',explode(',',$list))){
			$data['pids'] = _generateSelectTree($this->query('select class_id,class_name,pid from cd_catagory'));
		}
		return $data;
	}
	
	
	/**
     * 获取当前模板文件
     * @return array 文件列表
     */
    private function tplList($default_themes=''){
        $tplDir=app()->getRootPath().'/app/index/view/'.$default_themes;
        if(!is_dir($tplDir)){
            return false;
        }
        $listFile=scandir($tplDir);
        if(is_array($listFile)){
            $list=array();
            foreach ($listFile as $key => $value) {
                if ($value != "." && $value != "..") {
                    $list[$key]['file']=$value;
                    $list[$key]['name']=substr($value, 0, -5);
                }
            }
        }
        return array_values($list);
    }



}

