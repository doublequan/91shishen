<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class send extends Base 
{
	private $active_top_tag = 'user';
	
	//Common Groups
	private $groups = array();

	public function __construct(){
		parent::__construct();
		$res = $this->mBase->getList('users_group',array('AND'=>array('is_del=0')),'*','id ASC');
		if( $res ){
			foreach ( $res as $row ){
				$this->groups[$row['id']] = $row;
			}
		}
	}

	public function push(){
		$data = array();
		//Common Data
		$data['groups'] = $this->groups;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'send_push';
		$this->_view('common/header', $tags);
		$this->_view('user/send_push', $data);
		$this->_view('common/footer');
	}
	
	public function msg(){
		$data = array();
		//Common Data
		$data['groups'] = $this->groups;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'send_msg';
		$this->_view('common/header', $tags);
		$this->_view('user/send_msg', $data);
		$this->_view('common/footer');
	}
	
	public function email(){
		$data = array();
		//Common Data
		$data['groups'] = $this->groups;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'send_email';
		$this->_view('common/header', $tags);
		$this->_view('user/send_email', $data);
		$this->_view('common/footer');
	}
	
	public function sms(){
		$data = array();
		//Common Data
		$data['templates'] = array(
			1	=> '短信验证码模板',
			2	=> '促销活动模板',
		);
		$data['groups'] = $this->groups;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'send_sms';
		$this->_view('common/header', $tags);
		$this->_view('user/send_sms', $data);
		$this->_view('common/footer');
	}
	
	public function doSendPush() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('content');
			$this->checkParams('post',$must);
			$params = $this->params;
			//check parameters
			$users = $this->getUsers();
			if( !$users ){
				if( intval($this->input->get_post('type')) == 1 ){
					$ret = array('err_no'=>1000,'err_msg'=>'用户组内无用户，请选择其他用户组');
				}
				else{
					$ret = array('err_no'=>1000,'err_msg'=>'请至少选择一个用户');
				}
				break;
			}
			$t = array();
			foreach( $users as $row ){
				$devices = $this->mBase->getList('users_device',array('AND'=>array('uid='.$row['id'])));
				if( $devices ){
					foreach( $devices as $device ){
						$t[] = array(
							'os'			=> $device['os'],
							'deviceid'		=> $device['deviceid'],
							'pushtoken'		=> $device['pushtoken'],
							'content'		=> $params['content'],
							'create_time'	=> time(),
						);
					}
				}
			}
			if( $t ){
				$i = 0;
				$total = count($t);
				$data = array();
				foreach( $t as $row ){
					$data[] = $row;
					$i++;
					if( $i%500==0 || $i==$total ){
						$this->mBase->insertMulti('queue_push',$data);
						$data = array();
					}
				}
			}
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function doSendMsg() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('title','content');
			$this->checkParams('post',$must);
			$params = $this->params;
			//check parameters
			$users = $this->getUsers();
			if( !$users ){
				if( intval($this->input->get_post('type')) == 1 ){
					$ret = array('err_no'=>1000,'err_msg'=>'用户组内无用户，请选择其他用户组');
				}
				else{
					$ret = array('err_no'=>1000,'err_msg'=>'请至少选择一个用户');
				}
				break;
			}
			$t = array();
			foreach( $users as $row ){
				$t[] = array(
					'uid'			=> $row['id'],
					'title'			=> $params['title'],
					'content'		=> $params['content'],
					'create_time'	=> time(),
				);
			}
			if( $t ){
				$i = 0;
				$total = count($t);
				$data = array();
				foreach( $t as $row ){
					$data[] = $row;
					$i++;
					if( $i%500==0 || $i==$total ){
						$this->mBase->insertMulti('users_msg',$data);
						$data = array();
					}
				}
			}
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function doSendEmail() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('subject','content');
			$this->checkParams('post',$must);
			$params = $this->params;
			//check parameters
			$users = $this->getUsers();
			if( !$users ){
				if( intval($this->input->get_post('type')) == 1 ){
					$ret = array('err_no'=>1000,'err_msg'=>'用户组内无用户，请选择其他用户组');
				}
				else{
					$ret = array('err_no'=>1000,'err_msg'=>'请至少选择一个用户');
				}
				break;
			}
			$t = array();
			foreach( $users as $row ){
				$t[] = array(
					'email'			=> $row['email'],
					'subject'		=> $params['subject'],
					'content'		=> addslashes($params['content']),
					'create_time'	=> time(),
				);
			}
			if( $t ){
				$i = 0;
				$total = count($t);
				$data = array();
				foreach( $t as $row ){
					$data[] = $row;
					$i++;
					if( $i%500==0 || $i==$total ){
						$this->mBase->insertMulti('queue_email',$data);
						$data = array();
					}
				}
			}
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function doSendSMS() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('template_id','content');
			$this->checkParams('post',$must);
			$params = $this->params;
			//check parameters
			$users = $this->getUsers();
			if( !$users ){
				if( intval($this->input->get_post('type')) == 1 ){
					$ret = array('err_no'=>1000,'err_msg'=>'用户组内无用户，请选择其他用户组');
				}
				else{
					$ret = array('err_no'=>1000,'err_msg'=>'请至少选择一个用户');
				}
				break;
			}
			$t = array();
			foreach( $users as $row ){
				$t[] = array(
					'mobile'		=> $row['mobile'],
					'template_id'	=> $params['template_id'],
					'content'		=> $params['content'],
					'create_time'	=> time(),
				);
			}
			if( $t ){
				$i = 0;
				$total = count($t);
				$data = array();
				foreach( $t as $row ){
					$data[] = $row;
					$i++;
					if( $i%500==0 || $i==$total ){
						$this->mBase->insertMulti('queue_sms',$data);
						$data = array();
					}
				}
			}
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function doAddCounpon() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('limit','total','amount');
			$fields = array('start','end');
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$limit = floatval($params['limit']);
			$total = floatval($params['total']);
			$amount = intval($params['amount']);
			$start = $params['start'] ? strtotime($params['start']) : 0;
			$end = $params['end'] ? strtotime($params['end']) : 0;
			//check parameters
			$users = $this->getUsers();
			if( !$users ){
				$ret = array('err_no'=>1000,'err_msg'=>'请至少选择一个用户');
				break;
			}
			if( $total<=0 ){
				$ret = array('err_no'=>1000,'err_msg'=>'代金券金额应大于0');
				break;
			}
			if( $amount<1 ){
				$ret = array('err_no'=>1000,'err_msg'=>'每用户份数应至少为1');
				break;
			}
			if( $start && $end && $start>=$end ){
				$ret = array('err_no'=>1000,'err_msg'=>'代金券可用起止日期错误');
				break;
			}
			$t = array();
			foreach( $users as $row ){
				for( $i=0; $i<$amount; $i++ ){
					$t[] = array(
						'uid'			=> $row['id'],
						'coupon_type'	=> 1,
						'coupon_code'	=> strtoupper(getRandStr(5).substr(md5($row['id']), 0, 5)),
						'coupon_limit'	=> $limit,
						'coupon_total'	=> $total,
						'coupon_balance'=> $total,
						'start'			=> $start,
						'end'			=> $end,
						'status'		=> 1,
						'create_eid'	=> self::$user['id'],
						'create_name'	=> self::$user['username'],
						'create_time'	=> time(),
					);
				}
			}
			if( $t ){
				$i = 0;
				$total = count($t);
				$data = array();
				foreach( $t as $row ){
					$data[] = $row;
					$i++;
					if( $i%5000==0 || $i==$total ){
						$this->mBase->insertMulti('users_coupon',$data);
						$data = array();
					}
				}
			}
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function getUsers(){
		$users = array();
		$type = intval($this->input->get_post('type'));
		$group_id = intval($this->input->get_post('group_id'));
		$uids = $this->input->get_post('uids');
		$uids = $uids=='' ? array() : explode(',', $uids);
		$condition = array();
		if( $type==0 ){
			$condition['AND'] = array('status=1');
		} elseif( $type==1 && $group_id ){
			$condition['AND'] = array('status=1','group_id='.$group_id);
		} elseif( $type==2 && $uids ){
			$condition['AND'] = array('status=1','id IN ('.implode(',', $uids).')');
		}
		if( $condition ){
			$users = $this->mBase->getList('users',$condition);
		}
		return $users;
	}
	
	public function searchUsers() {
		$users = array();
		$k = $this->input->get('keyword');
		if( $k ){
			$condition = array(
				'AND' => array('status=1',"username LIKE '%".$k."%'"),
			);
			$res = $this->mBase->getList('users',$condition,'*','id DESC',0,10);
			if( $res ){
				$users['data'] = array();
				foreach( $res as $row ){
					$users['data'][] = array(
						'text'	=> $row['username'],
						'value'	=> $row['id'],
					);
				}
			}
		}
		$this->output($users);
	}
}
