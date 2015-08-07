<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class permission extends Base 
{
	private $active_top_tag;
	
	public function __construct(){
		parent::__construct();
		$this->active_top_tag = 'enterprise';
		$this->load->model('Permission_model', 'mPermission');
	}
	
	public function index() {
		$data = array();
		//init parameters
		$must = array();
		$fields = array('group_id','page','size');
		$this->checkParams('get',$must,$fields);
		$params = $this->params;
		$group_id = intval($params['group_id']);
		$page = max(1,intval($params['page']));
		$size = max(10,min(100,intval($params['size'])));
		//get permissions data
		$condition = array();
		if( $group_id ){
			$condition['AND'] = array('group_id='.$group_id);
		}
		$res = $this->mPermission->getList('permissions',$condition,'*','id ASC',$page,$size);
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//get permission group
		$data['groupMap'] = array();
		$res = $this->mPermission->getList('permissions_group',array(),'*','id ASC');
		if( $res ){
			foreach ( $res as $row ){
				$data['groupMap'][$row['id']] = $row;
			}
		}
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'permission';
		$this->_view('common/header', $tags);
		$this->_view('permission/permission_list', $data);
		$this->_view('common/footer');
	}
	
	public function add() {
		$data = array();
		//get permission group
		$data['groups'] = $this->mPermission->getList('permissions_group',array(),'*','id ASC');
		//display templates
		$this->_view('permission/permission_add', $data);
	}
	
	public function edit() {
		$data = array();
		//get permission group
		$groups = array();
		$res = $this->mPermission->getList('permissions_group',array(),'*','id ASC');
		if( $res ){
			foreach ( $res as $row ){
				$groups[$row['id']] = $row;
			}
		}
		$data['groups'] = $groups;
		//get single permission
		$id = intval($this->input->get('id'));
		$data['single'] = $this->mPermission->getSingle('permissions','id',$id);
		$group = isset($groups[$data['single']['group_id']]) ? $groups[$data['single']['group_id']] : false;
		if( $group ){
			$data['single']['key'] = str_replace($group['key'].'/', '', $data['single']['key']);
		}
		//display templates
		$this->_view('permission/permission_edit', $data);
	}
	
	public function doAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('group_id','name','key');
			$this->checkParams('post',$must);
			$params = $this->params;
			//check parameters
			$group = $this->mPermission->getSingle('permissions_group','id',$params['group_id']);
			if( !$group ){
				$ret = array('err_no'=>1000,'err_msg'=>'权限组不存在');
				break;
			}
			$params['key'] = strtolower($params['key']);
			if( !preg_match('/^[A-Za-z\_\/]*$/',$params['key']) ){
				$ret = array('err_no'=>1000,'err_msg'=>'权限KEY格式错误');
				break;
			}
			$params['key'] = $group['key'].'/'.$params['key'];
			$t = $this->mPermission->getSingle('permissions','key',$params['key']);
			if( $t ){
				$ret = array('err_no'=>1000,'err_msg'=>'权限KEY已经存在');
				break;
			}
			//insert data
			$data = array(
				'group_id'	=> $params['group_id'],
				'name'		=> $params['name'],
				'key'		=> $params['key'],
			);
			$this->mPermission->insert('permissions',$data);
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function doEdit() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('id','group_id','name','key');
			$this->checkParams('post',$must);
			$params = $this->params;
			//check parameters
			$id = intval($params['id']);
			$single = $this->mPermission->getSingle('permissions','id',$id);
			if( !$single ){
				$ret = array('err_no'=>1000,'err_msg'=>'permission is not exists');
				break;
			}
			$group = $this->mPermission->getSingle('permissions_group','id',$params['group_id']);
			if( !$group ){
				$ret = array('err_no'=>1000,'err_msg'=>'group is not exists');
				break;
			}
			$params['key'] = strtolower($params['key']);
			if( !preg_match('/^[A-Za-z\_\/]*$/',$params['key']) ){
				$ret = array('err_no'=>1000,'err_msg'=>'权限KEY格式错误，仅可以使用3-20位英文字母');
				break;
			}
			$params['key'] = $group['key'].'/'.$params['key'];
			$t = $this->mPermission->getSingle('permissions','key',$params['key']);
			if( $t && $t['id']!=$id ){
				$ret = array('err_no'=>1000,'err_msg'=>'权限KEY已经存在');
				break;
			}
			//update data
			$data = array(
				'group_id'	=> $params['group_id'],
				'name'		=> $params['name'],
				'key'		=> $params['key'],
			);
			$this->mPermission->update('permissions',$data,array('id'=>$id));
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
}
