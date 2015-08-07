<?php
/**
 * 浏览记录
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-29
 */
require_once 'base_model.php';
class View_model extends Base_model{
	
	/**
	 * 获取浏览记录
	 * @param integer $user_id
	 * @param integer $number
	 * @return array
	 */
	public function getViewLog($user_id = 0, $number = 5){
		$sql = "SELECT v.product_id,p.title,p.seo_title,p.thumb,p.price,p.unit,p.spec
					FROM users_log_view AS v
					LEFT JOIN products AS p ON p.id = v.product_id
					WHERE v.uid = {$user_id}
					GROUP BY v.product_id
					ORDER BY MAX(v.id) DESC LIMIT {$number}";
		$data = $this->getAll($sql);
		return $data;
	}
}