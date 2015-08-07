<?php
/**
 * 商品模块
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-4
 */
require_once 'base.php';
class Goods extends Base{
	
	//商品列表
	public function index($category_id=0,$page=1){
		$page = max(1, intval($this->input->get('page')));
		$sort = $this->input->get('sort', true);
		$type = $this->input->get('type', true);
	    $category_id = intval($category_id);
	    empty($category_id) && show_404();
	    //当前分类及子分类
	    $category = $this->Base_model->getRow("SELECT id,name,thumb_web,seo_title,seo_keywords,seo_description FROM goods_category WHERE id = {$category_id}");
		empty($category) && show_404();
	    $categories = $this->Category_model->getGoodsCategory($category_id);
	    //获取当前分类所有子类
	    $all_categories = $this->Category_model->getChildCateID(1, $category_id);
	    $in_categories = $category_id.','.implode(',', $all_categories);
	    $in_categories = trim($in_categories, ',');
	    //商品排序
		$sort = in_array($sort, array('id','sold','comment','price')) ? $sort : 'sold';
		$type = in_array($type, array('asc','desc')) ? $type : 'desc';
		$field = 'DISTINCT p.id,p.title,p.seo_title,p.price,p.thumb,p.unit,p.spec';
		$where['AND'][] = "p.category_id IN ({$in_categories})";
		$where['AND'][] = 'p.is_del = 0';
		$where['AND'][] = 's.site_id='.SITEID;
		//商品信息
		$data = $this->Base_model->getList('products p LEFT JOIN products_site s ON s.product_id = p.id', $where, $field, 'p.'.$sort.' '.$type, $page, 40);
		//排序类型
		$sorttype = array('sort'=>$sort, 'type'=>$type);
		//商品分页
		$pager = parent::_formatPager(base_url("cat_{$category_id}.html?sort={$sort}&type={$type}"), $data->pager);
		//广告碎片
	    $this->load->model('Fragment_model');
		$ads = $this->Fragment_model->getData(4, 2);
		$this->load->view('goods_index', array('data'=>$data->results,'sorttype'=>$sorttype,'page'=>$page,'pager'=>$pager,'category'=>$category,'categories'=>$categories,'ads'=>$ads));
	}
	
