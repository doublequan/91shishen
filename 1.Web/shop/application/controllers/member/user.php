<?php
/**
 * 注册/登录/退出
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-11
 */
require_once APPPATH.'controllers/base.php';
class User extends Base{
	
	//登录
	public function login(){
		//已登录则跳转
		if(false != $this->session->userdata('userinfo')){
			redirect(base_url('member/home/index'));
		}
		//登录后跳转
		$referer = trim($this->input->get('referer', true));
		$referer = str_replace(array('[removed]', '"', '\'', '\\'), '', $referer);
		if(empty($referer) || strpos($referer, base_url()) !== 0){
		     $referer = base_url('member/home/index');
		}
		$this->load->view('member/user_login', array('referer'=>$referer));
	}
	
	//注册
	public function register(){
		//已登录则跳转
		if(false != $this->session->userdata('userinfo')){
			redirect(base_url('member/home/index'));
		}
		$this->load->view('member/user_register');
	}
	
	//退出
	public function logout(){
		//删除SESSION
		if(false != $this->session->userdata('userinfo')){
			$this->session->unset_userdata('userinfo');
		}
		//删除Cookie
		$this->input->set_cookie('REMPWD', '', '');
		//页面跳转
		redirect(base_url('member/user/login'));
	}
	
	//提交登录
	public function ajax_login(){
		//仅限Ajax访问
		$this->input->is_ajax_request() || show_404();
		//默认信息
		$err['err_no'] = 1000;
		$err['err_msg'] = parent::$errorType[1000];
		//已登录则返回
		if(false != $this->session->userdata('userinfo')){
			exit(json_encode($err));
		}
		//接收数据
		$data['username'] = trim($this->input->post('username', true));
		$data['pass'] = trim($this->input->post('password', true));
		$rempwd = intval($this->input->post('rempwd')); //记住密码
// 		$data['captcha'] = trim($this->input->post('captcha', true));
// 		//验证码
// 		$captcha = $this->session->userdata('captcha');
// 		if(empty($captcha) || strtolower($captcha) != strtolower($data['captcha'])){
// 			$err['err_no'] = 1001;
// 			$err['err_msg'] = parent::$errorType[1001];
// 			exit(json_encode($err));
// 		}
		//账号检测
		$this->load->model('User_model');
		if(true == $this->User_model->checkPassword($data['username'], $data['pass'])){
		    //检查账号状态
			$uid = $this->User_model->getUid($data['username']);
			$username = $this->User_model->getUsername($uid);
		    $status = $this->User_model->getStatus($uid);
		    if($status != 1){
		        $err['err_no'] = 1010;
		        $err['err_msg'] = parent::$errorType[1010];
		        exit(json_encode($err));
		    }
			//更新会员信息
			$info = array('login_time'=>time(), 'login_ip'=>getUserIP());
			$this->Base_model->update('users', $info, array('id'=>$uid));
			//设置SESSION
			$userInfo = array('uid'=>$uid, 'username'=>$username);
			$this->session->set_userdata('userinfo', $userInfo);
			//设置COOKIE
			if($rempwd == 1){
			    $rempwd_cookie['p'] = md5(md5($data['pass']).REMPWD_SALT);
			    $rempwd_cookie['u'] = $data['username'];
			    //有效期30天
			    $this->input->set_cookie('REMPWD', serialize($rempwd_cookie), '2592000');
			}
 			//删除验证码
// 			$this->session->unset_userdata('captcha');
			//更新购物车
			$cart = json_decode($this->input->cookie('cart', true), true);
			if(empty($cart)){
				$this->Cart_model->syncToCart($uid);
			}else{
				$this->Cart_model->syncToDb($uid, $cart);
			}
			//提示信息
			$err['err_no'] = 0;
			$err['err_msg'] = parent::$errorType[0];
		}else{
			//记录日志
			$login_log['account'] = $data['username'];
			$login_log['pass'] = $data['pass'];
			$login_log['login_ip'] = getUserIP();
			$login_log['login_time'] = time();
			$this->Base_model->insert('users_log_login', $login_log);
			//提示信息
			$err['err_no'] = 1003;
			$err['err_msg'] = parent::$errorType[1003];
		}
		exit(json_encode($err));
	}
	
