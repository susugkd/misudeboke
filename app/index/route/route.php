<?php

//接口路由文件

use think\facade\Route;

Route::rule('about/:class_id','index/About/index');
Route::rule('view/:content_id','index/View/index');
Route::rule('search/:class_id','index/Search/index');

