<?php 
/*
 module:		友情链接分类控制器
 create_time:	2021-10-13 23:06:55
 author:		
 contact:		
*/

namespace app\admin\controller\Link;
use think\exception\ValidateException;
use app\admin\model\Link\Linkcata as LinkcataModel;
use app\admin\controller\Admin;
use think\facade\Db;

class Linkcata extends Admin {


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
			$where['linkcata_id'] = $this->request->post('linkcata_id', '', 'serach_in');
			$where['class_name'] = $this->request->post('class_name', '', 'serach_in');
			$where['status'] = $this->request->post('status', '', 'serach_in');

			$field = 'linkcata_id,class_name,status,jdt';

			$res = LinkcataModel::where(formatWhere($where))->field($field)->order('linkcata_id desc')->paginate(['list_rows'=>$limit,'page'=>$page])->toArray();

			$data['status'] = 200;
			$data['data'] = $res;
			return json($data);
		}
	}
	/*
 	* @Description  修改排序开关
 	*/
	function updateExt(){
		$postField = 'linkcata_id,status';
		$data = $this->request->only(explode(',',$postField),'post',null);
		if(!$data['linkcata_id']) throw new ValidateException ('参数错误');
		LinkcataModel::update($data);
		return json(['status'=>200,'msg'=>'操作成功']);
	}

	/*
 	* @Description  添加
 	*/
	public function add(){
		$postField = 'class_name,status,jdt';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Link\Linkcata::class);

		try{
			$res = LinkcataModel::create($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'data'=>$res->linkcata_id,'msg'=>'添加成功']);
	}


	/*
 	* @Description  修改
 	*/
	public function update(){
		$postField = 'linkcata_id,class_name,status,jdt';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Link\Linkcata::class);

		try{
			LinkcataModel::update($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'msg'=>'修改成功']);
	}


	/*
 	* @Description  修改信息之前查询信息的 勿要删除
 	*/
	function getUpdateInfo(){
		$id =  $this->request->post('linkcata_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'linkcata_id,class_name,status,jdt';
		$res = LinkcataModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  删除
 	*/
	function delete(){
		$idx =  $this->request->post('linkcata_id', '', 'serach_in');
		if(!$idx) throw new ValidateException ('参数错误');
		LinkcataModel::destroy(['linkcata_id'=>explode(',',$idx)],true);
		return json(['status'=>200,'msg'=>'操作成功']);
	}


	/*
 	* @Description  查看详情
 	*/
	function detail(){
		$id =  $this->request->post('linkcata_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'linkcata_id,class_name,status,jdt';
		$res = LinkcataModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}




}

