<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/cron.php';

class coupon extends Cron {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function status(){
		$condition = array(
    		'AND' => array('status=1'),
    	);
    	$res = $this->mBase->getList('users_coupon',$condition,'*','id ASC',0,1000);
    	if( $res ){
    		$t = time();
    		foreach( $res as $row ){
    			$status = 0;
    			$end = intval($row['end']);
    			if( $end && $end<$t ){
    				$status = 3;
    			} elseif ( $row['coupon_total']==$row['coupon_used'] && $row['coupon_balance']==0 ){
    				$status = 2;
    			}
    			if( $status ){
	    			$whereMap = array('id'=>$row['id']);
	    			$data = array(
	    				'status' => $status,
	    			);
	    			$rows = $this->mBase->update('users_coupon',$data,$whereMap);
	    			echo $rows;
    			}
    		}
    	}
    }
}