<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class card extends Base 
{
	private $active_top_tag = 'user';

	public function __construct(){
		parent::__construct();
	}

	public function pingan(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('keyword','page','size');
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		$k = $params['keyword'];
		//get users
		$condition = array();
		if( $k ){
			$condition['AND'][] = "cardno LIKE '%".$k."%'";
		}
		$res = $this->mBase->getList('users_card_pingan',$condition,'*','id DESC',$page,$size);
		$data['users'] = array();
		if( $res->results ){
			foreach ( $res->results as $row ){
				if( $row['uid'] ){
					$data['users'][$row['uid']] = $this->mBase->getSingle('users','id',$row['uid']);
				}
			}
		}
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'card_pingan';
		$this->_view('common/header', $tags);
		$this->_view('user/card_pingan_list', $data);
		$this->_view('common/footer');
	}
}
