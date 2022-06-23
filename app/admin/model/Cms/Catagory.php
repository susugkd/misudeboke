<?php 
/*
 module:		栏目管理模型
 create_time:	2021-04-17 22:55:04
 author:		
 contact:		
*/

namespace app\admin\model\Cms;
use think\Model;

class Catagory extends Model {


	protected $connection = 'mysql';

 	protected $pk = 'class_id';

 	protected $name = 'catagory';
	
 
	function menu(){
		return $this->hasOne(\app\admin\model\Cms\Menu::class,'menu_id','module_id');
	}
	
}

