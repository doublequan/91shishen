<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class order extends Base 
{
	private $active_top_tag = 'vip';
	
	public $statusMap = array(
		0	=> '新订单未支付',
		1	=> '支付成功订单',
		20	=> '已完成订单',
	);

	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('status','keyword','page','size');
		$this->checkParams('get',$must,$fields);
		if( $this->params['status']=='' || !array_key_exists($this->params['status'],$this->statusMap) ){
			$this->params['status'] = -1;
		}

		$status = intval($this->params['status']);
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		//get data
		$condition = array();
		if( $status!=-1 ){
			$condition['AND'][] = 'order_status='.$status;
		}
		$k = $params['keyword'];
		if( !empty($k) ){
			$condition['AND'][] = "id LIKE '%".$k."%'";
		}

		$data['users'] = array();
		$res = $this->mBase->getList('vip_orders',$condition,'*','create_time DESC',$page,$size);
		if( $res->results ){
			foreach( $res->results as &$row ){
				if(isset($k)){
					$row['order_id'] = $k ? str_replace($k, '<font color="red">'.$k.'</font>', $row['id']) : $row['id'];
				}
				else{
					$row['order_id'] = $row['id'];
				}
				if( !isset($data['users'][$row['uid']]) ){
					$t= $this->mBase->getSingle('vip_users','id',$row['uid']);
					if($t){
						$data['users'][$t['id']] = $t['username'];
					}
				}
			}
			unset($row);
		}
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//common data
		$data['statusMap'] = $this->statusMap;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'status_'.$params['status'];
		$this->_view('common/header', $tags);
		$this->_view('vip/order_list', $data);
		$this->_view('common/footer');
	}
	
	public function detail(){
		$data = array();
		//get parameters
		$must = array('order_id');
		$fields = array();
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		//get order
		$data['single'] = $this->mBase->getSingle('vip_orders','id',$params['order_id']);
		if( $data['single'] ){
			$arr = array('prov','city','district');
			foreach( $arr as $v ){
				$cache_key = 'AREA_'.$data['single'][$v];
				$t = Common_Cache::get($cache_key);
				if( !$t ){
					$t= $this->mBase->getSingle('areas','id',$data['single'][$v]);
					Common_Cache::save($cache_key, $t, 86400);
				}
				$data['single'][$v] = $t['name'];
			}
		}
		//get order details
		$condition = array(
			'AND' => array("order_id='".$params['order_id']."'"),
		);
		$data['details'] = $this->mBase->getList('vip_orders_detail',$condition);
		if( $data['details'] ){
			foreach( $data['details'] as &$row ){
				$t = $this->mBase->getSingle('vip_products','id',$row['product_id']);
				$row['product_no'] = $t['sku'];
				$row['unit'] = $t['unit'];
				$row['spec'] = $t['spec'];
				$row['spec_packing'] = $t['spec_packing'];
			}
			unset($row);
		}
		//get userinfo
		$data['vip_industry'] = array();
		$data['vip_company'] = array();
		$data['vip_user'] = array();
		if( $data['single']['uid'] ){
			$data['vip_user'] = $this->mBase->getSingle('vip_users','id',$data['single']['uid']);
			if( $data['vip_user']['company_id'] ){
				$data['vip_company'] = $this->mBase->getSingle('vip_companys','id',$data['vip_user']['company_id']);
				if( $data['vip_company']['industry_id'] ){
					$data['vip_industry'] = $this->mBase->getSingle('vip_industrys','id',$data['vip_company']['industry_id']);
				}
				$arr = array('prov','city');
				foreach( $arr as $v ){
					$cache_key = 'AREA_'.$data['vip_company'][$v];
					$t = Common_Cache::get($cache_key);
					if( !$t ){
						$t= $this->mBase->getSingle('areas','id',$data['vip_company'][$v]);
						Common_Cache::save($cache_key, $t, 86400);
					}
					$data['vip_company'][$v] = $t['name'];
				}	
			}
		}
		//Common Data
		$data['statusMap'] = $this->statusMap;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'order';
		$this->_view('common/header', $tags);
		$this->_view('vip/order_detail', $data);
		$this->_view('common/footer');
	}
	
	public function order_print(){
		$data = array();
		//get parameters
		$must = array('order_id');
		$fields = array();
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		//get order
		$data['single'] = $this->mBase->getSingle('vip_orders','id',$params['order_id']);
		if( $data['single'] ){
			$arr = array('prov','city','district');
			foreach( $arr as $v ){
				$cache_key = 'AREA_'.$data['single'][$v];
				$t = Common_Cache::get($cache_key);
				if( !$t ){
					$t= $this->mBase->getSingle('areas','id',$data['single'][$v]);
					Common_Cache::save($cache_key, $t, 86400);
				}
				$data['single'][$v] = $t ? $t['name'] : '未知区域';
			}
		}
		//get order details
		$condition = array(
			'AND' => array("order_id='".$params['order_id']."'"),
		);
		$data['details'] = $this->mBase->getList('vip_orders_detail',$condition);
		if( $data['details'] ){
			foreach( $data['details'] as &$row ){
				$t = $this->mBase->getSingle('vip_products','id',$row['product_id']);
				$row['product_no'] = $t['sku'];
				$row['unit'] = $t['unit'];
				$row['spec'] = $t['spec'];
				$row['spec_packing'] = $t['spec_packing'];
			}
			unset($row);
		}
		//get userinfo
		$data['current_user'] = array();
		if( $data['single']['uid'] ){
			$data['current_user'] = $this->mBase->getSingle('vip_users','id',$data['single']['uid']);
		}
		//get order action
		$condition = array(
			'AND' => array("order_id='".$params['order_id']."'"),
		);
		$data['actions'] = $this->mBase->getList('orders_action',$condition,'*','id ASC');
		
		//Common Data
		$data['statusMap'] = $this->statusMap;
		//display templates
		$this->_view('vip/order_print', $data);
	}

	public function order_print_muti(){
		$data = array();
		//get parameters
		$must = array('order_ids');
		$fields = array();
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;

		//get orders
		$order_ids = $params['order_ids'];
		$order_list_tmp = $this->mBase->getList('vip_orders', array('AND' => array('id in ('.$order_ids.')')));
		if(!$order_list_tmp){
			$this->showMsg(1000,'订单不存在');
			return;
		}

		$order_list = array();
		foreach ($order_list_tmp as $single_order) {
			$arr = array('prov','city','district');
			foreach( $arr as $v ){
				$cache_key = 'AREA_'.$single_order[$v];
				$t = Common_Cache::get($cache_key);
				if( !$t ){
					$t= $this->mBase->getSingle('areas','id',$single_order[$v]);
					Common_Cache::save($cache_key, $t, 86400);
				}
				$single_order[$v] = $t ? $t['name'] : '未知区域';
			}
			$order_list[$single_order['id']]['single'] = $single_order;

			//get userinfo
			$order_list[$single_order['id']]['current_user'] = array();
			if( $single_order['uid'] ){
				$order_list[$single_order['id']]['current_user'] = $this->mBase->getSingle('users','id',$single_order['uid']);
			}

		}

		//get order details
		$order_details = $this->mBase->getList('vip_orders_detail', array('AND' => array('order_id in ('.$order_ids.')')));
		foreach ($order_details as $single_detail) {
			$t = $this->mBase->getSingle('vip_products','id',$single_detail['product_id']);
			$single_detail['product_no'] = $t['sku'];
			$single_detail['unit'] = $t['unit'];
			$single_detail['spec'] = $t['spec'];
			$single_detail['spec_packing'] = $t['spec_packing'];

			$order_list[$single_detail['order_id']]['details'][] = $single_detail;
		}

		//get order action
		$data['actions'] = $this->mBase->getList('orders_action',array('AND'=>array('order_id in ('.$order_ids.')')),'*','id ASC');
		//order_list
		$data['order_list'] = $order_list;

		//Common Data
		$data['statusMap'] = $this->statusMap;
		//display templates
		$this->_view('vip/order_print_muti', $data);
	}

	public function doAction() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('order_id','status');
			$this->checkParams('post',$must);
			$params = $this->params;
			$status = intval($params['status']);
			//check parameters
			if( !array_key_exists($status, $this->statusMap) ){
				$ret = array('err_no'=>1000,'err_msg'=>'非法操作');
				break;
			}
			$order = $this->mBase->getSingle('vip_orders','id',$params['order_id']);
			if( !$order ){
				$ret = array('err_no'=>1000,'err_msg'=>'订单不存在');
				break;
			}
			$data = array(
				'order_status' => $status,
			);
			$this->mBase->update('vip_orders',$data,array('id'=>$order['id']));
			//Add Order Action
			$data = array(
				'order_id'		=> $order['id'],
				'status'		=> $status,
				'des'			=> '员工操作订单',
				'is_show'		=> 1,
				'create_eid'	=> parent::$user['id'],
				'create_name'	=> parent::$user['username'],
				'create_time'	=> time(),
			);
			$this->mBase->insert('vip_orders_action',$data);
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
}
