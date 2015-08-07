<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class loss_type extends Base 
{
	public function __construct(){
		parent::__construct();
		$this->active_top_tag = 'product';
	}
	
	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array();
		$this->checkParams('get',$must,$fields);
		$params = $this->params;
		//get stores
		$condition = array(
			'AND' => array('is_del=0'),
		);
		$data['results'] = $this->mBase->getList('loss_type',$condition,'*','id DESC');
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'loss_type';
		$this->_view('common/header', $tags);
		$this->_view('product/loss_type_list', $data);
		$this->_view('common/footer');
	}
	
	public function edit() {
		$data = array();
		//get single group
		$id = intval($this->input->get('id'));
		$data['single'] = $this->mBase->getSingle('loss_type','id',$id);
		//display templates
		$this->_view('products/loss_type_edit', $data);
	}
	
	public function doAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('name');
			$fields = array();
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$name = $params['name'];
			//check parameters
			$t = $this->mBase->getSingle('loss_type','name',$name);
			if( $t ){
				$ret = array('err_no'=>1000,'err_msg'=>'损耗类型名称已经存在');
				break;
			}
			//insert data
			$data = array(
				'name' => $params['name'],
			);
			$this->mBase->insert('loss_type',$data);
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function doEdit() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('id','name');
			$fields = array();
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$id = intval($params['id']);
			$name = $params['name'];
			//check parameters
			$type = $this->mBase->getSingle('loss_type','id',$id);
			if( !$type ){
				$ret = array('err_no'=>1000,'err_msg'=>'损耗类型不存在');
				break;
			}
			if( empty($name) ){
				$ret = array('err_no'=>1000,'err_msg'=>'type name is empty');
				break;
			}
			$t = $this->mBase->getSingle('loss_type','name',$name);
			if( $t && $t['id']!=$id ){
				$ret = array('err_no'=>1000,'err_msg'=>'损耗类型名称已经存在');
				break;
			}
			//update data
			$data = array(
				'name' => $params['name'],
			);
			$this->mBase->update('loss_type',$data,array('id'=>$id));
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
}
