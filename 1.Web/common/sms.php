<?php

class Common_Sms
{
	public function __construct(){}

	/**
	 * 日志格式  时间\tIP\t类型自定义信息$data字段
	 * @param array $data
	 */
	public static function send( $mobile, $content ){
		/**
		$params = array(
			'username'	=> 'NFTB700171',
			'scode'		=> '473796',
			'mobile'	=> $mobile,
			'content'	=> '@code@='.$content.',@minute@=10',
			'tempid'	=> 'MB-2014051307',
			//'msgid'		=> date('YmdHis').mt_rand(100000, 999999),
		);
		$url = 'http://www.mssms.cn:8000/msm/sdk/http/sendsmsutf8.jsp?'.http_build_query($params);
		*/
		$url = 'http://www.mssms.cn:8000/msm/sdk/http/sendsmsutf8.jsp?username=NFTB700171&scode=473796&mobile='.$mobile.'&content=@code@='.$content.',@minute@=10&tempid=MB-2014051307';
		return file_get_contents($url);
	}
	
	public static function sendAD( $mobile, $content ){
		$url = 'http://www.mssms.cn:8000/msm/sdk/http/sendsmsutf8.jsp?username=NFTB700171&scode=473796&mobile='.$mobile.'&content='.$content;
		return file_get_contents($url);
	}
}