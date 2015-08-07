<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/base.php';

class cart extends Base {
	
    public function __construct(){
        parent::__construct();
        $this->load->model('Base_model','mBase');
    }
    
    public function get(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$this->checkParams('post');
    		$params = $this->params;
    		//get user
    		$user = $this->mBase->getSingle('users_session','sessionid',$params['sessionid'],'uid');
    		if( !($user && $user['uid']) ){
    			$ret = array('err_no'=>3001,'err_msg'=>'user is not login');
    			break;
    		}
    		$uid = intval($user['uid']);
    		//get user's cart
    		$cart = $this->mBase->getSingle('users_cart','uid',$uid);
    		$products = array();
    		$price = 0;
    		$amount = 0;
    		if( isset($cart['content']) ){
    			$content = json_decode($cart['content'],true);
    			if( isset($content['products']) && !empty($content['products']) ){
		    		foreach ( $content['products'] as $product_id=>$row ){
		    			if( $product_id && isset($row['amount']) ){
			    			$product = $this->getProduct($product_id);
			    			if( $product ){
			    				$products[$product_id] = array(
			    					'product_id'	=> $product_id,
			    					'title'			=> $product['title'],
			    					'thumb'			=> $product['thumb'],
			    					'amount'		=> $row['amount'],
			    					'is_stock'		=> $product['stock']==0 || $row['amount']>$product['stock'] ? false : true,
			    					'price'			=> $product['price'],
			    				);
			    				$price += $row['amount']*$product['price'];
			    				$amount += $row['amount'];
			    			}
		    			}
		    		}
    			}
    		}
    		//return data
    		$results = array();
    		if( $products ){
    			$results = array(
	    			'products'	=> $products,
	    			'price'		=> $price,
    				'amount'	=> $amount,
	    		);
    		}
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>$results);
    	} while(0);
    	$this->output($ret);
    }
    
    public function sync(){
        $ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array('products');
    		$this->checkParams('post',$must);
    		$params = $this->params;
    		//get user
    		$user = $this->mBase->getSingle('users_session','sessionid',$params['sessionid'],'uid');
    		if( !($user && $user['uid']) ){
    			$ret = array('err_no'=>3001,'err_msg'=>'user is not login');
    			break;
    		}
    		$uid = intval($user['uid']);
    		//check products
    		$products = json_decode($params['products'],true);
    		if( empty($products) ){
    			$ret = array('err_no'=>3002,'err_msg'=>'products is empty');
    			break;
    		}
    		$content = array(
    			'products'	=> array(),
    			'price'		=> 0,
    		);
    		$cart = $this->mBase->getSingle('users_cart','uid',$uid);
    		if( $cart ){
    			$t = json_decode($cart['content'],true);
    			if( isset($t['products']) && isset($t['price']) ){
    				$content['products'] = $t['products'];
    				$content['price'] = $t['price'];
    			}
    		}
    		foreach ( $products as $row ){
    			if( isset($row['product_id']) && isset($row['amount']) ){
	    			$product = $this->getProduct($row['product_id']);
	    			if( $product ){
	    				$pid = $row['product_id'];
	    				$p = array(
	    					'amount'	=> $row['amount'],
	    					'price'		=> $product['price'],
	    				);
	    				if( isset($content['products'][$pid]) ){
	    					$content['price'] -= $content['products'][$pid]['amount']*$product['price'];
	    					unset($content['products'][$pid]);
	    				}
	    				$content['products'][$pid] = $p;
	    				$content['price'] += $row['amount']*$product['price'];
	    			}
    			}
    		}
    		//add to cart
    		if( !$cart ){
	    		$data = array(
	    			'uid'			=> $uid,
	    			'content'		=> addslashes(json_encode($content)),
	    			'create_time'	=> time(),
	    		);
	    		$this->mBase->insert('users_cart',$data);
    		} else {
    			$data = array(
    				'content'		=> addslashes(json_encode($content)),
    				'create_time'	=> time(),
    			);
    			$this->mBase->update('users_cart',$data,array('uid'=>$uid));
    		}
    		//return data
    		$results = array();
    		$price = 0;
    		$amount = 0;
    		if( $content['products'] ){
    			foreach( $content['products'] as $product_id=>$row ){
    				$product = $this->getProduct($product_id);
    				if( $product ){
	    				$results['products'][$product_id] = array(
    						'product_id'	=> $product_id,
    						'title'			=> $product['title'],
    						'thumb'			=> $product['thumb'],
    						'amount'		=> $row['amount'],
    						'is_stock'		=> $product['stock']==0 || $row['amount']>$product['stock'] ? false : true,
    						'price'			=> $row['price'],
	    				);
	    				$price += $row['amount']*$row['price'];
	    				$amount += $row['amount'];
    				}
    			}
    			$results['price'] = $price;
    			$results['amount'] = $amount;
    		}
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>$results);
    	} while(0);
    	$this->output($ret);
    }
    
    public function add(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array('product_id','amount');
    		$this->checkParams('post',$must);
    		$params = $this->params;
    		//get user
    		$user = $this->mBase->getSingle('users_session','sessionid',$params['sessionid'],'uid');
    		if( !($user && $user['uid']) ){
    			$ret = array('err_no'=>3001,'err_msg'=>'user is not login');
    			break;
    		}
    		$uid = intval($user['uid']);
    		//check product
    		$product = $this->getProduct($params['product_id']);
    		if( !$product ){
    			$ret = array('err_no'=>3002,'err_msg'=>'product is not exists');
    			break;
    		}
    		//get current user's cart
    		$products = array();
    		$price = 0;
    		$cart = $this->mBase->getSingle('users_cart','uid',$uid);
    		if( $cart ){
	    		$content = json_decode($cart['content'],true);
	    		if( $content ){
	    			$products = $content['products'];
	    			$price = $content['price'];
	    		}
    		}
    		if( isset($products[$params['product_id']]) ){
    			$products[$params['product_id']]['amount'] += $params['amount'];
    			$price += $product['price']*$params['amount'];
    		} else {
    			$products[$params['product_id']] = array(
    				'amount'		=> $params['amount'],
    				'price'			=> $product['price'],
    			);
    			$price += $product['price']*$params['amount'];
    		}
    		//add to cart
    		$content = array(
    			'products'	=> $products,
    			'price'		=> $price,
    		);
    		//check user's current cart
    		if( !$cart ){
    			$data = array(
    				'uid'			=> $uid,
    				'content'		=> addslashes(json_encode($content)),
    				'create_time'	=> time(),
    			);
    			$this->mBase->insert('users_cart',$data);
    		} else {
    			$data = array(
    				'content'		=> addslashes(json_encode($content)),
    				'create_time'	=> time(),
    			);
    			$this->mBase->update('users_cart',$data,array('uid'=>$uid));
    		}
    		//return data
    		$results = array();
    		$price = 0;
    		$amount = 0;
    		if( $content['products'] ){
    			foreach( $content['products'] as $product_id=>$row ){
    				$product = $this->getProduct($product_id);
    				if( $product ){
	    				$results['products'][$product_id] = array(
    						'product_id'	=> $product_id,
    						'title'			=> $product['title'],
    						'thumb'			=> $product['thumb'],
    						'amount'		=> $row['amount'],
    						'is_stock'		=> $product['stock']==0 || $row['amount']>$product['stock'] ? false : true,
    						'price'			=> $row['price'],
	    				);
	    				$price += $row['amount']*$row['price'];
	    				$amount += $row['amount'];
    				}
    			}
    			$results['price'] = $price;
    			$results['amount'] = $amount;
    		}
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>$results);
    	} while(0);
    	$this->output($ret);
    }
    
    public function remove(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array();
    		$fields = array('products');
    		$this->checkParams('post',$must,$fields);
    		$params = $this->params;
    		//get user
    		$user = $this->mBase->getSingle('users_session','sessionid',$params['sessionid'],'uid');
    		if( !($user && $user['uid']) ){
    			$ret = array('err_no'=>3001,'err_msg'=>'user is not login');
    			break;
    		}
    		$uid = intval($user['uid']);
    		//check product
    		$products = $params['products'] ? explode(',', trim($params['products'])) : false;
    		if( !$products ){
    			$this->mBase->delete('users_cart',array('uid'=>$uid),true);
    			$ret = array('err_no'=>0,'err_msg'=>'success','results'=>array());
    			break;
    		}
    		$content = array();
    		$cart = $this->mBase->getSingle('users_cart','uid',$uid);
    		if( $cart ){
    			$content = json_decode($cart['content'],true);
    			if( isset($content['products']) && $content['products'] ){
    				foreach( $products as $id ){
    					if( isset($content['products'][$id]) ){
    						unset($content['products'][$id]);
    					}
    				}
    				//update or remove user's cart
    				if( !$content['products'] ){
    					$this->mBase->delete('users_cart',array('uid'=>$uid),true);
    				} else {
    					//re_calculate price
    					$price = 0;
    					$amount = 0;
    					foreach ( $content['products'] as $id=>$row ){
    						$t = $this->mBase->getSingle('products','id',$id);
    						$price += $row['amount']*$t['price'];
    						$amount += $row['amount'];
    					}
    					$content['price'] = $price;
    					//update user's current cart
    					$data = array(
    						'uid'			=> $uid,
    						'content'		=> addslashes(json_encode($content)),
    						'create_time'	=> time(),
    					);
    					$this->mBase->update('users_cart',$data,array('uid'=>$uid));
    					$content['amount'] = $amount;
    				}
    			}
    		}
    		//return data
    		$results = array();
    		$price = 0;
    		$amount = 0;
    		if( $content['products'] ){
    			foreach( $content['products'] as $product_id=>$row ){
    				$product = $this->getProduct($product_id);
    				if( $product ){
	    				$results['products'][$product_id] = array(
    						'product_id'	=> $product_id,
    						'title'			=> $product['title'],
    						'thumb'			=> $product['thumb'],
    						'amount'		=> $row['amount'],
    						'is_stock'		=> $product['stock']==0 || $row['amount']>$product['stock'] ? false : true,
    						'price'			=> $row['price'],
	    				);
	    				$price += $row['amount']*$row['price'];
	    				$amount += $row['amount'];
    				}
    			}
    			$results['price'] = $price;
    			$results['amount'] = $amount;
    		}
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>$results);
    	} while(0);
    	$this->output($ret);
    }
    
    private function getProduct( $id ){
    	$cache_key = 'PRODUCT_'.$id;
    	$product = Common_Cache::get($cache_key);
    	if( !$product ){
    		$product = $this->mBase->getSingle('products','id',$id);
    		Common_Cache::save($cache_key, $product, 600);
    	}
    	return $product;
    }
}