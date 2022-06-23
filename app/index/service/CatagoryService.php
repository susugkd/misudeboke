<?php

namespace app\index\service;
use app\index\model\Catagory;

class CatagoryService
{
	
	private $data;
	private $cat;
	
	
	public function __construct(){
		$cat = new \org\Category(['class_id', 'pid', 'class_name','class_name']);
		$data = Catagory::where(['status'=>1])->field('class_id,pid,class_name')->order('sortid asc,class_id asc')->select();
		$this->data = $data;
		$this->cat = $cat;
	}
	
	//获取当前栏目父栏目下的所有子栏目
	public function getSubclassId($class_id){
		$data = $this->cat->getTree($this->data,$class_id);
		if($data){
			$list=[];
			foreach ($data as $value) {
			   $list[]=$value['class_id'];
			}
			return $class_id.','.implode(',', $list);
		}else{
			return $class_id;
		}
	}
	
	
	//返回面包屑信息
	public function getPosition($class_id)
	{
		$data = $this->cat->getPath($this->data,$class_id);
	    $pos = '当前位置：<a href="'.url('index/Index/index').'">首页</a>';
		
		foreach($data as $val)
		{
			$url = getClassUrl($val);
		    $pos .= '&nbsp;&gt;&gt;&nbsp;<a href="'.$url.'">'.$val['class_name'].'</a>';
		} 
		return $pos;
	}
	
	
	//获取顶级栏目ID
	public function getTopBigInfo($class_id){
		$data = $this->cat->getPath($this->data,$class_id);
		return $data[0];
	}

}
