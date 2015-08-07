<?php
/**
 * 订单模块
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-5
 */
require_once 'common.php';
class Order extends Common{
	
	//我的订单
	public function index($type = 'all'){
		$page = max(1, intval($this->input->get('page')));
		in_array($type, array('all','normal','complete','comment')) || show_404();
		//会员信息
		$userInfo = $this->session->userdata('userinfo');
		//包含未评价商品的订单
		$no_comment_order = $this->Base_model->getAll("SELECT order_id FROM orders_detail WHERE is_comment = 0 GROUP BY order_id");
		$no_comment_id = '0';
		if( ! empty($no_comment_order)){
			foreach($no_comment_order as $v){
				$no_comment_id .= ','.$v['order_id'];
			}
			$no_comment_id = trim($no_comment_id, ',');
		}
		//订单信息
		$where = "uid = {$userInfo['uid']}";
		if($type == 'normal'){
			//待处理
		    $where .= " AND order_status = 0";
		}elseif($type == 'complete'){
			//已完成
			$where .= " AND order_status = 20 AND id NOT IN ({$no_comment_id})";
		}elseif($type == 'comment'){
			//待评价
			$where .= " AND order_status = 20 AND id IN ({$no_comment_id})";
		}
		$sql_order = "SELECT id,order_status,pay_status,pay_type,create_time,price_total,price
					FROM orders
					WHERE {$where}
					ORDER BY create_time DESC";
		$order = $this->Base_model->pagerQuery($sql_order, $page, 5);
		$data = $order->results;
		$pager = parent::_formatPager(base_url("member/order/index/{$type}"), $order->pager);
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
		$this->load->view('member/order_index', array('data'=>$data,'type'=>$type,'pager'=>$pager,'currMenu'=>'member_order'));
	}
	
