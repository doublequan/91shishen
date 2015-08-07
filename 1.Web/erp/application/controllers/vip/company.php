<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class company extends Base 
{
	private $active_top_tag = 'vip';
	private $scaleMap;

	//Common Industrys
	public $industrys = array();

	public function __construct(){
		parent::__construct();
		$res = $this->mBase->getList('vip_industrys');
		if( $res ){
			foreach ( $res as $row ){
				$this->industrys[$row['id']] = $row;
			}
		}
		$this->scaleMap = array(
			1 => '1-99人',
			2 => '100-499人',
			3 => '500-499人',
			4 => '1000-2499人',
			5 => '2500-9999人',
			6 => '10000人以上',
		);
	}

	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('industry_id','keyword','page','size');
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$params['industry_id'] = intval($params['industry_id']);
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		//get users
		$condition = array();
		if( $params['industry_id'] ){
			$condition['AND'][] = 'industry_id='.$params['industry_id'];
		}
		$k = $params['keyword'];
		if( $k ){
			$condition['AND'][] = "name LIKE '%".$k."%'";
		}
		$res = $this->mBase->getList('vip_companys',$condition,'*','id DESC',$page,$size);
		if( $k && $res->results ){
			foreach( $res->results as &$row ){
				$row['name'] = str_replace($k, '<font color="red">'.$k.'</font>', $row['username']);
			}
			unset($row);
		}
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//Common Data
		$data['industrys'] = $this->industrys;
		$data['scaleMap'] = $this->scaleMap;

		//prov & city
		$this->load->model('area_model', 'area');
		$province_res = $this->area->getProvinceList();
		foreach ($province_res as $row) {
			$data['province_list'][$row['id']] = $row;
		}
		$city_res = $this->area->getCityList();
		foreach ($city_res as $row) {
			$data['city_list'][$row['id']] = $row;
		}

		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'company';
		$this->_view('common/header', $tags);
		$this->_view('vip/company_list', $data);
		$this->_view('common/footer');
	}
	
	public function add(){
		$data = array();
		//Common Data
		$data['industrys'] = $this->industrys;
		$data['scaleMap'] = $this->scaleMap;

		$this->load->model('area_model', 'area');
		$data['province_list'] = $this->area->getProvinceList();

		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'company_add';
		$this->_view('vip/company_add', $data);
	}
	
	public function edit(){
		$data = array();
		//get parameters
		$must = array('id');
		$fields = array();
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$id = intval($params['id']);
		//get single
		$data['single'] = $this->mBase->getSingle('vip_companys','id',$id);
		//Common Data
		$data['industrys'] = $this->industrys;
		$data['scaleMap'] = $this->scaleMap;

		$this->load->model('area_model', 'area');
		$data['province_list'] = $this->area->getProvinceList();
		$data['city_list'] = $this->area->getCityListByProvince($data['single']['prov']);

		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'company_edit';
		$this->_view('vip/company_edit', $data);
	}
	
	public function doAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
        do{
        	//get parameters
        	$must = array('industry_id','name','prov','city','address','tel','scale');
        	$fields = array();
        	$this->checkParams('post',$must,$fields);
        	$params = $this->params;
        	$params['industry_id'] = intval($params['industry_id']);
        	//check parameters
        	if( !array_key_exists($params['industry_id'],$this->industrys) ){
        		$ret = array('err_no'=>1000,'err_msg'=>'industry is not correct');
        		break;
        	}
        	$t = $this->mBase->getSingle('areas','id',$params['prov']);
        	if( !$t ){
        		$ret = array('err_no'=>1000,'err_msg'=>'province is not exists');
        		break;
        	}
        	$t = $this->mBase->getSingle('areas','id',$params['city']);
        	if( !$t ){
        		$ret = array('err_no'=>1000,'err_msg'=>'city is not exists');
        		break;
        	}
        	$ip = getUserIP();
        	$time = time();
        	$salt = getRandStr(10);
        	$data = array(
        		'industry_id'	=> $params['industry_id'],
        		'name'			=> $params['name'],
        		'prov'			=> $params['prov'],
        		'city'			=> $params['city'],
        		'address'		=> $params['address'],
        		'tel'			=> $params['tel'],
        		'scale'			=> $params['scale'],
        	);
            $user = $this->mBase->insert('vip_companys',$data);
            if( !$user ){
                $ret = array('err_no'=>1000,'err_msg'=>'add vip company failure');
        		break;
            }
            $ret = array('err_no'=>0,'err_msg'=>'操作成功');
        } while(0);
        $this->output($ret);
	}
	
	public function doEdit() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
    	do{
        	//get parameters
        	$must = array('id','industry_id','name','prov','city','address','tel','scale');
        	$fields = array();
        	$this->checkParams('post',$must,$fields);
        	$params = $this->params;
        	$id = intval($params['id']);
        	$params['industry_id'] = intval($params['industry_id']);
        	//check parameters
        	$single = $this->mBase->getSingle('vip_companys','id',$id);
        	if( !$single ){
        		$ret = array('err_no'=>1000,'err_msg'=>'vip company is not exists');
        		break;
        	}
        	if( !array_key_exists($params['industry_id'],$this->industrys) ){
        		$ret = array('err_no'=>1000,'err_msg'=>'industry is not correct');
        		break;
        	}
        	$t = $this->mBase->getSingle('areas','id',$params['prov']);
        	if( !$t ){
        		$ret = array('err_no'=>1000,'err_msg'=>'province is not exists');
        		break;
        	}
        	$t = $this->mBase->getSingle('areas','id',$params['city']);
        	if( !$t ){
        		$ret = array('err_no'=>1000,'err_msg'=>'city is not exists');
        		break;
        	}
        	$ip = getUserIP();
        	$time = time();
        	$salt = getRandStr(10);
        	$data = array(
        		'industry_id'	=> $params['industry_id'],
        		'name'			=> $params['name'],
        		'prov'			=> $params['prov'],
        		'city'			=> $params['city'],
        		'address'		=> $params['address'],
        		'tel'			=> $params['tel'],
        		'scale'			=> $params['scale'],
        	);
    		$this->mBase->update('vip_companys',$data,array('id'=>$params['id']));
    		$ret = array('err_no'=>0,'err_msg'=>'操作成功');
    	} while(0);
    	$this->output($ret);
	}
}
