<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class user extends Base {
	
    public function __construct(){
        parent::__construct();
        $this->load->model('User_model','modUser');
    }
    
    public function index(){
    	exit('403');
    }
    
    public function login(){
        $ret = array('err_no'=>1000,'err_msg'=>'system error');
        do{
        	//get parameters
        	$must = array('account','pass');
        	$this->checkParams('post',$must);
        	$params = $this->params;
        	//check parameters
        	if( $this->modUser->getVIPLoginFailCount()>=5 ){
        		$ret = array('err_no'=>3001,'err_msg'=>'login fail too much');
        		break;
        	}
        	$user = $this->modUser->getSingle('vip_users','username',$params['account']);
            if( !($user && $user['pass']==encryptPass($params['pass'], $user['salt'])) ){
            	$data = array(
            		'account'		=> $params['account'],
            		'pass'			=> $params['pass'],
            		'login_ip'		=> getUserIP(),
            		'login_time'	=> time(),
            	);
            	$this->modUser->insert('vip_users_log_login',$data);
            	$ret = array('err_no'=>3004,'err_msg'=>'login failure');
            	break;
            }
            //generate new sessionid and update
            session_regenerate_id();
            $this->modUser->delete('vip_users_session',array('uid'=>$user['id'],'os'=>$params['os']),true);
            $session = array(
            	'uid'			=> $user['id'],
            	'sessionid'		=> md5(session_id()),
            	'os'			=> $params['os'],
            	'expired_time'	=> 0,
            	'create_ip'		=> getUserIP(),
            	'create_time'	=> time(),
            );
            $this->modUser->insert('vip_users_session',$session);
            //update user's last login info
            unset($params['mobile'],$params['pass']);
            $data = array(
            	'login_ip'		=> strval(getUserIP()),
            	'login_time'	=> strval(time()),
            );
            $this->modUser->update('vip_users',$data,array('id'=>$user['id']));
            //return user info
            $results = array(
            	'sessionid'	=> $session['sessionid'],
            	'username'	=> $user['username'],
            	'discount'	=> $user['discount'],
            	'cardno'	=> '',
            	'avatar'	=> '',
            );
            $ret = array('err_no'=>0,'err_msg'=>'success','results'=>$results);
        } while(0);
        $this->output($ret);
    }
}