	//商品详情
	public function detail($id = 0){
	    //商品信息
		$product_id = intval($id);
		$data = $this->Base_model->getSingle('products','id',$product_id);
		empty($data) && show_404();
		//判断状态
		$is_sale = 'sale';
		$stock = $this->Base_model->getRow("SELECT stock FROM products_site WHERE site_id = ".SITEID." AND product_id = {$product_id}");
		//分类信息
		$category = $this->Base_model->getRow("SELECT id,name FROM goods_category WHERE id = {$data['category_id']}");
		//商品相册
		$photo = $this->Base_model->getAll("SELECT img,thumb FROM products_img WHERE product_id = {$product_id} ORDER BY sort ASC LIMIT 10");
		//推荐商品
		$this->load->model('Goods_model');
		$recommend = $this->Goods_model->getRecommend($product_id);
		//商品评论
		$this->load->model('Comment_model');
		$comment = $this->Comment_model->getComment($product_id);
		//更新浏览记录
		$userInfo = $this->session->userdata('userinfo');
		if( ! empty($userInfo)){
			$view['uid'] = $userInfo['uid'];
			$view['product_id'] = $product_id;
			$view['create_time'] = time();
			$this->Base_model->insert('users_log_view', $view);
		}

		//双12活动 王辉 2014-12-5
		$special_id = 9;
		$specials   = $this->Base_model->getRow("SELECT day_start,day_end FROM specials WHERE id = '$special_id'");
		$day_start  = strtotime($specials['day_start']);	
		$day_end    = strtotime($specials['day_end']);
		$date_now   = time();
		$shuang     = 0;
		$spe_site   = $this->Base_model->getAll("SELECT site_id FROM specials_site WHERE special_id = '$special_id'");
		$site_arr1  = array();
		foreach($spe_site as $k=>$v){
			$site_arr1[]  = $v['site_id'];
		}	 
		if(in_array(SITEID,$site_arr1)){
			if( $date_now > $day_start &&  $date_now < $day_end ) {
				$specials_product = $this->Base_model->getAll("SELECT product_id FROM specials_product where special_id = '$special_id'");
				$pro_arr = array();
				foreach ($specials_product as $k => $v) {
					$pro_arr[] = $v['product_id'];
				}
				if(in_array($product_id,$pro_arr)){
					$products_site = $this->Base_model->getAll("SELECT site_id FROM products_site WHERE product_id = '$product_id'");
					$site_arr2     = array();
					foreach ($products_site as $k => $v) {
						$site_arr2[] = $v['site_id'];
					}
					if(in_array(SITEID,$site_arr2)){
						$shuang = 1;
					}
				}
			}
		}

		//天天特价 王辉 2014-12-9
		$special_id = 1;
		$specials   = $this->Base_model->getRow("SELECT day_start,day_end FROM specials WHERE id = '$special_id'");
		$day_start  = strtotime($specials['day_start']);	
		$day_end    = strtotime($specials['day_end']);
		$date_now   = time();
		$tejia      = 0;
		$spe_site   = $this->Base_model->getAll("SELECT site_id FROM specials_site WHERE special_id = '$special_id'");
		$site_arr1  = array();
		foreach($spe_site as $k=>$v){
			$site_arr1[]  = $v['site_id'];
		}	 
		if(in_array(SITEID,$site_arr1)){
			if( $date_now > $day_start &&  $date_now < $day_end ) {
				$specials_product = $this->Base_model->getAll("SELECT product_id FROM specials_product where special_id = '$special_id'");
				$pro_arr = array();
				foreach ($specials_product as $k => $v) {
					$pro_arr[] = $v['product_id'];
				}
				if(in_array($product_id,$pro_arr)){
					$products_site = $this->Base_model->getAll("SELECT site_id FROM products_site WHERE product_id = '$product_id'");
					$site_arr2     = array();
					foreach ($products_site as $k => $v) {
						$site_arr2[] = $v['site_id'];
					}
					if(in_array(SITEID,$site_arr2)){
						$tejia = 1;
					}
				}
			}
		}

		//优选组合 王辉 2014-12-11 
		$special_id = 11;
		$specials   = $this->Base_model->getRow("SELECT day_start,day_end FROM specials WHERE id = '$special_id'");
		$day_start  = strtotime($specials['day_start']);	
		$day_end    = strtotime($specials['day_end']);
		$date_now   = time();
		$zuhe       = 0;
		$spe_site   = $this->Base_model->getAll("SELECT site_id FROM specials_site WHERE special_id = '$special_id'");
		$site_arr1  = array();
		foreach($spe_site as $k=>$v){
			$site_arr1[]  = $v['site_id'];
		}	 

		if(in_array(SITEID,$site_arr1)){
			$specials_product = $this->Base_model->getAll("SELECT product_id FROM specials_product where special_id = '$special_id'");
			$pro_arr = array();
			foreach ($specials_product as $k => $v) {
				$pro_arr[] = $v['product_id'];
			}
			if(in_array($product_id,$pro_arr)){
				$products_site = $this->Base_model->getAll("SELECT site_id FROM products_site WHERE product_id = '$product_id'");
				$site_arr2     = array();
				foreach ($products_site as $k => $v) {
					$site_arr2[] = $v['site_id'];
				}
				if(in_array(SITEID,$site_arr2)){
					$zuhe = 1;
				}
			}
		}
		
		$this->load->view('goods_detail', array('data'=>$data,'stock'=>$stock,'photo'=>$photo,'category'=>$category,'recommend'=>$recommend,'comment'=>$comment,'shuang'=>$shuang,'tejia'=>$tejia,'zuhe'=>$zuhe));
	}
	
	//推荐商品
	public function ajax_recommend(){
	    //仅限Ajax访问
	    $this->input->is_ajax_request() || show_404();
	    //接收参数
	    $product_id = intval($this->input->post('product_id'));
	    $page = max(1, intval($this->input->post('page')));
	    //默认状态
	    $err['err_no'] = 1000;
	    $err['err_msg'] = parent::$errorType[1000];
	    //商品信息
	    $category_id = $this->Base_model->getOne("SELECT category_id FROM products WHERE id = {$product_id}");
	    empty($category_id) && exit(json_encode($err));
	    //推荐信息
	    $this->load->model('Goods_model');
	    $data = $this->Goods_model->getRecommend($product_id, $page);
	    if(empty($data['total'])){
	        $err['err_no'] = 1003;
	        $err['err_msg'] = parent::$errorType[1003];
	    }else{
	    	$err['err_no'] = 0;
	    	$err['err_msg'] = parent::$errorType[0];
	    	$err['results'] = $data['data'];
	    }
	    exit(json_encode($err));
	}
	
	/**
	 * 客户端用产品详情Webview地址
	 * @param integer $id
	 */
	public function view($id = 0){
		//商品信息
		$product_id = intval($id);
		$data['single'] = $this->Base_model->getSingle('products','id',$product_id);
		$this->load->view('goods_webview',$data);
	}
}