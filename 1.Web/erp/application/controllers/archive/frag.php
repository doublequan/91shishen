<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class frag extends Base {
	
	private $active_top_tag = 'archive';
	
	//Common Sites
	private $sites = array();
	//Common config os types
	private $os_types = array();
	//frag types
	private $frag_types = array(
		0	=> '仅展示，无连接',
		1	=> '产品列表链接',
		2	=> '产品详情链接',
		3	=> 'WebView链接',
		4	=> '专题活动列表',
	);

	public function __construct(){
		parent::__construct();
		$res = $this->mBase->getList('sites',array('AND'=>array('is_del=0')));
		if( $res ){
			foreach ( $res as $row ){
				$this->sites[$row['id']] = $row;
			}
		}
		$this->os_types = getConfig('os_types');
	}
	
	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('site_id','os');
		$this->checkParams('get',$must,$fields);
		$this->params['os'] = $this->params['os']=='' ? -1 : $this->params['os'];
		$params = $data['params'] = $this->params;
		//get users
		$condition = array();
		if( $params['site_id'] ){
			$condition['AND'][] = 'site_id='.$params['site_id'];
		}
		if( $params['os']!=-1 ){
			$condition['AND'][] = 'os='.$params['os'];
		}
		$data['results'] = $this->mBase->getList('fragments_place',$condition,'*','id DESC');
		//Common Data
		$data['sites'] = $this->sites;
		$data['os_types'] = $this->os_types;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'frag_place_list';
		$this->_view('common/header', $tags);
		$this->_view('archive/frag_place_list', $data);
		$this->_view('common/footer');
    }
    
    public function place_add(){
    	$data = array();
    	//Common Data
    	$data['sites'] = $this->sites;
    	$data['os_types'] = $this->os_types;
		//display templates
		$this->_view('archive/frag_place_add', $data);
    }
    
    public function place_edit(){
    	$data = array();
    	//get parameters
    	$must = array('id');
    	$fields = array();
    	$this->checkParams('get',$must,$fields);
    	$params = $data['params'] = $this->params;
    	$id = intval($params['id']);
    	//get single
    	$data['single'] = $this->mBase->getSingle('fragments_place','id',$id);
    	//Common Data
    	$data['sites'] = $this->sites;
    	$data['os_types'] = $this->os_types;
    	//display templates
    	$this->_view('archive/frag_place_edit', $data);
    }
    
    public function doAddPlace(){
    	$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
    	do{
    		//get parameters
    		$must = array('site_id','os','name');
    		$fields = array('is_lock');
    		$this->checkParams('post',$must,$fields);
    		$params = $this->params;
    		$site_id = intval($params['site_id']);
    		$os = intval($params['os']);
    		//check parameters
    		if( !isset($this->sites[$site_id]) ){
    			$ret = array('err_no'=>1000,'err_msg'=>'您所选择的网站不存在');
    			break;
    		}
    		if( !isset($this->os_types[$os]) ){
    			$ret = array('err_no'=>1000,'err_msg'=>'您所选择的系统类型不支持');
    			break;
    		}
    		//insert data
    		$data = array(
    			'site_id'		=> $site_id,
    			'os'			=> $os,
    			'name'			=> $params['name'],
    			'is_lock'		=> $params['is_lock'] ? 1 : 0,
    			'modify_eid'	=> self::$user['id'],
    			'modify_name'	=> self::$user['username'],
    			'modify_time'	=> time(),
    		);
    		$this->mBase->insert('fragments_place',$data);
    		$ret = array('err_no'=>0,'err_msg'=>'操作成功');
    	} while(0);
    	$this->output($ret);
    }
    
    public function doEditPlace(){
    	$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
    	do{
    		//get parameters
    		$must = array('id','site_id','os','name');
    		$fields = array('is_lock');
    		$this->checkParams('post',$must,$fields);
    		$params = $this->params;
    		$site_id = intval($params['site_id']);
    		$os = intval($params['os']);
    		$id = intval($params['id']);
    		//check parameters
    		$single = $this->mBase->getSingle('fragments_place','id',$id);
    		if( !$single ){
    			$ret = array('err_no'=>1000,'err_msg'=>'您要编辑的碎片位置不存在');
    			break;
    		}
    		if( !isset($this->sites[$site_id]) ){
    			$ret = array('err_no'=>1000,'err_msg'=>'您所选择的网站不存在');
    			break;
    		}
    		if( !isset($this->os_types[$os]) ){
    			$ret = array('err_no'=>1000,'err_msg'=>'您所选择的系统类型不支持');
    			break;
    		}
    		//insert data
    		$data = array(
    			'site_id'		=> $site_id,
    			'os'			=> $os,
    			'name'			=> $params['name'],
    			'is_lock'		=> $params['is_lock'] ? 1 : 0,
    			'modify_eid'	=> self::$user['id'],
    			'modify_name'	=> self::$user['username'],
    			'modify_time'	=> time(),
    		);
    		$this->mBase->update('fragments_place',$data,array('id'=>$params['id']));
    		$ret = array('err_no'=>0,'err_msg'=>'操作成功');
    	} while(0);
    	$this->output($ret);
    }
    
    public function frag_list(){
    	$data = array();
    	//init parameters
    	$must = array('place_id');
    	$fields = array();
    	$this->checkParams('get',$must,$fields);
    	$params = $data['params'] = $this->params;
    	$place_id = intval($params['place_id']);
    	//get frag_place
    	$data['place'] = $this->mBase->getSingle('fragments_place','id',$place_id);
    	if( !$data['place'] ){
    		$this->showMsg(1000,'您所选择的碎片位置不存在',$this->active_top_tag,'frag_place_list');
    		return;
    	}
    	//get data
    	$condition = array(
			'AND' => array('place_id='.$place_id),
		);
    	$data['results'] = $this->mBase->getList('fragments',$condition,'*','sort ASC');
    	//Common Data
    	$data['sites'] = $this->sites;
    	$data['os_types'] = $this->os_types;
    	$data['frag_types'] = $this->frag_types;
    	//display templates
    	$tags['active_top_tag'] = $this->active_top_tag;
    	$tags['active_menu_tag'] = 'frag_place_list';
    	$this->_view('common/header', $tags);
    	$this->_view('archive/frag_list', $data);
    	$this->_view('common/footer');
    }
    
    public function frag_add(){
    	$data = array();
    	//init parameters
    	$must = array('place_id');
    	$fields = array('page','os');
    	$this->checkParams('get',$must,$fields);
    	$params = $data['params'] = $this->params;
    	$place_id = intval($params['place_id']);
    	//get frag_place
    	$data['place'] = $this->mBase->getSingle('fragments_place','id',$place_id);
    	if( !$data['place'] ){
    		$ret = array('err_no'=>1000,'err_msg'=>'碎片位置不存在');
    		$this->output($ret);
    	}
    	//Common Data
    	$data['sites'] = $this->sites;
    	$data['os_types'] = $this->os_types;
    	$data['frag_types'] = $this->frag_types;
    	//display templates
    	$this->_view('archive/frag_add', $data);
    }
    
    public function frag_edit(){
    	$data = array();
		//get parameters
		$must = array('id');
		$fields = array();
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$id = intval($params['id']);
		//get single
		$data['single'] = $this->mBase->getSingle('fragments','id',$id);
		if( !$data['single'] ){
			$ret = array('err_no'=>1000,'err_msg'=>'您要编辑的碎片不存在');
			$this->output($ret);
		}
		//get frag_place
		$data['place'] = $this->mBase->getSingle('fragments_place','id',$data['single']['place_id']);
		if( !$data['place'] ){
			$ret = array('err_no'=>1000,'err_msg'=>'您要编辑的碎片所属碎片位置不存在');
			$this->output($ret);
		}
		//Common Data
		$data['sites'] = $this->sites;
		$data['os_types'] = $this->os_types;
		$data['frag_types'] = $this->frag_types;
		//display templates
		$this->_view('archive/frag_edit', $data);
    }
    
    public function doAddFrag(){
    	$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('place_id','type','name');
			$fields = array('title','img_type','img_url','img_upload','url','des','extend','sort');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			//check parameters
			$t = $this->mBase->getSingle('fragments_place','id',$params['place_id']);
			if( !$t ){
				$ret = array('err_no'=>1000,'err_msg'=>'碎片位置不存在');
				break;
			}
			if( !isset($this->frag_types[$params['type']]) ){
				$ret = array('err_no'=>1000,'err_msg'=>'碎片类型不支持');
				break;
			}
			if( $params['title']=='' && $params['img_url']=='' && $params['img_upload']=='' ){
				$ret = array('err_no'=>1000,'err_msg'=>'碎片显示标题和图片不能都为空');
				break;
			}
			//insert data
			$data = array(
				'place_id'		=> $params['place_id'],
				'type'			=> $params['type'],
				'name'			=> $params['name'],
				'title'			=> $params['title'],
				'img'			=> $params['img_type']==1 ? $params['img_url'] : $params['img_upload'],
				'url'			=> $params['url'],
				'des'			=> $params['des'],
				'extend'		=> $params['extend'],
				'sort'			=> intval($params['sort']),
				'create_eid'	=> self::$user['id'],
				'create_name'	=> self::$user['username'],
				'create_time'	=> time(),
			);
			$t = $this->mBase->insert('fragments',$data);
			if( $t ){
				$this->mBase->updateNumber('fragments_place','num',1,array('id'=>$params['place_id']));
			}
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
    }
    
    public function doEditFrag(){
    	$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('id','type','name');
			$fields = array('title','img_type','img_url','img_upload','url','des','extend','sort');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$id = intval($params['id']);
			//check parameters
			$single = $this->mBase->getSingle('fragments','id',$id);
			if( !$single ){
				$ret = array('err_no'=>1000,'err_msg'=>'碎片位置不存在');
				break;
			}
			if( !isset($this->frag_types[$params['type']]) ){
				$ret = array('err_no'=>1000,'err_msg'=>'碎片类型不支持');
				break;
			}
			//update data
			$data = array(
				'type'			=> $params['type'],
				'name'			=> $params['name'],
				'title'			=> $params['title'],
				'img'			=> $params['img_type']==1 ? $params['img_url'] : $params['img_upload'],
				'url'			=> $params['url'],
				'des'			=> $params['des'],
				'extend'		=> $params['extend'],
				'sort'			=> intval($params['sort']),
				'modify_eid'	=> self::$user['id'],
				'modify_name'	=> self::$user['username'],
				'modify_time'	=> time(),
			);
			$this->mBase->update('fragments',$data,array('id'=>$params['id']));
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
    }
    
    public function frag_delete(){
    	$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
    	do{
    		//get parameters
    		$must = array('id');
    		$fields = array();
    		$this->checkParams('get',$must,$fields);
    		$params = $this->params;
    		//check parameter
    		$single = $this->mBase->getSingle('fragments','id',$params['id']);
    		if( !$single ){
    			$ret = array('err_no'=>1000,'err_msg'=>'碎片不存在');
    			break;
    		}
    		//update category
    		$data = array(
    			'is_del' => 1,
    		);
    		$t = $this->mBase->delete('fragments',array('id'=>$params['id']),true);
    		if( $t ){
    			$this->mBase->updateNumber('fragments_place','num',-1,array('id'=>$single['place_id']));
    		}
    		$ret = array('err_no'=>0,'err_msg'=>'操作成功');
    	} while(0);
    	$this->output($ret);
    }
}