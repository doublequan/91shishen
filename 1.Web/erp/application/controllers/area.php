<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Area extends CI_Controller 
{
	private $table_name = 'suppliers';

	public function index()
	{
		
	}

	public function getCityList(){
		$this->load->model('area_model', 'area');
		$province_id = $this->input->get('province_id');
		
		$city_list = $this->area->getCityListByProvince($province_id);
		die(json_encode($city_list));
	}

	public function getAreaList(){
		$this->load->model('area_model', 'area');
		$city_id = $this->input->get('city_id');
		
		$area_list = $this->area->getAreaListByCity($city_id);
		die(json_encode($area_list));
	}
	
}
