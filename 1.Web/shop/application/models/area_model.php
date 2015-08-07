<?php
/**
 * 地区信息
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-7
 */
require_once 'base_model.php';
class Area_model extends Base_model{
	
	/**
	 * 获取省信息
	 * @return array
	 */
	public function getProvince(){
		$sql = "SELECT * FROM areas WHERE father_id = 0 AND disable = 0 ORDER BY id ASC";
		$data = $this->getAll($sql);
		return (empty($data) ? array() : $data);
	}
	
	/**
	 * 获取市信息
	 * @param integer $province_id
	 * @return array
	 */
	public function getCity($province_id = 0){
		$sql = "SELECT * FROM areas WHERE father_id = {$province_id} AND disable = 0 ORDER BY id ASC";
		$data = $this->getAll($sql);
		return (empty($data) ? array() : $data);
	}
	
	/**
	 * 获取区信息
	 * @param integer $city_id
	 * @return array
	 */
	public function getArea($city_id = 0){
		$sql = "SELECT * FROM areas WHERE father_id = {$city_id} AND disable = 0 ORDER BY id ASC";
		$data = $this->getAll($sql);
		return (empty($data) ? array() : $data);
	}
}