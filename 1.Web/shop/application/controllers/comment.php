<?php
/**
 * 商品评论
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-28
 */
require_once 'base.php';
class Comment extends Base{
	
    //获取评论
    public function ajax_get(){
    	//仅限Ajax访问
    	$this->input->is_ajax_request() || show_404();
    	//默认状态
    	$err['err_no'] = 1000;
    	$err['err_msg'] = parent::$errorType[1000];
    	//评论信息
    	$product_id = intval($this->input->post('product_id'));
    	$page = max(1, intval($this->input->post('page')));
    	empty($product_id) && exit(json_encode($err));
    	$this->load->model('Comment_model');
    	$data = $this->Comment_model->getComment($product_id, $page, 5);
    	if(empty($data['total'])){
	        $err['err_no'] = 1003;
	        $err['err_msg'] = parent::$errorType[1003];
	    }else{
	    	//格式化处理
	    	$comments = array();
	    	foreach($data['data'] as $v){
	    		$comment = $v;
	    		$comment['header'] = getAvatar($v['uid']);
	    		$comment['time'] = date('Y-m-d H:i', $v['create_time']);
	    		$comments[] = $comment;
	    	}
	    	$err['err_no'] = 0;
	    	$err['err_msg'] = parent::$errorType[0];
	    	$err['results'] = $comments;
	    }
	    exit(json_encode($err));
    }
}
