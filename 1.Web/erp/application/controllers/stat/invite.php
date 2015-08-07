<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class invite extends Base 
{
	private $active_top_tag = 'stat';

	public function __construct(){
		parent::__construct();
	}

    public function index(){
    }
    
    public function amount(){
    	$data = array();
    	//init parameters
    	$must = array();
    	$fields = array('act');
    	$this->checkParams('get',$must,$fields);
    	$params = $data['params'] = $this->params;
    	//get data
    	$data['results'] = array();
    	$condition = array();
    	$condition['AND'][] = 'u.status=1';
    	$condition['AND'][] = 'u.invite_eid>0';
    	$condition['AND'][] = 'e.is_del=0';
    	$table = 'users AS u INNER JOIN employees AS e ON e.id=u.invite_eid';
    	$column = 'u.id,u.create_time,e.username,e.id AS eid';
    	$res = $this->mBase->getList($table,$condition,$column,'u.create_time DESC');
    	if( $res ){
    		foreach ( $res as $row ){
    			if( isset($data['results'][$row['eid']]) ){
    				$data['results'][$row['eid']]['total']++;
    			} else {
    				$data['results'][$row['eid']] = array(
    					'username'	=> $row['username'],
    					'total'		=> 1,
    					'last_time'	=> date('Y-m-d H:i:s',$row['create_time']),
    				);
    			}
    		}
    	}
    	//导出数据
    	if( $params['act']=='export' ){
    		$head = array('员工姓名','邀请用户数量','最新邀请成功时间');
    		$body = array();
    		if( $data['results'] ){
    			foreach ( $data['results'] as $k=>$row ){
    				$body[$k][] = $row['username'];
    				$body[$k][] = $row['total'];
    				$body[$k][] = $row['last_time'];
    			}
    		}
    		$this->exportExcel( $head, $body );
    	}
    	//get cached stores
    	$tags['active_top_tag'] = $this->active_top_tag;
    	$tags['active_menu_tag'] = 'invite_amount';
    	$this->_view('common/header', $tags);
    	$this->_view('stat/invite_amount', $data);
    	$this->_view('common/footer');
    }
    
    public function sale(){
    	$data = array();
    	//init parameters
    	$must = array();
    	$fields = array('act');
    	$this->checkParams('get',$must,$fields);
    	$params = $data['params'] = $this->params;
    	//get data
    	$data['results'] = array();
    	$condition = array();
    	$condition['AND'][] = 'u.status=1';
    	$condition['AND'][] = 'u.invite_eid>0';
    	$condition['AND'][] = 'o.order_status>=20';
    	$condition['AND'][] = 'e.is_del=0';
    	$table = 'users AS u INNER JOIN employees AS e ON e.id=u.invite_eid INNER JOIN orders AS o ON o.uid=u.id';
    	$column = 'u.id,e.username,e.id AS eid,o.price';
    	$res = $this->mBase->getList($table,$condition,$column);
    	if( $res ){
    		foreach ( $res as $row ){
    			if( isset($data['results'][$row['eid']]) ){
    				$data['results'][$row['eid']]['total'] ++;
    				$data['results'][$row['eid']]['price'] += $row['price'];
    			} else {
    				$data['results'][$row['eid']] = array(
    					'username'	=> $row['username'],
    					'total'		=> 1,
    					'price'		=> $row['price'],
    				);
    			}
    		}
    	}
    	//导出数据
    	if( $params['act']=='export' ){
    		$head = array('员工姓名','邀请用户数量','最新邀请成功时间');
    		$body = array();
    		if( $data['results'] ){
    			foreach ( $data['results'] as $k=>$row ){
    				$body[$k][] = $row['username'];
    				$body[$k][] = $row['total'];
    				$body[$k][] = $row['last_time'];
    			}
    		}
    		$this->exportExcel( $head, $body );
    	}
    	//get cached stores
    	$tags['active_top_tag'] = $this->active_top_tag;
    	$tags['active_menu_tag'] = 'invite_sale';
    	$this->_view('common/header', $tags);
    	$this->_view('stat/invite_sale', $data);
    	$this->_view('common/footer');
    }
}