<?php
/**
 * 会员信息
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-11
 */
require_once 'base_model.php';
class User_model extends Base_model{
	
	/**
	 * 检查用户名是否存在
	 * @param string $username
	 * @return boolean
	 */
	public function checkUsername($username = ''){
		$uid = $this->getOne("SELECT id FROM users WHERE username = '{$username}'");
		if(empty($uid)){
			return false;
		}
		return true;
	}
	
	/**
	 * 检查手机号是否存在
	 * @param string $mobile
	 * @return boolean
	 */
	public function checkMobile($mobile = ''){
		$uid = $this->getOne("SELECT id FROM users WHERE mobile = '{$mobile}'");
		if(empty($uid)){
			return false;
		}
		return true;
	}
	
	/**
	 * 检查邮箱是否存在
	 * @param string $email
	 * @return boolean
	 */
	public function checkEmail($email = ''){
		$uid = $this->getOne("SELECT id FROM users WHERE email = '{$email}'");
		if(empty($uid)){
			return false;
		}
		return true;
	}
	
	/**
	 * 获取用户ID
	 * @param string $user
	 * @return integer
	 */
	public function getUid($user = ''){
		$uid = $this->getOne("SELECT id FROM users WHERE username = '{$user}' OR mobile = '{$user}' OR email = '{$user}'");
		return intval($uid);
	}
	
	/**
	 * 获取用户名
	 * @param integer $uid
	 * @return string
	 */
	public function getUsername($uid = 0){
		$username = $this->getOne("SELECT username FROM users WHERE id = {$uid}");
		return $username;
	}
	
	/**
	 * 获取账号状态
	 * @param integer $uid
	 * @return integer
	 */
	public function getStatus($uid = 0){
	    $status = $this->getOne("SELECT status FROM users WHERE id = {$uid}");
	    return $status;
	}
	
	/**
	 * 检查用户名密码
	 * @param string $user
	 * @return boolean
	 */
	public function checkPassword($user = '', $password = ''){
		$data = $this->getRow("SELECT pass,salt FROM users WHERE username = '{$user}' OR mobile = '{$user}' OR email = '{$user}'");
		if(empty($data)){
			return false;
		}
		//$password = encryptPass($password, $data['salt']);
		$password = md5($password);
		return $data['pass'] == $password ? true : false;
	}
}