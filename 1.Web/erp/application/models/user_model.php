<?php

require_once dirname(__FILE__).'/base_model.php';

class User_model extends Base_model 
{
	public function __construct() {
		parent::__construct();
	}
	
	public function getProductViewStat( $page, $size ){
		$sql = 'SELECT product_id,COUNT(uid) AS num FROM users_log_view
				GROUP BY product_id ORDER BY num DESC';
		return $this->pagerQuery($sql,$page,$size);
	}
}