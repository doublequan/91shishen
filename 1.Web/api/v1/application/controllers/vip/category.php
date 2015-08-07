<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class category extends Base {
	
	public $table = 'vip_category';

    public function __construct(){
        parent::__construct();
        $this->load->model('Product_model','mProduct');
    }
    
    public function lists(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$this->checkParams('post');
    		$params = $this->params;
    		//get all category
    		$condition = array();
    		$results = array();
    		$res = $this->mProduct->getList($this->table,$condition,'*','sort ASC');
    		if( $res ){
	    		foreach( $res as $row ){
	    			$results[] = array(
	    				'id'	=> intval($row['id']),
	    				'name'	=> $row['name'],
	    				'thumb'	=> $row['thumb'],
	    			);
	    		}
    		}
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>$results);
    	} while(0);
    	$this->output($ret);
    }
}