<?php
/**
 * 个人中心
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-5
 */
require_once 'common.php';
class Home extends Common{
	
	//首页
	public function index(){
		$page = max(1, intval($this->input->get('page')));;
		$curr_time = time();
		//会员信息
		$userInfo = $this->session->userdata('userinfo');
		//余额积分
		$sql_user = "SELECT balance,score FROM users WHERE id = {$userInfo['uid']}";
		$userdata = $this->Base_model->getRow($sql_user);
		//优惠券
		$sql_cash = "SELECT COUNT(*) FROM users_coupon WHERE uid = {$userInfo['uid']} AND coupon_type = 1 AND coupon_balance > 0 ";
		$userdata['cash_coupon_number'] = $this->Base_model->getOne($sql_cash);
		//站内消息
		$sql_message = "SELECT COUNT(*) FROM users_msg WHERE uid = {$userInfo['uid']} AND is_read = 0";
		$userdata['message_number'] = $this->Base_model->getOne($sql_message);
		//待评论商品
		$sql_comment = "
			SELECT COUNT(*)
			FROM orders_detail AS d
			LEFT JOIN orders AS o ON o.id = d.order_id
			WHERE d.uid = {$userInfo['uid']} AND d.is_comment = 0 AND o.order_status = 20
		";
		$userdata['comment_number'] = $this->Base_model->getOne($sql_comment);
		//待处理订单
		$sql_waiting = "SELECT COUNT(*) FROM orders WHERE uid = {$userInfo['uid']} AND order_status = 0";
		$userdata['order_number'] = $this->Base_model->getOne($sql_waiting);
		//订单信息
		$sql_order = "SELECT id,order_status,pay_status,create_time,price_total,price
					FROM orders
					WHERE uid = {$userInfo['uid']}
					ORDER BY create_time DESC";
		$order = $this->Base_model->pagerQuery($sql_order, $page, 3);
		$data = $order->results;
		$pager = parent::_formatPager(base_url('member/order/index'), $order->pager);
		//商品信息
		if( ! empty($data)){
			foreach($data as &$v){
				$sql_goods = "SELECT d.*, p.thumb
							FROM orders_detail AS d
							LEFT JOIN products AS p ON p.id = d.product_id
							WHERE order_id = {$v['id']}
							ORDER BY d.id ASC LIMIT 4";
				$v['status_detail'] = isset(parent::$orderStatus[$v['order_status']]) ? parent::$orderStatus[$v['order_status']] : '进行中';
				$v['goods'] = $this->Base_model->getAll($sql_goods);
			}
		}
		//浏览记录
		$this->load->model('View_model');
		$view = $this->View_model->getViewLog($userInfo['uid'], 20);
		$this->load->view('member/home_index', array('data'=>$data,'userdata'=>$userdata,'pager'=>$pager,'view'=>$view,'currMenu'=>'member_home'));
	}
	
	//获取余额
	public function ajax_balance(){
	    //会员信息
	    $userInfo = $this->session->userdata('userinfo');
	    $card_no = $this->Base_model->getOne("SELECT cardno FROM users WHERE id = {$userInfo['uid']}");
	    $balance = $this->_updCard($card_no, $userInfo['uid']);
	    //返回
	    $err['err_no'] = 0;
	    $err['results'] = sprintf('%.2f', $balance);
	    exit(json_encode($err));
	}
}