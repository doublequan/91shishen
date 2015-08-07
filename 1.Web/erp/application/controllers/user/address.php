<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class address extends Base 
{
	private $active_top_tag = 'user';

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('uid','page','size');
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		//get users
		$condition = array();
		if( $params['uid'] ){
			$condition['AND'][] = 'uid='.$params['uid'];
		}
		$res = $this->mBase->getList('users_address',$condition,'*','id DESC',$page,$size);
		$data['users'] = array();
		$data['areas'] = array();
		if( $res->results ){
			foreach ( $res->results as $row ){
				if( !isset($data['users'][$row['uid']]) ){
					$t= $this->mBase->getSingle('users','id',$row['uid']);
					$data['users'][$t['id']] = $t['username'];
				}
				$arr = array('prov','city','district');
				foreach( $arr as $v ){
					if( !isset($data['areas'][$row[$v]]) ){
						$cache_key = 'AREA_'.$row[$v];
						$t = Common_Cache::get($cache_key);
						if( !$t ){
							$t= $this->mBase->getSingle('areas','id',$row[$v]);
							Common_Cache::save($cache_key, $t, 86400);
						}
						if( $t ){
							$data['areas'][$t['id']] = $t['name'];
						}
					}
				}
			}
		}
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'address';
		$this->_view('common/header', $tags);
		$this->_view('user/address_list', $data);
		$this->_view('common/footer');
	}
}
