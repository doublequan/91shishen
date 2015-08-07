<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class test extends Base 
{
	private $active_top_tag = 'stat';

	public function product(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('category_id','search','page','size');
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
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
			'AND' => array('is_del=0',"content=''"),
		);
		if( $params['category_id'] ){
			$condition['AND'][] = 'category_id='.$params['category_id'];
		}
		if( $params['search'] ){
			$condition['AND'][] = "title like '%{$params['search']}%'";
		}
		$res = $this->mBase->getList('products',$condition,'*','id DESC',$page,$size);
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'test_product';
		$this->_view('common/header', $tags);
		$this->_view('stat/test_product', $data);
		$this->_view('common/footer');
	}
}
