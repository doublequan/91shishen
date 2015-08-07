<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		/**
		if( !$this->input->is_cli_request() ){
			exit('HTTP Request is not Allowed');
		}
		*/
		$this->load->model('Base_model', 'mBase');
	}
}