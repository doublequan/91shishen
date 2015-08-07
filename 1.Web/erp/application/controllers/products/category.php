<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class category extends Base 
{
	private $active_top_tag = 'product';

	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('site_id');
		$this->checkParams('get',$must,$fields);
		$params = $this->params;

		//get categorys
		$condition = array(
			'AND' => array('is_del=0'),
		);
		$res = $this->mBase->getList('goods_category',$condition,'*','sort ASC');
		if(!empty($res)){
			$res = getTreeFromArray($res);
			$data['results'] = $res;
		}
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'category';
		$this->_view('common/header', $tags);
		$this->_view('product/category_list', $data);
		$this->_view('common/footer');
	}
	
	public function add() {
		$data = array();
		//get categorys
		$condition = array(
			'AND' => array('is_del=0'),
		);
		$res = $this->mBase->getList('goods_category',$condition,'id, father_id, name, sort','sort ASC');
		if(!empty($res)){
			$res = getTreeFromArray($res);
			$data['category_list'] = $res;
		}

		//display templates
		$this->_view('product/category_add', $data);
	}
	
	public function edit() {
		$data = array();
		//get single group
		$data['single'] = array();
		$id = intval($this->input->get('id'));
		$data['single'] = $this->mBase->getSingle('goods_category','id',$id);

		//get categorys
		$condition = array(
			'AND' => array('is_del=0'),
		);
		$res = $this->mBase->getList('goods_category',$condition,'id, site_id, father_id, name, sort','sort ASC');

		if(!empty($res)){
			$res = getTreeFromArray($res);
			$data['category_list'] = $res;
		}

		//display templates
		$this->_view('product/category_edit', $data);
	}
	
	public function doAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('father_id','name','type');
			$fields = array('thumb_web','thumb_wap','thumb_app','seo_title','seo_keywords','seo_description','sort');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$father_id = intval($params['father_id']);
			//check parameters
			if( $father_id ){
				$t = $this->mBase->getSingle('goods_category','id',$father_id);
				if( !$t ){
	        		$ret = array('err_no'=>1000,'err_msg'=>'上级分类不存在');
	        		break;
	        	}
			}
			$t = $this->mBase->getSingle('goods_category','name',$params['name']);
			if( $t ){
				$ret = array('err_no'=>1000,'err_msg'=>'分类名称已经存在');
				break;
			}
			//insert data
			$data = array(
				'father_id'			=> $father_id,
				'name'				=> $params['name'],
                'type'         		=> $params['type'],
				'thumb_web'			=> $params['thumb_web'],
				'thumb_wap'			=> $params['thumb_wap'],
				'thumb_app'			=> $params['thumb_app'],
				'seo_title'			=> $params['seo_title'],
				'seo_keywords'		=> $params['seo_keywords'],
				'seo_description'	=> $params['seo_description'],
				'sort'				=> $params['sort'],
			);
			$this->mBase->insert('goods_category',$data);
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function doEdit() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('id','father_id','name');
			$fields = array('thumb_web','thumb_wap','type','thumb_app','seo_title','seo_keywords','seo_description','sort');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$father_id = intval($params['father_id']);
			$id = intval($params['id']);
			//check parameters
			$category = $this->mBase->getSingle('goods_category','id',$id);
			if( !$category ){
				$ret = array('err_no'=>1000,'err_msg'=>'分类已经存在');
				break;
			}
			if( $father_id ){
				$t = $this->mBase->getSingle('goods_category','id',$father_id);
				if( !$t ){
					$ret = array('err_no'=>1000,'err_msg'=>'上级分类不存在');
					break;
				}
			}
			$t = $this->mBase->getSingle('goods_category','name',$params['name']);
			if( $t && $t['id']!=$id ){
				$ret = array('err_no'=>1000,'err_msg'=>'分类名称已经存在');
				break;
			}
			//update data
			$data = array(
				'father_id'			=> $father_id,
				'name'				=> $params['name'],
                'type'         => $params['type'],
				'thumb_web'			=> $params['thumb_web'],
				'thumb_wap'			=> $params['thumb_wap'],
				'thumb_app'			=> $params['thumb_app'],
				'seo_title'			=> $params['seo_title'],
				'seo_keywords'		=> $params['seo_keywords'],
				'seo_description'	=> $params['seo_description'],
				'sort'				=> $params['sort'],
			);
			$this->mBase->update('goods_category',$data,array('id'=>$id));
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}

	public function delete()
	{
		$category_id = $this->input->get('category_id');
		if(empty($category_id)){
			die('{"error":1, "msg": "信息错误，请刷新页面重试！"}');
		}
		$delete_rst = $this->mBase->update('goods_category', array('is_del'=>1),array('id' => $category_id));
		die('{"error":0, "msg": ""}');
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
        	$single = $this->mBase->getSingle('goods_category','id',$category_id);
        	if( !$single ){
        		$ret = array('err_no'=>1000,'err_msg'=>'good category is not exists');
        		break;
        	}
        	
        	$data = array(
        		'sort' => intval($params['category_sort']),
        	);
    		$this->mBase->update('goods_category',$data,array('id'=>$category_id));
    		$ret = array('err_no'=>0,'err_msg'=>'操作成功');
    	} while(0);
    	$this->output($ret);
	}
}
