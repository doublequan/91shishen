<?php
/**
 * 账号安全
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-28
 */
require_once 'common.php';
class Safety extends Common{
	
	//首页
	public function index(){
	    //用户信息
	    $userInfo = $this->session->userdata('userinfo');
	    $data = $this->Base_model->getRow("SELECT mobile_status,email_status FROM users WHERE id = {$userInfo['uid']}");
		$this->load->view('member/safety_index', array('data'=>$data,'currMenu'=>'member_safety'));
	}
	
	//修改密码
	public function passwd(){
		$this->load->view('member/safety_passwd', array('currMenu'=>'member_safety'));
	}
	
	//验证邮箱
	public function email(){
		$userInfo = $this->session->userdata('userinfo');
		$data = $this->Base_model->getRow("SELECT email_status,email FROM users WHERE id = {$userInfo['uid']}");
		$this->load->view('member/safety_email', array('data'=>$data,'currMenu'=>'member_safety'));
	}
	
	//验证手机
	public function mobile(){
	    $userInfo = $this->session->userdata('userinfo');
	    $data = $this->Base_model->getRow("SELECT mobile_status,mobile FROM users WHERE id = {$userInfo['uid']}");
	    $this->load->view('member/safety_mobile', array('data'=>$data, 'currMenu'=>'member_safety'));
	}
	
	//更换邮箱验证
	public function verify($email, $verify){
		//接收数据
	    $this->load->helper('verify');
	    isEmail($email) && isEmailVerify($verify) || show_404();
	    //验证数据
	    $curr_time = time();
	    $userInfo = $this->session->userdata('userinfo');
	    $data = $this->Base_model->getRow("SELECT verify,create_time FROM logs_email WHERE email = '{$email}' ORDER BY id DESC LIMIT 1");
	    if(empty($data['verify']) || $data['verify'] != $verify){
	    	$this->_redirect('验证链接错误！', base_url('member/safety/email'), false);
	    }elseif($curr_time - $data['create_time'] > 600){
	    	$this->_redirect('验证链接已过期！', base_url('member/safety/email'), false);
	    }else{
	    	//更新用户邮箱
	    	$data = array('email'=>$email, 'email_status'=>1);
	    	$result = $this->Base_model->update('users', $data, array('id'=>$userInfo['uid']));
	    	$result = $result == false ? false : true;
	    	$this->_redirect('验证邮箱成功！', base_url('member/safety/index'), $result);
	    }
	}
	
	//更换手机验证
	public function ajax_verify(){
	    //仅限Ajax访问
	    $this->input->is_ajax_request() || show_404();
	    //默认信息
	    $err['err_no'] = 1000;
	    $err['err_msg'] = parent::$errorType[1000];
	    //接收数据
	    $mobile = trim($this->input->post('mobile', true));
	    $verify = trim($this->input->post('verify', true));
	    $this->load->helper('verify');
	    if( ! isMobile($mobile) || ! isMobileVerify($verify)){
	        $err['err_no'] = 1002;
	        $err['err_msg'] = parent::$errorType[1002];
	        exit(json_encode($err));
	    }
	    //验证数据
	    $curr_time = time();
	    $userInfo = $this->session->userdata('userinfo');
	    $data = $this->Base_model->getRow("SELECT verify,create_time FROM logs_sms WHERE mobile = '{$mobile}' ORDER BY id DESC LIMIT 1");
	    if(empty($data['verify']) || ($data['verify'] != $verify) || ($curr_time - $data['create_time'] > 600)){
	        $err['err_no'] = 1003;
	        $err['err_msg'] = parent::$errorType[1003];
	        exit(json_encode($err));
	    }
	    //更新用户手机
	    $data = array('mobile'=>$mobile, 'mobile_status'=>1);
	    $result = $this->Base_model->update('users', $data, array('id'=>$userInfo['uid']));
	    if(false != $result){
	        $err['err_no'] = 0;
	        $err['err_msg'] = parent::$errorType[0];
	    }
	    exit(json_encode($err));
	}
	
