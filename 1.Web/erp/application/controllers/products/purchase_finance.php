<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class purchase_finance extends Base 
{
	private $active_top_tag;
	
	public $statusMap = array(
		0	=> '新申请财务单',
		1	=> '已提交财务单',
		2	=> '已审核财务单',
		3	=> '已结算财务单',
	);

	public $checkoutMap = array(
		0	=> '其他',
		1	=> '现金',
		2	=> '支付宝',
		3	=> '银行转账',
		4	=> '支票',
	);

	public $actionMap = array(
		'create'	=> 0,
		'confirm'	=> 1,
		'purchase'	=> 2,
		'receive'	=> 3,
		'check'		=> 4,
		'transfer'	=> -1,
	);

	public function __construct(){
		parent::__construct();
		$this->active_top_tag = 'product';
	}
	
	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('status','page','size','keyword');
		$this->checkParams('get',$must,$fields);
		if( !array_key_exists($this->params['status']?$this->params['status']:'', $this->statusMap) ){
			$this->params['status'] = current(array_keys($this->statusMap));
		}
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(10,min(100,intval($params['size'])));
		//get purchases
		$condition = array(
			'AND' => array('status='.$params['status']),
		);
		if(!empty($params['keyword'])){
			$condition['AND'][] = "id='{$params['keyword']}'";
		}
		$res = $this->mBase->getList('purchases_finance',$condition,'*','id DESC',$page,$size);
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		$data['statusMap'] = $this->statusMap;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'purchase_finance';
		$this->_view('common/header', $tags);
		$this->_view('product/purchase_finance_list', $data);
		$this->_view('common/footer');
	}
	
	public function add() {
		$data = array();
		//get parameters
		$must = array('purchase_id');
		$fields = array();
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		//check purchase
		$data['purchase'] = $this->mBase->getSingle('purchases','id',$params['purchase_id']);
		if( !$data['purchase'] ){
			$this->showMsgDialog(1000,'采购单不存在');
			return;
		}
		if( $data['purchase']['is_del']==1 ){
			$this->showMsgDialog(1000,'采购单已经被删除');
			return;
		}
		if( $data['purchase']['finance_status']>0 ){
			$this->showMsgDialog(1000,'采购单已经拥有财务单');
			return;
		}
		$data['checkoutMap'] = $this->checkoutMap;
		//get purchase total fee
		$money_buy = 0;
		$condition = array(
			'AND' => array("purchase_id='".$params['purchase_id']."'"),
		);
		$res = $this->mBase->getList('purchases_detail',$condition);
		if( $res ){
			foreach ( $res as $row ){
				$money_buy += ($row['price_plan']*$row['amount_plan']);
			}
		}
		$data['money_buy'] = $money_buy;
		//display templates
		$this->_view('product/purchase_finance_add', $data);
	}
	
	public function edit() {
		$data = array();
		//get parameters
		$must = array('purchase_id');
		$fields = array();
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		//check purchase_finance
		$data['single'] = $this->mBase->getSingle('purchases_finance','id',$params['purchase_id']);
		if( !$data['single'] ){
			$this->output(array('err_no'=>1000,'err_msg'=>'purchase finance is not exists'));
		}
		if( $data['single']['is_del']==1 ){
			$this->output(array('err_no'=>1000,'err_msg'=>'purchase finance is removed'));
		}
		if( $data['single']['status']>1 ){
			$this->output(array('err_no'=>1000,'err_msg'=>'purchase can not be edit'));
		}
		$data['checkoutMap'] = $this->checkoutMap;
		//display templates
                //print_r($data['single']);die;
		$this->_view('product/purchase_finance_edit', $data);
	}
	
	public function detail() {
		$data = array();
		//get parameters
		$must = array('purchase_id');
		$fields = array();
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		//check purchase_finance
		$finance_id = str_replace('PUR', 'FIN', $params['purchase_id']);
		$data['single'] = $this->mBase->getSingle('purchases_finance','id',$finance_id);
		if( !$data['single'] ){
			$this->output(array('err_no'=>1000,'err_msg'=>'采购财务多不存在'));
		}
		//Common Data
		$data['checkoutMap'] = $this->checkoutMap;
		//get purchase total fee
		$data['details'] = array();
		$money_real = 0;
		$condition = array(
			'AND' => array("purchase_id='".$params['purchase_id']."'"),
		);
		$res = $this->mBase->getList('purchases_detail',$condition);
		if( $res ){
			foreach ( $res as $row ){
				$money_real += ($row['price_real']*$row['amount_real']);
				$data['details'][$row['type']][] = $row;
			}
		}
		$data['money_real'] = $money_real;
		//display templates
		$this->_view('product/purchase_finance_detail', $data);
	}
	
	public function checkout() {
		$data = array();
		//get parameters
		$must = array('purchase_id');
		$fields = array();
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		//check purchase
		$data['purchase'] = $this->mBase->getSingle('purchases','id',$params['purchase_id']);
		if( !$data['purchase'] || $data['purchase']['is_del']==1 ){
			$this->showMsgDialog(1000,'采购单不存在');
			return;
		}
		//check purchase_finance
		$finance_id = str_replace('PUR', 'FIN', $params['purchase_id']);
		$data['single'] = $this->mBase->getSingle('purchases_finance','id',$finance_id);
		if( !$data['single'] ){
			$this->showMsgDialog(1000,'采购财务单不存在');
		}
		//Common Data
		$data['checkoutMap'] = $this->checkoutMap;
		//get purchase total fee
		$data['details'] = array();
		$money_real = 0;
		$condition = array(
			'AND' => array("purchase_id='".$params['purchase_id']."'"),
		);
		$res = $this->mBase->getList('purchases_detail',$condition);
		if( $res ){
			foreach ( $res as $row ){
				$money_real += ($row['price_real']*$row['amount_real']);
				$data['details'][$row['type']][] = $row;
			}
		}
		$data['money_real'] = $money_real;
		//display templates
		$this->_view('product/purchase_finance_checkout', $data);
	}

	public function doAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('purchase_id','money_apply');
			$fields = array('pay_type','pay_alipay','pay_bank','pay_bankno','pay_checkno');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			//check parameters
			$purchase = $this->mBase->getSingle('purchases','id',$params['purchase_id']);
			if( !$purchase ){
				$ret = array('err_no'=>1000,'err_msg'=>'采购单不存在');
				break;
			}
			if( $purchase['is_del']==1 ){
				$ret = array('err_no'=>1000,'err_msg'=>'采购单已经删除');
				break;
			}
			if( $purchase['status']>1 ){
				$ret = array('err_no'=>1000,'err_msg'=>'采购单已经拥有财务单');
				break;
			}
			//get purchase money
			$money_buy = 0;
			$condition = array(
				'AND' => array("purchase_id='".$params['purchase_id']."'"),
			);
			$res = $this->mBase->getList('purchases_detail',$condition,'*');
			if( $res ){
				foreach ( $res as $row ){
					$money_buy += $row['amount_plan']*$row['price_plan'];
				}
			}
			//insert data
			$finance_id = str_replace('PUR', 'FIN', $params['purchase_id']);
			$data = array(
				'id'			=> $finance_id,
				'purchase_id'	=> $params['purchase_id'],
				'money_buy'		=> $money_buy,
				'money_apply'	=> $params['money_apply'],
				'pay_type'		=> $params['pay_type'],
				'pay_alipay'	=> $params['pay_alipay'],
				'pay_bank'		=> $params['pay_bank'],
				'pay_bankno'	=> $params['pay_bankno'],
				'pay_checkno'	=> $params['pay_checkno'],
				'apply_eid'		=> self::$user['id'],
				'apply_name'	=> self::$user['username'],
				'apply_time'	=> time(),
				'status'		=> 0,
			);
			$t = $this->mBase->insert('purchases_finance',$data);
			if( $t ){
				//更新采购单财务状态
				$data = array('finance_status'=>1);
				$this->mBase->update('purchases',$data,array('id'=>$params['purchase_id']));
				//添加财务单待审核任务
				$this->load->model('Task_model', 'mTask');
				$this->mTask->addNewTask(4,$finance_id,parent::$user);
			}
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function doEdit() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('id','money_apply','pay_bank','pay_bankno');
			$fields = array();
			//$fields = array('pay_alipay','pay_bank','pay_bankno','pay_checkno');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			//check parameters
			$id = $params['id'];
			$single = $this->mBase->getSingle('purchases_finance','id',$id);
			if( !$single ){
				$ret = array('err_no'=>1000,'err_msg'=>'采购财务单不存在');
				break;
			}
			//update data
			$data = array(
				'money_apply'	=> $params['money_apply'],
                                'pay_bank'      => $params['pay_bank'],
                                'pay_bankno'    => $params['pay_bankno']
			);
			$this->mBase->update('purchases_finance',$data,array('id'=>$id));
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function doCheckout() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('purchase_id','finance_id','money_pay');
			$fields = array('pay_type','pay_alipay','pay_bank','pay_bankno','pay_checkno');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			//check parameters
			$single = $this->mBase->getSingle('purchases_finance','id',$params['finance_id']);
			if( !$single ){
				$ret = array('err_no'=>1000,'err_msg'=>'采购财务单不存在');
				break;
			}
			if( $single['status']!=2 ){
				$ret = array('err_no'=>1000,'err_msg'=>'采购财务单不可操作');
				break;
			}
			$data = array(
				'money_pay'		=> $params['money_pay'],
				'pay_type'		=> $params['pay_type'],
				'pay_alipay'	=> $params['pay_alipay'],
				'pay_bank'		=> $params['pay_bank'],
				'pay_bankno'	=> $params['pay_bankno'],
				'pay_checkno'	=> $params['pay_checkno'],
				'check_eid'		=> self::$user['id'],
				'check_name'	=> self::$user['username'],
				'check_time'	=> time(),
				'status'		=> 3,
			);
			$rows = $this->mBase->update('purchases_finance',$data,array('id'=>$params['finance_id']));
			if( $rows ){
				$data = array(
					'status'			=> 5,
					'finance_status'	=> 3, 
				);
				$this->mBase->update('purchases',$data,array('id'=>$params['purchase_id']));
				//Add Purchase Action Log
				$this->addAction($params['purchase_id'],'check');
			}
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	/**
	 * @desc Add Purchase Action 
	 * @param 0:create, 1:confirm, 2:purchase, 3:receive, 4:check, -1:transfer
	 */
	public function addAction( $id, $action='', $transfer_eid=0, $transfer_name='' ) {
		if( array_key_exists($action, $this->actionMap) ){
			$action = $this->actionMap[$action];
			$des = '';
			$username = parent::$user['username'];
			switch ( $action ){
				case 0:
					$des = '采购单'.$id.'被创建；当前处理人：'.$username;
					break;
				case 1:
					$des = '采购单'.$id.'被确认；当前处理人：'.$username;
					break;
				case 2:
					$des = '采购单'.$id.'开始采购；当前处理人：'.$username;
					break;
				case 3:
					$des = '采购单'.$id.'采购完成；当前处理人：'.$username;
					break;
				case 4:
					$des = '采购单'.$id.'结算完成；当前处理人：：'.$username;
					break;
				case -1:
					$des = '采购单'.$id.'被转交给'.$transfer_name.'；当前处理人：'.$username;
					break;
			}
			$data = array(
				'purchase_id'	=> $id,
				'action'		=> $action,
				'des'			=> $des,
				'create_eid'	=> parent::$user['id'],
				'create_name'	=> parent::$user['username'],
				'create_time'	=> time(),
			);
			$this->mBase->insert('purchases_action',$data);
		}
	}
}
