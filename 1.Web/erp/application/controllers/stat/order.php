<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class order extends Base 
{
	private $active_top_tag = 'stat';
    private $sites;

	public function __construct(){
		parent::__construct();
	}

    public function index(){
    }
    
    public function card(){
    	$data = array();
    	//init parameters
    	$must = array();
    	$fields = array('act','start','end','site_id');
    	$this->checkParams('get',$must,$fields);
    	$this->params['site_id'] = $this->params['site_id']=='' ? 1 : intval($this->params['site_id']);
    	$params = $data['params'] = $this->params;
    	$t = time();
    	$start = $params['start'] ? strtotime($params['start']) : $t-86400*7;
    	$end = $params['end'] ? strtotime($params['end']) : $t;
    	$site_id = $params['site_id'];
    	$data['dates'] = $this->getDates($start,$end,'m-d');
    	//get data
    	$data['chart_data'] = $data['results'] = array();
    	$condition = array();
    	$condition['AND'][] = 'o.order_status>=20';
    	$condition['AND'][] = 'o.create_time>='.$start;
    	$condition['AND'][] = 'o.create_time<'.($end+86400);
    	$condition['AND'][] = 'o.site_id='.$site_id;
    	$condition['AND'][] = "u.cardno<>''";
    	$condition['AND'][] = 'u.status=1';
    	$res = $this->mBase->getList('orders AS o INNER JOIN users AS u ON u.id=o.uid',$condition,'o.*','o.create_time ASC');
    	if( $res ){
    		foreach ( $res as $row ){
    			$day = date('Y-m-d',$row['create_time']);
    			if( isset($data['results'][$day]) ){
    				$data['results'][$day]++;
    			} else {
    				$data['results'][$day] = 1;
    			}
    		}
    		foreach ( $data['results'] as $k=>$v ){
    			$data['chart_data'][] = array($k,$v);
    		}
    	}
    	$data['params']['start'] = date('Y-m-d',$start);
    	$data['params']['end'] = date('Y-m-d',$end);
    	//get cached stores
    	$data['stores'] = $this->getCacheList('stores',array(),'all_stores',600);
    	$data['sites'] = $this->getCacheList('sites',array('AND'=>array('is_del=0')),'all_sites',600);
    	$data['site'] = $data['sites'][$site_id];
    	//display templates
    	$tags['active_top_tag'] = $this->active_top_tag;
    	$tags['active_menu_tag'] = 'order_card';
    	$this->_view('common/header', $tags);
    	$this->_view('stat/order_card', $data);
    	$this->_view('common/footer');
    }
    
    public function sale(){
    	$data = array();
    	//init parameters
    	$must = array();
    	$fields = array('act','start','end','site_id','pay_type','delivery_type');
    	$this->checkParams('get',$must,$fields);
    	$this->params['site_id'] = $this->params['site_id']=='' ? 1 : intval($this->params['site_id']);
    	$this->params['pay_type'] = intval($this->params['pay_type']);
    	$this->params['delivery_type'] = $this->params['delivery_type']!='' ? intval($this->params['delivery_type']) : -1;
    	$params = $data['params'] = $this->params;
    	$t = time();
    	$start = $params['start'] ? strtotime($params['start']) : $t-86400*15;
    	$end = $params['end'] ? strtotime($params['end']) : $t;
    	$site_id = $params['site_id'];
    	//get condition
    	$condition = array();
    	$condition['AND'][] = 'o.order_status>=20';
    	$condition['AND'][] = 'o.create_time>='.$start;
    	$condition['AND'][] = 'o.create_time<'.($end+86400);
    	$condition['AND'][] = 'o.site_id='.$site_id;
    	if( $params['pay_type'] ){
    		$condition['AND'][] = 'o.pay_type='.$params['pay_type'];
    	}
    	if( $params['delivery_type']==0 || $params['delivery_type']==1 ){
    		$condition['AND'][] = 'o.delivery_type='.$params['delivery_type'];
    	}
    	$condition['AND'][] = 'u.status=1';
    	$data['results'] = array();
    	$table = 'orders AS o INNER JOIN users AS u ON u.id=o.uid';
    	$column = 'o.id,o.price,o.price_total,o.create_time,o.pay_type,u.cardno';
    	$res = $this->mBase->getList($table,$condition,$column,'o.create_time ASC');
    	if( $res ){
    		foreach ( $res as $row ){
    			$day = date('Y-m-d',$row['create_time']);
    			$num = $this->mBase->getCount('orders_detail',array('AND'=>array("order_id='".$row['id']."'")));
    			if( isset($data['results'][$day]) ){
    				$data['results'][$day]['total']				+= 1;
    				$data['results'][$day]['details']			+= $num;
    				$data['results'][$day]['price']				+= $row['price'];
    				$data['results'][$day]['price_total']		+= $row['price_total'];
    				$data['results'][$day]['total_card']		+= $row['cardno'] ? 1 : 0;
    				$data['results'][$day]['price_card']		+= $row['cardno'] ? $row['price'] : 0;
    			} else {
    				$data['results'][$day] = array(
    					'total'			=> 1,
    					'details'		=> $num,
    					'price'			=> $row['price'],
    					'price_total'	=> $row['price_total'],
    					'total_card'	=> $row['cardno'] ? 1 : 0,
    					'price_card'	=> $row['cardno'] ? $row['price'] : 0,
    				);
    			}
    			if( isset($data['results'][$day]['pay_types'][$row['pay_type']]) ){
    				$data['results'][$day]['pay_types'][$row['pay_type']] += $row['price'];
    			} else {
    				$data['results'][$day]['pay_types'][$row['pay_type']] = $row['price'];
    			}
    		}
    		krsort($data['results']);
    	}
    	$data['params']['start'] = date('Y-m-d',$start);
    	$data['params']['end'] = date('Y-m-d',$end);
    	//get cached stores
    	$data['sites'] = $this->getCacheList('sites',array('AND'=>array('is_del=0')),'SITES_IS_DEL_0',600);
    	$pay_types = getConfig('pay_types');
    	unset($pay_types[0]);
    	$data['pay_types'] = $pay_types;
    	$data['delivery_types'] = getConfig('delivery_types');
    	//导出数据
		if( $params['act']=='export' ){
			$head = array('日期','销售额','客单价','订单数','商品详情数','商品总金额','储单数','储单金额');
			foreach( $pay_types as $v ){
				$head[] = $v;
			}
			$body = array();
			if( $data['results'] ){
				foreach ( $data['results'] as $k=>$row ){
					$body[$k][] = $k;
					$body[$k][] = '￥'.sprintf('%.2f',$row['price']);
					$body[$k][] = '￥'.sprintf('%.2f',$row['price']/$row['total']);
					$body[$k][] = $row['total'];
					$body[$k][] = $row['details'];
					$body[$k][] = '￥'.sprintf('%.2f',$row['price_total']);
					$body[$k][] = $row['total_card'];
					$body[$k][] = '￥'.sprintf('%.2f',$row['price_card']);
					//$body[$k][] = '￥'.sprintf('%.2f',$row['price_cash']);
					//$body[$k][] = '￥'.sprintf('%.2f',$row['price_discount']);
					//$body[$k][] = '￥'.sprintf('%.2f',$row['price_shipping']);
					//$body[$k][] = '￥'.sprintf('%.2f',$row['price_minus']);
					foreach( $pay_types as $k2=>$v ){
						$body[$k][] = isset($row['pay_types'][$k2]) ? '￥'.sprintf('%.2f',$row['pay_types'][$k2]) : '0.00';
					}
				}
			}
			$this->exportExcel( $head, $body );
		}
    	//display templates
    	$tags['active_top_tag'] = $this->active_top_tag;
    	$tags['active_menu_tag'] = 'order_sale';
    	$this->_view('common/header', $tags);
    	$this->_view('stat/order_sale', $data);
    	$this->_view('common/footer');
    }
    
    public function amount(){
    	$data = array();
    	//init parameters
    	$must = array();
    	$fields = array('act','start','end','site_id');
    	$this->checkParams('get',$must,$fields);
    	$this->params['site_id'] = $this->params['site_id']=='' ? 1 : intval($this->params['site_id']);
    	$params = $data['params'] = $this->params;
    	$t = time();
    	$start = $params['start'] ? strtotime($params['start']) : $t-86400*7;
    	$end = $params['end'] ? strtotime($params['end']) : $t;
    	$site_id = $params['site_id'];
    	$data['dates'] = $this->getDates($start,$end,'m-d');
    	//get data
    	$data['results'] = array();
    	$condition = array();
    	$condition['AND'][] = 'o.order_status>=20';
    	$condition['AND'][] = 'o.create_time>='.$start;
    	$condition['AND'][] = 'o.create_time<'.($end+86400);
    	$condition['AND'][] = 'o.site_id='.$site_id;
    	$condition['AND'][] = 'u.status=1';
    	$table = 'orders AS o INNER JOIN users AS u ON u.id=o.uid';
    	$column = 'o.id,o.price,o.price_total,o.create_time,o.pay_type,o.uid,u.cardno,u.store_id';
    	$res = $this->mBase->getList($table,$condition,$column,'o.create_time ASC');
    	if( $res ){
    		$uids_all = array();
    		$uids_card = array();
    		foreach ( $res as $row ){
    			$uids_all[$row['uid']] = $row['uid'];
    			if( $row['cardno'] ){
    				$uids_card[$row['uid']] = $row['uid'];
    			}
    			$day = date('Y-m-d',$row['create_time']);
    			if( isset($data['results'][$day]) ){
    				$data['results'][$day]['total']++;
    				if( $row['store_id'] ){
    					$data['results'][$day]['store']++;
    				}
    			} else {
    				$data['results'][$day] = array(
    					'total'	=> 1,
    					'first'	=> 0,
    					'card'	=> 0,
    					'store'	=> $row['store_id'] ? 1 : 0,
    				);
    			}
    		}
    		//获取用户是否有第一次下单的
    		$uids_all = array_unique($uids_all);
    		$uids_card = array_unique($uids_card);
    		$this->load->model('Stat_model','mStat');
    		$arr = $this->mStat->getUserFirstOrder($uids_all);
    		if( $arr ){
    			foreach ( $arr as $row ){
    				$day = date('Y-m-d',$row['create_time']);
    				if( isset($data['results'][$day]) ){
    					$data['results'][$day]['first']++;
    					if( isset($uids_card[$row['uid']]) ){
    						$data['results'][$day]['card']++;
    					}
    				}
    			}
    		}
    		//create series
    		$data['series'] = array(
    			array('name'=>'总订单量','data'=>array()),
    			array('name'=>'首次下单量','data'=>array()),
    			array('name'=>'储值卡首次下单量','data'=>array()),
    			array('name'=>'门店订单量','data'=>array()),
    		);
    		foreach ( $data['results'] as $k=>$row ){
    			$data['series'][0]['data'][] = intval($row['total']);
    			$data['series'][1]['data'][] = intval($row['first']);
    			$data['series'][2]['data'][] = intval($row['card']);
    			$data['series'][3]['data'][] = intval($row['store']);
    		}
    	}
    	$data['params']['start'] = date('Y-m-d',$start);
    	$data['params']['end'] = date('Y-m-d',$end);
    	//get cached stores
    	$data['stores'] = $this->getCacheList('stores',array(),'STORES',600);
    	$data['sites'] = $this->getCacheList('sites',array('AND'=>array('is_del=0')),'SITES_IS_DEL_0',600);
    	$data['site'] = $data['sites'][$site_id];
    	//导出数据
    	if( $params['act']=='export' ){
    		$head = array('日期','总订单量','首次下单量','储值卡首次下单量','门店订单量');
    		$body = array();
    		if( $data['results'] ){
    			foreach ( $data['results'] as $k=>$row ){
    				$body[$k][] = $k;
    				$body[$k][] = $row['total'];
    				$body[$k][] = $row['first'];
    				$body[$k][] = $row['card'];
    				$body[$k][] = $row['store'];
    			}
    		}
    		$this->exportExcel( $head, $body );
    	}
    	//display templates
    	$tags['active_top_tag'] = $this->active_top_tag;
    	$tags['active_menu_tag'] = 'order_amount';
    	$this->_view('common/header', $tags);
    	$this->_view('stat/order_amount', $data);
    	$this->_view('common/footer');
    }
    
    public function store(){
    	$data = array();
    	//init parameters
    	$must = array();
    	$fields = array('act','start','end','site_id');
    	$this->checkParams('get',$must,$fields);
    	$this->params['site_id'] = $this->params['site_id']=='' ? 1 : intval($this->params['site_id']);
    	$params = $data['params'] = $this->params;
    	$t = time();
    	$start = $params['start'] ? strtotime($params['start']) : $t-86400*7;
    	$end = $params['end'] ? strtotime($params['end']) : $t;
    	$site_id = $params['site_id'];
    	//get all uids
    	$uids = array();
    	$userMap = array();
    	$res = $this->mBase->getList('users',array('AND'=>array('store_id>0')),'id,store_id');
    	if( $res ){
    		foreach ( $res as $row ){
    			$uids[] = $row['id'];
    			$userMap[$row['id']] = $row['store_id'];
    		}
    		$uids = array_unique($uids);
    	}
    	if( !$uids ){
    		$this->showMsg(1000,'未关联门店用户账号，请在用户管理->用户列表->编辑中完成门店用户的绑定');
    		return;
    	}
    	//get condition
    	$condition = array();
    	$condition['AND'][] = 'order_status>=20';
    	$condition['AND'][] = 'create_time>='.$start;
    	$condition['AND'][] = 'create_time<'.($end+86400);
    	$condition['AND'][] = 'site_id='.$site_id;
    	$condition['AND'][] = 'uid IN ('.implode(',', $uids).')';
    	$data['results'] = array();
    	$res = $this->mBase->getList('orders',$condition);
    	if( $res ){
    		$stores = $this->getCacheList('stores',array(),'ALL_STORES',600);
    		foreach ( $res as $row ){
    			$day = date('Y-m-d',$row['create_time']);
    			$store_id = $userMap[$row['uid']];
    			if( isset($data['results'][$day][$store_id]) ){
    				$data['results'][$day][$store_id]['total'] += 1;
    				$data['results'][$day][$store_id]['price'] += $row['price'];
    			} else {
    				$data['results'][$day][$store_id] = array(
    					'name'	=> isset($stores[$store_id]) ? $stores[$store_id]['name'] : '',
    					'total' => 1,
    					'price' => $row['price'],
    				);
    			}
    		}
    	}
    	krsort($data['results']);
    	$data['params']['start'] = date('Y-m-d',$start);
    	$data['params']['end'] = date('Y-m-d',$end);
    	//get cached stores
    	$data['sites'] = $this->getCacheList('sites',array('AND'=>array('is_del=0')),'UNDELETE_SITE',600);
    	//display templates
    	$tags['active_top_tag'] = $this->active_top_tag;
    	$tags['active_menu_tag'] = 'order_store';
    	$this->_view('common/header', $tags);
    	$this->_view('stat/order_store', $data);
    	$this->_view('common/footer');
    }
    
    public function product(){
    	$data = array();
    	//init parameters
    	$must = array();
    	$fields = array('act','is_all','start','end','site_id');
    	$this->checkParams('get',$must,$fields);
    	$this->params['is_all'] = $this->params['is_all']=='' ? 0 : intval($this->params['is_all']);
    	$this->params['site_id'] = $this->params['site_id']=='' ? 1 : intval($this->params['site_id']);
    	$params = $data['params'] = $this->params;
    	$t = time();
    	$start = $params['start'] ? strtotime($params['start']) : $t-86400*7;
    	$end = $params['end'] ? strtotime($params['end']) : $t;
    	$site_id = $params['site_id'];
    	//get condition
    	$data['results'] = $data['products'] = array();
    	$condition = array();
    	$condition['AND'][] = 'o.order_status>=20';
    	$condition['AND'][] = 'o.site_id='.$site_id;
    	if( !$params['is_all'] ){
    		$condition['AND'][] = 'o.create_time>='.$start;
    		$condition['AND'][] = 'o.create_time<'.($end+86400);
    	}
    	$res = $this->mBase->getList('orders_detail AS od INNER JOIN orders AS o ON od.order_id=o.id',$condition);
    	if( $res ){
    		$product_ids = array();
    		foreach ( $res as $row ){
    			$product_ids[$row['product_id']] = $row['product_id'];
    			if( isset($data['results'][$row['product_id']]) ){
    				$data['results'][$row['product_id']] += $row['amount'];
    			} else {
    				$data['results'][$row['product_id']] = $row['amount'];
    			}
    		}
    		arsort($data['results']);
    		$min = min($product_ids);
    		$max = max($product_ids);
    		$condition = array(
    			'AND' => array('id<='.$max,'id>='.$min,'id IN ('.implode(',', $product_ids).')')
    		);
    		$arr = $this->mBase->getList('products',$condition);
    		foreach ( $arr as $row ){
    			$data['products'][$row['id']] = $row;
    		}
    	}
    	$data['params']['start'] = date('Y-m-d',$start);
    	$data['params']['end'] = date('Y-m-d',$end);
    	//get cached data
    	$data['sites'] = $this->getCacheList('sites',array('AND'=>array('is_del=0')),'all_sites',600);
    	$data['cates'] = $this->getCacheList('goods_category',array('AND'=>array('is_del=0')),'all_categorys',600);
    	//导出查询结果
    	if( $params['act']=='export' ){
    		$head = array('商品编号','商品货号','商品名称','商品分类','商品规格','商品单价','商品销量');
    		$body = array();
    		if( $data['results'] ){
    			foreach ( $data['results'] as $k=>$v ){
    				if( $data['products'][$k] ){
	    				$t = $data['products'][$k];
	    				$body[$k][] = $t['sku'];
	    				$body[$k][] = $t['product_pin'];
	    				$body[$k][] = $t['title'];
	    				$body[$k][] = isset($data['cates'][$t['category_id']]) ? $data['cates'][$t['category_id']]['name'] : '';
	    				$body[$k][] = $t['spec'];
	    				$body[$k][] = sprintf('%.2f', $t['price']);
	    				$body[$k][] = $v;
    				}
    			}
    		}
    		$this->exportExcel( $head, $body );
    	}
    	//display templates
    	$tags['active_top_tag'] = $this->active_top_tag;
    	$tags['active_menu_tag'] = 'order_product';
    	$this->_view('common/header', $tags);
    	$this->_view('stat/order_product', $data);
    	$this->_view('common/footer');
    }

    private function getDates($start, $end, $format='Y-m-d'){
    	$dates = array();
    	if( $start > $end ){
    		return $dates;
    	}
    	$twice = ($end - $start) / 86400;        
        for($i = 0;$i <= $twice; $i++){
            $dates[] = date($format, ($start + $i * 86400));
        }
        return $dates;
    }
}