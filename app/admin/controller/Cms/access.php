<?php

//Cms菜单配置

return [
		[
			'title' => 'CMS管理(admin/Cms)',
			'access' => 'admin/Cms',
			'sortid'=>999,
			'children' =>[
				[
					'title' => '栏目管理(admin/Cms.Catagory)',
					'access' =>  '/admin/cms/catagory.html',
					'children'=>[
						[
							'title'	=> '首页数据列表 (admin/Cms.Catagory/index)',
							'access'	=> (string)url('admin/Cms.Catagory/index'),
						],
						[
							'title'	=> '修改状态排序 (admin/Cms.Catagory/updateExt)',
							'access'	=> (string)url('admin/Cms.Catagory/updateExt'),
						],
						[
							'title'	=> '添加栏目 (admin/Cms.Catagory/add)',
							'access'	=> (string)url('admin/Cms.Catagory/add'),
						],
						[
							'title'	=> '修改栏目 (admin/Cms.Catagory/update)',
							'access'	=> (string)url('admin/Cms.Catagory/update'),
						],
						[
							'title'	=> '删除栏目 (admin/Cms.Catagory/delete)',
							'access'	=> (string)url('admin/Cms.Catagory/delete'),
						],
					],
				],
				[
					'title' => '文章管理(admin/Cms.Content)',
					'access' => '/admin/cms/content.html',
					'children'	=> [
						[
							'title'	=> '首页数据列表 (admin/Cms.Content/index)',
							'access'	=> (string)url('admin/Cms.Content/index'),
						],
						[
							'title'	=> '修改状态排序 (admin/Cms.Content/updateExt)',
							'access'	=> (string)url('admin/Cms.Content/updateExt'),
						],
						[
							'title'	=> '添加文章 (admin/Cms.Content/add)',
							'access'	=> (string)url('admin/Cms.Content/add'),
						],
						[
							'title'	=> '修改文章 (admin/Cms.Content/update)',
							'access'	=> (string)url('admin/Cms.Content/update'),
						],
						[
							'title'	=> '删除文章 (admin/Cms.Content/delete)',
							'access'	=> (string)url('admin/Cms.Content/delete'),
						],
						[
							'title'	=> '设置推荐位 (admin/Cms.Content/setPosition)',
							'access'	=> (string)url('admin/Cms.Content/setPosition'),
						],
						[
							'title'	=> '获取拓展模型 (admin/Cms.Content/getExtend)',
							'access'	=> (string)url('admin/Cms.Content/getExtend'),
						],
					],
				],
				[
					'title' => '碎片管理(admin/Cms.Frament)',
					'access' => '/admin/cms/frament.html',
					'children'	=> [
						[
							'title'	=> '首页数据列表 (admin/Cms.Frament/index)',
							'access'	=> (string)url('admin/Cms.Frament/index'),
						],
						[
							'title'	=> '添加碎片 (admin/Cms.Frament/add)',
							'access'	=> (string)url('admin/Cms.Frament/add'),
						],
						[
							'title'	=> '修改碎片 (admin/Cms.Frament/update)',
							'access'	=> (string)url('admin/Cms.Frament/update'),
						],
						[
							'title'	=> '删除碎片 (admin/Cms.Frament/delete)',
							'access'	=> (string)url('admin/Cms.Frament/delete'),
						]
					],
				],
				[
					'title' => '推荐位管理(admin/Cms.Position)',
					'access' => '/admin/cms/position.html',
					'children'	=> [
						[
							'title'	=> '首页数据列表 (admin/Cms.Position/index)',
							'access'	=> (string)url('admin/Cms.Position/index'),
						],
						[
							'title'	=> '添加推荐位 (admin/Cms.Position/add)',
							'access'	=> (string)url('admin/Cms.Position/add'),
						],
						[
							'title'	=> '修改推荐位 (admin/Cms.Position/update)',
							'access'	=> (string)url('admin/Cms.Position/update'),
						],
						[
							'title'	=> '删除推荐位 (admin/Cms.Position/delete)',
							'access'	=> (string)url('admin/Cms.Position/delete'),
						]
					],
				]
			],
		],
];