<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class Brand extends Base 
{
	private $active_top_tag;
	private $table_name;
	private $page_size;
	private $company_list = array();

	public function __construct(){
		parent::__construct();

		$this->active_top_tag = 'product';
		$this->table_name = 'brands';
		$this->page_size = 20;

		$this->load->helper('form');
	}

	public function index()
	{
		$this->load->model('brand_model', 'brand');

		$brand_name = $this->input->get('brand_name');
		$data['brand_name'] = $brand_name;

		$info_count = $this->brand->countBrands($brand_name);

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
			$info_list = $this->brand->getList($brand_name, $page, $this->page_size);
		}

		$data['info_list'] = $info_list;

		$data['upload_url_prefix'] = $this->config->item('upload_url_prefix');

		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'brand';

		$this->_view('common/header', $tags);
		$this->_view('product/brand_list', $data);
		$this->_view('common/footer');
	}

	public function form()
	{
		$this->load->library('form_validation');
		$this->load->model('brand_model', 'brand');

		$brand_id = $this->input->get('brand_id');
		
		$data = array();
		if(!empty($brand_id)){
			$data = $this->brand->getBrand($brand_id);
			$data['brand_id'] = $brand_id;
		}

		$this->_view('product/brand_form', $data);
	}

	public function add(){
		$this->load->library('form_validation');
		$this->form_validation->set_message('required', '“%s”不能为空！');

		if ($this->form_validation->run('brand') !== FALSE){

			$file_path = '';
			if(isset($_FILES['logo']) && !empty($_FILES['logo']['size'])){
				$relative_folder = '/upload/image/'.date('Y-m', time()).'/';

				$upload_folder = ROOTPATH.$relative_folder;
				if(!is_dir($upload_folder))
					mkdir($upload_folder, 0777, true);

				$file_type = strrchr($_FILES['logo']['name'], '.');
				$file_path = $relative_folder.time().rand(100000, 999999).$file_type;

				move_uploaded_file($_FILES['logo']['tmp_name'], ROOTPATH.$file_path);
			}

			$data_info = $_POST;
			$data_info['logo'] = $file_path;

			$insert_rst = $this->base_model->insert($this->table_name, $data_info);
			if($insert_rst === false){
				show_error("数据库错误，请重试！");
			}
			$brand_id = @$insert_rst['id'];
			header('Location: '.site_url('products/brand/form').'?rst=add_success&brand_id='.$brand_id);
			die();
		}
		$this->_view('product/brand_form', $data);
	}
	
	public function edit()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_message('required', '“%s”不能为空！');

		$brand_id = $this->input->get('brand_id');
		if ($this->form_validation->run('brand') !== FALSE){
			$data_info = $_POST;
			if(empty($data_info['id'])){
				show_error("商品信息错误，请刷新重试！");
			}

			$file_path = '';
			if(isset($_FILES['logo']) && !empty($_FILES['logo']['size'])){
				$relative_folder = '/upload/image/'.date('Y-m', time()).'/';

				$upload_folder = ROOTPATH.$relative_folder;
				if(!is_dir($upload_folder))
					mkdir($upload_folder, 0777, true);

				$file_type = strrchr($_FILES['logo']['name'], '.');
				$file_path = $relative_folder.time().rand(100000, 999999).$file_type;

				move_uploaded_file($_FILES['logo']['tmp_name'], ROOTPATH.$file_path);
			}
			$data_info['logo'] = $file_path;

			$brand_id = $data_info['id'];
			$brand_id_map = array('id' => $brand_id);
			$update_rst = $this->base_model->update($this->table_name, $data_info, $brand_id_map);

			header('Location: '.site_url('products/brand/form').'?rst=edit_success&brand_id='.$brand_id);
			die();
		}
		$this->_view('product/brand_form', $data);
	}

	public function delete()
	{
		$brand_id = $this->input->get('brand_id');
		if(empty($brand_id)){
			die('{"error":1, "msg": "信息错误，请刷新页面重试！"}');
		}

		$this->load->model('brand_model', 'brand');
		$delete_rst = $this->brand->delete($brand_id);

		die('{"error":0, "msg": ""}');
	}


}
