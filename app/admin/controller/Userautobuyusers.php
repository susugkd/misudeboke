<?php 
/*
 module:		供应商管理控制器
 create_time:	2021-10-13 23:06:50
 author:		
 contact:		
*/

namespace app\admin\controller;
use think\exception\ValidateException;
use app\admin\model\Userautobuyusers as UserautobuyusersModel;
use think\facade\Db;

class Userautobuyusers extends Admin {


	/*
 	* @Description  数据列表
 	*/
	function demo(){
		if ($this->request->isPost()){
			return view('index');
		}else{
			$limit  = $this->request->post('limit', 20, 'intval');
			$page = $this->request->post('page', 1, 'intval');

			$where = [];
			$where['status'] = $this->request->post('status', '', 'serach_in');
            $where['uid'] = $this->request->post('phone', '', 'serach_in');
            $where['day_str'] = $this->request->post('day_str', '', 'serach_in');
			$where['group_zone'] = $this->request->post('group_zone', '', 'serach_in');
			$field = 'id,uid,status,times,day_str,group_zone';
            $withJoin = [
                'getusername'=>explode(',','nickname'),
                'getuserphone'=>explode(',','phone'),
            ];
			$res = UserautobuyusersModel::where(formatWhere($where))->field($field)->withJoin($withJoin,'left')->order('id desc')->paginate(['list_rows'=>$limit,'page'=>$page])->toArray();

			$data['status'] = 200;
			$data['data'] = $res;
            $page == 1 && $data['sql_field_data'] = $this->getSqlField('uid');
			return json($data);
		}
	}

    /*
     * @Description  获取定义sql语句的字段信息
     */
    private function getSqlField($list){
        $data = [];
        if(in_array('uid',explode(',',$list))){
            $data['user_ids'] = $this->query('select uid,phone from eb_user');
        }
        return $data;
    }





}

