<?php 
/*
 module:		公司管理控制器
 create_time:	2021-10-13 23:06:52
 author:		
 contact:		
*/

namespace app\admin\model;
use think\Model;

class Company extends Model {


	protected $connection = 'mysql';

 	protected $pk = 'company_id';

 	protected $name = 'company';


}

