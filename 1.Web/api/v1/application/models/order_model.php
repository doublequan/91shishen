<?php

require_once dirname(__FILE__).'/base_model.php';

class Order_model extends Base_model
{
	public function addOrder( $orderParams, $detailParams, $action=array(), $cash=array(), $minusMap=array() ){
		/**
		 * Begin Transcation
		 */
		$this->db->trans_start();
		/**
		 * Insert Order Info
		 */
		$order = $this->insert('orders', $orderParams);
		/**
		 * Insert Order Details
		 */
		$this->insertMulti('orders_detail', $detailParams);
		/**
		 * Add Order Action
		 */
		$this->insert('orders_action', $action);
		/**
		 * Modify AND Lock Coupons
		 */
		$discount = false;
		if( $discount ){
			$data = array(
				'coupon_used'	=> $discount['coupon_used'],
				'coupon_balance'=> 0,
				'is_lock'		=> 1,
			);
			$whereMap = array('id'=>$discount['id']);
			$this->update('users_coupon', $data, $whereMap);
		}
		if( $cash ){
			$used = $order['price']>=$cash['coupon_balance'] ? $cash['coupon_balance'] : $order['price'];
			$balance = $cash['coupon_balance']-$used;
			$data = array(
				'coupon_used'	=> $used,
				'coupon_balance'=> $balance,
				'is_lock'		=> 1,
			);
			$whereMap = array('id'=>$cash['id']);
			$this->update('users_coupon', $data, $whereMap);
		}
		//Minus Stock if Necessary
		if( $minusMap ){
			foreach ( $minusMap as $id=>$num ){
				$sql = 'UPDATE products_site SET stock=stock-'.$num.' WHERE id='.$id;
				$this->db->query($sql);
			}
		}
		//Rollback OR Commit
		if( $this->db->trans_status()===FALSE ){
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}
	
	public function getStock( $product_id, $site_id ){
		$sql = 'SELECT * FROM products_site WHERE product_id='.$product_id.' AND site_id='.$site_id;
		return $this->getRow($sql);
	}
	
	public function rollbackStock( $product_id, $site_id, $amount ){
		$sql = 'UPDATE products_site SET stock=stock+'.$amount.'
				WHERE product_id='.$product_id.' AND site_id='.$site_id;
		$this->db->query($sql);
	}
}
