<?php

require_once dirname(__FILE__).'/../config/global.conf.php';

define('STORE_KEY', 'vhcdnstYF*c$0dWw');
define('STORE_CHANNEL', 1000);

//get site_id
$site_id = 1;
if( isset($_COOKIE['site_id']) ){
	$site_id = intval($_COOKIE['site_id']);
}

//common parameters
$params = array(
	'key'       => STORE_KEY,
	'channel'   => STORE_CHANNEL,
	'timestamp' => time(),
	'loc'       => '0,0',
	'net'       => '0',
	'sessionid' => 'null',
	'site_id'	=> $site_id,
);

//get request parameters
$req = $_POST;
$qt = getParam('qt', $req);
$apiMap = getConfig('apiMap');
if( !in_array($qt, $apiMap) ){
	echo json_encode(array('err_no'=>2002,'err_msg'=>'api illegal:'.$qt));
	exit;
}

$must = getParam('must', $req);
$must = json_decode($must,true);
$fields = getParam('fields', $req);
$fields = json_decode($fields,true);

//create sign
$params = $must ? array_merge($params,$must) : $params;
$str = '';
ksort($params);
foreach( $params as $k=>$v ){
	$str .= $v;
}
$params['sign'] = md5(md5($str).SKEY);
$params = $fields ? array_merge($params,$fields) : $params;

//send request
$ret = curlPost($qt, $params);
if( !$ret ){
	$ret = json_encode(array('err_no'=>1000,'err_msg'=>'system error'));
}
echo $ret;
exit;

/**
 * @param string $key
 * @param array $params
 * @return string
 */
function getParam( $key, $params ){
	return isset($params[$key]) ? urldecode($params[$key]) : '';
}

/**
 * @param string $qt
 * @param array $params
 * @return boolean|string
 */
function curlPost( $qt, $params ){
	$host = '';
	$url = 'http://api.100hl.cn/v1'.$qt;
	$ch = curl_init();
	$res= curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	if( $host ){
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: '.$host));
	}
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	$result = curl_exec ($ch);
	curl_close($ch);
	if ($result == NULL) {
		return false;
	}
	return $result;
}