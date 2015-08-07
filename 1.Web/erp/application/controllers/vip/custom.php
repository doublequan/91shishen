<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class custom extends Base 
{
	private $active_top_tag;
	private $statusMap;

	public function __construct(){
		parent::__construct();

		$this->active_top_tag = 'vip';
		$this->statusMap = array(
			0 	=> '全部',
			1	=> '新增',
			2	=> '已确认',
			3	=> '已删除',
		);
	}

	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('status','keyword','page','size');
		$this->checkParams('get',$must,$fields);
		$this->params['status'] = intval($this->params['status']);
		if( !array_key_exists($this->params['status'], $this->statusMap) ){
			$this->params['status'] = current(array_keys($this->statusMap));
		}
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));

		$condition = array();
		if($params['status'] > 0){
			$condition['AND'] = array('status='.$params['status']);
		}
		$k = $params['keyword'];
		if( isset($k) ){
			$condition['AND'][] = "id LIKE '%".$k."%'";
		}
		$res = $this->mBase->getList('vip_customs',$condition,'*','id DESC',$page,$size);

		$users = array();
		if( $res->results ){
			$companys_res = $this->mBase->getList('vip_companys', array('AND' => array('is_del=0')));
			if( $companys_res ){
				foreach ( $companys_res as $row ){
					$companys[$row['id']] = $row;
				}
			}

			foreach( $res->results as &$row ){
				$row['username'] = '未知大客户';
				$row['company_name'] = '未知公司名称';
				if( !isset($users[$row['uid']]) ){
					$t = $this->mBase->getSingle('vip_users','id',$row['uid']);
					if( $t ){
						$users[$row['uid']] = $t['username'];
						$company_ids[$row['uid']] = $t['company_id'];
						$row['username'] = $t['username'];
						$row['company_name'] = $companys[$t['company_id']]['name'];
					}
				} else {
					$row['username'] = $users[$row['uid']];
					$row['company_name'] = $companys[$company_ids[$row['uid']]]['name'];
				}
				if( isset($k) ){
					$row['show_id'] = str_replace($k, '<font color="red">'.$k.'</font>', $row['id']);
				} else {
					$row['show_id'] = $row['id'];
				}
			}
			unset($row);
		}
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//Common Data
		$data['statusMap'] = $this->statusMap;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'custom';
		$this->_view('common/header', $tags);
		$this->_view('vip/custom_list', $data);
		$this->_view('common/footer');
	}
	
	public function add(){
		$data = array();
		$data['companys'] = $this->mBase->getList('vip_companys');

		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'custom';
		$this->_view('vip/custom_add', $data);
	}
	
	public function edit(){
		$data = array();
		//get parameters
		$must = array('id');
		$fields = array();
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		//get info
		$single = $this->mBase->getSingle('vip_customs','id',$params['id']);

		$company = $this->mBase->getSingle('vip_companys','id',$single['company_id']);

		$single['company_name'] = '未知';
		if(is_array($company) && count($company) > 0){
			$single['company_name'] = $company['name'];
		}

		$user = $this->mBase->getSingle('vip_users','id',$single['uid']);
		$single['user_name'] = '未知';
		if(is_array($user) && count($user) > 0){
			$single['user_name'] = $user['username'];
		}

		$data['single'] = $single;
		$data['single']['detail'] = $this->mBase->getList('vip_customs_detail',array('AND'=>array("custom_id='{$single['id']}'")));
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'custom';
		$this->_view('vip/custom_edit', $data);
	}
	
	public function doAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
        do{
        	//get parameters
        	$must = array('company_id','uid','name','amount','unit');
        	$fields = array('price_single','note');
        	$this->checkParams('post',$must,$fields);
        	$params = $this->params;
        	//check parameters
        	$user = $this->mBase->getSingle('vip_users','id',$params['uid']);
        	if( !$user ){
        		$ret = array('err_no'=>3006,'err_msg'=>'user is not exists');
        		break;
        	}
        	
        	$custom_id = createBusinessID('CUS');
        	$data = array(
				'id'          => $custom_id,
				'company_id'  => $params['company_id'],
				'uid'         => $params['uid'],
				'create_eid'  => parent::$user['id'],
				'create_name' => parent::$user['username'],
				'create_time' => time(),
				'status'      => 1,
        	);
        	$single = $this->mBase->insert('vip_customs',$data);
        	//insert products img
        	if( $single ){
        		$data = array();
        		$length = count($params['name']);
        		for( $i=0; $i<$length; $i++ ){
        			$amount = isset($params['amount'][$i]) ? $params['amount'][$i] : 0;
        			$price_single = isset($params['price_single'][$i]) ? $params['price_single'][$i] : 0;     			
        			$data[] = array(
						'custom_id' => $custom_id,
						'name'      => isset($params['name'][$i]) ? $params['name'][$i] : '',
						'amount'    => $amount,
        				'unit'		=> isset($params['unit'][$i]) ? $params['unit'][$i] : '',
       					'price_single' 	=> $price_single,
       					'price_total' 	=> $amount*$price_single,
						'note'      => isset($params['note'][$i]) ? $params['note'][$i] : '',
        			);
        		}
        		$this->mBase->insertMulti('vip_customs_detail',$data);
        		//add vip config task
        		$this->load->model('Task_model', 'mTask');
        		$this->mTask->addNewTask(3,$custom_id,parent::$user);
        	}
        	$ret = array('err_no'=>0,'err_msg'=>'操作成功');
        } while(0);
        $this->output($ret);
	}
	
	public function doEdit() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
    	do{
    		//get parameters
        	$must = array('id','name','amount','unit');
        	$fields = array('price_single','note');
        	$this->checkParams('post',$must,$fields);
        	$params = $this->params;
        	//check parameters
        	$single = $this->mBase->getSingle('vip_customs','id',$params['id']);
        	
        	if($single){
	        	$data = array(
					'status'      => 1,
	        	);
	        	$this->mBase->update('vip_customs',$data,array('id'=>$single['id']));
	        	//delete
	        	$this->mBase->delete('vip_customs_detail',array('custom_id'=>$single['id']), true);
	        	//insert
	        	if( $single ){
	        		$data = array();
	        		$length = count($params['name']);
	        		for( $i=0; $i<$length; $i++ ){
	        			$amount = isset($params['amount'][$i]) ? $params['amount'][$i] : 0;
	        			$price_single = isset($params['price_single'][$i]) ? $params['price_single'][$i] : 0;     			
	        			$data[] = array(
							'custom_id' => $single['id'],
							'name'      => isset($params['name'][$i]) ? $params['name'][$i] : '',
							'amount'    => $amount,
	        				'unit'		=> isset($params['unit'][$i]) ? $params['unit'][$i] : '',
	       					'price_single' 	=> $price_single,
	       					'price_total' 	=> $amount*$price_single,
							'note'      => isset($params['note'][$i]) ? $params['note'][$i] : '',
	        			);
	        		}
	        		$this->mBase->insertMulti('vip_customs_detail',$data);
	        	}
	        	$ret = array('err_no'=>0,'err_msg'=>'操作成功');
        	}
    	} while(0);
    	$this->output($ret);
	}
	
	public function detail(){
		$data = array();
		//get parameters
		$must = array('id');
		$fields = array();
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		//get info
		$single = $this->mBase->getSingle('vip_customs','id',$params['id']);

		$company = $this->mBase->getSingle('vip_companys','id',$single['company_id']);
		$single['company_name'] = $company['name'];

		$user = $this->mBase->getSingle('vip_users','id',$single['company_id']);
		$single['user_name'] = $user['username'];

		$data['single'] = $single;
		$data['single']['detail'] = $this->mBase->getList('vip_customs_detail',array('AND'=>array("custom_id='{$single['id']}'")));
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'custom';
		$this->_view('vip/custom_detail', $data);
	}

	public function doEditStatus(){
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
    	do{
    		//get parameters
    		$must = array('id','status');
    		$fields = array();
    		$this->checkParams('get',$must,$fields);
    		$params = $this->params;
    		//update userinfo
    		$data = array(
    			'status' => $params['status'],
    		);
    		$this->mBase->update('users',$data,array('id'=>$params['id']));
    		$ret = array('err_no'=>0,'err_msg'=>'操作成功');
    	} while(0);
    	$this->output($ret);
	}
}
