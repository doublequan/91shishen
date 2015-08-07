<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/base.php';

class page extends Base{
	
	public function error_404(){
		$data = array();
		$this->load->view('page/error_404', $data);
	}
}