	//提交注册
	public function ajax_reg(){
		//仅限Ajax访问
		$this->input->is_ajax_request() || show_404();
		//默认信息
		$err['err_no'] = 1000;
		$err['err_msg'] = parent::$errorType[1000];
		//已登录则返回
		if(false != $this->session->userdata('userinfo')){
			exit(json_encode($err));
		}
		//接收数据
		$data['username'] = trim($this->input->post('username', true));
		$data['pass'] = trim($this->input->post('password', true));
		$data['mobile'] = trim($this->input->post('mobile', true));
		$data['email'] = trim($this->input->post('email', true));
		$data['cardno'] = trim($this->input->post('cardno', true));
		
// 		//验证码
// 		$captcha = $this->session->userdata('captcha');
// 		if(empty($captcha) || strtolower($captcha) != strtolower($data['captcha'])){
// 			$err['err_no'] = 1001;
// 			$err['err_msg'] = parent::$errorType[1001];
// 			exit(json_encode($err));
// 		}
// 		unset($data['captcha']);

		//格式验证
		$this->load->helper('verify');
		if( ! (
			isUsername($data['username']) && isPassword($data['pass'])
		    && (empty($data['mobile']) || isMobile($data['mobile']))
		    && (empty($data['email']) || isEmail($data['email']))
		    && (empty($data['cardno']) || isCard($data['cardno']))
		)){
			$err['err_no'] = 1002;
			$err['err_msg'] = parent::$errorType[1002];
			exit(json_encode($err));
		}
		//用户名唯一性
		$this->load->model('User_model');
		if(true == $this->User_model->checkUsername($data['username'])){
			$err['err_no'] = 1004;
			$err['err_msg'] = parent::$errorType[1004];
			$err['results'] = '用户名已存在！';
			exit(json_encode($err));
		}
		//手机号唯一性
		if( ! empty($data['mobile']) && true == $this->User_model->checkMobile($data['mobile'])){
			$err['err_no'] = 1004;
			$err['err_msg'] = parent::$errorType[1004];
			$err['results'] = '手机号已存在！';
			exit(json_encode($err));
		}
		//邮箱唯一性
		if( ! empty($data['email']) && true == $this->User_model->checkEmail($data['email'])){
			$err['err_no'] = 1004;
			$err['err_msg'] = parent::$errorType[1004];
			$err['results'] = '邮箱已存在！';
			exit(json_encode($err));
		}
		//处理数据
		$data['salt'] = getRandStr(10);
		//$data['pass'] = encryptPass($data['pass'], $data['salt']);
		$data['pass'] = md5($data['pass']);
		$data['status'] = 1; //正常
		$data['mobile_status'] = 0; //未验证
		$data['email_status'] = 0; //未验证
		$data['login_ip'] = $data['create_ip'] = getUserIP();
		$data['login_time'] = $data['create_time'] = time();
		//执行添加
		$result = $this->Base_model->insert('users', $data);
		if(false != $result){
			$uid = intval($result['id']);
			//新用户注册赠送5元代金券
			$couponData = array(
				'uid'			=> $uid,
				'coupon_type'	=> 1,
				'coupon_code'	=> strtoupper(getRandStr(5).substr(md5($uid), 0, 5)),
				'coupon_limit'	=> 0,
				'coupon_total'	=> 5,
				'coupon_balance'=> 5,
				'start'			=> 0,
				'end'			=> 0,
				'status'		=> 1,
				'create_eid'	=> 0,
				'create_name'	=> '新用户注册赠送',
				'create_time'	=> time(),
			);
			$this->Base_model->insert('users_coupon', $couponData);
			//绑定cardno
			if( $data['cardno'] ){
				$card = $this->Base_model->getSingle('users_card_pingan','cardno',$data['cardno']);
				if( $card && $card['is_bind']==0 ){
					$couponData = array(
						'uid'			=> $uid,
						'coupon_type'	=> 1,
						'coupon_code'	=> strtoupper(getRandStr(5).substr(md5($uid), 0, 5)),
						'coupon_limit'	=> 0,
						'coupon_total'	=> 5,
						'coupon_balance'=> 5,
						'start'			=> 0,
						'end'			=> 0,
						'status'		=> 1,
						'create_eid'	=> 0,
						'create_name'	=> '平安合作卡赠送',
						'create_time'	=> time(),
					);
					$t = $this->Base_model->insert('users_coupon', $couponData);
					if( $t ){
						$updateData = array(
							'uid'		=> $uid,
							'is_bind'	=> 1,
							'bind_time'	=> time(),
						);
						$this->Base_model->update('users_card_pingan',$updateData,array('cardno'=>$data['cardno']));
					}
				}
			}
			//设置SESSION
			$userInfo = array('uid'=>$result['id'], 'username'=>$result['username']);
			$this->session->set_userdata('userinfo', $userInfo);
			//更新购物车
			$cart = json_decode($this->input->cookie('cart', true), true);
			if( ! empty($cart)){
				$this->Cart_model->syncToDb($result['id'], $cart);
			}
			//提示信息
			$err['err_no'] = 0;
			$err['err_msg'] = parent::$errorType[0];
		}
		exit(json_encode($err));
	}
}