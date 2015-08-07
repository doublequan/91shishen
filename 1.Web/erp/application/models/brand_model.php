<?php

class Brand_model extends CI_Model 
{
    private $table_name;

    public function __construct() {
        parent::__construct();
        $this->load->database();

        $this->table_name = 'brands';
    }
    
    public function countBrands($sup_name = ''){
        $sql = "SELECT count(*) FROM {$this->table_name} WHERE 1=1";
        if(!empty($username))
            $sql .= " AND `sup_name` like '%{$sup_name}%'";

        $q = $this->db->query($sql);
        if($q){
            $count = $q->first_row('array');
            return intval($count["count(*)"]);
        }
        return 0;
    }

    public function getBrand($brand_id){
    	$sql = "SELECT * FROM {$this->table_name} WHERE id='{$brand_id}'";
    	$q = $this->db->query($sql);
    	return $q ? $q->first_row('array') : false;
    }

    public function getList($brand_name = '', $page=0, $size=10 ){
    	$sql = "SELECT * FROM `{$this->table_name}` WHERE 1=1";
    	if(!empty($brand_name))
            $sql .= " AND brand_name like '%{$brand_name}%'";

    	if( $page ){
            $start = ($page - 1) * $size;
            $sql .= " LIMIT {$start}, {$size}";
    	} else {
    		$sql .= " LIMIT $size";
    	}
        $q = $this->db->query($sql);
        return $q ? $q->result_array( 'array' ) : array();
    }

    public function getAll(){
        $sql = "SELECT * FROM `{$this->table_name}`";
        $q = $this->db->query($sql);
        return $q ? $q->result_array( 'array' ) : false;
    }

    public function delete($brand_id = 0){
        if(!empty($brand_id)){
            $sql = "DELETE FROM `{$this->table_name}` WHERE id={$brand_id}";
            $this->db->query($sql);
            return $this->db->affected_rows();
        }
        return 0;
    }

}