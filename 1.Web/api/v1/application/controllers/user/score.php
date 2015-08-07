<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class score extends Base {
	
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
            	'AND' => array('uid='.$uid),
            );
            $res = $this->modUser->getList('users_log_score',$condition,'*','id DESC',$page,$size);
            if( $res->results ){
            	foreach ( $res->results as &$row ){
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
}