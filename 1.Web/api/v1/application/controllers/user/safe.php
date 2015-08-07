<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class safe extends Base {
	
    public function __construct(){
        parent::__construct();
        $this->load->model('User_model','modUser');
    }
    
    public function index(){
    	exit('403');
    }
    
    public function reset(){
        $ret = array('err_no'=>1000,'err_msg'=>'system error');
        do{
        	//get parameters
        	$must = array('oldpass','pass','repass','pass');
        	$this->checkParams('post',$must);
        	$params = $this->params;
        	//get user
        	$user = $this->modUser->getSingle('users_session','sessionid',$params['sessionid'],'uid');
        	if( !($user && $user['uid']) ){
        		$ret = array('err_no'=>3001,'err_msg'=>'user is not login');
        		break;
        	}
        	$user = $this->modUser->getSingle('users','id',$user['uid']);
        	if( !($user && $user['id']) ){
        		$ret = array('err_no'=>3002,'err_msg'=>'user is not exists');
        		break;
        	}
        	//check parameters
        	if( strlen($params['oldpass'])<6 ){
        		$ret = array('err_no'=>3003,'err_msg'=>'old password illegal');
        		break;
        	}
        	if( $user['pass']!=encryptPass($params['oldpass'], $user['salt']) ){
        		$ret = array('err_no'=>3004,'err_msg'=>'old password error');
        		break;
        	}
        	if( strlen($params['pass'])<6 ){
        		$ret = array('err_no'=>3005,'err_msg'=>'new password illegal');
        		break;
        	}
        	if( $params['pass'] != $params['repass'] ){
        		$ret = array('err_no'=>3006,'err_msg'=>'new passwords not match');
        		break;
        	}
            //generate new sessionid and update
            session_regenerate_id();
            $this->modUser->delete('users_session',array('uid'=>$user['id'],'os'=>$params['os']),true);
            $sessionid = md5(session_id());
            $session = array(
            	'uid'			=> $user['id'],
            	'sessionid'		=> $sessionid,
            	'os'			=> $params['os'],
            	'expired_time'	=> 0,
            	'create_ip'		=> getUserIP(),
            	'create_time'	=> time(),
            );
            $this->modUser->insert('users_session',$session);
            //update userinfo
            $salt = getRandStr(10);
        	$data = array(
        		'pass'	=> encryptPass($params['pass'], $salt),
        		'salt'	=> $salt,
        	);
            $this->modUser->update('users',$data,array('id'=>$user['id']));
            //return userinfo
            unset($user['pass'],$user['salt']);
            $user['sessionid'] = $session['sessionid'];
            $results = array(
            	'sessionid'	=> $sessionid,
            	'username'	=> $user['username'],
            	'discount'	=> $user['discount'],
            	'cardno'	=> $user['cardno'],
            	'avatar'	=> getAvatar($user['id']),
            );
            $ret = array('err_no'=>0,'err_msg'=>'success','results'=>$results);
        } while(0);
        $this->output($ret);
    }
    
    public function forget(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array('username','mobile');
    		$this->checkParams('post',$must);
    		$params = $this->params;
    		//check parameters
    		$params['username'] = strtolower($params['username']);
        	if( !preg_match('/^[0-9a-z]{6,20}$/',$params['username']) ){
        		$ret = array('err_no'=>3001,'err_msg'=>'username is not correct');
        		break;
        	}
        	if( !preg_match('/^1[\d]{10}$/',$params['mobile']) ){
        		$ret = array('err_no'=>3002,'err_msg'=>'mobile is not correct');
        		break;
        	}
        	//check username and mobile
        	$user = $this->modUser->getSingle('users','username',$params['username']);
        	if( !($user && $user['id']) ){
        		$ret = array('err_no'=>3003,'err_msg'=>'username is not exists');
        		break;
        	}
        	$user = $this->modUser->getSingle('users','mobile',$params['mobile']);
        	if( !($user && $user['id']) ){
        		$ret = array('err_no'=>3004,'err_msg'=>'mobile is not exists');
        		break;
        	}
    		//generate new sessionid and update
    		session_regenerate_id();
    		$this->modUser->delete('users_session',array('uid'=>$user['id'],'os'=>$params['os']),true);
    		$session = array(
    			'uid'			=> $user['id'],
    			'sessionid'		=> md5(session_id()),
    			'os'			=> $params['os'],
    			'expired_time'	=> 0,
    			'create_ip'		=> getUserIP(),
    			'create_time'	=> time(),
    		);
    		$this->modUser->insert('users_session',$session);
    		//send user's password
    		$pass = strtolower(getRandStr(8));
    		$content = '您的密码已经重设为：'.$pass.'，请您登录后尽快修改';
    		//$content = iconv('UTF-8','GB2312//TRANSLIT',$content);
    		Common_Sms::sendAD($params['mobile'], $content);
    		$data = array(
    			'mobile'		=> $params['mobile'],
    			'content'		=> $content,
    			'create_time'	=> time(),
    		);
    		$this->modUser->insert('logs_sms',$data);
    		//reset user's password
    		$salt = getRandStr(10);
    		$data = array(
    			'pass'	=> encryptPass($pass, $salt),
    			'salt'	=> $salt,
    		);
    		$this->modUser->update('users',$data,array('id'=>$user['id']));
    		//return main userinfo
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>array());
    	} while(0);
    	$this->output($ret);
    }
}