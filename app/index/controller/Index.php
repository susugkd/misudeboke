<?php

namespace app\index\controller;
use app\index\service\BaseService;

class Index extends Base
{
	
	//首页
	public function index(){
		$this->view->assign('media', baseService::getMedia());  //网站关键词描述信息
		$this->view->assign('pid',0);
		$default_themes = config('base_config.default_themes') ? config('base_config.default_themes') : 'index';
		return view($default_themes.'/index');
	}
	
	
	
}
