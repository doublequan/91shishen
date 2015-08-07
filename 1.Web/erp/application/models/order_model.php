<?php

require_once dirname(__FILE__).'/base_model.php';

class Order_model extends Base_model 
{
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * 通用获取列表（多表联合查询）
     * @param string $condition
     * @param string $queryString
     * @param string $orderby
     * @param integer $page
     * @param integer $size
     * @retur   
     */ 
    public function getMoreList($condition,$page=0,$size=0){
        $sql='select distinct o.id,o.site_id,o.store_id,o.price,o.uid,u.username,o.date_day,o.date_noon,receiver,o.order_status,o.create_time,od.product_name,o.address,s.name as send_name
              from orders o 
              left join orders_detail od on o.id=od.order_id 
              left join users u on u.id=o.uid 
              left join stores s on o.store_id=s.id
              WHERE 1';
        if( isset($condition['AND']) && !empty($condition['AND']) ){
            foreach( $condition['AND'] as $v ){
                if(strpos($v,'store_id')!==false || 
                   strpos($v,'id')==0 || 
                   strpos($v,'site_id')!==false || 
                   strpos($v,'create_time')!==false || 
                   strpos($v,'price')!==false || 
                   strpos($v,'uid')!==false){
                    $v='o.'.$v;
                }
                if(strpos($v,'o.product_name')!==false){
                  $str_tmp=preg_replace("/o.product_name/","od.product_name",$v,1);
                  $v=$str_tmp;
                }                    
                $sql .= ' AND '.$v;
            }
        }
        if( isset($condition['OR']) && !empty($condition['OR']) ){
            foreach( $condition['OR'] as $arr ){
                if(strpos($attr,'o.product_name')!==false){
                  $str_tmp=preg_replace("/o.product_name/","od.product_name",$v,1);
                  $attr=$str_tmp;
                }                   
                if(strpos($attr,'store_id')!==false || 
                   strpos($arr,'id')==0 || 
                   strpos($arr,'site_id')!==false || 
                   strpos($arr,'create_time')!==false || 
                   strpos($arr,'price')!==false || 
                   strpos($arr,'uid')!==false){
                    $arr='o.'.$arr;
                }                                      
                $sql .= ' AND ('.implode(' OR ', $arr).')';
            }
        }
        $sql.=' group by o.id order by o.create_time DESC';
        if( $page ){
            return $this->pagerQuery($sql,$page,$size);
        } else {
            if( $size ){
                $sql .= ' LIMIT '.$size;
            }
            return $this->getAll($sql);
        }        
    }
}