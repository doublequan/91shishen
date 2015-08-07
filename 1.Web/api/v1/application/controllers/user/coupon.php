<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class coupon extends Base {
	
	public $statusMap = array(
		1 => '未使用',
		2 => '已使用',
		3 => '已过期',
	);
	
    public function __construct(){
        parent::__construct();
        $this->load->model('User_model','modUser');
    }
    
    public function lists(){
        $ret = array('err_no'=>1000,'err_msg'=>'system error');
        do{
        	//get parameters
        	$must = array('status');
        	$fields = array('type','page','size');
        	$this->checkParams('post',$must,$fields);
        	$params = $this->params;
        	$status = intval($params['status']);
        	$status = array_key_exists($status, $this->statusMap) ? $status : current(array_keys($this->statusMap));
        	$params['type'] = 1;
        	$page = max(1,intval($params['page']));
        	$size = max(1,min(100,intval($params['size'])));
        	//get user
        	$user = $this->modUser->getSingle('users_session','sessionid',$params['sessionid'],'uid');
        	if( !($user && $user['uid']) ){
        		$ret = array('err_no'=>3001,'err_msg'=>'user is not login');
        		break;
        	}
        	$uid = $user['uid'];
            //get data
            $condition = array(
            	'AND' => array('uid='.$uid,'coupon_type='.$params['type'],'status='.$status),
            );
            $res = $this->modUser->getList('users_coupon',$condition,'*','id DESC',$page,$size);
            if( $res->results ){
            	foreach( $res->results as &$row ){
            		unset($row['uid'],$row['coupon_type'],$row['coupon_pass'],$row['coupon_salt']);
            		$row['coupon_limit'] = $row['coupon_limit'] ? '单笔订单满￥'.$row['coupon_limit'].'可用' : '消费金额不限';
            		$row['start'] = $row['start'] ? date('Y-m-d',$row['start']) : '不限';
            		$row['end'] = $row['end'] ? date('Y-m-d',$row['end']) : '不限';
            		$row['coupon_code'] = strtoupper($row['coupon_code']);
            		$row['create_time'] = date('Y-m-d H:i:s',$row['create_time']);
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
    
    public function usable(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$this->checkParams('post');
    		$params = $this->params;
    		//get user
    		$user = $this->modUser->getSingle('users_session','sessionid',$params['sessionid'],'uid');
    		if( !($user && $user['uid']) ){
    			$ret = array('err_no'=>3001,'err_msg'=>'user is not login');
    			break;
    		}
    		$uid = $user['uid'];
    		//get data
    		$t = time();
    		$condition = array(
    			'AND' => array(
    				'uid='.$uid,
    				'coupon_type=1',
    				'status=1',
    				'is_lock=0',
    				'coupon_balance>0',
    				'(start=0 OR start<='.$t.')',
    				'(end=0 OR end>='.$t.')',
    			),
    		);
    		$res = $this->modUser->getList('users_coupon',$condition,'*','id DESC');
    		$results = array();
    		if( $res ){
    			foreach( $res as $row ){
    				$results[] = array(
    					'id'		=> $row['id'],
    					'des'		=> '余额￥'.$row['coupon_balance'],
    					'balance'	=> floatval($row['coupon_balance']),
    				);
    			}
    		}
    		$ret = array(
    			'err_no'  =>0,
    			'err_msg' =>'success',
    			'results' => $results,
    		);
    	} while(0);
    	$this->output($ret);
    }
}