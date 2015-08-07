<?php
/**
 * 会员中心父控制器
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-6
 */
require_once APPPATH.'controllers/base.php';
class Common extends Base{
	
	//构造函数
	public function __construct(){
		parent::__construct();
		//权限判断
		$userInfo = $this->session->userdata('userinfo');
		if(false == $userInfo){
			//AJAX请求
			if($this->input->is_ajax_request()){
				$err['err_no'] = 1010;
				$err['err_msg'] = parent::$errorType[1010];
				exit(json_encode($err));
			}
			//普通请求
			redirect(base_url('member/user/login').'?referer='.current_url());
		}
		$this->load->vars(array('userInfo'=>$userInfo,'currMenu'=>'member_order'));
	}
	
	//更新会员卡余额
	protected function _updCard($card_no = '', $uid = 0){
	    $balance = 0.00;
	    if(empty($card_no) || empty($uid)){
	        return $balance;
	    }
	    $this->load->library('yeepay');
	    $result = $this->yeepay->query($card_no);
	    if(isset($result['r2_code']) && $result['r2_code'] == '1'){
	        $balance = floatval($result['r17_cardbalance']);
	        //更新用户表
	        //$this->Base_model->update('users', array('balance'=>$balance), array('id'=>$uid));
	    }
	    //返回
	    return $balance;
	}
}