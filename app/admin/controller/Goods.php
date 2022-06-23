<?php 
/*
 module:		商品管理控制器
 create_time:	2021-10-13 23:06:52
 author:		
 contact:		
*/

namespace app\admin\controller;
use think\exception\ValidateException;
use app\admin\model\Goods as GoodsModel;
use think\facade\Db;

class Goods extends Admin {


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
			$where['goods_id'] = $this->request->post('goods_id', '', 'serach_in');
			$where['goods.class_id'] = $this->request->post('class_id', '', 'serach_in');
			$where['goods.supplier_id'] = $this->request->post('supplier_id', '', 'serach_in');
			$where['goods.status'] = $this->request->post('status', '', 'serach_in');

			if(!in_array(session('admin.role_id'),[1])){
					$where['goods.user_id'] = session('admin.user_id');
			}

			$field = 'goods_id,goods_name,pic,sale_price,status,sortid,create_time';

			$withJoin = [
				'goodscata'=>explode(',','class_name'),
				'supplier'=>explode(',','supplier_name'),
			];

			$res = GoodsModel::where(formatWhere($where))->field($field)->withJoin($withJoin,'left')->order('goods_id desc')->paginate(['list_rows'=>$limit,'page'=>$page])->toArray();

			$data['status'] = 200;
			$data['data'] = $res;
			$page == 1 && $data['sql_field_data'] = $this->getSqlField('class_id,supplier_id');
			return json($data);
		}
	}
	/*
 	* @Description  修改排序开关
 	*/
	function updateExt(){
		$postField = 'goods_id,status,sortid';
		$data = $this->request->only(explode(',',$postField),'post',null);
		if(!$data['goods_id']) throw new ValidateException ('参数错误');
		GoodsModel::update($data);
		return json(['status'=>200,'msg'=>'操作成功']);
	}

	/*
 	* @Description  添加
 	*/
	public function add(){
		$postField = 'goods_name,class_id,supplier_id,pic,sale_price,images,status,cd,store,sortid,create_time,detail,user_id';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Goods::class);
		$data['images'] = getItemData($data['images']);
		$data['create_time'] = time();
		$data['user_id'] = session('admin.user_id');

		try{
			$res = GoodsModel::create($data);
			if($res->goods_id && empty($data['sortid'])){
				 GoodsModel::update(['sortid'=>$res->goods_id,'goods_id'=>$res->goods_id]);
			}
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'data'=>$res->goods_id,'msg'=>'添加成功']);
	}


	/*
 	* @Description  修改
 	*/
	public function update(){
		$postField = 'goods_id,goods_name,class_id,supplier_id,pic,sale_price,images,status,cd,store,sortid,create_time,detail,user_id';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Goods::class);
		$data['images'] = getItemData($data['images']);
		$data['create_time'] = strtotime($data['create_time']);
		$data['user_id'] = session('admin.user_id');

		try{
			GoodsModel::update($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'msg'=>'修改成功']);
	}


	/*
 	* @Description  修改信息之前查询信息的 勿要删除
 	*/
	function getUpdateInfo(){
		$id =  $this->request->post('goods_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'goods_id,goods_name,class_id,supplier_id,pic,sale_price,images,status,cd,store,sortid,create_time,detail,user_id';
		$res = GoodsModel::field($field)->find($id);
		$res['images'] = json_decode($res['images'],true);
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  删除
 	*/
	function delete(){
		$idx =  $this->request->post('goods_id', '', 'serach_in');
		if(!$idx) throw new ValidateException ('参数错误');
		GoodsModel::destroy(['goods_id'=>explode(',',$idx)],true);
		return json(['status'=>200,'msg'=>'操作成功']);
	}


	/*
 	* @Description  查看详情
 	*/
	function detail(){
		$id =  $this->request->post('goods_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'goods_id,goods_name,pic,sale_price,status,sortid,create_time';
		$res = GoodsModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  获取定义sql语句的字段信息
 	*/
	function getFieldList(){
		return json(['status'=>200,'data'=>$this->getSqlField('class_id,supplier_id')]);
	}

	/*
 	* @Description  获取定义sql语句的字段信息
 	*/
	private function getSqlField($list){
		$data = [];
		if(in_array('class_id',explode(',',$list))){
			$data['class_ids'] = _generateSelectTree($this->query('select class_id,class_name,pid from pre_goods_cata'));
		}
		if(in_array('supplier_id',explode(',',$list))){
			$data['supplier_ids'] = $this->query('select supplier_id,supplier_name from pre_supplier');
		}
		return $data;
	}



}

