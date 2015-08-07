<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class store extends Base 
{
	private $active_top_tag = 'enterprise';
	
	//Common Sites
	private $sites = array();
	
	//Common Companys
	private $companys = array();
	
	//Stroe Type Map
	private $typeMap = array(
		'is_process'	=> '加工中心',
		'is_sell'		=> '销售门店',
		'is_storage'	=> '仓库',
		'is_pickup'		=> '自提点',
		'is_delivery'	=> '配送点',
	);

	public function __construct(){
		parent::__construct();
		$this->active_top_tag = 'enterprise';
		//get sites
		$condition = array(
			'AND' => array('is_del=0'),
		);
		$res = $this->mBase->getList('sites',$condition);
		if( $res ){
			foreach ( $res as $row ){
				$this->sites[$row['id']] = $row;
			}
		}
		//get companys
		$res = $this->mBase->getList('companys');
		if( $res ){
			foreach ( $res as $row ){
				$this->companys[$row['id']] = $row;
			}
		}
	}
	
	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('site_id','type_key','page','size');
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;

		$site_id = intval($params['site_id']);
		$data['search_site'] = $site_id;
		$type_key = $params['type_key'];
		$data['type_key'] = $type_key;
		
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		//get stores
		$condition = array(
			'AND' => array('is_del=0'),
		);
		if( $site_id ){
			$condition['AND'][] = 'site_id='.$site_id;
		}
		if( $type_key ){
			$condition['AND'][] = "{$type_key}=1";
		}
		$res = $this->mBase->getList('stores',$condition,'*','id DESC',$page,$size);
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		
		$this->load->model('area_model', 'area');
		$province_res = $this->area->getProvinceList();
		foreach ($province_res as $row) {
			$data['province_list'][$row['id']] = $row;
		}
		$city_res = $this->area->getCityList();
		foreach ($city_res as $row) {
			$data['city_list'][$row['id']] = $row;
		}
		// $district_res = $this->area->getAreaList();
		// foreach ($district_res as $row) {
		// 	$data['district_list'][$row['id']] = $row;
		// }

		//common data
		$data['sites'] = $this->sites;
		$data['companys'] = $this->companys;
		$data['typeMap'] = $this->typeMap;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'store';
		$this->_view('common/header', $tags);
		$this->_view('enterprise/store_list', $data);
		$this->_view('common/footer');
	}
	
	public function add() {
		$data = array();
		//common data
		$data['sites'] = $this->sites;
		$data['companys'] = $this->companys;
		$data['typeMap'] = $this->typeMap;

		$this->load->model('area_model', 'area');
		$province_list = $this->area->getProvinceList();
		$data['province_list'] = $province_list;

		//display templates
		$this->_view('enterprise/store_add', $data);
	}
	
	public function edit() {
		$data = array();
		//get single group
		$id = intval($this->input->get('id'));
		$data['single'] = $this->mBase->getSingle('stores','id',$id);

		$manager = $this->mBase->getSingle('employees', 'id', $data['single']['manager']);
		$data['manager'] = $manager;

		$this->load->model('area_model', 'area');
		$data['province_list'] = $this->area->getProvinceList();
		$data['city_list'] = $this->area->getCityListByProvince($data['single']['prov']);
		$data['district_list'] = $this->area->getAreaListByCity($data['single']['city']);

		//common data
		$data['sites'] = $this->sites;
		$data['companys'] = $this->companys;
		$data['typeMap'] = $this->typeMap;
		//display templates
		$this->_view('enterprise/store_edit', $data);
	}
	
	public function doAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('site_id','company_id','name','prov','city','district','address','tel','manager');
			$fields = array('loc','is_process','is_sell','is_storage','is_pickup','is_delivery','open_time');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$site_id = intval($params['site_id']);
			$company_id = intval($params['company_id']);
			//check parameters
			if( !isset($this->sites[$site_id]) ){
				$ret = array('err_no'=>1000,'err_msg'=>'所选网站不存在');
				break;
			}
			if( !isset($this->companys[$company_id]) ){
				$ret = array('err_no'=>1000,'err_msg'=>'所选公司不存在');
				break;
			}
			//insert data
			$data = array(
				'site_id'		=> $params['site_id'],
				'company_id'	=> $params['company_id'],
				'name'			=> $params['name'],
				'prov'			=> $params['prov'],
				'city'			=> $params['city'],
				'district'		=> $params['district'],
				'address'		=> $params['address'],
				'tel'			=> $params['tel'],
				'manager'		=> $params['manager'],
				'loc'			=> $params['loc'],
				'is_process'	=> intval($params['is_process']) ? 1 : 0,
				'is_sell'		=> intval($params['is_sell']) ? 1 : 0,
				'is_storage'	=> intval($params['is_storage']) ? 1 : 0,
				'is_pickup'		=> intval($params['is_pickup']) ? 1 : 0,
				'is_delivery'	=> intval($params['is_delivery']) ? 1 : 0,
				'open_time'		=> $params['open_time'],
				'create_time'	=> time(),
			);
			$this->mBase->insert('stores',$data);
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function doEdit() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('id','site_id','company_id','name','prov','city','district','address','tel','manager');
			$fields = array('loc','is_process','is_sell','is_storage','is_pickup','is_delivery','open_time');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$id = intval($params['id']);
			$site_id = intval($params['site_id']);
			$company_id = intval($params['company_id']);
			//check parameters
			$store = $this->mBase->getSingle('stores','id',$id);
			if( !$store ){
				$ret = array('err_no'=>1000,'err_msg'=>'门店不存在');
				break;
			}
			if( !isset($this->sites[$site_id]) ){
				$ret = array('err_no'=>1000,'err_msg'=>'所选网站不存在');
				break;
			}
			if( !isset($this->companys[$company_id]) ){
				$ret = array('err_no'=>1000,'err_msg'=>'所选公司不存在');
				break;
			}
			//update data
			$data = array(
				'site_id'		=> $params['site_id'],
				'company_id'	=> $params['company_id'],
				'name'			=> $params['name'],
				'prov'			=> $params['prov'],
				'city'			=> $params['city'],
				'district'		=> $params['district'],
				'address'		=> $params['address'],
				'tel'			=> $params['tel'],
				'manager'		=> $params['manager'],
				'loc'			=> $params['loc'],
				'is_process'	=> intval($params['is_process']) ? 1 : 0,
				'is_sell'		=> intval($params['is_sell']) ? 1 : 0,
				'is_storage'	=> intval($params['is_storage']) ? 1 : 0,
				'is_pickup'		=> intval($params['is_pickup']) ? 1 : 0,
				'is_delivery'	=> intval($params['is_delivery']) ? 1 : 0,
				'open_time'		=> $params['open_time'],
				'create_time'	=> time(),
			);
			$this->mBase->update('stores',$data,array('id'=>$id));
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}

	public function delete()
	{
		$row_id = $this->input->get('row_id');
		if(empty($row_id)){
			die('{"error":1, "msg": "信息错误，请刷新页面重试！"}');
		}
		$delete_rst = $this->mBase->update('stores', array('is_del'=>1),array('id' => $row_id));
		die('{"error":0, "msg": ""}');
	}

	public function getStoreByCity(){
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		$city_id = $this->input->get('city_id');
		if(empty($city_id))
			$this->output($ret);

		$condition = array(
			'AND' => array('is_del=0'),
		);
		$condition['AND'][] = 'city='.$city_id;
		
		$res = $this->mBase->getList('stores',$condition,'*','id DESC');
		if(!empty($res) && is_array($res)){
			$ret['store_list'] = $res;
			$this->output($ret);
		}
		$this->output($ret);
	}
}
