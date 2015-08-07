<?php
/**
 * 我的收藏
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-6
 */
require_once 'common.php';
class Favorite extends Common{
	
	//收藏列表
	public function index(){
		$page = max(1, intval($this->input->get('page')));
		$userInfo = $this->session->userdata('userinfo');
		//收藏
		$collect_sql = "SELECT f.product_id, f.create_time, p.title, p.thumb, p.price
				FROM users_fav AS f
				LEFT JOIN products AS p ON p.id = f.product_id
				WHERE f.uid = {$userInfo['uid']} AND f.is_del = 0
				ORDER BY f.create_time DESC";
		$data = $this->Base_model->pagerQuery($collect_sql, $page, 5);
		$pager = parent::_formatPager(base_url('member/favorite/index'), $data->pager);
		$this->load->view('member/favorite_index', array('data'=>$data->results,'pager'=>$pager,'currMenu'=>'member_favorite'));
	}
	
	//添加收藏
	public function ajax_add(){
		//仅限Ajax访问
		$this->input->is_ajax_request() || show_404();
		//会员信息
		$userInfo = $this->session->userdata('userinfo');
		//商品信息
		$product_id = intval($this->input->post('product_id'));
		$product = $this->Base_model->getRow("SELECT id,price FROM products WHERE id = {$product_id}");
		if(empty($product)){
			$err['err_no'] = 1003;
			$err['err_msg'] = parent::$errorType[1003];
			exit(json_encode($err));
		}
		//检查唯一性
		$check = $this->Base_model->getOne("SELECT id FROM users_fav WHERE uid = {$userInfo['uid']} AND product_id = {$product_id}");
		if( ! empty($check)){
			$err['err_no'] = 1004;
			$err['err_msg'] = parent::$errorType[1004];
			exit(json_encode($err));
		}
		//执行添加
		$data = array('uid'=>$userInfo['uid'], 'product_id'=>$product_id, 'price'=>$product['price'], 'create_time'=>time());
		if(false != $this->Base_model->insert('users_fav', $data)){
			$err['err_no'] = 0;
			$err['err_msg'] = parent::$errorType[0];
		}else{
			$err['err_no'] = 1000;
			$err['err_msg'] = parent::$errorType[1000];
		}
		exit(json_encode($err));
	}
	
	//取消收藏
	public function ajax_del(){
		//仅限Ajax访问
		$this->input->is_ajax_request() || show_404();
		//会员信息
		$userInfo = $this->session->userdata('userinfo');
		//执行取消
		$product_id = intval($this->input->post('product_id'));
		$result = $this->Base_model->delete('users_fav', array('uid'=>$userInfo['uid'], 'product_id'=>$product_id), true);
		if($result > 0){
			$err['err_no'] = 0;
			$err['err_msg'] = parent::$errorType[0];
		}else{
			$err['err_no'] = 1000;
			$err['err_msg'] = parent::$errorType[1000];
		}
		exit(json_encode($err));
	}
}