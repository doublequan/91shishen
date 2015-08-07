<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class dispatch extends Base 
{
	private $active_top_tag  = 'product';
	
	public $statusMap = array(
			0 => '新建调度单',
			1 => '已确认',
			2 => '运输中',
			3 => '已完成',
	);
	
	private $good_method_types;

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
		$fields = array('status','page','size','keyword');
		$this->checkParams('get',$must,$fields);
		$params = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(15,min(100,intval($params['size'])));
		if(empty($params['status'])){
			$params['status'] = 0;
		}

		$data['params'] = $params;
		//get dispatches
		$condition = array(
			'AND' => array('is_del=0'),
		);
		if(!empty($params['keyword'])){
			$condition['AND'][] = "id='{$params['keyword']}'";
		}
		else{
			$condition['AND'][] = "status={$params['status']}";
		}

		$res = $this->mBase->getList('dispatchs',$condition,'*','id DESC',$page,$size);
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;

		$this->load->model('area_model', 'area');
		$city_res = $this->area->getCityList();
		foreach ($city_res as $row) {
			$data['city_list'][$row['id']] = $row;
		}

		//store names
		$store_res = $this->mBase->getList('stores',array('AND'=>array('is_del=0')),'id, name');
		foreach ($store_res as $row) {
			$data['store_list'][$row['id']] = $row;
		}

		$data['statusMap'] = $this->statusMap;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'dispatch';
		$this->_view('common/header', $tags);
		$this->_view('product/dispatch_list', $data);
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
		$tags['active_menu_tag'] = 'dispatch';
		$this->_view('common/header', $tags);
		$this->_view('product/dispatch_add', $data);
		$this->_view('common/footer');
	}
	
	public function edit() {
		$data = array();
		//get single
		$id = $this->input->get('id');
		$data['single'] = $this->mBase->getSingle('dispatchs','id',$id);
		if( $data['single']['status'] ){
			$this->showMsg(1000,'调度单不存在');
		}
		//get single info
		$data['out_store'] = $this->mBase->getSingle('stores', 'id', $data['single']['out_store']);
		$data['in_store'] = $this->mBase->getSingle('stores', 'id', $data['single']['in_store']);
		$data['employee_info'] = $this->mBase->getSingle('employees', 'id', $data['single']['delivery_eid']);
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
		//get all categorys
		$data['categorys'] = array();
		$condition = array(
			'AND' => array('is_del=0'),
		);
		$res = $this->mBase->getList('goods_category',$condition);
		if( $res ){
			foreach ( $res as $row ){
				$data['categorys'][$row['id']] = $row;
			}
		}
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
		$tags['active_menu_tag'] = 'dispatch';
		$this->_view('common/header', $tags);
		$this->_view('product/dispatch_edit', $data);
		$this->_view('common/footer');
	}
	
	public function detail() {
		$data = array();
		//get single
		$id = $this->input->get('id');
		$data['single'] = $this->mBase->getSingle('dispatchs','id',$id);
		if( $data['single']['status'] ){
			
		}
		$out_city = $this->mBase->getSingle('areas', 'id', $data['single']['out_city']);
		$out_prov = $this->mBase->getSingle('areas', 'id', $out_city['father_id']);
		$data['out_city'] = $out_city['name'];
		$data['out_prov'] = $out_prov['name'];
		$in_city = $this->mBase->getSingle('areas', 'id', $data['single']['in_city']);
		$in_prov = $this->mBase->getSingle('areas', 'id', $in_city['father_id']);
		$data['in_city'] = $in_city['name'];
		$data['in_prov'] = $in_prov['name'];

		$data['out_store_info'] = $this->mBase->getSingle('stores', 'id', $data['single']['out_store']);
		$data['in_store_info'] = $this->mBase->getSingle('stores', 'id', $data['single']['in_store']);

		$data['employee_info'] = $this->mBase->getSingle('employees', 'id', $data['single']['delivery_eid']);

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
		//get all categorys
		$data['categorys'] = array();
		$condition = array(
				'AND' => array('is_del=0'),
		);
		$res = $this->mBase->getList('goods_category',$condition);
		if( $res ){
			foreach ( $res as $row ){
				$data['categorys'][$row['id']] = $row;
			}
		}
		$this->_view('product/dispatch_detail', $data);
	}

	public function doAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('datetime','out_city','out_store','in_city','in_store','delivery_eid');
			$fields = array('goods_id','goods_amount','products_id','products_amount');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			//check parameters
			$arr = array('out_city','out_store','in_city','in_store','delivery_eid'); 
			foreach ( $arr as &$v ){
				$v = intval($v);
			}
			$date = strtotime($params['datetime']);
			$t = strtotime(date('Y-m-d'));
			if( $date<$t ){
				$ret = array('err_no'=>1000,'err_msg'=>'调度日期必须晚于当前日期');
				break;
			}
			$t = $this->mBase->getSingle('areas','id',$params['out_city']);
			if( !$t ){
				$ret = array('err_no'=>1000,'err_msg'=>'出库城市不存在');
				break;
			}
			$t = $this->mBase->getSingle('areas','id',$params['in_city']);
			if( !$t ){
				$ret = array('err_no'=>1000,'err_msg'=>'入库城市不存在');
				break;
			}
			$t = $this->mBase->getSingle('stores','id',$params['out_store']);
			if( !$t ){
				$ret = array('err_no'=>1000,'err_msg'=>'out 门店不存在');
				break;
			}
			$t = $this->mBase->getSingle('stores','id',$params['in_store']);
			if( !$t ){
				$ret = array('err_no'=>1000,'err_msg'=>'in 门店不存在');
				break;
			}
			$emp = $this->mBase->getSingle('employees','id',$params['delivery_eid']);
			if( !$emp ){
				$ret = array('err_no'=>1000,'err_msg'=>'delivery 员工不存在');
				break;
			}
			$flag = true;
			$arr = array('goods_id','goods_amount','products_id','products_amount');
			foreach( $arr as $v ){
				if( !($params[$v]=='' || is_array($params[$v])) ){
					$flag = false;
					$ret = array('err_no'=>1000,'err_msg'=>'parameter '.$v.' is not correct');
					break;
				}
			}
			if( !$flag ){
				break;
			}
			//insert data
			$dispatch_id = createBusinessID('DIS');
			$data = array(
				'id'				=> $dispatch_id,
				'datetime'			=> date('Y-m-d H:i:s',$date),
				'out_city'			=> $params['out_city'],
				'out_store'			=> $params['out_store'],
				'in_city'			=> $params['in_city'],
				'in_store'			=> $params['in_store'],
				'create_eid'		=> self::$user['id'],
				'create_name'		=> self::$user['username'],
				'create_time'		=> time(),
				'delivery_eid'		=> $params['delivery_eid'],
				'delivery_name'		=> $emp['username'],
			);
			$this->mBase->insert('dispatchs',$data);
			//insert dispatch details
			if( $params['goods_id'] && $params['goods_amount'] && count($params['goods_id'])==count($params['goods_amount']) ){
				$data = array();
				foreach( $params['goods_id'] as $k=>$good_id ){
					if( isset($params['goods_amount'][$k]) ){
						$good_id = intval($good_id);
						$t = $this->mBase->getSingle('goods','id',$good_id);
						if( $t ){
							$data[] = array(
								'dispatch_id'	=> $dispatch_id,
								'type'			=> 1,
								'good_id'		=> $good_id,
								'name'			=> $t['name'],
								'unit'			=> $t['unit'],
								'amount'		=> $params['goods_amount'][$k],
								'price'			=> $t['price'],
							);
						}
					}
				}
				if( $data ){
					$this->mBase->insertMulti('dispatchs_detail',$data);
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
								'dispatch_id'	=> $dispatch_id,
								'type'			=> 2,
								'product_id'	=> $products_id,
								'name'			=> $t['title'],
								'unit'			=> $t['unit'],
								'amount'		=> $params['products_amount'][$k],
								'price'			=> $t['price'],
							);
						}
					}
				}
				if( $data ){
					$this->mBase->insertMulti('dispatchs_detail',$data);
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
			$must = array('id','datetime','out_city','out_store','in_city','in_store','delivery_eid');
			$fields = array('goods_id','goods_amount','products_id','products_amount');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			//check parameters
			$single = $this->mBase->getSingle('dispatchs','id',$params['id']);
			if( !$single ){
				$ret = array('err_no'=>1000,'err_msg'=>'调度单存在');
				break;
			}
			if( $single['status'] ){
				$ret = array('err_no'=>1000,'err_msg'=>'调度单不可编辑');
				break;
			}
			$arr = array('out_city','out_store','in_city','in_store','delivery_eid');
			foreach ( $arr as &$v ){
				$v = intval($v);
			}
			$date = strtotime($params['datetime']);
			$t = strtotime(date('Y-m-d'));
			if( $date<=$t ){
				$ret = array('err_no'=>1000,'err_msg'=>'调度日期必须晚于当前日期');
				break;
			}
			$t = $this->mBase->getSingle('areas','id',$params['out_city']);
			if( !$t ){
				$ret = array('err_no'=>1000,'err_msg'=>'出库城市不存在');
				break;
			}
			$t = $this->mBase->getSingle('areas','id',$params['in_city']);
			if( !$t ){
				$ret = array('err_no'=>1000,'err_msg'=>'入库城市不存在');
				break;
			}
			$t = $this->mBase->getSingle('stores','id',$params['out_store']);
			if( !$t ){
				$ret = array('err_no'=>1000,'err_msg'=>'出库门店不存在');
				break;
			}
			$t = $this->mBase->getSingle('stores','id',$params['in_store']);
			if( !$t ){
				$ret = array('err_no'=>1000,'err_msg'=>'出库门店不存在');
				break;
			}
			$emp = $this->mBase->getSingle('employees','id',$params['delivery_eid']);
			if( !$emp ){
				$ret = array('err_no'=>1000,'err_msg'=>'调度负责员工不存在');
				break;
			}
			$flag = true;
			$arr = array('goods_id','goods_amount','products_id','products_amount');
			foreach( $arr as $v ){
				if( !($params[$v]=='' || is_array($params[$v])) ){
					$flag = false;
					$ret = array('err_no'=>1000,'err_msg'=>'parameter '.$v.' is not correct');
					break;
				}
			}
			if( !$flag ){
				break;
			}
			//update data
			$data = array(
				'datetime'			=> date('Y-m-d H:i:s',$date),
				'out_city'			=> $params['out_city'],
				'out_store'			=> $params['out_store'],
				'in_city'			=> $params['in_city'],
				'in_store'			=> $params['in_store'],
				'create_time'		=> time(),
				'delivery_eid'		=> $params['delivery_eid'],
				'delivery_name'		=> $emp['username'],
			);
			$this->mBase->update('dispatchs',$data,array('id'=>$params['id']));
			//insert dispatch details
			$this->mBase->delete('dispatchs_detail',array('dispatch_id'=>$params['id']),true);
			if( $params['goods_id'] && $params['goods_amount'] && count($params['goods_id'])==count($params['goods_amount']) ){
				$data = array();
				foreach( $params['goods_id'] as $k=>$good_id ){
					if( isset($params['goods_amount'][$k]) ){
						$good_id = intval($good_id);
						$t = $this->mBase->getSingle('goods','id',$good_id);
						if( $t ){
							$data[] = array(
								'dispatch_id'	=> $params['id'],
								'type'			=> 1,
								'good_id'		=> $good_id,
								'name'			=> $t['name'],
								'unit'			=> $t['unit'],
								'amount'		=> $params['goods_amount'][$k],
								'price'			=> $t['price'],
							);
						}
					}
				}
				if( $data ){
					$this->mBase->insertMulti('dispatchs_detail',$data);
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
								'dispatch_id'	=> $params['id'],
								'type'			=> 2,
								'product_id'	=> $products_id,
								'name'			=> $t['title'],
								'unit'			=> $t['unit'],
								'amount'		=> $params['products_amount'][$k],
								'price'			=> $t['price'],
							);
						}
					}
				}
				if( $data ){
					$this->mBase->insertMulti('dispatchs_detail',$data);
				}
			}
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	/**
	 * @desc UPDATE dispatch
	 * @param action: confirm,delivery,receive
	 */
	public function doUpdate() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('id','action','eid');
			$fields = array('leave_time','arrive_time');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$id = intval($params['id']);
			$action = $params['action'];
			$eid = intval($params['eid']);
			$actionMap = array(
				'confirm'	=> 1,
				'delivery'	=> 2,
				'receive'	=> 3,
			);
			//check parameters
			$dispatch = $this->mBase->getSingle('dispatchs','id',$id);
			if( !$dispatch ){
				$ret = array('err_no'=>1000,'err_msg'=>'调度单存在');
				break;
			}
			if( !(array_key_exists($action, $actionMap) && ($dispatch['status']+1)==$actionMap[$action]) ){
				$ret = array('err_no'=>1000,'err_msg'=>'action is illegal');
				break;
			}
			$emp = $this->mBase->getSingle('employees','id',$eid);
			if( !$emp ){
				$ret = array('err_no'=>1000,'err_msg'=>'员工不存在');
				break;
			}
			//update data
			if( $action=='confirm' ){
				$data = array(
					'confirm_eid'	=> $eid,
					'confirm_name'	=> $emp['name'],
					'comfirm_time'	=> time(),
				);
			} elseif ( $action=='delivery' ){
				$data = array(
					'delivery_eid'	=> $eid,
					'delivery_name'	=> $emp['name'],
					'delivery_time'	=> time(),
					'leave_time'	=> strtotime($params['leave_time']),
				);
			} elseif ( $action=='receive' ){
				$data = array(
					'receive_eid'	=> $eid,
					'receive_name'	=> $emp['name'],
					'receive_time'	=> time(),
					'arrive_time'	=> strtotime($params['arrive_time']),
				);
			}
			$this->mBase->update('dispatchs',$data,array('id'=>$id));
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function dispatch_print(){
		$data = array();
		//get single
		$id = $this->input->get('id');
		$data['single'] = $this->mBase->getSingle('dispatchs','id',$id);
		if( $data['single']['status'] ){
			
		}

		$out_city = $this->mBase->getSingle('areas', 'id', $data['single']['out_city']);
		$out_prov = $this->mBase->getSingle('areas', 'id', $out_city['father_id']);
		$data['out_city'] = $out_city['name'];
		$data['out_prov'] = $out_prov['name'];
		$in_city = $this->mBase->getSingle('areas', 'id', $data['single']['in_city']);
		$in_prov = $this->mBase->getSingle('areas', 'id', $in_city['father_id']);
		$data['in_city'] = $in_city['name'];
		$data['in_prov'] = $in_prov['name'];

		$data['out_store_info'] = $this->mBase->getSingle('stores', 'id', $data['single']['out_store']);
		$data['in_store_info'] = $this->mBase->getSingle('stores', 'id', $data['single']['in_store']);

		$data['employee_info'] = $this->mBase->getSingle('employees', 'id', $data['single']['delivery_eid']);

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
		
		//get all categorys
		$data['categorys'] = array();
		$condition = array(
				'AND' => array('is_del=0'),
		);
		$res = $this->mBase->getList('goods_category',$condition);
		if( $res ){
			foreach ( $res as $row ){
				$data['categorys'][$row['id']] = $row;
			}
		}
		
		$this->_view('product/dispatch_print', $data);
	}

	public function dispatch_print_muti(){
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

		$dispatch_list = array();
		foreach ($dispatch_list_tmp as $single_dispatch) {
			$single_id = $single_dispatch['id'];
			$dispatch_list[$single_id]['single'] = $single_dispatch;

			//prov & city
			$out_city = $city_list[$single_dispatch['out_city']];
			$dispatch_list[$single_id]['out_city'] = $out_city['name'];
			$dispatch_list[$single_id]['out_prov'] = $prov_list[$out_city['father_id']]['name'];

			$in_city = $city_list[$single_dispatch['in_city']];
			$dispatch_list[$single_id]['in_city'] = $in_city['name'];
			$dispatch_list[$single_id]['in_prov'] = $prov_list[$in_city['father_id']]['name'];

			//stores
			$dispatch_list[$single_id]['out_store_info'] = $store_list[$single_dispatch['out_store']];
			$dispatch_list[$single_id]['in_store_info'] = $store_list[$single_dispatch['in_store']];

			//employee
			$dispatch_list[$single_id]['employee_info'] = $this->mBase->getSingle('employees', 'id', $single_dispatch['delivery_eid']);

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
		
		//get all categorys
		$data['categorys'] = array();
		$condition = array(
				'AND' => array('is_del=0'),
		);
		$res = $this->mBase->getList('goods_category',$condition);
		if( $res ){
			foreach ( $res as $row ){
				$data['categorys'][$row['id']] = $row;
			}
		}

		$this->_view('product/dispatch_print_muti', $data);
	}
	
	public function getStock(){
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('type','store_id','ids');
			$fields = array();
			$this->checkParams('get',$must,$fields);
			$params = $this->params;
			$type = $params['type'];
			$store_id = intval($params['store_id']);
			//get good/product stock
			if( $type=='good' ){
				$table = 'goods_stock';
				$column = 'good_id';
			} else {
				$table = 'products_stock';
				$column = 'product_id';
			}
			$stocks = array();
			foreach ( explode(',', $params['ids']) as $id ){
				$id = intval($id);
				if( $id ){
					$condition = array(
						'AND' => array('store_id='.$store_id,$column.'='.$id),
					);
				}
				$res = $this->mBase->getList($table,$condition,'amount');
				$stocks[] = $res ? $res[0]['amount'] : 0;
			}
			$ret = array(
				'err_no'	=> 0,
				'err_msg'	=> '操作成功',
				'stocks'	=> $stocks,
			);
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
			$data = array(
				'is_del' => 1,
			);
			$this->mBase->update('dispatchs',$data,array('id'=>$params['id']));
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
}
