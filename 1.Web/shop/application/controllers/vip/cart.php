<?php
/**
 * 购物车模块
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-4
 */
require_once APPPATH.'controllers/base.php';
class Cart extends Base{
	
    //购物车
	public function index(){
	    //购物车信息
		$cart = json_decode($this->input->cookie('vipcart', true), true);
		//用户信息
		$vipInfo = $this->session->userdata('vipinfo');
		$discount = $this->Base_model->getOne("SELECT discount FROM vip_users WHERE id = {$vipInfo['uid']}");
		$discount = $discount > 0 ? $discount / 100 : 1;
		//格式化数据
		$data = array();
		if( ! empty($cart)){
		    foreach($cart as $k => $v){
		        $product_id = intval($k);
		        $product = $this->Base_model->getRow("SELECT title,price,is_del FROM vip_products WHERE id = {$product_id}");
		        if(empty($product) || $product['is_del'] == 1){
		            unset($cart[$product_id]);
		            continue;
		        }
		        $product['price'] = $product['price'] * $discount;
		        $product['amount'] = max(1, intval($v['amount']));
		        $data[$product_id] = $product;
		        $cart[$product_id]['price'] = $product['price'];
		        $cart[$product_id]['amount'] = max(1, intval($v['amount']));
		    }
		    //更新购物车
		    $this->input->set_cookie('vipcart', json_encode($cart), '86400');
		    if(false != $vipInfo){
		        $this->Cart_model->vipSyncToDb($vipInfo['uid'], $cart);
		    }
		}
		$this->load->view('vip/cart_index', array('data'=>$data));
	}
	
	//购物车管理
	public function manage($action = ''){
		//仅限Ajax访问
		$this->input->is_ajax_request() || show_404();
		//用户信息
		$vipInfo = $this->session->userdata('vipinfo');
		$discount = $this->Base_model->getOne("SELECT discount FROM vip_users WHERE id = {$vipInfo['uid']}");
		$discount = $discount > 0 ? $discount / 100 : 1;
		//默认状态
		$err['err_no'] = 1000;
		$err['err_msg'] = parent::$errorType[1000];
		//接收数据
		$product_id = max(0, intval($this->input->post('product_id')));
		$amount = max(1, intval($this->input->post('amount')));
		//非删除则检查商品信息
		if(in_array($action, array('add','upd'))){
		    //商品信息
		    $product = $this->Base_model->getRow("SELECT id,title,price,thumb FROM vip_products WHERE id = {$product_id}");
		    $product['price'] = $product['price'] * $discount;
		    if(empty($product)){
		        $err['err_no'] = 1003;
		        $err['err_msg'] = parent::$errorType[1003];
		        exit(json_encode($err));
		    }
		}
		//购物车增删改
		switch($action){
			//添加商品
			case 'add':
				if( ! $this->input->cookie('vipcart', true)){
					$cart[$product_id] = array('price'=>$product['price'], 'amount'=>$amount);
				}else{
					$cart = json_decode($this->input->cookie('vipcart', true), true);
					if(empty($cart[$product_id])){
						$cart[$product_id] = array('price'=>$product['price'], 'amount'=>$amount);
					}else{
						$cart[$product_id]['price'] = $product['price'];
						$cart[$product_id]['amount'] += $amount;
					}
				}
				//更新购物车
				$this->input->set_cookie('vipcart', json_encode($cart), '86400');
				if(false != $vipInfo){
					$this->Cart_model->vipSyncToDb($vipInfo['uid'], $cart);
				}
				$err['err_no'] = 0;
				$err['err_msg'] = parent::$errorType[0];
				$err['results'] = $this->_cartInfo($cart);
				break;
			//更新商品
			case 'upd':
				if($this->input->cookie('vipcart',true)){
					$cart = json_decode($this->input->cookie('vipcart', true), true);
					if( ! empty($cart[$product_id])){
					    $cart[$product_id]['price'] = $product['price'];
					    $cart[$product_id]['amount'] = $amount;
						//更新购物车
						$this->input->set_cookie('vipcart', json_encode($cart), '86400');
						if(false != $vipInfo){
							$this->Cart_model->vipSyncToDb($vipInfo['uid'], $cart);
						}
						$err['err_no'] = 0;
						$err['err_msg'] = parent::$errorType[0];
						$err['results'] = $this->_cartInfo($cart);
						$err['results']['price'] = $product['price'];
						$err['results']['single_price'] = sprintf('%.2f', $product['price'] * $amount);
					}
				}
				break;
			//删除商品
			case 'del':
				if(false != $this->input->cookie('vipcart', true)){
					$cart = json_decode($this->input->cookie('vipcart', true), true);
					if( ! empty($cart[$product_id])){
						unset($cart[$product_id]);
						//更新购物车
						$this->input->set_cookie('vipcart', json_encode($cart), '86400');
						if(false != $vipInfo){
							$this->Cart_model->vipSyncToDb($vipInfo['uid'], $cart);
						}
						$err['err_no'] = 0;
						$err['err_msg'] = parent::$errorType[0];
						$err['results'] = $this->_cartInfo($cart);
					}
				}
				break;
			//清空购物车
			case 'empty':
			    if(false != $this->input->cookie('vipcart', true)){
			        //更新购物车
			    	$this->input->set_cookie('vipcart', '', '');
				    if(false != $vipInfo){
						$this->Cart_model->vipSyncToDb($vipInfo['uid']);
					}
                    $err['err_no'] = 0;
                    $err['err_msg'] = parent::$errorType[0];
			    }
			    break;
		}
		exit(json_encode($err));
	}
	
	//购物车合计
	private function _cartInfo($cart = array()){
	    $data['total_price'] = '0.00'; //总价
	    $data['total_number'] = 0; //总件数
	    $data['total_sort'] = 0; //总种类数
	    if(empty($cart)){
	        return $data;
	    }
	    foreach($cart as $v){
	        $data['total_sort'] += 1;
	        $data['total_number'] += $v['amount'];
	        $data['total_price'] += $v['price'] * $v['amount'];
	    }
	    $data['total_price'] = sprintf('%.2f', $data['total_price']);
	    return $data;
	}
}