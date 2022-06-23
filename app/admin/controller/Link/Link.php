<?php 
/*
 module:		友情链接控制器
 create_time:	2021-10-13 23:06:58
 author:		
 contact:		
*/

namespace app\admin\controller\Link;
use think\exception\ValidateException;
use app\admin\model\Link\Link as LinkModel;
use app\admin\controller\Admin;
use think\facade\Db;

class Link extends Admin {


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
			$where['link_id'] = $this->request->post('link_id', '', 'serach_in');
			$where['link.title'] = $this->request->post('title', '', 'serach_in');
			$where['link.url'] = $this->request->post('url', '', 'serach_in');
			$where['link.linkcata_id'] = $this->request->post('linkcata_id', '', 'serach_in');
			$where['link.status'] = $this->request->post('status', '', 'serach_in');

			$field = 'link_id,title,url,logo,status,sortid,create_time';

			$withJoin = [
				'linkcata'=>explode(',','class_name'),
			];

			$res = LinkModel::where(formatWhere($where))->field($field)->withJoin($withJoin,'left')->order('link_id desc')->paginate(['list_rows'=>$limit,'page'=>$page])->toArray();

			$data['status'] = 200;
			$data['data'] = $res;
			$page == 1 && $data['sql_field_data'] = $this->getSqlField('linkcata_id');
			return json($data);
		}
	}
	/*
 	* @Description  修改排序开关
 	*/
	function updateExt(){
		$postField = 'link_id,status,sortid';
		$data = $this->request->only(explode(',',$postField),'post',null);
		if(!$data['link_id']) throw new ValidateException ('参数错误');
		LinkModel::update($data);
		return json(['status'=>200,'msg'=>'操作成功']);
	}

	/*
 	* @Description  添加
 	*/
	public function add(){
		$postField = 'title,url,linkcata_id,logo,status,sortid,create_time';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Link\Link::class);
		$data['create_time'] = time();

		try{
			$res = LinkModel::create($data);
			if($res->link_id && empty($data['sortid'])){
				 LinkModel::update(['sortid'=>$res->link_id,'link_id'=>$res->link_id]);
			}
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'data'=>$res->link_id,'msg'=>'添加成功']);
	}


	/*
 	* @Description  修改
 	*/
	public function update(){
		$postField = 'link_id,title,url,linkcata_id,logo,status,sortid,create_time';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Link\Link::class);
		$data['create_time'] = strtotime($data['create_time']);

		try{
			LinkModel::update($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'msg'=>'修改成功']);
	}


	/*
 	* @Description  修改信息之前查询信息的 勿要删除
 	*/
	function getUpdateInfo(){
		$id =  $this->request->post('link_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'link_id,title,url,linkcata_id,logo,status,sortid,create_time';
		$res = LinkModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  删除
 	*/
	function delete(){
		$idx =  $this->request->post('link_id', '', 'serach_in');
		if(!$idx) throw new ValidateException ('参数错误');
		LinkModel::destroy(['link_id'=>explode(',',$idx)],true);
		return json(['status'=>200,'msg'=>'操作成功']);
	}


	/*
 	* @Description  查看详情
 	*/
	function detail(){
		$id =  $this->request->post('link_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$withJoin = [
			'linkcata'=>explode(',','class_name'),
		];

		$field = 'link_id,title,url,logo,status,sortid,create_time';
		$res = LinkModel::field($field)->withJoin($withJoin,'left')->find($id);
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  批量添加
 	*/
	public function batchAdd(){
		$data = $this->request->post($data);
		foreach($data['data'] as $k=>$v){
			$data['data'][$k]['create_time'] = time();
		}
		(new LinkModel)->saveAll($data['data']);
		return json(['status'=>200,'msg'=>'添加成功']);
	}


	/*
 	* @Description  获取定义sql语句的字段信息
 	*/
	function getFieldList(){
		return json(['status'=>200,'data'=>$this->getSqlField('linkcata_id')]);
	}

	/*
 	* @Description  获取定义sql语句的字段信息
 	*/
	private function getSqlField($list){
		$data = [];
		if(in_array('linkcata_id',explode(',',$list))){
			$data['linkcata_ids'] = $this->query('select linkcata_id,class_name from pre_linkcata');
		}
		return $data;
	}



}

