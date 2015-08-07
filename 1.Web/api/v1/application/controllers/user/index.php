<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once '../base.php';

class index extends Base {
	
    public function __construct(){
        parent::__construct();
    }
    
    public function index(){
    	exit('403');
    }
}