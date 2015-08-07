<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class process extends Base 
{
	private $active_top_tag = 'product';
	
	private $good_method_types = array();

	public function __construct(){
		parent::__construct();
		$this->good_method_types = getConfig('good_method_types');
	}

	public function index(){
		$data = array();
		//get stores
		$data['stores'] = $data['citys'] = array();
		$condition = array(
			'AND' => array('is_del=0','is_process=1'),
		);
		$res = $this->mBase->getList('stores',$condition);
		if( $res ){
			foreach( $res as $row ){
				$data['stores'][$row['city']][] = $row;
			}
			foreach ( $data['stores'] as $city_id=>$arr ){
				$prov = $this->mBase->getSingle('areas','id',$arr[0]['prov']);
				$city = $this->mBase->getSingle('areas','id',$city_id);
				$data['citys'][$city_id] = $prov['name'].' - '.$city['name'];
			}
		}
		//common data
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'good';
		$this->_view('common/header', $tags);
		$this->_view('product/process', $data);
		$this->_view('common/footer');
	}
	
	public function getProducts(){
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('store_id','good_id');
			$fields = array();
			$this->checkParams('get',$must,$fields);
			$params = $this->params;
			$store_id = intval($params['store_id']);
			$good_id = intval($params['good_id']);
			//get good
			$good = $this->mBase->getSingle('goods','id',$good_id);
			if( !$good ){
				$ret = array('err_no'=>1000,'err_msg'=>'所选原料不存在');
				break;
			}
			//get stock
			$stock = 0;
			$condition = array(
				'AND' => array('store_id='.$store_id,'good_id='.$good_id),
			);
			$res = $this->mBase->getList('goods_stock',$condition,'amount');
			if( $res ){
				$stock = $res[0]['amount'];
			}
			//get products
			$condition = array(
				'AND' => array('is_del=0','good_id='.$good_id),
			);
			$products = $this->mBase->getList('products',$condition,'id,title,sku,product_pin,spec,spec_packing,unit');
			if( !$products ){
				$ret = array('err_no'=>1000,'err_msg'=>'没有查找到和该原料关联的商品');
				break;
			}
			$ret = array(
				'err_no'	=> 0,
				'err_msg'	=> '操作成功',
				'results'	=> $products,
				'method'	=> isset($this->good_method_types[$good['method']]) ? $this->good_method_types[$good['method']] : '',
				'stock'		=> $stock,
			);
		} while(0);
		$this->output($ret);
	}
	
	public function doAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			$must = array('store_id','good_id','product_id','amount');
			$fields = array();
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$store_id = intval($params['store_id']);
			$good_id = intval($params['good_id']);
			//check store
			$store = $this->mBase->getSingle('stores','id',$store_id);
			if( !$store ){
				$ret = array('err_no'=>1000,'err_msg'=>'所选门店不存在');
				break;
			}
			//check good
			$good = $this->mBase->getSingle('goods','id',$good_id);
			if( !$good || $good['is_del']==1 ){
				$ret = array('err_no'=>1000,'err_msg'=>'所选原料不存在');
				break;
			}
			//get good stock in store
			$stock = 0;
			$condition = array(
				'AND' => array('store_id='.$store_id,'good_id='.$good_id),
			);
			$res = $this->mBase->getList('goods_stock',$condition,'amount');
			if( $res ){
				$stock = $res[0]['amount'];
			}
			if( $stock==0 ){
				$ret = array('err_no'=>1000,'err_msg'=>'所选原料在所选门店没有库存');
				break;
			}
			if( !is_array($params['product_id']) || count($params['product_id'])==0 ){
				$ret = array('err_no'=>1000,'err_msg'=>'请至少选择加工成一种商品');
				break;
			}
			if( !is_array($params['amount']) || count($params['amount'])==0 ){
				$ret = array('err_no'=>1000,'err_msg'=>'请至少选择加工成一种商品');
				break;
			}
			$count = count($params['product_id']);
			if( $count!=count($params['amount']) ){
				$ret = array('err_no'=>1000,'err_msg'=>'所选商品参数不合法');
				break;
			}
			$total = 0;
			$products = array();
			for( $i=0; $i<$count; $i++ ){
				$product_id = intval($params['product_id'][$i]);
				$amount = intval($params['amount'][$i]);
				if( $product_id && $amount ){
					$product = $this->mBase->getSingle('products','id',$product_id);
					if( $product ){
						$total += $product['good_num']*$amount;
						$product['amount'] = $amount;
						$products[] = $product; 
					}
				}
			}
			if( !($total && $products) ){
				$ret = array('err_no'=>1000,'err_msg'=>'请至少选择加工成一种商品');
				break;
			}
			if( $total>$stock ){
				$ret = array('err_no'=>1000,'err_msg'=>'要加工的商品超出所选原料在所选门店的总库存');
				break;
			}
			//decrease good stock and add log
			$this->load->model('Product_model', 'mProduct');
			$current = $this->mProduct->updateStock('good',$store_id,$good,0-$total);
			$data = array(
				'store_id'		=> $store_id,
				'good_id'		=> $good_id,
				'type'			=> 2,
				'change'		=> 0-$total,
				'current'		=> $current,
				'create_eid'	=> self::$user['id'],
				'create_name'	=> self::$user['username'],
				'create_time'	=> time(),
			);
			$this->mBase->insert('goods_stock_log', $data);
			//increase product stock and add log
			foreach ( $products as $product ){
				$current = $this->mProduct->updateStock('product',$store_id,$product,$product['amount']);
				$data = array(
					'store_id'		=> $store_id,
					'product_id'	=> $product['id'],
					'type'			=> 2,
					'change'		=> $product['amount'],
					'current'		=> $current,
					'create_eid'	=> self::$user['id'],
					'create_name'	=> self::$user['username'],
					'create_time'	=> time(),
				);
				$this->mBase->insert('products_stock_log', $data);
			}
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
}
