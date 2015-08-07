<?php
/**
 * 收货地址
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-6
 */
require_once 'base_model.php';
class Address_model extends Base_model{
	
	/**
	 * 获取所有地址
	 * @param integer $user_id
	 * @param boolean $vip
	 * @return array 
	 */
	public function getAddress($user_id = 0, $vip = false){
		$table = $vip ? 'vip_users_address' : 'users_address';
		$sql = "SELECT a.*, a1.name AS prov_name, a2.name AS city_name, a3.name AS district_name";
		if($vip == false){
		    $sql .= ", a4.name AS street_name";
		}
		$sql .= " FROM ".$table." AS a
				LEFT JOIN areas AS a1 ON a1.id = a.prov
				LEFT JOIN areas AS a2 ON a2.id = a.city
				LEFT JOIN areas AS a3 ON a3.id = a.district";
		if($vip == false){
		    $sql .= " LEFT JOIN areas AS a4 ON a4.id = a.street";
		}
		$sql .= " WHERE a.uid = {$user_id} ORDER BY a.is_default DESC, a.id DESC";
		$data = $this->getAll($sql);
		return (empty($data) ? array() : $data);
	}
	
	public function getUserAddress($user_id=0, $city_id=0){
		$sql = 'SELECT a.*,a1.name AS prov_name,a2.name AS city_name,a3.name AS district_name,a4.name AS street_name
				FROM users_address AS a
				LEFT JOIN areas AS a1 ON a1.id = a.prov
				LEFT JOIN areas AS a2 ON a2.id = a.city
				LEFT JOIN areas AS a3 ON a3.id = a.district
				LEFT JOIN areas AS a4 ON a4.id = a.street
				WHERE a.uid='.$user_id;
		if( $city_id ){
			$sql .= ' AND a.city='.$city_id;
		}
		$sql .= ' ORDER BY a.is_default DESC, a.id DESC';
		return $this->getAll($sql);
	}
	
	/**
	 * 获取默认地址
	 * @param integer $user_id
	 * @param boolean $vip
	 * @return array 
	 */
	public function getDefaultAddress($user_id = 0, $vip = false){
		$table = $vip ? 'vip_users_address' : 'users_address';
		$sql = "SELECT a.*, a1.name AS prov_name, a2.name AS city_name, a3.name AS district_name";
		if($vip == false){
		    $sql .= ", a4.name AS street_name";
		}
		$sql .= " FROM ".$table." AS a
				LEFT JOIN areas AS a1 ON a1.id = a.prov
				LEFT JOIN areas AS a2 ON a2.id = a.city
				LEFT JOIN areas AS a3 ON a3.id = a.district";
	   if($vip == false){
	       $sql .= " LEFT JOIN areas AS a4 ON a4.id = a.street";
	   }
	   $sql .= " WHERE a.uid = {$user_id} AND a.is_default = 1";
		$data = $this->getRow($sql);
		return (empty($data) ? array() : $data);
	}
	
	/**
	 * 根据ID获取地址
	 * @param integer $id
	 * @param boolean $vip
	 * @return array
	 */
	public function getAddressById($id = 0, $user_id = 0, $vip = false){
		$table = $vip ? 'vip_users_address' : 'users_address';
		$sql = "SELECT a.*, a1.name AS prov_name, a2.name AS city_name, a3.name AS district_name";
		if($vip == false){
		    $sql .= ", a4.name AS street_name";
		}
		$sql .= " FROM ".$table." AS a
				LEFT JOIN areas AS a1 ON a1.id = a.prov
				LEFT JOIN areas AS a2 ON a2.id = a.city
				LEFT JOIN areas AS a3 ON a3.id = a.district";
		if($vip == false){
		    $sql .= " LEFT JOIN areas AS a4 ON a4.id = a.street";
		}
		$sql .= " WHERE a.uid = {$user_id} AND a.id = {$id}";
		$data = $this->getRow($sql);
		return (empty($data) ? array() : $data);
	}
}