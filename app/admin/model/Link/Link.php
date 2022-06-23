<?php 
/*
 module:		友情链接控制器
 create_time:	2021-10-13 23:06:58
 author:		
 contact:		
*/

namespace app\admin\model\Link;
use think\Model;

class Link extends Model {


	protected $connection = 'mysql';

 	protected $pk = 'link_id';

 	protected $name = 'link';


	function linkcata(){
		return $this->hasOne(\app\admin\model\Link\Linkcata::class,'linkcata_id','linkcata_id');
	}



}

