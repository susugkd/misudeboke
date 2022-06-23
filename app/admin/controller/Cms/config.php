<?php

//Cms菜单配置

return [
		[
			'title' => 'CMS管理',
			'sortid'=>999,
			'icon' => 'el-icon-menu',
			'access' => 'admin/Cms',
			'children' =>[
				[
					'title' => '栏目管理',
					'icon' => 'el-icon-menu',
					'url' =>  (string)url('admin/Cms.Catagory/index'),
					'access' => '/admin/cms/catagory.html',
				],
				[
					'title' => '文章管理',
					'icon' => 'el-icon-menu',
					'url' => (string)url('admin/Cms.Content/index'),
					'access' => '/admin/cms/content.html',
				],
				[
					'title' => '碎片管理',
					'icon' => 'el-icon-menu',
					'url' => (string)url('admin/Cms.frament/index'),
					'access' => '/admin/cms/frament.html',
				],
				[
					'title' => '推荐位管理',
					'icon' => 'el-icon-menu',
					'url' => (string)url('admin/Cms.position/index'),
					'access' => '/admin/cms/position.html',
				]
			],
		],
];