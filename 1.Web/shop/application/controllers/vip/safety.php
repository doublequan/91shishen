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
		$vipInfo = $this->session->userdata('vipinfo');
		$this->load->view('vip/safety_index', array('currMenu'=>'vip_safety'));
	}
	
	//修改密码
	public function passwd(){
		$this->load->view('vip/safety_passwd', array('currMenu'=>'vip_safety'));
	}
	
	//验证手机
	public function mobile(){
	    $this->load->view('vip/safety_mobile', array('currMenu'=>'vip_safety'));
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
	    $vipInfo = $this->session->userdata('vipinfo');
	    $data = $this->Base_model->getRow("SELECT verify,create_time FROM logs_sms WHERE mobile = '{$mobile}' ORDER BY id DESC LIMIT 1");
	    if(empty($data['verify']) || ($data['verify'] != $verify) || ($curr_time - $data['create_time'] > 600)){
	        $err['err_no'] = 1003;
	        $err['err_msg'] = parent::$errorType[1003];
	        $err['results'] = $data['create_time'] - $curr_time + 60;
	        exit(json_encode($err));
	    }
	    //更新用户手机
	    $data = array('mobile'=>$mobile);
	    $result = $this->Base_model->update('vip_users', $data, array('id'=>$vipInfo['uid']));
	    if(false != $result){
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
	    $vipInfo = $this->session->userdata('vipinfo');
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
	    $user = $this->Base_model->getRow("SELECT pass,salt FROM vip_users WHERE id = {$vipInfo['uid']}");
	    if($user['pass'] != encryptPass($old_pass, $user['salt'])){
	        $err['err_no'] = 1003;
	        $err['err_msg'] = parent::$errorType[1003];
	        exit(json_encode($err));
	    }
	    $data['salt'] = getRandStr(10);
	    $data['pass'] = encryptPass($password, $data['salt']);
	    $result = $this->Base_model->update('vip_users', $data, array('id'=>$vipInfo['uid']));
	    if(false != $result){
	        //提示信息
	        $err['err_no'] = 0;
	        $err['err_msg'] = parent::$errorType[0];
	    }
	    exit(json_encode($err));
	}
}