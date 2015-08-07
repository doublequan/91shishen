<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class product extends Base 
{
	private $active_top_tag = 'product';

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('category_id','search','page','size');
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(15,min(100,intval($params['size'])));
		//get current site's category
		$res = array();
		$condition = array(
			'AND' => array('is_del=0'),
		);
		$res = $this->mBase->getList('goods_category',$condition);
		if( $res ){
			foreach ( $res as $row ){
				$data['category_map'][$row['id']] = $row;
			}
		}
		$res = getTreeFromArray($res);
		$data['category_list'] = $res;
		
		//get products
		$data['goods'] = array();
		$condition = array(
			'AND' => array('is_del=0'),
		);
		if( $params['category_id'] ){
			$condition['AND'][] = 'category_id='.$params['category_id'];
		}
		if( $params['search'] ){
			$condition['OR'][0] = array(
				"title LIKE '%".$params['search']."%'",
				"sku LIKE '%".$params['search']."%'",
				"product_pin LIKE '%".$params['search']."%'",
			);
		}
		$res = $this->mBase->getList('products',$condition,'*','id DESC',$page,$size);
		if( $res->results ){
			foreach ( $res->results as &$row ){
				$condition = array(
					'AND' => array('product_id='.$row['id']),
				);
				$row['siteNum'] = $this->mBase->getCount('products_site',$condition);
				//get good
				if( !isset($data['goods'][$row['good_id']]) ){
					$good = $this->mBase->getSingle('goods','id',$row['good_id']);
					if( $good ){
						$data['goods'][$row['good_id']] = $good;
					}
				}
			}
			unset($row);
		}
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;

		//common data
		$data['good_method_types'] = getConfig('good_method_types');
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'product';
		$this->_view('common/header', $tags);
		$this->_view('product/product_list', $data);
		$this->_view('common/footer');
	}
	
	public function add(){
		$data = array();
		//get current site's category
		$res = array();
		$condition = array(
			'AND' => array('is_del=0'),
		);
		$res = $this->mBase->getList('goods_category',$condition);
		$res = getTreeFromArray($res);
		$data['category_list'] = $res;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'product_add';
		$this->_view('common/header', $tags);
		$this->_view('product/product_add', $data);
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
		$data['single'] = $this->mBase->getSingle('products','id',$id);
		
		//get good info
		$good_id = intval($data['single']['good_id']);
		$data['good_info'] = $this->mBase->getSingle('goods','id',$good_id);

		//get product images
		$data['products_img'] = $this->mBase->getList('products_img',array('AND'=>array("product_id='{$id}'")));

		//get current site's category
		$res = array();
		$condition = array(
			'AND' => array('is_del=0'),
		);
		$res = $this->mBase->getList('goods_category',$condition);
		$res = getTreeFromArray($res);
		$data['category_list'] = $res;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'product_add';
		$this->_view('common/header', $tags);
		$this->_view('product/product_edit', $data);
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
			$must = array('sku','product_pin','good_id','good_num','category_id','type','title','price','price_market','thumb','spec','spec_packing','unit','images','thumbs','sorts');
			$fields = array('delivery','brand','integral','content','seo_title','seo_keywords','seo_description','product_place','product_level','loss_set','loss_stat');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			//check parameters
			$t = $this->mBase->getSingle('products','sku',$params['sku']);
			if( $t ){
				$ret = array('err_no'=>1000,'err_msg'=>'商品编号已经存在');
				break;
			}
			$t = $this->mBase->getSingle('products','product_pin',$params['product_pin']);
			if( $t ){
				$ret = array('err_no'=>1000,'err_msg'=>'商品货号已经存在');
				break;
			}
			$category = $this->mBase->getSingle('goods_category','id',$params['category_id']);
			if( !$category ){
				$ret = array('err_no'=>1000,'err_msg'=>'分类不存在');
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
				'product_pin'		=> $params['product_pin'],
				'category_id'		=> $params['category_id'],
				'brand'				=> $params['brand'],
				'good_id'			=> $params['good_id'],
				'good_num'			=> floatval($params['good_num']),
				'loss_set'			=> max(0,min(100,intval($params['loss_set']))),
				'loss_stat'			=> max(0,min(100,intval($params['loss_stat']))),
				'type'				=> $params['type'],
				'delivery'			=> $params['delivery'],
				'title'				=> $params['title'],
				'content'			=> addslashes($_POST['content']),
				'price'				=> $params['price'],
				'price_market'		=> $params['price_market'],
                'integral'          => $params['integral'],
				'thumb'				=> $params['thumb'],
				'spec'				=> $params['spec'],
				'spec_packing'		=> $params['spec_packing'],
				'unit'				=> $params['unit'],
				'product_place'		=> $params['product_place'],
				'product_level'		=> $params['product_level'],
				'seo_title'			=> $params['seo_title'],
				'seo_keywords'		=> $params['seo_keywords'],
				'seo_description'	=> $params['seo_description'],
				'create_time'		=> time(),
			);
			$single = $this->mBase->insert('products',$data);
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
				$this->mBase->insertMulti('products_img',$data);
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
			$must = array('id','sku','product_pin','good_id','good_num','category_id','type','title','price','price_market','thumb','spec','spec_packing','unit','images','thumbs','sorts');
			$fields = array('delivery','brand','integral','content','seo_title','seo_keywords','seo_description','product_place','product_level','loss_set','loss_stat');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$id = intval($params['id']);
			//check parameters
			$product = $this->mBase->getSingle('products','id',$id);
			if( !$product ){
				$ret = array('err_no'=>1000,'err_msg'=>'商品不存在');
				break;
			}
			if( $product['sku']!=$params['sku'] ){
				$t = $this->mBase->getSingle('products','sku',$params['sku']);
				if( $t ){
					$ret = array('err_no'=>1000,'err_msg'=>'商品编号已经存在');
					break;
				}
			}
			if( $product['product_pin']!=$params['product_pin'] ){
				$t = $this->mBase->getSingle('products','product_pin',$params['product_pin']);
				if( $t ){
					$ret = array('err_no'=>1000,'err_msg'=>'商品货号已经存在');
					break;
				}
			}
			$category = $this->mBase->getSingle('goods_category','id',$params['category_id']);
			if( !$category ){
				$ret = array('err_no'=>1000,'err_msg'=>'分类不存在');
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
				'product_pin'		=> $params['product_pin'],
				'category_id'		=> $params['category_id'],
				'brand'				=> $params['brand'],
				'good_id'			=> $params['good_id'],
				'good_num'			=> floatval($params['good_num']),
				'loss_set'			=> max(0,min(100,intval($params['loss_set']))),
				'loss_stat'			=> max(0,min(100,intval($params['loss_stat']))),
				'type'				=> $params['type'],
				'delivery'			=> $params['delivery'],
				'title'				=> $params['title'],
				'content'			=> addslashes($_POST['content']),
				'price'				=> $params['price'],
				'price_market'		=> $params['price_market'],
                'integral'          => $params['integral'],
				'thumb'				=> $params['thumb'],
				'spec'				=> $params['spec'],
				'spec_packing'		=> $params['spec_packing'],
				'unit'				=> $params['unit'],
				'product_place'		=> $params['product_place'],
				'product_level'		=> $params['product_level'],
				'seo_title'			=> $params['seo_title'],
				'seo_keywords'		=> $params['seo_keywords'],
				'seo_description'	=> $params['seo_description'],
			);
			$this->mBase->update('products',$data,array('id'=>$id));
			//insert products img
			$this->mBase->delete('products_img',array('product_id'=>$id),true);
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
			$this->mBase->insertMulti('products_img',$data);
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function delete(){
		$product_id = $this->input->get('product_id');
		if(empty($product_id)){
			die('{"error":1, "msg": "信息错误，请刷新页面重试！"}');
		}
		$delete_rst = $this->mBase->update('products', array('is_del'=>1),array('id' => $product_id));
		die('{"error":0, "msg": ""}');
	}
	
	public function sale(){
		$data = array();
		//get parameters
		$must = array('id');
		$fields = array();
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$id = intval($params['id']);
		//get product
		$data['single'] = $this->mBase->getSingle('products','id',$id);
		//get current sites
		$data['curr'] = array();
		$condition = array(
			'AND' => array('product_id='.$id),
		);
		$res = $this->mBase->getList('products_site',$condition);
		foreach( $res as $row ) {
			$data['curr'][$row['site_id']] = $row;
		}
		//get all sites
		$condition = array(
			'AND' => array('is_del=0','is_off=0'),
		);
		$data['sites'] = $this->mBase->getList('sites',$condition);
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'product';
		$this->_view('common/header', $tags);
		$this->_view('product/product_sale', $data);
		$this->_view('common/footer');
	}
	
	public function doSale() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			$must = array('product_id');
			$fields = array('sites','price');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$product_id = intval($params['product_id']);
			//check parameters
			$product = $this->mBase->getSingle('products','id',$product_id);
			if( !$product ){
				$ret = array('err_no'=>1000,'err_msg'=>'商品不存在');
				break;
			}
			if( $product['is_del']==1 ){
				$ret = array('err_no'=>1000,'err_msg'=>'商品已经被删除');
				break;
			}
			/**
			if( empty($params['sites']) || empty($params['price']) ){
				$ret = array('err_no'=>1000,'err_msg'=>'请至少选择一个可用网站');
				break;
			}
			*/
			//remove and reAdd product site
			$this->mBase->delete('products_site',array('product_id'=>$product_id),true);
			if( $params['sites'] ){
				$data = array();
				foreach ( $params['sites'] as $k=>$site_id ){
					$is_stock = isset($_POST['stock_'.$site_id]) ? intval($_POST['stock_'.$site_id]) : -1;
					$stock_num = isset($_POST['stock_num_'.$site_id]) ? intval($_POST['stock_num_'.$site_id]) : -1;
					$data[] = array(
						'product_id'	=> $product_id,
						'site_id'		=> $site_id,
						'price'			=> $params['price'][$k],
						'stock'			=> $is_stock>0 ? $stock_num : $is_stock,
						'modify_eid'	=> parent::$user['id'],
						'modify_name'	=> parent::$user['username'],
						'modify_time'	=> time(),
					);
				}
				$this->mBase->insertMulti('products_site',$data);
			}
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}

	public function sellStatus(){
		$single_id = $this->input->post('single_id');
		$status = $this->input->post('status');

		if(empty($single_id)){
			die('{"error":1, "msg": "信息错误，请刷新页面重试！"}');
		}

		$data_info['is_sell'] = intval($status);
		$single_id_map = array('id' => $single_id);
		$update_rst = $this->mBase->update('products', $data_info, $single_id_map);
		
		die('{"error":0, "msg": ""}');
	}
	
	public function sellStatusMulti(){
		$ids = $this->input->post('ids');
		$status = $this->input->post('status');
		$ids = explode(',', $ids);
	
		if(empty($ids)){
			die('{"error":1, "msg": "信息错误，请刷新页面重试！"}');
		}
	
		$data_info['is_sell'] = intval($status);
		foreach ( $ids as $single_id ){
			$single_id_map = array('id' => $single_id);
			$update_rst = $this->mBase->update('products', $data_info, $single_id_map);
		}
		die('{"error":0, "msg": ""}');
	}

	public function single_select_dialog(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('category_id','search','page','size');
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(10,min(100,intval($params['size'])));
		//get current site's category
		$data['category_list'] = array();
		$condition = array(
			'AND' => array('is_del=0'),
		);
		$res = $this->mBase->getList('goods_category',$condition);
		if( $res ){
			foreach ( $res as $row ){
				$data['category_map'][$row['id']] = $row;
			}
		}
		if(is_array($res) && count($res) > 0){
			$data['category_list'] = getTreeFromArray($res);
		}
		//get products
		$condition = array(
			'AND' => array('is_del=0'),
		);
		if( $params['category_id'] ){
			$condition['AND'][] = 'category_id='.$params['category_id'];
		}
		if( $params['search'] ){
			$condition['AND'][] = "title like '%{$params['search']}%'";
		}
		$res = $this->mBase->getList('products',$condition,'*','id DESC',$page,$size);
		if( $res->results ){
			foreach ( $res->results as &$row ){
				unset($row['content']);
				//get product site count
				$condition = array(
					'AND' => array('product_id='.$row['id']),
				);
				$row['siteNum'] = $this->mBase->getCount('products_site',$condition);
			}
			unset($row);
		}
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//display templates
		$this->_view('product/product_single_select', $data);
	}

	public function muti_select_dialog(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('category_id','search','page','size');
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(10,min(100,intval($params['size'])));
		//get current site's category
		$data['category_list'] = array();
		$data['category_map'] = array();
		$condition = array(
			'AND' => array('is_del=0'),
		);
		$res = $this->mBase->getList('goods_category',$condition);
		if( $res ){
			foreach ( $res as $row ){
				$data['category_map'][$row['id']] = $row;
			}
		}
		if(is_array($res) && count($res) > 0){
			$data['category_list'] = getTreeFromArray($res);
		}
		//get products
		$condition = array(
			'AND' => array('is_del=0'),
		);
		if( $params['category_id'] ){
			$condition['AND'][] = 'category_id='.$params['category_id'];
		}
		if( $params['search'] ){
			$condition['AND'][] = "title like '%{$params['search']}%'";
		}
		$res = $this->mBase->getList('products',$condition,'*','id DESC',$page,$size);
		if( $res->results ){
			foreach ( $res->results as &$row ){
				unset($row['content']);
				//get product site count
				$condition = array(
					'AND' => array('product_id='.$row['id']),
				);
				$row['siteNum'] = $this->mBase->getCount('products_site',$condition);
			}
			unset($row);
		}
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//display templates
		$this->_view('product/product_muti_select', $data);
	}
	
	public function searchByPin(){
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('pin');
			$fields = array();
			$this->checkParams('get',$must,$fields);
			$params = $this->params;
			$pin = $params['pin'];
			//check parameters
			if( strlen($pin)<4 ){
				$ret = array('err_no'=>1000,'err_msg'=>'商品货号长度小于5');
				break;
			}
			$results = array();
			$condition = array(
				'AND' => array('is_del=0','product_pin='.$pin),
			);
			$res = $this->mBase->getList('products',$condition);
			if( !$res ){
				$ret = array('err_no'=>1000,'err_msg'=>'商品不存在');
				break;
			}
			$results = current($res);
			$ret = array('err_no'=>0,'err_msg'=>'操作成功','results'=>$results);
		} while(0);
		$this->output($ret);
	}

    public function product_label_print_muti() {
        $data = array();
        //get parameters
        $must = array('product_ids','amount');
        $fields = array();
        $this->checkParams('get', $must, $fields);
        $params = $data['params'] = $this->params;
        //get products
        $product_ids = implode(',', $params);

        $product_list = $this->mBase->getList('products', array('AND' => array("id in ({$product_ids})")), 'id,sku,product_pin,title,price,spec,spec_packing,unit');
        if (!$product_list) {
            $this->showMsg(1000, '商品不存在');
            return;
        }
        $data['label_list'] = $product_list;
        $data['product_amount']=$params['amount'];
        $this->load->view('product/product_label_print_muti', $data);
    }	
}

