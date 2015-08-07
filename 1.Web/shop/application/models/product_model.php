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
	
	public function getGoodByProductID( $product_id ){
	}
}