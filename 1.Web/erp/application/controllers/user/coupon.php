<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class coupon extends Base 
{
	private $active_top_tag = 'user';
	
	public $statusMap = array(
		1 => '未使用',
		2 => '已使用',
		3 => '已过期',
	);

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('uid','type','username','keyword','page','size');
		$this->checkParams('get',$must,$fields);
		$this->params['type'] = in_array($this->params['type'],array(1,2)) ? $this->params['type'] : 1;
		$params = $data['params'] = $this->params;
		$uid = intval($params['uid']);
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		//get data
		$condition = array(
			'AND' => array('c.coupon_type='.$params['type']),
		);
		$k = $params['keyword'];
		if( $k ){
			$condition['AND'][] = "c.coupon_code LIKE '%".$k."%'";
		}
		$u = $params['username'];
        if( $u ){
        	$condition['AND'][] = "u.username LIKE '%".$u."%'";
        }	
		if( $uid ){
			$condition['AND'][] = 'c.uid='.$uid;
		}
		$table = 'users_coupon AS c LEFT JOIN users AS u ON c.uid=u.id';
		$res = $this->mBase->getList($table,$condition,'c.*,u.username','c.id DESC',$page,$size);
		$data['users'] = array();
		if( $res->results ){
			foreach( $res->results as &$row ){
				if( $k ){
					$row['coupon_code'] = str_replace($k, '<font color="red">'.$k.'</font>', $row['coupon_code']);
				}
				if( $u ){
					$row['username'] = str_replace($u, '<font color="red">'.$u.'</font>', $row['username']);
				}
			}
			unset($row);
		}
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		$data['title'] = $params['type']==1 ? '代金券' : '抵用券';
		//Common Data
		$data['statusMap'] = $this->statusMap;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = $params['type']==1 ? 'coupon_cash' : 'coupon_discount';
		$this->_view('common/header', $tags);
		$this->_view('user/coupon_list', $data);
		$this->_view('common/footer');
	}
	
	public function add(){
		$data = array();
		$type = intval($this->input->get('type'));
		$type = in_array($type,array(1,2)) ? $type : 1;
		$data['title'] = $type==1 ? '代金券' : '抵用券';
		//Common Data
		$data['statusMap'] = $this->statusMap;
		$data['groups'] = $this->mBase->getList('users_group',array(),'*','id DESC');
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'coupon_cash';
		$this->_view('common/header', $tags);
		$this->_view('user/coupon_add', $data);
		$this->_view('common/footer');
	}
	
	public function delete(){
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$id = intval($this->input->get('id'));
			$this->mBase->delete('users_coupon',array('id'=>$id),true);
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
}
