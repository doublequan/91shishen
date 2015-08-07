<?php
//global
set_time_limit(0);
error_reporting(E_ALL);

//local setting
define('MACH_ENV', 'develop');

//date zone set
date_default_timezone_set('Asia/Shanghai');

//global
define('SYSTEM_PATH', realpath(dirname(__FILE__)).'/../');

//API
define('API_DOMAIN','http://api.100hl.cn/');

//file and CDN
define('FILE_PATH',SYSTEM_PATH.'upload/');
define('FILE_DOMAIN','http://upload.hsh.com/');
define('AVATAR_DOMAIN', 'http://www.100hl.com/');
define('FILE_EXTENSIONS','rar,doc,docx,zip,pdf,txt,swf,wmv,gif,png,jpg,jpeg,bmp');

//database
define('DB', 'mysql');
define('CACHE', 'memcache');

//api crypt key
define('SKEY','TczAFlw@SyhYEyh*');

//remember-password cookie salt
define('REMPWD_SALT', '6KX0oPbrgN');

//iconv
iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");
iconv_set_encoding("internal_encoding", "UTF-8");

//log
define('LOG_PATH', SYSTEM_PATH.'log/');

//Shipping fee
define('SHIPPING_FREELIMIT', 59.00);
define('SHIPPING_FEE', 5.00);

/**
 * Load All Config and Common Files
 */
foreach ( glob(SYSTEM_PATH.'config/*') as $file ){
	require_once $file;
}
foreach ( glob(SYSTEM_PATH.'common/*') as $file ){
	require_once $file;
}
