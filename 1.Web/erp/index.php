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
define('SITEID', 1); //网站ID
define('ROOTPATH', BASEPATH.'../');
require_once BASEPATH.'core/CodeIgniter.php';
