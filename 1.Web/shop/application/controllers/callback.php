<?php
/**
 * 支付回调
 * @author LiuPF<mail@phpha.com>
 * @date 2014-10-5
 */
require_once 'base.php';
class Callback extends Base{
	
	//易宝网银支付|已停用
	private function yeepay(){
		//接收数据
		$data['r0_Cmd'] = $this->input->get('r0_Cmd');
		$data['r1_Code'] = $this->input->get('$r1_Code');
		$data['r2_TrxId'] = $this->input->get('$r2_TrxId');
		$data['r3_Amt'] = $this->input->get('r3_Amt');
		$data['r4_Cur'] = $this->input->get('r4_Cur');
		$data['r5_Pid'] = $this->input->get('r5_Pid');
		$data['r6_Order'] = $this->input->get('r6_Order');
		$data['r7_Uid'] = $this->input->get('r7_Uid');
		$data['r8_MP'] = $this->input->get('r8_MP');
		$data['r9_BType'] = $this->input->get('r9_BType');
		$data['hmac'] = $this->input->get('hmac');
		//校检签名
		$this->load->library('yeepay');
		$result = $this->yeepay->checkHmac($data);
		if(false == $result){
			show_404();
		}
		//当前时间
		$curr_time = time();
		//充值单号
		$pay_no = $data['r6_Order'];
		//充值信息
		$payment = $this->Base_model->getRow("SELECT money,status FROM users_charge WHERE id = '{$pay_no}'");
		if(empty($payment) || bccomp($payment['money'],$data['r3_Amt'],2) != 0){
			show_404();
		}
		//订单信息
		$order = $this->Base_model->getRow("SELECT price,pay_status FROM orders WHERE id = '{$pay_no}'");
		if(empty($order) || bccomp($order['price'],$data['r3_Amt'],2) != 0){
			show_404();
		}
		//处理结果
		if($data['r9_BType'] == '1'){
			//同步通知
			//跳转成功页面
			redirect(base_url("member/order/detail/{$pay_no}"));
		}elseif($data['r9_BType'] == '2' && $payment['status'] == 0 && $order['pay_status'] == 0){
			//异步通知
			//启用事务
			$this->db->trans_begin();
			//更新充值状态
			$data1 = array(
				'status' => 1,
				'trade_no' => $data['r2_TrxId'],
				'success_time' => $curr_time
			);
			$where1 = array('id' => $pay_no);
			$result1 = $this->Base_model->update('users_charge', $data1, $where1);
			//更新订单状态
			$data2 = array(
				'order_status' => 21,
				'pay_status' => 1,
			);
			$where2 = array('id' => $pay_no);
			$result2 = $this->Base_model->update('orders', $data2, $where2);
			//检查结果
			if($result1 && $result2){
				//提交事务
				$this->db->trans_commit();
				//成功提示
				exit('success');
			}else{
				//回滚事务
				$this->db->trans_rollback();
			}
		}
	}
	
	//易宝会员卡|异步
	public function card(){
	    //接收数据
	    $data = $this->input->post();
	    if(empty($data['pos_trade_no']) || empty($data['out_trade_no']) || empty($data['trade_amount'])){
	        show_404();
	    }
	    $order_id = trim($data['out_trade_no']);
	    $trade_no = trim($data['pos_trade_no']);
	    $order_price = floatval($data['trade_amount']);
	    //订单信息
	    $order = $this->Base_model->getRow("SELECT pay_status,order_status,price FROM orders WHERE id = '{$order_id}'");
	    //支付信息
	    $payment = $this->Base_model->getRow("SELECT is_notify,notify_time FROM orders_yeepay_card WHERE order_id = '{$order_id}' AND trade_no = '{$trade_no}'");
	    //检查数据
	    if(empty($order) || empty($payment) || bccomp($order['price'], $order_price, 2) != 0){
	    	show_404();
	    }
	    //启用事务
	    $this->db->trans_begin();
	    //执行的SQL语句数
	    $sql_num = 0;
	    //更新充值信息
	    $result1 = true;
	    if($payment['is_notify'] == 0 && $payment['notify_time'] == 0){
	        $data = array();
	    	$data['is_notify'] = 1;
	    	$data['notify_time'] = time();
	    	$result1 = $this->Base_model->update('orders_yeepay_card', $data, array('order_id'=>$order_id));
	    	$sql_num++;
	    }
	    //更新订单信息
	    $result2 = true;
	    if($order['order_status'] == 0 && $order['pay_status'] == 0){
	        $order = array();
	        $order['order_status'] = 21;
	        $order['pay_status'] = 1;
	        $order['price_pay'] = $order_price;
	        $result2 = $this->Base_model->update('orders', $order, array('id'=>$order_id));
	        $sql_num++;
	    }
	    //检查结果
	    if($sql_num > 0){
	        if($result1 && $result2){
	            //提交事务
	            $this->db->trans_commit();
	            //回写|易宝停止通知
	            exit('SUCCESS');
	        }else{
	            //回滚事务
	            $this->db->trans_rollback();
	        }
	    }
	}
	
