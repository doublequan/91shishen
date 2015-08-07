<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

/**
 * @author FeiYan
 * @param $type=1	order_confirm,
 * @param $type=2	vip_order_confirm
 * @param $type=3	vip_custom_confirm
 * @param $type=4	finance_apply
 * @param $type=5	purchase_apply
 * @param $type=6	dispatch_apply
 *
 */
class task extends Base {
	
	private $active_top_tag = 'home';
	
	private $task_types;
	
	private $task_status_types;
	
	private $task_action_types = array(
		1	=> '新建',
		2	=> '设置为开始处理',
		3	=> '设置为待处理',
		4	=> '设置为延期',
		5	=> '设置为完成',
		6	=> '设置为放弃',
		7	=> '移交他人',
		8	=> '通过任务',
		9	=> '不通过任务',
	);
		
	public function __construct(){
		parent::__construct();
		$this->task_types = getConfig('task_types');
		$this->task_status_types = getConfig('task_status_types');
	}
	
	public function index(){
		$data = array();
		//get parameters
		$must = array();
		$fields = array('type','status','page','size');
		$this->checkParams('get',$must,$fields);
		$this->params['status'] = intval($this->params['status']);
		if( !array_key_exists($this->params['status'], $this->task_status_types) ){
			$this->params['status'] = current(array_keys($this->task_status_types));
		}
		$this->params['type'] = intval($this->params['type']);
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		//get data
		$condition = array(
			'AND' => array('eid='.parent::$user['id'],'status='.$params['status']),
		);
		if( $params['type'] ){
			$condition['AND'][] = 'type='.intval($params['type']);
		}
		$res = $this->mBase->getList('tasks',$condition,'*','create_time DESC',$page,$size);
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//Common Data
		$data['task_types'] = $this->task_types;
		$data['task_status_types'] = $this->task_status_types;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'task_'.$params['status'];
		$tags['task_status_types'] = $this->task_status_types;
		$this->_view('common/header', $tags);
		$this->_view('home/task_list',$data);
		$this->_view('common/footer');
	}
	
	public function detail(){
		$data = array();
		//get parameters
		$must = array('task_id');
		$fields = array();
		$this->checkParams('get',$must,$fields);
		$this->params['task_id'] = intval($this->params['task_id']);
		$params = $data['params'] = $this->params;
		//get order
		$data['single'] = $this->mBase->getSingle('tasks','id',$params['task_id']);
		if( !$data['single'] ){
			$ret = array('err_no'=>1000,'err_msg'=>'task is not exists');
			$this->output($ret);
		}
		//get task actions
		$condition = array(
			'AND' => array('task_id='.$params['task_id']),
		);
		$data['actions'] = $this->mBase->getList('tasks_action',$condition);
		//get employees
		$condition = array(
			'AND' => array('is_del=0','id<>'.parent::$user['id']),
		);
		$data['employees'] = $this->mBase->getList('employees',$condition);
		//Common Data
		$data['task_types'] = $this->task_types;
		$data['task_status_types'] = $this->task_status_types;
		$data['task_action_types'] = $this->task_action_types;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'task_'.$data['single']['status'];
		$tags['task_status_types'] = $this->task_status_types;
		$this->_view('common/header', $tags);
		$this->_view('home/task_detail',$data);
		$this->_view('common/footer');
	}
	
