<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class product extends Base 
{
	private $active_top_tag = 'vip';
	
	//Common Category
	public $categorys = array();
	
	//Common Brands
	public $brands = array();
	
	//Common Specs
	public $specs = array();

	public function __construct(){
		parent::__construct();
		$res = $this->mBase->getList('vip_category',array('AND'=>array('is_del=0')));
		if( $res ){
			foreach ( $res as $row ){
				$this->categorys[$row['id']] = $row;
			}
		}
		$res = $this->mBase->getList('brands');
		if( $res ){
			foreach ( $res as $row ){
				$this->brands[$row['id']] = $row;
			}
		}
		$res = $this->mBase->getList('products_spec');
		if( $res ){
			foreach ( $res as $row ){
				$this->specs[$row['id']] = $row;
			}
		}
	}

	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('category_id','keyword','page','size');
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		//get data
		$condition = array(
			'AND' => array('is_del=0'),
		);
		if( $params['category_id'] ){
			$condition['AND'][] = 'category_id='.$params['category_id'];
		}
		$k = $params['keyword'];
		if( $k ){
			$condition['AND'][] = "title like '%".$k."%'";
		}
		$res = $this->mBase->getList('vip_products',$condition,'*','id DESC',$page,$size);
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//common data
		$data['categorys'] = $this->categorys;
		$data['brands'] = $this->brands;
		$data['specs'] = $this->specs;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'product';
		$this->_view('common/header', $tags);
		$this->_view('vip/product_list', $data);
		$this->_view('common/footer');
	}
	
	public function add(){
		$data = array();
		//common data
		$data['categorys'] = $this->categorys;
		$data['brands'] = $this->brands;
		$data['specs'] = $this->specs;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'product_add';
		$this->_view('common/header', $tags);
		$this->_view('vip/product_add', $data);
		$this->_view('common/footer');
	}
	
	public function edit(){
		$data = array();
		//get parameters
		$must = array('id');
		$fields = array();
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$id = intval($params['id']);
		//get product
		$data['single'] = $this->mBase->getSingle('vip_products','id',$id);
		//get product images
		$data['products_img'] = $this->mBase->getList('vip_products_img',array('AND'=>array("product_id='{$id}'")));
		//common data
		$data['categorys'] = $this->categorys;
		$data['brands'] = $this->brands;
		$data['specs'] = $this->specs;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'product_add';
		$this->_view('common/header', $tags);
		$this->_view('vip/product_edit', $data);
		$this->_view('common/footer');
	}
	
	public function doAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			//sku: 唯一产品编号
			//images: 产品多图数组，至少应包含一个
			//thumbs: 产品多图缩略图数组，至少应包含一个，数量和images相同
			//sorts: 产品多图排序数组，至少应包含一个，数量和images相同
			$must = array('sku','category_id','title','price','thumb','spec','spec_packing','unit','images','thumbs','sorts');
			$fields = array('brand_id','content','seo_title','seo_keywords','seo_description');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			//check parameters
			$t = $this->mBase->getSingle('vip_products','sku',$params['sku']);
			if( $t ){
				$ret = array('err_no'=>1000,'err_msg'=>'商品编号已经存在');
				break;
			}
			$category = $this->mBase->getSingle('vip_category','id',$params['category_id']);
			if( !$category ){
				$ret = array('err_no'=>1000,'err_msg'=>'分类已经存在');
				break;
			}
			if( !($params['images'] && is_array($params['images'])) || !($params['thumbs'] && is_array($params['thumbs'])) ){
				$ret = array('err_no'=>1000,'err_msg'=>'至少应该上传一张商品图片');
				break;
			}
			if( count($params['images'])!==count($params['thumbs']) ){
				$ret = array('err_no'=>1000,'err_msg'=>'商品图片信息错误，图片数量和缩略图数量不匹配');
				break;
			}
			//insert product
			$data = array(
				'sku'				=> $params['sku'],
				'category_id'		=> $params['category_id'],
				'brand_id'			=> $params['brand_id'],
				'title'				=> $params['title'],
				'content'			=> $params['content'],
				'price'				=> $params['price'],
				'thumb'				=> $params['thumb'],
				'spec'				=> $params['spec'],
				'spec_packing'		=> $params['spec_packing'],
				'unit'				=> $params['unit'],
				'seo_title'			=> $params['seo_title'],
				'seo_keywords'		=> $params['seo_keywords'],
				'seo_description'	=> $params['seo_description'],
				'create_eid'		=> parent::$user['id'],
				'create_name'		=> parent::$user['username'],
				'create_time'		=> time(),
			);
			$single = $this->mBase->insert('vip_products',$data);
			//insert products img
			if( $single ){
				$product_id = $single['id'];
				$data = array();
				$length = count($params['images']);
				for( $i=0; $i<$length; $i++ ){
					$data[] = array(
						'product_id'	=> $product_id,
						'img'			=> isset($params['images'][$i]) ? $params['images'][$i] : '',
						'thumb'			=> isset($params['thumbs'][$i]) ? $params['thumbs'][$i] : '',
						'sort'			=> isset($params['sorts'][$i]) ? $params['sorts'][$i] : '',
					);
				}
				$this->mBase->insertMulti('vip_products_img',$data);
			}
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function doEdit() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			//sku: 唯一产品编号
			//images: 产品多图数组，至少应包含一个
			//thumbs: 产品多图缩略图数组，至少应包含一个，数量和images相同
			//sorts: 产品多图排序数组，至少应包含一个，数量和images相同
			$must = array('id','sku','category_id','title','price','thumb','spec','spec_packing','unit','images','thumbs','sorts');
			$fields = array('brand_id','content','seo_title','seo_keywords','seo_description');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$id = intval($params['id']);
			//check parameters
			$product = $this->mBase->getSingle('vip_products','id',$id);
			if( !$product ){
				$ret = array('err_no'=>1000,'err_msg'=>'商品不存在，请刷新重试！');
				break;
			}
			$t = $this->mBase->getSingle('vip_products','sku',$params['sku']);
			if( $t && $t['id']!=$id ){
				$ret = array('err_no'=>1000,'err_msg'=>'商品编号已经存在');
				break;
			}
			$category = $this->mBase->getSingle('vip_category','id',$params['category_id']);
			if( !$category ){
				$ret = array('err_no'=>1000,'err_msg'=>'分类已经存在');
				break;
			}
			if( !($params['images'] && is_array($params['images'])) || !($params['thumbs'] && is_array($params['thumbs'])) ){
				$ret = array('err_no'=>1000,'err_msg'=>'至少应该上传一张商品图片');
				break;
			}
			if( count($params['images'])!==count($params['thumbs']) ){
				$ret = array('err_no'=>1000,'err_msg'=>'商品图片信息错误，图片数量和缩略图数量不匹配');
				break;
			}
			//update product
			$data = array(
				'sku'				=> $params['sku'],
				'category_id'		=> intval($params['category_id']),
				'brand_id'			=> intval($params['brand_id']),
				'title'				=> $params['title'],
				'content'			=> $params['content'],
				'price'				=> $params['price'],
				'thumb'				=> $params['thumb'],
				'spec'				=> intval($params['spec']),
				'spec_packing'		=> intval($params['spec_packing']),
				'unit'				=> $params['unit'],
				'seo_title'			=> $params['seo_title'],
				'seo_keywords'		=> $params['seo_keywords'],
				'seo_description'	=> $params['seo_description'],
				'modify_eid'		=> parent::$user['id'],
				'modify_name'		=> parent::$user['username'],
				'modify_time'		=> time(),
			);
			$this->mBase->update('vip_products',$data,array('id'=>$id));
			//insert products img
			$this->mBase->delete('vip_products_img',array('product_id'=>$id),true);
			$data = array();
			$length = count($params['images']);
			for( $i=0; $i<$length; $i++ ){
				$data[] = array(
					'product_id'	=> $id,
					'img'			=> isset($params['images'][$i]) ? $params['images'][$i] : '',
					'thumb'			=> isset($params['thumbs'][$i]) ? $params['thumbs'][$i] : '',
					'sort'			=> isset($params['sorts'][$i]) ? $params['sorts'][$i] : '',
				);
			}
			$this->mBase->insertMulti('vip_products_img',$data);
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}

	public function delete()
	{
		$product_id = $this->input->get('product_id');
		if(empty($product_id)){
			die('{"error":1, "msg": "信息错误，请刷新页面重试！"}');
		}
		$delete_rst = $this->mBase->update('vip_products', array('is_del'=>1),array('id' => $product_id));
		die('{"error":0, "msg": ""}');
	}
}
