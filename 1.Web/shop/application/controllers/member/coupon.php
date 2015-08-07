<?php
/**
 * 优惠券
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-10
 */
require_once 'common.php';
class Coupon extends Common{
	
	//优惠券列表
	public function index($coupon = '', $type = 'all'){
		in_array($coupon, array('cash')) || show_404();
		//in_array($coupon, array('discount','cash')) || show_404();
		in_array($type, array('all','normal','used','dued')) || show_404();
		$page = max(1, intval($this->input->get('page')));
		$userInfo = $this->session->userdata('userinfo');
		//优惠券类型[抵用券/代金券]
		if($coupon == 'cash'){
		    $coupon_type = 1;
		    $currMenu = 'member_coupon_cash';
		    $tpl = 'member/coupon_cash';
		}else{
			$coupon_type = 2;
			$currMenu = 'member_coupon_discount';
			$tpl = 'member/coupon_discount';
		}
		//条件筛选
		$curr_time = time();
		switch($type){
			case 'normal':
			    $where = "status = 1 and (unix_timestamp(now())<end or end=0) and coupon_balance>0";
			    break;
			case 'used':
			    $where = "status = 2";
				break;
			case 'dued':
				$where = "unix_timestamp(now())>end and end>0";
				break;
			default:
				$where = '1';
		}
		$sql = "SELECT * FROM users_coupon WHERE uid = {$userInfo['uid']} AND coupon_type = {$coupon_type} AND {$where} ORDER BY create_time DESC";
		$data = $this->Base_model->pagerQuery($sql, $page, 5);
		$pager = parent::_formatPager(base_url("member/coupon/index/{$coupon}/{$type}"), $data->pager);
		$this->load->view($tpl, array('data'=>$data->results,'type'=>$type,'pager'=>$pager,'currMenu'=>$currMenu));
	}
}