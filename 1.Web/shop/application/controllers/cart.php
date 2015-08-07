<?php
/**
 * 购物车模块
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-4
 */
require_once 'base.php';
class Cart extends Base{
	
	private $freeMap = array();
	
	private $paid = 0;
		
	public function __construct(){
		parent::__construct();
		//获取满59可赠送商品
		$this->load->model('Product_model', 'mProduct');
		$res = $this->mProduct->getSpecialProducts(7);
		if( $res ){
			foreach ( $res as $row ){
				$this->freeMap[$row['id']] = $row;
			}
		}
		//计算购物车已有商品价格
		$cart = json_decode($this->input->cookie('cart',true),true);
		if( $cart ){
			$freeNum = 0;
			foreach( $cart as $product_id=>$row ){
				$p = $this->Base_model->getSingle('products','id',$product_id);
				if( !isset($this->freeMap[$product_id]) || $freeNum>=2 ){
					$this->paid += $p['price']*$row['amount'];
				} else {
					$freeNum++;
					$this->paid += $p['price']*($row['amount']-1);
				}
			}
		}
	}
	
	//购物车
	public function index(){
	    //购物车信息
		$cart = json_decode($this->input->cookie('cart', true), true);
		//用户信息
		$userInfo = $this->session->userdata('userinfo');
		//格式化数据
		$data = array();
		//正常商品
		if( $cart ){
			$freeNum = 0;
		    foreach($cart as $k => $v){
		        $product_id = intval($k);
		        $amount = max(1, intval($v['amount']));
		        $product = $this->Base_model->getRow("SELECT title,price,thumb,is_del FROM products WHERE id = {$product_id}");
		        $stock = $this->Base_model->getOne("SELECT stock FROM products_site WHERE site_id = ".SITEID." AND product_id = {$product_id}");
		        //加入到免费商品
		        $product['free'] = false;
		        $product['free_label'] = '';
		        if( $this->paid>=59 && $freeNum<2 && isset($this->freeMap[$product_id]) ){
		        	$freeNum++;
		        	$product['free'] = true;
		        	$product['free_label'] = '【<font color="red">单笔订单满59元，免费商品任选2份</font>，<a href="/special/give" target="_blank"><font color="blue">查看更多</font></a>】';
		        }
		        if(empty($product) || $product['is_del'] == 1 || empty($stock) ){
		            unset($cart[$product_id]);
		            continue;
		        }
		        if($stock > 0){
		            $amount = min($amount, $stock);
		        }
		        $product['amount'] = $amount;
		        $data[$product_id] = $product;
		        $cart[$product_id]['price'] = $product['price'];
		        $cart[$product_id]['amount'] = $product['amount'];
		    }
		    //新疆阿克苏冰糖心苹果箱装（5.5kg），原价80，现价66促销
		    $pid = 3134;
		    if( $freeNum==0 && isset($cart[$pid]) ){
		    	$p = $this->Base_model->getSingle('products','id',$pid,'price');
		    	if( $this->paid-$p['price']>=59 ){
			    	$data[$pid]['minus'] = 14*$cart[$pid]['amount'];
			    	$data[$pid]['free_label'] = '【<font color="red">单笔订单满59元，不领取赠品，冰糖心苹果每箱立减14元!</font>】';
		    	}
		    }
		    //更新购物车
		    $this->input->set_cookie('cart', json_encode($cart), '86400');
		    if(false != $userInfo){
		        $this->Cart_model->syncToDb($userInfo['uid'], $cart);
		    }
		}
		$this->load->view('cart_index', array('data'=>$data));
	}
	
