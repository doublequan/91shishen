<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/dispatch.php';

class index extends Dispatch 
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
		$this->params['status'] = $this->params['status']=='' ? 4 : $this->params['status'];
		$this->params['status'] = intval($this->params['status']);
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(10,min(100,intval($params['size'])));
		//get dispatches
		$condition = array(
			'AND' => array('is_del=0'),
		);
		if( intval($params['status'])>=0){
			$condition['AND'][] = "status={$params['status']}";
		}
		if( $params['keyword'] ){
			$condition['AND'][] = "id='{$params['keyword']}'";
		}
		$res = $this->mBase->getList('dispatchs',$condition,'*','id DESC',$page,$size);
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//get cached stores
		$data['stores'] = $this->getCacheList('stores');
		//common data
		$data['statusMap'] = $this->statusMap;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'index_status_'.$params['status'];
		$this->_view('common/header', $tags);
		$this->_view('dispatch/list', $data);
		$this->_view('common/footer');
	}
	
	public function receive(){
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
		//get details
		$details = array();
		$res = $this->mBase->getList('dispatchs_detail',array('AND'=>array("dispatch_id='{$id}'")));
		foreach ( $res as &$row ) {
			if( $row['type']==1 && $row['good_id'] ){
				$good = $this->mBase->getSingle('goods', 'id', $row['good_id']);
				$good['detail_id'] = $row['id'];
				$good['amount_plan'] = $row['amount'];
				$good['price'] = $row['price'];
				$details[1][] = $good;
			}
			if( $row['type']==2 && $row['product_id'] ){
				$product = $this->mBase->getSingle('products', 'id', $row['product_id']);
				$product['detail_id'] = $row['id'];
				$product['amount_plan'] = $row['amount'];
				$product['price'] = $row['price'];
				$details[2][] = $product;
			}
		}
		$data['details'] = $details;
		$this->_view('dispatch/receive', $data);
	}
	
	public function doConfirmReceive() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('dispatch_id','detail_id','amount_real');
			$fields = array();
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			//check parameter
			$single = $this->mBase->getSingle('dispatchs','id',$params['dispatch_id']);
			if( !$single ){
				$ret = array('err_no'=>1000,'err_msg'=>'调度单单不存在');
				break;
			}
			if( !($params['detail_id'] && $params['amount_real']) ){
				$ret = array('err_no'=>1000,'err_msg'=>'参数不合法');
				break;
			}
			if( count($params['detail_id'])!=count($params['amount_real']) ){
				$ret = array('err_no'=>1000,'err_msg'=>'参数不合法');
				break;
			}
			foreach( $params['detail_id'] as $k=>$v ){
				$this->load->model('Product_model', 'mProduct');
				$id = intval($v);
				$amount_real = isset($params['amount_real'][$k]) ? floatval($params['amount_real'][$k]) : 0;
				//Add Good Stock and Log
				$store_id = $single['in_store'];
				$detail = $this->mBase->getSingle('dispatchs_detail','id',$id);
				if( $detail ){
					if( $detail['type']==1 && $detail['good_id'] ){
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
					if( $detail['type']==2 && $detail['product_id'] ){
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
			$this->mBase->update('dispatchs',$data,array('id'=>$params['dispatch_id']));
			$this->addAction($params['dispatch_id'], 7);
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}

	public function doAction() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('id','status');
			$fields = array();
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$id = $params['id'];
			$status = intval($params['status']);
			//check parameters
			$single = $this->mBase->getSingle('dispatchs','id',$id);
			if( !$single || $single['is_del']!=0 ){
				$ret = array('err_no'=>1000,'err_msg'=>'调度单不存在');
				break;
			}
			if( !in_array($status, array(0,2,3)) ){
				$ret = array('err_no'=>1000,'err_msg'=>'非法操作');
				break;
			}
			if( $status==0 && $single['status']!=1 ){
				$ret = array('err_no'=>1000,'err_msg'=>'当前调度单不能打回');
				break;
			}
			if( $status==2 && $single['status']!=1 ){
				$ret = array('err_no'=>1000,'err_msg'=>'当前调度单不能通过审核');
				break;
			}
			if( $status==3 && $single['status']!=2 ){
				$ret = array('err_no'=>1000,'err_msg'=>'当前调度单不能出库');
				break;
			}
			$data = array(
				'status'	=> $status,
				'last_eid'	=> self::$user['id'],
				'last_name'	=> self::$user['username'],
				'last_time'	=> time(),
			);
			$this->mBase->update('dispatchs',$data,array('id'=>$id));
			//insert action
			$action = 0;
			if( $status==0 ){
				$action = 4;
			} elseif ( $status==2 ){
				$action = 5;
			} elseif ( $status==3 ){
				$action = 6;
			}
			$this->addAction($id, $status);
			//if status==3, minus stocks
			if ( $status==3 ){
				$this->load->model('Product_model', 'mProduct');
				$store_id = $single['out_store'];
				$condition = array(
					'AND' => array("dispatch_id='".$id."'"),
				);
				$details = $this->mBase->getList('dispatchs_detail',$condition);
				foreach( $details as $detail ){
					$detail_id = intval($detail['id']);
					$amount = 0-$detail['amount'];
					//Add Good Stock and Log
					if( $detail['type']==1 && $detail['good_id'] ){
						$good_id = $detail['good_id'];
						$good = $this->mBase->getSingle('goods','id',$good_id);
						if( $good ){
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
					if( $detail['type']==2 && $detail['product_id'] ){
						$product_id = $detail['product_id'];
						$product = $this->mBase->getSingle('products','id',$product_id);
						if( $product ){
							//add good stock and add log
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
			}
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
}