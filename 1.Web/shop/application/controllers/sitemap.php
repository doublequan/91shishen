<?php

class sitemap extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('Base_model','mBase');
	}
	
	public function index(){
		$condition = array(
			'AND' => array('is_del=0'),
    	);
		$data['results'] = $this->mBase->getList('products',$condition,'*','id DESC');
		$this->load->view('sitemap/product', $data);
	}
	
	public function category(){
		$condition = array(
			'AND' => array('is_del=0'),
		);
		$data['results'] = $this->mBase->getList('goods_category',$condition,'*','id DESC');
		$this->load->view('sitemap/category', $data);
	}
}