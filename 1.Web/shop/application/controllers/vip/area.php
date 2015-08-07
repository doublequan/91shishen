<?php
/**
 * VIP地区信息
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-27
 */
require_once 'common.php';
class Area extends Common{
	
	//获取省信息
	public function ajax_province(){
		//仅限Ajax访问
		$this->input->is_ajax_request() || show_404();
		$this->load->model('Area_model');
		$data = $this->Area_model->getProvince();
		exit(json_encode($data));
	}
	
	//获取市信息
	public function ajax_city(){
		//仅限Ajax访问
		$this->input->is_ajax_request() || show_404();
		$province_id = $this->input->post('province_id', true);
		$this->load->model('Area_model');
		$data = $this->Area_model->getCity($province_id);
		exit(json_encode($data));
	}
	
	//获取区信息
	public function ajax_area(){
		//仅限Ajax访问
		$this->input->is_ajax_request() || show_404();
		$city_id = $this->input->post('city_id', true);
		$this->load->model('Area_model');
		$data = $this->Area_model->getArea($city_id);
		exit(json_encode($data));
	}
}