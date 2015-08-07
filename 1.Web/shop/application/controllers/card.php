<?php

require_once 'base.php';
class card extends Base{
	
	public function index(){
		$data = array();
		$data['is_home'] = 0;
		$this->load->view('card', $data);
	}
	
	public function add(){
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
    	do{
    		$params = array();
    		$arr = array('username','mobile','gender','address','ids');
    		foreach ( $arr as $v ){
    			$params[$v] = $this->input->post($v);
    		}
    		$details = array();
    		foreach( explode(',', $params['ids']) as $str ){
    			$arr = explode(':', $str);
    			if( isset($arr[0]) && isset($arr[1]) ){
    				$details[$arr[0]] = $arr[1];
    			}
    		}
    		$params['detail'] = json_encode($details);
    		$params['pay_type'] = 1;
    		unset($params['ids']);
    		$params['status'] = 1;
    		$params['create_time'] = time();
    		$this->Base_model->insert('orders_card',$params);
    		$ret = array('err_no'=>0,'err_msg'=>'操作成功');
    	} while(0);
    	echo json_encode($ret);
	}
}