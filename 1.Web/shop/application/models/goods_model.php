<?php
/**
 * 商品信息
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-1
 */
require_once 'base_model.php';
class Goods_model extends Base_model{
    
    /**
     * 获取推荐商品
     * @param integer $goods_id
     * @param integer $page
     * @param integer $limit
     * @return array
     */
    public function getRecommend($goods_id = 0, $page = 1, $limit = 6){
        $category_id = $this->getOne("SELECT category_id FROM products WHERE id = {$goods_id}");
        $start = ($page - 1) * $limit;
        $goods = $this->getAll(
            "SELECT SQL_CALC_FOUND_ROWS DISTINCT p.id,p.title,p.seo_title,p.price,p.thumb,p.unit,p.spec
            FROM products p
            LEFT JOIN products_site s ON s.product_id = p.id
            WHERE s.site_id > 0 AND p.category_id = {$category_id} AND p.id != {$goods_id} AND p.is_del = 0
            LIMIT {$start}, {$limit}"
        );
        $data['data'] = $goods;
        $data['total'] = $this->getOne("SELECT FOUND_ROWS()");
        return $data;
    }
    
    /**
     * 获取商品信息
     * @param integer $cid
     * @param integer $limit
     * @return array
     */
    public function getGoods($cid = 0, $order = 'p.id DESC', $limit = 5){
        $limit = max(1, intval($limit));
        $action = $limit == 1 ? 'getRow' : 'getAll';
        $sql = "SELECT DISTINCT p.id,p.title,p.seo_title,p.price,p.price_market,p.thumb,p.unit,p.spec
        		FROM products p
                INNER JOIN products_site s ON s.product_id = p.id
        		WHERE s.site_id=".SITEID." AND p.sku NOT LIKE 'YZ%'";
        if( $cid ){
        	$cids = $this->getChildCateID($cid);
        	$sql .= ' AND p.category_id IN ('.implode(',', $cids).')';
        }
        $sql .= ' AND p.is_del = 0 ORDER BY '.$order.' LIMIT '.$limit;
        $data = $this->$action($sql);
        return $data;
    }
    
    /**
     * 销量排行
     * @param number $limit
     * @return array
     */
    public function getRanking($limit = 5){
        $limit = max(1, intval($limit));
        $action = $limit == 1 ? 'getRow' : 'getAll';
        $data = $this->$action(
            "SELECT DISTINCT p.id,p.title,p.seo_title,p.price,p.price_market,p.thumb,p.unit,p.spec
            FROM products p
            LEFT JOIN products_site s ON s.product_id = p.id
            WHERE s.site_id > 0 AND p.is_del = 0
            ORDER BY p.sold DESC, p.id DESC
            LIMIT {$limit}"
        );
        return $data;
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
    
    public function getCheapest( $site_id ){
    	$sql = 'SELECT sp.product_id
    			FROM specials_product AS sp
    			INNER JOIN specials_site AS ss ON ss.special_id=sp.special_id
    			ORDER BY rand() LIMIT 1';
    	$product_id = $this->getOne($sql);
    	return $this->getSingle('products', 'id', $product_id);
    }
}