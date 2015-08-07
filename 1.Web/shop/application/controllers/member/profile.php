<?php
/**
 * 会员资料
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-11
 */
require_once 'common.php';
class Profile extends Common{
	
	//会员资料
	public function index(){
		$userInfo = $this->session->userdata('userinfo');
		$this->load->model('Profile_model');
		$data = $this->Profile_model->getProfile($userInfo['uid']);
		$this->load->view('member/profile_index', array('data'=>$data,'currMenu'=>'member_profile'));
	}
	
	//会员头像
	public function header(){
		$this->load->view('member/profile_header',array('currMenu'=>'member_profile'));
	}
	
	//上传头像
	public function upload(){
		//会员资料
		$userInfo = $this->session->userdata('userinfo');
		//上传配置
		$finger = md5($userInfo['uid']);
		$finger = substr_replace($finger, '/', 6, 0);
		$finger = substr_replace($finger, '/', 4, 0);
		$finger = substr_replace($finger, '/', 2, 0);
		$savepath = 'avatar/'.$finger;
		if( ! is_dir($savepath))  mkdirs($savepath);
		$savepath .=  '/'.$userInfo['uid'].'.jpg';
		$realpath = FCPATH.$savepath;
    	$xmlstr = $GLOBALS['HTTP_RAW_POST_DATA'];
	    $jpg = empty($xmlstr) ? file_get_contents('php://input') : $xmlstr;
	    if(false != file_put_contents($realpath, $jpg)){
	    	$data = array('code'=>200);
	    }else{
	    	$data = array('code'=>0);
	    }
	    exit(json_encode($data));
    }
	
	//执行更新
	public function ajax_upd(){
		//仅限Ajax访问
		$this->input->is_ajax_request() || show_404();
		//会员信息
		$userInfo = $this->session->userdata('userinfo');
		//接收数据
		$data['gender'] = intval($this->input->post('gender', true));
		$data['birthday'] = trim($this->input->post('birthday', true));
		$data['tel'] = trim($this->input->post('tel', true));
		$data['qq'] = trim($this->input->post('qq', true));
		//格式验证
		$this->load->helper('verify');
		if( ( ! empty($data['tel']) && ! isTelephone($data['tel'])) ||
			( ! empty($data['qq']) && ! isQq($data['qq'])) ||
			( ! empty($data['birthday']) && ! isDate($data['birthday']))
		){
			$err['err_no'] = 1002;
			$err['err_msg'] = parent::$errorType[1002];
			exit(json_encode($err));
		}
		//执行更新
		if(false != $this->Base_model->update('users', $data, array('id'=>$userInfo['uid']))){
			$err['err_no'] = 0;
			$err['err_msg'] = parent::$errorType[0];
		}else{
			$err['err_no'] = 1000;
			$err['err_msg'] = parent::$errorType[1000];
		}
		exit(json_encode($err));
	}
}