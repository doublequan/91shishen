<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class Site extends Base 
{
	private $active_top_tag = 'enterprise';
	
	private $provs;
	
	private $citys;
	
	private $stores;
	
	private $storeMap;

	public function __construct(){
		parent::__construct();
		//get all undisabled provinces and cities
		$condition = array(
			'AND' => array('disable=0'),
		);
		$res = $this->mBase->getList('areas',$condition,'*','sort ASC');
		if( $res ){
			foreach ( $res as $row ){
				if( $row['deep']==1 ){
					$this->provs[$row['id']] = $row;
				} elseif ( $row['deep']==2 ){
					$this->citys[$row['father_id']][$row['id']] = $row;
				}
			}
		}
		//get all stores
		$res = $this->mBase->getList('stores',array('AND'=>array('is_del=0')));
		if( $res ){
			foreach ( $res as $row ){
				$this->stores[$row['id']] = $row;
				$this->storeMap[$row['city']][$row['id']] = $row;
			}
		}
	}

	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('page','size');
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		$res = $this->mBase->getList('sites',array('AND'=>array('is_del=0')),'*','id ASC',$page,$size);
		$data['areas'] = array();
		$data['stores'] = array();
		if( $res->results ){
			$areas = array();
			$stores = array();
			foreach ( $res->results as &$row ){
				//get row prov name
				if( isset($areas[$row['prov']]) ){
					$row['prov'] = $areas[$row['prov']];
				} else {
					$t = $this->mBase->getSingle('areas','id',$row['prov']);
					$row['prov'] = $t ? $t['name'] : '';
				}
				//get row city name
				if( isset($areas[$row['city']]) ){
					$row['city'] = $areas[$row['city']];
				} else {
					$t = $this->mBase->getSingle('areas','id',$row['city']);
					$row['city'] = $t ? $t['name'] : '';
				}
				//get row store name
				if( isset($stores[$row['default_store']]) ){
					$row['store'] = $stores[$row['default_store']];
				} else {
					$t = $this->mBase->getSingle('stores','id',$row['default_store']);
					$row['store'] = $t ? $t['name'] : '';
				}
			}
		}
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//common data
		$data['provs'] = $this->provs;
		$data['citys'] = $this->citys;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'site';
		$this->_view('common/header', $tags);
		$this->_view('enterprise/site_list', $data);
		$this->_view('common/footer');
	}

	public function add(){
		$data = array();
		//common data
		$data['provs'] = $this->provs;
		$data['citys'] = $this->citys;
		$data['storeMap'] = $this->storeMap;
		//display templates
		$this->_view('enterprise/site_add', $data);
	}
	
	public function edit(){
		$data = array();
		//get single group
		$id = intval($this->input->get('id'));
		if( !$id ){
			$this->showMsgDialog(1000,'参数错误');
		}
		$data['single'] = $this->mBase->getSingle('sites','id',$id);
		//get store info
		$data['store'] = $this->mBase->getSingle('stores','id',$data['single']['default_store']);
		if( !$data['store'] ){
			$data['store']['prov'] = 320000;
			$data['store']['city'] = 320100;
			$data['single']['default_store'] = 0;
		}
		//common data
		$data['provs'] = $this->provs;
		$data['citys'] = $this->citys;
		$data['storeMap'] = $this->storeMap;
		//display templates
		$this->_view('enterprise/site_edit', $data);
	}
	
	public function doAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('name','prov','city','default_store');
			$fields = array();
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$prov = intval($params['prov']);
			$city = intval($params['city']);
			$default_store = intval($params['default_store']);
			//check parameters
			if( !isset($this->provs[$prov]) ){
				$ret = array('err_no'=>1000,'err_msg'=>'所选省份不存在');
				break;
			}
			if( !isset($this->citys[$prov][$city]) ){
				$ret = array('err_no'=>1000,'err_msg'=>'所选城市不存在');
				break;
			}
			if( !isset($this->stores[$default_store]) ){
				$ret = array('err_no'=>1000,'err_msg'=>'所选门店不存在');
				break;
			}
			//insert data
			$data = array(
				'name'			=> $params['name'],
				'prov'			=> $prov,
				'city'			=> $city,
				'default_store'	=> $default_store,
				'create_eid'	=> self::$user['id'],
				'create_name'	=> self::$user['username'],
				'create_time'	=> time(),
			);
			$this->mBase->insert('sites',$data);
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function doEdit() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('id','name','prov','city','default_store');
			$fields = array();
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$id = intval($params['id']);
			$prov = intval($params['prov']);
			$city = intval($params['city']);
			$default_store = intval($params['default_store']);
			//check parameters
			$site = $this->mBase->getSingle('sites','id',$id);
			if( !$site ){
				$ret = array('err_no'=>1000,'err_msg'=>'网站不存在');
				break;
			}
			if( !isset($this->provs[$prov]) ){
				$ret = array('err_no'=>1000,'err_msg'=>'所选省份不存在');
				break;
			}
			if( !isset($this->citys[$prov][$city]) ){
				$ret = array('err_no'=>1000,'err_msg'=>'所选城市不存在');
				break;
			}
			if( !isset($this->stores[$default_store]) ){
				$ret = array('err_no'=>1000,'err_msg'=>'所选门店不存在');
				break;
			}
			//update data
			$data = array(
				'name'			=> $params['name'],
				'prov'			=> $prov,
				'city'			=> $city,
				'default_store'	=> $default_store,
			);
			$this->mBase->update('sites',$data,array('id'=>$id));
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}

	public function siteStatus(){
		$site_id = $this->input->post('site_id');
		$status = $this->input->post('status');

		if(empty($site_id)){
			die('{"error":1, "msg": "信息错误，请刷新页面重试！"}');
		}

		$data_info['is_off'] = intval($status);
		$site_id_map = array('id' => $site_id);
		$update_rst = $this->mBase->update($this->table_name, $data_info, $site_id_map);
		
		die('{"error":0, "msg": ""}');
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
			$this->mBase->update('sites',$data,array('id'=>$params['id']));
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
}