	//订单详情
	public function detail($order_id = ''){
		//订单信息
		$order_id = trim($order_id);
		empty($order_id) && show_404();
		//会员信息
		$userInfo = $this->session->userdata('userinfo');
		$data = $this->Base_model->getRow("SELECT * FROM orders WHERE id = '{$order_id}'");
		if(empty($data) || $data['uid'] != $userInfo['uid']){
			show_404();
		}
		$data['status_detail'] = isset(parent::$orderStatus[$data['order_status']]) ? parent::$orderStatus[$data['order_status']] : '进行中';
		$data['paytype_detail'] = parent::$payType[$data['pay_type']];
		//订单明细
		$detail = $this->Base_model->getAll("
			SELECT d.*,p.thumb,p.sku
			FROM orders_detail AS d
			LEFT JOIN products AS p ON p.id = d.product_id
			WHERE order_id = {$order_id}
		");
		//店铺信息
		$store = array();
		if($data['delivery_type'] == 0 && $data['store_id'] != 0){
		    $this->load->model('Store_model');
		    $store = $this->Store_model->getStoreByID($data['store_id']);
		}
		//物流信息
		$express = $this->Base_model->getAll("
			SELECT status,des,create_time
			FROM orders_action
			WHERE order_id = '{$order_id}' AND is_show = 1
			ORDER BY id ASC
		");
		//出库/发货/完成时间
		$data['out_time'] = $this->Base_model->getOne("SELECT create_time FROM orders_action WHERE order_id = '{$order_id}' AND status = 25");
		$data['start_time'] = $this->Base_model->getOne("SELECT create_time FROM orders_action WHERE order_id = '{$order_id}' AND status = 27");
		$data['end_time'] = $this->Base_model->getOne("SELECT create_time FROM orders_action WHERE order_id = '{$order_id}' AND status = 20");
		//加密令牌
		$hash_token = md5(getRandStr().time());
		$data['hash_token'] = $hash_token;
		$this->session->set_userdata('hash_token', $hash_token);
		$this->load->view('member/order_detail', array('data'=>$data,'detail'=>$detail,'express'=>$express,'store'=>$store));
	}
	
	//取消订单
	public function ajax_cancel(){
		//仅限Ajax访问
		$this->input->is_ajax_request() || show_404();
		//默认信息
		$err['err_no'] = 1000;
		$err['err_msg'] = parent::$errorType[1000];
		//接收数据
		$order_id = trim($this->input->post('order_id', true));
		empty($order_id) && exit(json_encode($err));
		//会员信息
		$userInfo = $this->session->userdata('userinfo');
		//订单信息
		$order = $this->Base_model->getRow("SELECT uid,order_status,pay_type,price,price_cash,coupon_log_id,site_id FROM orders WHERE id = '{$order_id}'");
		//商品信息
		$product = $this->Base_model->getAll("SELECT product_id,amount FROM orders_detail WHERE order_id = {$order_id}");
		if(
		    empty($order) || empty($product) || $order['uid'] != $userInfo['uid']
		    || ! ($order['order_status'] == 0 || $order['order_status'] == 2 || $order['order_status'] == 1 && ($order['pay_type'] == 1 || bccomp($order['price'], '0.00', 2) == 0))
        ){
			$err['err_no'] = 1003;
			$err['err_msg'] = parent::$errorType[1003];
			exit(json_encode($err));
		}
		//启用事务
		$this->db->trans_begin();
		$curr_time = time();
		//订单状态
		$data1 = array('order_status' => 11);
		$where1 = array('id' => $order_id);
		$result1 = $this->Base_model->update('orders', $data1, $where1);
		//检查代金券
		$result2 = $result3 = true;
		$coupon_log = $this->Base_model->getRow("SELECT * FROM users_log_coupon WHERE id = '{$order['coupon_log_id']}'");
		if( ! empty($coupon_log) && $order['price_cash'] > 0 && $order['price_cash'] == $coupon_log['coupon_use']){
			//更新代金券
			$coupon_cash = $this->Base_model->getRow("SELECT coupon_used,coupon_balance FROM users_coupon WHERE id = {$coupon_log['coupon_id']}");
			$data2 = array(
				'coupon_used' => $coupon_cash['coupon_used'] - $coupon_log['coupon_use'],
				'coupon_balance' => $coupon_cash['coupon_balance'] + $coupon_log['coupon_use']
			);
			$where2 = array('id' => $coupon_log['coupon_id']);
			$result2 = $this->Base_model->update('users_coupon', $data2, $where2);
			//代金券退回记录
			$data3 = array('is_del' => 1);
			$where3 = array('id' => $order['coupon_log_id']);
			$result3 = $this->Base_model->update('users_log_coupon', $data3, $where3);
		}
		//处理结果
		if($result1 && $result2 && $result3){
			//提交事务
			$this->db->trans_commit();
			//更新库存
			foreach($product as $v){
			    $product_id = intval($v['product_id']);
			    $amount = intval($v['amount']);
			    $stock = $this->Base_model->getOne("SELECT stock FROM products_site WHERE product_id = {$product_id} AND site_id = {$order['site_id']}");
			    if($stock >= 0){
			        $curr_amount = $stock + $amount;
			        $this->Base_model->update('products_site', array('stock'=>$curr_amount), array('product_id'=>$product_id,'site_id'=>$order['site_id']));
			    }
			}
			//成功提示
			$err['err_no'] = 0;
			$err['err_msg'] = parent::$errorType[0];
		}else{
			//回滚事务
			$this->db->trans_rollback();
		}
		exit(json_encode($err));
	}
	
	//预提交检查
	public function check(){
		//购物车为空则跳转
		$cart = json_decode($this->input->cookie('cart', true), true);
		if(empty($cart)){
			redirect(base_url('cart/index'));
		}
		//当前系统时间
		$curr_time = time();
		//加密令牌
		$hash_token = md5(getRandStr().$curr_time);
		$this->session->set_userdata('hash_token', $hash_token);
		//会员信息
		$userInfo = $this->session->userdata('userinfo');
		$userData = $this->Base_model->getRow("SELECT username,tel,mobile,email,discount FROM users WHERE id = {$userInfo['uid']}");
		//获取满59元免费赠送商品
		$freeMap = array();
		$this->load->model('Product_model', 'mProduct');
		$res = $this->mProduct->getSpecialProducts(7);
		if( $res ){
			foreach ( $res as $row ){
				$freeMap[$row['id']] = $row;
			}
		}
		//检查已有商品总价
		$paid = 0;
		$freeNum = 0;
		foreach( $cart as $product_id=>$row ){
			$p = $this->Base_model->getSingle('products','id',$product_id,'price');
			if( !isset($freeMap[$product_id]) || $freeNum>=2 ){
				$paid += $p['price']*$row['amount'];
			} else {
				$freeNum++;
				$paid += $p['price']*($row['amount']-1);
			}
		}
		//格式化商品信息
		$freeNum = 0;
		$product = array();
		$total_price = 0.00;
		$price_discount_give = 0;
		//判断是否都是0元购商品
		foreach($cart as $k => $v){
		    $product_id = intval($k);
		    $amount = max(1, intval($v['amount']));
		    $product_info = $this->Base_model->getRow("SELECT title,price,thumb,is_del FROM products WHERE id = {$product_id}");
		    $stock = $this->Base_model->getOne("SELECT stock FROM products_site WHERE site_id = ".SITEID." AND product_id = {$product_id}");
		    if(empty($product_info) || $product_info['is_del'] == 1 || empty($stock)){
		        unset($cart[$product_id]);
		        continue;
		    }
		    if($stock > 0){
		        $amount = min($amount, $stock);
		    }
		    //加入到免费商品
		    $product_info['free'] = false;
		    $product_info['free_label'] = '';
		    $amount_calculate = $amount;
		    if( $paid>=59 && $freeNum<2 && isset($freeMap[$product_id]) ){
		    	$freeNum++;
		    	$product_info['free'] = true;
		    	$product_info['free_label'] = '【<font color="red">单笔订单满59元，免费商品任选2份</font>，<a href="/special/give" target="_blank"><font color="blue">查看更多</font></a>】';
		    	$amount_calculate--;
		    	$price_discount_give += $product_info['price'];
		    } else {
		    	$is_all_free = false;
		    }
		    //确认商品信息
		    $product_info['amount'] = $amount;
		    $product[$product_id] = $product_info;
		    $product[$product_id]['single_price'] = $product_info['price'] * $amount_calculate;
		    $total_price += $product[$product_id]['single_price'];
		    //确认购物车信息
		    $cart[$product_id]['price'] = $product_info['price'];
		    $cart[$product_id]['amount'] = $product_info['amount'];
		}
		//新疆阿克苏冰糖心苹果箱装（5.5kg），原价80，现价66促销
		$pid = 3134;
		if( $freeNum==0 && isset($cart[$pid]) ){
			$p = $this->Base_model->getSingle('products','id',$pid,'price');
			if( $paid-$p['price']>=59 ){
				$total_price -= 14*$cart[$pid]['amount'];
				$product[$pid]['single_price'] -= 14*$cart[$pid]['amount'];
				$product[$pid]['free_label'] = '【<font color="red">单笔订单满59元，不领取赠品，冰糖心苹果每箱立减14元!</font>】';
			}
		}
		//更新购物车
		$this->input->set_cookie('cart', json_encode($cart), '86400');
		if(false != $userInfo){
		    $this->Cart_model->syncToDb($userInfo['uid'], $cart);
		}
		//可用代金券
		//$cash_sql = "SELECT * FROM users_coupon WHERE uid = {$userInfo['uid']} AND coupon_type = 1 AND coupon_balance > 0 AND start <= {$curr_time} AND end > {$curr_time}";
		$cash_sql = "SELECT * FROM users_coupon
					WHERE uid={$userInfo['uid']} AND coupon_type=1 AND status=1 and coupon_limit<=".$total_price." and (unix_timestamp(now())<end or end=0) and coupon_balance>0";
		$cash_coupon = $this->Base_model->getAll($cash_sql);
		//不可用代金券
		$deny_cash_sql = "SELECT * FROM users_coupon
					WHERE uid={$userInfo['uid']} AND coupon_type=1 AND ((unix_timestamp(now())>end and end>0) or coupon_balance<=0 or coupon_limit>".$total_price.")";
		$deny_coupon = $this->Base_model->getAll($deny_cash_sql);	
		//折扣信息
		$discount_price = round($total_price * (1 - $userData['discount'] / 100), 2);
		//运费
		$freight = self::_getFreight($total_price);
		//根据当前网站获取可用自提点
		$site = $this->Base_model->getSingle('sites','id',SITEID);
		$prov = $this->Base_model->getSingle('areas','id',$site['prov']);
		$city = $this->Base_model->getSingle('areas','id',$site['city']);
		//get all undisabled provinces and cities
		$district = array();
		$street = array();
		$condition = array(
			'AND' => array('disable=0','father_id='.$site['city']),
		);
		$res = $this->Base_model->getList('areas',$condition,'*','sort ASC');
		if( $res ){
			$father_ids = array();
			foreach ( $res as $row ){
				$district[$row['id']] = $row;
				$father_ids[] = intval($row['id']);
			}
			$condition = array(
				'AND' => array('disable=0','father_id IN ('.implode(',', $father_ids).')'),
			);
			$arr = $this->Base_model->getList('areas',$condition,'*','sort ASC');
			foreach ( $arr as $row ){
				$street[$row['father_id']][] = $row;
			}
		}
		//获取城市内可用自提点
		$this->load->model('Store_model');
		$store = $this->Store_model->getStore(SITEID);
		//获取城市内可用用户地址
		$this->load->model('Address_model');
		$address = $this->Address_model->getUserAddress($userInfo['uid'],$city['id']);
		$data = array(
			'site' => $site,
			'prov' => $prov,
			'city' => $city,
			'district' => $district,
			'street' => $street,
			'product' => $product,
			'total_price' => $total_price,
		    'discount_price' => $discount_price,
			'address' => $address,
		    'store' => $store,
			'userdata' => $userData,
			'cash_coupon' => $cash_coupon,
			'deny_coupon'=>$deny_coupon,
			'freight' => $freight,
			'summary' => $total_price - $discount_price + $freight,
			'is_all_free' => false,
			'hash_token' => $hash_token
		);
		$this->load->view('member/order_check', $data);
	}
	
	//提交订单
	public function ajax_submit(){
		//仅限Ajax访问
		$this->input->is_ajax_request() || show_404();
		//接收数据
		$hash_token = $this->input->post('hash_token');
		$address_id = intval($this->input->post('address_id')); //收货地址ID
		$pay_type = intval($this->input->post('pay_type')); //支付类型
		$cash_coupon_id = intval($this->input->post('cash_coupon_id')); //代金券ID
		$cash_coupon_money = floatval($this->input->post('cash_coupon')); //代金券使用金额
		$product_id_array = $this->input->post('product_id'); //商品ID数组
		$amount_array = $this->input->post('amount'); //购买数量数组
		$goods_price = floatval($this->input->post('goods_price')); //商品总价
		$real_price = floatval($this->input->post('real_price')); //实际支付
		$discount_price = floatval($this->input->post('discount_price')); //折扣金额
		$is_receipt = intval($this->input->post('is_receipt')); //是否开发票
		$receipt_title = trim($this->input->post('receipt_title', true)); //发票抬头
		$receipt_des = trim($this->input->post('receipt_des', true)); //发票内容
		$receive_type = intval($this->input->post('receive_type')); //收货日期类型
		$receive_date = trim($this->input->post('receive_date', true)); //收货日期
		$receive_ma = intval($this->input->post('receive_ma')); //[1]上午[2]下午
		$delivery_type = intval($this->input->post('delivery_type')); //[0]自提[1]物流
		$store_id = intval($this->input->post('store_id')); //门店ID
		$note = trim($this->input->post('note', true)); //订单备注
		$my_name = trim($this->input->post('my_name', true)); //自提联系人
		$my_mobile = trim($this->input->post('my_mobile', true)); //自提手机号
		//当前时间
		$curr_time = time();
		//会员信息
		$userInfo = $this->session->userdata('userinfo');
		//默认状态
		$err['err_no'] = 1000;
		$err['err_msg'] = parent::$errorType[1000];
		
		//检查令牌
		if(empty($hash_token) || $hash_token != $this->session->userdata('hash_token')){
			$err['err_no'] = 1001;
			$err['err_msg'] = parent::$errorType[1001];
			exit(json_encode($err));
		}
		//检查支付类型
		if( ! in_array($pay_type, array(1,2,3))){
			exit(json_encode($err));
		}
		//检查发票和收货时间
		if( ! in_array($is_receipt, array(0,1)) || ! in_array($receive_type, array(1,2,3,4))){
		    exit(json_encode($err));
		}
		if($is_receipt == 1 && (empty($receipt_title) || empty($receipt_des))){
		    exit(json_encode($err));
		}
		//处理收货时间
		if($receive_type == 4){
			//截单时间为每天11点
			$endTime = 11;
		    //分界时间
		    $delimiter_time = strtotime(date('Y-m-d', $curr_time) . ' ' . $endTime . ':00:00');
		    //当前收货日期
		    $curr_date = strtotime($receive_date);
		    //最大日期
		    $max_date = (date('H')<$endTime)?strtotime('+6 day', strtotime(date('Y-m-d'))):strtotime('+7 day', strtotime(date('Y-m-d')));
		    //最小日期
		    $min_date = (date('H')<$endTime)?strtotime('+0 day', strtotime(date('Y-m-d'))):strtotime('+1 day', strtotime(date('Y-m-d')));
		    //检查当前日期
		    if($curr_date < $min_date || $curr_date > $max_date){
		        exit(json_encode($err));
		    }
		    //检查当前上下午  无需检查了，统一下午配送
		    /*
		    if($curr_date == $min_date){
		        $right_noon = $curr_time < $delimiter_time ? 1 : 2;
		        if($receive_ma != $right_noon)	exit(json_encode($err));
		    }
		    */
		}else{
		    $arrive_time = self::_getArriveTime($curr_time, $receive_type);
		    $receive_date = $arrive_time['date'];
		    $receive_ma = $arrive_time['noon'];
		}
		
		//商品总价
		$check_goods_price = 0.00;
		//实际支付金额
		$check_real_price = $goods_price - $discount_price;
		//使用代金券金额
        $price_cash = 0.00;
        //获取零元购商品
        $freeMap = array();
        $this->load->model('Product_model', 'mProduct');
        $res = $this->mProduct->getSpecialProducts(7);
        if( $res ){
        	foreach ( $res as $row ){
        		$freeMap[$row['id']] = $row;
        	}
        }
        //检查已有商品总价
        $paid = 0;
        $freeNum = 0;
        for($i=0; $i<count($product_id_array); $i++){
        	$product_id = intval($product_id_array[$i]);
        	$amount = intval($amount_array[$i]);
        	$p = $this->Base_model->getSingle('products','id',$product_id,'price');
        	if( !isset($freeMap[$product_id]) || $freeNum>=2 ){
        		$paid += $p['price']*$amount;
        	} else {
        		$freeNum++;
        		$paid += $p['price']*($amount-1);
        	}
        }
        //商品信息
        $frees = array();
        $freeNum = 0;
        $product = array();
        $offsale = array(); //缺货信息
        $price_discount_give = 0;
		for($i=0; $i<count($product_id_array); $i++){
			$product_id = intval($product_id_array[$i]);
			$amount = intval($amount_array[$i]);
			$product_info = $this->Base_model->getRow("SELECT title,price,is_del FROM products WHERE id = {$product_id}");
			//检查商品库存
			$stock = $this->Base_model->getOne("SELECT stock FROM products_site WHERE site_id = ".SITEID." AND product_id = {$product_id}");
			if(empty($product_info) || $product_info['is_del'] == 1 || ($stock >= 0 && $stock < $amount)){
			    $offsale[] = $product_id;
			}else{
				//计算商品总价
				$product[$product_id]['price'] = $product_info['price'];
				$product[$product_id]['amount'] = max(1, $amount);
				$product[$product_id]['single_price'] = $product[$product_id]['price']*$product[$product_id]['amount'];
				$product[$product_id]['title'] = $product_info['title'];
				$check_goods_price += $product[$product_id]['single_price'];
				//免费商品加入price_minus
				if( $paid>=59 && $freeNum<2 && isset($freeMap[$product_id]) ){
					$freeNum++;
					$frees[$product_id] = $product_id;
					$price_discount_give += $product_info['price'];
				}
			}
		}
		$check_goods_price -= $price_discount_give;
		
		//新疆阿克苏冰糖心苹果箱装（5.5kg），原价80，现价66促销
		$pid = 3134;
		if( $freeNum==0 && isset($product[$pid]) ){
			$p = $this->Base_model->getSingle('products','id',$pid,'price');
			if( $paid-$p['price']>=59 ){
				$check_goods_price -= 14*$product[$pid]['amount'];
				$price_discount_give += 14*$product[$pid]['amount'];
			}
		}
		//库存不足
		if( ! empty($offsale)){
		    $err['err_no'] = 1003;
		    $err['err_msg'] = parent::$errorType[1003];
		    $err['results'] = $offsale;
		    exit(json_encode($err));
		}
		
		//检查商品总价
		if(bccomp($goods_price, $check_goods_price, 2) != 0){
			//数据可能有误
			exit(json_encode($err));
		}
		
		//检查折扣信息
		$discount_rate = $this->Base_model->getOne("SELECT discount FROM users WHERE id = {$userInfo['uid']}");
		$discount_rate = $discount_rate / 100;
		$check_discount_price = $goods_price * (1 - $discount_rate);
		if(bccomp($discount_price, $check_discount_price, 2) != 0){
		    //数据可能有误
		    exit(json_encode($err));
		}
		
		//检查收货地址
		if( $address_id ){
			$check_address = $this->Base_model->getRow("SELECT uid FROM users_address WHERE id = {$address_id}");
			if(empty($check_address) || $check_address['uid'] != $userInfo['uid']){
			    //数据可能有误
			    exit(json_encode($err));
			}else{
			    $this->load->model('Address_model');
			    $address = $this->Address_model->getAddressById($address_id, $userInfo['uid']);
			}
		}
		
		//检查门店信息
		if($store_id > 0){
		    $store = $this->Base_model->getRow("SELECT site_id,is_del FROM stores WHERE id = {$store_id}");
		    if(empty($store) || $store['site_id'] != SITEID || $store['is_del'] != 0){
		        //数据可能有误
		        exit(json_encode($err));
		    }
		}
		
		//使用代金券
		if( ! empty($cash_coupon_id)){
		    $cash_coupon = $this->Base_model->getRow("SELECT * FROM users_coupon WHERE id = {$cash_coupon_id}");
		    if(empty($cash_coupon) || $cash_coupon['coupon_type'] != 1 || $cash_coupon['uid'] != $userInfo['uid'] || $cash_coupon['status'] != 1){
		        //该代金券不可用
		        exit(json_encode($err));
		    }
		    $price_cash = min($cash_coupon['coupon_balance'], $cash_coupon_money); //代金券金额
		    $price_cash = min($price_cash, $check_real_price);
		    $check_real_price -= $price_cash; //实际支付金额
		}
		
		//应付运费
		if( $store_id>0 ){
		    $price_shipping = 0.00;
		}else{
		    $price_shipping = self::_getFreight($check_real_price);
		}
		$check_real_price += $price_shipping;
		
		//检查实际支付金额
		if(bccomp($real_price, $check_real_price, 2) != 0){
		    //数据可能有误
		    exit(json_encode($err));
		}
		
		//订单数据
		$order = array();
		$order['id'] = createOrderID($userInfo['uid']); //订单号
		$order['uid'] = $userInfo['uid']; //会员ID
		$order['site_id'] = SITEID; //网站ID
		$order['pay_type'] = $pay_type; //支付方式
		$order['order_status'] = $pay_type==1 ? 21 : ( bccomp($real_price, '0.00', 2)==0 ? 1 : 0 ); //待支付或已付款
		$order['pay_status'] = 0; //实际付款状态
		$order['price_total'] = $goods_price+$price_discount_give; //商品总金额
		$order['price'] = $real_price; //实际支付金额
		$order['price_discount'] = $discount_price+$price_discount_give; //折扣金额
		$order['price_cash'] = $price_cash; //使用代金券金额
		$order['price_shipping'] = $price_shipping; //运费金额
		$order['price_minus'] = 0; //减免金额
		$order['score'] = round($real_price); //获得积分
		$order['create_time'] = $curr_time; //下单时间
		$order['is_receipt'] = $is_receipt; //是否开发票
		$order['receipt_title'] = $receipt_title; //发票抬头
		$order['receipt_des'] = $receipt_des; //发票内容
		$order['date_type'] = $receive_type; //收货时间类型
		$order['date_day'] = $receive_date; //收货日期
		$order['date_noon'] = $receive_ma; //上午下午
		$order['delivery_type'] = $delivery_type; //物流
		$order['store_id'] = $store_id; //门店ID
		$order['note'] = $note; //用户备注
		if( $address_id ){
			$order['prov'] = $address['prov_name']; //省份
			$order['city'] = $address['city_name']; //地市
			$order['district'] = $address['district_name']; //区县
			$order['street'] = $address['street_name']; //街道地址
			$order['address'] = $address['address']; //详细地址
			$order['receiver'] = $address['receiver']; //收件人姓名
			$order['mobile'] = $address['mobile']; //手机
			$order['tel'] = $address['tel']; //电话
			$order['zip'] = $address['zip']; //邮编
		}
		//自提联系方式
		if($delivery_type == 0){
		    $order['receiver'] = $my_name;
		    $order['mobile'] = $my_mobile;
		}
		//get site info
		$site = $this->Base_model->getSingle('sites','id',SITEID);
		$order['deal_store_id'] = $site ? $site['default_store'] : 0;
		
		//启用事务
		$this->db->trans_begin();
		//创建订单
		$result1 = $this->Base_model->insert('orders', $order);
		//订单商品和零元购
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
		$result2 = $this->Base_model->insertMulti('orders_detail', $orders_detail);
		//更新代金券
		$result3 = $result4 = $result5 = true;
		if($price_cash > 0){
			$cash_coupon_data = array(
				'coupon_used' => $cash_coupon['coupon_used'] + $price_cash,
				'coupon_balance' => $cash_coupon['coupon_balance'] - $price_cash,
				'status' => 2
			);
			$result3 = $this->Base_model->update('users_coupon', $cash_coupon_data, array('id'=>$cash_coupon_id));
			//日志信息
			$coupon_log['uid'] = $userInfo['uid'];
			$coupon_log['order_id'] = $order['id'];
			$coupon_log['coupon_id'] = $cash_coupon_id;
			$coupon_log['coupon_type'] = 1;
			$coupon_log['coupon_use'] = $price_cash;
			$coupon_log['coupon_balance'] = $cash_coupon_data['coupon_balance'];
			$coupon_log['create_time'] = $curr_time;
			$result4 = $this->Base_model->insert('users_log_coupon', $coupon_log);
			//更新订单代金券信息
			$coupon_log_id = $result4['id'];
			$result5 = $this->Base_model->update('orders', array('coupon_log_id'=>$coupon_log_id), array('id'=>$order['id']));
		}
		//生成支付单
		$result6 = true;
		if($real_price > 0){
		    //实付金额大于0
		    if($pay_type == 2){
		        //支付宝
		        $payment['order_id'] = $order['id'];
		        $payment['price'] = $order['price'];
		        $result6 = $this->Base_model->insert('orders_alipay', $payment);
		    }elseif($pay_type == 3){
		        //会员卡
		        $payment['order_id'] = $order['id'];
		        $payment['channel'] = 2; //线上渠道
		        $payment['trade_type'] = 1; //消费
		        $payment['price'] = $order['price'];
		        $result6 = $this->Base_model->insert('orders_yeepay_card', $payment);
		    }
		}
		//处理结果
		if($result1 && $result2 && $result3 && $result4 && $result5 && $result6){
			//提交事务
			$this->db->trans_commit();
			//更新库存
			foreach($product as $k => $v){
			    $product_id = intval($k);
			    $amount = intval($v['amount']);
			    $stock = $this->Base_model->getOne("SELECT stock FROM products_site WHERE product_id = {$product_id} AND site_id = ".SITEID);
			    if($stock >= 0){
			        $curr_amount = max(0, $stock - $amount);
			        $this->Base_model->update('products_site', array('stock'=>$curr_amount), array('product_id'=>$product_id,'site_id'=>SITEID));
			    }
			}
			//删除Token
			$this->session->unset_userdata('hash_token');
			//清空购物车
			$this->Cart_model->syncToDb($userInfo['uid']);
			$this->input->set_cookie('cart', '', '');
			//成功提示
			$err['err_no'] = 0;
			$err['err_msg'] = parent::$errorType[0];
			$err['results'] = $order['id'];
		}else{
			//回滚事务
			$this->db->trans_rollback();
		}
		exit(json_encode($err));
	}
	
	//提交订单成功提示页面
	public function success($order_id = ''){
		//订单ID
		$order_id = $this->security->xss_clean($order_id);
		empty($order_id) && show_404();
		//订单信息
		$data = $this->Base_model->getRow("SELECT * FROM orders WHERE id = '{$order_id}'");
		(empty($data) || ! in_array($data['order_status'], array(0,1,2,21))) && show_404();
		//加密令牌
		$hash_token = md5(getRandStr().time());
		$data['hash_token'] = $hash_token;
		$this->session->set_userdata('hash_token', $hash_token);
		if($data['pay_type'] == 1){
		    //货到付款
		    $tpl = 'member/order_success';
		}elseif($data['pay_type'] == 2){
		    //支付宝
		    $tpl = 'member/order_success_alipay';
		}elseif($data['pay_type'] == 3){
		    //易宝会员卡
		    $tpl = 'member/order_success_yeepay';
		}
		$this->load->view($tpl, array('data'=>$data));
	}
	
	//确认收货
	public function ajax_confirm(){
		//仅限Ajax访问
		$this->input->is_ajax_request() || show_404();
		//会员信息
		$userInfo = $this->session->userdata('userinfo');
		//默认状态
		$err['err_no'] = 1000;
		$err['err_msg'] = parent::$errorType[1000];
		//订单信息
		$order_id = trim($this->input->post('order_id',true));
		$order = $this->Base_model->getRow("SELECT id,order_status,uid,score FROM orders WHERE id = '{$order_id}'");
		if(empty($order['uid']) || $order['uid'] != $userInfo['uid'] || $order['order_status'] != 27){
			exit(json_encode($err));
		}
		//更新订单状态
		$result = $this->Base_model->update('orders', array('order_status'=>20), array('id'=>$order_id));
		if(false != $result){
			//更新订单日志
			$order_action = array(
				'order_id' => $order_id,
				'status' => '20', //订单完成
				'des' => '用户确认收货',
				'is_show' => 1,
				'create_time' => time()
			);
			$this->Base_model->insert('orders_action', $order_action);
			//提示信息
			$err['err_no'] = 0;
			$err['err_msg'] = parent::$errorType[0];
		}
		exit(json_encode($err));
	}
	
	//添加评论
	public function ajax_comment(){
		//仅限Ajax访问
		$this->input->is_ajax_request() || show_404();
		//默认状态
		$err['err_no'] = 1000;
		$err['err_msg'] = parent::$errorType[1000];
		//会员信息
		$userInfo = $this->session->userdata('userinfo');
		//接收数据
		$product_id = intval($this->input->post('product_id'));
		$order_id = trim($this->input->post('order_id', true));
		$score = intval($this->input->post('score'));
		$comment = trim($this->input->post('comment'));
		//评论安全处理
		$comment = strip_tags($comment);
		$comment = str_replace(array('"',"'",';'), '', $comment);
		//数据检查
		if(empty($product_id) || empty($order_id) || empty($score) || $score < 1 || $score > 5 || empty($comment)){
			$err['err_no'] = 1002;
			$err['err_msg'] = parent::$errorType[1002];
			exit(json_encode($err));
		}
		//订单信息
		$order = $this->Base_model->getRow("SELECT site_id,order_status,create_time FROM orders WHERE id = '{$order_id}'");
		//订单明细
		$detail = $this->Base_model->getRow("SELECT id,uid,is_comment FROM orders_detail WHERE order_id = '{$order_id}' AND product_id = {$product_id}");
		//检查评论
		if(empty($order) || empty($detail) || $detail['uid'] != $userInfo['uid'] || $detail['is_comment'] != 0 || $order['order_status'] != 20){
			$err['err_no'] = 1003;
			$err['err_msg'] = parent::$errorType[1003];
			exit(json_encode($err));
		}
		//好中差评
		$level = 1; //好评
		if($score == 1){
			$level = 3; //差评
		}elseif($score >= 2 && $score <= 3){
			$level = 2; //中评
		}
		//添加评论
		$data['site_id'] = $order['site_id'];
		$data['uid'] = $userInfo['uid'];
		$data['username'] = $userInfo['username'];
		$data['order_id'] = $order_id;
		$data['product_id'] = $product_id;
		$data['content'] = $comment;
		$data['score'] = $score;
		$data['level'] = $level;
		$data['status'] = 0;
		$data['buy_time'] = $order['create_time'];
		$data['create_time'] = time();
		$result = $this->Base_model->insert('products_comment', $data);
		if(false != $result){
			//更改评论状态
			$this->Base_model->update('orders_detail', array('is_comment'=>1), array('id'=>$detail['id']));
			//更新商品评论数
			$this->db->query("UPDATE products SET comment = comment + 1 WHERE id = {$product_id}");
			//成功提示
			$err['err_no'] = 0;
			$err['err_msg'] = parent::$errorType[0];
		}
		exit(json_encode($err));
	}
	
	
	
	//支付提交 TODO delete
	public function ajax_paysubmit(){
		//仅限Ajax访问
		$this->input->is_ajax_request() || show_404();
		//默认状态
		$err['err_no'] = 1000;
		$err['err_msg'] = parent::$errorType[1000];
		//接收数据
		$order_id = trim($this->input->post('order_id',true));
		empty($order_id) && exit(json_encode($err));
		//会员信息
		$userInfo = $this->session->userdata('userinfo');
		$userdata = $this->Base_model->getRow("SELECT score,balance FROM users WHERE id = {$userInfo['uid']}");
		$score = $userdata['score']; // 账户积分
		$balance = $userdata['balance']; //账户余额
		//订单信息
		$data = $this->Base_model->getRow("SELECT id,uid,order_status,pay_status,price,score FROM orders WHERE id = '{$order_id}'");
		if(
			empty($data) || $data['uid'] != $userInfo['uid'] || $balance < $data['price']
			|| $data['order_status'] != 0 || $data['pay_status'] != 0
		){
			$err['err_no'] = 1003;
			$err['err_msg'] = parent::$errorType[1003];
			exit(json_encode($err));
		}
		//启用事务
		$this->db->trans_begin();
		$curr_time = time();
		//订单信息
		$data1 = array(
			'pay_status' => 1,
			'order_status' => 1,
			'price_pay' => $data['price'],
			'pay_time' => $curr_time
		);
		$where1 = array('id' => $order_id);
		$result1 = $this->Base_model->update('orders', $data1, $where1);
		//账户金额+积分
		$data2 = array('balance' => $balance - $data['price'], 'score' => $score + $data['score']);
		$where2 = array('id' => $userInfo['uid']);
		$result2 = $this->Base_model->update('users', $data2, $where2);
		//资金日志
		$data3 = array(
			'uid' => $userInfo['uid'],
			'change' => 0 - $data['price'],
			'curr' => $balance - $data['price'],
			'reason' => "支付订单[{$data['id']}]",
			'create_time' => $curr_time
		);
		$result3 = $this->Base_model->insert('users_log_money', $data3);
		//积分日志
		$data4 = array(
			'uid' => $userInfo['uid'],
			'change' => $data['score'],
			'curr' => $score + $data['score'],
			'reason' => "支付订单[{$data['id']}]",
			'create_time' => $curr_time
		);
		$result4 = $this->Base_model->insert('users_log_score', $data4);
		//订单日志
		$data5 = array(
			'order_id' => $order_id,
			'status' => '1',
			'des' => '订单支付成功',
			'is_show' => 1,
			'create_time' => $curr_time
		);
		$result5 = $this->Base_model->insert('orders_action', $data5);
		//处理结果
		if($result1 && $result2 && $result3 && $result4 && $data5){
			//提交事务
			$this->db->trans_commit();
			//成功提示
			$err['err_no'] = 0;
			$err['err_msg'] = parent::$errorType[0];
		}else{
			//回滚事务
			$this->db->trans_rollback();
		}
		exit(json_encode($err));
	}
	
	
	
	/**
	 * 计算运费
	 * @param float $real_price 实付金额
	 * @return float
	 */
	private static function _getFreight($real_price = 0.00){
	    $freight_limit = SHIPPING_FREELIMIT; //满x元免运费
        $freight_amount = SHIPPING_FEE; //运费x元
        return $real_price >= $freight_limit ? 0.00 : $freight_amount;
	}
	
	/**
	 * 计算收货时间
	 * @param integer $create_time 下单时间
	 * @param integer $date_type 收货时间类型
	 * @return array|false
	 */
	private static function _getArriveTime($create_time = 0, $date_type = 0){
		//截单时间为每天11点
		$endTime = 11;
	    //分界时间
	    $delimiter_time = strtotime(date('Y-m-d', $create_time) . ' ' . $endTime . ':00:00');
	    //收货时间类型
	    switch($date_type){
	        //正常收货
	        case 1:
	            $data['date'] = (date('H')<$endTime)?date('Y-m-d', strtotime('+0 day', $create_time)):date('Y-m-d', strtotime('+1 day', $create_time));
	            $data['noon'] = $create_time < $delimiter_time ? 1 : 2;
	            break;
	        //仅工作日
	        case 2:
	            if(date('w', $create_time) == 6){
	                //周六
	                $data['date'] = date('Y-m-d', strtotime('+2 day', $create_time));
	                $data['noon'] = 1;
	            }elseif(date('w', $create_time) == 5){
	                //周五
	                $data['date'] = date('Y-m-d', strtotime('+3 day', $create_time));
	                $data['noon'] = 1;
	            }else{
	                //周一二三四日
	                $data['date'] = date('Y-m-d', strtotime('+1 day', $create_time));
	                $data['noon'] = $create_time < $delimiter_time ? 1 : 2;
	            }
	            break;
            //仅周末
	        case 3:
	            if(date('w', $create_time) == 1){
	                //周一
	                $data['date'] = date('Y-m-d', strtotime('+5 day', $create_time));
	                $data['noon'] = 1;
	            }elseif(date('w', $create_time) == 2){
	                //周二
	                $data['date'] = date('Y-m-d', strtotime('+4 day', $create_time));
	                $data['noon'] = 1;
	            }elseif(date('w', $create_time) == 3){
	                //周三
	                $data['date'] = date('Y-m-d', strtotime('+3 day', $create_time));
	                $data['noon'] = 1;
	            }elseif(date('w', $create_time) == 4){
	                //周四
	                $data['date'] = date('Y-m-d', strtotime('+2 day', $create_time));
	                $data['noon'] = 1;
	            }elseif(date('w', $create_time) == 5){
	                //周五
	                $data['date'] = date('Y-m-d', strtotime('+1 day', $create_time));
	                $data['noon'] = $create_time < $delimiter_time ? 1 : 2;
	            }elseif(date('w', $create_time) == 6){
	                //周六
	                $data['date'] = date('Y-m-d', strtotime('+1 day', $create_time));
	                $data['noon'] = $create_time < $delimiter_time ? 1 : 2;
	            }elseif(date('w', $create_time) == 0){
	                //周日
	                $data['date'] = date('Y-m-d', strtotime('+6 day', $create_time));
	                $data['noon'] = 1;
	            }
	            break;
	    }
	    $data['noon'] = 2; //统一下午配送，对前面的赋值直接覆盖
	    return $data;
	}
}