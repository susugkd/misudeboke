<?php 
/*
 module:		地图组件控制器
 create_time:	2021-10-13 23:06:38
 author:		
 contact:		
*/

namespace app\admin\controller;
use think\exception\ValidateException;
use app\admin\model\Map as MapModel;
use think\facade\Db;

class Map extends Admin {


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
			$where['map_id'] = $this->request->post('map_id', '', 'serach_in');
			$where['title'] = $this->request->post('title', '', 'serach_in');

			$field = 'map_id,title,bddt,gddt,txdt';

			$res = MapModel::where(formatWhere($where))->field($field)->order('map_id desc')->paginate(['list_rows'=>$limit,'page'=>$page])->toArray();

			$data['status'] = 200;
			$data['data'] = $res;
			return json($data);
		}
	}
	/*
 	* @Description  修改排序开关
 	*/
	function updateExt(){
		$postField = 'map_id,';
		$data = $this->request->only(explode(',',$postField),'post',null);
		if(!$data['map_id']) throw new ValidateException ('参数错误');
		MapModel::update($data);
		return json(['status'=>200,'msg'=>'操作成功']);
	}

	/*
 	* @Description  添加
 	*/
	public function add(){
		$postField = 'title,bddt,gddt,txdt';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Map::class);

		try{
			$res = MapModel::create($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'data'=>$res->map_id,'msg'=>'添加成功']);
	}


	/*
 	* @Description  修改
 	*/
	public function update(){
		$postField = 'map_id,title,bddt,gddt,txdt';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Map::class);

		try{
			MapModel::update($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'msg'=>'修改成功']);
	}


	/*
 	* @Description  修改信息之前查询信息的 勿要删除
 	*/
	function getUpdateInfo(){
		$id =  $this->request->post('map_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'map_id,title,bddt,gddt,txdt';
		$res = MapModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  删除
 	*/
	function delete(){
		$idx =  $this->request->post('map_id', '', 'serach_in');
		if(!$idx) throw new ValidateException ('参数错误');
		MapModel::destroy(['map_id'=>explode(',',$idx)],true);
		return json(['status'=>200,'msg'=>'操作成功']);
	}


	/*
 	* @Description  查看详情
 	*/
	function detail(){
		$id =  $this->request->post('map_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'map_id,title,bddt,gddt,txdt';
		$res = MapModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}




}

