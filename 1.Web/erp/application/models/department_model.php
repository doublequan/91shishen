<?php

class Department_model extends CI_Model 
{
    private $table_name;

    public function __construct() {
        parent::__construct();
        $this->load->database();

        $this->table_name = 'departments';
    }
    
    public function countSuppliers($sup_name = ''){
        $sql = "SELECT count(*) FROM {$this->table_name} WHERE 1=1";
        if(!empty($username))
            $sql .= "AND `sup_name` like '%{$sup_name}%'";

        $q = $this->db->query($sql);
        if($q){
            $count = $q->first_row('array');
            return intval($count["count(*)"]);
        }
        return 0;
    }

    /**
     * 根据公司id获取公司信息
     */
    public function getSupplier($department_id){
    	$sql = "SELECT * FROM {$this->table_name} WHERE id='{$department_id}'";
    	$q = $this->db->query($sql);
    	return $q ? $q->first_row('array') : false;
    }

    /**
     * 通用获取公司列表
     * @return array
     */
    public function getList($sup_name = '', $page=0, $size=10 ){
    	$sql = "SELECT * FROM `{$this->table_name}` WHERE 1=1";
    	if(!empty($sup_name))
            $sql .= " AND e.sup_name like '%{$sup_name}%'";

        $sql .= ' ORDER BY create_time DESC';

    	if( $page ){
            $start = ($page - 1) * $size;
            $sql .= " LIMIT {$start}, {$size}";
    	} else {
    		$sql .= " LIMIT $size";
    	}
        $q = $this->db->query($sql);
        return $q ? $q->result_array( 'array' ) : array();
    }

}