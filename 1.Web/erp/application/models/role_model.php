<?php

class Role_model extends CI_Model 
{
    private $table_name;

    public function __construct() {
        parent::__construct();
        $this->load->database();

        $this->table_name = 'roles';
    }
    
    public function countRoles($company_id){
        $sql = "SELECT count(*) FROM {$this->table_name}";
        if(!empty($company_id))
            $sql .= " WHERE company_id={$company_id}";

        $q = $this->db->query($sql);
        if($q){
            $count = $q->first_row('array');
            return intval($count["count(*)"]);
        }
        return 0;
    }

    public function getRole($role_id){
    	$sql = "SELECT r.*, c.name AS company_name FROM {$this->table_name} AS r LEFT JOIN `companys` AS c ON r.company_id=c.id WHERE r.id='{$role_id}'";
    	$q = $this->db->query($sql);
    	return $q ? $q->first_row('array') : false;
    }

    public function getList($company_id = 0, $page=0, $size=10 ){
    	$sql = "SELECT r.*, c.name AS company_name FROM {$this->table_name} AS r LEFT JOIN `companys` AS c ON r.company_id=c.id";
    	if(!empty($company_id))
            $sql .= " WHERE r.company_id={$company_id}";

    	if( $page ){
            $start = ($page - 1) * $size;
            $sql .= " LIMIT {$start}, {$size}";
    	} else {
    		$sql .= " LIMIT $size";
    	}
        $q = $this->db->query($sql);
        return $q ? $q->result_array( 'array' ) : array();
    }

    public function deleteRole($role_id = 0){
        if(!empty($role_id)){
            $sql = "DELETE FROM `{$this->table_name}` WHERE id={$role_id}";
            $this->db->query($sql);
            return $this->db->affected_rows();
        }
        return 0;
    }

    public function getCompanyRoles(){
        $sql = "SELECT r.*, c.id AS company_id FROM {$this->table_name} AS r LEFT JOIN sites AS s ON r.site_id=s.id LEFT JOIN companys AS c ON s.company_id=c.id";
        $q = $this->db->query($sql);
        return $q ? $q->result_array( 'array' ) : array();
    }
}