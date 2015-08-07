<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class group extends Base 
{
	private $active_top_tag;
	
	public function __construct(){
		parent::__construct();
		$this->active_top_tag = 'enterprise';
		$this->load->model('Permission_model', 'mPermission');
	}
	
	public function index() {
		$data = array();
		//get permission group
		$data['results'] = $this->mPermission->getList('permissions_group',array(),'*','id ASC');
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'permission_group';
		$this->_view('common/header', $tags);
		$this->_view('permission/group_list', $data);
		$this->_view('common/footer');
	}
	
	public function add() {
		$data = array();
		$this->_view('permission/group_add', $data);
	}
	
	public function edit() {
		$data = array();
		//get single group
		$data['single'] = array();
		$id = intval($this->input->get('id'));
		$data['single'] = $this->mPermission->getSingle('permissions_group','id',$id);
		$this->_view('permission/group_edit', $data);
	}
	
	public function doAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('name','key');
			$this->checkParams('post',$must);
			$params = $this->params;
			//check parameters
			$params['key'] = strtolower($params['key']);
			if( !preg_match('/^[a-z]{3,20}$/',$params['key']) ){
				$ret = array('err_no'=>1000,'err_msg'=>'权限组KEY格式错误，仅可以使用3-20位英文字母');
				break;
			}
			$t = $this->mPermission->getSingle('permissions_group','key',$params['key']);
			if( $t ){
				$ret = array('err_no'=>1000,'err_msg'=>'权限组KEY已经存在');
				break;
			}
			$t = $this->mPermission->getSingle('permissions_group','name',$params['name']);
			if( $t ){
				$ret = array('err_no'=>1000,'err_msg'=>'权限组名称已经存在');
				break;
			}
			//insert data
			$data = array(
				'name'	=> $params['name'],
				'key'	=> $params['key'],
			);
			$this->mPermission->insert('permissions_group',$data);
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function doEdit() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('id','name','key');
			$this->checkParams('post',$must);
			$params = $this->params;
			//check parameters
			$params['key'] = strtolower($params['key']);
			if( !preg_match('/^[a-z]{3,20}$/',$params['key']) ){
				$ret = array('err_no'=>1000,'err_msg'=>'权限组KEY格式错误，仅可以使用3-20位英文字母');
				break;
			}
			$id = intval($params['id']);
			$single = $this->mPermission->getSingle('permissions_group','id',$id);
			if( !$single ){
				$ret = array('err_no'=>1000,'err_msg'=>'权限组不存在');
				break;
			}
			$t = $this->mPermission->getSingle('permissions_group','name',$params['name']);
			if( $t && $t['id']!=$id ){
				$ret = array('err_no'=>1000,'err_msg'=>'权限组名称已经存在');
				break;
			}
			$t = $this->mPermission->getSingle('permissions_group','key',$params['key']);
			if( $t && $t['id']!=$id ){
				$ret = array('err_no'=>1000,'err_msg'=>'权限组KEY已经存在');
				break;
			}
			//update data
			$data = array(
				'name'	=> $params['name'],
				'key'	=> $params['key'],
			);
			$this->mPermission->update('permissions_group',$data,array('id'=>$id));
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
}
