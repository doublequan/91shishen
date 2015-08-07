<?php
/**
 * 收货地址
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-6
 */
require_once 'common.php';
class Address extends Common{
	
	//地址列表
	public function index(){
		//会员信息
		$userInfo = $this->session->userdata('userinfo');
		$userData = $this->Base_model->getRow("SELECT tel,mobile FROM users WHERE id = {$userInfo['uid']}");
		//地址信息
		$this->load->model('Address_model');
		$data = $this->Address_model->getAddress($userInfo['uid']);
		$this->load->view('member/address_index', array('data'=>$data, 'userdata'=>$userData, 'currMenu'=>'member_address'));
	}
	
	//设置默认地址
	public function ajax_set(){
		//仅限Ajax访问
		$this->input->is_ajax_request() || show_404();
		//会员信息
		$userInfo = $this->session->userdata('userinfo');
		//执行设置
		$id = intval($this->input->post('address_id'));
		if(false != $this->Base_model->update('users_address', array('is_default'=>1), array('uid'=>$userInfo['uid'], 'id'=>$id))){
			//保证默认地址唯一性
			$this->db->query("UPDATE users_address SET is_default = 0 WHERE uid = {$userInfo['uid']} AND id != {$id}");
			$err['err_no'] = 0;
			$err['err_msg'] = parent::$errorType[0];
		}else{
			$err['err_no'] = 1000;
			$err['err_msg'] = parent::$errorType[1000];
		}
		exit(json_encode($err));
	}
	
	//获取地址
	public function ajax_get(){
		//仅限Ajax访问
		$this->input->is_ajax_request() || show_404();
		//会员信息
		$userInfo = $this->session->userdata('userinfo');
		//地址信息
		$id = intval($this->input->post('address_id'));
		$this->load->model('Address_model');
		$data = $this->Address_model->getAddressById($id, $userInfo['uid']);
		exit(json_encode($data));
	}
	
	//添加地址
	public function ajax_add(){
		//仅限Ajax访问
		$this->input->is_ajax_request() || show_404();
		//会员信息
		$userInfo = $this->session->userdata('userinfo');
		//接收数据
		$data['uid'] = $userInfo['uid'];
		$data['receiver'] = trim($this->input->post('receiver', true));
		$data['zip'] = trim($this->input->post('zip', true));
		$data['tel'] = trim($this->input->post('tel', true));
		$data['prov'] = intval($this->input->post('prov'));
		$data['city'] = intval($this->input->post('city'));
		$data['district'] = intval($this->input->post('district'));
		$data['street'] = intval($this->input->post('street'));
		$data['address'] = trim($this->input->post('address', true));
		$data['mobile'] = trim($this->input->post('mobile', true));
		$data['is_default'] = intval($this->input->post('is_default', true)) > 0 ? 1 : 0;
		$data['create_time'] = time();
		//格式验证
		$this->load->helper('verify');
		if( ! (isMobile($data['mobile']) && (empty($data['tel']) || isTelephone($data['tel']))) ){
			$err['err_no'] = 1002;
			$err['err_msg'] = parent::$errorType[1002];
			exit(json_encode($err));
		}
		//执行添加
		$result = $this->Base_model->insert('users_address', $data);
		if(false != $result){
			//保证默认地址唯一性
			if($data['is_default'] == 1){
				$this->db->query("UPDATE users_address SET is_default = 0 WHERE uid = {$userInfo['uid']} AND id != {$result['id']}");
			}
			$err['err_no'] = 0;
			$err['err_msg'] = parent::$errorType[0];
		}else{
			$err['err_no'] = 1000;
			$err['err_msg'] = parent::$errorType[1000];
		}
		exit(json_encode($err));
	}
	
	//更新地址
	public function ajax_upd(){
		//仅限Ajax访问
		$this->input->is_ajax_request() || show_404();
		//会员信息
		$userInfo = $this->session->userdata('userinfo');
		//接收数据
		$id = intval($this->input->post('id'));
		$data['receiver'] = trim($this->input->post('receiver', true));
		$data['zip'] = trim($this->input->post('zip', true));
		$data['tel'] = trim($this->input->post('tel', true));
		$data['prov'] = intval($this->input->post('prov'));
		$data['city'] = intval($this->input->post('city'));
		$data['district'] = intval($this->input->post('district'));
		$data['street'] = intval($this->input->post('street'));
		$data['address'] = trim($this->input->post('address', true));
		$data['mobile'] = trim($this->input->post('mobile', true));
		$data['is_default'] = intval($this->input->post('is_default', true)) > 0 ? 1 : 0;
		//格式验证
		$this->load->helper('verify');
		if( ! (isMobile($data['mobile']) && (empty($data['tel']) || isTelephone($data['tel']))) ){
			$err['err_no'] = 1002;
			$err['err_msg'] = parent::$errorType[1002];
			exit(json_encode($err));
		}
		//执行更新
		if(false != $this->Base_model->update('users_address', $data, array('uid'=>$userInfo['uid'], 'id'=>$id))){
			//保证默认地址唯一性
			if($data['is_default'] == 1){
				$this->db->query("UPDATE users_address SET is_default = 0 WHERE uid = {$userInfo['uid']} AND id != {$id}");
			}
			$err['err_no'] = 0;
			$err['err_msg'] = parent::$errorType[0];
		}else{
			$err['err_no'] = 1000;
			$err['err_msg'] = parent::$errorType[1000];
		}
		exit(json_encode($err));
	}
	
	//删除地址
	public function ajax_del(){
		//仅限Ajax访问
		$this->input->is_ajax_request() || show_404();
		//会员信息
		$userInfo = $this->session->userdata('userinfo');
		//执行删除
		$id = intval($this->input->post('address_id'));
		$result = $this->Base_model->delete('users_address', array('uid'=>$userInfo['uid'], 'id'=>$id), true);
		if($result > 0){
			$err['err_no'] = 0;
			$err['err_msg'] = parent::$errorType[0];
		}else{
			$err['err_no'] = 1000;
			$err['err_msg'] = parent::$errorType[1000];
		}
		exit(json_encode($err));
	}
}