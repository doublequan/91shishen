<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class special extends Base {
	
	private $active_top_tag = 'archive';
	
	private $sites = array();

	public function __construct(){
		parent::__construct();
		$res = $this->mBase->getList('sites', array('AND'=>array('is_del=0')));
		foreach( $res as $row ) {
			$this->sites[$row['id']] = $row;
		}
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
		//get users
		$condition = array(
			'AND' => array('is_del=0'),
		);
		$res = $this->mBase->getList('specials',$condition,'*','id DESC',$page,$size);
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//common data
		$data['sites'] = $this->sites;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'special';
		$this->_view('common/header', $tags);
		$this->_view('archive/special_list', $data);
		$this->_view('common/footer');
    }
    
    public function add(){
    	$data = array();
    	//common data
    	$data['sites'] = $this->sites;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'special';
		$this->_view('common/header', $tags);
		$this->_view('archive/special_add', $data);
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
		$data['single'] = $this->mBase->getSingle('specials','id',$id);
		if( !$data['single'] ){
			exit('Parameter is not correct');
		}
		//get current sites
		$data['curr_sites'] = array();
		$condition = array(
			'AND' => array('special_id='.$id),
		);
		$res = $this->mBase->getList('specials_site',$condition);
		if( $res ){
			foreach ( $res as $row ){
				$data['curr_sites'][] = $row['site_id'];
			}
			$data['curr_sites'] = array_unique($data['curr_sites']);
		}
		//get current products
		$this->load->model('Product_model', 'mProduct');
		$data['curr_products'] = $this->mProduct->getSpecialProducts($id);
		//common data
		$data['sites'] = $this->sites;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'special';
		$this->_view('common/header', $tags);
		$this->_view('archive/special_edit', $data);
		$this->_view('common/footer');
    }
    
    public function doAdd(){
    	$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('name','alias','sites','banner_web','banner_app');
			$fields = array('day_start','day_end','products_id','sorts');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			//check parameters
			$t = $this->mBase->getSingle('specials','alias',$params['alias']);
			if( $t ){
				$ret = array('err_no'=>1000,'err_msg'=>'专题别名已经存在');
				break;
			}
			if( empty($params['sites']) ){
				$ret = array('err_no'=>1000,'err_msg'=>'请至少选择一个可用网站');
				break;
			}
			$day_start = $params['day_start'] ? strtotime($params['day_start']) : 0;
			$day_end = $params['day_end'] ? strtotime($params['day_end']) : 0;
			if( $day_start && $day_end && $day_start>$day_end ){
				$ret = array('err_no'=>1000,'err_msg'=>'活动开始时间不能早于结束时间');
				break;
			}
			//insert data
			$data = array(
				'name'				=> $params['name'],
				'alias'				=> $params['alias'],
				'banner_web'		=> $params['banner_web'],
				'banner_app'		=> $params['banner_app'],
				'create_eid'		=> self::$user['id'],
				'create_name'		=> self::$user['username'],
				'create_time'		=> time(),
			);
			if( $day_start ){
				$data['day_start'] = date('Y-m-d',$day_start);
			}
			if( $day_end ){
				$data['day_end'] = date('Y-m-d',$day_end);
			}
			$single = $this->mBase->insert('specials',$data);
			if( $single ){
				//add special sites
				$sites = array();
				foreach ( $params['sites'] as $v ){
					$sites[] = intval($v);
				}
				$sites = array_unique($sites);
				$data = array();
				foreach ( $sites as $site_id ){
					$data[] = array(
						'special_id'	=> $single['id'],
						'site_id'		=> $site_id,
					);
				}
				$this->mBase->insertMulti('specials_site',$data);
				//add special products
				if( $params['products_id'] && $params['sorts'] ){
					$data = array();
					foreach ( $params['products_id'] as $k=>$v ){
						$v = intval($v);
						$data[] = array(
							'special_id'	=> $single['id'],
							'product_id'	=> $v,
							'sort'			=> isset($params['sorts'][$k]) ? intval($params['sorts'][$k]) : 0,
						);
					}
					$this->mBase->insertMulti('specials_product',$data);
				}
			}
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
    }
    
    public function doEdit(){
    	$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('id','name','alias','sites','banner_web','banner_app');
			$fields = array('day_start','day_end','products_id','sorts');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$id = intval($params['id']);
			//check parameters
			if( !$id ){
				$ret = array('err_no'=>1000,'err_msg'=>'参数错误');
				break;
			}
			$single = $this->mBase->getSingle('specials','id',$id);
			if( !$single ){
				$ret = array('err_no'=>1000,'err_msg'=>'专题不存在');
				break;
			}
			if( $single['alias']!=$params['alias'] ){
				$t = $this->mBase->getSingle('specials','alias',$params['alias']);
				if( $t ){
					$ret = array('err_no'=>1000,'err_msg'=>'专题别名已经存在');
					break;
				}
			}
			if( empty($params['sites']) ){
				$ret = array('err_no'=>1000,'err_msg'=>'请至少选择一个可用网站');
				break;
			}
			$day_start = $params['day_start'] ? strtotime($params['day_start']) : 0;
			$day_end = $params['day_end'] ? strtotime($params['day_end']) : 0;
			if( $day_start && $day_end && $day_start>$day_end ){
				$ret = array('err_no'=>1000,'err_msg'=>'活动开始时间不能早于结束时间');
				break;
			}
			//update data
			$data = array(
				'name'				=> $params['name'],
				'banner_web'		=> $params['banner_web'],
				'banner_app'		=> $params['banner_app'],
				'alias'				=> $params['alias'],
			);
			if( $day_start ){
				$data['day_start'] = date('Y-m-d',$day_start);
			}
			if( $day_end ){
				$data['day_end'] = date('Y-m-d',$day_end);
			}
			$this->mBase->update('specials',$data,array('id'=>$id));
			//update sites
			$this->mBase->delete('specials_site',array('special_id'=>$id),true);
			//add special sites
			$sites = array();
			foreach ( $params['sites'] as $v ){
				$sites[] = intval($v);
			}
			$sites = array_unique($sites);
			$data = array();
			foreach ( $sites as $site_id ){
				$data[] = array(
					'special_id'	=> $id,
					'site_id'		=> $site_id,
				);
			}
			$this->mBase->insertMulti('specials_site',$data);
			//add special products
			$this->mBase->delete('specials_product',array('special_id'=>$id),true);
			if( $params['products_id'] && $params['sorts'] ){
				$data = array();
				foreach ( $params['products_id'] as $k=>$v ){
					$v = intval($v);
					$data[] = array(
						'special_id'	=> $id,
						'product_id'	=> $v,
						'sort'			=> isset($params['sorts'][$k]) ? intval($params['sorts'][$k]) : 0,
					);
				}
				$this->mBase->insertMulti('specials_product',$data);
			}
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
    		$this->mBase->update('specials',$data,array('id'=>$params['id']));
    		$ret = array('err_no'=>0,'err_msg'=>'操作成功');
    	} while(0);
    	$this->output($ret);
    }
}