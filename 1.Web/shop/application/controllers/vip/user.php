<?php
/**
 * 注册/登录/退出
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-25
 */
require_once APPPATH.'controllers/base.php';
class User extends Base{

	//登录
	public function login(){
		//已登录则跳转
		if(false != $this->session->userdata('vipinfo')){
			redirect(base_url('vip/home/index'));
		}
		//登录后跳转
		$referer = trim($this->input->get('referer', true));
		$referer = str_replace(array('[removed]', '"', '\'', '\\'), '', $referer);
		if(empty($referer) || strpos($referer, base_url()) !== 0){
			$referer = base_url('vip/product/index');
		}
		$this->load->view('vip/user_login', array('referer'=>$referer));
	}

	//退出
	public function logout(){
		//删除SESSION
		if(false != $this->session->userdata('vipinfo')){
			$this->session->unset_userdata('vipinfo');
		}
		//页面跳转
		redirect(base_url('vip/user/login'));
	}

	//提交登录
	public function ajax_login(){
		//仅限Ajax访问
		$this->input->is_ajax_request() || show_404();
		//默认信息
		$err['err_no'] = 1000;
		$err['err_msg'] = parent::$errorType[1000];
		//已登录则返回
		if(false != $this->session->userdata('vipinfo')){
			exit(json_encode($err));
		}
		//接收数据
		$data['username'] = trim($this->input->post('username', true));
		$data['pass'] = trim($this->input->post('password', true));
		//账号检测
		$this->load->model('Vip_model');
		if(true == $this->Vip_model->checkPassword($data['username'], $data['pass'])){
			//更新会员信息
			$uid = $this->Vip_model->getUid($data['username']);
			$info = array('login_time'=>time(), 'login_ip'=>getUserIP());
			$this->Base_model->update('vip_users', $info, array('id'=>$uid));
			//设置SESSION
			$vipInfo = array('uid'=>$uid, 'username'=>$data['username']);
			$this->session->set_userdata('vipinfo', $vipInfo);
			//更新购物车
			$cart = json_decode($this->input->cookie('vipcart', true), true);
			if(empty($cart)){
				$this->Cart_model->vipSyncToCart($uid);
			}else{
				$this->Cart_model->vipSyncToDb($uid, $cart);
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
			$this->Base_model->insert('vip_users_log_login', $login_log);
			//提示信息
			$err['err_no'] = 1003;
			$err['err_msg'] = parent::$errorType[1003];
		}
		exit(json_encode($err));
	}
}