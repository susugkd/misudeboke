<?php

namespace app\index\controller;
use app\index\service\FormExtendService;


class Form extends Base
{
	
	//表单提交页面
	public function index(){
		if ($this->request->isPost()){
			$formData = $this->request->post();
			if(empty($formData['form_id'])){
				$this->error('模型ID不能为空');
			}
			$res = FormExtendService::saveData($formData);
			if($res){
				$this->success('提交成功');
			}
		}
	}
	
	
}
