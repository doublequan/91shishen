<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class archive extends Base {
	
	private $active_top_tag = 'archive';
	
	//Common Category
	private $categorys = array();

	public function __construct(){
		parent::__construct();
		$res = $this->mBase->getList('archives_category',array('AND'=>array('is_del=0')));
		if( $res ){
			foreach ( $res as $row ){
				$this->categorys[$row['id']] = $row;
			}
		}
	}
	
	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('category_id','keyword','page','size');
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		//get users
		$condition = array(
			'AND' => array('is_del=0'),
		);
		if( $params['category_id'] ){
			$condition['AND'][] = 'category_id='.$params['category_id'];
		}
		$res = $this->mBase->getList('archives',$condition,'*','sort ASC,id DESC',$page,$size);
		$k = $params['keyword'];
		if( $k ){
			$condition['OR'][0] = array(
				"title LIKE '%".$k."%'",
			);
		}
		$res = $this->mBase->getList('archives',$condition,'*','sort ASC,id DESC',$page,$size);
		if( $k && $res->results ){
			foreach( $res->results as &$row ){
				$row['title'] = str_replace($k, '<font color="red">'.$k.'</font>', $row['title']);
			}
			unset($row);
		}
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//Common Data
		$data['categorys'] = $this->categorys;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'archive';
		$this->_view('common/header', $tags);
		$this->_view('archive/archive_list', $data);
		$this->_view('common/footer');
    }
    
    public function add(){
    	$data = array();
    	//Common Data
    	$data['categorys'] = $this->categorys;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'archive_add';
		$this->_view('common/header', $tags);
		$this->_view('archive/archive_add', $data);
		$this->_view('common/footer');
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
		$data['single'] = $this->mBase->getSingle('archives','id',$id);
		if( !$data['single'] ){
			$this->showMsg(1000,'内容不存在',$this->active_top_tag,'archive_add');
			return;
		}
		$data['single']['content'] = str_replace(array("\r\n", "\r", "\n"), "", $data['single']['content']);
		//Common Data
		$data['categorys'] = $this->categorys;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'archive_add';
		$this->_view('common/header', $tags);
		$this->_view('archive/archive_edit', $data);
		$this->_view('common/footer');
    }
    
    public function doAdd(){
    	$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('category_id','title');
			$fields = array('content','sort','seo_title','seo_keywords','seo_description','template_id','is_top');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			//check parameters
			if( !array_key_exists($params['category_id'], $this->categorys) ){
				$ret = array('err_no'=>1000,'err_msg'=>'分类已经存在');
				break;
			}
			//insert data
			$data = array(
				'category_id'		=> $params['category_id'],
				'title'				=> $params['title'],
				'content'			=> addslashes($_POST['content']),
				'sort'				=> intval($params['sort']),
				'template_id'		=> intval($params['template_id']),
				'is_top'			=> $params['is_top'] ? 1 : 0,
				'seo_title'			=> $params['seo_title'],
				'seo_keywords'		=> $params['seo_keywords'],
				'seo_description'	=> $params['seo_description'],
				'create_eid'		=> self::$user['id'],
				'create_name'		=> self::$user['username'],
				'create_time'		=> time(),
			);
			$this->mBase->insert('archives',$data);
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
    }
    
    public function doEdit(){
    	$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('id','category_id','title');
			$fields = array('content','sort','seo_title','seo_keywords','seo_description','template_id','is_top');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$id = intval($params['id']);
			//check parameters
			$single = $this->mBase->getSingle('archives','id',$id);
			if( !$single ){
				$ret = array('err_no'=>1000,'err_msg'=>'内容不存在');
				break;
			}
			//check parameters
			if( !array_key_exists($params['category_id'], $this->categorys) ){
				$ret = array('err_no'=>1000,'err_msg'=>'内容分类不存在');
				break;
			}
			//update data
			$data = array(
				'category_id'		=> $params['category_id'],
				'title'				=> $params['title'],
				'content'			=> addslashes($_POST['content']),
				'sort'				=> intval($params['sort']),
				'template_id'		=> intval($params['template_id']),
				'is_top'			=> $params['is_top'] ? 1 : 0,
				'seo_title'			=> $params['seo_title'],
				'seo_keywords'		=> $params['seo_keywords'],
				'seo_description'	=> $params['seo_description'],
			);
			$this->mBase->update('archives',$data,array('id'=>$params['id']));
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
    		$this->mBase->update('archives',$data,array('id'=>$params['id']));
    		$ret = array('err_no'=>0,'err_msg'=>'操作成功');
    	} while(0);
    	$this->output($ret);
    }
}