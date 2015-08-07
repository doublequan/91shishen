<?php

require_once 'base.php';
class App extends Base{
	
	public function index(){
		$data = array();
		$data['is_home'] = 0;
		$this->load->view('app_index', $data);
	}
}