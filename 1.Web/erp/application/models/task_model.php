<?php

require_once dirname(__FILE__).'/base_model.php';

class Task_model extends Base_model
{
	public function addNewTask( $type, $business_id, $user ){
		//Get All Type Users
		$sql = 'SELECT eid FROM tasks_employee WHERE type='.$type.' ORDER BY sort ASC LIMIT 1';
		$eid = intval($this->getOne($sql));
		$eid = $eid ? $eid : 1;
		/**
		 * Begin Transcation
		 */
		$this->db->trans_start();
		/**
		 * Insert New Task
		 */
		$data = array(
			'eid'			=> $eid,
			'type'			=> $type,
			'business_id'	=> $business_id,
			'status'		=> 1,
			'create_eid'	=> $user['id'],
			'create_name'	=> $user['username'],
			'create_time'	=> time(),
		);
		$task = $this->insert('tasks', $data);
		/**
		 * Insert Task Action
		 */
		$des = '';
		if( $type==1 ){
			$des = '有新的用户订单需要确认';
		} elseif ( $type==2 ){
			$des = '有新的大客户订单需要确认';
		} elseif ( $type==3 ){
			$des = '有新的大客户定制需求需要确认';
		} elseif ( $type==4 ){
			$des = '有新的财务申请单需要审批';
		} elseif ( $type==5 ){
			$des = '有新的采购申请单需要审批';
		} elseif ( $type==6 ){
			$des = '有新的调度申请单需要审批';
		}
		$data = array(
			'task_id'		=> $task['id'],
			'action'		=> 1,
			'des'			=> $des,
			'create_eid'	=> $user['id'],
			'create_name'	=> $user['username'],
			'create_time'	=> time(),
		);
		$this->insert('tasks_action', $data);
		//Rollback OR Commit
		if( $this->db->trans_status()===FALSE ){
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}
}
