<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class log extends Base 
{
	private $active_top_tag = 'user';
	
	public function __construct(){
		parent::__construct();
	}

	public function score(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('uid','page','size');
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		//get users
		$condition = array();
		if( $params['uid'] ){
			$condition['AND'][] = 'uid='.$params['uid'];
		}
		$res = $this->mBase->getList('users_log_score',$condition,'*','id DESC',$page,$size);
		$data['users'] = array();
		if( $res->results ){
			foreach ( $res->results as $row ){
				if( !isset($data['users'][$row['uid']]) ){
					$t= $this->mBase->getSingle('users','id',$row['uid']);
					$data['users'][$t['id']] = $t['username'];
				}
			}
		}
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'log_score';
		$this->_view('common/header', $tags);
		$this->_view('user/log_score_list', $data);
		$this->_view('common/footer');
	}
	
	public function login(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('page','size');
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		//get users
		$condition = array();
		$res = $this->mBase->getList('users_log_login',$condition,'*','id DESC',$page,$size);
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'log_login';
		$this->_view('common/header', $tags);
		$this->_view('user/log_login_list', $data);
		$this->_view('common/footer');
	}
	
	public function money(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('page','size');
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		//get users
		$condition = array();
		$res = $this->mBase->getList('users_log_money',$condition,'*','id DESC',$page,$size);
		$data['users'] = array();
		$data['employees'] = array(
			0 => '系统操作',
		);
		if( $res->results ){
			foreach ( $res->results as $row ){
				if( !isset($data['users'][$row['uid']]) ){
					$t= $this->mBase->getSingle('users','id',$row['uid']);
					$data['users'][$t['id']] = $t['username'];
				}
				if( !isset($data['employees'][$row['eid']]) ){
					$t= $this->mBase->getSingle('employees','id',$row['eid']);
					$data['employees'][$t['id']] = $t['username'];
				}
			}
		}
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'log_money';
		$this->_view('common/header', $tags);
		$this->_view('user/log_money_list', $data);
		$this->_view('common/footer');
	}
	
	//type: 1->代金券    2->抵用券
	public function coupon(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('type','page','size');
		$this->checkParams('get',$must,$fields);
		$this->params['type'] = in_array($this->params['type'],array(1,2)) ? $this->params['type'] : 1;
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		//get data
		$condition = array(
			'AND' => array('coupon_type='.$params['type']),
		);
		$res = $this->mBase->getList('users_log_coupon',$condition,'*','id DESC',$page,$size);
		$data['users'] = array();
		$data['coupons'] = array();
		if( $res->results ){
			foreach ( $res->results as $row ){
				if( !isset($data['users'][$row['uid']]) ){
					$t= $this->mBase->getSingle('users','id',$row['uid']);
					$data['users'][$t['id']] = $t['username'];
				}
				if( !isset($data['coupons'][$row['coupon_id']]) ){
					$t= $this->mBase->getSingle('users_coupon','id',$row['coupon_id']);
					$data['coupons'][$t['id']] = $t['coupon_code'];
				}
			}
		}
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		$data['title'] = $params['type']==1 ? '代金券' : '抵用券';
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = $params['type']==1 ? 'log_coupon_cash' : 'log_coupon_discount';
		$this->_view('common/header', $tags);
		$this->_view('user/log_coupon_list', $data);
		$this->_view('common/footer');
	}
	
	public function view(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('page','size');
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		//get data
		$this->load->model('User_model', 'mUser');
		$res = $this->mUser->getProductViewStat($page,$size);
		if( $res->results ){
			foreach ( $res->results as &$row ){
				$t= $this->mBase->getSingle('products','id',$row['product_id']);
				$row['product_name'] = $t['title'];
			}
			unset($row);
		}
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'log_view';
		$this->_view('common/header', $tags);
		$this->_view('user/log_view_list', $data);
		$this->_view('common/footer');
	}
}
