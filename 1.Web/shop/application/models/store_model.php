<?php
/**
 * 门店信息
 * @author LiuPF<mail@phpha.com>
 * @date 2014-10-15
 */
require_once 'base_model.php';
class Store_model extends Base_model{
	
	/**
	 * 获取站点所有门店
	 * @param integer $site_id
	 * @return array 
	 */
	public function getStore($site_id = 1){
		$sql = "SELECT s.*, a1.name AS prov_name, a2.name AS city_name, a3.name AS district_name
				FROM stores AS s
				LEFT JOIN areas AS a1 ON a1.id = s.prov
				LEFT JOIN areas AS a2 ON a2.id = s.city
				LEFT JOIN areas AS a3 ON a3.id = s.district
				WHERE s.site_id = {$site_id} AND s.is_pickup=1 AND is_del = 0
				ORDER BY s.id ASC";
		$data = $this->getAll($sql);
		return (empty($data) ? array() : $data);
	}
	
	/**
	 * 根据ID获取门店
	 * @param integer $store_id
	 * @return array
	 */
	public function getStoreByID($store_id = 0){
	    $sql = "SELECT s.*, a1.name AS prov_name, a2.name AS city_name, a3.name AS district_name
	    FROM stores AS s
	    LEFT JOIN areas AS a1 ON a1.id = s.prov
	    LEFT JOIN areas AS a2 ON a2.id = s.city
	    LEFT JOIN areas AS a3 ON a3.id = s.district
	    WHERE s.id = {$store_id} AND is_del = 0";
	    $data = $this->getRow($sql);
	    return (empty($data) ? array() : $data);
	}
	
}