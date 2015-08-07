<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/base.php';

class my extends Base 
{
	
	private $active_top_tag = 'system';
	
	public function __construct(){
		parent::__construct();
	}
	
	public function info() {
		$data = array();
		//get current employee
		$data['single'] = $this->mBase->getSingle('employees','id',parent::$user['id']);
		// get company 
		$data['company'] = $this->mBase->getSingle('companys','id',parent::$user['company_id']);
		// get dept
		$data['department'] = $this->mBase->getSingle('departments','id',parent::$user['dept_id']);
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'my_info';
		$this->_view('common/header', $tags);
		$this->_view('my/info_detail', $data);
		$this->_view('common/footer');
	}
	
	public function edit() {
		$data = array();
		//get current employee
		$data['single'] = $this->mBase->getSingle('employees','id',parent::$user['id']);
		// get company
		$data['company'] = $this->mBase->getSingle('companys','id',parent::$user['company_id']);
		// get dept
		$data['department'] = $this->mBase->getSingle('departments','id',parent::$user['dept_id']);
		//display templates
		$this->_view('my/info_edit', $data);
	}
	
	public function updateInfo() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('mobile','email','birthday');
			$fields = array('oldpass','pass','repass','address');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			//check parameters			
			if( !preg_match('/^1[0-9]{10}$/',$params['mobile']) ){
        		$ret = array('err_no'=>1000,'err_msg'=>'手机号码格式错误');
        		break;
        	}
        	if( !filter_var($params['email'],FILTER_VALIDATE_EMAIL) ){
        		$ret = array('err_no'=>1000,'err_msg'=>'邮箱格式错误');
        		break;
        	}
			//update data
			$data = array(
				'birthday'	=> date('Y-m-d',strtotime($params['birthday'])),
				'address'	=> $params['address'],
				'mobile'	=> $params['mobile'],
				'email'		=> $params['email'],
			);
			if( $params['oldpass'] ){
				if( strlen($params['pass'])<6 ){
					$ret = array('err_no'=>1000,'err_msg'=>'新密码不能少于6位');
					break;
				}
				if( $params['pass']!=$params['repass'] ){
					$ret = array('err_no'=>1000,'err_msg'=>'两次输入的密码不一致');
					break;
				}
				if( encryptPass($params['oldpass'], parent::$user['salt'])!=parent::$user['pass'] ){
					$ret = array('err_no'=>1000,'err_msg'=>'老密码不正确');
					break;
				}
				$salt = getRandStr(10);
				$pass = encryptPass($params['pass'], $salt);
				$data['pass'] = $pass;
				$data['salt'] = $salt;
			}
			$this->mBase->update('employees',$data,array('id'=>parent::$user['id']));
			$is_logout = false;
			if( isset($data['pass']) ){
				parent::$user = null;
				unset($_SESSION[USER_SESSION_NAME]);
				setcookie(USER_COOKIE_NAME, '', time()-3600, "/");
				$is_logout = true;
			}
			$ret = array('err_no'=>0,'err_msg'=>'操作成功','is_logout'=>$is_logout);
		} while(0);
		$this->output($ret);
	}
}
