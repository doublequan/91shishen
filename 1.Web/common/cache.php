<?php

class Common_Cache
{	
	public function __construct(){}
	
	public static function connect(){
		try{
			$mem = new Memcache;
			$mem->connect ('127.0.0.1',11211);
			return $mem;
		} catch ( Exception $e ){
			$data = array(
				'logtype'	=> 'memcache',
				'action'	=> 'conntect',
				'err_msg'	=> 'memcache connect failure: '.$e->getMessage(),
			);
			Common_Log::add($data);
		}
		return false;
	}
	
	public static function save( $cache_key, $value, $expire ){
		$mem = self::connect();
		if( $mem ){
			return $mem->set($cache_key, $value, 0, $expire);
		}
		return false;
	}
	
	public static function get( $cache_key ){
		$mem = self::connect();
		if( $mem ){
			return $mem->get($cache_key);
		}
		return false;
	}
	
	public static function delete( $cache_key ){
		$mem = self::connect();
		if( $mem ){
			return $mem->delete($cache_key);
		}
		return false;
	}
}