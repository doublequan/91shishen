<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class spec extends Base 
{
	private $active_top_tag = 'product';
	
	private $typeMap = array(
		1 => '物品规格',
		2 => '包装规格',
	);

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('type','page','size');
		$this->checkParams('get',$must,$fields);
		$type = intval($this->params['type']);
		$this->params['type'] = array_key_exists($type,$this->typeMap) ? $type : current(array_keys($this->typeMap));
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		//get data
		$condition = array(
			'AND' => array('is_del=0','type='.$params['type']),
		);
		$res = $this->mBase->getList('products_spec',$condition,'*','id DESC',$page,$size);
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//common data
		$data['typeMap'] = $this->typeMap;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'spec';
		$this->_view('common/header', $tags);
		$this->_view('product/spec_list', $data);
		$this->_view('common/footer');
	}
	
	public function add(){
		$data = array();
		//common data
		$data['typeMap'] = $this->typeMap;
		//display templates
		$this->_view('product/spec_add', $data);
	}
	
	public function edit(){
		$data = array();
		//get single group
		$id = intval($this->input->get('id'));
		$data['single'] = $this->mBase->getSingle('products_spec','id',$id);
		//common data
		$data['typeMap'] = $this->typeMap;
		//display templates
		$this->_view('product/spec_edit', $data);
	}
	
	public function doAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('type','name');
			$fields = array('unit');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			//check parameters
			if( !array_key_exists($params['type'],$this->typeMap) ){
				$ret = array('err_no'=>1000,'err_msg'=>'所选规格类型不存在！');
				break;
			}
			$condition = array(
				'AND' => array('type='.$params['type'],"name='".$params['name']."'"),
			);
			$t = $this->mBase->getList('products_spec',$condition,'id');
			if( $t ){
				$ret = array('err_no'=>1000,'err_msg'=>'规格名称已经存在！');
				break;
			}
			//insert data
			$data = array(
				'type'			=> $params['type'],
				'name'			=> $params['name'],
				'unit'			=> $params['unit'],
				'create_eid'	=> parent::$user['id'],
				'create_name'	=> parent::$user['username'],
				'create_time'	=> time(),
			);
			$this->mBase->insert('products_spec',$data);
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function doEdit() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('id','name');
			$fields = array('unit');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$id = intval($params['id']);
			//check parameters
			$single = $this->mBase->getSingle('products_spec','id',$id);
			if( !$single ){
				$ret = array('err_no'=>1000,'err_msg'=>'规格信息不存在！');
				break;
			}
			$condition = array(
				'AND' => array('type='.$single['type'],"name='".$params['name']."'"),
			);
			$t = $this->mBase->getList('products_spec',$condition,'id');
			$t = $t ? current($t) : $t;
			if( $t && $t['id']!=$id ){
				$ret = array('err_no'=>1000,'err_msg'=>'规格名称已经存在！');
				break;
			}
			//update data
			$data = array(
				'name'			=> $params['name'],
				'unit'			=> $params['unit'],
				'create_eid'	=> parent::$user['id'],
				'create_name'	=> parent::$user['username'],
				'create_time'	=> time(),
			);
			$this->mBase->update('products_spec',$data,array('id'=>$id));
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
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
			$id = intval($params['id']);
			//check parameters
			$single = $this->mBase->getSingle('products_spec','id',$id);
			if( !$single ){
				$ret = array('err_no'=>1000,'err_msg'=>'规格信息不存在！');
				break;
			}
			//update data
			$data = array('is_del'=>1);
			$this->mBase->update('products_spec',$data,array('id'=>$id));
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
}
