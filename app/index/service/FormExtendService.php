<?php

namespace app\index\service;
use think\exception\ValidateException;
use think\facade\Validate;

class FormExtendService
{
	
	//提交表单数据
	public function saveData($formData){
		$fieldList = db("field")->where('menu_id',$formData['form_id'])->select();
		if(!$fieldList){
			throw new ValidateException('模型不存在');
		} 
		$extInfo = db('menu')->where('menu_id',$formData['form_id'])->find();
		if(!$extInfo['is_post']){
			throw new ValidateException('禁止投稿');
		}
		
		foreach($fieldList as $key=>$v){		
			$rule = [];
			if(!empty($v['validate']) || !empty($v['rule'])){
				if(in_array('notempty',explode(',',$v['validate']))){
					array_push($rule,'require');
					$msg[$v['field'].'.require'] = $v['title'].'不能为空';
				}

				if(in_array('unique',explode(',',$v['validate']))){
					array_push($rule,'unique:'.$extInfo['table_name']);
					$msg[$v['field'].'.unique'] = $v['title'].'已存在';
				}
				
				if(!empty($v['rule'])){
					$rule['regex'] = $v['rule'];
					$msg[$v['field'].'.regex'] = $v['title'].'格式错误';
				}
				$rules[$v['field']] = $rule;
			}
			
			if($v['type'] == 9){
				$formData[$v['field']] = strtotime($formData[$v['field']]);
			}		
			if($v['type'] == 11){
				$formData[$v['field']] = time();
			}
			if($v['type'] == 34){
				$formData[$v['field']] = request()->ip();
			}
		}
		if($rules){
			$validate = Validate::rule($rules)->message($msg);	
			if (!$validate->check($formData)) {
				throw new ValidateException($validate->getError());
			}
		}
		
		db($extInfo['table_name'])->insertGetId($formData);
		
		return true;
	}
	
}
