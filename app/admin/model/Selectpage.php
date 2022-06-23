<?php 
/*
 module:		下拉框分页控制器
 create_time:	2021-10-17 17:38:11
 author:		
 contact:		
*/

namespace app\admin\model;
use think\Model;

class Selectpage extends Model {


	protected $connection = 'mysql';

 	protected $pk = 'selectpage_id';

 	protected $name = 'selectpage';


	function membe(){
		return $this->hasOne(\app\admin\model\Membe::class,'membe_id','member_id');
	}



}

