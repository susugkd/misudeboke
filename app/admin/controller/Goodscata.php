<?php 
/*
 module:		商品分类控制器
 create_time:	2021-10-13 23:06:48
 author:		
 contact:		
*/

namespace app\admin\controller;
use think\exception\ValidateException;
use app\admin\model\Goodscata as GoodscataModel;
use think\facade\Db;

class Goodscata extends Admin {


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
			$where['class_id'] = $this->request->post('class_id', '', 'serach_in');

			$field = 'class_id,class_name,status,sortid,pid';

			$res = GoodscataModel::where(formatWhere($where))->field($field)->order('class_id desc')->paginate(['list_rows'=>$limit,'page'=>$page])->toArray();

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
		if(!$data['class_id']) throw new ValidateException ('参数错误');
		GoodscataModel::update($data);
		return json(['status'=>200,'msg'=>'操作成功']);
	}

	/*
 	* @Description  添加
 	*/
	public function add(){
		$postField = 'class_name,pid,status,sortid';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Goodscata::class);

		try{
			$res = GoodscataModel::create($data);
			if($res->class_id && empty($data['sortid'])){
				 GoodscataModel::update(['sortid'=>$res->class_id,'class_id'=>$res->class_id]);
			}
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'data'=>$res->class_id,'msg'=>'添加成功']);
	}


	/*
 	* @Description  修改
 	*/
	public function update(){
		$postField = 'class_id,class_name,pid,status,sortid';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Goodscata::class);

		try{
			GoodscataModel::update($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'msg'=>'修改成功']);
	}


	/*
 	* @Description  修改信息之前查询信息的 勿要删除
 	*/
	function getUpdateInfo(){
		$id =  $this->request->post('class_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'class_id,class_name,pid,status,sortid';
		$res = GoodscataModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  删除
 	*/
	function delete(){
		$idx =  $this->request->post('class_id', '', 'serach_in');
		if(!$idx) throw new ValidateException ('参数错误');
		GoodscataModel::destroy(['class_id'=>explode(',',$idx)],true);
		return json(['status'=>200,'msg'=>'操作成功']);
	}


	/*
 	* @Description  查看详情
 	*/
	function detail(){
		$id =  $this->request->post('class_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'class_id,class_name,status,sortid';
		$res = GoodscataModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
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
			$data['pids'] = _generateSelectTree($this->query('select class_id,class_name,pid from pre_goods_cata'));
		}
		return $data;
	}



}

