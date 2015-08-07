<?php
/**
 * VIP用户
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-25
 */
require_once 'base_model.php';
class Vip_model extends Base_model{

	/**
	 * 获取用户ID
	 * @param string $user
	 * @return integer
	 */
	public function getUid($username = ''){
		$uid = $this->getOne("SELECT id FROM vip_users WHERE username = '{$username}'");
		return intval($uid);
	}

	/**
	 * 检查用户名密码
	 * @param string $user
	 * @return boolean
	 */
	public function checkPassword($username = '', $password = ''){
		$data = $this->getRow("SELECT pass,salt FROM vip_users WHERE username = '{$username}'");
		if(empty($data)){
			return false;
		}
		//$password = encryptPass($password, $data['salt']);
		$password = md5($password);
		return $data['pass'] == $password ? true : false;
	}
}