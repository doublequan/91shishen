<?php

require_once dirname(__FILE__).'/base_model.php';

class Employee_model extends Base_model 
{

    public function __construct() {
        parent::__construct();
    }
    
    public function countEmployees($username = '', $company_id = NULL, $dept_id = NULL){
        $sql = "SELECT count(*) FROM {$this->table_name} WHERE is_del=0 ";
        if(!empty($username))
            $sql .= "AND username like '%{$username}%'";
        if(!empty($company_id))
            $sql .= "AND company_id='{$company_id}'";
        if(!empty($dept_id))
            $sql .= "AND dept_id='{$dept_id}'";

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
    public function getEmployee2($employee_id){
    	$sql = "SELECT * FROM {$this->table_name} WHERE id='{$employee_id}'";
    	$q = $this->db->query($sql);
    	return $q ? $q->first_row('array') : false;
    }

    /**
     * 通用获取公司列表
     * @return array
     */
    public function getList2($username = '', $company_id = NULL, $dept_id = NULL, $page=0, $size=10 ){
    	$sql = "SELECT e.*, c.name FROM `{$this->table_name}` AS e LEFT JOIN `companys` AS c ON e.company_id=c.id WHERE 1=1";
    	if(!empty($username))
            $sql .= " AND e.username like '%{$username}%'";
        if(!empty($company_id))
            $sql .= " AND e.company_id='{$company_id}'";
        if(!empty($dept_id))
            $sql .= " AND e.dept_id='{$dept_id}'";

        $sql .= ' ORDER BY e.create_time DESC';

    	if( $page ){
            $start = ($page - 1) * $size;
            $sql .= " LIMIT {$start}, {$size}";
    	} else {
    		$sql .= " LIMIT $size";
    	}
        $q = $this->db->query($sql);
        return $q ? $q->result_array( 'array' ) : array();
    }
    
    /**
     * 获取最后插入的自增ID
     * @return integer
     */
    public function insert_id2(){
    	 return $this->db->insert_id();
    }
}