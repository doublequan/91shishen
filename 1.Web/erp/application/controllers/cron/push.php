<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/cron.php';

class push extends Cron {
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * $secret_key //应用的secret key
	 * $method //GET或POST
	 * $url url
	 * $arrContent //请求参数(包括GET和POST的所有参数，不含计算的sign)
	 * return $sign string
	 **/
	function genSign($secret_key, $method, $url, $arrContent) {
		$gather = $method.$url;
		ksort($arrContent);
		foreach($arrContent as $key => $value) {
			$gather .= $key.'='.$value;
		}
		$gather .= $secret_key;
		$sign = md5(urlencode($gather));
		return $sign;
	}
	
	public function send(){
		$data = array();
		$t = time();
		$t = $t-$t%60;
		$condition = array(
			'AND' => array('is_push=0'),
		);
		$res = $this->mBase->getList('queue_push',$condition,'*','id ASC');
		if( $res ){
			foreach( $res as $row ){
				if( $row['exec_time']==0 || $row['exec_time']==$t ){
					$data[$row['id']] = $row;
				}
			}
		}
		if( $data ){
			$android_secret_key = 'xzzwZsMKNHQTKwScGXagVDViZQXynudv';
			$android_push_url = 'http://channel.api.duapp.com/rest/2.0/channel/channel';
			$android_method = 'POST';
			foreach( $data as $id=>$row ){
				if( $row['os']==1 ){
					//Android, Use Baidu Cloud
					$params = array(
						'method'	=> 'push_msg',
						'apikey'	=> 'dIpU7XCoYgUwSqUaDNTh0uW6',
						'user_id'	=> '1089360006297169175',
						'push_type'	=> 1,
						'messages'	=> '{"title":"惠生活后台推送测试","description:"'.$row['content'].'"}',
						'msg_keys'	=> $id,
						'timestamp'	=> time(),
					);
					$params['sign'] = $this->genSign($android_secret_key, $android_method, $android_push_url, $params);
					$msg = curlRequest($android_push_url,$params);
					echo $msg;exit;
				}
			}
		}
	}
}