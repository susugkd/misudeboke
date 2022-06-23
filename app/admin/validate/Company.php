<?php 
/*
 module:		公司管理控制器
 create_time:	2021-10-13 23:06:50
 author:		
 contact:		
*/

namespace app\admin\validate;
use think\validate;

class Company extends validate {


	protected $rule = [
		'company_name'=>['require'],
		'contacts'=>['require'],
		'phone'=>['require'],
	];

	protected $message = [
		'company_name.require'=>'供应商名称不能为空',
		'contacts.require'=>'联系人不能为空',
		'phone.require'=>'联系电话不能为空',
	];

	protected $scene  = [
		'add'=>['company_name','contacts','phone'],
		'update'=>['company_name','contacts','phone'],
	];



}

