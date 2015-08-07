<?php
/**
 * 商品评论
 * @author LiuPF<mail@phpha.com>
 * @date 2014-10-3
 */
require_once 'base_model.php';
class Comment_model extends Base_model{
	
	/**
	 * 获取商品评论
	 * @param integer $product_id
	 * @param integer $page
	 * @param integer $limit
	 * @return array
	 */
	public function getComment($product_id = 0, $page = 1, $limit = 5){
		$start = ($page - 1) * $limit;
		$comment = $this->getAll("
			SELECT SQL_CALC_FOUND_ROWS uid,username,content,level,score,create_time
            FROM products_comment
            WHERE status=1 AND product_id = {$product_id} ORDER BY create_time DESC
			LIMIT {$start}, {$limit}
		");
		$data['data'] = $comment;
		$data['total'] = $this->getOne("SELECT FOUND_ROWS()");
		return $data;
    }
}