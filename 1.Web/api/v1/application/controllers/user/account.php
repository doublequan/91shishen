<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class account extends Base {
	
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
        	if( $this->modUser->getLoginFailCount()>=5 ){
        		$ret = array('err_no'=>3001,'err_msg'=>'login fail too much');
        		break;
        	}
        	$user = array();
        	if( preg_match('/^1[\d]{10}/', $params['account']) ){
        		$user = $this->modUser->getSingle('users','mobile',$params['account']);
        	} elseif ( filter_var($params['account'],FILTER_VALIDATE_EMAIL) ){
        		$user = $this->modUser->getSingle('users','email',$params['account']);
        	} else {
        		$user = $this->modUser->getSingle('users','username',$params['account']);
        	}
        	if( !($user && $user['status']==1) ){
        		$ret = array('err_no'=>3005,'err_msg'=>'account is lock');
        		break;
        	}
            if( $user['pass']!=encryptPass($params['pass'], $user['salt']) ){
            	$data = array(
            		'account'		=> $params['account'],
            		'pass'			=> $params['pass'],
            		'login_ip'		=> getUserIP(),
            		'login_time'	=> time(),
            	);
            	$this->modUser->insert('users_log_login',$data);
            	$ret = array('err_no'=>3004,'err_msg'=>'login failure');
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
            //update user's last login info
            unset($params['mobile'],$params['pass']);
            $data = array(
            	'login_os'		=> $params['os'],
            	'login_ip'		=> strval(getUserIP()),
            	'login_time'	=> strval(time()),
            );
            $this->modUser->update('users',$data,array('id'=>$user['id']));
            //return user info
            $results = array(
            	'sessionid'	=> $session['sessionid'],
            	'username'	=> $user['username'],
            	'discount'	=> $user['discount'],
            	'cardno'	=> $user['cardno'],
            	'avatar'	=> getAvatar($user['id']),
            );
            $ret = array('err_no'=>0,'err_msg'=>'success','results'=>$results);
        } while(0);
        $this->output($ret);
    }
    
    public function register(){
        $ret = array('err_no'=>1000,'err_msg'=>'system error');
        do{
        	//get parameters
        	$must = array('username','mobile','pass','repass');
        	$fields = array('cardno','invite_code');
        	$this->checkParams('post',$must,$fields);
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
        	if( strlen($params['pass'])<6 ){
        		$ret = array('err_no'=>3003,'err_msg'=>'password is not correct');
        		break;
        	}
        	if( $params['pass'] != $params['repass'] ){
        		$ret = array('err_no'=>3004,'err_msg'=>'passwords not match');
        		break;
        	}
        	$user = $this->modUser->getSingle('users','username',$params['username']);
        	if( $user ){
        		$ret = array('err_no'=>3005,'err_msg'=>'username is already exists');
        		break;
        	}
        	$user = $this->modUser->getSingle('users','mobile',$params['mobile']);
        	if( $user ){
        		$ret = array('err_no'=>3006,'err_msg'=>'mobile is already exists');
        		break;
        	}
        	$ip = getUserIP();
        	$time = time();
        	$salt = getRandStr(10);
        	$data = array(
        		'username'		=> $params['username'],
        		'mobile'		=> $params['mobile'],
        		'pass'			=> encryptPass($params['pass'], $salt),
        		'salt'			=> $salt,
        		'status'		=> 1,
        		'login_os'		=> $params['os'],
        		'login_ip'		=> $ip,
        		'login_time'	=> $time,
        		'create_os'		=> $params['os'],
        		'create_ip'		=> $ip,
        		'create_time'	=> $time,
        		'cardno'		=> $params['cardno'],
        	);
            $t = $this->modUser->insert('users',$data);
            $user = $this->modUser->getSingle('users','id',isset($t['id']) ? intval($t['id']) : 0);
            if( !$user ){
                $ret = array('err_no'=>3004,'err_msg'=>'reg failure');
        		break;
            }
            //新用户注册送5元代金券
            $couponData = array(
            	'uid'			=> $user['id'],
            	'coupon_type'	=> 1,
            	'coupon_code'	=> strtoupper(getRandStr(5).substr(md5($user['id']), 0, 5)),
            	'coupon_limit'	=> 0,
            	'coupon_total'	=> 5,
            	'coupon_balance'=> 5,
            	'start'			=> 0,
            	'end'			=> 0,
            	'status'		=> 1,
            	'create_eid'	=> 0,
            	'create_name'	=> '新用户注册赠送',
            	'create_time'	=> time(),
            );
            $this->modUser->insert('users_coupon', $couponData);
            //平安合作会员卡绑定送5元代金券
            if( $params['cardno'] ){
	            $card = $this->modUser->getSingle('users_card_pingan','cardno',$params['cardno']);
	            if( $card && $card['is_bind']==0 ){
		            $couponData = array(
		            	'uid'			=> $user['id'],
		            	'coupon_type'	=> 1,
		            	'coupon_code'	=> strtoupper(getRandStr(5).substr(md5($user['id']), 0, 5)),
		            	'coupon_limit'	=> 0,
		            	'coupon_total'	=> 5,
		            	'coupon_balance'=> 5,
		            	'start'			=> 0,
		            	'end'			=> 0,
		            	'status'		=> 1,
		            	'create_eid'	=> 0,
		            	'create_name'	=> '平安合作卡赠送',
		            	'create_time'	=> time(),
		            );
		            $t = $this->modUser->insert('users_coupon', $couponData);
		            if( $t ){
		            	$updateData = array(
		            		'uid'		=> $user['id'],
		            		'is_bind'	=> 1,
		            		'bind_time'	=> time(),
		            	);
		            	$this->modUser->update('users_card_pingan',$updateData,array('cardno'=>$data['cardno']));
		            }
	            }
            }
            //insert users_session
            $session = array(
            	'uid'			=> $user['id'],
            	'sessionid'		=> md5(session_id()),
            	'os'			=> $params['os'],
            	'expired_time'	=> 0,
            	'create_ip'		=> $ip,
            	'create_time'	=> $time,
            );
            $this->modUser->insert('users_session',$session);
            //判断邀请码和邀请员工
            if( $params['invite_code'] ){
            	$emp = $this->modUser->getSingle('employees','invite_code',$params['invite_code']);
            	if( $emp ){
            		$this->modUser->update(
            			'users',
            			array('invite_eid'=>$emp['id']),
            			array('id'=>$user['id'])
            		);
            	}
            }
            //return userinfo
            $results = array(
            	'sessionid'	=> $session['sessionid'],
            	'username'	=> $user['username'],
            	'discount'	=> $user['discount'],
            	'cardno'	=> $user['cardno'],
            	'avatar'	=> getAvatar($user['id']),
            );
            $ret = array('err_no'=>0,'err_msg'=>'success','results'=>$results);
        } while(0);
        $this->output($ret);
    }
    
    public function modify(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array();
    		$fields = array('cardno','gender','tel','qq','receipt_title','receipt_des');
    		$this->checkParams('post',$must,$fields);
    		$params = $this->params;
    		//get user
    		$user = $this->modUser->getSingle('users_session','sessionid',$params['sessionid'],'uid');
    		if( !($user && $user['uid']) ){
    			$ret = array('err_no'=>3001,'err_msg'=>'user is not login');
    			break;
    		}
    		$user = $this->modUser->getSingle('users','id',$user['uid']);
    		if( !$user ){
    			$ret = array('err_no'=>3001,'err_msg'=>'user is not exists');
    			break;
    		}
    		$data = array(
    			'cardno'		=> $params['cardno'],
    			'gender'		=> $params['gender'],
    			'qq'			=> $params['qq'],
    			'tel'			=> $params['tel'],
    			'receipt_title'	=> $params['receipt_title'],
    			'receipt_des'	=> $params['receipt_des'],
    		);
    		$this->modUser->update('users',$data,array('id'=>$user['id']));
    		//return userinfo
    		$results = array(
    			'sessionid'		=> $params['sessionid'],
    			'username'		=> $user['username'],
    			'discount'		=> $user['discount'],
    			'cardno'		=> $params['cardno'],
    			'avatar'		=> getAvatar($user['id']),
    			'group_id'		=> $user['group_id'],
    			'mobile'		=> $user['mobile'],
    			'email'			=> $user['email'],
    			'balance'		=> $user['balance'],
    			'score'			=> $user['score'],
    			'gender'		=> $params['gender'],
    			'birthday'		=> $user['birthday'],
    			'qq'			=> $params['qq'],
    			'tel'			=> $params['tel'],
    			'mobile_status'	=> $user['mobile_status'],
    			'email_status'	=> $user['email_status'],
    			'receipt_title'	=> $params['receipt_title'],
    			'receipt_des'	=> $params['receipt_des'],
    			'login_ip'		=> long2ip($user['login_ip']),
    			'login_time'	=> date('Y-m-d H:i:s',$user['login_time']),
    			'create_ip'		=> long2ip($user['create_ip']),
    			'create_time'	=> date('Y-m-d H:i:s',$user['create_time']),
    		);
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>$results);
    	} while(0);
    	$this->output($ret);
    }
    
    public function detail(){
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
    		$user = $this->modUser->getSingle('users','id',$user['uid']);
    		if( !($user && $user['id']) ){
    			$ret = array('err_no'=>3001,'err_msg'=>'user is not exists');
    			break;
    		}
    		//return userinfo
    		$results = array(
    			'sessionid'		=> $params['sessionid'],
    			'username'		=> $user['username'],
    			'discount'		=> $user['discount'],
    			'cardno'		=> $user['cardno'],
    			'avatar'		=> getAvatar($user['id']),
    			'group_id'		=> $user['group_id'],
    			'mobile'		=> $user['mobile'],
    			'email'			=> $user['email'],
    			'balance'		=> $user['balance'],
    			'score'			=> $user['score'],
    			'gender'		=> $user['gender'],
    			'birthday'		=> $user['birthday'],
    			'qq'			=> $user['qq'],
    			'tel'			=> $user['tel'],
    			'mobile_status'	=> $user['mobile_status'],
    			'email_status'	=> $user['email_status'],
    			'receipt_title'	=> $user['receipt_title'],
    			'receipt_des'	=> $user['receipt_des'],
    			'login_ip'		=> long2ip($user['login_ip']),
    			'login_time'	=> date('Y-m-d H:i:s',$user['login_time']),
    			'create_ip'		=> long2ip($user['create_ip']),
    			'create_time'	=> date('Y-m-d H:i:s',$user['create_time']),
    		);
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>$results);
    	} while(0);
    	$this->output($ret);
    }

    public function sign(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array('sessionid');
    		$this->checkParams('post',$must);
    		$params = $this->params;
    		//get user
    		$user = $this->modUser->getSingle('users_session','sessionid',$params['sessionid'],'uid');
    		if( !($user && $user['uid']) ){
    			$ret = array('err_no'=>3001,'err_msg'=>'user is not login');
    			break;
    		}
    		$day = date('Y-m-d');
    		$single = $this->modUser->getUserSign($user['uid'],$day);
    		if( $single ){
    			$ret = array('err_no'=>3002,'err_msg'=>'already signed');
    			break;
    		}
    		$params = array(
    			'uid'			=> $user['uid'],
    			'day'			=> $day,
    			'score'			=> 10,
    			'create_time'	=> time(),
    		);
    		$single = $this->modUser->insert('users_sign',$params);
    		if( !$single ){
    			$ret = array('err_no'=>3003,'err_msg'=>'sign failure');
    			break;
    		}
            //add score
            $this->modUser->addUserScore($user['uid'],$params['score'],date('Y-m-d').' 签到赠送积分');
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>array());
    	} while(0);
    	$this->output($ret);
    }
}