<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class category extends Base {

    public function __construct(){
        parent::__construct();
        $this->load->model('Product_model','mProduct');
    }
    
    public function index(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array();
    		$this->checkParams('post',$must);
    		$params = $this->params;
    		//get all category
    		$condition = array(
    			'AND' => array('is_del=0'),
    		);
    		$results = array();
    		$res = $this->mProduct->getList('goods_category',$condition,'*','sort ASC');
    		if( $res ){
	    		$t = array();
	    		foreach( $res as $row ){
	    			$t[$row['father_id']][] = array(
	    				'id'	=> intval($row['id']),
	    				'name'	=> $row['name'],
	    				'thumb'	=> $row['thumb_app'],
	    			);
	    		}
	    		if( $t[0] ){
		    		foreach( $t[0] as $r1 ){
		    			if( isset($t[$r1['id']]) && $t[$r1['id']] ){
		    				foreach ( $t[$r1['id']] as $r2 ){
		    					if( isset($t[$r2['id']]) && $t[$r2['id']] ){
		    						foreach ( $t[$r2['id']] as $r3 ){
		    							$r3['childs'] = array();
		    							$r2['childs'][] = $r3;
		    						}
		    					} else {
		    						$r2['childs'] = array();
		    					}
		    					$r1['childs'][] = $r2;
		    				}
		    			} else {
		    				$r1['childs'] = array();
		    			}
		    			$results[] = $r1;
		    		}
	    		}
    		}
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>$results);
    	} while(0);
    	$this->output($ret);
    }
}