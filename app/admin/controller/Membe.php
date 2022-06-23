<?php 
/*
 module:		会员管理控制器
 create_time:	2021-10-13 23:05:54
 author:		
 contact:		
*/

namespace app\admin\controller;
use think\exception\ValidateException;
use app\admin\model\Membe as MembeModel;
use think\facade\Db;

class Membe extends Admin {


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
			$where['membe_id'] = $this->request->post('membe_id', '', 'serach_in');
			$where['username'] = $this->request->post('username', '', 'serach_in');
			$where['sex'] = $this->request->post('sex', '', 'serach_in');
			$where['mobile'] = $this->request->post('mobile', '', 'serach_in');
			$where['email'] = $this->request->post('email', '', 'serach_in');
			$where['status'] = $this->request->post('status', '', 'serach_in');
			$where['amount'] = $this->request->post('amount', '', 'serach_in');

			$create_time = $this->request->post('create_time', '', 'serach_in');
			$where['create_time'] = ['between',[strtotime($create_time[0]),strtotime($create_time[1])]];

			$field = 'membe_id,username,sex,mobile,pic,email,status,amount,ssq,create_time';

			$res = MembeModel::where(formatWhere($where))->field($field)->order('membe_id desc')->paginate(['list_rows'=>$limit,'page'=>$page])->toArray();

