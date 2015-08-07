<?php
/**
 * 会员卡充值|在线支付
 * @author LiuPF<mail@phpha.com>
 * @date 2014-10-3
 */
require_once 'common.php';
class Payment extends Common{
	
	//在线充值
	public function index(){
		//会员信息
		$userInfo = $this->session->userdata('userinfo');
		//会员卡信息
		$card_no = $this->Base_model->getOne("SELECT cardno FROM users WHERE id = {$userInfo['uid']}");
		//加密令牌
		$hash_token = md5(getRandStr().time());
		$this->session->set_userdata('hash_token', $hash_token);
		$this->load->view('member/payment_index', array('card_no'=>$card_no,'hash_token'=>$hash_token));
	}
	
	//提交充值
	public function ajax_submit(){
	    //仅限Ajax访问
	    $this->input->is_ajax_request() || show_404();
	   //默认状态
	    $err['err_no'] = 1000;
	    $err['err_msg'] = parent::$errorType[1000];
	    //接收数据
	    $money = floatval($this->input->post('money')); //支付金额
	    $card_no = trim($this->input->post('card_no', true)); //会员卡卡号
	    $card_no = str_replace(' ', '', $card_no); //去除空格
	    $card_pw = trim($this->input->post('card_pw', true)); //会员卡密码
	    $hash_token = $this->input->post('hash_token', true); //TOKEN
	    //会员信息
	    $userInfo = $this->session->userdata('userinfo');
	    //检查令牌
	    if(empty($hash_token) || $hash_token != $this->session->userdata('hash_token')){
	        $err['err_no'] = 1001;
	        $err['err_msg'] = parent::$errorType[1001];
	        exit(json_encode($err));
	    }
	    //检查参数
	    if(empty($money) || empty($card_no) || empty($card_pw)){
	        $err['err_no'] = 1002;
	        $err['err_msg'] = parent::$errorType[1002];
	        exit(json_encode($err));
	    }
	    //检查金额
	    if($money < 10 && $money > 50000){
	        $err['err_no'] = 1003;
	        $err['err_msg'] = parent::$errorType[1003];
	        exit(json_encode($err));
	    }
	    //启用事务
	    $this->db->trans_begin();
	    //创建充值单
	    $data1['id'] = createBusinessID('PAY');
	    $data1['uid'] = $userInfo['uid'];
	    $data1['card_no'] = $card_no;
	    $data1['money'] = $money;
	    $data1['status'] = 0;
	    $data1['create_time'] = time();
	    $result1 = $this->Base_model->insert('users_charge', $data1);
	    //在线支付 TODO
	    $result2 = false;
	    
	    if($result1 && $result2){
	        //提交事务
	        $this->db->trans_commit();
	        //删除Token
	        $this->session->unset_userdata('hash_token');
	        //成功提示
	        $err['err_no'] = 0;
	        $err['err_msg'] = parent::$errorType[0];
	    }else{
	        //回滚事务
	        $this->db->trans_rollback();
	    }
	    exit(json_encode($err));
	}
	
	//在线支付|支付宝
	public function alipay(){
		//接收数据
		$pay_no = trim($this->input->post('pay_no', true)); //支付单号
		$money = floatval($this->input->post('money')); //支付金额
		$hash_token = $this->input->post('hash_token', true); //TOKEN
		//会员信息
		$userInfo = $this->session->userdata('userinfo');
		//检查令牌
		if(empty($pay_no) || empty($hash_token) || $hash_token != $this->session->userdata('hash_token')){
			$this->_redirect('参数错误，请返回重新进行支付！', base_url("member/order/detail/{$pay_no}"), false);
		}else{
			//订单信息
            $order = $this->Base_model->getRow("SELECT price,pay_status FROM orders WHERE id = '{$pay_no}'");
            //检查数据
			if(empty($order) || $order['pay_status'] != 0 || bccomp($order['price'], $money, 2) != 0){
				$this->_redirect('参数错误，请返回重新进行支付！', base_url("member/order/detail/{$pay_no}"), false);
			}else{
				//删除Token
				$this->session->unset_userdata('hash_token');
				//调用支付接口
				$this->load->library('alipay');
				$this->alipay->pay($money, $pay_no);
			}
		}
	}
	
