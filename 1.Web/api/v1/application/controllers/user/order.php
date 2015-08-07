<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class order extends Base {
	
    public function __construct(){
        parent::__construct();
        $this->load->model('User_model','modUser');
    }
    
    public function lists(){
        $ret = array('err_no'=>1000,'err_msg'=>'system error');
        do{
        	$fields = array('pay_status','page','size');
        	$this->checkParams('post',array(),$fields);
        	$params = $this->params;
        	$page = max(1,intval($params['page']));
        	$size = max(10,min(100,intval($params['size'])));
        	//get user
        	$user = $this->modUser->getSingle('users_session','sessionid',$params['sessionid'],'uid');
        	if( !($user && $user['uid']) ){
        		$ret = array('err_no'=>3001,'err_msg'=>'user is not login');
        		break;
        	}
        	$uid = $user['uid'];
            //get user's orders
            $condition = array(
            	'AND' => array('uid='.$uid,'order_status<>10'),
            );
            if( $params['pay_status']!='' ){
            	$pay_status = intval($params['pay_status']);
            	$condition['AND'][] = 'pay_status='.$pay_status;
            }
            $res = $this->modUser->getList('orders',$condition,'*','create_time DESC',$page,$size);
            if( $res->results ){
            	foreach( $res->results as &$row ){
            		unset($row['uid']);
            		$row['order_status_des'] = '处理中';
            		if( $row['order_status']==0 ){
            			$row['order_status_des'] = '未支付';
            		} elseif ( $row['order_status']==20 ){
            			$row['order_status_des'] = '已完成';
            		}
            		$row['create_time'] = date('Y-m-d H:i:s',$row['create_time']);
            		$order_date_types = getConfig('order_date_types');
            		if( !array_key_exists($row['date_type'],$order_date_types) ){
            			$row['date_type'] = current($order_date_types);
            		} else {
            			if( $row['date_type']==4 ){
            				$row['date_type'] = $row['date_day'];
            			} else {
            				$row['date_type'] = $order_date_types[$row['date_type']];
            			}
            		}
            		if( $row['date_noon']==1 ){
            			$row['date_noon'] = '上午';
            		} elseif ( $row['date_noon']==2 ){
            			$row['date_noon'] = '下午';
            		} else {
            			$row['date_noon'] = '不限';
            		}
            		//get store name
            		$store_name = '';
            		if( $row['store_id'] ){
            			$store = $this->modUser->getSingle('stores','id',$row['store_id']);
            			if( $store ){
	            			$arr = array('prov','city','district');
	            			foreach( $arr as $v ){
	            				if( $store[$v] ){
	            					$cache_key = 'AREA_'.$store[$v];
	            					$t = Common_Cache::get($cache_key);
	            					if( !$t ){
	            						$t= $this->modUser->getSingle('areas','id',$store[$v]);
	            						Common_Cache::save($cache_key, $t, 86400);
	            					}
	            					$store_name .= $t ? $t['name'] : '';
	            				}
	            			}
	            			$store_name .= $store['address'];
	            			$store_name .= $store['name'];
            			}
            		}
            		$row['store_name'] = $store_name;
            		//get order details
            		$condition = array(
            			'AND' => array('order_id='.$row['id']),
            		);
            		$row['details'] = $this->modUser->getList('orders_detail',$condition,'*');
            		if( $row['details'] ){
            			foreach( $row['details'] as &$detail ){
            				$detail['create_time'] = date('Y-m-d H:i:s',$detail['create_time']);
            			}
            			unset($detail);
            		}
            		//get order actions
            		$condition = array(
            			'AND' => array('order_id='.$row['id'],'is_show=1'),
            		);
            		$row['actions'] = $this->modUser->getList('orders_action',$condition,'*','id DESC');
            		if( $row['actions'] ){
            			$t = getConfig('order_status_types');
            			foreach( $row['actions'] as &$action ){
            				$action = array(
            					'des'			=> (isset($t[$action['status']]) ? $t[$action['status']] : '').'，'.$action['des'],
            					'create_time'	=> date('Y-m-d H:i:s',$action['create_time']),
            				);
            			}
            			unset($action);
            		}
            	}
            	unset($row);
            }
            $ret = array(
            	'err_no'  =>0,
            	'err_msg' =>'success',
            	'results' => array(
            		'list'  => $res->results,
            		'pager' => $res->pager,
            	),
            );
        } while(0);
        $this->output($ret);
    }
    
    public function detail(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array('order_id');
    		$this->checkParams('post',$must);
    		$params = $this->params;
    		//get user
    		$user = $this->modUser->getSingle('users_session','sessionid',$params['sessionid'],'uid');
        	if( !($user && $user['uid']) ){
        		$ret = array('err_no'=>3001,'err_msg'=>'user is not login');
        		break;
        	}
        	$uid = $user['uid'];
        	//get order
        	$order = $this->modUser->getSingle('orders','id',$params['order_id']);
        	if( !$order ){
        		$ret = array('err_no'=>3002,'err_msg'=>'order is not exists');
        		break;
        	}
    		if( $order['uid']!=$uid ){
    			$ret = array('err_no'=>3003,'err_msg'=>'order and user not match');
    			break;
    		}
    		//get order details
    		$condition = array(
    			'AND' => array('order_id='.$params['order_id']),
    		);
    		$details = $this->modUser->getList('orders_detail',$condition,'*','id ASC');
    		if( !$details ){
    			$ret = array('err_no'=>3004,'err_msg'=>'order detail is empty');
    			break;
    		}
    		//get order action
    		$condition = array(
    			'AND' => array('order_id='.$params['order_id']),
    		);
    		$actions = $this->modUser->getList('orders_action',$condition,'*','id ASC');
    		//return data
    		foreach( $details as &$detail ){
    			$detail['create_time'] = date('Y-m-d H:i:s',$detail['create_time']);
    		}
    		unset($detail);
    		$order['details'] = $details;
    		if( $actions ){
    			foreach( $actions as &$action ){
    				$action['create_time'] = date('Y-m-d H:i:s',$action['create_time']);
    			}
    			unset($action);
    		}
    		$order['actions'] = $actions;
    		$order['create_time'] = date('Y-m-d H:i:s',$order['create_time']);
    		//get order date
    		$order_date_types = getConfig('order_date_types');
    		if( !array_key_exists($order['date_type'],$order_date_types) ){
    			$order['date_type'] = current($order_date_types);
    		} else {
    			$order['date_type'] = $order_date_types[$order['date_type']];
    		}
    		unset($order['date_day'],$order['date_noon']);
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>$order);
    	} while(0);
    	$this->output($ret);
    }
    
    public function cancel(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array('order_id');
    		$this->checkParams('post',$must);
    		$params = $this->params;
    		//get user
    		$user = $this->modUser->getSingle('users_session','sessionid',$params['sessionid'],'uid');
    		if( !($user && $user['uid']) ){
    			$ret = array('err_no'=>3001,'err_msg'=>'user is not login');
    			break;
    		}
    		$uid = $user['uid'];
    		//get order
    		$order = $this->modUser->getSingle('orders','id',$params['order_id']);
    		if( !$order ){
    			$ret = array('err_no'=>3002,'err_msg'=>'order is not exists');
    			break;
    		}
    		if( $order['uid']!=$uid ){
    			$ret = array('err_no'=>3003,'err_msg'=>'order and user not match');
    			break;
    		}
    		if( !in_array($order['order_status'],array(0,1,2)) ){
    			$ret = array('err_no'=>3004,'err_msg'=>'order can not be canceled');
    			break;
    		}
    		//update order
    		$data = array(
    			'order_status' => 11,
    		);
    		$rows = $this->modUser->update('orders',$data,array('id'=>$order['id']));
    		if( $rows ){
    			//rollback product stock
    			$this->load->model('Order_model','mOrder');
    			$condition = array(
    				'AND' => array('order_id='.$params['order_id']),
    			);
    			$details = $this->modUser->getList('orders_detail',$condition,'*','id ASC');
    			if( $details ){
    				foreach ( $details as $row ){
    					$this->mOrder->rollbackStock($row['product_id'],$order['site_id'],$row['amount']);
    				}
    			}
	    		//add order action
	    		$action = array(
	    			'order_id'		=> $order['id'],
	    			'status'		=> 11,
	    			'des'			=> '客户取消订单',
	    			'is_show'		=> 1,
	    			'create_eid'	=> 0,
	    			'create_name'	=> '客户',
	    			'create_time'	=> time(),
	    		);
	    		$this->modUser->insert('orders_action', $action);
    		}
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>array());
    	} while(0);
    	$this->output($ret);
    }
}