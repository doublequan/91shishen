<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class category extends Base 
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
		$params = $this->params;
		//get categorys
		$condition = array(
			'AND' => array('is_del=0'),
		);
		$data['results'] = $this->mBase->getList('vip_category',$condition,'*','sort ASC');
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'category';
		$this->_view('common/header', $tags);
		$this->_view('vip/category_list', $data);
		$this->_view('common/footer');
	}
	
	public function add() {
		$data = array();
		//display templates
		$this->_view('vip/category_add', $data);
	}
	
	public function edit() {
		$data = array();
		//get single group
		$id = intval($this->input->get('id'));
		$data['single'] = $this->mBase->getSingle('vip_category','id',$id);
		//display templates
		$this->_view('vip/category_edit', $data);
	}
	
	public function doAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('name','thumb');
			$fields = array('seo_title','seo_keywords','seo_description','sort');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			//check parameters
			$t = $this->mBase->getSingle('vip_category','name',$params['name']);
			if( $t ){
				$ret = array('err_no'=>1000,'err_msg'=>'category name is exists');
				break;
			}
			//insert data
			$data = array(
				'name'				=> $params['name'],
				'thumb'				=> $params['thumb'],
				'seo_title'			=> $params['seo_title'],
				'seo_keywords'		=> $params['seo_keywords'],
				'seo_description'	=> $params['seo_description'],
				'sort'				=> max(1,min(100,$params['sort'])),
			);
			$this->mBase->insert('vip_category',$data);
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function doEdit() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('id','name','thumb');
			$fields = array('seo_title','seo_keywords','seo_description','sort');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$id = intval($params['id']);
			//check parameters
			$category = $this->mBase->getSingle('vip_category','id',$id);
			if( !$category ){
				$ret = array('err_no'=>1000,'err_msg'=>'分类已经存在');
				break;
			}
			$t = $this->mBase->getSingle('vip_category','name',$params['name']);
			if( $t && $t['id']!=$id ){
				$ret = array('err_no'=>1000,'err_msg'=>'category name is exists');
				break;
			}
			//update data
			$data = array(
				'name'				=> $params['name'],
				'thumb'				=> $params['thumb'],
				'seo_title'			=> $params['seo_title'],
				'seo_keywords'		=> $params['seo_keywords'],
				'seo_description'	=> $params['seo_description'],
				'sort'				=> $params['sort'],
			);
			$this->mBase->update('vip_category',$data,array('id'=>$id));
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}

	public function updateSort(){
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
    	do{
        	//get parameters
        	$must = array('category_id','category_sort');
        	$fields = array();
        	$this->checkParams('post',$must,$fields);
        	$params = $this->params;
        	$category_id = intval($params['category_id']);
        	//check parameters
        	$single = $this->mBase->getSingle('vip_category','id',$category_id);
        	if( !$single ){
        		$ret = array('err_no'=>1000,'err_msg'=>'good category is not exists');
        		break;
        	}
        	
        	$data = array(
        		'sort' => intval($params['category_sort']),
        	);
    		$this->mBase->update('vip_category',$data,array('id'=>$category_id));
    		$ret = array('err_no'=>0,'err_msg'=>'操作成功');
    	} while(0);
    	$this->output($ret);
	}
}
