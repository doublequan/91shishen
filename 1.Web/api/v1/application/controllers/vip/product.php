<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class product extends Base {
	
	public $table = 'vip_products';

    public function __construct(){
        parent::__construct();
        $this->load->model('Product_model','mProduct');
    }
    
    public function lists(){
        $ret = array('err_no'=>1000,'err_msg'=>'system error');
        do{
        	//get parameters
        	$must = array();
        	$fields = array('category_id','price_min','price_max','page','size');
        	$this->checkParams('post',$must,$fields);
        	$params = $this->params;
        	$page = max(1,intval($params['page']));
        	$size = max(10,min(100,intval($params['size'])));
        	//init condition
        	$condition['AND'] = array('is_del=0');
        	//if cate_id exists
        	if( $params['category_id'] ){
        		$condition['AND'][] = 'category_id='.$params['category_id'];
        	}
        	//if price exists
        	if( $params['price_min'] ){
        		$condition['AND'][] = 'price>='.$params['price_min'];
        	}
        	if( $params['price_max'] ){
        		$condition['AND'][] = 'price<='.$params['price_max'];
        	}
    		$res = $this->mProduct->getList($this->table,$condition,'*','id DESC',$page,$size);
    		$list = array();
    		if( $res->results ){
    			//get all brand
    			$mapBrand = array();
    			$mapArr = $this->mProduct->getList('brands');
    			if( $mapArr ){
    				foreach ( $mapArr as $row ){
    					$mapBrand[$row['id']] = $row;
    				}
    			}
    			foreach( $res->results as $row ){
    				$brand = isset($mapBrand[$row['brand_id']]) ? $mapBrand[$row['brand_id']] : array();
    				$list[] = array(
    					'id'			=> $row['id'],
    					'category_id'	=> $row['category_id'],
    					'brand_id'		=> $row['brand_id'],
    					'brand_name'	=> $brand ? $brand['name'] : '',
    					'brand_logo'	=> $brand ? $brand['logo'] : '',
    					'title'			=> $row['title'],
    					'price'			=> $row['price'],
    					'spec'			=> $row['spec'],
    					'spec_packing'	=> $row['spec_packing'],
    					'unit'			=> $row['unit'],
    					'thumb'			=> $row['thumb'],
    				);
    			}
    		}
    		$ret = array(
    			'err_no'  =>0,
    			'err_msg' =>'success',
    			'results' => array(
    				'list'  => $list,
    				'pager'	=> $res->pager,
    			),
    		);
        } while(0);
        $this->output($ret);
    }
    
    public function detail(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
        	$must = array('product_id');
        	$this->checkParams('post',$must);
        	$params = $this->params;
    		$product = $this->mProduct->getSingle($this->table,'id',$params['product_id']);
    		if( !$product || $product['is_del']==1 || $product['is_sell']==0 ){
    			$ret = array('err_no'=>3001,'err_msg'=>'product is not exists');
    			break;
    		}
    		//get product images
    		$condition = array(
    			'AND' => array('product_id='.$params['product_id']),
    		);
    		$imgs = $this->mProduct->getList('vip_products_img',$condition,'img,thumb','sort DESC');
    		//get brand
    		$brand = array(
    			'id' => 0,
    			'name' => '',
    			'logo' => '',
    		);
    		if( $product['brand_id'] ){
    			$t = $this->mProduct->getSingle('brands','id',$product['brand_id']);
    			if( $t ){
    				$brand = array(
    					'id'	=> $t['id'],
    					'name'	=> $t['name'],
    					'logo'	=> $t['logo'],
    				);
    			}
    		}
    		$ret = array(
    			'err_no'  => 0,
    			'err_msg' => 'success',
    			'results' => array(
    				'id'			=> $product['id'],
    				'title'			=> $product['title'],
    				'category_id'	=> $product['category_id'],
    				'content'		=> $product['content'],
    				'price'			=> $product['price'],
    				'thumb'			=> $product['thumb'],
    				'spec'			=> $product['spec'],
    				'spec_packing'	=> $product['spec_packing'],
    				'unit'			=> $product['unit'],
    				'brand'			=> $brand,
    				'imgs'			=> $imgs,
    			),
    		);
    	} while(0);
    	$this->output($ret);
    }
    
    public function price(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array('product_id');
    		$fields = array('day_start','day_end','page','size');
    		$this->checkParams('post',$must,$fields);
    		$params = $this->params;
    		$page = max(1,intval($params['page']));
    		$size = max(10,min(100,intval($params['size'])));
    		//check product
    		$product = $this->mProduct->getSingle($this->table,'id',$params['product_id']);
    		if( !$product || $product['is_del']==1 ){
    			$ret = array('err_no'=>3001,'err_msg'=>'product is not exists');
    			break;
    		}
    		//check date
    		$day_start = strtotime($params['day_start']);
    		$day_end = strtotime($params['day_end']);
    		if( $day_start && $day_end && $day_start>$day_end ){
    			$ret = array('err_no'=>3002,'err_msg'=>'day end earlier than day start');
    			break;
    		}
    		//get product price history
    		$condition = array(
    			'AND' => array('product_id='.$params['product_id']),
    		);
    		if( $day_start ){
    			$day_start = date('Y-m-d',$day_start);
    			$condition['AND'][] = "day>='".$day_start."'";
    		}
    		if( $day_end ){
    			$day_end = date('Y-m-d',$day_end);
    			$condition['AND'][] = "day<='".$day_end."'";
    		}
    		$res = $this->mProduct->getList('vip_products_price',$condition,'*','day DESC',$page,$size);
    		$list = array();
    		if( $res->results ){
    			foreach( $res->results as $row ){
    				$list[] = array(
    					'day'			=> $row['day'],
    					'price'			=> $row['price'],
    					'create_time'	=> date('Y-m-d H:i:s',$row['create_time']),
    				);
    			}
    		}
    		$ret = array(
    			'err_no'  =>0,
    			'err_msg' =>'success',
    			'results' => array(
    				'list'  => $list,
    				'pager'	=> $res->pager,
    			),
    		);
    	} while(0);
    	$this->output($ret);
    }
}