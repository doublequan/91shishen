<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class log extends Base {
	
	private $active_top_tag = 'system';

	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$paths = array();
		foreach( glob(LOG_PATH.'*') as $path ){
			$paths[] = $path;
		}
		$paths = array_reverse($paths);
		foreach( $paths as &$path ){
			$hours = array();
			foreach( glob($path.'/*') as $file ){
				$hours[] = str_replace('.log', '', str_replace($path.'/','',$file));
			}
			$path = array(
				'day'	=> str_replace(LOG_PATH,'',$path),
				'hours'	=> $hours,
			);
		}
		$data['paths'] = $paths;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'log_list';
		$this->_view('common/header', $tags);
		$this->_view('system/log_list',$data);
		$this->_view('common/footer');
	}
	
	public function detail(){
		$day = $this->input->get('day');
		$hour = $this->input->get('hour');
	    $file = LOG_PATH.$day.'/'.$hour.'.log';
	    if( !file_exists($file) ){
	        exit('Log File is Not Exists');
	    }
	    $logs = array();
        $fp = fopen($file,'r');
        while( $str = fgets($fp) ){
        	$str = trim($str);
        	$pos = intval(strpos($str, '&os='));
        	$os = $pos ? substr($str, $pos+4, 1) : 0;
        	$str = substr($str,1,strpos($str, '][params')-1);
        	$arr = explode('][', $str);
        	if( count($arr)==7 ){
	        	$row = array(
	        		'os'		=> $os,
	        		'date'		=> $arr[0],
	        		'ip'		=> $arr[1],
	        		'api'		=> str_replace('uri=', '', $arr[3]),
	        		'err_no'	=> str_replace('err_no=', '', $arr[4]),
	        		'err_msg'	=> str_replace('err_msg=', '', $arr[5]),
	        		'time'		=> str_replace('time=', '', $arr[6]),
	        	);
	        	$logs[] = $row;
        	}
        }
	    $logs = array_reverse($logs);
	    $data['logs'] = $logs;
	    $data['date'] = date('Y-m-d',strtotime($day));
	    //Common Data
	    $data['os_types'] = getConfig('os_types');
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'log_list';
		$this->_view('common/header', $tags);
		$this->_view('system/log_detail',$data);
		$this->_view('common/footer');
	}
}