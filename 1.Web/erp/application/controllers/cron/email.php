<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/cron.php';

class email extends Cron {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function send(){
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
}