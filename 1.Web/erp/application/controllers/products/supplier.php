<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class Supplier extends Base 
{
	private $table_name;
	private $page_size;
	private $active_top_tag;

	public function __construct(){
		parent::__construct();
		$this->table_name = 'suppliers';
		$this->page_size = 20;
		$this->active_top_tag = 'product';
	}

	public function index()
	{
		$this->load->helper('form');
		$this->load->model('supplier_model', 'supplier');

		$sup_name = $this->input->get('sup_name');
		$data['sup_name'] = $sup_name;

		$info_count = $this->supplier->countSuppliers($sup_name);

		$data['info_count'] = $info_count;
		
		if($info_count == 0)
			$data['total_pages'] = 1;
		elseif($info_count % $this->page_size > 0)
			$data['total_pages'] = intval($info_count / $this->page_size) + 1;
		else
			$data['total_pages'] = intval($info_count / $this->page_size);

		$page = $this->input->get('page');
		if(empty($page))
			$page = 1;

		$data['page'] = $page;

		$info_list = array();
		if($info_count){
			$info_list = $this->supplier->getList($sup_name, $page, $this->page_size);
		}

		$data['results'] = $info_list;

		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'supplier';
		$this->_view('common/header', $tags);
		$this->_view('product/supplier_list', $data);
		$this->_view('common/footer');
	}

	public function form(){
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('supplier_model', 'supplier');
		$this->load->model('area_model', 'area');

		$supplier_id = $this->input->get('supplier_id');
		
		if(!empty($supplier_id)){
			$data = $this->supplier->getSupplier($supplier_id);
			$data['supplier_id'] = $supplier_id;
			if(!empty($data['logistics_province_id']))
				$data['city_list'] = $this->area->getCityListByProvince($data['logistics_province_id']);
			if(!empty($data['logistics_city_id'])){
				$data['area_list'] = $this->area->getAreaListByCity($data['logistics_city_id']);
			}
		}
		
		$province_list = $this->area->getProvinceList();
		$data['province_list'] = $province_list;

		$this->_view('product/supplier_form', $data);
	}

	public function add(){
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_message('required', '“%s”不能为空！');

		$this->load->model('area_model', 'area');
		$province_list = $this->area->getProvinceList();
		$data['province_list'] = $province_list;
		
		if ($this->form_validation->run('supplier') !== FALSE){
			$data_info = $_POST;
			$data_info['create_time'] = time();

			$insert_rst = $this->base_model->insert($this->table_name, $data_info);
			if($insert_rst === false){
				show_error("数据库错误，请重试！");
			}
			$supplier_id = @$insert_rst['id'];
			header('Location: '.site_url('products/supplier/form').'?rst=add_success&supplier_id='.$supplier_id);
			die();
		}
		$this->_view('product/supplier_form', $data);
	}
	
	public function edit()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_message('required', '“%s”不能为空！');

		$this->load->model('area_model', 'area');
		$province_list = $this->area->getProvinceList();
		$data['province_list'] = $province_list;

		$supplier_id = $this->input->get('supplier_id');
		if ($this->form_validation->run('supplier') !== FALSE){
			$data_info = $_POST;

			if(empty($data_info['id'])){
				show_error("商品信息错误，请刷新重试！");
			}

			$supplier_id = $data_info['id'];
			$supplier_id_map = array('id' => $supplier_id);
			$update_rst = $this->base_model->update($this->table_name, $data_info, $supplier_id_map);

			header('Location: '.site_url('products/supplier/form').'?rst=edit_success&supplier_id='.$supplier_id);
			die();
		}
		$this->_view('product/supplier_form', $data);
	}

	public function delete()
	{
		$supplier_id = $this->input->get('supplier_id');

		if(empty($supplier_id)){
			die('{"error":1, "msg": "信息错误，请刷新页面重试！"}');
		}

		$data_info['is_del'] = 1;
		$supplier_id_map = array('id' => $supplier_id);
		$update_rst = $this->base_model->update($this->table_name, $data_info, $supplier_id_map);
		
		die('{"error":0, "msg": ""}');
	}

	public function single_select_dialog()
	{
		$this->load->helper('form');
		$this->load->model('supplier_model', 'supplier');

		$sup_name = $this->input->get('sup_name');
		$data['sup_name'] = $sup_name;

		$info_count = $this->supplier->countSuppliers($sup_name);

		$data['info_count'] = $info_count;
		
		if($info_count == 0)
			$data['total_pages'] = 1;
		elseif($info_count % $this->page_size > 0)
			$data['total_pages'] = intval($info_count / $this->page_size) + 1;
		else
			$data['total_pages'] = intval($info_count / $this->page_size);

		$page = $this->input->get('page');
		if(empty($page))
			$page = 1;

		$data['page'] = $page;

		$info_list = array();
		if($info_count){
			$info_list = $this->supplier->getList($sup_name, $page, $this->page_size);
		}

		$data['results'] = $info_list;

		//display templates
		$this->_view('product/supplier_single_select', $data);
	}

}
