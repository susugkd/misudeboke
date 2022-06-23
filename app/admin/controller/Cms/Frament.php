<?php 
/*
 module:		frament控制器
 create_time:	2021-10-06 11:57:55
 author:		
 contact:		
*/

namespace app\admin\controller\Cms;
use think\exception\ValidateException;
use app\admin\model\Cms\Frament as FramentModel;
use app\admin\controller\Admin;
use think\facade\Db;

class Frament extends Admin {


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
			$where['frament_id'] = $this->request->post('frament_id', '', 'serach_in');
			$where['title'] = $this->request->post('title', '', 'serach_in');

			$field = 'frament_id,title';

			$res = FramentModel::where(formatWhere($where))->field($field)->order('frament_id desc')->paginate(['list_rows'=>$limit,'page'=>$page])->toArray();

			$data['status'] = 200;
			$data['data'] = $res;
			return json($data);
		}
	}
	/*
 	* @Description  修改排序开关
 	*/
	function updateExt(){
		$postField = 'frament_id,';
		$data = $this->request->only(explode(',',$postField),'post',null);
		if(!$data['frament_id']) throw new ValidateException ('参数错误');
		FramentModel::update($data);
		return json(['status'=>200,'msg'=>'操作成功']);
	}

	/*
 	* @Description  添加
 	*/
	public function add(){
		$postField = 'title,content';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Cms\Frament::class);

		try{
			$res = FramentModel::create($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'data'=>$res->frament_id,'msg'=>'添加成功']);
	}


	/*
 	* @Description  修改
 	*/
	public function update(){
		$postField = 'frament_id,title,content';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Cms\Frament::class);

		try{
			FramentModel::update($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'msg'=>'修改成功']);
	}


	/*
 	* @Description  修改信息之前查询信息的 勿要删除
 	*/
	function getUpdateInfo(){
		$id =  $this->request->post('frament_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'frament_id,title,content';
		$res = FramentModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  删除
 	*/
	function delete(){
		$idx =  $this->request->post('frament_id', '', 'serach_in');
		if(!$idx) throw new ValidateException ('参数错误');
		FramentModel::destroy(['frament_id'=>explode(',',$idx)],true);
		return json(['status'=>200,'msg'=>'操作成功']);
	}




}

