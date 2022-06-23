<?php
// +----------------------------------------------------------------------
// | 应用公共文件
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------


use think\facade\Db; 
use think\facade\Log; 
use think\facade\Config; 

error_reporting(0);


//上传文件黑名单过滤
function upload_replace($str){
	$farr = ["/php|php3|php4|php5|phtml|pht|/is"];
	$str = preg_replace($farr,'',$str);
	return $str;
}

//查询方法过滤
function serach_in($str){
	$farr = ["/^select[\s]+|insert[\s]+|and[\s]+|or[\s]+|create[\s]+|update[\s]+|delete[\s]+|alter[\s]+|count[\s]+|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/i"];
	$str = preg_replace($farr,'',$str);
	return trim($str);
}

//获取键值对信息
function getItemData($data){
	if(count($data) > 0){
		foreach($data as $key=>$v){
			if(count($v) == 0){
				unset($data[$key]);
			}
		}
		$str = json_encode(array_values($data),JSON_UNESCAPED_UNICODE);
	}else{
		$str = '';
	}
	return $str;
}


/**
 * tp官方数组查询方法废弃，数组转化为现有支持的查询方法
 * @param array $data 原始查询条件
 * @return array
 */
function formatWhere($data){
	$where = [];
	foreach( $data as $k=>$v){
		if(is_array($v)){
			if(((string) $v[1] <> null && !is_array($v[1])) || (is_array($v[1]) && (string) $v[1][0] <> null)){
				switch(strtolower($v[0])){			
					//模糊查询
					case 'like':
						$v[1] = '%'.$v[1].'%';
					break;
					
					//表达式查询
					case 'exp':
						$v[1] = Db::raw($v[1]);
					break;
				}
				$where[] = [$k,$v[0],$v[1]];
			}
		}else{
			if((string) $v != null){
				$where[] = [$k,'=',$v];
			}
		}
	}
	return $where;
}

	
/**
 * 生成sql查询语句
 * @access protected
 * @param  sql     原始sql语句
 * @param  $where  查询条件
 * @param  $limit  分页
 * @param  $orderby  排序
 * @return array
 */
function loadList($sql,$where=[],$limit,$orderby){
	$sql = str_replace('pre_',config('database.connections.mysql.prefix'),strtolower($sql));
	foreach($where as $key=>$val){
		if(is_array($val)){
			switch($val[1]){
				case 'between':
					if(empty($val[2][0]) && !empty($val[2][1])){
						$map .= $val[0].' < '.$val[2][1].' and ';
					}
					if(!empty($val[2][0]) && empty($val[2][1])){
						$map .= $val[0].' > '.$val[2][1].' and ';
					}
					if(!empty($val[2][0]) && !empty($val[2][1])){
						$map .= $val[0].' between '.$val[2][0].' and '.$val[2][1].' and ';
					}
				break;
				
				case 'exp':
					$map .= $val[0].' '.$val[2].' and ';
				break;
				
				case 'in':
					$map .= $val[0].' in ('.$val[2].') and ';
				break;
				
				case 'not in':
					$map .= $val[0].' not in ('.$val[2].') and ';
				break;
				
				case 'find in set':
					$map .= ' find_in_set(\''.$val[2].'\','.$val[0].') and ';
				break;
				
				default:
					$map .= $val[0].' '.$val[1]." '".$val[2]."'".' and ';
				break;
			}	
		}
	}
	$map .= '1=1';

	$is_where = strripos($sql,"where");
	if($is_where === false){
		$where = !empty($where) ?  ' where '.$map : '';
		$sql = $sql.$where;
	}else{
		$l_sql = substr($sql, 0, $is_where);
		$r_sql = substr($sql, $is_where+5, strlen($sql)- $is_where - 5);
		$where = !empty($where) ?  ' where '.$map.' and ' : ' where ';
		$sql = $l_sql . $where . $r_sql;
	}
	
	$group = preg_split('/group\s+by/',$sql);
	if(is_array($group)){
		$is_where = strripos($group[1],"where");
		if($is_where){
			$l_sql = substr($group[1], 0, $is_where);
			$r_sql = substr($group[1], $is_where+5, strlen($group[1])- $is_where - 5);
			$where = !empty($is_where) ? 'where' : '';
			$sql = $group[0] . $where.$r_sql.' group by '.$l_sql;
		}
	}
	$limit = ' limit '.$limit;
	
	$countWhere = 'select count(*) as count from ('.$sql.') as tp';
	
	if (strripos($sql,"order by")=== false && $orderby) {
		$sql .= ' order by '.$orderby;
	}
	
	if (strripos($sql,"limit")=== false) {
		$sql .= $limit;
	}
	
	$result = Db::query($sql);
	$count = Db::query($countWhere);
	
	return ['data'=>$result,'total'=>$count[0]['count']];
}

/*获取应用url前缀*/
function getBaseUrl(){
	$baseAppName = app('http')->getName();
	if(config('app.app_map')){
		$newapp = array_flip(config('app.app_map'))[$baseAppName];
		if($newapp) $baseAppName = $newapp;
	}

	$basename ='/'.$baseAppName;

	if(config('app.domain_bind')){
		$newapp = array_flip(config('app.domain_bind'))[$baseAppName];
		if($newapp) $basename = '';
	}
	
	return $basename;
}


/**
 * 实例化数据库类
 * @param string        $name 操作的数据表名称（不含前缀）
 * @param array|string  $config 数据库配置参数
 * @param bool          $force 是否强制重新连接
 * @return \think\db\Query
 */
if (!function_exists('db')) {
	
    function db($name = '',$connect='')
    {
		if(empty($connect)){
			$connect = config('database.default');
		}
        return Db::connect($connect,false)->name($name);
    }
}

