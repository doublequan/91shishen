<?php

include_once 'base_model.php';

class Product_model extends Base_model
{
	public function setProductNum( $id, $column, $step=1 ){
		$sql = 'UPDATE products SET '.$column.'='.$column.'+'.$step.' WHERE id='.$id;
		return $this->db->query($sql);
	}
	
	public function getRandProduct( $site_id ){
		$sql = 'SELECT * FROM products WHERE site_id='.$site_id.' AND is_del=0 ORDER BY rand() LIMIT 1';
		return $this->getRow($sql);
	}
	
	public function getUserFav( $uid, $product_id ){
		$sql = 'SELECT * FROM users_fav WHERE uid='.$uid.' AND product_id='.$product_id;
		return $this->getRow($sql);
	}
	
	public function getProductList( $site_id, $category_id=0, $price_min=0, $price_max=0, $orderby='id DESC', $page=1, $size=20 ){
		$keyMap = array('PRODUCT_LIST',$category_id,$price_min,$price_max,$orderby,$page,$size);
		$cache_key = implode('_', $keyMap);
		$res = Common_Cache::get($cache_key);
		if( !$res ){
			$sql = 'SELECT s.stock,p.*
					FROM products AS p
					RIGHT JOIN products_site AS s ON p.id=s.product_id
					WHERE p.is_del=0 AND s.site_id='.$site_id;
			if( $category_id ){
				$categorys = $this->getChildCateID($category_id);
				$sql .= ' AND p.category_id IN ('.implode(',', $categorys).')';
			}
			if( $price_min ){
				$sql .= ' AND p.price>='.$price_min;
			}
			if( $price_max ){
				$sql .= ' AND p.price<='.$price_max;
			}
			$sql .= ' ORDER BY '.$orderby;
			$res = $this->pagerQuery($sql,$page,$size);
			Common_Cache::save($cache_key, $res, 60);
		}
		return $res;
	}
	
	public function getSearchResults( $site_id, $keyword='', $category_id=0, $price_min=0, $price_max=0, $orderby='id DESC', $page=1, $size=20 ){
		$sql = "SELECT s.stock,p.*
				FROM products AS p
				RIGHT JOIN products_site AS s ON p.id=s.product_id
				WHERE p.is_del=0 AND s.site_id=".$site_id." AND p.title LIKE '%".$keyword."%'";
		if( $category_id ){
			$categorys = $this->getChildCateID($category_id);
			$sql .= ' AND p.category_id IN ('.implode(',', $categorys).')';
		}
		if( $price_min ){
			$sql .= ' AND p.price>='.$price_min;
		}
		if( $price_max ){
			$sql .= ' AND p.price<='.$price_max;
		}
		$sql .= ' ORDER BY '.$orderby;
		return $this->pagerQuery($sql,$page,$size);
	}
	
	private function getChildCateID( $category_id ){
		$data = array();
		$condition = array(
			'AND' => array('father_id='.$category_id),
		);
		$res = $this->getList('goods_category',$condition,'*','sort ASC');
		if( $res ){
			foreach( $res as $row ){
				$data[$row['id']] = $row['id'];
				$data += $this->getChildCateID($row['id']);
			}
		} else {
			$data[$category_id] = $category_id;
		}
		return $data;
	}
	
	public function getSpecialProducts( $special_id ){
		$sql = 'SELECT p.*,sp.sort,c.name AS category_name
				FROM specials_product AS sp
				INNER JOIN products AS p ON sp.product_id=p.id
				LEFT JOIN goods_category AS c ON p.category_id=c.id
				WHERE sp.special_id='.$special_id.' ORDER BY sp.sort ASC';
		return $this->getAll($sql);
	}
	
	public function getFreeProducts( $site_id ){
		$sql = "SELECT s.stock,p.*
				FROM products AS p
				RIGHT JOIN products_site AS s ON p.id=s.product_id
				WHERE p.is_del=0 AND s.site_id=".$site_id." AND p.price<=3
				ORDER BY rand() LIMIT 1";
		return $this->getRow($sql);
	}
}
