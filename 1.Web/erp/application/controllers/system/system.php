<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class system extends Base {
	private $active_top_tag = 'system';

	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'system';
		$this->_view('common/header', $tags);
		$this->_view('system/system');
		$this->_view('common/footer');
	}
	
}