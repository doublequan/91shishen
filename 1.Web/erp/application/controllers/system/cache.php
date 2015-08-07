<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class cache extends Base {
	
	private $active_top_tag = 'system';

	public function __construct(){
		parent::__construct();
	}
	
	public function config(){
		$data = array();
		//get data
		global $config;
		$data['config'] = $config;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'cache_config';
		$this->_view('common/header', $tags);
		$this->_view('system/cache_config_list',$data);
		$this->_view('common/footer');
	}
	
	public function delete(){
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
    	do{
    		//get parameters
    		$must = array('key');
    		$fields = array();
    		$this->checkParams('get',$must,$fields);
    		$params = $this->params;
    		//check parameter
    		$cache_key = 'SYSTEM_CONFIG_'.$params['key'];
    		Common_Cache::delete($cache_key);
    		$ret = array('err_no'=>0,'err_msg'=>'操作成功');
    	} while(0);
    	$this->output($ret);
	}
	
	public function user(){
		$data = array();
		//get data
		$data['os_types'] = getConfig('os_types');
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'cache_user';
		$this->_view('common/header', $tags);
		$this->_view('system/cache_user_list',$data);
		$this->_view('common/footer');
	}
	
	public function delete_user_session(){
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$os = intval($this->input->post('os'));
			$os_types = getConfig('os_types');
			//check parameter
			$this->mBase->delete('users_session',array('os'=>$os),true);
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
}