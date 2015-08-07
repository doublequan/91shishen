<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class index extends Base 
{
	private $active_top_tag = 'home';
	
	//Common Sites
	public $sites = array();

	public function __construct(){
		parent::__construct();
		$res = $this->mBase->getList('sites',array('AND'=>array('is_del=0')));
		if( $res ){
			foreach ( $res as $row ){
				$this->sites[$row['id']] = $row;
			}
		}
	}
	
	public function index(){
		$data = array();

		$site_num = $this->mBase->countList('sites', array('AND' => array('is_del=0') ));
		$employee_num = $this->mBase->countList('employees', array('AND' => array('is_del=0') ));
		$good_num = $this->mBase->countList('goods', array('AND' => array('is_del=0') ));
		$product_num = $this->mBase->countList('products', array('AND' => array('is_del=0') ));
		$user_num = $this->mBase->countList('users', array('AND' => array('status=1') ));
		$vip_user_num = $this->mBase->countList('vip_users', array('AND' => array('is_del=0') ));

		$data['system_info'] = array(
			'site_num'      => $site_num[0]['count(*)'],
			'employee_num'  => $employee_num[0]['count(*)'],
			'good_num'      => $good_num[0]['count(*)'],
			'product_num'   => $product_num[0]['count(*)'],
			'user_num'      => $user_num[0]['count(*)'],
			'vip_user_num'  => $vip_user_num[0]['count(*)'],
		);

		$order_num = $this->mBase->countList('orders');
		$order_nopay_num = $this->mBase->countList('orders', array('AND' => array('order_status=0') ));
		$order_pay_num = $this->mBase->countList('orders', array('AND' => array('order_status=1') ));
		$vip_order_num = $this->mBase->countList('vip_orders');
		$vip_order_nopay_num = $this->mBase->countList('vip_orders', array('AND' => array('order_status=0') ));
		$vip_order_pay_num = $this->mBase->countList('vip_orders', array('AND' => array('order_status=1') ));

		$data['order_info'] = array(
			'order_num'           => $order_num[0]['count(*)'],
			'order_nopay_num'     => $order_nopay_num[0]['count(*)'],
			'order_pay_num'       => $order_pay_num[0]['count(*)'],
			'vip_order_num'       => $vip_order_num[0]['count(*)'],
			'vip_order_nopay_num' => $vip_order_nopay_num[0]['count(*)'],
			'vip_order_pay_num'   => $vip_order_pay_num[0]['count(*)'],
		);

		$condition = array(
			'AND' => array('order_status=1'),
		);
		$results = $this->mBase->getList('orders',$condition,'*','create_time DESC',0,10);
		if( $results ){
			foreach( $results as &$row ){
				if( !isset($data['users'][$row['uid']]) ){
					$t= $this->mBase->getSingle('users','id',$row['uid']);
					$data['users'][$t['id']] = $t['username'];
				}
			}
			unset($row);
		}
		$data['results'] = $results;

		//common data
		$data['sites'] = $this->sites;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'index';
		$tags['task_status_types'] = getConfig('task_status_types');
		$tags['sites'] = $this->sites;
		$this->_view('common/header', $tags);
		$this->_view('home/index', $data);
		$this->_view('common/footer');
	}
	
}