	//在线支付|易宝会员卡
	public function yeepay($pay_no){
	    //支付单号
	    empty($pay_no) && show_404();
	    //订单信息
	    $data = $this->Base_model->getRow("SELECT id,price,pay_status FROM orders WHERE id = '{$pay_no}'");
	    //会员信息
	    $userInfo = $this->session->userdata('userinfo');
	    $data['card_no'] = $this->Base_model->getOne("SELECT cardno FROM users WHERE id = {$userInfo['uid']}");
	    //检查数据
	    if(empty($data) || $data['pay_status'] != 0){
	        show_404();
	    }
	    //加密令牌
	    $hash_token = md5(getRandStr().time());
	    $this->session->set_userdata('hash_token', $hash_token);
	    $this->load->view('member/payment_yeepay', array('data'=>$data,'hash_token'=>$hash_token));
	}
	
	//提交支付|易宝会员卡
	public function ajax_yeepay(){
	    //仅限Ajax访问
	    $this->input->is_ajax_request() || show_404();
	    //默认状态
	    $err['err_no'] = 1000;
	    $err['err_msg'] = parent::$errorType[1000];
	    //接收数据
	    $pay_no = trim($this->input->post('pay_no', true)); //支付单号
	    $money = floatval($this->input->post('money')); //支付金额
// 	    $card_no = trim($this->input->post('card_no', true)); //会员卡卡号
// 	    $card_no = str_replace(' ', '', $card_no); //去除空格
	    $card_pw = trim($this->input->post('card_pw', true)); //会员卡密码
	    $hash_token = $this->input->post('hash_token', true); //TOKEN
	    //会员信息
	    $userInfo = $this->session->userdata('userinfo');
	    $card_no = $this->Base_model->getOne("SELECT cardno FROM users WHERE id = {$userInfo['uid']}");
	    //检查参数
	    if(empty($pay_no) || empty($money) || empty($card_no) || empty($card_pw)){
	        $err['err_no'] = 1002;
	        $err['err_msg'] = parent::$errorType[1002];
	        exit(json_encode($err));
	    }
	    //检查令牌
	    if(empty($hash_token) || $hash_token != $this->session->userdata('hash_token')){
	        $err['err_no'] = 1001;
	        $err['err_msg'] = parent::$errorType[1001];
	        exit(json_encode($err));
	    }
        //订单信息
        $order = $this->Base_model->getRow("SELECT price,pay_status FROM orders WHERE id = '{$pay_no}'");
        //检查数据
        if(empty($order) || $order['pay_status'] != 0 || bccomp($order['price'], $money, 2) != 0){
            $err['err_no'] = 1003;
	        $err['err_msg'] = parent::$errorType[1003];
	        exit(json_encode($err));
        }else{
            //调用支付接口
            $this->load->library('yeepay');
            $result = $this->yeepay->cardpay($money, $pay_no, $card_no, $card_pw);
            if(isset($result['r2_code']) && $result['r2_code'] == '1'){
                //启用事务
                $this->db->trans_begin();
                //更新充值信息
                $payment['trade_no'] = $result['r12_externalid'];
                $payment['trade_time'] = strtotime($result['r10_trxtime']);
                $payment['is_return'] = 1;
                $payment['return_time'] = time();
                $result1 = $this->Base_model->update('orders_yeepay_card', $payment, array('order_id'=>$pay_no));
                //更新订单信息
                $order['order_status'] = 21;
                $order['pay_status'] = 1;
                $order['price_pay'] = floatval($result['r5_amount']);
                $result2 = $this->Base_model->update('orders', $order, array('id'=>$pay_no));
                //创建订单日志
                $log['order_id'] = $pay_no;
                $log['status'] = 1;
                $log['des'] = '会员卡成功支付￥'.$order['price'];
                $log['is_show'] = 1;
                $log['create_time'] = time();
                $result3 = $this->Base_model->insert('orders_action', $log);
                //处理结果
                if($result1 && $result2 && $result3){
                    //提交事务
                    $this->db->trans_commit();
                    //更新余额
                    //$this->_updCard($card_no, $userInfo['uid']);
                    //支付成功
                    $err['err_no'] = 0;
                    $err['err_msg'] = parent::$errorType[0];
                    //删除Token
                    $this->session->unset_userdata('hash_token');
                }else{
                    //回滚事务
                    $this->db->trans_rollback();
                }
            }else{
                $err['err_no'] = 1004;
                $err['err_msg'] = $result['r3_message'];
            }
        }
        exit(json_encode($err));
	}
	
	//充值成功
	public function success($pay_no = ''){
		empty($pay_no) && show_404();
		//支付信息
		$data = $this->Base_model->getRow("SELECT * FROM users_charge WHERE id = '{$pay_no}'");
		empty($data) && show_404();
		$this->load->view('member/payment_success', array('data'=>$data));
	}
}