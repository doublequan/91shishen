<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class employee extends Base 
{
	private $active_top_tag = 'enterprise';
	
	//Common Companys
	private $companys = array();
	
	//Common Departments
	private $depts = array();
	
	//Common Departments Map
	private $deptMap = array();
	
	//Common Roles
	private $roles = array();

	public function __construct(){
		parent::__construct();
		$res = $this->mBase->getList('companys');
		if( $res ){
			foreach ( $res as $row ){
				$this->companys[$row['id']] = $row;
			}
		}
		$res = $this->mBase->getList('departments');
		if( $res ){
			foreach ( $res as $row ){
				$this->depts[$row['id']] = $row;
				$this->deptMap[$row['company_id']][$row['id']] = $row;
			}
		}
		$res = $this->mBase->getList('roles');
		if( $res ){
			foreach ( $res as $row ){
				$this->roles[$row['id']] = $row;
			}
		}
	}
	
	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('company_id','dept_id','username','page','size');
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$company_id = intval($params['company_id']);
		$dept_id = intval($params['dept_id']);
		$username = $params['username'];
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		//get list
		$condition = array(
			'AND' => array('is_del=0'),
		);
		if( $company_id ){
			$condition['AND'][] = 'company_id='.$company_id;
		}
		if( $dept_id ){
			$condition['AND'][] = 'dept_id='.$dept_id;
		}
		if( $username ){
			$condition['AND'][] = "username LIKE '%{$username}%'";
		}
		$res = $this->mBase->getList('employees',$condition,'*','id DESC',$page,$size);
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//common data
		$data['companys'] = $this->companys;
		$data['depts'] = $this->depts;
		$data['deptMap'] = isset($this->deptMap[$company_id]) ? $this->deptMap[$company_id] : array();
		//display templates 
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'employee';
		$this->_view('common/header', $tags);
		$this->_view('employee/employee_list', $data);
		$this->_view('common/footer');
	}
	
	public function add() {
		$data = array();
		//common data
		$data['companys'] = $this->companys;
		$data['deptMap'] = $this->deptMap;
		$data['roles'] = $this->roles;
		//display templates
		$this->_view('employee/employee_add', $data);
	}
	
	public function edit() {
		$data = array();
		//get single group
		$id = intval($this->input->get('id'));
		$data['single'] = $this->mBase->getSingle('employees','id',$id);
		if( !$data['single'] ){
			$this->showMsg(1000,'员工不存在',$this->active_top_tag,'employee');
			return;
		}
		//get roles
		$data['roles'] = $this->roles;
		//common data
		$data['companys'] = $this->companys;
		$data['deptMap'] = $this->deptMap;
		//display templates
		$this->_view('employee/employee_edit', $data);
	}
	
	public function doAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('account','username','pass','repass','company_id','dept_id','gender','idcard','mobile','email','role_ids');
			$fields = array('birthday','hire_date','expire_date','quit_date','address','mobile','email');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$params['gender'] = intval($params['gender']);
			//check parameters
			if( !preg_match('/^[a-z]{3,18}[0-9]{0,2}$/',$params['account']) ){
        		$ret = array('err_no'=>1000,'err_msg'=>'员工登录账号格式错误，应为姓姓名全拼或者后面加数字');
        		break;
        	}
			$t = $this->mBase->getSingle('employees','account',$params['account']);
			if( $t ){
				$ret = array('err_no'=>1000,'err_msg'=>'员工登录账号已经存在');
				break;
			}
			if( !preg_match("/^[\x{4e00}-\x{9fa5}]{2,20}$/u", $params['username']) ){
				$ret = array('err_no'=>1000,'err_msg'=>'员工姓名格式错误，仅支持中文');
				break;
			}
			if( strlen($params['pass'])<6 ){
				$ret = array('err_no'=>1000,'err_msg'=>'登陆密码不能少于6位');
				break;
			}
			if( $params['pass']!=$params['repass'] ){
				$ret = array('err_no'=>1000,'err_msg'=>'两次输入的密码不一致');
				break;
			}
			$params['company_id'] = intval($params['company_id']);
			if( !$params['company_id'] ){
				$ret = array('err_no'=>1000,'err_msg'=>'请选择员工所属公司');
				break;
			}
			$params['dept_id'] = intval($params['dept_id']);
			if( !$params['dept_id'] ){
				$ret = array('err_no'=>1000,'err_msg'=>'请选择员工所属部门');
				break;
			}
			$len = strlen($params['idcard']);
			if( !( $len==15 || $len==18 ) ){
				$ret = array('err_no'=>1000,'err_msg'=>'身份证号码格式错误，应为15或18位');
				break;
			}
			if( !preg_match('/^1[0-9]{10}$/',$params['mobile']) ){
				$ret = array('err_no'=>1000,'err_msg'=>'手机号码格式错误');
				break;
			}
			if( !filter_var($params['email'],FILTER_VALIDATE_EMAIL) ){
				$ret = array('err_no'=>1000,'err_msg'=>'邮箱格式错误');
				break;
			}
			if( !$params['role_ids'] ){
				$ret = array('err_no'=>1000,'err_msg'=>'请选择员工拥有的权限角色');
				break;
			}
			$pass = $params['pass'];
			$salt = getRandStr(10);
			$pass = encryptPass($pass, $salt);
			//insert data
			$data = array(
				'account'		=> $params['account'],
				'invite_code'	=> strtoupper(substr(md5($params['account']), 0, 10)),
				'username'		=> $params['username'],
				'pass'			=> $pass,
				'salt'			=> $salt,
				'company_id'	=> $params['company_id'],
				'dept_id'		=> $params['dept_id'],
				'gender'		=> $params['gender'],
				'idcard'		=> $params['idcard'],
				'roles'			=> implode(',',$params['role_ids']),
				'birthday'		=> date('Y-m-d',strtotime($params['birthday'])),
				'hire_date'		=> date('Y-m-d',strtotime($params['hire_date'])),
				'expire_date'	=> date('Y-m-d',strtotime($params['expire_date'])),
				'quit_date'		=> date('Y-m-d',strtotime($params['quit_date'])),
				'address'		=> $params['address'],
				'mobile'		=> $params['mobile'],
				'email'			=> $params['email'],
				//'create_ip'		=> getUserIP(),
				'create_time'	=> time(),
			);
			$this->mBase->insert('employees',$data);
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function doEdit() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('id','username','company_id','dept_id','gender','idcard','mobile','email','role_ids');
			$fields = array('pass','repass','birthday','hire_date','expire_date','quit_date','address');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			//check parameters
			$id = intval($params['id']);
			$employee = $this->mBase->getSingle('employees','id',$id);
			if( !$employee ){
				$ret = array('err_no'=>1000,'err_msg'=>'员工不存在');
				break;
			}
			if( !preg_match("/^[\x{4e00}-\x{9fa5}]{2,20}$/u", $params['username']) ){
				$ret = array('err_no'=>1000,'err_msg'=>'员工姓名格式错误，仅支持中文');
				break;
			}
			$params['company_id'] = intval($params['company_id']);
			if( !$params['company_id'] ){
				$ret = array('err_no'=>1000,'err_msg'=>'请选择员工所属公司');
				break;
			}
			$params['dept_id'] = intval($params['dept_id']);
			if( !$params['dept_id'] ){
				$ret = array('err_no'=>1000,'err_msg'=>'请选择员工所属部门');
				break;
			}
			$params['gender'] = intval($params['gender']);
			$len = strlen($params['idcard']);
			if( !( $len==15 || $len==18 ) ){
				$ret = array('err_no'=>1000,'err_msg'=>'身份证号码格式错误，应为15或18位');
				break;
			}
			if( !preg_match('/^1[0-9]{10}$/',$params['mobile']) ){
				$ret = array('err_no'=>1000,'err_msg'=>'手机号码格式错误');
				break;
			}
			if( !filter_var($params['email'],FILTER_VALIDATE_EMAIL) ){
				$ret = array('err_no'=>1000,'err_msg'=>'邮箱格式错误');
				break;
			}
			if( !$params['role_ids'] ){
				$ret = array('err_no'=>1000,'err_msg'=>'请选择员工拥有的权限角色');
				break;
			}
			//update data
			$data = array(
				'username'		=> $params['username'],
				'company_id'	=> $params['company_id'],
				'dept_id'		=> $params['dept_id'],
				'gender'		=> $params['gender'],
				'idcard'		=> $params['idcard'],
				'roles'			=> implode(',',$params['role_ids']),
				'birthday'		=> date('Y-m-d',strtotime($params['birthday'])),
				'hire_date'		=> date('Y-m-d',strtotime($params['hire_date'])),
				'expire_date'	=> date('Y-m-d',strtotime($params['expire_date'])),
				'quit_date'		=> date('Y-m-d',strtotime($params['quit_date'])),
				'address'		=> $params['address'],
				'mobile'		=> $params['mobile'],
				'email'			=> $params['email'],
			);
			if( $params['pass'] ){
				if( strlen($params['pass'])<6 ){
					$ret = array('err_no'=>1000,'err_msg'=>'新密码不能少于6位');
					break;
				}
				if( $params['pass']!=$params['repass'] ){
					$ret = array('err_no'=>1000,'err_msg'=>'两次输入的密码不一致');
					break;
				}
				$salt = getRandStr(10);
				$pass = encryptPass($params['pass'], $salt);
				$data['pass'] = $pass;
				$data['salt'] = $salt;
			}
			$this->mBase->update('employees',$data,array('id'=>$id));
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	/**
	 * search employees by keyword, return max 20
	 */
	public function search(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('company_id','dept_id','keyword');
		$this->checkParams('get',$must,$fields);
		$params = $this->params;
		$company_id = intval($params['company_id']);
		$dept_id = intval($params['dept_id']);
		$keyword = $params['keyword'];
		//get employee data
		$condition = array(
			'AND' => array('is_del=0'),
		);
		if( $company_id ){
			$condition['AND'][] = 'company_id='.$company_id;
		}
		if( $dept_id ){
			$condition['AND'][] = 'dept_id='.$dept_id;
		}
		if( $keyword ){
			$condition['OR'][] = "account LIKE '%".$keyword."%'";
			$condition['OR'][] = "username LIKE '%".$keyword."%'";
			$condition['OR'][] = "email LIKE '%".$keyword."%'";
			$condition['OR'][] = "mobile LIKE '%".$keyword."%'";
		}
		$ret = $this->mBase->getList('employees',$condition,'id,username,account,email,mobile','id DESC');
		echo json_encode($ret);
	}

	public function select_dialog() {
		$data = array();
		//init parameters
		$must = array();
		$fields = array('company_id','dept_id','username','page','size');
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$company_id = intval($params['company_id']);
		$dept_id = intval($params['dept_id']);
		$username = $params['username'];
		$page = max(1,intval($params['page']));
		$size = max(10,min(100,intval($params['size'])));
		//get employee data
		$condition = array(
			'AND' => array('is_del=0'),
		);
		if( $company_id ){
			$condition['AND'][] = 'company_id='.$company_id;
		}
		if( $dept_id ){
			$condition['AND'][] = 'dept_id='.$dept_id;
		}
		if( $username ){
			$condition['AND'][] = "username LIKE '%{$username}%'";
		}
		$res = $this->mBase->getList('employees',$condition,'*','id DESC',$page,$size);
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//common data
		$data['companys'] = $this->companys;
		$data['depts'] = $this->depts;
		$data['deptMap'] = isset($this->deptMap[$company_id]) ? $this->deptMap[$company_id] : array();
		//display templates
		$this->_view('employee/select_dialog', $data);
	}

	public function delete(){
		$row_id = $this->input->post('row_id');
		if(empty($row_id)){
			die('{"error":1, "msg": "信息错误，请刷新页面重试！"}');
		}
		$delete_rst = $this->mBase->update('employees', array('is_del'=>1),array('id' => $row_id));
		die('{"error":0, "msg": ""}');
	}
}
