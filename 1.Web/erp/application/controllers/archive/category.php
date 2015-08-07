<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class category extends Base {
	
	private $active_top_tag = 'archive';

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
		//get users
		$condition = array(
			'AND' => array('is_del=0'),
		);
		$data['results'] = $this->mBase->getList('archives_category',$condition,'*','sort ASC');
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'category';
		$this->_view('common/header', $tags);
		$this->_view('archive/category_list', $data);
		$this->_view('common/footer');
    }
    
    public function add(){
    	$data = array();
		//display templates
		$this->_view('archive/category_add', $data);
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
		$data['single'] = $this->mBase->getSingle('archives_category','id',$id);
		//display templates
		$this->_view('archive/category_edit', $data);
    }
    
    public function doAdd(){
    	$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('name','alias');
			$fields = array('seo_title','seo_keywords','seo_description','sort');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			//check parameters
			$t = $this->mBase->getSingle('archives_category','name',$params['name']);
			if( $t ){
				$ret = array('err_no'=>1000,'err_msg'=>'分类名称已经存在');
				break;
			}
			$t = $this->mBase->getSingle('archives_category','alias',$params['alias']);
			if( $t ){
				$ret = array('err_no'=>1000,'err_msg'=>'分类别名已经存在');
				break;
			}
			//insert data
			$data = array(
				'name'				=> $params['name'],
				'alias'				=> $params['alias'],
				'seo_title'			=> $params['seo_title'],
				'seo_keywords'		=> $params['seo_keywords'],
				'seo_description'	=> $params['seo_description'],
				'sort'				=> $params['sort'],
				'create_eid'		=> self::$user['id'],
				'create_name'		=> self::$user['username'],
				'create_time'		=> time(),
			);
			$this->mBase->insert('archives_category',$data);
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
    }
    
    public function doEdit(){
    	$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('id','name','alias');
			$fields = array('seo_title','seo_keywords','seo_description','sort');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$id = intval($params['id']);
			//check parameters
			$single = $this->mBase->getSingle('archives_category','id',$id);
			if( !$single ){
				$ret = array('err_no'=>1000,'err_msg'=>'您要编辑的分类不存在');
				break;
			}
			if( $params['name']!=$single['name'] ){
				$t = $this->mBase->getSingle('archives_category','name',$params['name']);
				if( $t ){
					$ret = array('err_no'=>1000,'err_msg'=>'分类名称已经存在');
					break;
				}
			}
			if( $params['alias']!=$single['alias'] ){
				$t = $this->mBase->getSingle('archives_category','alias',$params['alias']);
				if( $t ){
					$ret = array('err_no'=>1000,'err_msg'=>'分类别名已经存在');
					break;
				}
			}
			//update data
			$data = array(
				'name'				=> $params['name'],
				'alias'				=> $params['alias'],
				'seo_title'			=> $params['seo_title'],
				'seo_keywords'		=> $params['seo_keywords'],
				'seo_description'	=> $params['seo_description'],
				'sort'				=> $params['sort'],
			);
			$this->mBase->update('archives_category',$data,array('id'=>$params['id']));
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
    		//update category
    		$data = array(
    			'is_del' => 1,
    		);
    		$this->mBase->update('archives_category',$data,array('id'=>$params['id']));
    		$ret = array('err_no'=>0,'err_msg'=>'操作成功');
    	} while(0);
    	$this->output($ret);
    }
}