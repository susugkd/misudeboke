<?php 
/*
 module:		推荐位控制器
 create_time:	2021-10-06 11:57:46
 author:		
 contact:		
*/

namespace app\admin\controller\Cms;
use think\exception\ValidateException;
use app\admin\model\Cms\Position as PositionModel;
use app\admin\controller\Admin;
use think\facade\Db;

class Position extends Admin {


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
			$where['position_id'] = $this->request->post('position_id', '', 'serach_in');
			$where['title'] = $this->request->post('title', '', 'serach_in');

			$field = 'position_id,title,sortid';

			$res = PositionModel::where(formatWhere($where))->field($field)->order('position_id desc')->paginate(['list_rows'=>$limit,'page'=>$page])->toArray();

			$data['status'] = 200;
			$data['data'] = $res;
			return json($data);
		}
	}
	/*
 	* @Description  修改排序开关
 	*/
	function updateExt(){
		$postField = 'position_id,sortid';
		$data = $this->request->only(explode(',',$postField),'post',null);
		if(!$data['position_id']) throw new ValidateException ('参数错误');
		PositionModel::update($data);
		return json(['status'=>200,'msg'=>'操作成功']);
	}

	/*
 	* @Description  添加
 	*/
	public function add(){
		$postField = 'title,sortid';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Cms\Position::class);

		try{
			$res = PositionModel::create($data);
			if($res->position_id && empty($data['sortid'])){
				 PositionModel::update(['sortid'=>$res->position_id,'position_id'=>$res->position_id]);
			}
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'data'=>$res->position_id,'msg'=>'添加成功']);
	}


	/*
 	* @Description  修改
 	*/
	public function update(){
		$postField = 'position_id,title,sortid';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Cms\Position::class);

		try{
			PositionModel::update($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'msg'=>'修改成功']);
	}


	/*
 	* @Description  修改信息之前查询信息的 勿要删除
 	*/
	function getUpdateInfo(){
		$id =  $this->request->post('position_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'position_id,title,sortid';
		$res = PositionModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  删除
 	*/
	function delete(){
		$idx =  $this->request->post('position_id', '', 'serach_in');
		if(!$idx) throw new ValidateException ('参数错误');
		PositionModel::destroy(['position_id'=>explode(',',$idx)],true);
		return json(['status'=>200,'msg'=>'操作成功']);
	}




}

