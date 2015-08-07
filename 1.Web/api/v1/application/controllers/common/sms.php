<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class sms extends Base {
	
    public function __construct(){
        parent::__construct();
        $this->load->model('User_model','modUser');
    }
    
    public function send(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array('mobile');
    		$this->checkParams('post',$must);
    		$params = $this->params;
    		//check parameters
        	if( !preg_match('/^1[\d]{10}$/',$params['mobile']) ){
        		$ret = array('err_no'=>3001,'err_msg'=>'mobile is not correct');
        		break;
        	}
        	//check send count
        	$num = $this->modUser->getSMSCount($params['mobile']);
        	if( $num>5 ){
        		$ret = array('err_no'=>3002,'err_msg'=>'request send too much');
        		break;
        	}
        	$verify = mt_rand(100000,999999);
    		$content = '您的验证码是：'.$verify;
    		//$contentSend = iconv('UTF-8','GB2312//TRANSLIT',$content);
    		Common_Sms::send($params['mobile'], $verify);
    		$data = array(
    			'mobile'		=> $params['mobile'],
    			'verify'		=> $verify,
    			'content'		=> $content,
    			'create_ip'		=> getUserIP(),
    			'create_time'	=> time(),
    		);
    		$this->modUser->insert('logs_sms',$data);
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>array());
    	} while(0);
    	$this->output($ret);
    }
}