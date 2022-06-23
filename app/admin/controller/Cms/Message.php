<?php 
/*
 module:		推荐位控制器
 create_time:	2021-10-06 11:57:46
 author:		
 contact:		
*/

namespace app\admin\controller\Cms;
use think\exception\ValidateException;
use app\admin\model\Cms\Message as MessageModel;
use app\admin\controller\Admin;
use think\facade\Db;

class Message extends Admin {


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
			$where['title'] = $this->request->post('title', '', 'serach_in');

			$field = 'message_id,name,phone,email,content';

			$res = MessageModel::where(formatWhere($where))->field($field)->order('message_id desc')->paginate(['list_rows'=>$limit,'page'=>$page])->toArray();
			$data['status'] = 200;
			$data['data'] = $res;
			return json($data);
		}
	}


	/*
 	* @Description  删除
 	*/
	function delete(){
		$idx =  $this->request->post('message_id', '', 'serach_in');
		if(!$idx) throw new ValidateException ('参数错误');
        MessageModel::destroy(['message_id'=>explode(',',$idx)],true);
		return json(['status'=>200,'msg'=>'操作成功']);
	}




}

