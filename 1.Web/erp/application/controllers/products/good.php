<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class good extends Base 
{
	private $active_top_tag = 'product';
	
	private $methodMap = array();
	
	private $unitMap = array();
		
	public function __construct(){
		parent::__construct();
		$this->methodMap = getConfig('good_method_types');
		$this->unitMap = getConfig('good_unit_types');
	}

	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('category_id','keyword','page','size');
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		//get categorys
		$condition = array(
			'AND' => array('is_del=0'),
		);
		$res = $this->mBase->getList('goods_category',$condition,'id, father_id, name, sort','sort ASC');

		if(!empty($res)){
			$res = getTreeFromArray($res);
			$data['category_list'] = $res;
		}
		//get goods
		$condition = array(
			'AND' => array('is_del=0'),
		);
		if( $params['category_id'] ){
			$condition['AND'][] = 'category_id='.$params['category_id'];
		}
		if( $params['keyword'] ){
			$condition['AND'][] = "name like '%{$params['keyword']}%'";
		}
		$res = $this->mBase->getList('goods',$condition,'*','id DESC',$page,$size);
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//common data
		$data['methodMap'] = $this->methodMap;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'good';
		$this->_view('common/header', $tags);
		$this->_view('product/good_list', $data);
		$this->_view('common/footer');
	}

	public function add(){
		$data = array();
		//get parameters
		$must = array();
		$fields = array();
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		//get categorys
		$condition = array(
			'AND' => array('is_del=0'),
		);
		$res = $this->mBase->getList('goods_category',$condition,'id, father_id, name, sort','sort ASC');
		if(!empty($res)){
			$res = getTreeFromArray($res);
			$data['category_list'] = $res;
		}
		//common data
		$data['methodMap'] = $this->methodMap;
		$data['unitMap'] = $this->unitMap;
		//display templates
		$this->_view('product/good_add', $data);
	}
	
	public function edit(){
		$data = array();
		//get parameters
		$must = array('id');
		$fields = array();
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$id = intval($params['id']);
		//get good
		$data['single'] = $this->mBase->getSingle('goods','id',$id);
		//get categorys
		$condition = array(
			'AND' => array('is_del=0'),
		);
		$res = $this->mBase->getList('goods_category',$condition,'id, father_id, name, sort','sort ASC');
		if(!empty($res)){
			$res = getTreeFromArray($res);
			$data['category_list'] = $res;
		}
		//common data
		$data['methodMap'] = $this->methodMap;
		$data['unitMap'] = $this->unitMap;
		//display templates
		$this->_view('product/good_edit', $data);
	}
	
	public function doAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('category_id','name','method','amount','unit');
			$fields = array('brand','thumb');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$category_id = intval($params['category_id']);
			$method = intval($params['method']);
			$amount = floatval($params['amount']);
			$unit = $params['unit'];
			//check parameters
			$category = $this->mBase->getSingle('goods_category','id',$category_id);
			if( !$category ){
				$ret = array('err_no'=>1000,'err_msg'=>'分类不存在');
				break;
			}
			//check method
			if( !array_key_exists($method, $this->methodMap) ){
				$ret = array('err_no'=>1000,'err_msg'=>'计价方式不存在');
				break;
			}
			//check unit
			if( !in_array($unit, $this->unitMap[$method]) ){
				$ret = array('err_no'=>1000,'err_msg'=>'单位不存在');
				break;
			}
			//insert data
			$data = array(
				'category_id'	=> $params['category_id'],
				'brand'			=> $params['brand'],
				'name'			=> $params['name'],
				'method'		=> $method,
				'amount'		=> $amount,
				'unit'			=> $unit,
				'thumb'			=> $params['thumb'],
			);
			$this->mBase->insert('goods',$data);
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function doEdit() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('id','category_id','name','method','amount','unit');
			$fields = array('brand','thumb');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			//check parameters
			$id = intval($params['id']);
			$category_id = intval($params['category_id']);
			$method = intval($params['method']);
			$amount = floatval($params['amount']);
			$unit = $params['unit'];
			//check parameters
			$good = $this->mBase->getSingle('goods','id',$id);
			if( !$good ){
				$ret = array('err_no'=>1000,'err_msg'=>'货物信息不存在');
				break;
			}
			$category = $this->mBase->getSingle('goods_category','id',$category_id);
			if( !$category ){
				$ret = array('err_no'=>1000,'err_msg'=>'分类已经存在');
				break;
			}
			//check method
			if( !array_key_exists($method, $this->methodMap) ){
				$ret = array('err_no'=>1000,'err_msg'=>'计价方式不存在');
				break;
			}
			//check unit
			if( !in_array($unit, $this->unitMap[$method]) ){
				$ret = array('err_no'=>1000,'err_msg'=>'单位不存在');
				break;
			}
			//insert data
			$data = array(
				'category_id'	=> $params['category_id'],
				'brand'			=> $params['brand'],
				'name'			=> $params['name'],
				'method'		=> $method,
				'amount'		=> $amount,
				'unit'			=> $unit,
				'thumb'			=> $params['thumb'],
			);
			$this->mBase->update('goods',$data,array('id'=>$id));
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}

	public function delete(){
		$good_id = $this->input->get('good_id');
		if(empty($good_id)){
			die('{"error":1, "msg": "信息错误，请刷新页面重试！"}');
		}
		$delete_rst = $this->mBase->update('goods', array('is_del'=>1),array('id' => $good_id));
		die('{"error":0, "msg": ""}');
	}
	
	public function single_select_dialog(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('category_id','keyword','page','size');
		$this->checkParams('get',$must,$fields);
		$data['params'] = $params = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(10,min(100,intval($params['size'])));
		
		//get current site's category
		$data['category_list'] = array();
		$condition = array(
			'AND' => array('is_del=0'),
		);
		$res = $this->mBase->getList('goods_category',$condition,'id, father_id, name, sort');
		if( $res ){
			foreach ( $res as $row ){
				$data['category_map'][$row['id']] = $row;
			}
		}
		if(is_array($res) && count($res) > 0){
			$data['category_list'] = getTreeFromArray($res);
		}
		//get goods
		$condition = array(
			'AND' => array('is_del=0'),
		);
		if( $params['category_id'] ){
			$condition['AND'][] = 'category_id='.$params['category_id'];
		}
		if( $params['keyword'] ){
			$condition['AND'][] = "name like '%{$params['keyword']}%'";
		}
		$res = $this->mBase->getList('goods',$condition,'*','id DESC',$page,$size);
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//common data
		$data['methodMap'] = $this->methodMap;
		//display templates
		$this->_view('product/good_single_select', $data);
	}

	public function muti_select_dialog()
	{
		$data = array();
		//init parameters
		$must = array();
		$fields = array('category_id','keyword','page','size');
		$this->checkParams('get',$must,$fields);
		$data['params'] = $params = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(10,min(100,intval($params['size'])));
		//get current site's category
		$data['category_list'] = array();
		$condition = array(
			'AND' => array('is_del=0'),
		);
		$res = $this->mBase->getList('goods_category',$condition,'id, father_id, name, sort');
		if( $res ){
			foreach ( $res as $row ){
				$data['category_map'][$row['id']] = $row;
			}
		}
		if(is_array($res) && count($res) > 0){
			$data['category_list'] = getTreeFromArray($res);
		}
		//get goods
		$condition = array(
			'AND' => array('is_del=0'),
		);
		if( $params['category_id'] ){
			$condition['AND'][] = 'category_id='.$params['category_id'];
		}
		if( $params['keyword'] ){
			$condition['AND'][] = "name like '%{$params['keyword']}%'";
		}
		$res = $this->mBase->getList('goods',$condition,'*','id DESC',$page,$size);
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//common data
		$data['methodMap'] = $this->methodMap;
		//display templates
		$this->_view('product/good_muti_select', $data);
	}
}
