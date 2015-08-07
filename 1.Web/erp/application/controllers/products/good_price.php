<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class good_price extends Base 
{
	private $active_top_tag = 'product';

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$data = array();
		//init parameters
		$must = array('good_id');
		$fields = array('start','end','page','size');
		$this->checkParams('get',$must,$fields);
		$params = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));

		if(empty($params['start'])){
			$params['start'] = date('Y-m-d', strtotime('-15 day'));
		}
		if(empty($params['end'])){
			$params['end'] = date('Y-m-d', strtotime('+1 day'));
		}

		$data['params'] = $params;
		$data['single'] = $data['results'] = array();
		$single = $this->mBase->getSingle('goods', 'id', $params['good_id']);
		if(is_array($single) && count($single) > 0){
			$data['single'] = $single;
			//get data
			$condition = array(
				'AND' => array('good_id='.$params['good_id']),
			);
			$start = strtotime($params['start']);
			if( $start ){
				$condition['AND'][] = "create_time>={$start}";
			}
			$end = strtotime($params['end']) + 86400;
			if( $end ){
				$condition['AND'][] = "create_time<={$end}";
			}
			$res = $this->mBase->getList('goods_price',$condition,'*','create_time');
			$data['results'] = $res;

			$chart_data = array();
			foreach ($res as $value) {
				$chart_data[] = array( intval($value['create_time'])*1000, doubleval($value['price']));
			}
			$data['chart_data'] = $chart_data;
		}

		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'good';
		$this->_view('common/header', $tags);
		$this->_view('product/good_price_list', $data);
		$this->_view('common/footer');
	}
	
	public function doAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('good_id','price');
			$fields = array();
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$params['good_id'] = intval($params['good_id']);
			//check parameters
			$t = $this->mBase->getSingle('goods','id',$params['good_id']);
			if( !$t ){
				$ret = array('err_no'=>1000,'err_msg'=>'原料不存在');
				break;
			}
			$params['price'] = floatval($params['price']);
			if( !$params['price'] ){
				$ret = array('err_no'=>1000,'err_msg'=>'价格不能为0');
				break;
			}
			//insert price
			$time = time();
			$data = array(
				'good_id'		=> $params['good_id'],
				'day'			=> date('Y-m-d H:i:s',$time),
				'price'			=> $params['price'],
				'create_eid'	=> parent::$user['id'],
				'create_name'	=> parent::$user['username'],
				'create_time'	=> $time,
			);
			$t = $this->mBase->insert('goods_price',$data);
			//update good price
			if( $t ){
				$data = array(
					'price'			=> $params['price'],
				);
				$this->mBase->update('goods',$data,array('id'=>$params['good_id']));
			}
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
}
