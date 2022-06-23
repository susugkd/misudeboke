<?php 
/*
 module:		内容管理模型
 create_time:	2021-04-18 11:23:14
 author:		
 contact:		
*/

namespace app\admin\model\Cms;
use think\Model;

class Content extends Model {


	protected $connection = 'mysql';

 	protected $pk = 'content_id';

 	protected $name = 'content';
	
	function catagory(){
		return $this->hasOne(\app\admin\model\Cms\Catagory::class,'class_id','class_id');
	}
 

}

