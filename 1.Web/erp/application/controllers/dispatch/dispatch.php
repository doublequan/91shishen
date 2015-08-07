<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class Dispatch extends Base
{
	
	protected $active_top_tag  = 'dispatch';
	
	public $statusMap = array(
		-1 => '全部调度单',
		0 => '编辑中调度单',
		1 => '未审核调度单',
		2 => '已审核调度单',
		3 => '已出库调度单',
		4 => '已入库调度单',
	);
	
	public $actionMap = array(
		1 => '新建调度单',
		2 => '编辑调度单',
		3 => '提审调度单',
		4 => '打回调度单',
		5 => '审核通过调度单',
		6 => '出库调度单',
		7 => '入库调度单',
	);

	public function __construct(){
		parent::__construct();
		$data = array();
		$data['statusMap'] = $this->statusMap;
		$data['actionMap'] = $this->actionMap;
		$data['active_top_tag'] = $this->active_top_tag;
		$this->load->vars($data);
	}
	
	public function addAction( $dispatch_id, $action ){
		if( $dispatch_id && $action ){
			$data = array(
				'dispatch_id'	=> $dispatch_id,
				'action'		=> $action,
				'create_eid'	=> self::$user['id'],
				'create_name'	=> self::$user['username'],
				'create_time'	=> time(),
			);
			$this->mBase->insert('dispatchs_action',$data);
		}
	}
}