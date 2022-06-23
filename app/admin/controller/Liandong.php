<?php 
/*
 module:		字段联动演示控制器
 create_time:	2021-10-13 23:07:08
 author:		
 contact:		
*/

namespace app\admin\controller;
use think\exception\ValidateException;
use app\admin\model\Liandong as LiandongModel;
use think\facade\Db;

class Liandong extends Admin {


	/*
 	* @Description  字段联动
 	*/
	public function index(){
		if (!$this->request->isPost()){
			return view('index');
		}else{
			$data = $this->request->post();
			$this->validate($data,\app\admin\validate\Liandong::class);
	
			$info = LiandongModel::column('data','name');
			foreach ($data as $key => $value) {
				if(array_key_exists($key,$info)){
					LiandongModel::field('data')->where(['name'=>$key])->update(['data'=>$value]);
				}else{
					LiandongModel::create(['name'=>$key,'data'=>$value]);
				}
			}
			return json(['status'=>200,'msg'=>'操作成功']);
		}
	}


	/*
 	* @Description  修改信息之前查询信息的 勿要删除
 	*/
	function getInfo(){
		$res = LiandongModel::column('data','name');

		$data['status'] = 200;
		$data['data'] = $res;
		return json($data);
	}




}

