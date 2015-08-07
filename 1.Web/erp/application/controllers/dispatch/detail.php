<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/dispatch.php';

class detail extends Dispatch 
{

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$data = array();
		//get single
		$id = $this->input->get('id');
		$data['single'] = $this->mBase->getSingle('dispatchs','id',$id);
		if( !($data['single'] && $data['single']['is_del']==0) ){
			$this->showMsg(1000,'调度单不存在');
			return;
		}
		//get cached areas
		$data['areas'] = $this->getCacheList('areas',array(),'all_areas',86400);
		//get store info
		$data['out_store_info'] = $this->mBase->getSingle('stores', 'id', $data['single']['out_store']);
		$data['in_store_info'] = $this->mBase->getSingle('stores', 'id', $data['single']['in_store']);
		//goods & products
		$dispatchs_details = $this->mBase->getList('dispatchs_detail', array('AND' => array("dispatch_id='{$id}'")));
		$amount_arr = array();
		foreach ($dispatchs_details as $value) {
			if($value['good_id'] > 0){
				$amount_arr['good'][$value['good_id']] = $value['amount'];
			}
			if($value['product_id'] > 0){
				$amount_arr['product'][$value['product_id']] = $value['amount'];
			}
		}
		$data['amount_arr'] = $amount_arr;

		$good_ids = $product_ids = array();
		foreach ($dispatchs_details as $value) {
			if($value['good_id'] > 0){
				$good_ids[] = $value['good_id'];
			}
			if($value['product_id'] > 0){
				$product_ids[] = $value['product_id'];
			}
		}

		$dispatch_goods = array();
		if(count($good_ids) > 0){
			$good_ids = implode(',', $good_ids);
			$good_condition['AND'][] = "id in ({$good_ids})";
			$good_condition['AND'][] = "is_del=0";
			$dispatch_goods = $this->mBase->getList('goods', $good_condition);
		}

		$dispatch_products = array();
		if(count($product_ids) > 0){
			$product_ids = implode(',', $product_ids);
			$product_condition['AND'][] = "id in ({$product_ids})";
			$product_condition['AND'][] = "is_del=0";
			$dispatch_products = $this->mBase->getList('products', $product_condition);
		}

