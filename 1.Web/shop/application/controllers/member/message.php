<?php
/**
 * 站内消息
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-29
 */
require_once 'common.php';
class Message extends Common{
	
	//消息列表
	public function index(){
		$page = max(1, intval($this->input->get('page')));
		$userInfo = $this->session->userdata('userinfo');
		//收藏
		$message_sql = "SELECT * FROM users_msg WHERE uid = {$userInfo['uid']} ORDER BY id DESC";
		$data = $this->Base_model->pagerQuery($message_sql, $page, 10);
		$pager = parent::_formatPager(base_url('member/message/index'), $data->pager);
		$this->load->view('member/message_index', array('data'=>$data->results, 'pager'=>$pager, 'currMenu'=>'member_message'));
	}
	
	//读取消息
	public function ajax_get(){
		//仅限Ajax访问
		$this->input->is_ajax_request() || show_404();
		//会员信息
		$userInfo = $this->session->userdata('userinfo');
		//消息信息
		$message_id = intval($this->input->post('message_id'));
		$data = $this->Base_model->getRow("SELECT title,content,is_read FROM users_msg WHERE id = {$message_id} AND uid = {$userInfo['uid']}");
		if(empty($data)){
			$err['err_no'] = 1003;
			$err['err_msg'] = parent::$errorType[1003];
		}else{
			$err['err_no'] = 0;
			$err['err_msg'] = parent::$errorType[0];
			$err['results'] = $data;
			//更新状态
			if($data['is_read'] == 0){
				$this->Base_model->update('users_msg', array('is_read'=>1), array('id'=>$message_id, 'uid'=>$userInfo['uid']));
			}
		}
		exit(json_encode($err));
	}
	
	//删除消息
	public function ajax_del(){
	    //仅限Ajax访问
	    $this->input->is_ajax_request() || show_404();
	    //会员信息
	    $userInfo = $this->session->userdata('userinfo');
	    //消息信息
	    $message_id = intval($this->input->post('message_id'));
	    $result = $this->Base_model->delete('users_msg', array('id'=>$message_id,'uid'=>$userInfo['uid']), true);
	    if($result > 0){
			$err['err_no'] = 0;
			$err['err_msg'] = parent::$errorType[0];
		}else{
			$err['err_no'] = 1000;
			$err['err_msg'] = parent::$errorType[1000];
		}
		exit(json_encode($err));
	}
}