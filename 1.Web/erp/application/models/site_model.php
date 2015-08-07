<?php

require_once dirname(__FILE__).'/base_model.php';

class Site_model extends Base_model 
{
    private $table_name;

    public function __construct() {
        parent::__construct();

        $this->table_name = 'sites';
    }

    public function getAllSiteNames(){
        $sql = "SELECT * FROM sites";
        $q = $this->db->query($sql);
        return $q ? $q->result_array( 'array' ) : false;
    }
}