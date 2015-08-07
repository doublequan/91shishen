<?php
/**
 * VIP父控制器
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-25
 */
require_once APPPATH.'controllers/base.php';
class Common extends Base{

	//构造函数
	public function __construct(){
		parent::__construct();
		//权限判断
		$vipInfo = $this->session->userdata('vipinfo');
		if(false == $vipInfo){
			//AJAX请求
			if($this->input->is_ajax_request()){
				$err['err_no'] = 1010;
				$err['err_msg'] = parent::$errorType[1010];
				exit(json_encode($err));
			}
			//普通请求
			redirect(base_url('vip/user/login').'?referer='.current_url());
		}
		$this->load->vars(array('vipInfo'=>$vipInfo,'currMenu'=>'vip_home'));
	}
}