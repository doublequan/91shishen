<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class search extends Base {

    public function __construct(){
        parent::__construct();
        $this->load->model('Product_model','mProduct');
    }
    
    public function index(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array('keyword');
    		$fields = array('category_id','price','orderby','sortby','page','size');
    		$this->checkParams('post',$must,$fields);
    		$params = $this->params;
    		$page = max(1,intval($params['page']));
    		$size = max(20,min(100,intval($params['size'])));
    		//get site_id
    		$site_id = intval($params['site_id']);
    		//get keyword
    		$keyword = $params['keyword'];
    		//get category_id
        	$category_id = intval($params['category_id']);
        	//get price
        	$price_min = $price_max = 0;
        	if( $params['price'] ){
        		$arr = explode(',', $params['price']);
        		$price_min = isset($arr[0]) ? floatval($arr[0]) : 0;
        		$price_max = isset($arr[1]) ? floatval($arr[1]) : 0;
        	}
        	//get sort
        	$orderby = intval($params['orderby']);
        	$sortby = intval($params['sortby']);
        	$orderByMap = array(
        		'0' => 'id',
        		'1' => 'create_time',
        		'2' => 'sold',
        		'3' => 'click',
        		'4' => 'comment',
        		'5' => 'fav',
        	);
        	$sortByMap = array(
        		'-1' => 'DESC',
        		'1'  => 'ASC',
        	);
        	$orderby = array_key_exists($orderby, $orderByMap) ? $orderByMap[$orderby] : 'create_time';
        	$sortby = array_key_exists($sortby, $sortByMap) ? $sortByMap[$sortby] : 'DESC';
    		$res = $this->mProduct->getSearchResults($site_id,$keyword,$category_id,$price_min,$price_max,$orderby.' '.$sortby,$page,$size);
    		$list = array();
    		if( $res->results ){
    			foreach( $res->results as $row ){
    				//$condition['AND'] = array('product_id='.$row['id']);
    				//$imgs = $this->mProduct->getList('products_img',$condition);
    				$list[] = array(
    					'id'			=> $row['id'],
    					'category_id'	=> $row['category_id'],
    					'type'			=> $row['type'],
    					'title'			=> $row['title'],
    					'price'			=> $row['price'],
    					'price_market'	=> $row['price_market'],
    					'spec'			=> $row['spec'],
    					//'spec_packing'	=> $row['spec_packing'],
    					//'delivery_time'	=> $row['delivery_time'],
    					//'return_policy'	=> $row['return_policy'],
    					//'fav'			=> $row['fav'],
    					//'click'			=> $row['click'],
    					//'sold'			=> $row['sold'],
    					//'comment'		=> $row['comment'],
    					'book_date'		=> date('Y-m-d H:i:s',$row['book_time']),
    					'book_time'		=> $row['book_time'],
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
    
    public function rand(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array();
    		$this->checkParams('post',$must);
    		$params = $this->params;
    		//check site_id
    		$site_id = intval($params['site_id']);
    		$site = $this->mProduct->getSingle('sites','id',$site_id);
    		if( !$site ){
    			$ret = array('err_no'=>3001,'err_msg'=>'site is not exists');
    			break;
    		}
    		//get random product
    		$product = $this->mProduct->getRandProduct($site_id);
    		if( !$product ){
    			$ret = array('err_no'=>3002,'err_msg'=>'get rand product failure');
    			break;
    		}
    		//get product images
    		$condition = array(
    			'AND' => array('product_id='.$product['id']),
    		);
    		$imgs = $this->mProduct->getList('products_img',$condition,'img,thumb','sort DESC');
    		//get brand
    		$brand = array(
    			'id' => 0,
    			'name' => $product['brand'],
    			'logo' => '',
    		);
    		$ret = array(
    			'err_no'  => 0,
    			'err_msg' => 'success',
    			'results' => array(
    				'id'			=> $product['id'],
    				'title'			=> $product['title'],
    				'type'			=> $product['type'],
    				'category_id'	=> $product['category_id'],
    				'content'		=> $product['content'],
    				'price'			=> $product['price'],
    				'price_market'	=> $product['price_market'],
    				'thumb'			=> $product['thumb'],
    				'spec'			=> $product['spec'],
    				'spec_packing'	=> $product['spec_packing'],
    				//'delivery_time'	=> $product['delivery_time'],
    				//'return_policy'	=> $product['return_policy'],
    				'fav'			=> $product['fav'],
    				'click'			=> $product['click'],
    				'sold'			=> $product['sold'],
    				'comment'		=> $product['comment'],
    				'book_date'		=> date('Y-m-d H:i:s',$product['book_time']),
    				'book_time'		=> $product['book_time'],
    				'brand'			=> $brand,
    				'imgs'			=> $imgs,
    			),
    		);
    		//update click count
    		$this->mProduct->setProductNum($product['id'],'click',1);
    	} while(0);
    	$this->output($ret);
    }
}