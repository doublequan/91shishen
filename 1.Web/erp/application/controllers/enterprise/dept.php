<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class dept extends Base 
{
	private $active_top_tag = 'enterprise';
	
	//Common Companys
	private $companys = array();

	public function __construct(){
		parent::__construct();
		$this->active_top_tag = 'enterprise';
		$res = $this->mBase->getList('companys', array('AND' => array('is_del=0')));
		if( $res ){
			foreach ( $res as $row ){
				$this->companys[$row['id']] = $row;
			}
		}
	}
	
	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('company_id');
		$this->checkParams('get',$must,$fields);
		$params = $this->params;

		if(empty($params['company_id']))
			$company_id = current(array_keys($this->companys));
		else
			$company_id = intval($params['company_id']);

		//get departments
		$condition = array(
			'AND' => array('is_del=0'),
		);
		if( $company_id ){
			$condition['AND'][] = 'company_id='.$company_id;
		}
		$res = $this->mBase->getList('departments',$condition,'*','id DESC');

		if(!empty($res)){
			$res = getTreeFromArray($res);
			$data['results'] = $res;
		}
		
		//common data
		$data['companys'] = $this->companys;
		$data['company_id'] = $company_id;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'department';
		$this->_view('common/header', $tags);
		$this->_view('enterprise/dept_list', $data);
		$this->_view('common/footer');
	}
	
	public function add() {
		$data = array();
		//common data
		$data['companys'] = $this->companys;

		//get departments
		$condition = array(
			'AND' => array('is_del=0'),
		);
		$res = $this->mBase->getList('departments',$condition,'id, father_id, company_id, name','create_time ASC');

		if(!empty($res)){
			$res = getTreeFromArray($res);
			$data['department_list'] = $res;
		}
		else{
			$data['department_list'] = array();
		}

		//display templates
		$this->_view('enterprise/dept_add', $data);
	}
	
	public function edit() {
		$data = array();
		//get single group
		$data['single'] = array();
		$id = intval($this->input->get('id'));
		$data['single'] = $this->mBase->getSingle('departments','id',$id);
		//common data
		$data['companys'] = $this->companys;

		//get departments
		$condition = array(
			'AND' => array('is_del=0'),
		);
		$res = $this->mBase->getList('departments',$condition,'id, father_id, company_id, name','create_time ASC');

		if(!empty($res)){
			$res = getTreeFromArray($res);
			$data['department_list'] = $res;
		}
		else{
			$data['department_list'] = array();
		}

		$data['leader'] = $this->mBase->getSingle('employees', 'id', $data['single']['leader']);

		//display templates
		$this->_view('enterprise/dept_edit', $data);
	}
	
	public function doAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('father_id','company_id','name');
			$fields = array('leader');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$father_id = intval($params['father_id']);
			$company_id = intval($params['company_id']);
			//check parameters
			if( $father_id ){
				$t = $this->mBase->getSingle('departments','id',$father_id);
				if( !$t ){
	        		$ret = array('err_no'=>1000,'err_msg'=>'上级部门不存在');
	        		break;
	        	}
			}
			$t = $this->mBase->getSingle('companys','id',$company_id);
			if( !$t ){
				$ret = array('err_no'=>1000,'err_msg'=>'部门所属公司不存在');
				break;
			}
			//insert data
			$data = array(
				'father_id'		=> $father_id,
				'company_id'	=> $company_id,
				'name'			=> $params['name'],
				'leader'		=> intval($params['leader']),
				'create_eid'	=> 1,
				'create_time'	=> time(),
			);
			$this->mBase->insert('departments',$data);
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function doEdit() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('id','father_id','company_id','name');
			$fields = array('leader');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$id = intval($params['id']);
			$father_id = intval($params['father_id']);
			$company_id = intval($params['company_id']);
			//check parameters
			$dept = $this->mBase->getSingle('departments','id',$id);
			if( !$dept ){
				$ret = array('err_no'=>1000,'err_msg'=>'部门不存在');
				break;
			}
			if( $father_id ){
				$t = $this->mBase->getSingle('departments','id',$father_id);
				if( !$t ){
	        		$ret = array('err_no'=>1000,'err_msg'=>'上级部门不存在');
	        		break;
	        	}
			}
			$t = $this->mBase->getSingle('companys','id',$company_id);
			if( !$t ){
				$ret = array('err_no'=>1000,'err_msg'=>'部门所属公司不存在');
				break;
			}
			//insert data
			$data = array(
				'father_id'		=> $father_id,
				'company_id'	=> $company_id,
				'name'			=> $params['name'],
				'leader'		=> intval($params['leader']),
			);
			$this->mBase->update('departments',$data,array('id'=>$id));
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}

	public function getDeptChildren()
	{
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		$company_id = $this->input->post('company_id');
		if(!empty($company_id))
		{
			$father_id = $this->input->post('father_id');
			if(empty($father_id)){
				$father_id = 0;
			}
			$condition['AND'][] = "`company_id`={$company_id}";
			$condition['AND'][] = "`father_id`={$father_id}";
			$info_list = $this->mBase->getList('departments', $condition, '*', '', 0, 100);
			if(count($info_list) == 0){
				$ret = array('err_no'=>100,'err_msg'=>'无下级部门，请添加！');
			}
			else{
				$ret = array('err_no'=>0,'err_msg'=>'操作成功', 'info_list' => $info_list);
			}
		}
		$this->output($ret);
	}



	private function getDeptTree($depts)
	{
		$rst = array();
		foreach ($depts as $key => $value) {
			
		}

	}
}
