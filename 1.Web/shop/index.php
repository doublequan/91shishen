<?php
define('ROOT_PATH', realpath(dirname(__FILE__)).'/');
require_once ROOT_PATH.'../config/global.conf.php';
if (defined('STDIN')){
	chdir(dirname(__FILE__));
}
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('EXT', '.php');
define('FCPATH', str_replace(SELF, '', __FILE__));
define('BASEPATH', SYSTEM_PATH.'system/');
define('SYSDIR', 'system');
define('APPPATH', FCPATH.'application/');
define('STATICURL', '/static/');
//网站ID
if(isset($_COOKIE['SITEID']) && in_array(intval($_COOKIE['SITEID']), array(1,2,3))){
    define('SITEID', intval($_COOKIE['SITEID']));
}else{
    define('SITEID', 1);
    setcookie('SITEID', '1', time()+86400, '/');
}
require_once BASEPATH.'core/CodeIgniter.php';