	//发送验证邮件
	public function ajax_email(){
	    //仅限Ajax访问
	    $this->input->is_ajax_request() || show_404();
	    //默认信息
	    $err['err_no'] = 1000;
	    $err['err_msg'] = parent::$errorType[1000];
	    //接收数据
	    $email = trim($this->input->post('email', true));
	    //格式验证
	    $this->load->helper('verify');
	    if( ! isEmail($email)){
	        $err['err_no'] = 1002;
	        $err['err_msg'] = parent::$errorType[1002];
	        exit(json_encode($err));
	    }
	    //当前时间
	    $curr_time = time();
	    //检查时间间隔
	    $create_time = $this->Base_model->getOne("SELECT create_time FROM logs_email WHERE email = '{$email}' ORDER BY id DESC LIMIT 1");
	    if( ! empty($create_time) && ($curr_time - $create_time <= 60)){
	    	$err['err_no'] = 1003;
	    	$err['err_msg'] = parent::$errorType[1003];
	    	$err['results'] = $create_time - $curr_time + 60;
	    	exit(json_encode($err));
	    }
	    //发送验证邮件
	    $verify = md5(getRandStr().$curr_time);
	    $subject = '[惠生活]验证邮件';
	    $message = '您正在验证邮箱，请点击下面的链接进行验证！<br /><a href="'.base_url("member/safety/verify/{$email}/{$verify}").'" target="_blank">'.base_url("member/safety/verify/{$email}/{$verify}").'</a>';
	    if(true == $this->_sendEmail($email, $subject, $message)){
	        //更新记录
	        $logs['email'] = $email;
	        $logs['verify'] = $verify;
	        $logs['subject'] = $subject;
	        $logs['content'] = $message;
	        $logs['create_ip'] = getUserIP();
	        $logs['create_time'] = $curr_time;
	        $this->Base_model->insert('logs_email', $logs);
	        //提示信息
	        $err['err_no'] = 0;
	        $err['err_msg'] = parent::$errorType[0];
	    }
	    exit(json_encode($err));
	}
	
	//发送验证短信
	public function ajax_mobile(){
	    //仅限Ajax访问
	    $this->input->is_ajax_request() || show_404();
	    //默认信息
	    $err['err_no'] = 1000;
	    $err['err_msg'] = parent::$errorType[1000];
	    //接收数据
	    $mobile = trim($this->input->post('mobile', true));
	    //格式验证
	    $this->load->helper('verify');
	    if( ! isMobile($mobile)){
	        $err['err_no'] = 1002;
	        $err['err_msg'] = parent::$errorType[1002];
	        exit(json_encode($err));
	    }
	    //当前时间
	    $curr_time = time();
	    //检查时间间隔
	    $create_time = $this->Base_model->getOne("SELECT create_time FROM logs_sms WHERE mobile = '{$mobile}' ORDER BY id DESC LIMIT 1");
	    if( ! empty($create_time) && ($curr_time - $create_time <= 60)){
	        $err['err_no'] = 1003;
	        $err['err_msg'] = parent::$errorType[1003];
	        $err['results'] = $create_time - $curr_time + 60;
	        exit(json_encode($err));
	    }
	    //发送短信验证码
	    $verify = mt_rand(100000, 999999); //6位数字
	    if(true == Common_Sms::send($mobile, $verify)){
	        //更新记录
	        $logs['mobile'] = $mobile;
	        $logs['verify'] = $verify;
	        $logs['content'] = $verify;
	        $logs['create_ip'] = getUserIP();
	        $logs['create_time'] = $curr_time;
	        $this->Base_model->insert('logs_sms', $logs);
	        //提示信息
	        $err['err_no'] = 0;
	        $err['err_msg'] = parent::$errorType[0];
	    }
	    exit(json_encode($err));
	}
	
	//执行修改密码
	public function ajax_passwd(){
	    //仅限Ajax访问
	    $this->input->is_ajax_request() || show_404();
	    //用户信息
	    $userInfo = $this->session->userdata('userinfo');
	    //默认信息
	    $err['err_no'] = 1000;
	    $err['err_msg'] = parent::$errorType[1000];
	    //接收数据
	    $old_pass = trim($this->input->post('old_pass', true));
	    $password = trim($this->input->post('password', true));
	    //格式验证
	    $this->load->helper('verify');
	    if( ! isPassword($password)){
	        $err['err_no'] = 1002;
	        $err['err_msg'] = parent::$errorType[1002];
	        exit(json_encode($err));
	    }
	    //验证旧密码
	    $user = $this->Base_model->getRow("SELECT pass,salt FROM users WHERE id = {$userInfo['uid']}");
	    //if($user['pass'] != encryptPass($old_pass, $user['salt'])){
	    if($user['pass'] != md5($old_pass)){
	        $err['err_no'] = 1003;
	        $err['err_msg'] = parent::$errorType[1003];
	        exit(json_encode($err));
	    }
	    $data['salt'] = getRandStr(10);
	    $data['pass'] = md5($password);
	    //$data['pass'] = encryptPass($password, $data['salt']);
	    $result = $this->Base_model->update('users', $data, array('id'=>$userInfo['uid']));
	    if(false != $result){
	        //提示信息
	        $err['err_no'] = 0;
	        $err['err_msg'] = parent::$errorType[0];
	    }
	    exit(json_encode($err));
	}
}