<?php

class Common_Log
{
	public function __construct(){}

	/**
	 * 日志格式  时间\tIP\t类型自定义信息$data字段
	 * @param array $data
	 */
	public static function add( $data ){
		$dir = LOG_PATH . date('Ymd');
		if( !is_dir($dir) ){
			mkdir($dir);
		}
		$file = $dir .'/'. date('YmdH').'.log';
		$fp = fopen($file,'a+');
		if( $fp ){
			$arr = array(
				'['.date('Y-m-d H:i:s').']',
				'['.getUserIP(true).']',
			);
			foreach( $data as $k=>$v ){
				$arr[] = '['.$k.'='.$v.']';
			}
			unset($v);
			fwrite($fp, implode('',$arr)."\n");
		}
		return;
	}
}