	//支付宝
	public function alipay($type = ''){
		//返回类型
		in_array($type, array('return','notify')) || show_404();
		//接收数据
		$data = $type == 'return' ? $this->input->get() : $this->input->post();
		empty($data) && show_404();
		//校检签名
		$this->load->library('alipay');
		$check = $this->alipay->checkSign($data);
		if(false == $check){
			show_404();
		}
		//当前时间
		$curr_time = time();
		//充值单号
		$order_id = $data['out_trade_no'];
		//充值信息
		$payment = $this->Base_model->getRow("SELECT return_time,notify_time FROM orders_alipay WHERE order_id = '{$order_id}'");
		//订单信息
		$order = $this->Base_model->getRow("SELECT pay_status,order_status FROM orders WHERE id = '{$order_id}'");
		//检查数据
		if(empty($payment) || empty($order)){
			show_404();
		}
		//日志信息
		$paylog['type'] = $type;
		$paylog['params'] = http_build_query($data);
		Common_Log::add($paylog);
		//处理结果
		if($type == 'return'){
			//同步通知
		    $this->db->trans_begin(); //启用事务
		    $sql_num = 0; //执行的SQL语句数
		    //更新充值信息
		    $result1 = true;
		    if($payment['return_time'] == 0){
		        $result1 = $this->Base_model->update('orders_alipay', array('return_time'=>$curr_time), array('order_id'=>$order_id));
		        $sql_num++;
		    }
		    //更新订单信息
		    $result2 = true;
		    if($order['order_status'] == 0 && $order['pay_status'] == 0){
		        $order = array();
		        $order['order_status'] = 21;
		        $order['pay_status'] = 1;
		        $order['price_pay'] = floatval($data['total_fee']);
		        $result2 = $this->Base_model->update('orders', $order, array('id'=>$order_id));
		        $sql_num++;
		    }
		    //检查结果
		    if($sql_num > 0){
		        if($result1 && $result2){
		            //提交事务
		            $this->db->trans_commit();
		        }else{
		            //回滚事务
		            $this->db->trans_rollback();
		        }
		    }
			//跳转成功页面
			redirect(base_url("member/order/detail/{$order_id}"));
		}elseif(in_array($data['trade_status'], array('TRADE_FINISHED','TRADE_SUCCESS'))){
			//异步通知
			$this->db->trans_begin(); //启用事务
			//更新充值信息
			$result1 = true;
			if($payment['notify_time'] == 0){
			    $payment = array();
			    $payment['trade_no'] = $data['trade_no'];
			    $payment['buyer_id'] = $data['buyer_id'];
			    $payment['buyer_email'] = $data['buyer_email'];
			    $payment['create_time'] = strtotime($data['gmt_create']);
			    $payment['pay_time'] = strtotime($data['gmt_payment']);
			    $payment['notify_time'] = strtotime($data['notify_time']);
			    $payment['price'] = floatval($data['total_fee']);
			    $result1 = $this->Base_model->update('orders_alipay', $payment, array('order_id'=>$order_id));
			}
			//更新订单信息
			$result2 = true;
			if($order['order_status'] == 0 && $order['pay_status'] == 0){
			    $order = array();
			    $order['order_status'] = 21;
			    $order['pay_status'] = 1;
			    $order['price_pay'] = floatval($data['total_fee']);
			    $result2 = $this->Base_model->update('orders', $order, array('id'=>$order_id));
			}
			//创建订单日志
			$log['order_id'] = $order_id;
			$log['status'] = 1;
			$log['des'] = '支付宝成功支付￥'.$order['price_pay'];
			$log['is_show'] = 1;
			$log['create_time'] = $curr_time;
			$result3 = $this->Base_model->insert('orders_action', $log);
			//检查结果
			if($result1 && $result2 && $result3){
				//提交事务
				$this->db->trans_commit();
				//成功提示
				exit('success');
			}else{
				//回滚事务
				$this->db->trans_rollback();
			}
		}
	}
}