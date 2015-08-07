<?php
/**
 * 商品搜索
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-24
 */
require_once 'base.php';
class Search extends Base{
	
	//搜索
	public function index(){
		$page = max(1, intval($this->input->get('page')));
		$cid = max(0, intval($this->input->get('cid')));
		$sort = trim($this->input->get('sort', true));
		$type = trim($this->input->get('type', true));
		$s = trim($this->input->get('s'));
		$keyword = urldecode($s);
		if(empty($keyword)){
		    redirect(base_url());
		}
	    $this->load->model('Category_model');
		if( ! in_array($sort, array('id','sold','comment','price')) || ! in_array($type, array('asc','desc'))){
		    $sort = 'id';
		    $type = 'desc';
		}
		//商品排序
		$field = 'DISTINCT p.id,p.category_id,p.title,p.seo_title,p.price,p.thumb,p.unit,p.spec';
	    $where['AND'][] = 'p.is_del = 0';
	    $where['AND'][] = 's.site_id > 0';
	    $where['AND'][] = "p.title LIKE '%{$keyword}%'";
	    if($cid > 0){
	        $where['AND'][] = "p.category_id = {$cid}";
	    }
		//商品信息
		$data = $this->Base_model->getList('products p LEFT JOIN products_site s ON s.product_id = p.id', $where, $field, 'p.'.$sort.' '.$type, $page, 40);
		//分类统计
		$where = "p.is_del = 0 AND s.site_id > 0 AND p.title LIKE '%{$keyword}%'";
		if($cid > 0)  $where .= " AND p.category_id = {$cid}";
		$categories = $this->Base_model->getAll("
		    SELECT COUNT(DISTINCT p.id) AS category_num,p.category_id,c.name
		    FROM products AS p
		    LEFT JOIN goods_category c ON c.id = p.category_id
		    LEFT JOIN products_site s ON s.product_id = p.id
		    WHERE {$where}
		    GROUP BY category_id
		");
		$total_num = $data->pager['total'];
		//排序类型
		$sorttype = array('sort'=>$sort, 'type'=>$type);
		//商品分页
		$pager = parent::_formatPager(base_url("search/index?s={$s}&cid={$cid}&sort={$sort}&type={$type}"), $data->pager);
		//销量排行
		$this->load->model('Goods_model');
		$ranking = $this->Goods_model->getRanking(4);
		//更新日志
		$userInfo = $this->session->userdata('userinfo');
		$logs['uid'] = empty($userInfo) ? 0 :$userInfo['uid'];
		$logs['keyword'] = $keyword;
		$logs['create_ip'] = getUserIP();
		$logs['create_time'] = time();
		$this->Base_model->insert('logs_search', $logs);
		$this->load->view('search_index', array('data'=>$data->results,'sorttype'=>$sorttype,'page'=>$page,'pager'=>$pager,'ranking'=>$ranking,'categories'=>$categories,'total_num'=>$total_num,'keyword'=>$keyword,'s'=>$s));
	}
}