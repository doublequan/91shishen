<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/base.php';

class order extends Base {

    public function __construct(){
        parent::__construct();
        $this->load->model('Order_model','mOrder');
    }
    
    public function add(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array('products','pay_type','delivery_type','store_id','address_id');
    		$fields = array('note','discount_id','cash_id','is_receipt','receipt_title','receipt_des','date_type','date_day','date_noon');
    		$this->checkParams('post',$must,$fields);
    		$params = $this->params;
    		//get user
    		$user = $this->mOrder->getSingle('users_session','sessionid',$params['sessionid'],'uid');
    		if( !($user && $user['uid']) ){
    			$ret = array('err_no'=>3001,'err_msg'=>'user is not login');
    			break;
    		}
    		$uid = intval($user['uid']);
    		//get user
    		$user = $this->mOrder->getSingle('users','id',$uid);
    		if( !$user ){
    			$ret = array('err_no'=>3001,'err_msg'=>'user is not login');
    			break;
    		}
    		//check site_id
    		$site_id = intval($params['site_id']);
    		$site = $this->mOrder->getSingle('sites','id',$site_id);
    		if( !$site ){
    			$ret = array('err_no'=>3002,'err_msg'=>'site is not exists');
    			break;
    		}
    		//check products
    		$products = json_decode($params['products'],true);
    		if( empty($products) ){
    			$ret = array('err_no'=>3003,'err_msg'=>'products is empty');
    			break;
    		}
    		$removed = array();
    		$details = array();
    		$minusMap = array();
    		$price_total = 0;
    		$shake_free = false;
    		$shake_free_price = 0;
    		foreach ( $products as $row ){
    			if( isset($row['product_id']) && isset($row['amount']) ){
    				$t = $this->mOrder->getSingle('products','id',$row['product_id']);
    				$product_stock = $this->mOrder->getStock($row['product_id'],$params['site_id']);
    				if( $t && $product_stock ){
    					$minus = 0;
    					if( $product_stock['stock']==0 ){
    						continue;
    					}
    					if( $product_stock['stock']>0 ){
    						$minus = $row['amount']>$product_stock['stock'] ? $product_stock['stock'] : $row['amount'];
    						$row['amount'] = $minus;
    					}
    					if( $minus ){
    						$minusMap[$product_stock['id']] = $minus;
    					}
    					$detail_total = $row['amount']*$t['price'];
    					$removed[$row['product_id']] = $detail_total;
    					$details[] = array(
    						'uid'			=> $uid,
    						'product_id'	=> $row['product_id'],
    						'product_name'	=> $t['title'],
    						'thumb'			=> $t['thumb'],
    						'amount'		=> $row['amount'],
    						'price_single'	=> $t['price'],
    						'price_total'	=> $detail_total,
    					);
    					$price_total += $detail_total;
    					if( !$shake_free && $t['price']<=3 ){
    						$shake_free_price = $t['price'];
    						$shake_free = true;
    					}
    				}
    			}
    		}
    		if( empty($details) ){
    			$ret = array('err_no'=>3004,'err_msg'=>'products is error');
    			break;
    		}
    		//check pay_type
    		if( !in_array($params['pay_type'], array(1,2,3)) ){
    			$ret = array('err_no'=>3005,'err_msg'=>'pay_type is not exists');
    			break;
    		}
    		//check get by self
    		if( $params['delivery_type']==0 ){
    			if( $params['store_id']==0 ){
    				$ret = array('err_no'=>3006,'err_msg'=>'store_id is zero');
    				break;
    			}
    			$store = $this->mOrder->getSingle('stores','id',$params['store_id']);
    			if( !$store ){
    				$ret = array('err_no'=>3007,'err_msg'=>'store is not exists');
    				break;
    			}
    			$params['address_id'] = 0;
    		} else {
    			if( $params['address_id']==0 ){
    				$ret = array('err_no'=>3008,'err_msg'=>'address_id is zero');
    				break;
    			}
    			$address = $this->mOrder->getSingle('users_address','id',$params['address_id']);
    			if( !$address ){
    				$ret = array('err_no'=>3009,'err_msg'=>'address is not exists');
    				break;
    			}
    			$params['store_id'] = 0;
    		}
    		//check coupon: discount first, cash second
    		$t = time();
    		$discount_id = intval($params['discount_id']);
    		$price_discount = 0;
    		$discount = 0;
    		//will not use discount coupon from 2014.10.02
    		/**
    		$discount_id = false;
    		if( $discount_id ){
    			$discount = $this->mOrder->getSingle('users_coupon','id',$params['discount_id']);
    			if( $discount && $discount['coupon_balance']>0 ){
    				if( ($discount['start']==0 || $discount['start']<=$t) && ($discount['end']==0 || $discount['end']>=$t) ){
    					if( $discount['coupon_limit']==0 || $price_total>$discount['coupon_limit'] ){
    						$price_discount = $discount['coupon_balance'];
    					}
    				}
    			}
    		}
    		*/
    		$cash_id = intval($params['cash_id']);
    		$price_cash = 0;
    		$cash = array();
    		if( $cash_id ){
    			$cash = $this->mOrder->getSingle('users_coupon','id',$params['cash_id']);
    			if( $cash && $cash['coupon_balance']>0 ){
    				if( ($cash['start']==0 || $cash['start']<=$t) && ($cash['end']==0 || $cash['end']>=$t) ){
	    				if( $cash['coupon_limit']==0 || $price_total>$cash['coupon_limit'] ){
	    					$price_cash = $price_total>=$cash['coupon_balance'] ? $cash['coupon_balance'] : $price_total;
	    				}
	    			}
    			}
    		}
    		//Create Order Parameters
    		$order_id = createOrderID($uid);
    		$time = time();
    		$current_discount = min(100,max(1,$user['discount'])); 
    		$price_discount = round(((100-$current_discount)/100)*$price_total,2);
    		$price_minus = $shake_free_price;
    		$price_total_left = $price_total-$price_discount-$price_cash-$price_minus;
    		$price_shipping = $params['delivery_type']==0 || $price_total_left>=SHIPPING_FREELIMIT ? 0 : SHIPPING_FEE;
    		$price = $price_total-$price_discount-$price_cash+$price_shipping;
    		//get order date
    		$order_date_types = getConfig('order_date_types');
    		$date_type = intval($params['date_type']);
    		$date_type = array_key_exists($date_type,$order_date_types) ? $date_type : 1;
    		$date_day = $params['date_day'] ? date('Y-m-d',strtotime($params['date_day'])) : '';
    		$date_noon = intval($params['date_noon']);
    		if( $date_type!=4 ){
    			$date_day = getOrderDay($date_type);
    			if( !$date_day ){
    				$date_day = '';
    			}
    		}
    		//create order info
    		$orderParams = array(
    			'id'			=> $order_id,
    			'uid'			=> $uid,
    			'site_id'		=> $params['site_id'],
    			'pay_type'		=> $params['pay_type'],
    			'order_status'	=> $params['pay_type']==1 ? 21 : 0,
    			'delivery_type'	=> $params['delivery_type'],
    			'date_type'		=> $date_type,
    			'date_day'		=> $date_day,
    			'date_noon'		=> $date_noon,
    			'note' 			=> $params['note'],
    			'price' 		=> $price,
    			'price_total' 	=> $price_total,
    			'price_discount'=> $price_discount,
    			'price_cash'	=> $price_cash,
    			'price_minus'	=> $price_minus,
    			'price_shipping'=> $price_shipping,
    			'is_receipt'	=> $params['is_receipt'] ? 1 : 0,
    			'receipt_title'	=> $params['receipt_title'],
    			'receipt_des'	=> $params['receipt_des'],
    			'create_time'	=> $time,
    		);
    		//set order as paid if PRICE==0
    		if( $price==0 ){
    			$orderParams['order_status'] = 1;
    			$orderParams['pay_status'] = 1;
    		}
    		//set default deal store
    		$orderParams['deal_store_id'] = $site['default_store'];
    		if( $params['delivery_type']==0 ){
    			$orderParams['store_id']	= $params['store_id'];
    			$orderParams['prov']		= '';
    			$orderParams['city']		= '';
    			$orderParams['district']	= '';
    			$orderParams['street']	= '';
    			$orderParams['address']		= '';
    			$orderParams['zip']			= '';
    			$orderParams['receiver']	= '';
    			$orderParams['mobile']		= '';
    			$orderParams['tel']			= '';
    		} else {
    			$orderParams['store_id']	= 0;
    			//get area data
    			$prov = $this->mOrder->getSingle('areas','id',$address['prov']);
    			$city = $this->mOrder->getSingle('areas','id',$address['city']);
    			$district = $this->mOrder->getSingle('areas','id',$address['district']);
    			$street = $this->mOrder->getSingle('areas','id',$address['street']);
    			$orderParams['prov']		= isset($prov['name']) ? $prov['name'] : '';
    			$orderParams['city']		= isset($city['name']) ? $city['name'] : '';
    			$orderParams['district']	= isset($district['name']) ? $district['name'] : '';
    			$orderParams['street']		= isset($street['name']) ? $street['name'] : '';
    			$orderParams['address']		= $address['address'];
    			$orderParams['zip']			= $address['zip'];
    			$orderParams['receiver']	= $address['receiver'];
    			$orderParams['mobile']		= $address['mobile'];
    			$orderParams['tel']			= $address['tel'];
    		}
    		//Create Order Details Parameters
    		foreach( $details as &$row ){
    			$row['order_id']	= $order_id;
    			$row['create_time']	= $time;
    		}
    		unset($row);
    		//Create Order Action
    		$action = array(
    			'order_id'		=> $orderParams['id'],
    			'status'		=> 0,
    			'des'			=> '客户下单',
    			'is_show'		=> 1,
    			'create_eid'	=> 0,
    			'create_name'	=> '客户',
    			'create_time'	=> time(),
    		);
    		if( !$this->mOrder->addOrder($orderParams, $details, $action, $cash, $minusMap) ){
    			$ret = array('err_no'=>3010,'err_msg'=>'add order failure');
    			break;
    		}
    		//update or remove shopping cart after add order success
    		$cart = $this->mOrder->getSingle('users_cart','uid',$uid);
    		$content = array();
    		if( $cart && isset($cart['content']) ){
    			$t = json_decode($cart['content'],true);
    			$products = $t['products'];
    			$price = $t['price'];
    			if( $products ){
    				foreach ( $products as $product_id=>$row ){
    					if( isset($removed[$product_id]) ){
    						$product = $this->mOrder->getSingle('products','id',$product_id);
    						$price -= $product['price'];
    						unset($products[$product_id]);
    					}
    				}
    			}
    			if( $products ){
    				$content = array(
    					'products'	=> $products,
    					'price'		=> $price,
    				);
    			}
    		}
    		if( $content ){
    			$data = array(
    				'content'		=> addslashes(json_encode($content)),
    				'create_time'	=> time(),
    			);
    			$this->mOrder->update('users_cart',$data,array('uid'=>$uid));
    		} else {
    			$this->mOrder->delete('users_cart',array('uid'=>$uid),true);
    		}
    		$orderParams['create_time'] = date('Y-m-d H:i:s',$orderParams['create_time']);
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>$orderParams);
    	} while(0);
    	$this->output($ret);
    }
    
    public function one(){
        $ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array('site_id','product_id','amount');
    		$this->checkParams('post',$must);
    		$params = $this->params;
    		//get user
    		$user = $this->mOrder->getSingle('users_session','sessionid',$params['sessionid'],'uid');
    		if( !($user && $user['uid']) ){
    			$ret = array('err_no'=>3001,'err_msg'=>'user is not login');
    			break;
    		}
    		$uid = intval($user['uid']);
    		//check site_id
    		$site_id = intval($params['site_id']);
    		$site = $this->mOrder->getSingle('sites','id',$site_id);
    		if( !$site ){
    			$ret = array('err_no'=>3002,'err_msg'=>'site is not exists');
    			break;
    		}
    		//get product
    		$product = $this->mOrder->getSingle('products','id',$params['product_id']);
    		if( !$product || $product['is_del']==1 ){
    			$ret = array('err_no'=>3003,'err_msg'=>'products is not exists');
    			break;
    		}
    		//get user prefer
    		$prefer = $this->mOrder->getSingle('users_prefer','uid',$uid);
    		if( !$prefer ){
    			$ret = array('err_no'=>3004,'err_msg'=>'user prefer is not exists');
    			break;
    		}
    		if( !in_array($prefer['pay_type'], array(1,2,3)) ){
    			$ret = array('err_no'=>3005,'err_msg'=>'pay_type is not exists');
    			break;
    		}
    		if( !in_array($prefer['delivery_type'], array(0,1)) ){
    			$ret = array('err_no'=>3006,'err_msg'=>'delivery_type is not exists');
    			break;
    		}
    		if( $prefer['delivery_type']==0 ){
    			if( !$params['store_id'] ){
        			$ret = array('err_no'=>3007,'err_msg'=>'store_id is zero');
        			break;
        		}
        		$store = $this->mOrder->getSingle('stores','id',$params['store_id']);
        		if( !$store ){
        			$ret = array('err_no'=>3008,'err_msg'=>'store is not exists');
        			break;
        		}
    		} else {
    			$arr = array('prov','city','district','zip','address','receiver');
    			$flag = true;
    			foreach( $arr as $v ){
    				if( $prefer[$v]=='' ){
    					$ret = array('err_no'=>3009,'err_msg'=>'address parameter '.$v.' is empty');
    					$flag = false;
    					break;
    				}
    			}
    			if( !$flag ){
    				break;
    			}
    			if( $prefer['tel']=='' && $prefer['mobile']=='' ){
    				$ret = array('err_no'=>3010,'err_msg'=>'tel and mobile both empty');
    				break;
    			}
    		}
    		//Create Order Parameter
    		$order_id = createOrderID($uid);
    		$time = time();
    		$price_total = $product['price']*$params['amount'];
    		$price_shipping = $price_total>=SHIPPING_FREELIMIT ? 0 : SHIPPING_FEE;
    		$price = $price_total+$price_shipping;
    		$orderParams = array(
    			'id'			=> $order_id,
    			'uid'			=> $uid,
    			'site_id'		=> $params['site_id'],
    			'pay_type'		=> $prefer['pay_type'],
    			'order_status'	=> 0,
    			'delivery_type'	=> $prefer['delivery_type'],
    			'price' 		=> $price,
    			'price_total' 	=> $price_total,
    			'price_discount'=> 0,
    			'price_cash'	=> 0,
    			'price_shipping'=> $price_shipping,
    			'is_receipt'	=> $prefer['is_receipt'] ? 1 : 0,
    			'receipt_title'	=> $prefer['receipt_title'],
    			'receipt_des'	=> $prefer['receipt_des'],
    			'create_time'	=> $time,
    		);
    		if( $prefer['delivery_type']==0 ){
    			$orderParams['store_id']	= $prefer['store_id'];
    			$orderParams['prov']		= '';
    			$orderParams['city']		= '';
    			$orderParams['district']	= '';
    			$orderParams['address']		= '';
    			$orderParams['zip']			= '';
    			$orderParams['receiver']	= '';
    			$orderParams['mobile']		= '';
    			$orderParams['tel']			= '';
    		} else {
    			$orderParams['store_id']	= 0;
    			$orderParams['prov']		= $prefer['prov'];
    			$orderParams['city']		= $prefer['city'];
    			$orderParams['district']	= $prefer['district'];
    			$orderParams['address']		= $prefer['address'];
    			$orderParams['zip']			= $prefer['zip'];
    			$orderParams['receiver']	= $prefer['receiver'];
    			$orderParams['mobile']		= $prefer['mobile'];
    			$orderParams['tel']			= $prefer['tel'];
    		}
    		//Create OrderDetail Parameter 
    		$details = array(
    			array(
    				'order_id'		=> $order_id,
    				'product_id'	=> $params['product_id'],
    				'product_name'	=> $product['title'],
    				'thumb'			=> $product['thumb'],
    				'amount'		=> $params['amount'],
    				'price_single'	=> $product['price'],
    				'price_total'	=> $price_total,
    				'create_time'	=> $time,
    			),
    		);
    		if( !$this->mOrder->addOrder($orderParams, $details) ){
    			$ret = array('err_no'=>3011,'err_msg'=>'add order failure');
    			break;
    		}
    		$orderParams['create_time'] = date('Y-m-d H:i:s',$orderParams['create_time']);
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>$orderParams);
    	} while(0);
    	$this->output($ret);
    }
}