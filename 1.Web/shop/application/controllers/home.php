<?php
/**
 * 入口文件
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-14
 */
require_once 'base.php';
class Home extends Base{
	
	public function index(){
		$data = array();
		$data['is_home'] = 1;
	    $this->load->model('Goods_model');
	    $this->load->model('Fragment_model');
	    //商品分类
	    $categories = $this->Category_model->getGoodsCategory();
	    $data['categories'] = $categories;;
	    //新品上架
	    $newest = $this->Goods_model->getGoods(0, 'p.id DESC', 9);
	    $data['newest'] = $newest;
	    //今日实惠
	    //$data['economic'] = $this->Goods_model->getCheapest(SITEID);
	    //碎片列表
	    $data['fragments'] = array();
	    $placesAll = array(
	    	1	=> array(1,17,18,19,21,22,23,24,25,26,27,28,54),
	    	2	=> array(57,61,62,63,65,66,67,68,69,70,71,72,73),
	    	3	=> array(88,92,93,94,96,97,98,99,100,101,102,103,104),
	    );
	    $places = $placesAll[SITEID];
	    $condition = array(
	    	'AND' => array('place_id IN ('.implode(',', $places).')'),
	    );
	    $res = $this->Base_model->getList('fragments',$condition,'*','sort ASC');
	    if( $res ){
	    	$places_flip = array_flip($places);
	    	foreach ( $res as $row ){
	    		$key = $places_flip[$row['place_id']];
	    		$place_id = $placesAll[1][$key];
	    		$data['fragments'][$place_id][] = $row;
	    	}
	    }
	    //公告、资讯
	    $data['archives'] = array();
	    $condition = array(
	    	'AND' => array('category_id=1','is_del=0'),
	    );
	    $data['archives'][1] = $this->Base_model->getList('archives',$condition,'*','create_time DESC',0,4);
	    $condition = array(
	    	'AND' => array('category_id=2'),
	    );
	    $data['archives'][2] = $this->Base_model->getList('archives',$condition,'*','create_time DESC',0,4);
	    //get index category
	    foreach ( $categories as $k=>$row ){
	        $data['product'][$k]['id'] = $row['id'];
	    	$data['product'][$k]['name'] = $row['name'];
		    //分类楼层左侧列表
		    $data['product'][$k]['list'] = $this->Goods_model->getGoods($row['id'], 'p.click DESC', 10);
	    }
		$this->load->view('home_index', $data);
	}
}