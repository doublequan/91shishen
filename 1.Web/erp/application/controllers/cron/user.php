<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/cron.php';

class user extends Cron {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function stat(){
		$data = array();
		$t = time();
		$t = $t-$t%3600;
		$day = date('Y-m-d',$t);
		$os_types = getConfig('os_types');
		foreach ( $os_types as $os=>$type ){
			$condition = array(
				'AND' => array('status=1','create_os='.$os,'create_time<'.$t,'create_time>='.($t-3600)),
			);
			$change = $this->mBase->getCount('users',$condition);
			$condition = array(
				'AND' => array('status=1','create_os='.$os,'create_time<'.$t),
			);
			$total = $this->mBase->getCount('users',$condition);
			$insertData = array(
				'day'	=> $day,
				't'		=> $t,
				'os'	=> $os,
				'change'=> $change,
				'total'	=> $total,
			);
			$updateData = array(
				'change'=> $change,
				'total'	=> $total,
			);
			$this->mBase->insertDuplicate('stat_users',$insertData,$updateData);
		}
    }
    
    public function email(){
    	$table = 'queue_email';
		$data = array();
		$t = time();
		$t = $t-$t%60;
		$condition = array(
			'AND' => array('is_send=0'),
		);
		$res = $this->mBase->getList($table,$condition,'*','id ASC',0,'50');
		if( $res ){
			$emailConfig = array(
				'crlf' => "\r\n",
				'newline' => "\r\n",
				'charset' => 'utf-8',
				'mailtype' => 'html',
				'protocol' => 'smtp',
				'useragent' => 'www.100hl.com',
				'smtp_host' => 'smtp.qq.com',
				'smtp_user' => 'service@100hl.cn',
				'smtp_pass' => 'hsh2014',
				'smtp_port' => '25',
				'smtp_timeout' => '5'
			);
			foreach( $res as $row ){
				if( ($row['exec_time']==0 || $row['exec_time']==$t) ){
					$whereMap = array('id'=>$row['id']);
					if( filter_var($row['email'],FILTER_VALIDATE_EMAIL) ){
						$start = microtime(true);
						
						$this->load->library('email');
						$this->email->initialize($emailConfig);
						$this->email->from($emailConfig['smtp_user'], '惠生活');
						$this->email->to($row['email']);
						$this->email->subject($row['subject']);
						$this->email->message(htmlentities($row['content']));
						$result = $this->email->send();
						$end = microtime(true);
						$used = $end-$start;
						$data = array(
							'is_send'		=> 1,
							'start_time'	=> $start,
							'end_time'		=> $end,
							'used_time'		=> $used,
						);
						$this->mBase->update($table,$data,$whereMap);
					} else {
						$this->mBase->delete($table,$whereMap,true);
					}
				}
			}
		}
    }
    
    public function sms(){
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
    
    /**
     * 每分钟执行：更新用户代金券状态
     */
    public function coupon(){
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