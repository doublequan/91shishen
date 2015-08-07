<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class sale extends Base 
{
	private $active_top_tag;
	private $sites;
	private $stores;

	public function __construct(){
		parent::__construct();
		$this->active_top_tag = 'product';
		$condition = array(
			'AND'	=> array('is_off=0','is_del=0'),
		);
		$res = $this->mBase->getList('sites',$condition);
		foreach( $res as $row ) {
			$this->sites[$row['id']] = $row;
		}
		$condition = array(
			'AND'	=> array('is_sell=1','is_del=0'),
		);
		$res = $this->mBase->getList('stores',$condition);
		foreach( $res as $row ) {
			$this->stores[$row['id']] = $row;
		}
	}
	
	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('site_id','store_id','day_start','day_end','page','size');
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		//get sales
		$site_id = intval($params['site_id']);
		$store_id = intval($params['store_id']);
		$condition = array();
		if( $site_id ){
			$condition['AND'][] = 'site_id='.$site_id;
		}
		if( $store_id ){
			$condition['AND'][] = 'store_id='.$store_id;
		}
		$res = $this->mBase->getList('sales',$condition,'*','create_time DESC',$page,$size);
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		$data['sites'] = $this->sites;
		$data['stores'] = $this->stores;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'sale';
		$this->_view('common/header', $tags);
		$this->_view('product/sale_list', $data);
		$this->_view('common/footer');
	}
	
	public function add(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('site_id','store_id','day_start','day_end','page','size');
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		//get sales
		$site_id = intval($params['site_id']);
		$store_id = intval($params['store_id']);
		$condition = array();
		if( $site_id ){
			$condition['AND'][] = 'site_id='.$site_id;
		}
		if( $store_id ){
			$condition['AND'][] = 'store_id='.$store_id;
		}
		$res = $this->mBase->getList('sales',$condition,'*','create_time DESC',$page,$size);
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		$data['sites'] = $this->sites;
		$data['stores'] = $this->stores;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'sale';
		$this->_view('common/header', $tags);
		$this->_view('product/sale_add', $data);
		$this->_view('common/footer');
	}
}
