<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class card extends Base 
{
	
	private $active_top_tag = 'order';
	
	private $order_status_types = array();
	
	private $statusMap = array(
		1 => '新订单',
		2 => '已确认',
		3 => '已完成',
	);

	public function __construct(){
		parent::__construct();
		$this->order_status_types = getConfig('order_status_types');
	}
	
	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('status','page','size');
		$this->checkParams('get',$must,$fields);
		$this->params['status']= !$this->params['status'] ? 0 : intval($this->params['status']);
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		//get list
		$condition = array(
			'AND' => array('is_del=0'),
		);
		if( $params['status'] ){
			$condition['AND'][] = 'status='.$params['status'];
		}
		$res = $this->mBase->getList('orders_card',$condition,'*','id DESC',$page,$size);
		if( $res->results ){
			foreach( $res->results as &$row ){
				$row['details'] = json_decode($row['detail'],true);
			}
			unset($row);
		}
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//common data
		$data['statusMap'] = $this->statusMap;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'card';
		$tags['order_status_types'] = $this->order_status_types;
		$this->_view('common/header', $tags);
		$this->_view('order/card_list', $data);
		$this->_view('common/footer');
	}
	
	public function updateStatus(){
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
    	do{
        	//get parameters
        	$must = array('id','status');
        	$fields = array();
        	$this->checkParams('post',$must,$fields);
        	$params = $this->params;
        	$id = intval($params['id']);
        	//check parameters
        	$single = $this->mBase->getSingle('orders_card','id',$id);
        	if( !$single ){
        		$ret = array('err_no'=>1000,'err_msg'=>'会员卡订单不存在');
        		break;
        	}
        	$data = array(
        		'status' => intval($params['status']),
        	);
    		$this->mBase->update('orders_card',$data,array('id'=>$params['id']));
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
			$this->mBase->update('orders_card',$data,array('id'=>$params['id']));
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
}
