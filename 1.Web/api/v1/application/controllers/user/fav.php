<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class fav extends Base {
	
    public function __construct(){
        parent::__construct();
        $this->load->model('User_model','modUser');
    }
    
    public function lists(){
        $ret = array('err_no'=>1000,'err_msg'=>'system error');
        do{
        	//get parameters
        	$must = array();
        	$fields = array('page','size');
        	$this->checkParams('post',$must,$fields);
        	$params = $this->params;
        	$page = max(1,intval($params['page']));
        	$size = max(10,min(100,intval($params['size'])));
        	//get user
        	$user = $this->modUser->getSingle('users_session','sessionid',$params['sessionid'],'uid');
        	if( !($user && $user['uid']) ){
        		$ret = array('err_no'=>3001,'err_msg'=>'user is not login');
        		break;
        	}
        	$uid = $user['uid'];
            //get data
            $condition = array(
            	'AND' => array('is_del=0','uid='.$uid),
            );
            $res = $this->modUser->getList('users_fav',$condition,'*','id DESC',$page,$size);
            if( $res->results ){
            	foreach( $res->results as &$row ){
            		$product = $this->modUser->getSingle('products','id',$row['product_id']);
            		if( !$product ){
            			unset($row);
            		}
            		$row['product_name'] = $product['title'];
            		$row['price'] = $product['price'];
            		$row['thumb'] = $product['thumb'];
            		unset($row['uid'],$row['create_time']);
            	}
            	unset($row);
            }
            $ret = array(
            	'err_no'  =>0,
            	'err_msg' =>'success',
            	'results' => array(
            		'list'  => $res->results,
            		'pager'	=> $res->pager,
            	),
            );
        } while(0);
        $this->output($ret);
    }
    
    public function add(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array('sessionid','product_id');
    		$this->checkParams('post',$must);
    		$params = $this->params;
    		//get user
    		$user = $this->modUser->getSingle('users_session','sessionid',$params['sessionid'],'uid');
        	if( !($user && $user['uid']) ){
        		$ret = array('err_no'=>3001,'err_msg'=>'user is not login');
        		break;
        	}
        	$uid = $user['uid'];
    		//check product
        	$product = $this->modUser->getSingle('products','id',$params['product_id'],'*');
    		if( !$product ){
    			$ret = array('err_no'=>3002,'err_msg'=>'product is not exists');
    			break;
    		}
    		$condition = array(
    			'AND' => array('uid='.$uid,'product_id='.$params['product_id']),
    		);
    		$res = $this->modUser->getList('users_fav',$condition,'*','id DESC',0,0);
    		if( count($res)==0 ){
    			//add user fav
    			$data = array(
    				'uid'			=> $uid,
    				'product_id'	=> $params['product_id'],
    				'create_time'	=> time(),
    			);
    			$this->modUser->insert('users_fav',$data);
    		} else {
    			//update user fav
    			$data = array(
    				'is_del'		=> 0,
    				'create_time'	=> time(),
    			);
    			$whereMap = array(
    				'uid'			=> $uid,
    				'product_id'	=> $params['product_id'],
    			);
    			$this->modUser->update('users_fav',$data,$whereMap);
    		}
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>array());
    	} while(0);
    	$this->output($ret);
    }
    
    public function remove(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array('sessionid','id');
    		$this->checkParams('post',$must);
    		$params = $this->params;
    		//get user
    		$user = $this->modUser->getSingle('users_session','sessionid',$params['sessionid'],'uid');
    		if( !($user && $user['uid']) ){
    			$ret = array('err_no'=>3001,'err_msg'=>'user is not login');
    			break;
    		}
    		$uid = $user['uid'];
    		//get fav
    		$single = $this->modUser->getSingle('users_fav','id',$params['id'],'uid,product_id');
    		if( !$single ){
    			$ret = array('err_no'=>3002,'err_msg'=>'fav is not exists');
    			break;
    		}
    		if( $single['uid']!=$uid ){
    			$ret = array('err_no'=>3003,'err_msg'=>'fav and user not match');
    			break;
    		}
    		//delete fav
    		$whereMap = array(
    			'uid'			=> $uid,
    			'product_id'	=> $single['product_id'],
    		);
    		$this->modUser->delete('users_fav',$whereMap);
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>array());
    	} while(0);
    	$this->output($ret);
    }
}