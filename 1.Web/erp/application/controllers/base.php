<?php

class Base extends CI_Controller {
	
	protected static $user;
	
	public $params = array();
	
	public function __construct() {
		parent::__construct();
		set_time_limit(0);
		$this->load->model('Base_model', 'mBase');
        isset($_SESSION) || session_start();
		$this->load->helper('url');
		self::checkUserLogin();
		$this->requireUserLogin();
	}
	
	private function checkUserPermission(){
		$uri = $this->uri->uri_string();
	}
	
	protected function setUserCookie( $account, $pass ){
		return setcookie ( USER_COOKIE_NAME, authcode("account=$account&pass=$pass",'ENCODE'),time()+USER_COOKIE_TIME, "/" );
	}
	
	public function destroyUser(){
		self::$user = false;
	}
	
	private function checkUserLogin() {
		if( self::$user ){
			return true;
		} else {
			if( isset($_SESSION[USER_SESSION_NAME]['id']) ){
				self::$user = $_SESSION[USER_SESSION_NAME];
				return true;
			} elseif( isset( $_COOKIE[USER_COOKIE_NAME] ) ){
				$userInfo = authcode($_COOKIE[USER_COOKIE_NAME]);
				if ($userInfo) {
					parse_str( $userInfo );
					if ( isset( $account, $pass ) ) {
						$employee = $this->mBase->getSingle('employees','account',$account);
						if ( $employee && $employee['pass']==$pass ) {
							//get user's permissions
							$employee['permissions'] = $this->getUserPermissions();
							//return userinfo
							self::$user = $employee;
							$_SESSION[USER_SESSION_NAME] = $employee;
							return true;
						}
					}
				}
			}
		}
		return false;
	}
	
	protected function getUserPermissions() {
		$p = array();
		if( self::$user ){
			$roles = explode(',',trim(self::$user['roles']));
			if( $roles ){
				foreach( $roles as $role_id ){
					$role = $this->mBase->getSingle('roles','id',$role_id);
					if( isset($role['permissions']) && !empty($role['permissions']) ){
						$permissions = json_decode($role['permissions'],true);
						if( $permissions ){
							foreach ( $permissions as $k=>$v ){
								$p[$k] = $v;
							}
						}
					}
				}
			}
		}
		$p = array_unique($p);
		return $p;
	}
	
	protected function requireUserLogin( $backurl='' ) {
		if( self::$user && self::$user['id']>0) {
			return true;
		} else {
			header('Location:'.site_url().'/auth/login?backurl='.current_url());
		}
	}
	
	protected function checkParams( $method, $must=array(), $fields=array() ) {
		$params = array();
		if( $must ){
			foreach ( $must as $k ){
				$v = $this->input->$method($k,true);
				$v = is_array($v) ? $v : trim($v);
				if( $v=='' ){
					$this->output(array('err_no'=>1000,'err_msg'=>'参数错误，缺少必传的参数：'.$k));
				} else {
					$params[$k] = $v;
				}
			}
		}
		if( $fields ){
			foreach ( $fields as $k ){
				 $v = $this->input->$method($k,true);
				 $params[$k] = is_array($v) ? $v : trim($v);
			}
		}
		$this->params = $params;
		return;
	}
	
	public function output( $ret ){
		echo json_encode($ret);
		exit;
	}
	
	protected function _redirect($uri) {
		redirect ( $uri );
	}

	public function _view( $template, $data=null ) {
		$data['base_url'] = base_url();
		$data['static_url'] = base_url().'static/';
		$data['current_url'] = current_url();
        $data['user'] = self::$user;
        $data['taskCount'] = $this->getTaskCount(1);
		$this->load->view($template, $data);
	}
	
	public function showMsg( $err_no=0, $err_msg, $top_tag='error', $menu_tag='' ) {
		$tag = array();
		$tag['active_top_tag'] = $top_tag;
		$tag['active_menu_tag'] = $menu_tag;
		$tag['user'] = self::$user;
		$tag['taskCount'] = $this->getTaskCount(1);
		$this->load->view('common/header', $tag);
		$data['err_no'] = $err_no;
		$data['err_msg'] = $err_msg;
		$this->load->view('common/msg', $data);
		$this->load->view('common/footer');
	}
	
	public function showMsgDialog( $err_no=0, $err_msg ) {
		$data = array();
		$data['err_no'] = $err_no;
		$data['err_msg'] = $err_msg;
		$this->load->view('common/msg_dialog', $data);
	}
	
	private function getTaskCount( $status, $type=0 ){
		if( self::$user ){
			$cache_key = 'TASK_COUNT_'.self::$user['id'];
			$num = Common_Cache::get($cache_key);
			if( !$num ){
				$condition = array(
					'AND' => array('eid='.self::$user['id'],'status=1'),
				);
				$num = $this->mBase->getCount('tasks',$condition);
				Common_Cache::save($cache_key, $num, 60);
			}
			return intval($num);
		}
		return 0;
	}
	
	protected function getCache( $table, $id, $expired=3600 ){
		$cache_key = $table.'_id_'.$id;
		$res = Common_Cache::get($cache_key);
		if( !$res ){
			$res = $this->mBase->getSingle($table,'id',$id);
			Common_Cache::save($cache_key, $res, $expired);
		}
		return $res;
	}
	
	protected function getCacheList( $table, $condition=array(), $cache_key='list', $expired=3600 ){
		$res = Common_Cache::get($cache_key);
		if( !$res ){
			$res = array();
			$t = $this->mBase->getList($table,$condition);
			if( $t ){
				foreach ( $t as $row ){
					$res[$row['id']] = $row;
				}
			}
			Common_Cache::save($cache_key, $res, $expired);
		}
		return $res;
	}
	
	public function exportExcel( $header, $data ){
		ini_set('memory_limit','256M');
		header("content-Type:text/html; charset=gbk");
		header("Content-Type:application/vnd.ms-excel");
		header("Content-disposition: attachment;filename=".date("Y-m-d-H-i-s", time()).".csv");
		$tStr = "";
		if( $header && $data ){
			#数组长度
			$cols = count($header);
			#写入缓存
			$outstream = fopen("php://temp", 'w');
			#写入头部
			foreach( $header as $k=>$v ){
				$header[$k] = iconv("UTF-8", "GBK", $v);
			}
			fputcsv($outstream, $header);
			//逐行写入数据
			foreach( $data as $row ){
				foreach ($row as $k=>$v){
					$row[$k] = @iconv("UTF-8", "GBK", $v);
				}
				fputcsv($outstream, $row);
			}
			//缓存导入CSV文件
			rewind($outstream);
			$tStr = stream_get_contents($outstream,-1);
			fclose($outstream);
		}
		exit($tStr);
	}
}
