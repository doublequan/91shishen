<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class device extends Base 
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
		$res = $this->mBase->getList('users_device',$condition,'*','id DESC',$page,$size);
		$data['users'] = array();
		if( $res->results ){
			foreach ( $res->results as $row ){
				if( !isset($data['users'][$row['uid']]) ){
					$t= $this->mBase->getSingle('users','id',$row['uid']);
					if( $t ){
						$data['users'][$t['id']] = $t['username'];
					}
				}
			}
		}
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//Config Data
		$data['os_types'] = getConfig('os_types');
		$data['net_types'] = getConfig('net_types');
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'device';
		$this->_view('common/header', $tags);
		$this->_view('user/device_list', $data);
		$this->_view('common/footer');
	}
}
