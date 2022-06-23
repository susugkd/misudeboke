<?php 
/*
 module:		推荐位管理模型
 create_time:	2021-04-18 11:55:33
 author:		
 contact:		
*/

namespace app\admin\model\Cms;
use think\Model;

class Position extends Model {


	protected $connection = 'mysql';

 	protected $pk = 'position_id';

 	protected $name = 'position';
 

}

