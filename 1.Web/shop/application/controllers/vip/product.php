<?php
/**
 * VIP商品信息
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-30
 */
require_once 'common.php';
class Product extends Common{
	
	//商品列表
	public function index($category_id = 0){
		$page = max(1, intval($this->input->get('page')));
		$min = max(0, intval($this->input->get('min')));
		$max = max($min, intval($this->input->get('max')));
		$category_id = intval($category_id);
		//会员信息
		$vipInfo = $this->session->userdata('vipinfo');
		$discount = $this->Base_model->getOne("SELECT discount FROM vip_users WHERE id = {$vipInfo['uid']}");
		$discount = $discount > 0 ? $discount / 100 : 1;
		//商品排序
		$field = 'id,sku,title,seo_title,price,thumb,unit,spec,spec_packing';
		$where['AND'][] = 'is_del = 0';
		$search = array('min'=>0, 'max'=>0);
		if($category_id > 0){
			$where['AND'][] = "category_id = {$category_id}";
		}
		if($max > $min && $min >= 0){
			$where['AND'][] = "(price * {$discount}) >= {$min} AND (price * {$discount}) <= {$max}";
			$search['min'] = $min;
			$search['max'] = $max;
		}
		//商品信息
		$data = $this->Base_model->getList('vip_products', $where, $field, 'id DESC', $page, 10);
		//商品分类
		$category = $this->Base_model->getRow("SELECT id,name,seo_title,seo_keywords,seo_description FROM vip_category WHERE id = {$category_id}");
		$categories = $this->Base_model->getAll("SELECT id,name FROM vip_category WHERE is_del = 0 ORDER BY sort ASC, id ASC");
		//商品分页
		$pager = parent::_formatPager(base_url("vip/product/index/{$category_id}?min={$min}&max={$max}"), $data->pager);
		$this->load->view('vip/product_index', array('data'=>$data->results,'discount'=>$discount,'search'=>$search,'pager'=>$pager,'page'=>$page,'category'=>$category,'categories'=>$categories));
	}
	
	//商品定制
	public function booking(){
		$curr_time = time();
		//加密令牌
		$hash_token = md5(getRandStr().$curr_time);
		$this->session->set_userdata('hash_token', $hash_token);
		$this->load->view('vip/product_booking', array('hash_token'=>$hash_token));
	}
	
	//定制记录
	public function booklog(){
		$page = max(1, intval($this->input->get('page')));
		//会员信息
		$vipInfo = $this->session->userdata('vipinfo');
		$where['AND'][] = "uid = {$vipInfo['uid']}";
		$field = "id,create_time";
		$data = $this->Base_model->getList('vip_customs', $where, $field, 'id DESC', $page, 5);
		$pager = parent::_formatPager(base_url("vip/product/booklog"), $data->pager);
		//定制详情
		foreach($data->results as &$v){
			$v['detail'] = $this->Base_model->getAll("SELECT name,amount,note FROM vip_customs_detail WHERE custom_id = '{$v['id']}'");
		}
		$this->load->view('vip/product_booklog', array('data'=>$data->results,'pager'=>$pager));
	}
	
	//提交定制
	public function ajax_book(){
		//仅限Ajax访问
		$this->input->is_ajax_request() || show_404();
		//接收数据
		$hash_token = $this->input->post('hash_token');
		$product_name_array = $this->input->post('product_name');
		$book_number_array = $this->input->post('book_number');
		$note_array = $this->input->post('note');
		//会员信息
		$vipInfo = $this->session->userdata('vipinfo');
		//默认状态
		$err['err_no'] = 1000;
		$err['err_msg'] = parent::$errorType[1000];
		//检查数据
		$bookId = createBusinessID('CUS');
		$book_detail = array();
		for($i=0; $i<count($product_name_array); $i++){
			$product_name = addslashes(strip_tags($product_name_array[$i]));
			$book_number = intval($book_number_array[$i]);
			if(empty($product_name) || $book_number <= 0){
				continue;
			}
			$logs['custom_id'] = $bookId;
			$logs['name'] = $product_name;
			$logs['amount'] = $book_number;
			$logs['note'] = addslashes(strip_tags($note_array[$i]));
			$book_detail[] = $logs;
		}
		if(empty($book_detail)){
			exit(json_encode($err));
		}
		//检查令牌
		if(empty($hash_token) || $hash_token != $this->session->userdata('hash_token')){
			$err['err_no'] = 1001;
			$err['err_msg'] = parent::$errorType[1001];
			exit(json_encode($err));
		}
		//定制记录
		$data['id'] = $bookId;
		$data['uid'] = $vipInfo['uid'];
		$data['create_time'] = time();
		$result = $this->Base_model->insert('vip_customs',$data);
		if(false != $result){
			//订制详情
			$this->Base_model->insertMulti('vip_customs_detail', $book_detail);
			//删除Token
			$this->session->unset_userdata('hash_token');
			//成功提示
			$err['err_no'] = 0;
			$err['err_msg'] = '订单提交成功';
		}
		exit(json_encode($err));
	}
}