			$data['status'] = 200;
			$data['data'] = $res;
			return json($data);
		}
	}
	/*
 	* @Description  修改排序开关
 	*/
	function updateExt(){
		$postField = 'membe_id,status';
		$data = $this->request->only(explode(',',$postField),'post',null);
		if(!$data['membe_id']) throw new ValidateException ('参数错误');
		MembeModel::update($data);
		return json(['status'=>200,'msg'=>'操作成功']);
	}

	/*
 	* @Description  添加
 	*/
	public function add(){
		$postField = 'username,sex,mobile,pic,email,password,status,amount,ssq,create_time';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Membe::class);
		$data['password'] = md5($data['password'].config('my.password_secrect'));
		$data['ssq'] = implode('-',$data['ssq']);
		$data['create_time'] = time();

		try{
			$res = MembeModel::create($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'data'=>$res->membe_id,'msg'=>'添加成功']);
	}


	/*
 	* @Description  修改
 	*/
	public function update(){
		$postField = 'membe_id,username,sex,mobile,pic,email,status,amount,ssq,create_time';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Membe::class);
		$data['ssq'] = implode('-',$data['ssq']);
		$data['create_time'] = strtotime($data['create_time']);

		try{
			MembeModel::update($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'msg'=>'修改成功']);
	}


	/*
 	* @Description  修改信息之前查询信息的 勿要删除
 	*/
	function getUpdateInfo(){
		$id =  $this->request->post('membe_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'membe_id,username,sex,mobile,pic,email,status,amount,ssq,create_time';
		$res = MembeModel::field($field)->find($id);
		$res['ssq'] = explode('-',$res['ssq']);
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  删除
 	*/
	function delete(){
		$idx =  $this->request->post('membe_id', '', 'serach_in');
		if(!$idx) throw new ValidateException ('参数错误');
		MembeModel::destroy(['membe_id'=>explode(',',$idx)],true);
		return json(['status'=>200,'msg'=>'操作成功']);
	}


	/*
 	* @Description  查看详情
 	*/
	function detail(){
		$id =  $this->request->post('membe_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'membe_id,username,sex,mobile,pic,email,status,amount,ssq,create_time';
		$res = MembeModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  导入
 	*/
	public function importData(){
		$data = $this->request->post();
		$list = [];
		foreach($data as $key=>$val){
			$list[$key]['username'] = $val['用户名'];
			$list[$key]['sex'] = getValByKey($val['性别'],'[{"key":"男","val":"1","label_color":""},{"key":"女","val":"2","label_color":""}]');
			$list[$key]['mobile'] = $val['手机号'];
			$list[$key]['pic'] = $val['头像'];
			$list[$key]['email'] = $val['邮箱'];
			$list[$key]['password'] = $val['密码'] ? md5($val['密码']) : '';
			$list[$key]['status'] = getValByKey($val['状态'],'[{"key":"开启","val":"1"},{"key":"关闭","val":"0"}]');
			$list[$key]['amount'] = $val['积分'];
			$list[$key]['ssq'] = $val['省市区'];
			$list[$key]['create_time'] = time();
		}
		(new MembeModel)->saveAll($list);
		return json(['status'=>200]);
	}


	/*
 	* @Description  导出
 	*/
	function dumpdata(){
		$page = $this->request->post('page', 1, 'intval');
		$limit = config('my.dumpsize') ? config('my.dumpsize') : 1000;

		$where = [];
		$where['membe_id'] = ['in',$this->request->post('membe_id', '', 'serach_in')];
		$where['username'] = $this->request->post('username', '', 'serach_in');
		$where['sex'] = $this->request->post('sex', '', 'serach_in');
		$where['mobile'] = $this->request->post('mobile', '', 'serach_in');
		$where['email'] = $this->request->post('email', '', 'serach_in');
		$where['status'] = $this->request->post('status', '', 'serach_in');
		$where['amount'] = $this->request->post('amount', '', 'serach_in');

		$create_time = $this->request->post('create_time', '', 'serach_in');
		$where['create_time'] = ['between',[strtotime($create_time[0]),strtotime($create_time[1])]];

		$field = 'membe_id,username,sex,mobile,pic,email,password,status,amount,ssq,create_time';

		$res = MembeModel::where(formatWhere($where))->field($field)->order('membe_id desc')->limit(($page-1)*$limit,$limit)->select()->toArray();

		foreach($res as $key=>$val){
			$res[$key]['sex'] = getItemVal($val['sex'],'[{"key":"男","val":"1","label_color":""},{"key":"女","val":"2","label_color":""}]');
			$res[$key]['status'] = getItemVal($val['status'],'[{"key":"开启","val":"1"},{"key":"关闭","val":"0"}]');
			$res[$key]['create_time'] = date('Y-m-d',$val['create_time']);
		}

		$data['status'] = 200;
		$data['header'] = explode(',','编号,用户名,性别,手机号,头像,邮箱,密码,状态,积分,省市区,创建时间');
		$data['percentage'] = ceil($page * 100/ceil(MembeModel::where(formatWhere($where))->count()/$limit));
		$data['filename'] = '会员管理.'.config('my.dump_extension');
		$data['data'] = $res;
		return json($data);
	}


	/*
 	* @Description  重置密码
 	*/
	public function resetPwd(){
		$postField = 'membe_id,password';
		$data = $this->request->only(explode(',',$postField),'post',null);
		if(empty($data['membe_id'])) throw new ValidateException ('参数错误');
		if(empty($data['password'])) throw new ValidateException ('密码不能为空');

		$data['password'] = md5($data['password'].config('my.password_secrect'));
		$res = MembeModel::update($data);
		return json(['status'=>200,'msg'=>'操作成功']);
	}


	/*
 	* @Description  数值加
 	*/
	public function jia(){
		$postField = 'membe_id,amount';
		$data = $this->request->only(explode(',',$postField),'post',null);
		if(empty($data['membe_id'])) throw new ValidateException ('参数错误');
		if(empty($data['amount'])) throw new ValidateException ('值不能为空');
		$res = MembeModel::field('amount')->where('membe_id',$data['membe_id'])->inc('amount',$data['amount'])->update();
		return json(['status'=>200,'msg'=>'操作成功']);
	}


	/*
 	* @Description  数值减
 	*/
	public function jian(){
		$postField = 'membe_id,amount';
		$data = $this->request->only(explode(',',$postField),'post',null);
		if(empty($data['membe_id'])) throw new ValidateException ('参数错误');
		if(empty($data['amount'])) throw new ValidateException ('值不能为空');

		if($data['amount'] > MembeModel::where('membe_id',$data['membe_id'])->value('amount')){
			throw new ValidateException('数据不足');
		}
		$res = MembeModel::field('amount')->where('membe_id',$data['membe_id'])->dec('amount',$data['amount'])->update();
		return json(['status'=>200,'msg'=>'操作成功']);
	}


	/*
 	* @Description  禁用
 	*/
	public function forbidden(){
		$idx = $this->request->post('membe_id', '', 'serach_in');
		if(empty($idx)) throw new ValidateException ('参数错误');

		$data['status'] = '0';
		$res = MembeModel::field('status')->where(['membe_id'=>explode(',',$idx)])->update($data);
		return json(['status'=>200,'msg'=>'操作成功']);
	}




}

