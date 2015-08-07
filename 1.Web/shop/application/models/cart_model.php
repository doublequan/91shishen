<?php
/**
 * 购物车
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-13
 */
require_once 'base_model.php';
class Cart_model extends Base_model{
	
	/**
	 * 同步购物车到数据库
	 * @param integer $uid
	 * @param array $cart
	 * @return boolean
	 */
	public function syncToDb($uid = 0, $cart = array()){
		if(empty($uid)){
	        return false;
	    }
	    //购物车格式转换
	    $data = array('price'=>0.00, 'products'=>array());
	    foreach($cart as $k => $v){
	        $data['price'] += $v['price'] * $v['amount'];
	        $data['products'][$k] = array('price'=>$v['price'], 'amount'=>$v['amount']);
	    }
	    //更新数据库
	    $data['price'] = sprintf('%.2f', $data['price']);
	    $content = addslashes(json_encode($data));
		$create_time = time();
		$cart_sql = "
		    INSERT INTO users_cart(uid,content,create_time)
            VALUES({$uid},'{$content}',{$create_time})
            ON DUPLICATE KEY UPDATE
            content = '{$content}', create_time = {$create_time}
		";
		$result = $this->db->query($cart_sql);
		return $result;
	}
	
	/**
	 * 同步数据库到购物车
	 * @param integer $uid
	 * @return null|boolean
	 */
	public function syncToCart($uid = 0){
		if(empty($uid)){
			return false;
		}
		//更新购物车
		$cart = $this->getOne("SELECT content FROM users_cart WHERE uid = {$uid}");
	    if( ! empty($cart)){
    	    $cart = json_decode($cart, true);
    	}
		if( ! empty($cart['products'])){
		    //购物车格式转换
		    $data = array();
		    foreach($cart['products'] as $k => $v){
		        //商品信息
		        $product = $this->getRow("SELECT title,thumb,price FROM products WHERE id = {$k}");
		        if(empty($product)){
		            continue;
		        }
		        $data[$k] = array('price'=>$product['price'], 'amount'=>$v['amount']);
		    }
		    $data = json_encode($data);
			$this->input->set_cookie('cart', $data, '86400');
		}
	}
	
	/**
	 * VIP同步购物车到数据库
	 * @param integer $uid
	 * @param array $cart
	 * @return boolean
	 */
	public function vipSyncToDb($uid = 0, $cart = array()){
	    if(empty($uid)){
	        return false;
	    }
	    //购物车格式转换
	    $data = array('price'=>0.00, 'products'=>array());
	    foreach($cart as $k => $v){
	        $data['price'] += $v['price'] * $v['amount'];
	        $data['products'][$k] = array('price'=>$v['price'], 'amount'=>$v['amount']);
	    }
	    //更新数据库
	    $data['price'] = sprintf('%.2f', $data['price']);
	    $content = addslashes(json_encode($data));
	    $create_time = time();
	    $cart_sql = "
	        INSERT INTO vip_users_cart(uid,content,create_time)
            VALUES({$uid},'{$content}',{$create_time})
            ON DUPLICATE KEY UPDATE
            content = '{$content}', create_time = {$create_time}
	    ";
	    $result = $this->db->query($cart_sql);
	    return $result;
	}
	
	/**
	* VIP同步数据库到购物车
	* @param integer $uid
	* @return null|boolean
	*/
	public function vipSyncToCart($uid = 0){
	    if(empty($uid)){
    	    return false;
    	}
    	//更新购物车
    	$cart = $this->getOne("SELECT content FROM vip_users_cart WHERE uid = {$uid}");
    	if( ! empty($cart)){
    	    $cart = json_decode($cart, true);
    	}
		if( ! empty($cart['products'])){
		    //购物车格式转换
		    $data = array();
		    foreach($cart['products'] as $k => $v){
		        //商品信息
		        $product = $this->getRow("SELECT title,thumb,price FROM vip_products WHERE id = {$k}");
		        if(empty($product)){
		            continue;
		        }
		        $data[$k] = array('price'=>$product['price'], 'amount'=>$v['amount']);
		    }
		    $data = json_encode($data);
			$this->input->set_cookie('vipcart', $data, '86400');
		}
    }
}