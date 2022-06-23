<?php 
/*
 module:		日志管理控制器
 create_time:	2021-10-13 23:07:31
 author:		
 contact:		
*/

namespace app\admin\controller;
use think\exception\ValidateException;
use app\admin\model\Log as LogModel;
use think\facade\Db;

class Log extends Admin {


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
			$where['id'] = $this->request->post('id', '', 'serach_in');
			$where['username'] = $this->request->post('username', '', 'serach_in');

			$create_time = $this->request->post('create_time', '', 'serach_in');
			$where['create_time'] = ['between',[strtotime($create_time[0]),strtotime($create_time[1])]];
			$where['type'] = $this->request->post('type', '', 'serach_in');

			$field = 'id,application_name,username,url,ip,create_time,type';

			$res = LogModel::where(formatWhere($where))->field($field)->order('id desc')->paginate(['list_rows'=>$limit,'page'=>$page])->toArray();

			$data['status'] = 200;
			$data['data'] = $res;
			return json($data);
		}
	}
	/*
 	* @Description  删除
 	*/
	function delete(){
		$idx =  $this->request->post('id', '', 'serach_in');
		if(!$idx) throw new ValidateException ('参数错误');
		LogModel::destroy(['id'=>explode(',',$idx)],true);
		return json(['status'=>200,'msg'=>'操作成功']);
	}


	/*
 	* @Description  查看详情
 	*/
	function detail(){
		$id =  $this->request->post('id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'id,application_name,username,url,ip,useragent,content,errmsg,create_time,type';
		$res = LogModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  导出
 	*/
	function dumpdata(){
		$page = $this->request->post('page', 1, 'intval');
		$limit = config('my.dumpsize') ? config('my.dumpsize') : 1000;

		$where = [];
		$where['id'] = ['in',$this->request->post('id', '', 'serach_in')];
		$where['username'] = $this->request->post('username', '', 'serach_in');

		$create_time = $this->request->post('create_time', '', 'serach_in');
		$where['create_time'] = ['between',[strtotime($create_time[0]),strtotime($create_time[1])]];
		$where['type'] = $this->request->post('type', '', 'serach_in');

		$field = 'id,application_name,username,url,ip,useragent,content,errmsg,create_time,type';

		$res = LogModel::where(formatWhere($where))->field($field)->order('id desc')->limit(($page-1)*$limit,$limit)->select()->toArray();

		foreach($res as $key=>$val){
			$res[$key]['create_time'] = date('Y-m-d H:i:s',$val['create_time']);
			$res[$key]['type'] = getItemVal($val['type'],'[{"key":"登录日志","val":"1","label_color":"info"},{"key":"操作日志","val":"2","label_color":"warning"},{"key":"异常日志","val":"3","label_color":"danger"}]');
		}

		$data['status'] = 200;
		$data['header'] = explode(',','编号,应用名,用户名,请求url,客户端ip,浏览器信息,请求内容,异常信息,创建时间,类型');
		$data['percentage'] = ceil($page * 100/ceil(LogModel::where(formatWhere($where))->count()/$limit));
		$data['filename'] = '日志管理.'.config('my.dump_extension');
		$data['data'] = $res;
		return json($data);
	}




}

