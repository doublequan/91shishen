<?php

class Good_model extends CI_Model 
{
    private $table_name;

    public function __construct() {
        parent::__construct();
        $this->load->database();

        $this->table_name = 'goods';
    }
    
    public function getGood($good_id){
    	$sql = "SELECT * FROM {$this->table_name} WHERE id='{$good_id}'";
    	$q = $this->db->query($sql);
    	return $q ? $q->first_row('array') : false;
    }

    public function getList($site_id = 0, $category_id = 0, $stype='', $search='', $page=0, $size=10 ){
    	$sql = "SELECT g.*, s.name AS site_name, b.name AS brand_name FROM `{$this->table_name}` AS g LEFT JOIN `sites` AS s ON g.site_id=s.id LEFT JOIN `brands` AS b ON g.brand_id=b.id WHERE 1=1 ";
    	if(!empty($site_id))
            $sql .= " AND g.site_id={$site_id}";
        if(!empty($category_id))
            $sql .= " AND g.category_id={$category_id}";
        if(!empty($stype) && !empty($search))
            $sql .= " AND g.{$stype} like '%{$search}%'";

        $sql .= ' ORDER BY g.create_time DESC';

    	if( $page ){
            $start = ($page - 1) * $size;
            $sql .= " LIMIT {$start}, {$size}";
    	} else {
    		$sql .= " LIMIT $size";
    	}
        $q = $this->db->query($sql);
        return $q ? $q->result_array( 'array' ) : array();
    }
    
    public function delete($good_id = 0){
        if(!empty($good_id)){
            $sql = "DELETE FROM `{$this->table_name}` WHERE id={$good_id}";
            $this->db->query($sql);
            return $this->db->affected_rows();
        }
        return 0;
    }
}