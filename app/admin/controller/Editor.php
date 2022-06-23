<?php 
/*
 module:		编辑器控制器
 create_time:	2021-10-13 23:06:36
 author:		
 contact:		
*/

namespace app\admin\controller;
use think\exception\ValidateException;
use app\admin\model\Editor as EditorModel;
use think\facade\Db;

class Editor extends Admin {


	/*
 	* @Description  数据列表
 	*/
	function index(){
		if (!$this->request->isPost()){
			return view('index');
		}else{
			$limit  = $this->request->post('limit', 20, 'intval');
			$page = $this->request->post('page', 1, 'intval');

			$where = [];
			$where['editor_id'] = $this->request->post('editor_id', '', 'serach_in');
			$where['title'] = $this->request->post('title', '', 'serach_in');

			$field = 'editor_id,title,wangeditor,ueditor';

			$res = EditorModel::where(formatWhere($where))->field($field)->order('editor_id desc')->paginate(['list_rows'=>$limit,'page'=>$page])->toArray();

			$data['status'] = 200;
			$data['data'] = $res;
			return json($data);
		}
	}
	/*
 	* @Description  修改排序开关
 	*/
	function updateExt(){
		$postField = 'editor_id,';
		$data = $this->request->only(explode(',',$postField),'post',null);
		if(!$data['editor_id']) throw new ValidateException ('参数错误');
		EditorModel::update($data);
		return json(['status'=>200,'msg'=>'操作成功']);
	}

	/*
 	* @Description  添加
 	*/
	public function add(){
		$postField = 'title,wangeditor,ueditor';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Editor::class);

		try{
			$res = EditorModel::create($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'data'=>$res->editor_id,'msg'=>'添加成功']);
	}


	/*
 	* @Description  修改
 	*/
	public function update(){
		$postField = 'editor_id,title,wangeditor,ueditor';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Editor::class);

		try{
			EditorModel::update($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'msg'=>'修改成功']);
	}


	/*
 	* @Description  修改信息之前查询信息的 勿要删除
 	*/
	function getUpdateInfo(){
		$id =  $this->request->post('editor_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'editor_id,title,wangeditor,ueditor';
		$res = EditorModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}


	/*
 	* @Description  删除
 	*/
	function delete(){
		$idx =  $this->request->post('editor_id', '', 'serach_in');
		if(!$idx) throw new ValidateException ('参数错误');
		EditorModel::destroy(['editor_id'=>explode(',',$idx)],true);
		return json(['status'=>200,'msg'=>'操作成功']);
	}




}

