<?php 
/*
 module:		下拉框分页控制器
 create_time:	2021-10-17 17:38:11
 author:		
 contact:		
*/

namespace app\admin\controller;
use think\exception\ValidateException;
use app\admin\model\Selectpage as SelectpageModel;
use think\facade\Db;

class Selectpage extends Admin {


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
			$where['selectpage_id'] = $this->request->post('selectpage_id', '', 'serach_in');
			$where['selectpage.title'] = $this->request->post('title', '', 'serach_in');
			$where['selectpage.member_id'] = $this->request->post('member_id', '', 'serach_in');

			$field = 'selectpage_id,title';

			$withJoin = [
				'membe'=>explode(',','username'),
			];

			$res = SelectpageModel::where(formatWhere($where))->field($field)->withJoin($withJoin,'left')->order('selectpage_id desc')->paginate(['list_rows'=>$limit,'page'=>$page])->toArray();

			$data['status'] = 200;
			$data['data'] = $res;
			return json($data);
		}
	}
	/*
 	* @Description  修改排序开关
 	*/
	function updateExt(){
		$postField = 'selectpage_id,';
		$data = $this->request->only(explode(',',$postField),'post',null);
		if(!$data['selectpage_id']) throw new ValidateException ('参数错误');
		SelectpageModel::update($data);
		return json(['status'=>200,'msg'=>'操作成功']);
	}

	/*
 	* @Description  添加
 	*/
	public function add(){
		$postField = 'title,member_id';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Selectpage::class);

		try{
			$res = SelectpageModel::create($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'data'=>$res->selectpage_id,'msg'=>'添加成功']);
	}


	/*
 	* @Description  修改
 	*/
	public function update(){
		$postField = 'selectpage_id,title,member_id';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Selectpage::class);

		try{
			SelectpageModel::update($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'msg'=>'修改成功']);
	}


	/*
 	* @Description  修改信息之前查询信息的 勿要删除
 	*/
	function getUpdateInfo(){
		$id =  $this->request->post('selectpage_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'selectpage_id,title,member_id';
		$res = SelectpageModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  删除
 	*/
	function delete(){
		$idx =  $this->request->post('selectpage_id', '', 'serach_in');
		if(!$idx) throw new ValidateException ('参数错误');
		SelectpageModel::destroy(['selectpage_id'=>explode(',',$idx)],true);
		return json(['status'=>200,'msg'=>'操作成功']);
	}


	/*
 	* @Description  查看详情
 	*/
	function detail(){
		$id =  $this->request->post('selectpage_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'selectpage_id,title';
		$res = SelectpageModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  获取下拉分页的数据
 	*/
	public function getMember_id(){
		$limit  = $this->request->post('limit', 20, 'intval');
		$page = $this->request->post('page', 1, 'intval');

		$where = [];
		$skip = ($page-1) * $limit.','.$limit;
		$data = $this->getSelectPageData('select membe_id,username from pre_membe',$where,$skip); 
		return json(['status'=>200,'data'=>$data]);
	}



}

