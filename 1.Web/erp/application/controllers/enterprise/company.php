<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class Company extends Base 
{
	private $active_top_tag;
	private $table_name;
	private $province_list;
	private $city_list;

	public function __construct(){
		parent::__construct();

		$this->active_top_tag = 'enterprise';
		$this->table_name = 'companys';

		$this->load->model('area_model', 'area');
		$province_res = $this->area->getProvinceList();
		foreach ($province_res as $row) {
			$this->province_list[$row['id']] = $row;
		}
		$city_res = $this->area->getCityList();
		foreach ($city_res as $row) {
			$this->city_list[$row['id']] = $row;
		}
	}

	public function index(){
		//init parameters
		$must = array();
		$fields = array('keyword');
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		//get companys
		$condition = array(
			'AND' => array('is_del=0'),
		);
		$k = $params['keyword'];
		if( $k ){
			$condition['OR'][0] = array(
				"name LIKE '%".$k."%'",
				"address LIKE '%".$k."%'",
			);
		}
		$data['results'] = $this->mBase->getList('companys',$condition,'*','id DESC');
		if( $k && $data['results'] ){
			foreach( $data['results'] as &$row ){
				$row['name'] = str_replace($k, '<font color="red">'.$k.'</font>', $row['name']);
				$row['address'] = str_replace($k, '<font color="red">'.$k.'</font>', $row['address']);;
			}
			unset($row);
		}

		$data['province_list'] = $this->province_list;
		$data['city_list'] = $this->city_list;

		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'company';

		$this->_view('common/header', $tags);
		$this->_view('enterprise/company_list', $data);
		$this->_view('common/footer');
	}

	public function form(){
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('area_model', 'area');
		$company_id = $this->input->get('company_id');

		if(!empty($company_id)){
			$data = $this->mBase->getSingle('companys', 'id', $company_id);
			$data['company_id'] = $company_id;

			$data['city_list'] = $this->area->getCityListByProvince($data['province_id']);

			$manager_info = $this->mBase->getSingle('employees', 'id', $data['manager']);
			$data['manager_info'] = $manager_info;
		}
		
		$province_list = $this->area->getProvinceList();
		$data['province_list'] = $province_list;
		
		$this->_view('enterprise/company_form', $data);
	}

	public function add(){
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_message('required', '“%s”不能为空！');

		$this->load->model('area_model', 'area');
		$province_list = $this->area->getProvinceList();
		$data['province_list'] = $province_list;
		
		if ($this->form_validation->run('company') !== FALSE){
			$data_info = $_POST;
			$data_info['create_time'] = time();

			if(!empty($data_info['employee_name']))
				unset($data_info['employee_name']);

			$insert_rst = $this->base_model->insert($this->table_name, $data_info);
			if($insert_rst === false){
				show_error("数据库错误，请重试！");
			}
			$company_id = @$insert_rst['id'];
			header('Location: '.site_url('enterprise/company/form').'?rst=add_success&company_id='.$company_id);
			die();
		}
		$this->_view('enterprise/company_form', $data);
	}
	
	public function edit()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_message('required', '“%s”不能为空！');

		$company_id = $this->input->get('company_id');

		if ($this->form_validation->run('company') !== FALSE){
			$data_info = $_POST;

			if(empty($data_info['id'])){
				show_error("商品信息错误，请刷新重试！");
			}

			if(!empty($data_info['employee_name']))
				unset($data_info['employee_name']);

			$company_id = $data_info['id'];
			$company_id_map = array('id' => $company_id);
			$update_rst = $this->base_model->update($this->table_name, $data_info, $company_id_map);

			header('Location: '.site_url('enterprise/company/form').'?rst=edit_success&company_id='.$company_id);
			die();
		}
		$this->_view('enterprise/company_form');
	}

	public function delete()
	{
		$company_id = $this->input->get('company_id');

		if(empty($company_id)){
			die('{"error":1, "msg": "信息错误，请刷新页面重试！"}');
		}

		$data_info['is_del'] = 1;
		$company_id_map = array('id' => $company_id);
		$update_rst = $this->base_model->update($this->table_name, $data_info, $company_id_map);
		
		die('{"error":0, "msg": ""}');
	}

}
