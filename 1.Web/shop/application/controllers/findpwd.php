<?php
/**
 * 找回密码
 * @author LiuPF<mail@phpha.com>
 * @date 2014-10-10
 */
require_once 'base.php';
class Findpwd extends Base{
    
    //首页
    public function index(){
        //加密令牌
        $hash_token = md5(getRandStr().time());
        $this->session->set_userdata('hash_token', $hash_token);
        $this->load->view('findpwd_index', array('hash_token'=>$hash_token));
    }
    
    //提交
    public function ajax_submit(){
        //仅限Ajax访问
    	$this->input->is_ajax_request() || show_404();
    	//接收数据
    	$username = trim($this->input->post('username', true));
    	$mobile = trim($this->input->post('mobile', true));
    	$email = trim($this->input->post('email', true));
    	$type = trim($this->input->post('type', true));
    	//默认状态
    	$err['err_no'] = 1000;
    	$err['err_msg'] = parent::$errorType[1000];
    	//检查数据
    	$this->load->helper('verify');
    	if( ! in_array($type, array('mobile','email')) || empty($username)){
    		exit(json_encode($err));
    	}
    	if( ! ($type == 'mobile' && isMobile($mobile) || $type == 'email' && isEmail($email)) ){
    		exit(json_encode($err));
    	}
    	//验证用户名
    	$user = $this->Base_model->getRow("SELECT id,mobile,email,mobile_status,email_status FROM users WHERE username = '{$username}'");
    	if(empty($user['id'])){
    		$err['err_no'] = 1003;
    		$err['err_msg'] = parent::$errorType[1003];
    		exit(json_encode($err));
    	}
    	//验证绑定账号
    	if($type == 'mobile'){
    		//验证手机
    		if($user['mobile'] != $mobile || $user['mobile_status'] != 1){
    			$err['err_no'] = 1003;
    			$err['err_msg'] = parent::$errorType[1003];
    			exit(json_encode($err));
    		}
    	}elseif($type == 'email'){
    		//验证邮箱
    		if($user['email'] != $email || $user['email_status'] != 1){
    			$err['err_no'] = 1003;
    			$err['err_msg'] = parent::$errorType[1003];
    			exit(json_encode($err));
    		}
    	}
    	//发送验证码
    	if($type == 'mobile'){
    		$result = $this->send_sms($mobile);
    		//处理结果
    		if($result['err_no'] == 0){
    			//插入记录
    			$logs['uid'] = $user['id'];
    			$logs['mobile'] = $mobile;
    			$logs['create_time'] = time();
    			$this->Base_model->insert('users_findpass', $logs);
    			//成功信息
    			$err['err_no'] = 0;
    			$err['err_msg'] = parent::$errorType[0];
    		}elseif($result['err_no'] == 1001){
    			$err['err_no'] = 1004;
    			$err['err_msg'] = parent::$errorType[1004];
    			$err['results'] = $result['results'];
    		}
    	}elseif($type == 'email'){
    		$result = $this->send_email($email);
    		//处理结果
    		if($result['err_no'] == 0){
    			//插入记录
    			$logs['uid'] = $user['id'];
    			$logs['email'] = $email;
    			$logs['create_time'] = time();
    			$this->Base_model->insert('users_findpass', $logs);
    			//成功信息
    			$err['err_no'] = 0;
    			$err['err_msg'] = parent::$errorType[0];
    		}elseif($result['err_no'] == 1001){
    			$err['err_no'] = 1004;
    			$err['err_msg'] = parent::$errorType[1004];
    			$err['results'] = $result['results'];
    		}
    	}
    	exit(json_encode($err));
    }
    
    //重置密码
    public function reset($type = ''){
    	in_array($type, array('mobile','email')) || show_404();
    	$username = $this->input->get('username', true);
    	$email = $this->input->get('email', true);
    	$mobile = $this->input->get('mobile', true);
    	if($type == 'email'){
    		//邮箱找回
    		$email = empty($email) ? '' : $email;
    		$this->load->view('findpwd_reset_email', array('email'=>$email,'username'=>$username));
    	}elseif($type == 'mobile'){
    		//手机找回
    		$mobile = empty($mobile) ? '' : $mobile;
    		$this->load->view('findpwd_reset_mobile', array('mobile'=>$mobile,'username'=>$username));
    	}
    }
    
