<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class client extends Base {
	
    public function __construct(){
        parent::__construct();
        $this->load->model('User_model','modUser');
    }
    
    public function install(){
        $ret = array('err_no'=>1000,'err_msg'=>'system error');
        do{
        	//get parameters
        	$must = array('deviceid','pushtoken','sv','os','brand','screen','net');
        	$this->checkParams('post',$must);
        	$params = $this->params;
        	$params['os_str'] = $params['os'];
        	$params['os'] = strpos(strtolower($params['os']), 'android')===false ? 1 : 0;
        	//get device
        	$single = $this->modUser->getSingle('users_device','deviceid',$params['deviceid']);
        	$data = array(
        		'uid'			=> 0,
        		'deviceid'		=> $params['deviceid'],
        		'pushtoken'		=> $params['pushtoken'],
        		'os'			=> $params['os'],
        		'os_str'		=> $params['os_str'],
        		'sv'			=> $params['sv'],
        		'brand'			=> $params['brand'],
        		'screen'		=> $params['screen'],
        		'net'			=> $params['net'],
        		'create_time'	=> time(),
        	);
        	if( !$single ){
	        	//insert user_device
	            $this->modUser->insert('users_device',$data);
        	} else {
	            //update user_device
	            unset($data['deviceid']);
	            $data['modify_time'] = time();
	            $this->modUser->update('users_device',$data,array('id'=>$single['id']));
        	}
            $ret = array('err_no'=>0,'err_msg'=>'success','results'=>array());
        } while(0);
        $this->output($ret);
    }
    
    public function run(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
        do{
        	//get parameters
        	$must = array('deviceid','pushtoken','sv','os','brand','screen','net');
        	$this->checkParams('post',$must);
        	$params = $this->params;
        	$params['os_str'] = $params['os'];
        	$params['os'] = strpos(strtolower($params['os']), 'android')===false ? 1 : 0;
        	//check session
        	$uid = 0;
        	if( $params['sessionid'] ){
	        	$user = $this->modUser->getSingle('users_session','sessionid',$params['sessionid'],'uid');
	        	if( $user && $user['uid'] ){
	        		$uid = intval($user['uid']);
	        	}
        	}
        	//get device
        	$single = $this->modUser->getSingle('users_device','deviceid',$params['deviceid']);
        	$data = array(
        		'uid'			=> $uid,
        		'deviceid'		=> $params['deviceid'],
        		'pushtoken'		=> $params['pushtoken'],
        		'os'			=> $params['os'],
        		'os_str'		=> $params['os_str'],
        		'sv'			=> $params['sv'],
        		'brand'			=> $params['brand'],
        		'screen'		=> $params['screen'],
        		'net'			=> $params['net'],
        		'create_time'	=> time(),
        	);
        	if( !$single ){
	        	//insert user_device
	            $this->modUser->insert('users_device',$data);
        	} else {
	            //update user_device
	            unset($data['deviceid']);
	            $data['modify_time'] = time();
	            $this->modUser->update('users_device',$data,array('id'=>$single['id']));
        	}
        	//get all sites
        	$sites = array();
        	$condition = array(
        		'AND' => array('is_del=0','is_off=0'),
        	);
        	$res = $this->modUser->getList('sites',$condition,'*','sort ASC');
        	if( $res ){
        		foreach( $res as $row ){
        			$condition = array(
        				'AND' => array('is_del=0','site_id='.$row['id'],'is_pickup=1'),
        			);
        			$stores = $this->modUser->getList('stores',$condition,'id,name');
        			$sites[] = array(
        				'id'		=> $row['id'],
        				'name'		=> $row['name'],
        				'stores'	=> $stores
        			);
        		}
        	} else {
        		$condition = array(
        			'AND' => array('is_del=0','site_id=1','is_pickup=1'),
        		);
        		$stores = $this->modUser->getList('stores',$condition,'id,name');
        		$sites[] = array(
        			'id'		=> 1,
        			'name'		=> '南京',
        			'stores'	=> $stores
        		);
        	}
        	//get user stat
        	$results = array(
        		'cart'	=> 0,
        		'msg'	=> 0,
        		'sites'	=> $sites,
        	);
        	if( $uid ){
	        	//get cart
        		$cart = $this->modUser->getSingle('users_cart','uid',$uid);
        		if( isset($cart['content']) ){
        			$t = json_decode($cart['content'],true);
        			$results['cart'] = isset($t['products']) ? count($t['products']) : 0;
        		}
	        	//get new msg
	        	$condition = array(
	        		'AND' => array('uid='.$uid,'is_read=0'), 
	        	);
	        	$results['msg'] = intval($this->modUser->getCount('users_msg',$condition));
        	}
        	//shipping
        	$results['shipping_freelimit'] = intval(SHIPPING_FREELIMIT);
        	$results['shipping_fee'] = intval(SHIPPING_FEE);
        	//get current city
        	$site_id = 1;
        	if( $params['loc']!='0,0' ){
        		$loc = explode(',', $params['loc']);
        		$params = array(
        			'location'	=> $loc[1].','.$loc[0],
        			'ak'		=> '090f44353dc2004e6975f3d144e87edd',
        			'output'	=> 'json',
        		);
        		$url = 'http://api.map.baidu.com/geocoder/v2/?'.http_build_query($params);
        		$json = curlRequest($url);
        		$arr = json_decode($json,true);
        		if( isset($arr['result']['addressComponent']['city']) ){
        			$city = $arr['result']['addressComponent']['city'];
        			$city = str_replace('市', '', $city);
        			$cache_key = 'LOC_CITY_'.$city;
        			$site_id = Common_Cache::get($cache_key);
        			if( !$site_id ){
        				$site = $this->modUser->getSingle('sites','name',$city);
        				if( $site ){
        					$site_id = $site['id'];
        					Common_Cache::save($cache_key, $site_id, 86400);
        				}
        			}
        		}
        	}
        	$results['site_id'] = intval($site_id);
            $ret = array('err_no'=>0,'err_msg'=>'success','results'=>$results);
        } while(0);
        $this->output($ret);
    }
    
    public function info(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array();
    		$this->checkParams('post',$must);
    		$params = $this->params;
    		//shipping
    		$results['shipping_freelimit'] = intval(SHIPPING_FREELIMIT);
    		$results['shipping_fee'] = intval(SHIPPING_FEE);
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>$results);
    	} while(0);
    	$this->output($ret);
    }
}