	public function deal(){
		$ret = array('err_no'=>1000,'err_msg'=>'system error');
		do{
			//get parameters
			$must = array('id','type','next_status','only_task');
			$fields = array();
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			foreach( $params as &$v ){
				$v = intval($v);
			}		
			unset($v);
			//get task
			if( !$params['id'] ){
				$ret = array('err_no'=>1000,'err_msg'=>'任务参数错误');
				break;
			}
			$task = $this->mBase->getSingle('tasks','id',$params['id']);
			if( !$task ){
				$ret = array('err_no'=>1000,'err_msg'=>'任务不存在');
				break;
			}
			if( !in_array($task['status'], array(1,2,3,4)) ){
				$ret = array('err_no'=>1000,'err_msg'=>'任务当前为不可变更状态');
				break;
			}
			if( !array_key_exists($params['type'], $this->task_types) ){
				$ret = array('err_no'=>1000,'err_msg'=>'未知任务类型');
				break;
			}
			//UPDATE TASK
			$data = array(
				'status'	=> $params['next_status'],
				'last_eid'	=> self::$user['id'],
				'last_name'	=> self::$user['username'],
				'last_time'	=> time(),
			);
			$rows = $this->mBase->update('tasks',$data,array('id'=>$params['id']));
			if( !$rows ){
				$ret = array('err_no'=>1000,'err_msg'=>'任务更新失败，请重试');
				break;
			}
			//ADD NEW TASK ACTION
			if( $params['next_status']==5 ){
				//ADD FINISH ACTION
				$data = array(
					'task_id'		=> $params['id'],
					'action'		=> 8,
					'des'			=> '',
					'create_eid'	=> self::$user['id'],
					'create_name'	=> self::$user['username'],
					'create_time'	=> time(),
				);
				$this->mBase->insert('tasks_action',$data);
			}
			$data = array(
				'task_id'		=> $params['id'],
				'action'		=> $params['next_status'],
				'des'			=> '',
				'create_eid'	=> parent::$user['id'],
				'create_name'	=> parent::$user['username'],
				'create_time'	=> time(),
			);
			$this->mBase->insert('tasks_action',$data);
			//UPDATE BUSINESS
			if( $params['only_task']==0 ){
				if( $params['type']==1 ){
					//UPDATE USER ORDER
					$data = array(
						'order_status' => 1,
					);
					$rows = $this->mBase->update('orders',$data,array('id'=>$task['business_id']));
					if( $rows ){
						$data = array(
							'order_id'		=> $task['business_id'],
							'status'		=> 1,
							'des'			=> '用户订单被 '.parent::$user['username'].' 确认',
							'create_eid'	=> parent::$user['id'],
							'create_name'	=> parent::$user['username'],
							'create_time'	=> time(),
						);
						$this->mBase->insert('orders_action',$data);
					}
				} elseif ( $params['type']==2 ){
					//UPDATE VIP ORDER
					$data = array(
						'order_status' => 1,
					);
					$rows = $this->mBase->update('vip_orders',$data,array('id'=>$task['business_id']));
					if( $rows ){
						$data = array(
							'order_id'		=> $task['business_id'],
							'status'		=> 1,
							'des'			=> '大客户订单被 '.parent::$user['username'].' 确认',
							'create_eid'	=> parent::$user['id'],
							'create_name'	=> parent::$user['username'],
							'create_time'	=> time(),
						);
						$this->mBase->insert('vip_orders_action',$data);
					}
				} elseif ( $params['type']==3 ){
					//UPDATE VIP CUSTOM
					$data = array(
						'status'	=> 2,
						'deal_eid'	=> parent::$user['id'],
						'deal_name'	=> parent::$user['username'],
						'deal_time'	=> time(),
					);
					$rows = $this->mBase->update('vip_customs',$data,array('id'=>$task['business_id']));
				} elseif ( $params['type']==4 ){
					//UPDATE FINANCE
					$data = array(
						'status'		=> 2,
						'approve_eid'	=> parent::$user['id'],
						'approve_name'	=> parent::$user['username'],
						'approve_time'	=> time(),
					);
					$this->mBase->update('purchases_finance',$data,array('id'=>$task['business_id']));
					$purchase_id = str_replace('FIN', 'PUR', $task['business_id']);
					$data = array(
						'finance_status' => 2,
					);
					$this->mBase->update('purchases',$data,array('id'=>$purchase_id));
				} elseif ( $params['type']==5 ){
					//UPDATE PURCHASE
					$data = array(
						'status'	=> 2,
					);
					$rows = $this->mBase->update('purchases',$data,array('id'=>$task['business_id']));
				} elseif ( $params['type']==6 ){
					//UPDATE DISPATCH
					$data = array(
						'status'		=> 1,
						'confirm_eid'	=> parent::$user['id'],
						'confirm_name'	=> parent::$user['username'],
						'confirm_time'	=> time(),
					);
					$rows = $this->mBase->update('vip_customs',$data,array('id'=>$task['business_id']));
				}
			}
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
    	$this->output($ret);
	}
	
	public function transfer()
	{
		$id = $this->input->post('id');
		$eid = $this->input->post('eid');
		if(empty($eid) || empty($id)){
			die('{"error":1, "msg": "信息错误，请刷新页面重试！"}');
		}
		$op_rst = $this->mBase->update('tasks', array('eid'=>$eid), array('id' => $id));
		die('{"error":0, "msg": ""}');
	}

	public function delete(){
		$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array('key');
    		$fields = array();
    		$this->checkParams('get',$must,$fields);
    		$params = $this->params;
    		//check parameter
    		$cache_key = 'SYSTEM_CONFIG_'.$params['key'];
    		Common_Cache::delete($cache_key);
    		$ret = array('err_no'=>0,'err_msg'=>'success');
    	} while(0);
    	$this->output($ret);
	}
}