    //修改密码|邮箱
    public function ajax_email(){
    	//仅限Ajax访问
    	$this->input->is_ajax_request() || show_404();
    	//默认信息
    	$err['err_no'] = 1000;
    	$err['err_msg'] = parent::$errorType[1000];
    	//接收数据
    	$username = trim($this->input->post('username', true));
    	$email = trim($this->input->post('email', true));
    	$verify = trim($this->input->post('verify', true));
    	$password = trim($this->input->post('password', true));
    	$this->load->helper('verify');
    	if( ! isEmail($email) || ! isUsername($username) || ! isPassword($password) || empty($verify)){
    		$err['err_no'] = 1002;
    		$err['err_msg'] = parent::$errorType[1002];
    		exit(json_encode($err));
    	}
    	//验证用户名
    	$user = $this->Base_model->getRow("SELECT id,mobile,email,mobile_status,email_status FROM users WHERE username = '{$username}'");
    	if(empty($user['id'])){
    		$err['err_no'] = 1003;
    		$err['err_msg'] = parent::$errorType[1003];
    		exit(json_encode($err));
    	}
    	//验证邮箱
    	if($user['email'] != $email || $user['email_status'] != 1){
    		$err['err_no'] = 1003;
    		$err['err_msg'] = parent::$errorType[1003];
    		exit(json_encode($err));
    	}
    	//验证数据
    	$curr_time = time();
    	$data = $this->Base_model->getRow("SELECT verify,create_time FROM logs_email WHERE email = '{$email}' ORDER BY id DESC LIMIT 1");
    	if(empty($data['verify']) || ($data['verify'] != $verify) || ($curr_time - $data['create_time'] > 600)){
    		$err['err_no'] = 1004;
    		$err['err_msg'] = parent::$errorType[1004];
    		exit(json_encode($err));
    	}
    	//设置密码
    	$result = $this->Base_model->update('users', array('pass'=>md5($password)), array('id'=>$user['id']));
    	if(false != $result){
    		$err['err_no'] = 0;
    		$err['err_msg'] = parent::$errorType[0];
    	}
    	exit(json_encode($err));
    }
    
    //修改密码|手机
    public function ajax_mobile(){
    	//仅限Ajax访问
    	$this->input->is_ajax_request() || show_404();
    	//默认信息
    	$err['err_no'] = 1000;
    	$err['err_msg'] = parent::$errorType[1000];
    	//接收数据
    	$username = trim($this->input->post('username', true));
    	$mobile = trim($this->input->post('mobile', true));
    	$verify = trim($this->input->post('verify', true));
    	$password = trim($this->input->post('password', true));
    	$this->load->helper('verify');
    	if( ! isMobile($mobile) || ! isUsername($username) || ! isPassword($password) || empty($verify)){
    		$err['err_no'] = 1002;
    		$err['err_msg'] = parent::$errorType[1002];
    		exit(json_encode($err));
    	}
    	//验证用户名
    	$user = $this->Base_model->getRow("SELECT id,mobile,email,mobile_status,email_status FROM users WHERE username = '{$username}'");
    	if(empty($user['id'])){
    		$err['err_no'] = 1003;
    		$err['err_msg'] = parent::$errorType[1003];
    		exit(json_encode($err));
    	}
    	//验证邮箱
    	if($user['mobile'] != $mobile || $user['mobile_status'] != 1){
    		$err['err_no'] = 1003;
    		$err['err_msg'] = parent::$errorType[1003];
    		exit(json_encode($err));
    	}
    	//验证数据
    	$curr_time = time();
    	$data = $this->Base_model->getRow("SELECT verify,create_time FROM logs_sms WHERE mobile = '{$mobile}' ORDER BY id DESC LIMIT 1");
    	if(empty($data['verify']) || ($data['verify'] != $verify) || ($curr_time - $data['create_time'] > 600)){
    		$err['err_no'] = 1004;
    		$err['err_msg'] = parent::$errorType[1004];
    		exit(json_encode($err));
    	}
    	//设置密码
    	$result = $this->Base_model->update('users', array('pass'=>md5($password)), array('id'=>$user['id']));
    	if(false != $result){
    		$err['err_no'] = 0;
    		$err['err_msg'] = parent::$errorType[0];
    	}
    	exit(json_encode($err));
    }
    
    //发送验证邮件
    private function send_email($email = ''){
    	//当前时间
    	$curr_time = time();
    	//默认返回
    	$err['err_no'] = 1000;
    	//检查时间间隔
    	$create_time = $this->Base_model->getOne("SELECT create_time FROM logs_email WHERE email = '{$email}' ORDER BY id DESC LIMIT 1");
    	if( ! empty($create_time) && ($curr_time - $create_time <= 60)){
    		$err['err_no'] = 1001;
    		$err['results'] = $create_time + 60 - $curr_time;
    		return $err;
    	}
    	//发送验证邮件
    	$verify = getRandStr(10);
    	$subject = '[惠生活]验证邮件';
    	$message = "您正在通过密保邮箱找回密码，本次的验证码为：{$verify}，有效期10分钟！";
    	if(true == $this->_sendEmail($email, $subject, $message)){
    		//更新记录
    		$logs['email'] = $email;
    		$logs['verify'] = $verify;
    		$logs['subject'] = $subject;
    		$logs['content'] = $message;
    		$logs['create_ip'] = getUserIP();
    		$logs['create_time'] = $curr_time;
    		$this->Base_model->insert('logs_email', $logs);
    		//成功返回
    		$err['err_no'] = 0;
    		return $err;
    	}
    	return $err;
    }
    
    //发送验证短信
    private function send_sms($mobile = ''){
    	//当前时间
    	$curr_time = time();
    	//默认返回
    	$err['err_no'] = 1000;
    	//检查时间间隔
    	$create_time = $this->Base_model->getOne("SELECT create_time FROM logs_sms WHERE mobile = '{$mobile}' ORDER BY id DESC LIMIT 1");
    	if( ! empty($create_time) && ($curr_time - $create_time <= 60)){
    		$err['err_no'] = 1001;
    		$err['results'] = $create_time + 60 - $curr_time;
    		return $err;
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
    		//成功返回
    		$err['err_no'] = 0;
    		return $err;
    	}
    	return $err;
    }
}