<?php
/**
 * VIP收货地址
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-27
 */
require_once 'common.php';
class Address extends Common{
	
	//地址列表
	public function index(){
		//会员信息
		$vipInfo = $this->session->userdata('vipinfo');
		$vipData = $this->Base_model->getRow("SELECT mobile FROM vip_users WHERE id = {$vipInfo['uid']}");
		//地址信息
		$this->load->model('Address_model');
		$data = $this->Address_model->getAddress($vipInfo['uid'], true);
		$this->load->view('vip/address_index', array('data'=>$data, 'vipdata'=>$vipData, 'currMenu'=>'vip_address'));
	}
	
	//设置默认地址
	public function ajax_set(){
		//仅限Ajax访问
		$this->input->is_ajax_request() || show_404();
		//会员信息
		$vipInfo = $this->session->userdata('vipinfo');
		//执行设置
		$id = intval($this->input->post('address_id'));
		if(false != $this->Base_model->update('vip_users_address', array('is_default'=>1), array('uid'=>$vipInfo['uid'], 'id'=>$id))){
			//保证默认地址唯一性
			$this->db->query("UPDATE vip_users_address SET is_default = 0 WHERE uid = {$vipInfo['uid']} AND id != {$id}");
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
		$vipInfo = $this->session->userdata('vipinfo');
		//地址信息
		$id = intval($this->input->post('address_id'));
		$this->load->model('Address_model');
		$data = $this->Address_model->getAddressById($id, $vipInfo['uid'], true);
		exit(json_encode($data));
	}
	
	//添加地址
	public function ajax_add(){
		//仅限Ajax访问
		$this->input->is_ajax_request() || show_404();
		//会员信息
		$vipInfo = $this->session->userdata('vipinfo');
		//接收数据
		$data['uid'] = $vipInfo['uid'];
		$data['receiver'] = trim($this->input->post('receiver', true));
		$data['zip'] = trim($this->input->post('zip', true));
		$data['prov'] = trim($this->input->post('prov', true));
		$data['city'] = trim($this->input->post('city', true));
		$data['district'] = trim($this->input->post('district', true));
		$data['address'] = trim($this->input->post('address', true));
		$data['mobile'] = trim($this->input->post('mobile', true));
		$data['is_default'] = intval($this->input->post('is_default', true)) > 0 ? 1 : 0;
		$data['create_time'] = time();
		//格式验证
		$this->load->helper('verify');
		if( ! (
			isZipcode($data['zip']) && isZipcode($data['prov'])
			&& isZipcode($data['city']) && isZipcode($data['district'])
			&& isMobile($data['mobile'])
		)){
			$err['err_no'] = 1002;
			$err['err_msg'] = parent::$errorType[1002];
			exit(json_encode($err));
		}
		//执行添加
		$result = $this->Base_model->insert('vip_users_address', $data);
		if(false != $result){
			//保证默认地址唯一性
			if($data['is_default'] == 1){
				$this->db->query("UPDATE vip_users_address SET is_default = 0 WHERE uid = {$vipInfo['uid']} AND id != {$result['id']}");
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
		$vipInfo = $this->session->userdata('vipinfo');
		//接收数据
		$id = intval($this->input->post('id'));
		$data['receiver'] = trim($this->input->post('receiver', true));
		$data['zip'] = trim($this->input->post('zip', true));
		$data['prov'] = trim($this->input->post('prov', true));
		$data['city'] = trim($this->input->post('city', true));
		$data['district'] = trim($this->input->post('district', true));
		$data['address'] = trim($this->input->post('address', true));
		$data['mobile'] = trim($this->input->post('mobile', true));
		$data['is_default'] = intval($this->input->post('is_default', true)) > 0 ? 1 : 0;
		//格式验证
		$this->load->helper('verify');
		if( ! (
			isZipcode($data['zip']) && isZipcode($data['prov'])
			&& isZipcode($data['city']) && isZipcode($data['district'])
			&& isMobile($data['mobile'])
		)){
			$err['err_no'] = 1002;
			$err['err_msg'] = parent::$errorType[1002];
			exit(json_encode($err));
		}
		//执行更新
		if(false != $this->Base_model->update('vip_users_address', $data, array('uid'=>$vipInfo['uid'], 'id'=>$id))){
			//保证默认地址唯一性
			if($data['is_default'] == 1){
				$this->db->query("UPDATE vip_users_address SET is_default = 0 WHERE uid = {$vipInfo['uid']} AND id != {$id}");
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
		$vipInfo = $this->session->userdata('vipinfo');
		//执行删除
		$id = intval($this->input->post('address_id'));
		$result = $this->Base_model->delete('vip_users_address', array('uid'=>$vipInfo['uid'], 'id'=>$id), true);
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