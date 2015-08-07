<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class pay extends Base 
{
	private $active_top_tag = 'stat';
	
	private $stores;
	
	public function __construct(){
		parent::__construct();
		//获取所有自提门店
		$cache_key = 'STORES_IS_DEL_0_IS_PICKUP_1';
		$this->stores = $this->getCacheList('stores',array('AND'=>array('is_del=0','is_pickup=1')),$cache_key,600);
	}

    public function alipay(){
    	$data = array();
    	//init parameters
    	$must = array();
    	$fields = array('act','store_id','keyword','page','size');
    	$this->checkParams('get',$must,$fields);
    	$this->params['store_id'] = $this->params['store_id']=='' ? 0 : intval($this->params['store_id']);
    	$params = $data['params'] = $this->params;
    	$k = $params['keyword'];
    	$page = max(1,intval($params['page']));
    	$size = max(20,min(100,intval($params['size'])));
    	//get condition
    	$condition = array();
    	if( $params['store_id'] ){
    		$condition['AND'][] = 'o.store_id='.$params['store_id'];
    	}
    	if( $k ){
    		$condition['AND'][] = "o.id LIKE '%".$k."%'";
    	}
    	$table = 'orders_alipay AS oa INNER JOIN orders AS o ON o.id=oa.order_id';
    	$column = 'oa.*';
    	$res = $this->mBase->getList($table,$condition,$column,'oa.id DESC',$page,$size);
    	$data['results'] = $res->results;
    	$data['pager'] = $res->pager;
    	//common data
    	$data['stores'] = $this->stores;
    	//导出数据
    	if( $params['act']=='export' ){
    		$head = array('订单号','支付金额','支付状态','支付宝订单编号','支付用户账号	','支付时间');
    		$body = array();
    		if( $data['results'] ){
    			foreach ( $data['results'] as $k=>$row ){
    				$body[$k][] = $row['order_id'];
    				$body[$k][] = '￥'.sprintf('%.2f',$row['price']);
    				$body[$k][] = $row['pay_time'] ? '已支付' : '未支付';
    				$body[$k][] = $row['trade_no'];
    				$body[$k][] = $row['buyer_email'];
    				$body[$k][] = $row['pay_time'] ? date('Y-m-d H:i:s',$row['pay_time']) : '';
    			}
    		}
    		$this->exportExcel( $head, $body );
    	}
    	//display templates
    	$tags['active_top_tag'] = $this->active_top_tag;
    	$tags['active_menu_tag'] = 'pay_alipay';
    	$this->_view('common/header', $tags);
    	$this->_view('stat/pay_alipay', $data);
    	$this->_view('common/footer');
    }
    
    public function yeepay(){
    	$data = array();
    	//init parameters
    	$must = array();
    	$fields = array('act','store_id','keyword','page','size');
    	$this->checkParams('get',$must,$fields);
    	$this->params['store_id'] = $this->params['store_id']=='' ? 0 : intval($this->params['store_id']);
    	$params = $data['params'] = $this->params;
    	$k = $params['keyword'];
    	$page = max(1,intval($params['page']));
    	$size = max(20,min(100,intval($params['size'])));
    	//get condition
    	$condition = array();
    	if( $params['store_id'] ){
    		$condition['AND'][] = 'o.store_id='.$params['store_id'];
    	}
    	if( $k ){
    		$condition['AND'][] = "order_id LIKE '%".$k."%'";
    	}
    	$table = 'orders_yeepay_card AS oyc INNER JOIN orders AS o ON o.id=oyc.order_id';
    	$column = 'oyc.*';
    	$res = $this->mBase->getList($table,$condition,$column,'oyc.id DESC',$page,$size);
    	$data['results'] = $res->results;
    	$data['pager'] = $res->pager;
    	//common data
    	$data['stores'] = $this->stores;
    	$data['channelMap'] = array(1=>'POS',2=>'线上');
    	$data['typeMap'] = array(1=>'消费',2=>'冲正',3=>'退款',4=>'充值');
    	//导出数据
    	if( $params['act']=='export' ){
    		$head = array('订单号','支付金额','支付状态','易宝交易流水号','易宝渠道类型','易宝交易类型','交易时间');
    		$body = array();
    		if( $data['results'] ){
    			foreach ( $data['results'] as $k=>$row ){
    				$body[$k][] = $row['order_id'];
    				$body[$k][] = '￥'.sprintf('%.2f',$row['price']);
    				$body[$k][] = $row['trade_no'] ? '已支付' : '未支付';
    				$body[$k][] = $row['trade_no'];
    				$body[$k][] = isset($data['channelMap'][$row['channel']]) ? $data['channelMap'][$row['channel']] : '';
    				$body[$k][] = isset($data['typeMap'][$row['trade_type']]) ? $data['typeMap'][$row['trade_type']] : '';
    				$body[$k][] = $row['trade_time'] ? date('Y-m-d H:i:s',$row['trade_time']) : '';
    			}
    		}
    		$this->exportExcel( $head, $body );
    	}
    	//display templates
    	$tags['active_top_tag'] = $this->active_top_tag;
    	$tags['active_menu_tag'] = 'pay_yeepay';
    	$this->_view('common/header', $tags);
    	$this->_view('stat/pay_yeepay', $data);
    	$this->_view('common/footer');
    }
}