<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class main extends Base {

    public function __construct(){
        parent::__construct();
        $this->load->model('Product_model','mProduct');
    }
    
    public function lists(){
        $ret = array('err_no'=>1000,'err_msg'=>'system error');
        do{
        	//get parameters
        	$must = array();
        	$fields = array('category_id','price','orderby','sortby','page','size');
        	$this->checkParams('post',$must,$fields);
        	$params = $this->params;
        	$page = max(1,intval($params['page']));
        	$size = max(20,min(100,intval($params['size'])));
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
        	$res = $this->mProduct->getProductList($params['site_id'],$category_id,$price_min,$price_max,$orderby.' '.$sortby,$page,$size);
        	if( $res->results ){
    			$list = array();
    			foreach( $res->results as $row ){
    				$list[] = array(
    					'id'			=> $row['id'],
    					'category_id'	=> $row['category_id'],
    					'type'			=> $row['type'],
    					'title'			=> $row['title'],
    					'des'			=> $row['seo_title'],
    					'price'			=> $row['price'],
    					'price_market'	=> $row['price_market'],
    					'spec'			=> $row['spec'],
    					'book_date'		=> date('Y-m-d H:i:s',$row['book_time']),
    					'book_time'		=> $row['book_time'],
    					'thumb'			=> $row['thumb'],
    				);
    			}
    			$res->results = $list;
    		}
    		$ret = array(
    			'err_no'  =>0,
    			'err_msg' =>'success',
    			'results' => array(
    				'list'  => $res->results,
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
    		$product = $this->mBase->getSingle('products','id',$params['product_id']);
    		if( !$product || $product['is_del']==1 ){
    			$ret = array('err_no'=>3001,'err_msg'=>'product is not exists');
    			break;
    		}
    		//get product images
    		$condition = array(
    			'AND' => array('product_id='.$params['product_id']),
    		);
    		$imgs = $this->mBase->getList('products_img',$condition,'img,thumb','sort DESC');
    		//get brand
    		$brand = array(
    			'id' => 0,
    			'name' => $product['brand'],
    			'logo' => '',
    		);
    		//check user's fav status
    		$fav_id = '0';
    		if( strlen($this->params['sessionid'])==32 ){
    			//get user
    			$user = $this->mProduct->getSingle('users_session','sessionid',$params['sessionid'],'uid');
    			if( $user && $user['uid'] ){
    				$uid = $user['uid'];
    				$fav = $this->mProduct->getUserFav($uid,$this->params['product_id']);
    				if( $fav && $fav['is_del']==0 ){
    					$fav_id = $fav['id'];
    				}
    			}
    		}
    		//get product stock
    		$curr = array();
    		$condition = array(
    			'AND' => array('product_id='.$params['product_id'],'site_id='.$params['site_id']),
    		);
    		$res = $this->mBase->getList('products_site',$condition,'*');
    		if( $res ){
    			$curr = $res[0];
    		}
    		//get special
    		$special_id = 0;
    		$special = '';
    		//$condition = array(
    		//	'AND' => array('product_id='.$params['product_id'],'site_id='.$params['site_id']),
    		//);
    		//$res = $this->mBase->getList('products_site',$condition,'*');
    		//get promotion
    		$promotion = '';
    		$ret = array(
    			'err_no'  => 0,
    			'err_msg' => 'success',
    			'results' => array(
    				'id'			=> $product['id'],
    				'title'			=> $product['title'],
    				'des'			=> $product['seo_title'],
    				'type'			=> $product['type'],
    				'category_id'	=> $product['category_id'],
    				'content'		=> $product['content'],
    				'content_view'	=> 'http://www.100hl.com/goods/view/'.$product['id'],
    				'price'			=> $product['price'],
    				'price_market'	=> $product['price_market'],
    				'thumb'			=> $product['thumb'],
    				'spec'			=> $product['spec'],
    				'spec_packing'	=> $product['spec_packing'],
    				//'delivery_time'	=> '00:00—12:00下单，次日11:00前送达；12:00—24:00下单，次日18:00前送达。',
    				//'return_policy'	=> $product['return_policy'],
    				'fav'			=> $product['fav'],
    				'click'			=> $product['click'],
    				'sold'			=> $product['sold'],
    				'comment'		=> $product['comment'],
    				'book_date'		=> date('Y-m-d H:i:s',$product['book_time']),
    				'book_time'		=> $product['book_time'],
    				'brand'			=> $brand,
    				'imgs'			=> $imgs,
    				'fav_id'		=> strval($fav_id),
    				'is_sell'		=> $curr ? true : false,
    				'is_stock'		=> $curr && $curr['stock']!=0 ? true : false,
    				'special_id'	=> $special_id,
    				'special'		=> $special,
    				'promotion'		=> $promotion,
    			),
    		);
    		//update click count
    		$this->mProduct->setProductNum($params['product_id'],'click',1);
    	} while(0);
    	$this->output($ret);
    }
}