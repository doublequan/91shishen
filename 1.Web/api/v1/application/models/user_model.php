<?php

include_once 'base_model.php';

class User_model extends Base_model
{
	public function getSMSCount( $mobile ){
		$ip = getUserIP();
		$t = time()-600;
		$sql = "SELECT COUNT(id) AS num FROM logs_sms WHERE create_ip='".$ip."' OR create_time>=".$t;
		return $this->getOne($sql);
	}
	
	public function getLoginFailCount(){
		$ip = getUserIP();
		$t = time()-600;
		$sql = "SELECT COUNT(id) AS num FROM users_log_login WHERE login_ip='".$ip."' AND login_time>=".$t;
		return $this->getOne($sql);
	}
	
	public function getVIPLoginFailCount(){
		$ip = getUserIP();
		$t = time()-600;
		$sql = "SELECT COUNT(id) AS num FROM vip_users_log_login WHERE login_ip='".$ip."' AND login_time>=".$t;
		return $this->getOne($sql);
	}
	
	public function getAddressCount( $uid ){
		$sql = 'SELECT COUNT(id) AS num FROM users_address WHERE uid='.$uid;
		return $this->getOne($sql);
	}
	
	public function getUserSign( $uid, $day ){
		$sql = "SELECT id FROM users_sign WHERE uid={$uid} AND day='{$day}'";
		return $this->getOne($sql);
	}
	
	public function addUserScore( $id, $score, $reason='' ){
		$sql = 'SELECT * FROM users WHERE id='.$id;
		$user = $this->getRow($sql);
		if( $user ){
			$sql = 'UPDATE users SET score=score+'.$score.' WHERE id='.$id;
			$this->db->query($sql);
			$data = array(
				'uid'			=> $id,
				'change'		=> $score,
				'curr'			=> $user['score']+$score,
				'reason'		=> $reason,
				'create_time'	=> time(),
			);
			$sql = dbInsert('users_log_score', $data);
			$this->db->query($sql);
		}
	}
}
