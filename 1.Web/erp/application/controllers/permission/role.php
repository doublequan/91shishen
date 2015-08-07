<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class role extends Base 
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
		$fields = array();
		$this->checkParams('get',$must,$fields);
		$params = $this->params;
		//get permissions data
		$condition = array();
		$data['results'] = $this->mPermission->getList('roles',$condition,'*','id ASC');
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'permission_role';
		$this->_view('common/header', $tags);
		$this->_view('permission/role_list', $data);
		$this->_view('common/footer');
	}
	
	public function add() {
		$data = array();
		//get permission groups
		$data['groupMap'] = array();
		$res = $this->mPermission->getList('permissions_group',array(),'*','id ASC');
		if( $res ){
			foreach ( $res as $row ){
				$data['groupMap'][$row['id']] = $row;
			}
		}
		//get permissions
		$data['permissions'] = array();
		$res = $this->mPermission->getList('permissions');
		if( $res ){
			foreach ( $res as $row ){
				$data['permissions'][$row['group_id']][] = $row;
			}
		}
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'permission_role';
		$this->_view('common/header', $tags);
		$this->_view('permission/role_add', $data);
		$this->_view('common/footer');
	}
	
	public function edit() {
		$data = array();
		//get single permission
		$data['single'] = array();
		$id = intval($this->input->get('id'));
		$data['single'] = $this->mPermission->getSingle('roles','id',$id);
		$data['curr_permissions'] = array();
		if( isset($data['single']['permissions']) ){
			$data['curr_permissions'] = json_decode($data['single']['permissions'],true);
		}
		//get permission groups
		$data['groupMap'] = array();
		$res = $this->mPermission->getList('permissions_group',array(),'*','id ASC');
		if( $res ){
			foreach ( $res as $row ){
				$data['groupMap'][$row['id']] = $row;
			}
		}
		//get permissions
		$data['permissions'] = array();
		$res = $this->mPermission->getList('permissions');
		if( $res ){
			foreach ( $res as $row ){
				$data['permissions'][$row['group_id']][] = $row;
			}
		}
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'permission_role';
		$this->_view('common/header', $tags);
		$this->_view('permission/role_edit', $data);
		$this->_view('common/footer');
	}
	
	public function doAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('name','permission_ids');
			$this->checkParams('post',$must);
			$params = $this->params;
			//check parameters
			$t = $this->mPermission->getSingle('roles','name',$params['name']);
			if( $t ){
				$ret = array('err_no'=>1000,'err_msg'=>'角色名称已经存在');
				break;
			}
			$permission_ids = $params['permission_ids'];
			if( !$permission_ids ){
				$ret = array('err_no'=>1000,'err_msg'=>'角色对应的权限不能为空');
				break;
			}
			$condition = array(
				'AND' => array('id IN ('.implode(',', $permission_ids).')'),
			);
			$res = $this->mPermission->getList('permissions',$condition);
			if( !$res ){
				$ret = array('err_no'=>1000,'err_msg'=>'选择的权限全部不存在');
				break;
			}
			$permissions = array();
			foreach( $res as $row ){
				$permissions[$row['id']] = $row['key'];
			}
			//insert data
			$data = array(
				'name'			=> $params['name'],
				'permissions'	=> json_encode($permissions),
			);
			$this->mPermission->insert('roles',$data);
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function doEdit() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('id','name','permission_ids');
			$this->checkParams('post',$must);
			$params = $this->params;
			//check parameters
			$id = intval($params['id']);
			$single = $this->mPermission->getSingle('roles','id',$id);
			if( !$single ){
				$ret = array('err_no'=>1000,'err_msg'=>'角色不存在');
				break;
			}
			$t = $this->mPermission->getSingle('roles','name',$params['name']);
			if( $t && $t['id']!=$id ){
				$ret = array('err_no'=>1000,'err_msg'=>'角色名称已经存在');
				break;
			}
			$permission_ids = $params['permission_ids'];
			if( !$permission_ids ){
				$ret = array('err_no'=>1000,'err_msg'=>'角色对应的权限不能为空');
				break;
			}
			$condition = array(
				'AND' => array('id IN ('.implode(',', $permission_ids).')'),
			);
			$res = $this->mPermission->getList('permissions',$condition);
			if( !$res ){
				$ret = array('err_no'=>1000,'err_msg'=>'选择的权限全部不存在');
				break;
			}
			$permissions = array();
			foreach( $res as $row ){
				$permissions[$row['id']] = $row['key'];
			}
			//update data
			$data = array(
				'name'			=> $params['name'],
				'permissions'	=> json_encode($permissions),
			);
			$this->mPermission->update('roles',$data,array('id'=>$id));
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
}
