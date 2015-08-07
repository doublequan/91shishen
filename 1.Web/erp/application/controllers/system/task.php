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
	
	private $active_top_tag = 'system';
	
	private $task_types;
			
	public function __construct(){
		parent::__construct();
		$this->task_types = getConfig('task_types');
	}
	
	public function index(){
		$data = array();
		//get data
		$condition = array();
		$res = $this->mBase->getList('tasks_employee',$condition,'*','sort ASC');
		$data['employees'] = array();
		$data['results'] = array();
		if( $res ){
			foreach( $res as $row ){
				if( !isset($data['employees'][$row['eid']]) ){
					$t = $this->mBase->getSingle('employees','id',$row['eid']);
					$data['employees'][$row['eid']] = $t ? $t['username'] : '未知员工';
				}
				$row['username'] = $data['employees'][$row['eid']];
				$data['results'][$row['type']][] = $row;
			}
		}
		//Common Data
		$data['task_types'] = $this->task_types;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'task';
		$this->_view('common/header', $tags);
		$this->_view('system/task_employee',$data);
		$this->_view('common/footer');
	}
	
	public function resort(){
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('type','sort');
			$fields = array();
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$type = intval($params['type']);
			//check sort
			$str = trim($params['sort']);
			if( !$str ){
				$ret = array('err_no'=>1000,'err_msg'=>'排序值为空');
				break;
			}
			//get task
			if( !array_key_exists($type, $this->task_types) ){
				$ret = array('err_no'=>1000,'err_msg'=>'任务类型不存在');
				break;
			}
			$results = array();
			foreach( explode(',',$str) as $t ){
				$arr = explode(':', $t);
				if( isset($arr[0]) && isset($arr[1]) ){
					$results[$arr[0]] = $arr[1];
				}
			}
			asort($results);
			$i=0;
			foreach( $results as $eid=>$v ){
				$i++;
				$data = array('sort'=>$i);
				$this->mBase->update('tasks_employee',$data,array('type'=>$type,'eid'=>$eid));
			}
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
    	$this->output($ret);
	}
	
	public function add(){
		$data = array();
		//Common Data
		$data['task_types'] = $this->task_types;
		//display templates
		$this->_view('system/task_employee_add', $data);
	}
	
	public function doAdd(){
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('type','eid');
			$fields = array();
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$type = intval($params['type']);
			$eid = intval($params['eid']);
			//get task
			if( !array_key_exists($type, $this->task_types) ){
				$ret = array('err_no'=>1000,'err_msg'=>'任务类型不存在');
				break;
			}
			$condition = array(
				'AND' => array('type='.$type),
			);
			$res = $this->mBase->getList('tasks_employee',$condition,'*','sort ASC');
			$results = array();
			if( $res ){
				foreach ( $res as $row ){
					$results[$row['eid']] = $row;
				}
			}
			if( isset($results[$eid]) ){
				$ret = array('err_no'=>1000,'err_msg'=>'此处理人已经属于当前任务类型');
				break;
			}
			$data = array(
				'type'	=> $type,
				'eid'	=> $eid,
				'sort'	=> count($results)+1,
			);
			$this->mBase->insert('tasks_employee',$data);
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function delete(){
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
    	do{
    		//get parameters
    		$must = array('id');
    		$fields = array();
    		$this->checkParams('post',$must,$fields);
    		$params = $this->params;
    		$id = intval($params['id']);
    		//check parameter
    		$single = $this->mBase->getSingle('tasks_employee','id',$id);
    		if( !$single ){
    			$ret = array('err_no'=>1000,'err_msg'=>'数据不存在');
    			break;
    		}
    		//delete
    		$this->mBase->delete('tasks_employee',array('id'=>$id),true);
    		//reset cache
    		$cache_key = 'TASK_EMPLOYEES_TYPE_'.$single['type'];
    		$condition = array(
    			'AND' => array('type='.$single['type']),
    		);
    		$res = $this->mBase->getList('tasks_employee',$condition,'*','sort ASC');
    		if( !$res ){
    			Common_Cache::delete($cache_key);
    		} else {
    			//reset sort
    			$i=0;
    			foreach( $res as $row ){
    				$i++;
    				$data = array('sort'=>$i);
    				$this->mBase->update('tasks_employee',$data,array('id'=>$row['id']));
    			}
    			//reset cache
    			$data = array();
    			foreach ( $res as $row ){
    				$data[] = $row['eid'];
    			}
    			Common_Cache::save($cache_key, $data, 3600);
    		}
    		$ret = array('err_no'=>0,'err_msg'=>'操作成功');
    	} while(0);
    	$this->output($ret);
	}
}