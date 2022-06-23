<?php 
/*
 module:		其它常用控制器
 create_time:	2021-10-13 23:06:42
 author:		
 contact:		
*/

namespace app\admin\controller;
use think\exception\ValidateException;
use app\admin\model\Other as OtherModel;
use think\facade\Db;

class Other extends Admin {


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
			$where['other_id'] = $this->request->post('other_id', '', 'serach_in');
			$where['title'] = $this->request->post('title', '', 'serach_in');

			$field = 'other_id,title,jsq,tags,hk,color,jzd,ssq';

			$res = OtherModel::where(formatWhere($where))->field($field)->order('other_id desc')->paginate(['list_rows'=>$limit,'page'=>$page])->toArray();

			$data['status'] = 200;
			$data['data'] = $res;
			return json($data);
		}
	}
	/*
 	* @Description  修改排序开关
 	*/
	function updateExt(){
		$postField = 'other_id,';
		$data = $this->request->only(explode(',',$postField),'post',null);
		if(!$data['other_id']) throw new ValidateException ('参数错误');
		OtherModel::update($data);
		return json(['status'=>200,'msg'=>'操作成功']);
	}

	/*
 	* @Description  添加
 	*/
	public function add(){
		$postField = 'title,jsq,tags,hk,color,jzd,ssq';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Other::class);
		$data['tags'] = implode(',',$data['tags']);
		$data['jzd'] = getItemData($data['jzd']);
		$data['ssq'] = implode('-',$data['ssq']);

		try{
			$res = OtherModel::create($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'data'=>$res->other_id,'msg'=>'添加成功']);
	}


	/*
 	* @Description  修改
 	*/
	public function update(){
		$postField = 'other_id,title,jsq,tags,hk,color,jzd,ssq';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Other::class);
		$data['tags'] = implode(',',$data['tags']);
		$data['jzd'] = getItemData($data['jzd']);
		$data['ssq'] = implode('-',$data['ssq']);

		try{
			OtherModel::update($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'msg'=>'修改成功']);
	}


	/*
 	* @Description  修改信息之前查询信息的 勿要删除
 	*/
	function getUpdateInfo(){
		$id =  $this->request->post('other_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'other_id,title,jsq,tags,hk,color,jzd,ssq';
		$res = OtherModel::field($field)->find($id);
		$res['tags'] = explode(',',$res['tags']);
		$res['jzd'] = json_decode($res['jzd'],true);
		$res['ssq'] = explode('-',$res['ssq']);
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  删除
 	*/
	function delete(){
		$idx =  $this->request->post('other_id', '', 'serach_in');
		if(!$idx) throw new ValidateException ('参数错误');
		OtherModel::destroy(['other_id'=>explode(',',$idx)],true);
		return json(['status'=>200,'msg'=>'操作成功']);
	}


	/*
 	* @Description  查看详情
 	*/
	function detail(){
		$id =  $this->request->post('other_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'other_id,title,jsq,tags,hk,color,jzd,ssq';
		$res = OtherModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}




}

