<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class stock extends Base 
{
	private $active_top_tag = 'product';
	
	private $stores;

	public $qtMap = array(
		1	=> 'good',
		2	=> 'product',
	);
	
	public $typeMap = array(
		1	=> '入库',
		2	=> '出库',
		3	=> '订单',
		4	=> '损耗',
	);

	public function __construct(){
		parent::__construct();
		//stores
		$store_res = $this->mBase->getList('stores');
		foreach ($store_res as $row) {
			$this->stores[$row['id']] = $row;
		}
	}
	
	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('qt','store_id','item_id','keyword','page','size');
		$this->checkParams('get',$must,$fields);
		$this->params['qt'] = $this->params['qt'] ? $this->params['qt'] : '';
		if( !in_array($this->params['qt'],$this->qtMap) ){
			$this->params['qt'] = current($this->qtMap);
		}
		$this->params['store_id'] = $this->params['store_id'] ? $this->params['store_id'] : '';
		if( !array_key_exists($this->params['store_id'], $this->stores) ){
			$this->params['store_id'] = 0;
		}
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		$store_id = intval($params['store_id']);
		$item_id = intval($params['item_id']);
		$keyword = $params['keyword'];
		//get stock list
		$this->load->model('Product_model', 'mProduct');
		if( $params['qt']=='good' ){
			$res = $this->mProduct->getGoodStock($store_id,$item_id,$keyword,$page,$size);
		} else {
			$res = $this->mProduct->getProductStock($store_id,$item_id,$keyword,$page,$size);
		}
		if( $res->results ){
			foreach ( $res->results as &$row ){
				$arr = array(
					'qt'			=> $params['qt'],
					'store_id'		=> $store_id ? $store_id : $row['store_id'],
					'store_name'	=> $store_id ? $this->stores[$store_id]['name'] : '全部门店',
					'item_id'		=> $row[$params['qt'].'_id'],
					'item_name'		=> $params['qt']=='good' ? $row['name'] : $row['title'],
				);
				$row['log_url'] = site_url('products/stock/log_list?'.http_build_query($arr));
			}
			unset($row);
		}
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		$data['qtMap'] = $this->qtMap;
		$data['stores'] = $this->stores;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'stock';
		$this->_view('common/header', $tags);
		$this->_view('product/stock_list', $data);
		$this->_view('common/footer');
	}
	
	public function deal(){
		$data = array();
		//get parameters
		$must = array('type','id','flag','data','store_id');
		$this->checkParams('get',$must);
		$params = $data['params'] = $this->params;
		$type = intval($params['type']);
		$id = intval($params['id']);
                $data['flag'] = intval($params['flag']);
                $data['area_data'] = $params['data'];
                $data['stores_id'] = intval($params['store_id']);
                $data['type'] =$type;
		//get single
		$single = array();
		if( $type==1 ){
			$single = $this->mBase->getSingle('goods','id',$id);
		} elseif( $type==2 ) {
			$single = $this->mBase->getSingle('products','id',$id);
		}
		if( !($single && $single['is_del']==0) ){
			$this->showMsg(1000,'原料或者商品不存在');
			return;
		}
		$data['single'] = $single;
		//get all undisabled provinces and cities
		$data['provs'] = array();
		$data['citys'] = array();
		$data['stores'] = array();
		$condition = array(
			'AND' => array('disable=0'),
		);
		$res = $this->mBase->getList('areas',$condition,'*','sort ASC');
		if( $res ){
			foreach ( $res as $row ){
				if( $row['deep']==1 ){
					$data['provs'][$row['id']] = $row;
				} elseif ( $row['deep']==2 ){
					$data['citys'][$row['father_id']][$row['id']] = $row;
				}
			}
		}
		//get all stores
		$res = $this->mBase->getList('stores',array('AND'=>array('is_del=0')));
		if( $res ){
			foreach ( $res as $row ){
				$data['stores'][$row['city']][$row['id']] = $row;
			}
		}
		$this->_view('product/stock_deal', $data);
	}
	
	public function doDeal() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('type','id','store_id','action','amount');
			$this->checkParams('post',$must);
			$params = $this->params;
			$type = intval($params['type']);
			$id = intval($params['id']);
			$store_id = intval($params['store_id']);
			$action = $params['action']==1 ? 1 : -1;
			$amount = floatval($params['amount']);
			$amount = $amount*$action;
			//check parameters
			$t = $this->mBase->getSingle('stores','id',$store_id);
			if( !$t ){
				$ret = array('err_no'=>1000,'err_msg'=>'入库门店不存在');
				break;
			}
			//Goods Stock
			$this->load->model('Product_model', 'mProduct');
			if( $type==1 && $id ){
				$good = $this->mBase->getSingle('goods','id',$id);
				if( $good && $amount ){
					//add good stock and add log
					$current = $this->mProduct->updateStock('good',$store_id,$good,$amount);
					$data = array(
						'store_id'		=> $store_id,
						'good_id'		=> $id,
						'type'			=> ($action == 1)? 1 : 2,
						'change'		=> $amount,
						'current'		=> $current,
						'create_eid'	=> self::$user['id'],
						'create_name'	=> self::$user['username'],
						'create_time'	=> time(),
					);
					$this->mBase->insert('goods_stock_log', $data);
				}
			}
			//Products Stock
			if( $type==2 && $id ){
				$product = $this->mBase->getSingle('products','id',$id);
				if( $product && $amount ){
					//add product stock and add log
					$current = $this->mProduct->updateStock('product',$store_id,$product,$amount);
					$data = array(
						'store_id'		=> $store_id,
						'product_id'	=> $id,
						'type'			=> ($action == 1)? 1 : 2,
						'change'		=> $amount,
						'current'		=> $current,
						'create_eid'	=> self::$user['id'],
						'create_name'	=> self::$user['username'],
						'create_time'	=> time(),
					);
					$this->mBase->insert('products_stock_log', $data);
				}
			}
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
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
		$tags['active_menu_tag'] = 'stock';
		$this->_view('common/header', $tags);
		$this->_view('product/stock_add', $data);
		$this->_view('common/footer');
	}
	
	public function doAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('in_store');
			$fields = array('goods_id','goods_amount','products_id','products_amount');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$store_id = intval($params['in_store']);
			//check parameters
			$t = $this->mBase->getSingle('stores','id',$store_id);
			if( !$t ){
				$ret = array('err_no'=>1000,'err_msg'=>'入库门店不存在');
				break;
			}
			$flag = true;
			$arr = array('goods_id','goods_amount','products_id','products_amount');
			foreach( $arr as $v ){
				if( !($params[$v]=='' || is_array($params[$v])) ){
					$flag = false;
					$ret = array('err_no'=>1000,'err_msg'=>'库存参数错误');
					break;
				}
			}
			if( !$flag ){
				break;
			}
			//Goods Stock
			$this->load->model('Product_model', 'mProduct');
			$count = count($params['goods_id']);
			if( is_array($params['goods_id']) && $count ){
				for( $i=0; $i<$count; $i++ ){
					$good_id = $params['goods_id'][$i];
					$amount = floatval($params['goods_amount'][$i]);
					$good = $this->mBase->getSingle('goods','id',$good_id);
					if( $good && $amount ){
						//add good stock and add log
						$current = $this->mProduct->updateStock('good',$store_id,$good,$amount);
						$data = array(
							'store_id'		=> $store_id,
							'good_id'		=> $good_id,
							'type'			=> 1,
							'change'		=> $amount,
							'current'		=> $current,
							'create_eid'	=> self::$user['id'],
							'create_name'	=> self::$user['username'],
							'create_time'	=> time(),
						);
						$this->mBase->insert('goods_stock_log', $data);
					}
				}
			}
			//Products Stock
			$count = count($params['products_id']);
			if( is_array($params['products_id']) && $count ){
				for( $i=0; $i<$count; $i++ ){
					$product_id = $params['products_id'][$i];
					$amount = floatval($params['products_amount'][$i]);
					$product = $this->mBase->getSingle('products','id',$product_id);
					if( $product && $amount ){
						//add product stock and add log
						$current = $this->mProduct->updateStock('product',$store_id,$product,$amount);
						$data = array(
							'store_id'		=> $store_id,
							'product_id'	=> $product_id,
							'type'			=> 1,
							'change'		=> $amount,
							'current'		=> $current,
							'create_eid'	=> self::$user['id'],
							'create_name'	=> self::$user['username'],
							'create_time'	=> time(),
						);
						$this->mBase->insert('products_stock_log', $data);
					}
				}
			}
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function log_list(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('qt','store_id','store_name','item_id','item_name','page','size');
		$this->checkParams('get',$must,$fields);
		$this->params['qt'] = $this->params['qt'] ? $this->params['qt'] : '';
		if( !in_array($this->params['qt'],$this->qtMap) ){
			$this->params['qt'] = current($this->qtMap);
		}
		$this->params['store_id'] = $this->params['store_id'] ? $this->params['store_id'] : '';
		if( !array_key_exists($this->params['store_id'], $this->stores) ){
			$this->params['store_id'] = 0;
		}
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		//get list
		$store_id = intval($params['store_id']);
		$item_id = intval($params['item_id']);
		$condition = array();
		$existMap = array();
		if( $params['qt']=='good' ){
			$table = 'goods_stock_log';
			if( $store_id ){
				$condition['AND'][] = 'store_id='.$store_id;
			}
			if( $item_id ){
				$condition['AND'][] = 'good_id='.$item_id;
			}
			$res = $this->mBase->getList($table,$condition,'*','id DESC',$page,$size);
			if( $res->results ){
				foreach ( $res->results as &$row ){
					if( isset($existMap[$row['good_id']]) ){
						$row['good'] = $existMap[$row['good_id']];
					} else {
						$t = $this->mBase->getSingle('goods','id',$row['good_id']);
						$row['good'] = $t;
						$existMap[$row['good_id']] = $t;
					}
				}
				unset($row);
			}
		} else {
			$table = 'products_stock_log';
			if( $store_id ){
				$condition['AND'][] = 'store_id='.$store_id;
			}
			if( $item_id ){
				$condition['AND'][] = 'product_id='.$item_id;
			}
			$res = $this->mBase->getList($table,$condition,'*','id DESC',$page,$size);
			if( $res->results ){
				foreach ( $res->results as &$row ){
					if( isset($existMap[$row['product_id']]) ){
						$row['product'] = $existMap[$row['product_id']];
					} else {
						$t = $this->mBase->getSingle('products','id',$row['product_id']);
						$row['product'] = $t;
						$existMap[$row['product_id']] = $t;
					}
				}
				unset($row);
			}
		}
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		$data['qtMap'] = $this->qtMap;
		$data['typeMap'] = $this->typeMap;
		$data['stores'] = $this->stores;
		$data['existMap'] = $existMap;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'stock';
		$this->_view('common/header', $tags);
		$this->_view('product/stock_log_list', $data);
		$this->_view('common/footer');
	}
	
	public function export(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('qt','store_id','item_id','keyword');
		$this->checkParams('get',$must,$fields);
		$this->params['qt'] = $this->params['qt'] ? $this->params['qt'] : '';
		if( !in_array($this->params['qt'],$this->qtMap) ){
			$this->params['qt'] = current($this->qtMap);
		}
		$this->params['store_id'] = $this->params['store_id'] ? $this->params['store_id'] : '';
		if( !array_key_exists($this->params['store_id'], $this->stores) ){
			$this->params['store_id'] = 0;
		}
		$params = $this->params;
		$store_id = intval($params['store_id']);
		$item_id = intval($params['item_id']);
		$keyword = $params['keyword'];
		//get stock list
		$header = array();
		$data = array();
		$this->load->model('Product_model', 'mProduct');
		if( $params['qt']=='good' ){
			$header = array('序号','原料ID','原料名称','门店','货架位置','库存数量','库存最后变更时间');
			$data = array();
			$res = $this->mProduct->getGoodStock($store_id,$item_id,$keyword);
			if( $res->results ){
				foreach ( $res->results as $k=>$row ){
					$data[$k][] = $k+1;
					$data[$k][] = $row['good_id'];
					$data[$k][] = $row['name'];
					$data[$k][] = $store_id ? $this->stores[$store_id]['name'] : $this->stores[$row['store_id']]['name'];
					$data[$k][] = $row['place'];
					$data[$k][] = $row['amount'];
					$data[$k][] = $row['change_time'] ? date('Y-m-d H:i:s',$row['change_time']) : '';
				}
			}
		} else {
			$header = array('序号','商品ID','商品编号','商品货号','商品名称','门店','货架位置','库存数量','库存最后变更时间');
			$data = array();
			$res = $this->mProduct->getProductStock($store_id,$item_id,$keyword);
			if( $res->results ){
				foreach ( $res->results as $k=>$row ){
					$data[$k][] = $k+1;
					$data[$k][] = $row['product_id'];
					$data[$k][] = $row['sku'];
					$data[$k][] = $row['product_pin'];
					$data[$k][] = $row['title'];
					$data[$k][] = $store_id ? $this->stores[$store_id]['name'] : $this->stores[$row['store_id']]['name'];
					$data[$k][] = $row['place'];
					$data[$k][] = $row['amount'];
					$data[$k][] = $row['change_time'] ? date('Y-m-d H:i:s',$row['change_time']) : '';
				}
			}
		}
		$this->exportExcel( $header, $data );
	}
}
