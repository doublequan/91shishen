<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class group extends Base 
{
	private $active_top_tag = 'user';

	public function __construct(){
		parent::__construct();
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
		//get groups
		$condition = array(
			'AND' => array('is_del=0'),
		);
		$res = $this->mBase->getList('users_group',$condition,'*','id DESC',$page,$size);
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'group';
		$this->_view('common/header', $tags);
		$this->_view('user/group_list', $data);
		$this->_view('common/footer');
	}
	
	public function add(){
		$data = array();
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'group_add';
		$this->_view('user/group_add', $data);
	}
	
	public function edit(){
		$data = array();
		//get parameters
		$must = array('id');
		$fields = array();
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$id = intval($params['id']);
		//get product
		$data['single'] = $this->mBase->getSingle('users_group','id',$id);
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'group_edit';
		$this->_view('user/group_edit', $data);
	}

	public function doAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
        do{
        	//get parameters
        	$must = array('name','discount','min','max');
        	$fields = array();
        	$this->checkParams('post',$must,$fields);
        	$params = $this->params;
        	//insert group
        	$data = array(
        		'name'		=> $params['name'],
        		'discount'	=> max(1,min(100,$params['discount'])),
        		'min'		=> $params['min'],
        		'max'		=> $params['max'],
        	);
            $user = $this->mBase->insert('users_group',$data);
            $ret = array('err_no'=>0,'err_msg'=>'操作成功');
        } while(0);
        $this->output($ret);
	}
	
	public function doEdit() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
    	do{
    		//get parameters
    		$must = array('id','name','discount','min','max');
        	$fields = array();
        	$this->checkParams('post',$must,$fields);
    		$params = $this->params;
    		//update group
    		$data = array(
    			'name'		=> $params['name'],
        		'discount'	=> max(1,min(100,$params['discount'])),
        		'min'		=> $params['min'],
        		'max'		=> $params['max'],
    		);
    		$this->mBase->update('users_group',$data,array('id'=>$params['id']));
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
    		$data = array(
    			'is_del' => 1,
    		);
    		$this->mBase->update('users_group',$data,array('id'=>$params['id']));
    		$ret = array('err_no'=>0,'err_msg'=>'操作成功');
    	} while(0);
    	$this->output($ret);
	}
}
