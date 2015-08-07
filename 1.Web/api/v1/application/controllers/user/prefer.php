<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class prefer extends Base {
	
    public function __construct(){
        parent::__construct();
        $this->load->model('User_model','modUser');
    }
    
    public function get(){
        $ret = array('err_no'=>1000,'err_msg'=>'system error');
        do{
        	$this->checkParams('post');
        	$params = $this->params;
        	//get user
        	$user = $this->modUser->getSingle('users_session','sessionid',$params['sessionid'],'uid');
        	if( !($user && $user['uid']) ){
        		$ret = array('err_no'=>3001,'err_msg'=>'user is not login');
        		break;
        	}
        	$uid = $user['uid'];
            //get user's prefer
        	$prefer = $this->modUser->getSingle('users_prefer','uid',$uid);
            $ret = array(
            	'err_no'  =>0,
            	'err_msg' =>'success',
            	'results' => $prefer,
            );
        } while(0);
        $this->output($ret);
    }
    
    public function set(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array('pay_type','delivery_type');
    		$fields = array('store_id','prov','city','district','zip','address','receiver','tel','mobile','is_receipt','receipt_title','receipt_des');
    		$this->checkParams('post',$must,$fields);
    		$params = $this->params;
    		//get user
    		$user = $this->modUser->getSingle('users_session','sessionid',$params['sessionid'],'uid');
        	if( !($user && $user['uid']) ){
        		$ret = array('err_no'=>3001,'err_msg'=>'user is not login');
        		break;
        	}
        	$uid = $user['uid'];
        	//check parameters
        	if( !in_array($params['pay_type'], array(1,2,3)) ){
        		$ret = array('err_no'=>3002,'err_msg'=>'pay_type is not exists');
        		break;
        	}
        	if( !in_array($params['delivery_type'], array(0,1)) ){
        		$ret = array('err_no'=>3003,'err_msg'=>'delivery_type is not exists');
        		break;
        	}
        	//create data
        	$data = array(
        		'pay_type'		=> $params['pay_type'],
        		'delivery_type'	=> $params['delivery_type'],
        		'is_receipt'	=> $params['is_receipt'],
        		'receipt_title'	=> $params['receipt_title'],
        		'receipt_des'	=> $params['receipt_des'],
        	);
        	if( $params['delivery_type']==0 ){
        		if( !$params['store_id'] ){
        			$ret = array('err_no'=>3004,'err_msg'=>'store_id is zero');
        			break;
        		}
        		$store = $this->modUser->getSingle('stores','id',$params['store_id']);
        		if( !$store ){
        			$ret = array('err_no'=>3005,'err_msg'=>'store is not exists');
        			break;
        		}
        		$data['store_id'] = $params['store_id'];
        	} else {
        		$arr = array('prov','city','district','zip','address','receiver');
        		$flag = true;
        		foreach( $arr as $v ){
        			if( $params[$v]=='' ){
        				$ret = array('err_no'=>3006,'err_msg'=>'address parameter '.$v.' is empty');
        				$flag = false;
        				break;
        			}
        		}
        		if( !$flag ){
        			break;
        		}
        		if( $params['tel']=='' && $params['mobile']=='' ){
        			$ret = array('err_no'=>3007,'err_msg'=>'tel and mobile both empty');
        			break;
        		}
        		$prov = $this->modUser->getSingle('areas','id',$params['prov']);
        		$city = $this->modUser->getSingle('areas','id',$params['city']);
        		$district = $this->modUser->getSingle('areas','id',$params['district']);
        		$data['prov']		= isset($prov['name']) ? $prov['name'] : '';
        		$data['city']		= isset($city['name']) ? $city['name'] : '';
        		$data['district']	= isset($district['name']) ? $district['name'] : '';
        		$data['zip']		= $params['zip'];
        		$data['address']	= $params['address'];
        		$data['receiver']	= $params['receiver'];
        		$data['tel']		= $params['tel'] ? $params['tel'] : '';
        		$data['mobile']		= $params['mobile'] ? $params['mobile'] : '';
        	}
        	//get user's prefer
        	$prefer = $this->modUser->getSingle('users_prefer','uid',$uid);
        	if( !$prefer ){
        		//insert new prefer
        		$data['uid'] = $uid;
        		$prefer = $this->modUser->insert('users_prefer',$data);
        	} else {
        		//update current user's prefer
        		$this->modUser->update('users_prefer',$data,array('id'=>$prefer['id']));
        		$prefer = array_merge($prefer,$data);
        	}
        	$ret = array(
        		'err_no'  =>0,
        		'err_msg' =>'success',
        		'results' => $prefer,
        	);
    	} while(0);
    	$this->output($ret);
    }
}