<?php

class Category_model extends CI_Model 
{
    private $table_name;

    public function __construct() {
        parent::__construct();
        $this->load->database();

        $this->table_name = 'goods_category';
    }
    
    public function getCategoryList($father_id = 0)
    {
        $sql = "SELECT * FROM `{$this->table_name}` WHERE `father_id`={$father_id}";
        $q = $this->db->query($sql);
        return $q ? $q->result_array( 'array' ) : array();
    }

}