	//购物车管理
	public function manage($action = ''){
		//仅限Ajax访问
		$this->input->is_ajax_request() || show_404();
		//用户信息
		$userInfo = $this->session->userdata('userinfo');
		//默认状态
		$err['err_no'] = 1000;
		$err['err_msg'] = parent::$errorType[1000];
		//接收数据
		$product_id = max(0, intval($this->input->post('product_id')));
		$product_ids = $this->input->post('product_ids');
		$amount = max(1, intval($this->input->post('amount')));
		//非删除则检查商品信息
		if(in_array($action, array('add','upd'))){
		    //商品信息
		    $product = $this->Base_model->getRow("SELECT title,price,thumb,is_del FROM products WHERE id = {$product_id}");
		    $stock = $this->Base_model->getRow("SELECT stock FROM products_site WHERE site_id = ".SITEID." AND product_id = {$product_id}");
            if(empty($product) || $product['is_del'] == 1 || empty($stock)){
		        $err['err_no'] = 1003;
		        $err['err_msg'] = parent::$errorType[1003];
		        exit(json_encode($err));
		    }
		    //库存信息
		    $cart = json_decode($this->input->cookie('cart', true), true);
		    $curr_num = 0;
		    if( ! empty($cart[$product_id])){
		        $curr_num = max(1, intval($cart[$product_id]['amount']));
		    }
		    if(
		        ($action == 'add' && $stock['stock'] >= 0 && $stock['stock'] < $amount + $curr_num) ||
		        ($action == 'upd' && $stock['stock'] >= 0 && $stock['stock'] < $amount)
		    ){
		        $err['err_no'] = 1004;
		        exit(json_encode($err));
		    }
		}
		//批量删除检查商品ID数组
		if($action == 'pdel' && empty($product_ids)){
		    exit(json_encode($err));
		}
		//购物车增删改
		switch($action){
			//添加商品
			case 'add':
				if( ! $this->input->cookie('cart', true)){
					$cart[$product_id] = array('price'=>$product['price'], 'amount'=>$amount);
				}else{
					$cart = json_decode($this->input->cookie('cart', true), true);
					if(empty($cart[$product_id])){
						$cart[$product_id] = array('price'=>$product['price'], 'amount'=>$amount);
					}else{
						$cart[$product_id]['price'] = $product['price'];
						$cart[$product_id]['amount'] += $amount;
					}
				}
				//更新购物车
				$this->input->set_cookie('cart', json_encode($cart), '86400');
				if(false != $userInfo){
					$this->Cart_model->syncToDb($userInfo['uid'], $cart);
				}
				$err['err_no'] = 0;
				$err['err_msg'] = parent::$errorType[0];
				$err['results'] = $this->_cartInfo($cart);
				break;
			//更新商品
			case 'upd':
				if($this->input->cookie('cart',true)){
					$cart = json_decode($this->input->cookie('cart', true), true);
					if( ! empty($cart[$product_id])){
					    $cart[$product_id]['price'] = $product['price'];
					    $cart[$product_id]['amount'] = $amount;
						//更新购物车
						$this->input->set_cookie('cart', json_encode($cart), '86400');
						if(false != $userInfo){
							$this->Cart_model->syncToDb($userInfo['uid'], $cart);
						}
						$err['err_no'] = 0;
						$err['err_msg'] = parent::$errorType[0];
						$err['results'] = $this->_cartInfo($cart);
						$err['results']['price'] = $product['price'];
						$err['results']['single_price'] = sprintf('%.2f', $err['results']['total_single'][$product_id]);
					}
				}
				break;
			//删除商品
			case 'del':
				if(false != $this->input->cookie('cart', true)){
					$cart = json_decode($this->input->cookie('cart', true), true);
					if( ! empty($cart[$product_id])){
						unset($cart[$product_id]);
						//更新购物车
						$this->input->set_cookie('cart', json_encode($cart), '86400');
						if(false != $userInfo){
							$this->Cart_model->syncToDb($userInfo['uid'], $cart);
						}
						$err['err_no'] = 0;
						$err['err_msg'] = parent::$errorType[0];
						$err['results'] = $this->_cartInfo($cart);
					}
				}
				break;
			//批量删除
			case 'pdel':
			    if(false != $this->input->cookie('cart', true)){
			        $cart = json_decode($this->input->cookie('cart', true), true);
			        foreach($product_ids as $v){
			            if( ! empty($cart[$v])){
			                unset($cart[$v]);
			            }
			        }
			        //更新购物车
			        $this->input->set_cookie('cart', json_encode($cart), '86400');
			        if(false != $userInfo){
			            $this->Cart_model->syncToDb($userInfo['uid'], $cart);
			        }
			        $err['err_no'] = 0;
			        $err['err_msg'] = parent::$errorType[0];
			        $err['results'] = $this->_cartInfo($cart);
			    }
			    break;
			//清空购物车
			case 'empty':
			    if(false != $this->input->cookie('cart', true)){
			        //更新购物车
			    	$this->input->set_cookie('cart', '', '');
				    if(false != $userInfo){
						$this->Cart_model->syncToDb($userInfo['uid']);
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
	    $data['total_single'] = array(); //单品总价
	    if(empty($cart)){
	        return $data;
	    }
	    $frees = array();
	    $freeNum = 0;
	    foreach($cart as $k => $v){
	    	$product_id = intval($k);
	    	if( $this->paid>=59 && $freeNum<2 && isset($this->freeMap[$product_id]) ){
	    		$freeNum++;
	    		$frees[$product_id] = $product_id;
	    	}
	    }
	    foreach($cart as $product_id=>&$v){
	        $data['total_sort'] += 1;
	        $data['total_number'] += $v['amount'];
	        $real_amount = $v['amount'];
	        if( isset($frees[$product_id]) ){
	        	$real_amount = $v['amount']-1;
	        }
	        $data['total_single'][$product_id] = sprintf('%.2f', $v['price'] * $real_amount);
	        $data['total_price'] += $data['total_single'][$product_id];
	    }
	    unset($v);
	    //新疆阿克苏冰糖心苹果箱装（5.5kg），原价80，现价66促销
	    $pid = 3134;
	    if( $freeNum==0 && isset($cart[$pid]) ){
	    	$p = $this->Base_model->getSingle('products','id',$pid,'price');
	    	if( $this->paid-$p['price']>=59 ){
	    		$data['total_single'][$pid] -= 14*$cart[$pid]['amount'];
	    		$data['total_price'] -= 14*$cart[$pid]['amount'];
	    	}
	    }
	    $data['total_price'] = sprintf('%.2f', $data['total_price']);
	    return $data;
	}
}