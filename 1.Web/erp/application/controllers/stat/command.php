<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class command extends CI_Controller 
{
	public function __construct(){
		parent::__construct();
        $this->load->model('Base_model', 'mBase');
	}

	public function user_count(){
        //用户总量
        $this->user_total();

        //新增用户
        $this->user_new();

        //活跃用户
        $this->user_active();

        //用户设备
        $this->user_mobile();

        //用户搜索关键词
        $this->user_search();
	}

    public function order_count(){
        //订单统计
        $this->order_amount();

        //销售额统计
        $this->sales_volume();
    }

    private function user_total(){
        $total_count = $this->mBase->countList('users', array('AND' => array('status!=2')));
        if(is_array($total_count) && count($total_count) > 0){
            $total_count = $total_count[0]['count(*)'];
        }
        else{
            $total_count = 0;
        }
        $data = array(
            'site_id' => 0,
            'count' => $total_count,
            'create_time' => time(),
        );
        $this->mBase->insert('stat_users_total', $data);
    }

    private function user_new(){
        $now_hour = strtotime(date('Y-m-d H').':00:00');
        $last_hour = $now_hour - 3600;

        $condition = array(
            'AND'=>array(
                'status!=2',
                "create_time between {$last_hour} and {$now_hour}",
            )
        );

        $new_count = $this->mBase->countList('users', $condition);
        if(is_array($new_count) && count($new_count) > 0){
            $new_count = $new_count[0]['count(*)'];
        }
        else{
            $new_count = 0;
        }
        $data = array(
            'site_id' => 0,
            'count' => $new_count,
            'create_time' => time(),
        );
        $this->mBase->insert('stat_users_new', $data);
    }

    private function user_active(){
        $now_hour = strtotime(date('Y-m-d H').':00:00');
        $last_hour = $now_hour - 3600;

        $condition = array(
            'AND'=>array(
                'status!=2',
                "login_time between {$last_hour} and {$now_hour}",
            )
        );

        $active_count = $this->mBase->countList('users', $condition);
        if(is_array($active_count) && count($active_count) > 0){
            $active_count = $active_count[0]['count(*)'];
        }
        else{
            $active_count = 0;
        }
        $data = array(
            'site_id' => 0,
            'count' => $active_count,
            'create_time' => time(),
        );
        $this->mBase->insert('stat_users_active', $data);
    }

    private function user_mobile(){
        $sql = "SELECT brand, count(*) FROM `users_device` WHERE uid>0 GROUP BY brand";
        $query_rst = $this->db->query($sql);
        
        $results = array();
        if($query_rst){
            $query_rst = $query_rst->result_array('array');

            $m_total = 0;
            foreach ($query_rst as $key => $value) {
                $m_total += intval($value['count(*)']);
            }
            
            foreach ($query_rst as $key => $value) {
                $results[$value['brand']] = array(
                    'number' => intval($value['count(*)']),
                    'percent' => round(intval($value['count(*)'])/$m_total, 4),
                );
            }
        }

        $data = array(
            'site_id' => 0,
            'info' => json_encode($results),
            'create_time' => time(),
        );

        $this->mBase->insert('stat_users_mobile', $data);
    }

    private function user_search(){
        $sql = "SELECT keyword,count(id) AS count FROM `logs_search` WHERE 1 GROUP BY keyword ORDER BY count DESC";
        $query_rst = $this->db->query($sql);

        $results = array();
        if($query_rst){
            $query_rst = $query_rst->result_array('array');

            if(count($query_rst) > 30){
                $query_rst = array_slice($query_rst, 0, 20);
            }

            foreach ($query_rst as $key => $value) {
                $results[] = array(
                    'name' => urlencode($value['keyword']),
                    'number' => intval($value['count']),
                );
            }

            $data = array(
                'day' => date('Y-m-d', strtotime('-1 day')),
                'info' => json_encode($results),
                'create_time' => time(),
            );

            $this->mBase->insert('stat_users_search', $data);
        }
    }

    public function product_fav(){
        $sql = "SELECT f.product_id,p.sku,p.site_id,p.title,f.count FROM products AS p 
            INNER JOIN 
            (SELECT product_id, count(*) AS count FROM `users_fav` WHERE is_del=0 GROUP BY product_id) AS f 
            ON p.id=f.product_id ORDER BY f.count DESC";
        $query_rst = $this->db->query($sql);

        $results = array();
        if($query_rst){
            $query_rst = $query_rst->result_array('array');

            if(count($query_rst) > 20){
                $query_rst = array_slice($query_rst, 0, 20);
            }

            foreach ($query_rst as $key => $value) {
                $results[$value['site_id']][$value['product_id']] = array(
                    'sku' => $value['sku'],
                    'name' => urlencode($value['title']),
                    'number' => intval($value['count']),
                );
            }

            $day = date('Y-m-d', strtotime('-1 day'));

            foreach ($results as $site_id => $value) {
                $data = array(
                    'site_id' => $site_id,
                    'day' => $day,
                    'info' => json_encode($value),
                    'create_time' => time(),
                );

                $this->mBase->insert('stat_product_fav', $data);
                unset($data);
            }
        }
    }

    private function order_amount(){
        $day = date('Y-m-d', strtotime('-1 day'));

        $site_sql = "SELECT count(o.id) as amount,o.site_id FROM orders AS o 
                LEFT JOIN orders_alipay AS oa ON o.id=oa.order_id
                WHERE o.order_status=2
                GROUP BY o.site_id";
        $query_site_rst = $this->db->query($site_sql);
        if($query_site_rst){
            $query_site_rst = $query_site_rst->result_array('array');
            foreach ($query_site_rst as $key => $sin_site) {
                $site_data = array(
                    'type' => 'order',
                    'model' => 'total',
                    'dim' => 'sites',
                    'day' => $day,
                    'dim_id' => $sin_site['site_id'],
                    'info' => $sin_site['amount'],
                    'create_time' => time(),
                );
                $this->mBase->insert('stat_order_sales', $site_data);
            }
        }

        $store_sql = "SELECT count(o.id) as amount,o.store_id FROM orders AS o 
                LEFT JOIN orders_alipay AS oa ON o.id=oa.order_id
                WHERE o.order_status=2
                GROUP BY o.store_id";
        $query_store_rst = $this->db->query($store_sql);
        if($query_store_rst){
            $query_store_rst = $query_store_rst->result_array('array');
            foreach ($query_store_rst as $key => $sin_store) {
                $store_data = array(
                    'type' => 'order',
                    'model' => 'total',
                    'dim' => 'stores',
                    'day' => $day,
                    'dim_id' => $sin_store['store_id'],
                    'info' => $sin_store['amount'],
                    'create_time' => time(),
                );
                $this->mBase->insert('stat_order_sales', $store_data);
            }
        }
    }

    private function sales_volume(){
        $day = date('Y-m-d', strtotime('-1 day'));

        $site_sql = "SELECT sum(o.price) as amount,o.site_id FROM orders AS o 
                LEFT JOIN orders_alipay AS oa ON o.id=oa.order_id
                WHERE o.order_status=2
                GROUP BY o.site_id";
        $query_site_rst = $this->db->query($site_sql);
        if($query_site_rst){
            $query_site_rst = $query_site_rst->result_array('array');
            foreach ($query_site_rst as $key => $sin_site) {
                $site_data = array(
                    'type' => 'sales',
                    'model' => 'total',
                    'dim' => 'sites',
                    'day' => $day,
                    'dim_id' => $sin_site['site_id'],
                    'info' => $sin_site['amount'],
                    'create_time' => time(),
                );
                $this->mBase->insert('stat_order_sales', $site_data);
            }
        }

        $store_sql = "SELECT sum(o.price) as amount,o.store_id FROM orders AS o 
                LEFT JOIN orders_alipay AS oa ON o.id=oa.order_id
                WHERE o.order_status=2
                GROUP BY o.store_id";
        $query_store_rst = $this->db->query($store_sql);
        if($query_store_rst){
            $query_store_rst = $query_store_rst->result_array('array');
            foreach ($query_store_rst as $key => $sin_store) {
                $store_data = array(
                    'type' => 'sales',
                    'model' => 'total',
                    'dim' => 'stores',
                    'day' => $day,
                    'dim_id' => $sin_store['store_id'],
                    'info' => $sin_store['amount'],
                    'create_time' => time(),
                );
                $this->mBase->insert('stat_order_sales', $store_data);
            }
        }
    }
}
