<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class promotion extends Base {

    public function __construct(){
        parent::__construct();
        $this->load->model('Product_model','mProduct');
    }
    
    public function free(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
        	$must = array();
        	$this->checkParams('post',$must);
        	$params = $this->params;
        	//get rand free products
        	$product = $this->mProduct->getFreeProducts($params['site_id']);
        	if( !$product ){
        		$ret = array('err_no'=>3001,'err_msg'=>'product is not exists');
        		break;
        	}
    		$promotion = '此商品支持0元抢购';
    		$ret = array(
    			'err_no'  => 0,
    			'err_msg' => 'success',
    			'results' => array(
    				'id'			=> $product['id'],
    				'title'			=> $product['title'],
    				'des'			=> $product['seo_title'],
    				'price'			=> $product['price'],
    				'thumb'			=> $product['thumb'],
    			),
    		);
    		//update click count
    		$this->mProduct->setProductNum($product['id'],'click',1);
    	} while(0);
    	$this->output($ret);
    }
}