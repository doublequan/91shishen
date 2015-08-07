<?php

require_once dirname(__FILE__).'/base_model.php';

class Product_model extends Base_model 
{
	public function __construct() {
		parent::__construct();
	}
	
	public function getSpecialProducts( $special_id ){
		$sql = 'SELECT p.*,sp.sort,c.name AS category_name
				FROM specials_product AS sp
				INNER JOIN products AS p ON sp.product_id=p.id
				LEFT JOIN goods_category AS c ON p.category_id=c.id 
				WHERE sp.special_id='.$special_id.' ORDER BY sp.sort ASC';
		return $this->getAll($sql);
	}
	
	public function updateStock( $type, $store_id, $single, $change ){
		$amount = 0;
		$table = $type.'s_stock';
		$column = $type.'_id';
		$where = 'store_id='.$store_id.' AND '.$column.'='.$single['id'];
		$sql = 'SELECT * FROM '.$table.' WHERE '.$where;
		$curr = $this->getRow($sql);
		if( !$curr ){
			if( $change ){
				$data = array(
					'store_id'		=> $store_id,
					$column			=> $single['id'],
					'amount'		=> $change,
					'price'			=> $single['price'],
					'change_time'	=> time(),
				);
				$this->insert($table, $data);
			}
			$amount = $change;
		} else {
			$amount = $curr['amount']+$change;
			$amount = $amount<0 ? 0 : $amount;
			$sql = 'UPDATE '.$table.' SET amount='.$amount.' WHERE '.$where;
			$this->db->query($sql);
		}
		return $amount;
	}
	
	public function getGoodStock( $store_id=0, $good_id=0, $keyword='', $page=0, $size=0 ){
		$sql = 'SELECT g.name,gs.*
				FROM goods AS g
				INNER JOIN goods_stock AS gs ON g.id=gs.good_id
				WHERE g.is_del=0';
		if( $store_id ){
			$sql .= ' AND gs.store_id='.$store_id;
		}
		if( $good_id ){
			$sql .= ' AND gs.good_id='.$good_id;
		}
		if( $keyword ){
			$sql .= " AND g.name LIKE '%".$keyword."%'";
		}
		return $this->pagerQuery($sql,$page,$size);
	}
	
	public function getProductStock( $store_id=0, $product_id=0, $keyword='', $page=0, $size=0 ){
		$sql = 'SELECT p.title,p.sku,p.product_pin,ps.*
				FROM products AS p
				INNER JOIN products_stock AS ps ON p.id=ps.product_id
				WHERE p.is_del=0';
		if( $store_id ){
			$sql .= ' AND ps.store_id='.$store_id;
		}
		if( $product_id ){
			$sql .= ' AND ps.product_id='.$product_id;
		}
		if( $keyword ){
			$sql .= " AND p.title LIKE '%".$keyword."%'";
		}
		return $this->pagerQuery($sql,$page,$size);
	}
	
	public function rollbackStock( $product_id, $site_id, $amount ){
		$sql = 'UPDATE products_site SET stock=stock+'.$amount.'
				WHERE stock<>-1 AND product_id='.$product_id.' AND site_id='.$site_id;
		$this->db->query($sql);
	}
}