<?php
/**
 * VIP订单模块
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-29
 */
require_once 'common.php';
class Order extends Common{
	
	//我的订单
	public function index($type = 'all'){
		$page = max(1, intval($this->input->get('page')));;
	    //会员信息
	    $vipInfo = $this->session->userdata('vipinfo');
	    //订单信息
	    $sql_order = "
	        SELECT id,price,receiver,create_time,order_status
	    	FROM vip_orders
		    WHERE uid = {$vipInfo['uid']}
		    ORDER BY create_time DESC
        ";
	    $order = $this->Base_model->pagerQuery($sql_order, $page, 5);
	    $data['order'] = $order->results;
	    foreach($data['order'] as &$v){
	        $v['status_detail'] = parent::$orderStatus[$v['order_status']];
	    }
	    $pager = parent::_formatPager(base_url('vip/order/index'), $order->pager);
		$this->load->view('vip/order_index', array('data'=>$data,'pager'=>$pager,'currMenu'=>'vip_order'));
	}
	
	//订单详情
	public function detail($order_id = ''){
		//订单信息
		empty($order_id) && show_404();
		$order_id = trim($order_id);
		//会员信息
		$vipInfo = $this->session->userdata('vipinfo');
		$data = $this->Base_model->getRow("SELECT * FROM vip_orders WHERE id = {$order_id}");
		if(empty($data) || $data['uid'] != $vipInfo['uid']){
		    show_404();
		}
		$data['status_detail'] = parent::$orderStatus[$data['order_status']];
		//订单明细
		$detail = $this->Base_model->getAll("
			SELECT d.*,p.thumb,p.sku
			FROM vip_orders_detail AS d
			LEFT JOIN vip_products AS p ON p.id = d.product_id
			WHERE order_id = {$order_id}
		");
		$this->load->view('vip/order_detail', array('data'=>$data,'detail'=>$detail));
	}
	
	//取消订单
	public function ajax_cancel(){
		//仅限Ajax访问
		$this->input->is_ajax_request() || show_404();
		//会员信息
		$vipInfo = $this->session->userdata('vipinfo');
		//订单信息
		$order_id = trim($this->input->post('order_id', true));
		$order = $this->Base_model->getRow("SELECT order_status,uid FROM vip_orders WHERE id = '{$order_id}'");
		if(empty($order) || $order['uid'] != $vipInfo['uid'] || $order['order_status'] != 1){
			$err['err_no'] = 1003;
			$err['err_msg'] = parent::$errorType[1003];
			exit(json_encode($err));
		}
		//执行取消
		$data['order_status'] = 11; //取消订单
		$result = $this->Base_model->update('vip_orders', $data, array('id'=>$order_id));
		if($result > 0){
			$err['err_no'] = 0;
			$err['err_msg'] = parent::$errorType[0];
		}else{
			$err['err_no'] = 1000;
			$err['err_msg'] = parent::$errorType[1000];
		}
		exit(json_encode($err));
	}
	
	//预提交检查
	public function check(){
		//购物车为空则跳转
		$cart = json_decode($this->input->cookie('vipcart', true), true);
		if(empty($cart)){
			redirect(base_url('vip/cart/index'));
		}
		//当前系统时间
		$curr_time = time();
		//加密令牌
		$hash_token = md5(getRandStr().$curr_time);
		$this->session->set_userdata('hash_token', $hash_token);
		//会员信息
		$vipInfo = $this->session->userdata('vipinfo');
		$vipData = $this->Base_model->getRow("SELECT username,mobile,discount FROM vip_users WHERE id = {$vipInfo['uid']}");
		$discount = $vipData['discount'];
		$discount = $discount > 0 ? $discount / 100 : 1;
        //格式化商品信息
		$product = array();
		$total_price = 0.00;
		foreach($cart as $k => $v){
		    $product_id = intval($k);
		    $product_info = $this->Base_model->getRow("SELECT title,price,is_del FROM vip_products WHERE id = {$product_id}");
		    if(empty($product_info) || $product_info['is_del'] == 1){
		        unset($cart[$product_id]);
		        continue;
		    }
		    //确认商品信息
		    $product_info['price'] = $product_info['price'] * $discount;
		    $product_info['amount'] = max(1, intval($v['amount']));
		    $product[$product_id] = $product_info;
		    $product[$product_id]['single_price'] = $product_info['price'] * $product_info['amount'];
		    $total_price += $product[$product_id]['single_price'];
		    //确认购物车信息
		    $cart[$product_id]['price'] = $product_info['price'];
		    $cart[$product_id]['amount'] = max(1, intval($v['amount']));
		}
		//更新购物车
		$this->input->set_cookie('vipcart', json_encode($cart), '86400');
		if(false != $vipInfo){
		    $this->Cart_model->vipSyncToDb($vipInfo['uid'], $cart);
		}
		//收货地址
		$this->load->model('Address_model');
		$address = $this->Address_model->getAddress($vipInfo['uid'], true);
		$data = array(
			'product' => $product,
			'total_price' => $total_price,
			'address' => $address,
			'vipdata' => $vipData,
			'hash_token' => $hash_token
		);
		$this->load->view('vip/order_check', $data);
	}
	
	//提交订单
	public function ajax_submit(){
		//仅限Ajax访问
		$this->input->is_ajax_request() || show_404();
		//接收数据
		$hash_token = $this->input->post('hash_token');
		$address_id = intval($this->input->post('address_id'));
		$pay_type = intval($this->input->post('pay_type'));
		$product_id_array = $this->input->post('product_id');
		$amount_array = $this->input->post('amount');
		$note = trim($this->input->post('note', true));
		$real_price = floatval($this->input->post('real_price')); //实际支付
		//当前时间
		$curr_time = time();
		//会员信息
		$vipInfo = $this->session->userdata('vipinfo');
		$discount = $this->Base_model->getOne("SELECT discount FROM vip_users WHERE id = {$vipInfo['uid']}");
		$discount = $discount > 0 ? $discount / 100 : 1;
		//默认状态
		$err['err_no'] = 1000;
		$err['err_msg'] = parent::$errorType[1000];
		
		//检查令牌
		if(empty($hash_token) || $hash_token != $this->session->userdata('hash_token')){
			$err['err_no'] = 1001;
			$err['err_msg'] = parent::$errorType[1001];
			exit(json_encode($err));
		}
	
		//预定义检测变量
		$check_real_price = 0.00; //实际支付金额
        //商品信息
        $product = array();
		for($i=0; $i<count($product_id_array); $i++){
			$product_id = intval($product_id_array[$i]);
			$amount = intval($amount_array[$i]);
			$product_info = $this->Base_model->getRow("SELECT title,price FROM vip_products WHERE id = {$product_id}");
			$product_info['price'] = $product_info['price'] * $discount;
			if( ! empty($product_info)){
				$product[$product_id]['price'] = $product_info['price'];
				$product[$product_id]['amount'] = max(1, $amount);
				$product[$product_id]['single_price'] = $product[$product_id]['price'] * $product[$product_id]['amount'];
				$product[$product_id]['title'] = $product_info['title'];
				$check_real_price += $product[$product_id]['single_price'];
			}
		}
		
		//检查实际支付金额
		if(bccomp($real_price, $check_real_price, 2) != 0){
		    //数据可能有误
		    exit(json_encode($err));
		}
		//检查收货地址
		$address_uid = $this->Base_model->getOne("SELECT uid FROM vip_users_address WHERE id = {$address_id}");
		if($address_uid != $vipInfo['uid']){
		    //数据可能有误
		    exit(json_encode($err));
		}else{
		    $this->load->model('Address_model');
		    $address = $this->Address_model->getAddressById($address_id, $vipInfo['uid'], true);
		}
		
		//设置订单信息
		$order = array();
		$order['id'] = createOrderID($vipInfo['uid']); //订单号
		$order['uid'] = $vipInfo['uid']; //会员ID
		$order['order_status'] = 1; //待确认
		$order['price'] = $real_price; //实际支付金额
		$order['create_time'] = $curr_time; //下单时间
		$order['prov'] = $address['prov_name']; //省份
		$order['city'] = $address['city_name']; //地市
		$order['district'] = $address['district_name']; //区县
		$order['address'] = $address['address']; //详细地址
		$order['receiver'] = $address['receiver']; //收件人姓名
		$order['mobile'] = $address['mobile']; //手机
		$order['zip'] = $address['zip']; //邮编
		$order['note'] = $note; //用户备注
		
		//创建订单
		$result = $this->Base_model->insert('vip_orders', $order);
		if(false != $result){
			//订单商品详情
			$orders_detail = array();
			foreach($product as $k=>$v){
			    $order_goods['uid'] = $order['uid'];
				$order_goods['order_id'] = $order['id'];
				$order_goods['product_id'] = $k;
				$order_goods['product_name'] = $v['title'];
				$order_goods['price_single'] = $v['price'];
				$order_goods['amount'] = $v['amount'];
				$order_goods['price_total'] = $v['single_price'];
				$order_goods['create_time'] = $curr_time;
				$orders_detail[] = $order_goods;
			}
			$this->Base_model->insertMulti('vip_orders_detail', $orders_detail);
            
			//删除Token
			$this->session->unset_userdata('hash_token');
			//清空购物车
			$this->Cart_model->vipSyncToDb($vipInfo['uid']);
			$this->input->set_cookie('vipcart', '', '');
			//成功提示
			$err['err_no'] = 0;
			$err['err_msg'] = parent::$errorType[0];
			$err['results'] = $order['id'];
		}
		exit(json_encode($err));
	}
	
	//提交订单成功提示页面
	public function success($order_id = ''){
	    //订单ID
	    $order_id = $this->security->xss_clean($order_id);
	    empty($order_id) && show_404();
	    //订单信息
	    $data = $this->Base_model->getRow("SELECT * FROM vip_orders WHERE id = '{$order_id}'");
	    empty($data) && show_404();
	    $data['arrive_time'] = $this->_getArriveTime($data['create_time']);
		$this->load->view('vip/order_success', array('data'=>$data));
	}
	
	//送达时间
	private function _getArriveTime($create_time = 0){
	    $delimiter_time = strtotime(date('Y-m-d').' 12:00:00'); //分界时间
	    return $create_time < $delimiter_time ? strtotime(date('Y-m-d', strtotime('+1 day')).' 11:00:00') : strtotime(date('Y-m-d', strtotime('+1 day')).' 18:00:00');
	}
}