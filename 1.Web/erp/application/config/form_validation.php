<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array(
    'brand' => array(
        array(
            'field' => 'brand_id',
            'label' => '品牌ID',
            'rules' => 'min_length[1]|max_length[11]|numeric'
        ),
        array(
            'field' => 'name',
            'label' => '品牌名称',
            'rules' => 'required|min_length[1]|max_length[255]'
        ),
        array(
            'field' => 'url',
            'label' => '品牌网址',
            'rules' => 'max_length[255]'
        ),
        array(
            'field' => 'logo',
            'label' => '品牌Logo',
            'rules' => 'max_length[255]'
        ),
        array(
            'field' => 'sort',
            'label' => '排序',
            'rules' => 'max_length[5]'
        ),
        array(
            'field' => 'description',
            'label' => '品牌描述',
            'rules' => 'max_length[10000]'
        ),
    ),
    'company' => array(
        array(
            'field' => 'company_id',
            'label' => '公司ID',
            'rules' => 'min_length[1]|max_length[11]'
        ),
        array(
            'field' => 'name',
            'label' => '公司名称',
            'rules' => 'required|min_length[3]|max_length[30]'
        ),
        array(
            'field' => 'province_id',
            'label' => '所属省区',
            'rules' => 'required|min_length[5]|max_length[7]'
        ),
        array(
            'field' => 'city_id',
            'label' => '所属市',
            'rules' => 'min_length[5]|max_length[7]'
        ),
        array(
            'field' => 'address',
            'label' => '公司地址',
            'rules' => 'required|min_length[3]|max_length[100]'
        ),
        array(
            'field' => 'phone',
            'label' => '公司电话',
            'rules' => 'min_length[6]|max_length[30]'
        ),
        array(
            'field' => 'manager',
            'label' => '公司经理',
            'rules' => 'min_length[1]|max_length[11]'
        ),
        array(
            'field' => 'father_id',
            'label' => '上级公司',
            'rules' => 'min_length[1]|max_length[10]'
        ),
        array(
            'field' => 'comment',
            'label' => '备注',
            'rules' => 'min_length[0]|max_length[255]'
        ),
    ),
    'supplier' => array(
        array(
            'field' => 'supplier_id',
            'label' => '供应商ID',
            'rules' => 'min_length[1]|max_length[11]'
        ),
        array(
            'field' => 'sup_name',
            'label' => '供应商名称',
            'rules' => 'required|min_length[1]|max_length[30]'
        ),
        array(
            'field' => 'sup_phone',
            'label' => '供应商电话',
            'rules' => 'required'
        ),
        array(
            'field' => 'contact_name',
            'label' => '供应商联系人',
            'rules' => 'required|min_length[1]|max_length[20]'
        ),
        array(
            'field' => 'contact_email',
            'label' => '供应商联系人邮箱',
            'rules' => 'required|min_length[5]|max_length[100]'
        ),
        array(
            'field' => 'contact_mobile',
            'label' => '供应商联系人手机',
            'rules' => 'required|min_length[6]|max_length[20]'
        ),
    ),
    'site' => array(
        array(
            'field' => 'site_id',
            'label' => '供应商ID',
            'rules' => 'min_length[1]|max_length[11]'
        ),
        array(
            'field' => 'name',
            'label' => '网站名称',
            'rules' => 'required|min_length[1]|max_length[250]'
        ),
        array(
            'field' => 'domain',
            'label' => '网站域名',
            'rules' => 'required|min_length[1]|max_length[250]'
        ),
        array(
            'field' => 'company_id',
            'label' => '所属公司',
            'rules' => 'required|numeric'
        ),
    ),
);