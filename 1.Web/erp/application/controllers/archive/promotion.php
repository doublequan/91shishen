<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class promotion extends Base {
	
	private $active_top_tag = 'archive';
	
	private $sites = array();

	public function __construct(){
		parent::__construct();
		$res = $this->mBase->getList('sites');
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
		$res = $this->mBase->getList('promotions',$condition,'*','id DESC',$page,$size);
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//common data
		$data['sites'] = $this->sites;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'promotion';
		$this->_view('common/header', $tags);
		$this->_view('archive/promotion_list', $data);
		$this->_view('common/footer');
    }
    
    public function add(){
    	$data = array();
    	//common data
    	$data['sites'] = $this->sites;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'promotion';
		$this->_view('common/header', $tags);
		$this->_view('archive/promotion_add', $data);
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
		$data['single'] = $this->mBase->getSingle('promotions','id',$id);
		if( !$data['single'] ){
			exit('Parameter is not correct');
		}
		$t = json_decode($data['single']['limit_product'],true);
		$data['single']['limit_product'] = $t ? implode(',', $t) : '';
		$arr = json_decode($data['single']['limit_addition'],true);
		$t = array();
		if( $arr ){
			foreach ( $arr as $k=>$v ){
				$t[] = $k.':'.$v;
			}
		}
		$data['single']['limit_addition'] = implode(',', $t);
		$t = json_decode($data['single']['give_product'],true);
		$data['single']['give_product'] = $t ? implode(',', $t) : '';
		//get current sites
		$data['curr_sites'] = array();
		$condition = array(
			'AND' => array('promotion_id='.$id),
		);
		$res = $this->mBase->getList('promotions_site',$condition);
		if( $res ){
			foreach ( $res as $row ){
				$data['curr_sites'][] = $row['site_id'];
			}
			$data['curr_sites'] = array_unique($data['curr_sites']);
		}
		//common data
		$data['sites'] = $this->sites;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'promotion';
		$this->_view('common/header', $tags);
		$this->_view('archive/promotion_edit', $data);
		$this->_view('common/footer');
    }
    
    public function doAdd(){
    	$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('name','trigger','limit_type','give_type','sites');
			$fields = array('limit_user','limit_price','limit_product','limit_addition','give_price','give_product','give_score','day_start','day_end');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$trigger = intval($params['trigger']);
			$limit_type = intval($params['limit_type']);
			$give_type = intval($params['give_type']);
			//check parameters
			if( !in_array($trigger,array(1,2,3)) ){
				$ret = array('err_no'=>1000,'err_msg'=>'不合法的触发类型');
				break;
			}
			if( !in_array($limit_type,array(1,2,3)) ){
				$ret = array('err_no'=>1000,'err_msg'=>'不合法的促销限制类型');
				break;
			}
			if( !in_array($give_type,array(1,2,3)) ){
				$ret = array('err_no'=>1000,'err_msg'=>'不合法的促销回馈类型');
				break;
			}
			if( empty($params['sites']) ){
				$ret = array('err_no'=>1000,'err_msg'=>'请至少选择一个可用网站');
				break;
			}
			$day_start = strtotime($params['day_start']);
			$day_end = strtotime($params['day_end']);
			if( $day_start && $day_end && $day_start>$day_end ){
				$ret = array('err_no'=>1000,'err_msg'=>'活动开始时间不能早于结束时间');
				break;
			}
			//get promotion limit detail
			$limit_price = 0;
			$limit_product = '';
			$limit_addition = '';
			if( $limit_type==1 ){
				$limit_price = floatval($params['limit_price']);
			} elseif ( $limit_type==2 ){
				$arr = explode(',', trim($params['limit_product']));
				if( $arr ){
					$t = array();
					foreach ( $arr as $v ){
						$product = $this->mBase->getSingle('products','id',$v);
						if( $product ){
							$t[] = $v;
						}
					}
					$limit_product = json_encode($t);
				}
			} elseif ( $limit_type==3 ){
				$arr = explode(',', trim($params['limit_addition']));
				if( $arr ){
					$t = array();
					foreach ( $arr as $v ){
						$arr2 = explode(':', $v);
						if( isset($arr2[0]) && $arr2[0] ){
							$product = $this->mBase->getSingle('products','id',$arr2[0]);
							if( $product ){
								$t[$arr2[0]] = $arr2[1];
							}
						}
					}
					$limit_addition = json_encode($t);
				}
			}
			//get promotion give detail
			$give_price = 0;
			$give_product = '';
			$give_score = 0;
			if( $give_type==1 ){
				$give_price = floatval($params['give_price']);
			} elseif ( $give_type==2 ){
				$arr = explode(',', trim($params['limit_product']));
				if( $arr ){
					$t = array();
					foreach ( $arr as $v ){
						$product = $this->mBase->getSingle('products','id',$v);
						if( $product ){
							$t[] = $v;
						}
					}
					$limit_product = json_encode($t);
				}
			} elseif ( $give_type==3 ){
				$give_score = intval($params['give_score']);
			}
			//insert data
			$data = array(
				'name'			=> $params['name'],
				'trigger'		=> $trigger,
				'limit_type'	=> $limit_type,
				'give_type'		=> $give_type,
				'limit_user'	=> intval($params['limit_user']),
				'limit_price'	=> $limit_price,
				'limit_product'	=> $limit_product,
				'limit_addition'=> $limit_addition,
				'give_price'	=> $give_price,
				'give_product'	=> $give_product,
				'give_score'	=> $give_score,
				'day_start'		=> date('Y-m-d',$day_start),
				'day_end'		=> date('Y-m-d',$day_end),
				'create_eid'	=> self::$user['id'],
				'create_name'	=> self::$user['username'],
				'create_time'	=> time(),
			);
			$single = $this->mBase->insert('promotions',$data);
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
						'promotion_id'	=> $single['id'],
						'site_id'		=> $site_id,
					);
				}
				$this->mBase->insertMulti('promotions_site',$data);
			}
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
    }
    
    public function doEdit(){
    	$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('id','name','trigger','limit_type','give_type','sites');
			$fields = array('limit_user','limit_price','limit_product','limit_addition','give_price','give_product','give_score','day_start','day_end');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$id = intval($params['id']);
			$trigger = intval($params['trigger']);
			$limit_type = intval($params['limit_type']);
			$give_type = intval($params['give_type']);
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
			if( !in_array($trigger,array(1,2,3)) ){
				$ret = array('err_no'=>1000,'err_msg'=>'不合法的触发类型');
				break;
			}
			if( !in_array($limit_type,array(1,2,3)) ){
				$ret = array('err_no'=>1000,'err_msg'=>'不合法的促销限制类型');
				break;
			}
			if( !in_array($give_type,array(1,2,3)) ){
				$ret = array('err_no'=>1000,'err_msg'=>'不合法的促销回馈类型');
				break;
			}
			if( empty($params['sites']) ){
				$ret = array('err_no'=>1000,'err_msg'=>'请至少选择一个可用网站');
				break;
			}
			$day_start = strtotime($params['day_start']);
			$day_end = strtotime($params['day_end']);
			if( $day_start && $day_end && $day_start>$day_end ){
				$ret = array('err_no'=>1000,'err_msg'=>'活动开始时间不能早于结束时间');
				break;
			}
			//get promotion limit detail
			$limit_price = 0;
			$limit_product = '';
			$limit_addition = '';
			if( $limit_type==1 ){
				$limit_price = floatval($params['limit_price']);
			} elseif ( $limit_type==2 ){
				$arr = explode(',', trim($params['limit_product']));
				if( $arr ){
					$t = array();
					foreach ( $arr as $v ){
						$product = $this->mBase->getSingle('products','id',$v);
						if( $product ){
							$t[] = $v;
						}
					}
					$limit_product = json_encode($t);
				}
			} elseif ( $limit_type==3 ){
				$arr = explode(',', trim($params['limit_addition']));
				if( $arr ){
					$t = array();
					foreach ( $arr as $v ){
						$arr2 = explode(':', $v);
						if( isset($arr2[0]) && $arr2[0] ){
							$product = $this->mBase->getSingle('products','id',$arr2[0]);
							if( $product ){
								$t[$arr2[0]] = $arr2[1];
							}
						}
					}
					$limit_addition = json_encode($t);
				}
			}
			//get promotion give detail
			$give_price = 0;
			$give_product = '';
			$give_score = 0;
			if( $give_type==1 ){
				$give_price = floatval($params['give_price']);
			} elseif ( $give_type==2 ){
				$arr = explode(',', trim($params['limit_product']));
				if( $arr ){
					$t = array();
					foreach ( $arr as $v ){
						$product = $this->mBase->getSingle('products','id',$v);
						if( $product ){
							$t[] = $v;
						}
					}
					$limit_product = json_encode($t);
				}
			} elseif ( $give_type==3 ){
				$give_score = intval($params['give_score']);
			}
			//update data
			$data = array(
				'name'			=> $params['name'],
				'trigger'		=> $trigger,
				'limit_type'	=> $limit_type,
				'give_type'		=> $give_type,
				'limit_user'	=> intval($params['limit_user']),
				'limit_price'	=> $limit_price,
				'limit_product'	=> $limit_product,
				'limit_addition'=> $limit_addition,
				'give_price'	=> $give_price,
				'give_product'	=> $give_product,
				'give_score'	=> $give_score,
				'day_start'		=> date('Y-m-d',$day_start),
				'day_end'		=> date('Y-m-d',$day_end),
			);
			$rows = $this->mBase->update('promotions',$data,array('id'=>$id));
			//add special sites
			$this->mBase->delete('promotions_site',array('promotion_id'=>$id),true);
			$sites = array();
			foreach ( $params['sites'] as $v ){
				$sites[] = intval($v);
			}
			$sites = array_unique($sites);
			$data = array();
			foreach ( $sites as $site_id ){
				$data[] = array(
					'promotion_id'	=> $single['id'],
					'site_id'		=> $site_id,
				);
			}
			$this->mBase->insertMulti('promotions_site',$data);
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
    		$this->mBase->update('promotions',$data,array('id'=>$params['id']));
    		$ret = array('err_no'=>0,'err_msg'=>'操作成功');
    	} while(0);
    	$this->output($ret);
    }
}