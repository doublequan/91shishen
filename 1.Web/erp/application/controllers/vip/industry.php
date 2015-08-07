<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class industry extends Base 
{
	private $active_top_tag = 'vip';

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array();
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$data['results'] = $this->mBase->getList('vip_industrys');
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'industry';
		$this->_view('common/header', $tags);
		$this->_view('vip/industry_list', $data);
		$this->_view('common/footer');
	}
	
	public function add(){
		$data = array();
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'industry_add';
		$this->_view('vip/industry_add', $data);
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
		$data['single'] = $this->mBase->getSingle('vip_industrys','id',$id);
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'industry_edit';
		$this->_view('user/industry_edit', $data);
	}
	
	public function doAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
        do{
        	//get parameters
        	$must = array('name');
        	$fields = array();
        	$this->checkParams('post',$must,$fields);
        	$params = $this->params;
        	//check parameters
        	$t = $this->mBase->getSingle('vip_industrys','name',$params['name']);
        	if( $t ){
        		$ret = array('err_no'=>1000,'err_msg'=>'industry name is exists');
        		break;
        	}
        	//insert data
        	$data = array(
        		'name' => $params['name'],
        	);
            $user = $this->mBase->insert('vip_industrys',$data);
            if( !$user ){
                $ret = array('err_no'=>1000,'err_msg'=>'add vip industry failure');
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
        	$must = array('id','name');
        	$fields = array();
        	$this->checkParams('post',$must,$fields);
        	$params = $this->params;
        	$id = intval($params['id']);
        	//check parameters
        	$single = $this->mBase->getSingle('vip_industrys','id',$id);
        	if( !$single ){
        		$ret = array('err_no'=>1000,'err_msg'=>'vip industry is not exists');
        		break;
        	}
        	$t = $this->mBase->getSingle('vip_industrys','name',$params['name']);
        	if( $t && $t['id']!=$id ){
        		$ret = array('err_no'=>1000,'err_msg'=>'industry name is exists');
        		break;
        	}
        	$data = array(
        		'name'			=> $params['name'],
        	);
    		$this->mBase->update('vip_industrys',$data,array('id'=>$params['id']));
    		$ret = array('err_no'=>0,'err_msg'=>'操作成功');
    	} while(0);
    	$this->output($ret);
	}
}
