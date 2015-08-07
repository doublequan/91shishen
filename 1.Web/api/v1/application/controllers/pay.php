<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/base.php';

class pay extends Base {

    public function __construct(){
        parent::__construct();
        $this->load->model('Order_model','mOrder');
    }
    
    public function success(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array('order_id','pay_type','pay_price','pay_no','pay_time');
    		$this->checkParams('post',$must);
    		$params = $this->params;
    		//get order
    		$order = $this->mOrder->getSingle('orders','id',$params['order_id']);
    		if( !$order ){
    			$ret = array('err_no'=>3001,'err_msg'=>'order is not exists');
    			break;
    		}
    		if( $order['order_status']!=0 ){
    			$ret = array('err_no'=>3002,'err_msg'=>'order has paid or canceled');
    			break;
    		}
    		if( $order['price']!=$params['pay_price'] ){
    			$ret = array('err_no'=>3003,'err_msg'=>'price not match');
    			break;
    		}
    		//modify order to paid
    		$data = array(
    			'pay_type'		=> 2,
    			'pay_status'	=> 1,
    			'order_status'	=> 21,
    			'price_pay'		=> floatval($params['pay_price']),
    		);
    		$whereMap = array('id'=>$order['id']);
    		$this->mOrder->update('orders',$data,$whereMap);
    		//get alipay order record
    		$record = $this->mOrder->getSingle('orders_alipay','id',$params['order_id']);
    		$data = array(
    			'order_id'		=> $params['order_id'],
    			'price'			=> $params['pay_price'],
    			'return_time'	=> time(),
    		);
    		if( !$record ){
    			$this->mOrder->insert('orders_alipay', $data);
    		} else {
    			$this->mOrder->update('orders_alipay', $data, array('id'=>$record['id']));
    		}
    		//add new order action
    		if( $record ){
	    		$data = array(
	    			'order_id'		=> $order['id'],
	    			'status'		=> 1,
	    			'des'			=> '支付宝在线支付￥'.$order['price'],
	    			'is_show'		=> 1,
	    			'create_eid'	=> 0,
	    			'create_name'	=> '客户',
	    			'create_time'	=> time(),
	    		);
	    		$this->mOrder->insert('orders_action', $data);
    		}
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>array());
    	} while(0);
    	$this->output($ret);
    }
    
    public function alipay_notify(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array(
    			'trade_status',		//支付宝支付状态， TRADE_SUCCESS | TRADE_FINISHED
    			'out_trade_no',		//系统订单编号order_id
    			'trade_no',			//支付宝交易号
    		);
    		$fields = array(
    			'buyer_id',			//买家支付宝账号
    			'buyer_email',		//买家支付宝邮箱
    			'total_fee',		//订单总价
    			'notify_time',		//异步通知时间
    			'gmt_create',		//订单创建时间
    			'gmt_payment'		//订单支付时间
    		);
    		$this->checkParams('get',$must,$fields);
    		$params = $this->params;
    		if( $params['trade_status']=='TRADE_SUCCESS' || $params['trade_status']=='TRADE_FINISHED' ){
    			$order_id = $params['out_trade_no'];
	    		//update order
	    		$data = array(
	    			'pay_type'		=> 2,
	    			'pay_status'	=> 1,
	    			'order_status'	=> 21,
	    			'price_pay'		=> floatval($params['total_fee']),
	    		);
	    		$whereMap = array('id'=>$order_id);
	    		$this->mOrder->update('orders',$data,$whereMap);
	    		//get alipay order record
	    		$record = $this->mOrder->getSingle('orders_alipay','id',$params['order_id']);
	    		$data = array(
	    			'order_id'		=> $params['order_id'],
	    			'trade_no'		=> $params['trade_no'],
	    			'buyer_id'		=> $params['buyer_id'],
	    			'buyer_email'	=> $params['buyer_email'],
	    			'create_time'	=> strtotime($params['gmt_create']),
	    			'pay_time'		=> strtotime($params['gmt_payment']),
	    			'price'			=> $params['total_fee'],
	    			'notify_time'	=> $params['notify_time'],
	    		);
	    		if( !$record ){
	    			$this->mOrder->insert('orders_alipay', $data);
	    		} else {
	    			$this->mOrder->update('orders_alipay', $data, array('id'=>$record['id']));
	    		}
	    		//add new order action
	    		if( $record ){
	    			$data = array(
	    				'order_id'		=> $order_id,
	    				'status'		=> 1,
	    				'des'			=> '支付宝在线支付￥'.$params['total_fee'],
	    				'is_show'		=> 1,
	    				'create_eid'	=> 0,
	    				'create_name'	=> '客户',
	    				'create_time'	=> time(),
	    			);
	    			$this->mOrder->insert('orders_action', $data);
	    		}
    		}
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>array());
    	} while(0);
    	$this->output($ret);
    }
    
    public function yeepay_card(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array('order_id','card_num','card_pass','pay_price');
    		$this->checkParams('post',$must);
    		$params = $this->params;
    		//get order
    		$order = $this->mOrder->getSingle('orders','id',$params['order_id']);
    		if( !$order ){
    			$ret = array('err_no'=>3001,'err_msg'=>'order is not exists');
    			break;
    		}
    		if( $order['order_status']!=0 ){
    			$ret = array('err_no'=>3002,'err_msg'=>'order has paid or canceled');
    			break;
    		}
    		if( $order['price']!=$params['pay_price'] ){
    			$ret = array('err_no'=>3003,'err_msg'=>'price not match');
    			break;
    		}
    		//encryp class
    		include dirname(__FILE__).'/encrypt_class.php';
    		$rep = new Crypt3Des();
    		//default settings
    		$p0_cmd = 'BUYDIRECT';
    		$p1_merid = 'FA45255';
    		$p2_requestid = $order['id'];
    		$p3_amount = $order['price'];
    		$p4_currency = 'CNY';
    		$p8_url = API_DOMAIN.'v1/pay/yeepay_card_notify';
    		$p9_cardno = $params['card_num'];
    		$encrypt_card = $rep->encrypt($p9_cardno);
    		$p10_cardpwd = base64_decode($params['card_pass']);
    		$encrypt_cardpwd = $rep->encrypt($p10_cardpwd);
    		$p11_ordertime = date('YmdHis');
    		//create HMAC
    		$hmac = hash_hmac('md5',$p0_cmd.$p1_merid.$p2_requestid.$p3_amount.$p4_currency.$p8_url.$p9_cardno.$p10_cardpwd.$p11_ordertime,'0587Tsc81qkpo6IP7H8774Nk6d9Cr1b1ZL6t20G73v3tj4949KiN4sEuCzx6');
    		//send request
    		$data = array(
    			'p0_cmd'		=> $p0_cmd,
    			'p1_merid'		=> $p1_merid,
    			'p2_requestid'	=> $p2_requestid,
    			'p3_amount'		=> $p3_amount,
    			'p4_currency'	=> $p4_currency,
    			'p8_url'		=> $p8_url,
    			'p9_cardno'		=> $encrypt_card,
    			'p10_cardpwd'	=> $encrypt_cardpwd,
    			'p11_ordertime'	=> $p11_ordertime,
    			'hmac'			=> $hmac,
    		);
    		$ch = curl_init();
    		curl_setopt($ch, CURLOPT_URL, 'https://c.yeepay.com/prepay-interface/direct.action');
    		curl_setopt($ch, CURLOPT_TIMEOUT, 1);
    		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
    		curl_setopt($ch, CURLOPT_POST, 1);
    		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    		$str = curl_exec($ch);
    		if( curl_errno($ch) ){
    			$ret = array('err_no'=>4000,'err_msg'=>'yeepay request error');
    			break;
    		}
    		curl_close ($ch);
    		$result = array();
    		$arr = explode("\n",$str);
    		if( $arr ){
	    		foreach( $arr as $v ){
	    			$v = trim($v);
	    			if( $v ){
	    				$t = explode('=',$v);
	    				if( isset($t[0]) ){
	    					$result[$t[0]] = isset($t[1]) ? $t[1] : '';
	    				}
	    			}
	    		}
    		}
    		if( !($result && isset($result['r2_code']) && $result['r2_code']==1) ){
    			$ret = array('err_no'=>4001,'err_msg'=>'支付失败：'.$result['r3_message']);
    			break;
    		}
    		//get yeepay card record
    		$record = $this->mOrder->getSingle('orders_yeepay_card','id',$params['order_id']);
    		$data = array(
    			'order_id'		=> $params['order_id'],
    			'channel'		=> $result['r13_channel'],
    			'trade_type'	=> $result['r9_business'],
    			'trade_no'		=> $result['r12_externalid'],
    			'trade_time'	=> strtotime($result['r10_trxtime']),
    			'is_return'		=> 1,
    			'return_time'	=> time(),
    		);
    		if( !$record ){
    			$this->mOrder->insert('orders_yeepay_card', $data);
    		} else {
    			$this->mOrder->update('orders_yeepay_card', $data, array('id'=>$record['id']));
    		}
    		//modify order to paid
    		$data = array(
    			'pay_type'		=> 3,
    			'pay_status'	=> 1,
    			'order_status'	=> 21,
    			'price_pay'		=> floatval($result['r5_amount']),
    		);
    		$whereMap = array('id'=>$order['id']);
    		$this->mOrder->update('orders',$data,$whereMap);
    		//add new order action
    		$data = array(
    			'order_id'		=> $order['id'],
    			'status'		=> 1,
    			'des'			=> '会员卡成功支付￥'.$order['price'],
    			'is_show'		=> 1,
    			'create_eid'	=> 0,
    			'create_name'	=> '客户',
    			'create_time'	=> time(),
    		);
    		$this->mOrder->insert('orders_action', $data);
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>array());
    	} while(0);
    	$this->output($ret);
    }
    
    /**
     * what YeePay notify returns
     * @param pos_trade_no=0000923715
     * @param pos_no=
     * @param trade_amount=0.01
     * @param card_no=wC8nlHBaKjLBMlhWCbBGJ1mgJm3bEN9f
     * @param trade_time=Tue Oct 07 18:00:55 CST 2014
     * @param out_trade_no=20141007113551684322
     */
    public function yeepay_card_notify(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array(
    			'out_trade_no',		//系统订单编号order_id
    			'trade_time',		//交易时间
    		);
    		$this->checkParams('get',$must);
    		$params = $this->params;
    		$order_id = $params['out_trade_no'];
    		//get order
    		$order = $this->mOrder->getSingle('orders','id',$order_id);
    		if( !$order ){
    			$ret = array('err_no'=>3001,'err_msg'=>'order is not exists');
    			break;
    		}
    		$record = $this->mOrder->getSingle('orders_yeepay_card','order_id',$order_id);
    		if( !$record ){
    			$ret = array('err_no'=>3001,'err_msg'=>'order is not exists');
    			break;
    		}
    		$data = array(
    			'is_notify'		=> 1,
    			'notify_time'	=> time(),
    		);
    		$this->mOrder->update('orders_yeepay_card', $data, array('id'=>$record['id']));
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>array());
    	} while(0);
    	$this->output($ret);
    }
    
    public function yeepay_pos_notify(){
    	echo 'wait YeePay';
    }
}