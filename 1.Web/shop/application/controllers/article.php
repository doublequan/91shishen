<?php
/**
 * 文章模块
 * @author LiuPF<mail@phpha.com>
 * @date 2014-7-30
 */
require_once 'base.php';
class Article extends Base{
	
	public function __construct(){
		parent::__construct();
		$this->load->model('Category_model');
		$categories = $this->Category_model->getArticleCategory();
		$this->load->vars(array('categories'=>$categories));
	}
	
	//文章列表
	public function index($category_id=0,$page=1){
		$category_id = intval($category_id);
		$page = max(1, intval($page));
		empty($category_id) && show_404();
		$data = array();
		$where['AND'][] = "category_id = {$category_id}";
		$where['AND'][] = "is_del=0";
		$res = $this->Base_model->getList('archives', $where, 'id,title,create_time', 'sort ASC,id DESC', $page, 10);
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		$data['category'] = $this->Base_model->getSingle('archives_category','id',$category_id);
		$this->load->view('article_index', $data);
	}
	
	//文章详情
	public function view($id = 0){
		$id = intval($id);
		$data = $this->Base_model->getRow("SELECT * FROM archives WHERE id = {$id}");
		empty($data) && show_404();
		$category_id = $data['category_id'];
		$category = $this->Base_model->getRow("SELECT id,name FROM archives_category WHERE id = {$category_id}");
		$this->load->view('article_view', array('data'=>$data,'category'=>$category));
	}
	
	public function detail( $id=0 ){
		$data = array();
		$id = intval($id);
		$data['single'] = $this->Base_model->getSingle('archives','id',$id);
		empty($data['single']) && show_404();
		$data['category'] = $this->Base_model->getSingle('archives_category','id',$data['single']['category_id']);
		if( $data['single']['template_id']==2 ){
			$this->load->view('archive/detail_2', $data);
		} else {
			$this->load->view('archive/detail_1', $data);
		}
	}
}