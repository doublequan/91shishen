<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class comment extends Base {
	
    public function __construct(){
        parent::__construct();
        $this->load->model('Product_model','mProduct');
    }
        
    public function lists(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array('product_id');
    		$fields = array('level','page','size');
    		$this->checkParams('post',$must,$fields);
    		$params = $this->params;
    		$page = max(1,intval($params['page']));
    		$size = max(1,min(100,intval($params['size'])));
    		//check product
    		$product = $this->mProduct->getSingle('products','id',$params['product_id']);
        	if( !$product ){
        		$ret = array('err_no'=>3001,'err_msg'=>'product is not exists');
        		break;
        	}
        	//get list
        	$num = array(
        		1 => 0,
        		2 => 0,
        		3 => 0,
        	);
        	$condition = array(
        		'AND' => array('product_id='.$params['product_id'],'status=1')
        	);
        	if( $params['level']>0 ){
        		$condition['AND'][] = 'level='.$params['level'];
        	}
        	$res = $this->mProduct->getList('products_comment',$condition,'*','create_time DESC',$page,$size);
        	if( $res->results ){
        		foreach( $res->results as &$row ){
        			unset($row['uid'],$row['order_id'],$row['product_id'],$row['status']);
        			$row['buy_time'] = date('Y-m-d',$row['buy_time']);
        			$row['create_time'] = date('Y-m-d',$row['create_time']);
        		}
        		unset($row);
        		//get each level's comment number
        		for( $i=1; $i<=3; $i++ ){
        			$condition = array(
        				'AND' => array('product_id='.$params['product_id'],'status=1','level='.$i)
        			);
        			$num[$i] = $this->mProduct->getCount('products_comment',$condition);
        		}
        	}
        	$stat = array(
        		1 => '0%',
        		2 => '0%',
        		3 => '0%',
        	);
        	if( $num ){
        		$total = array_sum($num);
        		if( $total>0 ){
	        		for( $i=1; $i<=3; $i++ ){
	        			$stat[$i] = (round($num[$i]/$total,2)*100).'%';
	        		}
        		}
        	} else {
        		for( $i=1; $i<=3; $i++ ){
        			$stat[$i] = '0%';
        		}
        	}
    		$ret = array(
    			'err_no'  =>0,
    			'err_msg' =>'success',
    			'results' => array(
    				'num'	=> $num,
    				'stat'	=> $stat,
    				'list'  => $res->results,
    				'pager'	=> $res->pager,
    			),
    		);
    	} while(0);
    	$this->output($ret);
    }
    
    public function add(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array('order_id','product_id','content','level','score');
    		$this->checkParams('post',$must);
    		$params = $this->params;
    		$params['level'] = intval($params['level']);
    		$params['level'] = in_array($params['level'],array(1,2,3)) ? $params['level'] : 1;
    		$params['score'] = intval($params['score']);
    		$params['score'] = in_array($params['score'],array(1,2,3,4,5)) ? $params['score'] : 1;
    		//check user
    		$user = $this->mProduct->getSingle('users_session','sessionid',$params['sessionid'],'uid');
    		if( !($user && $user['uid']) ){
    			$ret = array('err_no'=>3001,'err_msg'=>'user is not login');
    			break;
    		}
    		//get user
    		$user = $this->mProduct->getSingle('users','id',$user['uid'],'id,username');
    		if( !$user ){
    			$ret = array('err_no'=>3002,'err_msg'=>'user is not exists');
    			break;
    		}
    		//check order
    		$condition = array(
    			'AND' => array('order_id='.$params['order_id'])
    		);
    		$details = array();
    		$res = $this->mProduct->getList('orders_detail',$condition,'id,uid,product_id,is_comment,create_time');
    		if( $res ){
    			foreach( $res as $row ){
    				$product_id = intval($row['product_id']);
    				if( $product_id ){
    					$details[$product_id] = $row;
    				}
    			}
    		}
    		if( !$details ){
    			$ret = array('err_no'=>3003,'err_msg'=>'order is not exists');
    			break;
    		}
    		if( !array_key_exists($params['product_id'], $details) ){
    			$ret = array('err_no'=>3004,'err_msg'=>'order and product not match');
    			break;
    		}
    		/**
    		if( $details[$params['product_id']]['uid']!=$user['id'] ){
    			$ret = array('err_no'=>3005,'err_msg'=>'order and user not match');
    			break;
    		}
    		*/
    		if( $details[$params['product_id']]['is_comment']==1 ){
    			$ret = array('err_no'=>3006,'err_msg'=>'comment is exists');
    			break;
    		}
    		//check product
    		$product = $this->mProduct->getSingle('products','id',$params['product_id']);
    		if( !$product ){
    			$ret = array('err_no'=>3007,'err_msg'=>'product is not exists');
    			break;
    		}
    		//add comment and modify order_detail comment status
    		$data = array(
    			'site_id'		=> $product['site_id'],
    			'uid'			=> $user['id'],
    			'username'		=> $user['username'],
    			'order_id'		=> $params['order_id'],
    			'product_id'	=> $params['product_id'],
    			'content'		=> $params['content'],
    			'level'			=> $params['level'],
    			'score'			=> $params['score'],
    			'status'		=> 1,
    			'buy_time'		=> $details[$params['product_id']]['create_time'],
    			'create_time'	=> time(),
    		);
    		$single = $this->mProduct->insert('products_comment',$data);
    		if( !$single ){
    			$ret = array('err_no'=>3008,'err_msg'=>'comment add failure');
    			break;
    		}
    		$data = array('is_comment'=>1);
    		$whereMap = array('id'=>$details[$params['product_id']]['id']);
    		$this->mProduct->update('orders_detail',$data,$whereMap);
    		$ret = array(
    			'err_no'  =>0,
    			'err_msg' =>'success',
    			'results' => array(
    				'id'			=> $single['id'],
    				'username'		=> $single['username'],
    				'content'		=> $single['content'],
    				'level'			=> $single['level'],
    				'score'			=> $single['score'],
    				'buy_time'		=> date('Y-m-d',$single['buy_time']),
    				'create_time'	=> date('Y-m-d',$single['create_time']),
    			),
    		);
    	} while(0);
    	$this->output($ret);
    }
}