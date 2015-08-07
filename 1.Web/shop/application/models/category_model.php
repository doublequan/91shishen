<?php
/**
 * 商品/文章分类
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-17
 */
require_once 'base_model.php';
class Category_model extends Base_model{
    
    /**
     * 获取文章分类
     * @param integer $pid 父类ID
     * @return array 分类数组
     */
    public function getArticleCategory($pid = 0){
        $categories = $this->getAll("SELECT id,name FROM archives_category WHERE is_del = 0 ORDER BY sort ASC, id ASC");
        return $categories;
    }
    
    /**
     * 获取商品分类
     * @param integer $pid 父类ID
     * @return array 分类数组
     */
    public function getGoodsCategory($pid = 0){
        $categories = $this->getAll("SELECT id,father_id,name FROM goods_category WHERE father_id = {$pid} AND is_del = 0 AND type = 1 ORDER BY sort ASC, id ASC");
       if( ! empty($categories)){
            //二级分类
            foreach($categories as &$v){
                $cate = $this->getAll("SELECT id,father_id,name FROM goods_category WHERE father_id = {$v['id']} AND is_del = 0 ORDER BY sort ASC, id ASC");
                if( ! empty($cate)){
                    $v['children'] = $cate;
                    //三级分类
                    foreach($v['children'] as &$v2){
                        $cate2 = $this->getAll("SELECT id,father_id,name FROM goods_category WHERE father_id = {$v2['id']} AND is_del = 0 ORDER BY sort ASC, id ASC");
                        if( ! empty($cate2)){
                            $v2['children'] = $cate2;
                        }
                    }
                }
            }
        }
        return $categories;
    }
    
    /**
     * 获取当前分类所有子类
     * @param number $pid
     */
    public function getChildCateID( $site_id, $category_id ){
    	$data = array();
		$res = $this->getAll("SELECT id FROM goods_category WHERE father_id = {$category_id} AND is_del = 0 ORDER BY id ASC");
    	if( $res ){
    		foreach( $res as $row ){
    			$data[$row['id']] = $row['id'];
    			$data += $this->getChildCateID($site_id, $row['id']);
    		}
    	} else {
    		$data[$category_id] = $category_id;
    	}
    	return $data;
    }
}