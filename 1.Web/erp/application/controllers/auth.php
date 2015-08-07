<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class auth extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Employee_model', 'mEmployee');
	}
	
	public function login() {
		$data = array();
		$data['backurl'] = $this->input->get('backurl');
		$this->load->view('auth/login', $data);
	}
    
    public function doLogin(){
    	$ret = array('err_no' => 1000,'err_msg' => 'system error');
    	do{
    		//get parameters
    		$arr = array('account','pass','verify');
    		foreach ( $arr as $v ){
    			$params[$v] = $this->input->post($v);
    		}
    		//check parameters
    		require_once ROOT_PATH . 'verify/seccodeProxy.class.php';
    		if( !seccodeProxy::check('erp_verify',$params['verify'],false) ){
    			$ret = array('err_no'=>1000,'err_msg'=>'验证码错误');
    			break;
    		}
    		if( empty($params['account']) ){
    			$ret = array('err_no'=>1000,'err_msg'=>'登录账号不能为空');
    			break;
    		}
    		if( empty($params['pass']) ){
    			$ret = array('err_no'=>1000,'err_msg'=>'登录密码不能为空');
    			break;
    		}
    		$condition = array(
    			'AND' => array(
    				'login_ip='.getUserIP(),
    				'login_time>='.(time()-600),
    			),
    		);
    		$num = $this->mEmployee->getCount('employees_log_login',$condition);
    		if( $num>=3 ){
    			$ret = array('err_no'=>1000,'err_msg'=>'登录失败次数过多，请10分钟后再重试');
    			break;
    		}
    		$employee = $this->mEmployee->getSingle('employees','account',$params['account']);
    		if( !(isset($employee['pass']) && $employee['pass']==encryptPass($params['pass'],$employee['salt'])) ){
    			$data = array(
    				'account'		=> $params['account'],
    				'pass'			=> $params['pass'],
    				'login_ip'		=> getUserIP(),
    				'login_time'	=> time(),
    			);
    			$this->mEmployee->insert('employees_log_login',$data);
    			$ret = array('err_no'=>1000,'err_msg'=>'登录失败，账号或密码错误');
    			break;
    		}
    		//set cookie
    		setcookie(USER_COOKIE_NAME,authcode('account='.$params['account'].'&pass='. $employee['pass'],'ENCODE'),time()+USER_COOKIE_TIME,'/');
    		$ret = array('err_no'=>0,'err_msg'=>'Success');
    		$data = array(
    			'login_ip'		=> getUserIP(),
    			'login_time'	=> time(),
    		);
    		$this->mEmployee->update('employees',$data,array('id'=>$employee['id']));
    		//add employee log action
    	} while( false );
    	echo json_encode($ret);
    }
    
    public function logout(){
    	require_once dirname(__FILE__).'/base.php';
    	$base = new Base();
    	$base->destroyUser();
    	unset($_SESSION[USER_SESSION_NAME]);
    	setcookie(USER_COOKIE_NAME, '', time()-3600, "/");
    	redirect('auth/login');
    }
}