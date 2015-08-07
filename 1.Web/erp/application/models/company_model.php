<?php

require_once dirname(__FILE__).'/base_model.php';

class Company_model extends Base_model 
{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function getAllCompanyNames(){
        $sql = "SELECT * FROM companys WHERE is_del=0";
        $q = $this->db->query($sql);
        return $q ? $q->result_array( 'array' ) : false;
    }
    
}