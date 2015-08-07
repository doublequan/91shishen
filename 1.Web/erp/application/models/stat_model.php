<?php

require_once dirname(__FILE__).'/base_model.php';

class Stat_model extends Base_model 
{
    public function __construct() {
        parent::__construct();
    }
    
    public function getUserFirstOrder( $uids ){
    	$sql = 'SELECT uid,MIN(id) AS id,create_time
    			FROM orders
    			WHERE uid IN ('.implode(',', $uids).')
    			GROUP BY uid;';
    	return $this->getAll($sql);
    }
}