<?php
namespace app\index\facade;

use think\Facade;

class Cat extends Facade
{
    protected static function getFacadeClass()
    {
    	return 'app\index\service\CatagoryService';
    }
}