<?php
/**
 * 广告碎片
 * @author LiuPF<mail@phpha.com>
 * @date 2014-10-4
 */
require_once 'base_model.php';
class Fragment_model extends Base_model{
	
	/**
	 * 获取广告列表
	 * @param integer $place_id
	 * @param integer $limit
	 * @return array
	 */
	public function getData($place_id, $limit = 5){
		$sql = "SELECT title,img,url,des FROM fragments WHERE place_id = {$place_id} ORDER BY sort ASC LIMIT {$limit}";
		$data = $this->getAll($sql);
		return $data;
	}
}