<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class comment extends Base 
{
	
	private $active_top_tag = 'order';
	
	private $order_status_types = array();
	
	private $statusMap = array(
			0 => '未审核',
			1 => '已审核',
			2 => '审核不通过',
			3 => '删除',
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
		$this->params['status'] = $this->params['status']=='' ? -1 : intval($this->params['status']);
		$params = $this->params;
		//get all sites
		$data['sites'] = array();
		$res = $this->mBase->getList('sites');
		if( $res ){
			foreach ( $res as $row ){
				$data['sites'][$row['id']] = $row;
			}
		}
		$condition = array(
			'AND' => array('is_auto=0'),
		);
		if( $this->params['status']!=-1 ){
			$condition['AND'][] = 'status='.$params['status'];
		}
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		$data['params'] = $params;
		$res = $this->mBase->getList('products_comment',$condition,'*','create_time DESC',$page,$size);
		$data['products'] = array();
		if( $res->results ){
			foreach( $res->results as &$row ){
				if( !isset($data['products'][$row['product_id']]) ){
					$t= $this->mBase->getSingle('products','id',$row['product_id']);
					if( $t ){
						$data['products'][$t['id']] = $t['title'];
					}
				}
			}
			unset($row);
		}
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//common data
		$data['statusMap'] = $this->statusMap;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'comment';
		$tags['order_status_types'] = $this->order_status_types;
		$this->_view('common/header', $tags);
		$this->_view('order/comment_list', $data);
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
        	$single = $this->mBase->getSingle('products_comment','id',$id);
        	if( !$single ){
        		$ret = array('err_no'=>1000,'err_msg'=>'产品评论不存在');
        		break;
        	}
        	
        	$data = array(
        		'status' => intval($params['status']),
        	);
    		$this->mBase->update('products_comment',$data,array('id'=>$params['id']));
    		$ret = array('err_no'=>0,'err_msg'=>'操作成功');
    	} while(0);
    	$this->output($ret);
	}
}
