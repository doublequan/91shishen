<?php
/**
 * 日志记录
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-28
 */
require_once 'common.php';
class Logs extends Common{
    
    //资金记录
    public function money(){
        $page = max(1, intval($this->input->get('page')));
		$userInfo = $this->session->userdata('userinfo');
		$logs_sql = "SELECT * FROM users_log_money WHERE uid = {$userInfo['uid']} ORDER BY id DESC";
		$data = $this->Base_model->pagerQuery($logs_sql, $page, 10);
		$pager = parent::_formatPager(base_url('member/logs/money'), $data->pager);
		$this->load->view('member/logs_money', array('data'=>$data->results, 'pager'=>$pager, 'currMenu'=>'member_logs_money'));
    }
    
    //积分记录
    public function score(){
        $page = max(1, intval($this->input->get('page')));
        $userInfo = $this->session->userdata('userinfo');
        $logs_sql = "SELECT * FROM users_log_score WHERE uid = {$userInfo['uid']} ORDER BY id DESC";
        $data = $this->Base_model->pagerQuery($logs_sql, $page, 10);
        $pager = parent::_formatPager(base_url('member/logs/score'), $data->pager);
        $this->load->view('member/logs_score', array('data'=>$data->results, 'pager'=>$pager, 'currMenu'=>'member_logs_score'));
    }
    
    //充值记录
    public function charge(){
    	$page = max(1, intval($this->input->get('page')));
    	$userInfo = $this->session->userdata('userinfo');
    	$logs_sql = "SELECT * FROM users_charge WHERE uid = {$userInfo['uid']} ORDER BY id DESC";
    	$data = $this->Base_model->pagerQuery($logs_sql, $page, 10);
    	$pager = parent::_formatPager(base_url('member/logs/charge'), $data->pager);
    	$this->load->view('member/logs_charge', array('data'=>$data->results, 'pager'=>$pager, 'currMenu'=>'member_logs_charge'));
    }
}