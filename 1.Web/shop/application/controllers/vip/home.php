<?php
/**
 * VIP用户中心
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-25
 */
require_once 'common.php';
class Home extends Common{
	
	//首页
	public function index(){
	    $page = max(1, intval($this->input->get('page')));;
	    $curr_time = time();
	    //会员信息
	    $vipInfo = $this->session->userdata('vipinfo');
	    //登录时间
	    $data['login_time'] = $this->Base_model->getOne("SELECT login_time FROM vip_users WHERE id = {$vipInfo['uid']}");
	    //交易总数
	    $data['total_times'] = $this->Base_model->getOne("SELECT COUNT(*) FROM vip_users WHERE id = {$vipInfo['uid']}");
	    //订单信息
	    $sql_order = "SELECT id,price,receiver,create_time,order_status
	    	FROM vip_orders
		    WHERE uid = {$vipInfo['uid']}
		    ORDER BY create_time DESC";
	    $order = $this->Base_model->pagerQuery($sql_order, $page, 3);
	    $data['order'] = $order->results;
	    if( ! empty($data['order'])){
	        foreach($data['order'] as &$v){
	            $v['status_detail'] = parent::$orderStatus[$v['order_status']];
	        }   
	    }
	    $pager = parent::_formatPager(base_url('vip/home/index'), $order->pager);
		$this->load->view('vip/home_index', array('data'=>$data,'pager'=>$pager,'currMenu'=>'vip_home'));
	}
}