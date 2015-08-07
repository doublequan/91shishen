<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/cron.php';

class sms extends Cron {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function send(){
    	$table = 'queue_sms';
    	$data = array();
    	$t = time();
    	$t = $t-$t%60;
    	$condition = array(
    			'AND' => array('is_send=0'),
    	);
    	$res = $this->mBase->getList($table,$condition,'*','id ASC',0,'10');
    	if( $res ){
    		foreach( $res as $row ){
    			if( ($row['exec_time']==0 || $row['exec_time']==$t) ){
    				$whereMap = array('id'=>$row['id']);
    				if( preg_match('/^1[\d]{10}$/',$row['mobile']) ){
    					$start = microtime(true);
    					$msg = Common_Sms::sendAD($row['mobile'], $row['content']);
    					$end = microtime(true);
    					$used = $end-$start;
    					$data = array(
    						'is_send'		=> 1,
    						'start_time'	=> $start,
    						'end_time'		=> $end,
    						'used_time'		=> $used,
    						'err_msg'		=> trim($msg),
    					);
    					$this->mBase->update($table,$data,$whereMap);
    				} else {
    					$this->mBase->delete($table,$whereMap,true);
    				}
    			}
    		}
    	}
    }
}