<?php 
/*
 module:		时间格式控制器
 create_time:	2021-10-13 23:06:44
 author:		
 contact:		
*/

namespace app\admin\controller;
use think\exception\ValidateException;
use app\admin\model\Times as TimesModel;
use think\facade\Db;

class Times extends Admin {


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
			$where['times_id'] = $this->request->post('times_id', '', 'serach_in');

			$d = $this->request->post('d', '', 'serach_in');
			$where['d'] = ['between',[$d[0],$d[1]]];

			$field = 'times_id,a,b,year,month,sfm,d';

			$res = TimesModel::where(formatWhere($where))->field($field)->order('times_id desc')->paginate(['list_rows'=>$limit,'page'=>$page])->toArray();

			$data['status'] = 200;
			$data['data'] = $res;
			return json($data);
		}
	}
	/*
 	* @Description  添加
 	*/
	public function add(){
		$postField = 'a,b,year,month,sfm,d';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Times::class);
		$data['a'] = strtotime($data['a']);
		$data['b'] = strtotime($data['b']);

		try{
			$res = TimesModel::create($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'data'=>$res->times_id,'msg'=>'添加成功']);
	}


	/*
 	* @Description  修改
 	*/
	public function update(){
		$postField = 'times_id,a,b,year,month,sfm,d';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Times::class);
		$data['a'] = strtotime($data['a']);
		$data['b'] = strtotime($data['b']);

		try{
			TimesModel::update($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'msg'=>'修改成功']);
	}


	/*
 	* @Description  修改信息之前查询信息的 勿要删除
 	*/
	function getUpdateInfo(){
		$id =  $this->request->post('times_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'times_id,a,b,year,month,sfm,d';
		$res = TimesModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  修改排序开关
 	*/
	function updateExt(){
		$postField = 'times_id,';
		$data = $this->request->only(explode(',',$postField),'post',null);
		if(!$data['times_id']) throw new ValidateException ('参数错误');
		TimesModel::update($data);
		return json(['status'=>200,'msg'=>'操作成功']);
	}

	/*
 	* @Description  删除
 	*/
	function delete(){
		$idx =  $this->request->post('times_id', '', 'serach_in');
		if(!$idx) throw new ValidateException ('参数错误');
		TimesModel::destroy(['times_id'=>explode(',',$idx)],true);
		return json(['status'=>200,'msg'=>'操作成功']);
	}


	/*
 	* @Description  查看详情
 	*/
	function detail(){
		$id =  $this->request->post('times_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'times_id,a,b,year,month,sfm,d';
		$res = TimesModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}




}

