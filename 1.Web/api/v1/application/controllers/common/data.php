<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class data extends Base {
	
    public function __construct(){
        parent::__construct();
        $this->load->model('Base_model','mBase');
    }
    
    public function site(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get all sites
    		$results = array();
    		$condition = array(
    			'AND' => array('is_del=0','is_off=0'),
    		);
    		$res = $this->mBase->getList('sites',$condition,'*','sort ASC');
    		if( $res ){
	    		foreach( $res as $row ){
	    			$condition = array(
	    				'AND' => array('is_del=0','site_id='.$row['id'],'is_pickup=1'),
	    			);
	    			$stores = $this->mBase->getList('stores',$condition,'id,name');
	    			$results[] = array(
	    				'id'		=> $row['id'],
	    				'name'		=> $row['name'],
	    				'stores'	=> $stores
	    			);
	    		}
    		} else {
    			$condition = array(
    				'AND' => array('is_del=0','site_id=1','is_pickup=1'),
    			);
    			$stores = $this->mBase->getList('stores',$condition,'id,name');
    			$results[] = array(
    				'id'		=> 1,
    				'name'		=> '南京',
    				'stores'	=> $stores
    			);
    		}
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>$results);
    	} while(0);
    	$this->output($ret);
    }
    
    public function area(){
        $ret = array('err_no'=>1000,'err_msg'=>'system error');
        do{
        	//get parameters
        	$must = array();
        	$fields = array('is_all');
        	$this->checkParams('post',$must,$fields);
        	$params = $this->params;
        	//get current site's area
        	$site_id = intval($params['site_id']);
        	$site = $this->mBase->getSingle('sites','id',$site_id);
        	if( !$site ){
        		$ret = array('err_no'=>3000,'err_msg'=>'unknown site');
        		break;
        	}
        	$company = $this->mBase->getSingle('companys','id',$site['company_id']);
        	if( !$company ){
        		$ret = array('err_no'=>3001,'err_msg'=>'unknown company');
        		break;
        	}
        	$prov = $this->mBase->getSingle('areas','id',$company['province_id']);
        	$city = $this->mBase->getSingle('areas','id',$company['city_id']);
        	if( !( $prov && $city ) ){
        		$ret = array('err_no'=>3002,'err_msg'=>'unknown area');
        		break;
        	}
        	//get all areas by city_id
        	$areas = array();
        	$res = $this->getChildArea($city);
        	foreach ( $res as $father_id=>$arr ){
        		if( $father_id==$city['id'] ){
        			foreach ( $arr as $row ){
        				if( isset($res[$row['id']]) ){
        					$areas['district'][] = $row;
        				}
        			}
        		} else {
        			$areas['street'][$father_id] = $arr;
        		}
        	}
        	$results = array(
        		'city_id'	=> $city['id'],
        		'prov'	=> array(
        			'id'	=> $prov['id'],
        			'name'	=> $prov['name'],
        		),
        		'city'	=> array(
        			'id'	=> $city['id'],
        			'name'	=> $city['name'],
        		),
        		'areas'	=> $areas,
        	);
            $ret = array('err_no'=>0,'err_msg'=>'success','results'=>$results);
        } while(0);
        $this->output($ret);
    }
    
    private function getChildArea( $area ){
    	$data = array();
    	$condition = array(
    		'AND' => array('father_id='.$area['id']),
    	);
    	$res = $this->mBase->getList('areas',$condition,'*','sort ASC');
    	if( $res ){
    		foreach( $res as $row ){
    			$data[$row['father_id']][] = array(
    				'id'	=> $row['id'],
    				'name'	=> $row['name'],
    			);
    			$data += $this->getChildArea($row);
    		}
    	} else {
    		$data[$area['father_id']][] = array(
    			'id'	=> $area['id'],
    			'name'	=> $area['name'],
    		);
    	}
    	return $data;
    }
    
    public function about(){
    	$ret = array(
    		'err_no' => 0,
    		'err_msg' => 'success',
    		'results' => array(
    			'date'		=> '2014-10-14',
    			'sv'		=> '0.1.0',
    			'logo'		=> 'http://182.92.159.5/logo.png',
    			'content'	=> '惠生活牛逼！惠生活很牛逼！！惠生活非常牛逼！！！',
    		),
    	);
    	$this->output($ret);
    }
}