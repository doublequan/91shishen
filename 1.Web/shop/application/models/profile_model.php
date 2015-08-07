<?php
/**
 * 个人资料
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-12
 */
require_once 'base_model.php';
class Profile_model extends Base_model{
	
	/**
	 * 获取个人资料
	 * @param integer $user_id
	 * @param boolean $vip
	 * @return array
	 */
	public function getProfile($user_id = 0, $vip = false){
		$table = $vip ? 'vip_users' : 'users';
		$data = $this->getRow("SELECT * FROM {$table} WHERE id = {$user_id}");
		return $data;
	}
}