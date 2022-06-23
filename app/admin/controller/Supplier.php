<?php 
/*
 module:		供应商管理控制器
 create_time:	2021-10-13 23:06:50
 author:		
 contact:		
*/

namespace app\admin\controller;
use think\exception\ValidateException;
use app\admin\model\Supplier as SupplierModel;
use think\facade\Db;

class Supplier extends Admin {


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
			$where['supplier_id'] = $this->request->post('supplier_id', '', 'serach_in');
			$where['supplier_name'] = $this->request->post('supplier_name', '', 'serach_in');
			$where['status'] = $this->request->post('status', '', 'serach_in');
			$where['username'] = $this->request->post('username', '', 'serach_in');

			$field = 'supplier_id,supplier_name,status,username,create_time';

			$res = SupplierModel::where(formatWhere($where))->field($field)->order('supplier_id desc')->paginate(['list_rows'=>$limit,'page'=>$page])->toArray();

			$data['status'] = 200;
			$data['data'] = $res;
			return json($data);
		}
	}
	/*
 	* @Description  修改排序开关
 	*/
	function updateExt(){
		$postField = 'supplier_id,status';
		$data = $this->request->only(explode(',',$postField),'post',null);
		if(!$data['supplier_id']) throw new ValidateException ('参数错误');
		SupplierModel::update($data);
		return json(['status'=>200,'msg'=>'操作成功']);
	}

	/*
 	* @Description  添加
 	*/
	public function add(){
		$postField = 'supplier_name,status,username,password,create_time';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Supplier::class);
		$data['password'] = md5($data['password'].config('my.password_secrect'));
		$data['create_time'] = time();

		try{
			$res = SupplierModel::create($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'data'=>$res->supplier_id,'msg'=>'添加成功']);
	}


	/*
 	* @Description  修改
 	*/
	public function update(){
		$postField = 'supplier_id,supplier_name,status,username,create_time';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Supplier::class);
		$data['create_time'] = strtotime($data['create_time']);

		try{
			SupplierModel::update($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'msg'=>'修改成功']);
	}


	/*
 	* @Description  修改信息之前查询信息的 勿要删除
 	*/
	function getUpdateInfo(){
		$id =  $this->request->post('supplier_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'supplier_id,supplier_name,status,username,create_time';
		$res = SupplierModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  删除
 	*/
	function delete(){
		$idx =  $this->request->post('supplier_id', '', 'serach_in');
		if(!$idx) throw new ValidateException ('参数错误');
		SupplierModel::destroy(['supplier_id'=>explode(',',$idx)],true);
		return json(['status'=>200,'msg'=>'操作成功']);
	}


	/*
 	* @Description  查看详情
 	*/
	function detail(){
		$id =  $this->request->post('supplier_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'supplier_id,supplier_name,status,username,create_time';
		$res = SupplierModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  重置密码
 	*/
	public function resetPwd(){
		$postField = 'supplier_id,password';
		$data = $this->request->only(explode(',',$postField),'post',null);
		if(empty($data['supplier_id'])) throw new ValidateException ('参数错误');
		if(empty($data['password'])) throw new ValidateException ('密码不能为空');

		$data['password'] = md5($data['password'].config('my.password_secrect'));
		$res = SupplierModel::update($data);
		return json(['status'=>200,'msg'=>'操作成功']);
	}




}

