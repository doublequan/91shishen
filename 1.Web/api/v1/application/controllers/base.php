<?php

class Base extends CI_Controller {
	
	protected $apiMap = array();
	
	protected $keyMap = array(
		//1.x版本
		'aQ%RRsF66Hn+MUDC'	=> array('os'=>1,'channel'=>1000,'sv'=>'0.1.0'),	//Adroid
		'aX1IXOzRE1B@hgSr'	=> array('os'=>2,'channel'=>1000,'sv'=>'0.1.0'),	//iPhone
		'E4ql5jpB0pojo*7V'	=> array('os'=>3,'channel'=>1000,'sv'=>'0.1.0'),	//iPad
		'vhcdnstYF*c$0dWw'	=> array('os'=>4,'channel'=>1000,'sv'=>'0.1.0'),	//Touch
	);

	protected $mustMap = array(
		'channel',
		'key',
		'loc',
		'net',
		'timestamp',
		'sessionid',
		'sign',
		'site_id',
	);
	
	public $params = array();
	
	public $signMap = array();
	
	public $time_start;
	
	public $time_end;
	
	public function __construct() {
		parent::__construct();
        isset($_SESSION) || session_start();
        $this->apiMap = getConfig('apiMap');
		$this->load->helper('url');
		$this->time_start = microtime(true);
		$this->load->model('Base_model','mBase');
	}
	
	protected function checkParams( $method, $must=array(), $fields=array(), $checkSign=true ) {
		$params = array();
		foreach ( array_merge($this->mustMap,$must) as $k ){
			$v = $this->input->$method($k,true);
			if( $v=='' ){
				$this->output(array('err_no'=>2001,'err_msg'=>"parameter {$k} is missing"));
			} else {
				$params[$k] = $v;
			}
		}
		$this->signMap = $params;
		$params['os'] = $this->keyMap[$params['key']]['os'];
		$params['sv'] = $this->keyMap[$params['key']]['sv'];
		if( $fields ){
			foreach ( $fields as $k ){
				$params[$k] = $this->input->$method($k,true);
			}
		}
		$this->params = $params;
		if( $checkSign ){
			$this->checkRequest();
			$this->checkSign();
		}
		return;
	}
	
	protected function checkRequest(){
		do{
			$api = current_url();
			if( !in_array($api, $this->apiMap) ){
				$ret = array('err_no'=>2002,'err_msg'=>'api illegal');
				break;
			}
			//check key and channel
			$key = $this->input->get_post('key',true);
			$channel = $this->input->get_post('channel',true);
			if( !($key && array_key_exists($key, $this->keyMap)) ){
				$ret = array('err_no'=>2003,'err_msg'=>'key illegal');
				break;
			}
			if( !($channel && preg_match('/\d{4}/', $channel)) ){
				$ret = array('err_no'=>2004,'err_msg'=>'channel illegal');
				break;
			}
			if( $this->keyMap[$key]['channel']!=$channel ){
				$ret = array('err_no'=>2005,'err_msg'=>'key and channel not match');
				break;
			}
			//check timstamp
			$timestamp = $this->input->get_post('timestamp',true);
			if( time()-$timestamp>60 ){
				$ret = array('err_no'=>2006,'err_msg'=>'request timeout');
				break;
			}
			return true;
		}while(false);
		$this->output($ret);
	}
	
	protected function checkSign() {
		$params = $this->signMap;
		$sign = $params['sign'];
		unset($params['sign']);
		$str = '';
		ksort($params);
		foreach( $params as $k=>$v ){
			$str .= $v;
		}
		if( md5(md5($str).SKEY)!=$sign ){
			$this->output(array('err_no'=>2007,'err_msg'=>'sign verify failure'));
		}
		return;
	}
	
	public function output( $ret ){
		if( $ret['err_no']!=0 ){
			$ret['results'] = array();
		}
		echo json_encode($ret);
		$this->time_end = microtime(true);
		$uri = current_url();
		$params = $this->params;
		unset($params['pass'],$params['repass'],$params['key']);
		$data = array(
			'logtype'	=> 'mobile',
			'uri'		=> $uri, 
			'err_no'	=> $ret['err_no'],
			'err_msg'	=> $ret['err_msg'],
			'time'		=> round(($this->time_end-$this->time_start),6),
			'params'	=> http_build_query($params),
		);
		Common_Log::add($data);
		exit;
	}
}