<?php

class Area_model extends CI_Model 
{
    private $table_name;
    private $province_deep;
    private $city_deep;
    private $area_deep;

    public function __construct() {
        parent::__construct();
        $this->load->database();

        $this->table_name = 'areas';
        $this->province_deep = 1;
        $this->city_deep = 2;
        $this->area_deep = 3;
    }
    
    /**
     * 获取省区列表
     */
    public function getProvinceList(){
    	$sql = "SELECT * FROM {$this->table_name} WHERE `deep`={$this->province_deep};";
    	$q = $this->db->query($sql);
    	return $q->result_array();
    }

    public function getCityList(){
        $sql = "SELECT * FROM {$this->table_name} WHERE `deep`={$this->city_deep};";
        $q = $this->db->query($sql);
        return $q->result_array();
    }

    public function getAreaList(){
        $sql = "SELECT * FROM {$this->table_name} WHERE `deep`={$this->area_deep};";
        $q = $this->db->query($sql);
        return $q->result_array();
    }

    /**
     * 根据省区获取城市列表
     */
    public function getCityListByProvince($province_id = 0){
        $sql = "SELECT * FROM {$this->table_name} WHERE `deep`={$this->city_deep} AND `father_id`={$province_id};";
        $q = $this->db->query($sql);
        return $q->result_array();
    }

    /**
     * 根据城市获取城市辖区列表
     */
    public function getAreaListByCity($city_id = 0){
        $sql = "SELECT * FROM {$this->table_name} WHERE `deep`={$this->area_deep} AND `father_id`={$city_id};";
        $q = $this->db->query($sql);
        return $q->result_array();
    }

    /**
     * 根据省区id获取省区信息
     */
    public function getProvince($province_id = 0){
        $sql = "SELECT * FROM {$this->table_name} WHERE `deep`={$this->province_deep} AND `id`={$province_id}";
        $q = $this->db->query($sql);
        return $q->row_array();
    }

    /**
     * 根据市id获取市信息
     */
    public function getCity($city_id = 0){
        $sql = "SELECT * FROM {$this->table_name} WHERE `deep`={$this->city_deep} AND `id`={$city_id}";
        $q = $this->db->query($sql);
        return $q->row_array();
    }

    /**
     * 根据区域id获取区域信息
     */
    public function getArea($area_id = 0){
        $sql = "SELECT * FROM {$this->table_name} WHERE `deep`={$this->area_deep} AND `id`={$area_id}";
        $q = $this->db->query($sql);
        return $q->row_array();
    }


}