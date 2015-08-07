<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class special extends Base {

    public function __construct(){
        parent::__construct();
        $this->load->model('Product_model','mProduct');
    }
    
    public function detail(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
        	$must = array('special_id');
        	$this->checkParams('post',$must);
        	$params = $this->params;
        	$site_id = intval($params['site_id']);
        	$special_id = intval($params['special_id']);
        	//get special
        	if( !$special_id ){
        		$ret = array('err_no'=>3001,'err_msg'=>'special is not exists');
        		break;
        	}
    		$special = $this->mBase->getSingle('specials','id',$special_id);
    		if( !$special || $special['is_del']==1 ){
    			$ret = array('err_no'=>3001,'err_msg'=>'special is not exists');
    			break;
    		}
    		$curr = time();
    		$start = $special['day_start'] ? strtotime($special['day_start']) : 0;
    		$end = $special['day_end'] ? strtotime($special['day_end']) : 0;
    		$end = $end ? $end+86400 : 0;
    		if( $start && $start>$curr ){
    			$ret = array('err_no'=>3002,'err_msg'=>'special is not start');
    			break;
    		}
    		if( $end && $end<=$curr ){
    			$ret = array('err_no'=>3002,'err_msg'=>'special is already end');
    			break;
    		}
    		//check site
    		$sites = array();
    		$condition = array(
    			'AND' => array('special_id='.$special_id),
    		);
    		$res = $this->mBase->getList('specials_site',$condition);
    		if( $res ){
    			foreach ( $res as $row ){
    				$sites[$row['site_id']] = $row;
    			}
    		}
    		if( !isset($sites[$site_id]) ){
    			$ret = array('err_no'=>3002,'err_msg'=>'current site is not avaliable');
    			break;
    		}
    		//get all avaliable sepcials
    		$sepcials = array();
    		$condition = array(
    			'AND' => array('is_del=0'),
    		);
    		$res = $this->mBase->getList('specials',$condition);
    		if( $res ){
    			foreach ( $res as $row ){
    				$start = $row['day_start'] ? strtotime($row['day_start']) : 0;
    				$end = $row['day_end'] ? strtotime($row['day_end']) : 0;
    				$end = $end ? $end+86400 : 0;
    				if( ($start || $start<=$curr) && ($end || $end>$curr) ){
    					$sepcials[] = array(
    						'id'	=> $row['id'],
    						'name'	=> $row['name'],
    						'curr'	=> $row['id']==$special_id ? true : false,
    					);
    				}
    			}
    		}
    		//get special products
    		$products = array();
    		$this->load->model('Product_model', 'mProduct');
    		$res = $this->mProduct->getSpecialProducts($special_id);
    		if( $res ){
    			foreach ( $res as $i=>$row ){
    				$products[] = array(
    					'id'			=> $row['id'],
    					'category_id'	=> $row['category_id'],
    					'type'			=> $row['type'],
    					'title'			=> $row['title'],
    					'price'			=> $row['price'],
    					'price_market'	=> $row['price_market'],
    					'spec'			=> $row['spec'],
    					'book_date'		=> date('Y-m-d H:i:s',$row['book_time']),
    					'book_time'		=> $row['book_time'],
    					'thumb'			=> $row['thumb'],
    				);
    			}
    		}
    		$ret = array(
    			'err_no'	=> 0,
    			'err_msg'	=> 'success',
    			'results'	=> array(
	    			'name'		=> $special['name'],
	    			'banner'	=> $special['banner_app'],
	    			'spcials'	=> $sepcials,
	    			'products'	=> $products,
	    		),
    		);
    	} while(0);
    	$this->output($ret);
    }
}