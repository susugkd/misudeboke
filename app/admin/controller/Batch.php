<?php 
/*
 module:		批量操作演示控制器
 create_time:	2021-10-13 23:07:05
 author:		
 contact:		
*/

namespace app\admin\controller;
use think\exception\ValidateException;
use app\admin\model\Batch as BatchModel;
use think\facade\Db;

class Batch extends Admin {


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
//			$where['batch_id'] = $this->request->post('batch_id', '', 'serach_in');
			$where['title'] = ['like',$this->request->post('title', '', 'serach_in')];
//			$where['sex'] = $this->request->post('sex', '', 'serach_in');
//			$where['status'] = $this->request->post('status', '', 'serach_in');
			$where['bq'] = ['like',$this->request->post('bq', '', 'serach_in')];

			$field = 'batch_id,title,sex,status,amount,wb,bq,sortid,file,jsq';
			$res = BatchModel::where(formatWhere($where))->field($field)->order('batch_id desc')->paginate(['list_rows'=>$limit,'page'=>$page])->toArray();

			$data['status'] = 200;
			$data['data'] = $res;
			return json($data);
		}
	}
	/*
 	* @Description  修改排序开关
 	*/
	function updateExt(){
		$postField = 'batch_id,status,sortid';
		$data = $this->request->only(explode(',',$postField),'post',null);
		if(!$data['batch_id']) throw new ValidateException ('参数错误');
		BatchModel::update($data);
		return json(['status'=>200,'msg'=>'操作成功']);
	}

	/*
 	* @Description  添加
 	*/
	public function add(){
		$postField = 'title,sex,status,amount,wb,bq,sortid,file,jsq';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Batch::class);
		$data['bq'] = implode(',',$data['bq']);

		try{
			$res = BatchModel::create($data);
			if($res->batch_id && empty($data['sortid'])){
				 BatchModel::update(['sortid'=>$res->batch_id,'batch_id'=>$res->batch_id]);
			}
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'data'=>$res->batch_id,'msg'=>'添加成功']);
	}


	/*
 	* @Description  修改
 	*/
	public function update(){
		$postField = 'batch_id,title,sex,status,amount,wb,bq,file,sortid,jsq';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Batch::class);
		$data['bq'] = implode(',',$data['bq']);

		try{
			BatchModel::update($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'msg'=>'修改成功']);
	}


	/*
 	* @Description  修改信息之前查询信息的 勿要删除
 	*/
	function getUpdateInfo(){
		$id =  $this->request->post('batch_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'batch_id,title,sex,status,amount,wb,bq,file,sortid,jsq';
		$res = BatchModel::field($field)->find($id);
		$res['bq'] = explode(',',$res['bq']);
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  删除
 	*/
	function delete(){
		$idx =  $this->request->post('batch_id', '', 'serach_in');
		if(!$idx) throw new ValidateException ('参数错误');
		BatchModel::destroy(['batch_id'=>explode(',',$idx)],true);
		return json(['status'=>200,'msg'=>'操作成功']);
	}


	/*
 	* @Description  批量添加
 	*/
	public function batchAdd(){
		$data = $this->request->post($data);
		(new BatchModel)->saveAll($data['data']);
		return json(['status'=>200,'msg'=>'添加成功']);
	}
    /*
     * @Description  查看详情
     */
    function detail(){
        $id =  $this->request->post('batch_id', '', 'serach_in');
        if(!$id) throw new ValidateException ('参数错误');
        $field = 'batch_id,title,sex,status,amount,wb,bq,file,sortid,jsq';
        $res = BatchModel::field($field)->find($id);
        return json(['status'=>200,'data'=>$res]);
    }

	/*
 	* @Description  批量修改
 	*/
	public function batchUpdate(){
		$data = $this->request->post($data);
		(new BatchModel)->saveAll($data['data']);
		return json(['status'=>200,'msg'=>'修改成功']);
	}




}

