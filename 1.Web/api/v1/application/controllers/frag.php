<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/base.php';

class frag extends Base {

    public function __construct(){
        parent::__construct();
        $this->load->model('Product_model','mProduct');
    }
    
    public function lists(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array('site_id');
    		$this->checkParams('post',$must);
    		$params = $this->params;
    		//check site
    		$site_id = intval($params['site_id']);
    		$site = $this->mProduct->getSingle('sites','id',$site_id);
    		if( !$site ){
    			$ret = array('err_no'=>3001,'err_msg'=>'site is not exists');
    			break;
    		}
    		//get all frags
    		$condition = array(
    			'AND' => array('site_id='.$site_id,'os='.$params['os']),
    		);
    		$results = array();
    		$places = $this->mProduct->getList('fragments_place',$condition,'*','id ASC');
    		if( $places ){
    			foreach ( $places as $k=>$place ){
    				$place_id = $k+1;
    				$condition = array(
    					'AND' => array('place_id='.$place['id']),
    				);
    				$fragments = $this->mProduct->getList('fragments',$condition,'*','sort ASC');
    				if( $fragments ){
    					foreach( $fragments as $fragment ){
    						$results[$place_id][] = array(
    							'id'	=> $fragment['url'],
    							'type'	=> $fragment['type'],
    							'url'	=> $fragment['url'],
    							'title'	=> $fragment['title'],
    							'img'	=> $fragment['img'],
    							'des'	=> $fragment['des'],
    						);
    					}
    				}
    			}
    		}
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>$results);
    	} while(0);
    	$this->output($ret);
    }
}