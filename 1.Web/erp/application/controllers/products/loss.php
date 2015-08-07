<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class loss extends Base 
{
	//Common Loss Types
	private $types = array();
	
	//Common Loss Types
	private $tMap = array(1,2);
	
	public function __construct(){
		parent::__construct();
		$this->active_top_tag = 'product';
		$res = $this->mBase->getList('loss_type', array('AND'=>array('is_del=0')));
		if( $res ){
			foreach ( $res as $row ){
				$this->types[$row['id']] = $row;
			}
		}
	}
	
	/**
	 * @param t[1:goods,2:products]
	 */
	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('t','type_id','page','size');
		$this->checkParams('get',$must,$fields);
		$params = $this->params;
		$t = intval($params['t']);
		$t = in_array($t, $this->tMap) ? $t : current($this->tMap);
		$params['t'] = $t;
		$data['params'] = $params;

		$page = max(1,intval($params['page']));
		$size = max(10,min(100,intval($params['size'])));
		//get loss 
		$data['results'] = array();
		if( $t==1 ){
			$res = $this->mBase->getList('goods_loss',array(),'*','id DESC',$page,$size);
			$data['results'] = $res->results;
			$data['pager'] = $res->pager;

		} elseif ( $t==2 ){
			$res = $this->mBase->getList('products_loss',array(),'*','id DESC',$page,$size);
			$data['results'] = $res->results;
			$data['pager'] = $res->pager;
		}

		//common data
		$data['types'] = $this->types;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'loss';
		$this->_view('common/header', $tags);
		$this->_view('product/loss_list', $data);
		$this->_view('common/footer');
	}
	
	public function add() {
		$data = array();
		//common data
		$data['types'] = $this->types;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'loss';
		$this->_view('common/header', $tags);
		$this->_view('product/loss_add', $data);
		$this->_view('common/footer');
	}
	
	public function doAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('t','type_id','des','id','amount_total','amount_loss','respon_eid');
			$fields = array();
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$t = intval($params['t']);
			$type_id = intval($params['type_id']);
			//check parameters
			if( !in_array($t, $this->tMap) ){
				$ret = array('err_no'=>1000,'err_msg'=>'product type is not exists');
				break;
			}
			if( !array_key_exists($type_id, $this->types) ){
				$ret = array('err_no'=>1000,'err_msg'=>'损耗类型不存在');
				break;
			}
			$emp = $this->mBase->getSingle('employees','id',$params['respon_eid']);
			if( !$emp ){
				$ret = array('err_no'=>1000,'err_msg'=>'损耗负责员工不存在');
				break;
			}
			if( $t==1 ){
				$single = $this->mBase->getSingle('goods','id',$params['id']);
				if( !$single ){
					$ret = array('err_no'=>1000,'err_msg'=>'原料信息不存在');
					break;
				}
				$data = array(
					'type_id'		=> $params['type_id'],
					'des'			=> $params['des'],
					'good_id'		=> $params['id'],
					'good_name'		=> $single['name'],
					'unit'			=> $single['unit'],
					'amount_total'	=> $params['amount_total'],
					'amount_loss'	=> $params['amount_loss'],
					'amount_left'	=> floatval($params['amount_total']-$params['amount_loss']),
					'price'			=> $single['price'],
					'respon_eid'	=> $params['respon_eid'],
					'respon_name'	=> $emp['username'],
					'create_eid'	=> parent::$user['id'],
					'create_name'	=> parent::$user['username'],
					'create_time'	=> time(),
				);
				$this->mBase->insert('goods_loss',$data);
			} elseif ( $t==2 ){
				$single = $this->mBase->getSingle('products','id',$params['id']);
				if( !$single ){
					$ret = array('err_no'=>1000,'err_msg'=>'商品不存在');
					break;
				}
				$data = array(
						'type_id'		=> $params['type_id'],
						'des'			=> $params['des'],
						'product_id'	=> $params['id'],
						'product_name'	=> $single['title'],
						'unit'			=> $single['unit'],
						'amount_total'	=> $params['amount_total'],
						'amount_loss'	=> $params['amount_loss'],
						'amount_left'	=> floatval($params['amount_total']-$params['amount_loss']),
						'price'			=> $single['price'],
						'respon_eid'	=> $params['respon_eid'],
						'respon_name'	=> $emp['username'],
						'create_eid'	=> parent::$user['id'],
						'create_name'	=> parent::$user['username'],
						'create_time'	=> time(),
				);
				$this->mBase->insert('products_loss',$data);
			}
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
}