		$data['dispatch_goods'] = $dispatch_goods;
		$data['dispatch_products'] = $dispatch_products;
		$this->_view('dispatch/detail', $data);
	}
	
	public function print_single(){
		$data = array();
		//get single
		$id = $this->input->get('id');
		$data['single'] = $this->mBase->getSingle('dispatchs','id',$id);
		if( !($data['single'] && $data['single']['is_del']==0) ){
			$this->showMsg(1000,'调度单不存在');
			return;
		}
		//get cached areas
		$data['areas'] = $this->getCacheList('areas',array(),'all_areas',86400);
		//get store info
		$data['out_store_info'] = $this->mBase->getSingle('stores', 'id', $data['single']['out_store']);
		$data['in_store_info'] = $this->mBase->getSingle('stores', 'id', $data['single']['in_store']);
		//get dispatch actions
		$condition = array(
			'AND' => array("dispatch_id='".$id."'"),
		);
		$data['actions'] = $this->mBase->getList('dispatchs_action',$condition);
		//goods & products
		$dispatchs_details = $this->mBase->getList('dispatchs_detail', array('AND' => array("dispatch_id='{$id}'")));
		$amount_arr = array();
		foreach ($dispatchs_details as $value) {
			if($value['good_id'] > 0){
				$amount_arr['good'][$value['good_id']] = $value['amount'];
			}
			if($value['product_id'] > 0){
				$amount_arr['product'][$value['product_id']] = $value['amount'];
			}
		}
		$data['amount_arr'] = $amount_arr;
		$good_ids = $product_ids = array();
		foreach ($dispatchs_details as $value) {
			if($value['good_id'] > 0){
				$good_ids[] = $value['good_id'];
			}
			if($value['product_id'] > 0){
				$product_ids[] = $value['product_id'];
			}
		}
		$dispatch_goods = array();
		if(count($good_ids) > 0){
			$good_ids = implode(',', $good_ids);
			$good_condition['AND'][] = "id in ({$good_ids})";
			$good_condition['AND'][] = "is_del=0";
			$dispatch_goods = $this->mBase->getList('goods', $good_condition);
		}
		$dispatch_products = array();
		if(count($product_ids) > 0){
			$product_ids = implode(',', $product_ids);
			$product_condition['AND'][] = "id in ({$product_ids})";
			$product_condition['AND'][] = "is_del=0";
			$dispatch_products = $this->mBase->getList('products', $product_condition);
		}
		$data['dispatch_goods'] = $dispatch_goods;
		$data['dispatch_products'] = $dispatch_products;
		$this->_view('dispatch/print_single', $data);
	}

	public function print_muti(){
		$data = array();
		$dispatch_ids = $this->input->get('dispatch_ids');
		if(empty($dispatch_ids)){
			$this->showMsg(1000,'输入错误');
			return;
		}
		$dispatch_ids = str_replace(',', "','", $dispatch_ids);
		$dispatch_list_tmp = $this->mBase->getList('dispatchs', array('AND' => array("id in ('".$dispatch_ids."')")));
		if(!$dispatch_list_tmp){
			$this->showMsg(1000,'采购单不存在');
			return;
		}
		//get cached areas
		$data['areas'] = $this->getCacheList('areas',array(),'all_areas',86400);
		
		$dispatch_list = array();
		foreach ($dispatch_list_tmp as $single_dispatch) {
			$single_id = $single_dispatch['id'];
			$dispatch_list[$single_id]['single'] = $single_dispatch;
			//get store info
			$dispatch_list[$single_id]['out_store_info'] = $this->mBase->getSingle('stores', 'id', $single_dispatch['out_store']);
			$dispatch_list[$single_id]['in_store_info'] = $this->mBase->getSingle('stores', 'id', $single_dispatch['in_store']);
			//get dispatch actions
			$condition = array(
				'AND' => array("dispatch_id='".$single_id."'"),
			);
			$dispatch_list[$single_id]['actions'] = $this->mBase->getList('dispatchs_action',$condition);
			//goods & products
			$dispatchs_details = $this->mBase->getList('dispatchs_detail', array('AND' => array("dispatch_id='{$single_id}'")));
			$amount_arr = array();
			foreach ($dispatchs_details as $value) {
				if($value['good_id'] > 0){
					$amount_arr['good'][$value['good_id']] = $value['amount'];
				}
				if($value['product_id'] > 0){
					$amount_arr['product'][$value['product_id']] = $value['amount'];
				}
			}
			$dispatch_list[$single_id]['amount_arr'] = $amount_arr;
			$good_ids = $product_ids = array();
			foreach ($dispatchs_details as $value) {
				if($value['good_id'] > 0){
					$good_ids[] = $value['good_id'];
				}
				if($value['product_id'] > 0){
					$product_ids[] = $value['product_id'];
				}
			}
			$dispatch_goods = $good_condition = array();
			if(count($good_ids) > 0){
				$good_ids = implode(',', $good_ids);
				$good_condition['AND'][] = "id in ({$good_ids})";
				$good_condition['AND'][] = "is_del=0";
				$dispatch_goods = $this->mBase->getList('goods', $good_condition);
			}
			$dispatch_products = $product_condition = array();
			if(count($product_ids) > 0){
				$product_ids = implode(',', $product_ids);
				$product_condition['AND'][] = "id in ({$product_ids})";
				$product_condition['AND'][] = "is_del=0";
				$dispatch_products = $this->mBase->getList('products', $product_condition);
			}

			$dispatch_list[$single_id]['dispatch_goods'] = $dispatch_goods;
			$dispatch_list[$single_id]['dispatch_products'] = $dispatch_products;
		}
		$data['dispatch_list'] = $dispatch_list;
		$this->_view('dispatch/print_muti', $data);
	}
}
