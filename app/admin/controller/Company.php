<?php 
/*
 module:		商品管理控制器
 create_time:	2021-10-13 23:06:52
 author:		
 contact:		
*/

namespace app\admin\controller;
use think\exception\ValidateException;
use app\admin\model\Company as CompanyModel;
use think\facade\Db;

class Company extends Admin {

    function index(){
        if (!$this->request->isPost()){
			return view('index');
		}else{
            $limit  = $this->request->post('limit', 20, 'intval');
			$page = $this->request->post('page', 1, 'intval');

			// $where = [];
			// $where['company_id'] = $this->request->post('company_id', '', 'serach_in');
			// $where['company_name'] = $this->request->post('company_name', '', 'serach_in');
			$keyword=$this->request->post('company_name', '', 'serach_in');
			// echo $keyword; die;
			$where=['company_name','like','%'.$keyword.'%'];
			// print_r(formatWhere($where)) ; die;
			

			$field = 'company_id,company_name,contacts,phone,description';

			$res = CompanyModel::where([$where])->field($field)->order('company_id asc')->paginate(['list_rows'=>$limit,'page'=>$page])->toArray();

			$data['status'] = 200;
			$data['data'] = $res;
			return json($data);
        }
    }
    /*
 	* @Description  添加
 	*/
	 public function add(){
		$postField = 'company_name,contacts,phone,description';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Company::class);

		try{
			$res = CompanyModel::create($data);
			// if($res->company_id && empty($data['sortid'])){
			// 	 CompanyModel::update(['sortid'=>$res->company_id,'company_id'=>$res->company_id]);
			// }
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'data'=>$res->company_id,'msg'=>'添加成功']);
	}
	/*
 	* @Description  修改
 	*/
    public function update(){
		$postField = 'company_id,company_name,contacts,phone,description';
		$data = $this->request->only(explode(',',$postField),'post',null);

		$this->validate($data,\app\admin\validate\Company::class);

		try{
			CompanyModel::update($data);
		}catch(\Exception $e){
			throw new ValidateException($e->getMessage());
		}
		return json(['status'=>200,'msg'=>'修改成功']);
	}

	/*
 	* @Description  修改信息之前查询信息的 勿要删除
 	*/
	 function getUpdateInfo(){
		$id =  $this->request->post('company_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'company_id,company_name,contacts,phone,description';
		$res = CompanyModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}

	/*
 	* @Description  删除
 	*/
	 function delete(){
		$idx =  $this->request->post('company_id', '', 'serach_in');
		if(!$idx) throw new ValidateException ('参数错误');
		CompanyModel::destroy(['company_id'=>explode(',',$idx)],true);
		return json(['status'=>200,'msg'=>'操作成功']);
	}

	/*
 	* @Description  查看详情
 	*/
	 function detail(){
		$id =  $this->request->post('company_id', '', 'serach_in');
		if(!$id) throw new ValidateException ('参数错误');
		$field = 'company_id,company_name,contacts,phone,description';
		$res = CompanyModel::field($field)->find($id);
		return json(['status'=>200,'data'=>$res]);
	}
}