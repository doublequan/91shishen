<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class purchase extends Base 
{
	private $active_top_tag = 'product';
	
	private $typeMap = array(
		1	=> '自主采购',
		2	=> '供应商采购',
	);
	
	private $checkoutMap = array(
		0	=> '其他',
		1	=> '转账',
		2	=> '现金',
		3	=> '支票',
	);

	private $statusMap = array(
		0	=> '新建采购单',
		1	=> '审核中采购单',
		2	=> '审核通过采购单',
		3	=> '已开始采购单',
		4	=> '已入库采购单',
		5	=> '结算完成采购单',
	);
	
	public $actionMap = array(
		'create'	=> 0,
		'submit'	=> 1,
		'approve'	=> 2,
		'purchase'	=> 3,
		'receive'	=> 4,
		'check'		=> 5,
		'transfer'	=> -1,
	);
	
	public function __construct(){
		parent::__construct();
		$data = array(
			'good_method_types' => getConfig('good_method_types'),
		);
		$this->load->vars($data);
	}
	
	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('type','status','page','size','keyword');
		$this->checkParams('get',$must,$fields);
		$this->params['type'] = $this->params['type'] ? intval($this->params['type']) : -1;
		if( !array_key_exists($this->params['type'],$this->typeMap) ){
			$this->params['type'] = current(array_keys($this->typeMap));
		}
		$this->params['status'] = $this->params['status'] ? intval($this->params['status']) : -1;
		if( !array_key_exists($this->params['status'], $this->statusMap) ){
			$this->params['status'] = current(array_keys($this->statusMap));
		}
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		//get purchases
		$condition = array(
			'AND' => array('is_del=0'),
		);
		if( $params['keyword'] ){
			$condition['AND'][] = "id LIKE '%{$params['keyword']}%'";
		}
		if( $this->params['type']!=-1 ){
			$condition['AND'][] = 'type='.$params['type'];
		}
		if( $this->params['status']!=-1 ){
			$condition['AND'][] = 'status='.$params['status'];
		}
		$res = $this->mBase->getList('purchases',$condition,'*','id DESC',$page,$size);
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//employees
		$condition = array(
			'AND' => array('is_del=0','id<>'.parent::$user['id']),
		);
		$data['employees'] = $this->mBase->getList('employees',$condition);
		//stores
		$store_list = array();
		if(is_array($data['results']) && count($data['results']) > 0){
			$store_ids = array();
			foreach ($data['results'] as $value) {
				$store_ids[] = $value['store_id'];
			}
			$store_ids = implode(',', $store_ids);

			$res = $this->mBase->getList('stores',array('AND' => array("id in ({$store_ids})")));

			foreach ($res as $value) {
				$store_list[$value['id']] = $value;
			}
		}
		$data['store_list'] = $store_list;
		//common data
		$data['typeMap'] = $this->typeMap;
		$data['statusMap'] = $this->statusMap;
		$data['checkoutMap'] = $this->checkoutMap;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'purchase';
		$this->_view('common/header', $tags);
		$this->_view('product/purchase_list', $data);
		$this->_view('common/footer');
	}
	
	public function add() {
		$data = array();
		//get all undisabled provinces and cities
		$data['provs'] = $data['citys'] = array();
		$condition = array(
			'AND' => array('disable=0'),
		);
		$res = $this->mBase->getList('areas',$condition,'*','sort ASC');
		if( $res ){
			foreach ( $res as $row ){
				if( $row['deep']==1 ){
					$data['provs'][] = $row;
				} elseif ( $row['deep']==2 ){
					$data['citys'][$row['father_id']][] = $row;
				}
			}
		}
		//get all stores
		$data['stores'] = array();
		$condition = array(
			'AND' => array('is_del=0'),
		);
		$res = $this->mBase->getList('stores',$condition);
		if( $res ){
			foreach ( $res as $row ){
				$data['stores'][$row['city']][] = $row;
			}
		}
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'purchase';
		$this->_view('common/header', $tags);
		$this->_view('product/purchase_add', $data);
		$this->_view('common/footer');
	}
	
	public function edit() {
		$data = array();
		//get single
		$id = $this->input->get('id');
		$data['single'] = $this->mBase->getSingle('purchases','id',$id);
		if( $data['single']['status'] ){
			
		}
		//store 
		$data['store'] = $this->mBase->getSingle('stores','id',$data['single']['store_id']);
		//get all undisabled provinces and cities
		$data['provs'] = $data['citys'] = array();
		$condition = array(
			'AND' => array('disable=0'),
		);
		$res = $this->mBase->getList('areas',$condition,'*','sort ASC');
		if( $res ){
			foreach ( $res as $row ){
				if( $row['deep']==1 ){
					$data['provs'][] = $row;
				} elseif ( $row['deep']==2 ){
					$data['citys'][$row['father_id']][] = $row;
				}
			}
		}
		//get all stores
		$data['stores'] = array();
		$condition = array(
			'AND' => array('is_del=0'),
		);
		$res = $this->mBase->getList('stores',$condition);
		if( $res ){
			foreach ( $res as $row ){
				$data['stores'][$row['city']][] = $row;
			}
		}
		//supplier
		$data['supplier_info'] = array();
		if(!empty($data['single']['supplier_id'])){
			$data['supplier_info'] = $this->mBase->getSingle('suppliers', 'id', $data['single']['supplier_id']);
		}
		//goods & products
		$purchases_details = $this->mBase->getList('purchases_detail', array('AND' => array("purchase_id='{$id}'")));
		$amount_arr = array();
		foreach ($purchases_details as $value) {
			if($value['good_id'] > 0){
				$amount_arr['good'][$value['good_id']] = $value['amount_plan'];
			}
			if($value['product_id'] > 0){
				$amount_arr['product'][$value['product_id']] = $value['amount_plan'];
			}
		}
		$data['amount_arr'] = $amount_arr;
		$good_ids = $product_ids = array();
		foreach ($purchases_details as $value) {
			if($value['good_id'] > 0){
				$good_ids[] = $value['good_id'];
			}
			if($value['product_id'] > 0){
				$product_ids[] = $value['product_id'];
			}
		}

		$purchase_goods = array();
		if(count($good_ids) > 0){
			$good_ids = implode(',', $good_ids);
			$good_condition['AND'][] = "id in ({$good_ids})";
			$good_condition['AND'][] = "is_del=0";
			$purchase_goods = $this->mBase->getList('goods', $good_condition);
		}

		$purchase_products = array();
		if(count($product_ids) > 0){
			$product_ids = implode(',', $product_ids);
			$product_condition['AND'][] = "id in ({$product_ids})";
			$product_condition['AND'][] = "is_del=0";
			$purchase_products = $this->mBase->getList('products', $product_condition);
		}

		$data['purchase_goods'] = $purchase_goods;
		$data['purchase_products'] = $purchase_products;

		//site names
		$site_res = $this->mBase->getList('sites',array(),'id, name');
		foreach ($site_res as $row) {
			$data['site_list'][$row['id']] = $row;
		}
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'purchase';
		$this->_view('common/header', $tags);
		$this->_view('product/purchase_edit', $data);
		$this->_view('common/footer');
	}
	
	public function detail() {
		$data = array();
		//get single
		$id = $this->input->get('id');
		$data['single'] = $this->mBase->getSingle('purchases','id',$id);
		if( $data['single']['status'] ){
			
		}

		//store 
		$store_info = $this->mBase->getSingle('stores', 'id', $data['single']['store_id']);
		$data['store_info'] = $store_info;


		$prov_info = $this->mBase->getSingle('areas', 'id', $store_info['prov']);
		$data['store_prov'] = $prov_info['name'];
		$city_info = $this->mBase->getSingle('areas', 'id', $store_info['city']);
		$data['store_city'] = $city_info['name'];

		//supplier
		$data['supplier_info'] = array();
		if(!empty($data['single']['supplier_id'])){
			$data['supplier_info'] = $this->mBase->getSingle('suppliers', 'id', $data['single']['supplier_id']);
		}

		//goods & products
		$purchases_details = $this->mBase->getList('purchases_detail', array('AND' => array("purchase_id='{$id}'")));
		$amount_arr = array();
		foreach ($purchases_details as $value) {
			if($value['good_id'] > 0){
				$amount_arr['good'][$value['good_id']] = $value['amount_plan'];
			}
			if($value['product_id'] > 0){
				$amount_arr['product'][$value['product_id']] = $value['amount_plan'];
			}
		}
		$data['amount_arr'] = $amount_arr;

		$good_ids = $product_ids = array();
		foreach ($purchases_details as $value) {
			if($value['good_id'] > 0){
				$good_ids[] = $value['good_id'];
			}
			if($value['product_id'] > 0){
				$product_ids[] = $value['product_id'];
			}
		}

		$purchase_goods = array();
		if(count($good_ids) > 0){
			$good_ids = implode(',', $good_ids);
			$good_condition['AND'][] = "id in ({$good_ids})";
			$good_condition['AND'][] = "is_del=0";
			$purchase_goods = $this->mBase->getList('goods', $good_condition);
		}

		$purchase_products = array();
		if(count($product_ids) > 0){
			$product_ids = implode(',', $product_ids);
			$product_condition['AND'][] = "id in ({$product_ids})";
			$product_condition['AND'][] = "is_del=0";
			$purchase_products = $this->mBase->getList('products', $product_condition);
		}

		$data['purchase_goods'] = $purchase_goods;
		$data['purchase_products'] = $purchase_products;

		//site names
		$site_res = $this->mBase->getList('sites',array(),'id, name');
		foreach ($site_res as $row) {
			$data['site_list'][$row['id']] = $row;
		}
		$this->_view('product/purchase_detail', $data);
	}
	
	public function receive() {
		$data = array();
		//get single
		$id = $this->input->get('id');
		$data['single'] = $this->mBase->getSingle('purchases','id',$id);
		//store info
		$store_info = $this->mBase->getSingle('stores', 'id', $data['single']['store_id']);
		$data['store_info'] = $store_info;
		//store area info
		$prov_info = $this->mBase->getSingle('areas', 'id', $store_info['prov']);
		$data['store_prov'] = $prov_info['name'];
		$city_info = $this->mBase->getSingle('areas', 'id', $store_info['city']);
		$data['store_city'] = $city_info['name'];
		//purchase detail
		$data['details'] = array();
		$res = $this->mBase->getList('purchases_detail', array('AND'=>array("purchase_id='{$id}'")));
		if( $res ){
			$products = array();
			foreach( $res as $row ){
				if( $row['type']==2 && $row['product_id'] ){
					//get product spec
					$p = array();
					if( !isset($products[$row['product_id']]) ){
						$p = $this->mBase->getSingle('products','id',$row['product_id']);
						if( $p ){
							$products[] = $p;
						}
					} else {
						$p = $products[$row['product_id']];
					}
					$row['spec'] = $p ? $p['spec'] : '';
				}
				$data['details'][$row['type']][] = $row;
			}
		}
		$this->_view('product/purchase_receive', $data);
	}

	public function doAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('type','store_id','checkout_type','price_borrow','price_fee');
			$fields = array('supplier_id','is_invoice','invoice_title','invoice_content','express_id','express_no','goods_id','goods_amount','products_id','products_amount');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			//check parameters
			$t = $this->mBase->getSingle('stores','id',$params['store_id']);
			if( !$t ){
				$ret = array('err_no'=>1000,'err_msg'=>'门店不存在');
				break;
			}
			//insert data
			$purchase_id = createBusinessID('PUR');
			$data = array(
				'id'				=> $purchase_id,
				'type'				=> $params['type'],
				'store_id'			=> intval($params['store_id']),
				'checkout_type'		=> intval($params['checkout_type']),
				'price_borrow'		=> $params['price_borrow'],
				'price_fee'			=> $params['price_fee'],
				'supplier_id'		=> intval($params['supplier_id']),
				'is_invoice'		=> intval($params['is_invoice']),
				'invoice_title'		=> $params['invoice_title'],
				'invoice_content'	=> $params['invoice_content'],
				'express_id'		=> intval($params['express_id']),
				'express_no'		=> $params['express_no'],
				'create_eid'		=> self::$user['id'],
				'create_name'		=> self::$user['username'],
				'create_time'		=> time(),
			);
			$this->mBase->insert('purchases',$data);
			//insert purchase details
			if( $params['goods_id'] && $params['goods_amount'] && count($params['goods_id'])==count($params['goods_amount']) ){
				$data = array();
				foreach( $params['goods_id'] as $k=>$good_id ){
					if( isset($params['goods_amount'][$k]) ){
						$good_id = intval($good_id);
						$t = $this->mBase->getSingle('goods','id',$good_id);
						if( $t ){
							$data[] = array(
								'purchase_id'	=> $purchase_id,
								'type'			=> 1,
								'good_id'		=> $good_id,
								'name'			=> $t['name'],
								'supplier_id'	=> intval($params['supplier_id']),
								'unit'			=> $t['unit'],
								'amount_plan'	=> $params['goods_amount'][$k],
								'price_plan'	=> $t['price'],
							);
						}
					}
				}
				if( $data ){
					$this->mBase->insertMulti('purchases_detail',$data);
				}
			}
			if( $params['products_id'] && $params['products_amount'] && count($params['products_id'])==count($params['products_amount']) ){
				$data = array();
				foreach( $params['products_id'] as $k=>$products_id ){
					if( isset($params['products_amount'][$k]) ){
						$products_id = intval($products_id);
						$t = $this->mBase->getSingle('products','id',$products_id);
						if( $t ){
							$data[] = array(
								'purchase_id'	=> $purchase_id,
								'type'			=> 2,
								'product_id'	=> $products_id,
								'name'			=> $t['title'],
								'supplier_id'	=> intval($params['supplier_id']),
								'unit'			=> $t['unit'],
								'amount_plan'	=> floatval($params['products_amount'][$k]),
								'price_plan'	=> $t['price'],
							);
						}
					}
				}
				if( $data ){
					$this->mBase->insertMulti('purchases_detail',$data);
				}
			}
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function doEdit() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('id','type','store_id','checkout_type','price_borrow','price_fee');
			$fields = array('supplier_id','is_invoice','invoice_title','invoice_content','express_id','express_no','goods_id','goods_amount','products_id','products_amount');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			//check parameters
			$id = $params['id'];
			$purchase = $this->mBase->getSingle('purchases','id',$id);
			if( !$purchase ){
				$ret = array('err_no'=>1000,'err_msg'=>'采购单不存在');
				break;
			}
			$t = $this->mBase->getSingle('stores','id',$params['store_id']);
			if( !$t ){
				$ret = array('err_no'=>1000,'err_msg'=>'门店不存在');
				break;
			}
			//insert data
			$data = array(
				'type'				=> $params['type'],
				'store_id'			=> intval($params['store_id']),
				'checkout_type'		=> intval($params['checkout_type']),
				'price_borrow'		=> $params['price_borrow'],
				'price_fee'			=> $params['price_fee'],
				'supplier_id'		=> intval($params['supplier_id']),
				'is_invoice'		=> intval($params['is_invoice']),
				'invoice_title'		=> $params['invoice_title'],
				'invoice_content'	=> $params['invoice_content'],
				'express_id'		=> intval($params['express_id']),
				'express_no'		=> $params['express_no'],
			);
			$this->mBase->update('purchases',$data,array('id'=>$id));
			//remove and re_insert purchase details
			$this->mBase->delete('purchases_detail',array('purchase_id'=>$id),true);
			if( $params['goods_id'] && $params['goods_amount'] && count($params['goods_id'])==count($params['goods_amount']) ){
				$data = array();
				foreach( $params['goods_id'] as $k=>$good_id ){
					if( isset($params['goods_amount'][$k]) ){
						$good_id = intval($good_id);
						$t = $this->mBase->getSingle('goods','id',$good_id);
						if( $t ){
							$data[] = array(
								'purchase_id'	=> $params['id'],
								'type'			=> 1,
								'good_id'		=> $good_id,
								'name'			=> $t['name'],
								'supplier_id'	=> intval($params['supplier_id']),
								'unit'			=> $t['unit'],
								'amount_plan'	=> $params['goods_amount'][$k],
								'price_plan'	=> $t['price'],
							);
						}
					}
				}
				if( $data ){
					$this->mBase->insertMulti('purchases_detail',$data);
				}
			}
			if( $params['products_id'] && $params['products_amount'] && count($params['products_id'])==count($params['products_amount']) ){
				$data = array();
				foreach( $params['products_id'] as $k=>$products_id ){
					if( isset($params['products_amount'][$k]) ){
						$products_id = intval($products_id);
						$t = $this->mBase->getSingle('products','id',$products_id);
						if( $t ){
							$data[] = array(
								'purchase_id'	=> $params['id'],
								'type'			=> 2,
								'product_id'	=> $products_id,
								'name'			=> $t['title'],
								'supplier_id'	=> intval($params['supplier_id']),
								'unit'			=> $t['unit'],
								'amount_plan'	=> $params['products_amount'][$k],
								'price_plan'	=> $t['price'],
							);
						}
					}
				}
				if( $data ){
					$this->mBase->insertMulti('purchases_detail',$data);
				}
			}
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function delete(){
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('id');
			$fields = array();
			$this->checkParams('get',$must,$fields);
			$params = $this->params;
			//update userinfo
			$data = array(
				'is_del' => 1,
			);
			$this->mBase->update('purchases',$data,array('id'=>$params['id']));
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function doConfirmReceive() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('purchase_id','detail_id','price_real','amount_real');
			$fields = array();
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			//check parameter
			$single = $this->mBase->getSingle('purchases','id',$params['purchase_id']);
			if( !$single ){
				$ret = array('err_no'=>1000,'err_msg'=>'采购单不存在');
				break;
			}
			if( !($params['detail_id'] && $params['price_real'] && $params['amount_real']) ){
				$ret = array('err_no'=>1000,'err_msg'=>'参数不合法');
				break;
			}
			$x = count($params['detail_id'])==count($params['price_real']) ? true : fasle;
			$y = count($params['detail_id'])==count($params['amount_real']) ? true : fasle;
			if( !($x && $y) ){
				$ret = array('err_no'=>1000,'err_msg'=>'参数不合法');
				break;
			}
			foreach( $params['detail_id'] as $k=>$v ){
				$id = intval($v);
				$price_real = isset($params['price_real'][$k]) ? floatval($params['price_real'][$k]) : 0;
				$amount_real = isset($params['amount_real'][$k]) ? floatval($params['amount_real'][$k]) : 0;
				$data = array(
					'price_real'	=> $price_real,
					'amount_real'	=> $amount_real,
				);
				$this->mBase->update('purchases_detail',$data,array('id'=>$id));
				//Add Good Stock and Log
				$store_id = $single['store_id'];
				$detail = $this->mBase->getSingle('purchases_detail','id',$id);
				if( $detail ){
					$this->load->model('Product_model', 'mProduct');
					if( $detail['good_id'] ){
						$good_id = $detail['good_id'];
						$good = $this->mBase->getSingle('goods','id',$good_id);
						if( $good && $amount_real ){
							//add good stock and add log
							$current = $this->mProduct->updateStock('good',$store_id,$good,$amount_real);
							$data = array(
								'store_id'		=> $store_id,
								'good_id'		=> $good_id,
								'type'			=> 1,
								'change'		=> $amount_real,
								'current'		=> $current,
								'create_eid'	=> self::$user['id'],
								'create_name'	=> self::$user['username'],
								'create_time'	=> time(),
							);
							$this->mBase->insert('goods_stock_log', $data);
						}
					}
					if( $detail['product_id'] ){
						$product_id = $detail['product_id'];
						$product = $this->mBase->getSingle('products','id',$product_id);
						if( $product && $amount_real ){
							//add good stock and add log
							$current = $this->mProduct->updateStock('product',$store_id,$product,$amount_real);
							$data = array(
								'store_id'		=> $store_id,
								'product_id'	=> $product_id,
								'type'			=> 1,
								'change'		=> $amount_real,
								'current'		=> $current,
								'create_eid'	=> self::$user['id'],
								'create_name'	=> self::$user['username'],
								'create_time'	=> time(),
							);
							$this->mBase->insert('products_stock_log', $data);
						}
					}
				}
			}
			$data = array('status'=>4);
			$this->mBase->update('purchases',$data,array('id'=>$params['purchase_id']));
			$this->addAction($id,'reveive');
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function doAction() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('id','action');
			$fields = array('transfer_eid');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			if( !array_key_exists($params['action'], $this->actionMap) ){
				$ret = array('err_no'=>1000,'err_msg'=>'非法操作');
				break;
			}
			$ids = array();
			$arr = explode(',',trim($params['id']));
			if( $arr ){
				foreach ( $arr as $v ){
					$ids[] = $v;
				} 
			}
			$ids = array_unique($ids);
			if( !$ids ){
				$ret = array('err_no'=>1000,'err_msg'=>'请至少选择一个采购单');
				break;
			}
			$transfer_eid = 0;
			$transfer_name = '';
			if( $params['action']=='transfer' ){
				$transfer_eid = intval($params['transfer_eid']);
				$emp = $this->mBase->getSingle('employees','id',$transfer_eid);
				if( !$emp ){
					$ret = array('err_no'=>1000,'err_msg'=>'员工不存在');
					break;
				}
				$transfer_name = $emp['username'];
			}
			$status = $this->actionMap[$params['action']];
			foreach( $ids as $id ){
				$data = array(
					'status'	=> $status,
				);
				$this->mBase->update('purchases',$data,array('id'=>$id));
				$this->addAction($id,$params['action'],$transfer_eid,$transfer_name);
				//添加采购单待处理任务
				$this->load->model('Task_model', 'mTask');
				$this->mTask->addNewTask(5,$id,parent::$user);
			}
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	/**
	 * @desc Add Purchase Action 
	 * @param 0:create, 1:confirm, 2:purchase, 3:receive, 4:check, -1:transfer
	 */
	public function addAction( $id, $action='', $transfer_eid=0, $transfer_name='' ) {
		if( array_key_exists($action, $this->actionMap) ){
			$action = $this->actionMap[$action];
			$des = '';
			$username = parent::$user['username'];
			switch ( $action ){
				case 0:
					$des = '采购单'.$id.'被创建；当前处理人：'.$username;
					break;
				case 1:
					$des = '采购单'.$id.'被确认；当前处理人：'.$username;
					break;
				case 2:
					$des = '采购单'.$id.'开始采购；当前处理人：'.$username;
					break;
				case 3:
					$des = '采购单'.$id.'采购完成；当前处理人：'.$username;
					break;
				case 4:
					$des = '采购单'.$id.'结算完成；当前处理人：：'.$username;
					break;
				case -1:
					$des = '采购单'.$id.'被转交给'.$transfer_name.'；当前处理人：'.$username;
					break;
			}
			$data = array(
				'purchase_id'	=> $id,
				'action'		=> $action,
				'des'			=> $des,
				'create_eid'	=> parent::$user['id'],
				'create_name'	=> parent::$user['username'],
				'create_time'	=> time(),
			);
			$this->mBase->insert('purchases_action',$data);
		}
	}
	
	public function purchase_print() {
		$data = array();
		//get single
		$id = $this->input->get('id');
		$data['single'] = $this->mBase->getSingle('purchases','id',$id);
		if( $data['single']['status'] ){
			
		}

		//store 
		$store_info = $this->mBase->getSingle('stores', 'id', $data['single']['store_id']);
		$data['store_info'] = $store_info;


		$prov_info = $this->mBase->getSingle('areas', 'id', $store_info['prov']);
		$data['store_prov'] = $prov_info['name'];
		$city_info = $this->mBase->getSingle('areas', 'id', $store_info['city']);
		$data['store_city'] = $city_info['name'];

		//supplier
		$data['supplier_info'] = array();
		if(!empty($data['single']['supplier_id'])){
			$data['supplier_info'] = $this->mBase->getSingle('suppliers', 'id', $data['single']['supplier_id']);
		}

		//goods & products
		$purchases_details = $this->mBase->getList('purchases_detail', array('AND' => array("purchase_id='{$id}'")));
		$amount_arr = array();
		foreach ($purchases_details as $value) {
			if($value['good_id'] > 0){
				$amount_arr['good'][$value['good_id']] = $value['amount_plan'];
			}
			if($value['product_id'] > 0){
				$amount_arr['product'][$value['product_id']] = $value['amount_plan'];
			}
		}
		$data['amount_arr'] = $amount_arr;

		$good_ids = $product_ids = array();
		foreach ($purchases_details as $value) {
			if($value['good_id'] > 0){
				$good_ids[] = $value['good_id'];
			}
			if($value['product_id'] > 0){
				$product_ids[] = $value['product_id'];
			}
		}

		$purchase_goods = array();
		if(count($good_ids) > 0){
			$good_ids = implode(',', $good_ids);
			$good_condition['AND'][] = "id in ({$good_ids})";
			$good_condition['AND'][] = "is_del=0";
			$purchase_goods = $this->mBase->getList('goods', $good_condition);
		}

		$purchase_products = array();
		if(count($product_ids) > 0){
			$product_ids = implode(',', $product_ids);
			$product_condition['AND'][] = "id in ({$product_ids})";
			$product_condition['AND'][] = "is_del=0";
			$purchase_products = $this->mBase->getList('products', $product_condition);
		}

		$data['purchase_goods'] = $purchase_goods;
		$data['purchase_products'] = $purchase_products;
                //print create time 
                $data['single']['create_time'] = date('Y-m-d',time());
		//site names
		$site_res = $this->mBase->getList('sites',array(),'id, name');
		foreach ($site_res as $row) {
			$data['site_list'][$row['id']] = $row;
		}
                
                
		$this->_view('product/purchase_print', $data);
	}
	
	public function purchase_print_muti() {
		$data = array();
		//get single
		$purchase_ids = $this->input->get('purchase_ids');

		if(empty($purchase_ids)){
			$this->showMsg(1000,'输入错误');
			return;
		}

		$purchase_ids = str_replace(',', "','", $purchase_ids);

		$purchase_list_tmp = $this->mBase->getList('purchases', array('AND' => array("id in ('".$purchase_ids."')")));
		if(!$purchase_list_tmp){
			$this->showMsg(1000,'采购单不存在');
			return;
		}

		$this->load->model('area_model', 'area');
		$prov_list = $city_list = $store_list = array();
		$prov_list_tmp = $this->area->getProvinceList();
		foreach ($prov_list_tmp as $value) {
			$prov_list[$value['id']] = $value;
		}
		$city_list_tmp = $this->area->getCityList();
		foreach ($city_list_tmp as $value) {
			$city_list[$value['id']] = $value;
		}

		$store_list_tmp = $this->mBase->getList('stores', array('AND' => array('is_del=0')));
		foreach ($store_list_tmp as $value) {
			$store_list[$value['id']] = $value;
		}

		$purchase_list = $supplier_list_tmp = array();
		foreach ($purchase_list_tmp as $single_purchase) {
			$single_purchase_id = $single_purchase['id'];
			$purchase_list[$single_purchase_id]['single'] = $single_purchase;
			//store 
			$purchase_list[$single_purchase_id]['store_info'] = $store_list[$single_purchase['store_id']];
			$purchase_list[$single_purchase_id]['store_prov'] = $prov_list[$store_list[$single_purchase['store_id']]['prov']]['name'];
			$purchase_list[$single_purchase_id]['store_city'] = $city_list[$store_list[$single_purchase['store_id']]['city']]['name'];

			//supplier
			$supplier_id = $single_purchase['supplier_id'];
			if(isset($supplier_list_tmp[$supplier_id])){
				$purchase_list[$single_purchase_id]['supplier_info'] = $supplier_list_tmp[$supplier_id];
			}
			else{
				$supplier_info = $this->mBase->getSingle('suppliers', 'id', $supplier_id);
				$supplier_list_tmp[$supplier_id] = $purchase_list[$single_purchase_id]['supplier_info'] = $supplier_info;
			}

			$purchases_details = $this->mBase->getList('purchases_detail', array('AND' => array("purchase_id='{$single_purchase_id}'")));
			$amount_arr = array();
			foreach ($purchases_details as $value) {
				if($value['good_id'] > 0){
					$amount_arr['good'][$value['good_id']] = $value['amount_plan'];
				}
				if($value['product_id'] > 0){
					$amount_arr['product'][$value['product_id']] = $value['amount_plan'];
				}
			}
			$purchase_list[$single_purchase_id]['amount_arr'] = $amount_arr;

			$good_ids = $product_ids = array();
			foreach ($purchases_details as $value) {
				if($value['good_id'] > 0){
					$good_ids[] = $value['good_id'];
				}
				if($value['product_id'] > 0){
					$product_ids[] = $value['product_id'];
				}
			}

			$purchase_goods = $good_condition = array();
			if(count($good_ids) > 0){
				$good_ids = implode(',', $good_ids);
				$good_condition['AND'][] = "id in ({$good_ids})";
				$good_condition['AND'][] = "is_del=0";
				$purchase_goods = $this->mBase->getList('goods', $good_condition);
			}

			$purchase_products = $product_condition = array();
			if(count($product_ids) > 0){
				$product_ids = implode(',', $product_ids);
				$product_condition['AND'][] = "id in ({$product_ids})";
				$product_condition['AND'][] = "is_del=0";
				$purchase_products = $this->mBase->getList('products', $product_condition);
			}

			$purchase_list[$single_purchase_id]['purchase_goods'] = $purchase_goods;
			$purchase_list[$single_purchase_id]['purchase_products'] = $purchase_products;
		}

		$data['purchase_list'] = $purchase_list;
		//site names
		$site_res = $this->mBase->getList('sites',array(),'id, name');
		foreach ($site_res as $row) {
			$data['site_list'][$row['id']] = $row;
		}

		$this->_view('product/purchase_print_muti', $data);
	}

	public function create_from_user_order(){
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('order_id');
			$this->checkParams('post',$must);
			$params = $this->params;
			$order_ids = array();
			foreach( $params['order_id'] as $v ){
				$v = trim($v);
				if( $v ){
					$order_ids[] = $v;
				}
			}
			$order_ids = array_unique($order_ids);
			if( !$order_ids ){
				$ret = array('err_no'=>1001,'err_msg'=>'请至少选择一个订单');
				break;
			}
			$goods = array();
			$details = array();
			$stocks = array();
			foreach ( $order_ids as $order_id ){
				//get user order details
				$condition = array(
					'AND' => array("order_id='".$order_id."'"),
				);
				$res = $this->mBase->getList('orders_detail',$condition);
				if( $res ){
					foreach( $res as $row ){
						$product = $this->mBase->getSingle('products','id',$row['product_id']);
						if( !$product ){
							continue;
						}
						//获取商品当前库存
						if( !isset($stocks['product'][$row['product_id']]) ){
							$condition = array(
								'AND' => array('product_id='.$row['product_id']),
							);
							$stock = 0;
							$arr = $this->mBase->getList('products_stock',$condition);
							if( $arr ){
								foreach( $arr as $t ){
									$stock += $t['amount'];
								}
							}
							$stocks['product'][$row['product_id']] = $stock;
						}
						//get order product
						if( isset($details[$row['product_id']]) ){
							$details[$row['product_id']]['amount'] += $row['amount'];
						} else {
							$details[$row['product_id']] = array(
								'id'		=> $row['product_id'],
								'sku'		=> $product['sku'],
								'name'		=> $product['title'],
								'price'		=> $product['price'],
								'spec'		=> $product['spec'],
								'amount'	=> $row['amount'],
								'loss_set'	=> $product['loss_set'],
								'loss_stat'	=> $product['loss_stat'],
							);
						}
						//get goods
						$loss_rate = $product['loss_stat'] ? (100-$product['loss_stat']) : (100-$product['loss_set']);
						$loss_rate = round($loss_rate/100,2);
						if( $product['good_id'] && $product['good_num'] ){
							//获取原料库存
							if( !isset($stocks['good'][$product['good_id']]) ){
								$condition = array(
									'AND' => array('good_id='.$product['good_id']),
								);
								$stock = 0;
								$arr = $this->mBase->getList('goods_stock',$condition);
								if( $arr ){
									foreach( $arr as $t ){
										$stock += $t['amount'];
									}
								}
								$stocks['good'][$product['good_id']] = $stock;
							}
							if( isset($goods[$product['good_id']]) ){
								$goods[$product['good_id']]['amount'] += round(($row['amount']*$product['good_num'])/$loss_rate);
							} else {
								$good = $this->mBase->getSingle('goods','id',$product['good_id']);
								if( $good ){
									$goods[$product['good_id']] = array(
										'id'		=> $good['id'],
										'name'		=> $good['name'],
										'unit'		=> $good['unit'],
										'brand_id'	=> $good['brand_id'],
										'price'		=> $good['price'],
										'amount'	=> round(($row['amount']*$product['good_num'])/$loss_rate),
									);
								}
							}
						}
					}
				}
			}
			if( !$goods ){
				$ret = array('err_no'=>1002,'err_msg'=>'商品未关联原料');
				break;
			}
			
			$data['purchase_goods'] = $goods;
			$data['purchase_products'] = $details;
			$data['stocks'] = $stocks;
			
			//get all undisabled provinces and cities
			$data['provs'] = $data['citys'] = array();
			$condition = array(
					'AND' => array('disable=0'),
			);
			$res = $this->mBase->getList('areas',$condition,'*','sort ASC');
			if( $res ){
				foreach ( $res as $row ){
					if( $row['deep']==1 ){
						$data['provs'][] = $row;
					} elseif ( $row['deep']==2 ){
						$data['citys'][$row['father_id']][] = $row;
					}
				}
			}
			//get all stores
			$data['stores'] = array();
			$condition = array(
					'AND' => array('is_del=0'),
			);
			$res = $this->mBase->getList('stores',$condition);
			if( $res ){
				foreach ( $res as $row ){
					$data['stores'][$row['city']][] = $row;
				}
			}
			//display templates
			$tags['active_top_tag'] = $this->active_top_tag;
			$tags['active_menu_tag'] = 'purchase_add';
			$this->_view('common/header', $tags);
			$this->_view('product/purchase_create_from_user', $data);
			$this->_view('common/footer');
			return;
		} while(0);
		$this->showMsg($ret['err_no'],$ret['err_msg']);
		//$this->output($ret);
	}
}
