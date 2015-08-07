<?php
/**
 * 支付宝
 * @author LiuPF<mail@phpha.com>
 * @date 2014-10-5
 */
class Alipay{
	
	//支付配置
	private static $config = array(
		'partner'			=> '2088611083612660',
		'key'				=> '87v9iey5jy6s47s5yj4kw9dilpfjtgvc',
		'seller_email'		=> 'zhifubao@100hl.cn',
		'gateway'			=> 'https://mapi.alipay.com/gateway.do?',
		'return_url'		=> 'http://www.100hl.com/callback/alipay/return',
		'notify_url'		=> 'http://www.100hl.com/callback/alipay/notify',
		'payment_type'		=> '1',
		'transport'			=> 'http',
		'http_verify_url'	=> 'http://notify.alipay.com/trade/notify_query.do?',
		'https_verify_url'	=> 'https://mapi.alipay.com/gateway.do?service=notify_verify&',
	);
	
	/**
	 * 执行支付
	 * @param float $money
	 * @param string $pay_no
	 * @return boolean
	 */
	public function pay($money = 0.00, $pay_no = ''){
		//格式检查
		if($money <= 0 || empty($pay_no))	return false;
		//请求参数
		$data['service'] = 'create_direct_pay_by_user';
		$data['partner'] = self::$config['partner'];
		$data['payment_type'] = self::$config['payment_type'];
		$data['notify_url'] = self::$config['notify_url'];
		$data['return_url'] = self::$config['return_url'];
		$data['seller_email'] = self::$config['seller_email'];
		$data['out_trade_no'] = $pay_no;
		$data['subject'] = '惠生活在线支付';
		$data['total_fee'] = $money;
		$data['_input_charset'] = 'utf-8';
		$data['sign'] = self::createSign($data);
		$data['sign_type'] = 'MD5';
		//发起支付
		$pay_url = self::$config['gateway'].http_build_query($data);
		redirect($pay_url);
	}
	
	/**
	 * 验证返回签名及来源
	 * @return boolean
	 */
	public function checkSign($data = array()){
		//检验签名
		$check_sign = self::createSign($data) == $data['sign'] ? true : false;
		//验证ATN结果
		$check_atn = 'true';
		if( ! empty($data['notify_id'])){
			//$check_atn = self::getResponse($data['notify_id']);
		}
		if(preg_match("/true$/i", $check_atn) && $check_sign){
			return true;
		}
		return false;
	}
	
	/**
	 * 生成签名
	 * @param array $data
	 * @return string
	 */
	private static function createSign($data = array()){
	    ksort($data);
		reset($data);
		$sign = '';
		while(list($k,$v) = each($data)){
			if(empty($v) || $k == 'sign' || $k == 'sign_type')	continue;
			$sign .= "{$k}={$v}&";
		}
		$sign = trim($sign, '&');
		return md5($sign.self::$config['key']);
	}
	
	/**
	 * 获取远程服务器ATN结果并验证返回URL
	 * @param string $notify_id 通知校验ID
	 * @return string 服务器ATN结果
	 */
	private static function getResponse($notify_id = ''){
		$transport = self::$config['transport'];
		$partner = self::$config['partner'];
		$cacert_url = APPPATH.'config/cacert.pem'; //证书地址
		$veryfy_url = $transport == 'https' ? self::$config['https_verify_url'] : self::$config['http_verify_url'];
		$veryfy_url = $veryfy_url."partner=".$partner."&notify_id=".$notify_id;
		$result = self::getHttpRes($veryfy_url, $cacert_url);
		return $result;
	}
	
	/**
	 * 远程获取数据GET模式
	 * @param string $url 指定URL完整路径地址
	 * @param string $cacert_url 指定当前工作目录绝对路径
	 * @return string 远程输出的数据
	 */
	private static function getHttpRes($url, $cacert_url){
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1); // 显示输出结果
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true); //SSL证书认证
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); //严格认证
		curl_setopt($curl, CURLOPT_CAINFO, $cacert_url); //证书地址
		$result = curl_exec($curl);
		curl_close($curl);
		return $result;
	}
}