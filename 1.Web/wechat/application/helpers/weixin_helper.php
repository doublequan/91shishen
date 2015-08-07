<?php
/**
 * 微信函数库
 * @author cuiqg <cuiqg@100hl.cn>
 * @date 2014-12-12
 */


/**
 * @param array $params
 * @param string $request_url
 * @return string $type
 */
function send_request($request_url = "", $params = array(), $type = "get", $form = "text")
{
		       
    if($request_url)
    {
    	$request_result = "";
    	if($type = "get")
    	{
    		if(!empty($params))
    		{
		    	$request_url = substr($request_url, -1) != '?' ? $request_url.="?" : $request_url;

		        foreach($params as $key => $val)
		        {
		        	$request_url .= "$key=$val&";
		        }

		        $request_url = rtrim($request_url, "&");
	    	}
	       $request_result =  https_request($request_url);

    	}
    	else if($type = "post")
    	{

    	 	$request_result = https_request($request_url, $params, $form);
    	}

        return $request_result;
    }
    else
    {
        
        return '参数有问题！';
    }
	
}

/**
 * @todo 发送HTTP 请求
 * @param string $url
 */	

function https_request($url, $data = null, $content_type = null){

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }

    if(!empty($content_type)){

    	switch ($content_type) {
    		case 'text':
    			$content_type = 'Content-type: text/plain';
    			break;
    		case 'form':
    			$content_type = 'Content-Type: application/x-www-form-urlencoded';
    			break;
    	}
    	
    	curl_setopt($curl, CURLOPT_HTTPHEADER, array($content_type));
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);
    curl_setopt($curl, CURLOPT_TIMEOUT_MS, 0);
    $output = curl_exec($curl);
    curl_close($curl);
    if($output === false)
    {
    	return false;
    }
    else
    {
    	return $output;
    }
}

/**
 * @todo 获取access_token
 *
 */

function get_accessToken()
{
	$access_token = "";
	if(!isset($_COOKIE['access_token']))
	{
		$request_url = config_item('token_url');

		$params = array(
			'grant_type' 	=> 'client_credential',
			'appid'			=> config_item('app_id'),
			'secret'		=> config_item('app_secret'),
			);
		$result = send_request($request_url, $params);

		$result_arr = json_decode($result, true);

		setcookie('access_token',$result_arr['access_token'],time()+$result_arr['expires_in']);

		$access_token = $result_arr['access_token'];

	}
	else
	{
		$access_token = $_COOKIE['access_token'];
	}

	return $access_token;

}

/**
 * @todo 获取短网址
 *
 */

function dwz($long_url)
{
	if($long_url)
	{
		$request_url = config_item('sina_dwz');

		$param_arr = array(
			'source' => "209678993",
			'url_long'	=> trim($long_url),
			);
		$result = send_request($request_url, $param_arr);

		$result_arr = json_decode($result,true);
		
		if($result_arr['urls'][0]['result'])
		{
			$result = $result_arr['urls'][0]['url_short'];
		}
		
		return $result;
	}
	else{
		return "吗蛋,你至少要穿一个值啊";
	}
}

// 判断是否为JSON

function is_json($object){

	json_decode($object);
	return (json_last_error() == JSON_ERROR_NONE);
}