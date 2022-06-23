<?php 
/*
 module:		供应商管理控制器
 create_time:	2021-10-13 23:06:50
 author:		
 contact:		
*/

namespace app\admin\model;
use think\Model;

class Userautobuyusers extends Model {


	protected $connection = 'mysql';

 	protected $pk = 'id';

 	protected $name = 'User_autobuy_users';

    function getusername(){
        return $this->hasOne(\app\admin\model\User::class,'uid','uid');
    }
    function getuserphone(){
        return $this->hasOne(\app\admin\model\User::class,'uid','uid');
    }


}

