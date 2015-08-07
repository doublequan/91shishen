<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/dispatch.php';

class my extends Dispatch 
{
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('status','page','size','keyword');
		$this->checkParams('get',$must,$fields);
		if($this->input->get('status')===false){
			$this->params['status']='-1';
		}else{
			$this->params['status'] = intval($this->params['status']);
		}
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(10,min(100,intval($params['size'])));
		//get dispatches
		$condition = array(
			'AND' => array('is_del=0'),
		);
		if($params['status']!='-1'){
			$condition['AND'][] = "status={$params['status']}";
		}
		if( $params['keyword'] ){
			$condition['AND'][] = "id='{$params['keyword']}'";
		}
		$res = $this->mBase->getList('dispatchs',$condition,'*','status ASC,id DESC',$page,$size);
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//get cached stores
		$data['stores'] = $this->getCacheList('stores');
		//common data
		$data['statusMap'] = $this->statusMap;
		//display templates
		$tags['active_menu_tag'] = 'my';
		$this->_view('common/header', $tags);
		$this->_view('dispatch/my', $data);
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
		$tags['active_menu_tag'] = 'my_add';
		$this->_view('common/header', $tags);
		$this->_view('dispatch/add', $data);
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
		$condition = array(
			'AND' => array('is_del=0'),
		);
		$data['categorys'] = $this->getCacheList('goods_category',$condition,'list_categorys');
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
		$tags['active_menu_tag'] = 'my';
		$this->_view('common/header', $tags);
		$this->_view('dispatch/edit', $data);
		$this->_view('common/footer');
	}

	public function doAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('out_store','in_store');
			$fields = array('goods_id','goods_amount','products_id','products_amount');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			//check parameters
			$out_store = intval($params['out_store']);
			$in_store = intval($params['in_store']);
			$t = $this->mBase->getSingle('stores','id',$out_store);
			if( !$t ){
				$ret = array('err_no'=>1000,'err_msg'=>'出库门店不存在');
				break;
			}
			$t = $this->mBase->getSingle('stores','id',$in_store);
			if( !$t ){
				$ret = array('err_no'=>1000,'err_msg'=>'入库门店不存在');
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
				'id'			=> $dispatch_id,
				'out_store'		=> $out_store,
				'in_store'		=> $in_store,
				'last_eid'		=> self::$user['id'],
				'last_name'		=> self::$user['username'],
				'last_time'		=> time(),
				'create_eid'	=> self::$user['id'],
				'create_name'	=> self::$user['username'],
				'create_time'	=> time(),
			);
			$this->mBase->insert('dispatchs',$data);
			//insert action
			$this->addAction($dispatch_id, 1);
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
			$must = array('id','out_store','in_store');
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
			$out_store = intval($params['out_store']);
			$in_store = intval($params['in_store']);
			if( $single['out_store']!=$out_store ){
				$t = $this->mBase->getSingle('stores','id',$out_store);
				if( !$t ){
					$ret = array('err_no'=>1000,'err_msg'=>'出库门店不存在');
					break;
				}
			}
			if( $single['in_store']!=$in_store ){
				$t = $this->mBase->getSingle('stores','id',$in_store);
				if( !$t ){
					$ret = array('err_no'=>1000,'err_msg'=>'入库门店不存在');
					break;
				}
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
				'out_store'		=> $out_store,
				'in_store'		=> $in_store,
				'last_eid'		=> self::$user['id'],
				'last_name'		=> self::$user['username'],
				'last_time'		=> time(),
			);
			$this->mBase->update('dispatchs',$data,array('id'=>$params['id']));
			//insert action
			$this->addAction($params['id'], 2);
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
	
	public function doSubmit() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('ids');
			$fields = array();
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$ids = explode(',',$params['ids']);
			//check parameters
			if( !$ids ){
				$ret = array('err_no'=>1000,'err_msg'=>'请至少选择一个调度单');
				break;
			}
			foreach( $ids as $id ){
				$single = $this->mBase->getSingle('dispatchs','id',$id);
				if( $single['status']==0 ){
					$data = array(
						'status'	=> 1,
						'last_eid'	=> self::$user['id'],
						'last_name'	=> self::$user['username'],
						'last_time'	=> time(),
					);
					$this->mBase->update('dispatchs',$data,array('id'=>$id));
					//insert action
					$this->addAction($id, 3);
				}
			}
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
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
			//check
			$single = $this->mBase->getSingle('dispatchs','id',$params['id']);
			if( !$single ){
				$ret = array('err_no'=>1000,'err_msg'=>'调度单不存在');
				break;
			}
			if( $single['create_eid']!=parent::$user['id'] ){
				$ret = array('err_no'=>1000,'err_msg'=>'不可删除其他人创建的调度单');
				break;
			}
			$data = array(
				'is_del' => 1,
			);
			$this->mBase->update('dispatchs',$data,array('id'=>$params['id']));
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
}
