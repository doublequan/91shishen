<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once dirname(__FILE__) . '/../base.php';

class order extends Base {
    private $active_top_tag = 'order';
    private $sites = array();
    private $stores = array();
    private $order_status_types = array();
    private $order_date_types = array();

    public function __construct() {
        parent::__construct();
        $res = $this->mBase->getList('sites', array('AND' => array('is_del=0')));
        if ($res) {
            foreach ($res as $row) {
                $this->sites[$row['id']] = $row;
            }
        }
        $res = $this->mBase->getList('stores', array('AND' => array('is_del=0')));
        if ($res) {
            foreach ($res as $row) {
                $this->stores[$row['id']] = $row;
            }
        }
        $this->order_status_types = getConfig('order_status_types');
        $this->order_date_types = getConfig('order_date_types');
        $this->pay_status_types = getConfig('pay_status_types');
    }

    public function index() {
        $data = array();
        //init parameters
        $must = array();
        $fields = array(
            'status',
            'site_id',
            'store_id',
            'deal_site_id',
            'deal_store_id',
            'day',
            'keyword',
            'product_name',
            'page',
            'size',
            'pay_status',
            'order_strat_date',
            'order_end_date',
            'date_day',
            'date_noon',
            'price_from',
            'price_to',
            'username',
            'pay_type',
        );
        $this->checkParams('get', $must, $fields);
        if ($this->params['status'] == '' || !array_key_exists($this->params['status'], $this->order_status_types)) {
            $this->params['status'] = -1;
        }
        $this->params['site_id'] = intval($this->params['site_id']);
        if (!array_key_exists($this->params['site_id'], $this->sites)) {
            $this->params['site_id'] = 0;
        }
        $this->params['store_id'] = intval($this->params['store_id']);
        if (!array_key_exists($this->params['store_id'], $this->stores)) {
            $this->params['store_id'] = 0;
        }
        $this->params['deal_site_id'] = intval($this->params['deal_site_id']);
        if (!array_key_exists($this->params['deal_site_id'], $this->sites)) {
            $this->params['deal_site_id'] = 0;
        }
        $this->params['deal_store_id'] = intval($this->params['deal_store_id']);
        if (!array_key_exists($this->params['deal_store_id'], $this->stores)) {
            $this->params['deal_store_id'] = 0;
        }
        if ($this->params['pay_status'] == '' || !array_key_exists($this->params['pay_status'], $this->pay_status_types)) {
            $this->params['pay_status'] = -1;
        }
        $params = $data['params'] = $this->params;
        $page = max(1, intval($params['page']));
        $size = max(20, min(500, intval($params['size'])));
        $data['url'] = http_build_query($params);
        //get data
        $condition = array();
        if ($params['status'] != -1) {
            $condition['AND'][] = 'order_status=' . $params['status'];
        }
        //advance search
        if($params['pay_type']){
            // pay_type
            $condition['AND'][] = 'pay_type=' . $params['pay_type'];
        }
        if ($params['store_id']) {
            $condition['AND'][] = 'store_id=' . $params['store_id'];
        }
        if ($params['site_id']) {
            $condition['AND'][] = 'site_id=' . $params['site_id'];
        }
        if ($params['deal_store_id']) {
            $condition['AND'][] = 'deal_store_id=' . $params['deal_store_id'];
        }        
        $p = $params['product_name'];
        if ($p) {
            $condition['AND'][] = "product_name like '%" . $p . "%'";
        }         
        $k = $params['keyword'];
        if ($k){
            $condition['AND'][] = "id like '%" . $k . "%'";
        }
        if ($params['pay_status'] != -1) {
            $condition['AND'][] = "pay_status={$params['pay_status']}";
        }
        if ($params['date_day']) {
            $condition['AND'][] = "date_day='{$params['date_day']}'";
        }
        $params['date_noon'] = intval($params['date_noon']);
        if ($params['date_noon']) {
            $condition['AND'][] = "date_noon={$params['date_noon']}";
        }
        if (!empty($params['order_strat_date']) && !empty($params['order_end_date'])) {
            $order_strat_date = strtotime($params['order_strat_date']);
            $order_end_date = strtotime($params['order_end_date']);
            $condition['AND'][] = "create_time>={$order_strat_date}";
            $condition['AND'][] = "create_time<={$order_end_date}";
        }
        if (!empty($params['price_from'])) {
            $condition['AND'][] = "price>=" . doubleval($params['price_from']);
        }
        if (!empty($params['price_to'])) {
            $condition['AND'][] = "price<=" . doubleval($params['price_to']);
        }
        if (!empty($params['username'])) {
            $adv_user = $this->mBase->getSingle('users', 'username', $params['username']);
            $uid = 0;
            if ($adv_user) {
                $uid = $adv_user['id'];
            }
            $condition['AND'][] = 'uid=' . $uid;
        }   
        $data['users'] = array();
        $this->load->model('Order_model','mOrder');
        //根据是否有条件值调用不同的获取数据的方法
        if(!empty($condition)){
           $res = $this->mOrder->getMoreList($condition, $page, $size);
           $outdata = $this->mOrder->getMoreList($condition, 0, 0);
        }else{
           $res = $this->mBase->getList('orders', $condition, '*', 'create_time DESC', $page, $size); 
           $outdata = $this->mBase->getList('orders', $condition, '*', 'create_time DESC', 0, 0); 
        }
        $_SESSION['outdata']=$outdata;
        if ($res->results) {
            foreach ($res->results as &$row) {
                $row['order_id'] = $k ? str_replace($k, '<font color="red">' . $k . '</font>', $row['id']) : $row['id'];
                if (!isset($data['users'][$row['uid']])) {
                    $t = $this->mBase->getSingle('users', 'id', $row['uid']);
                    if ($t && isset($t['username'])) {
                        $data['users'][$row['uid']] = $t['username'];
                    } else {
                        $data['users'][$row['uid']] = '未知用户';
                    }
                }
            }
            unset($row);
        }       
        $data['results'] = $res->results;
        $data['pager'] = $res->pager;
        //common data
        $data['order_status_types'] = $this->order_status_types;
        $data['order_date_types'] = $this->order_date_types;
        $data['pay_status_types'] = $this->pay_status_types;
        $data['sites'] = $this->sites;
        $data['stores'] = $this->stores;
        $data['storeMap'] = array();
        if ($this->stores) {
            foreach ($this->stores as $row) {
                $data['storeMap'][$row['site_id']][] = $row;
            }
        }
        //display templates
        $tags['active_top_tag'] = $this->active_top_tag;
        $tags['order_status_types'] = $this->order_status_types;
        $tags['active_menu_tag'] = 'status_' . $params['status'];
        $this->_view('common/header', $tags);
        $this->_view('order/order_list', $data);
        $this->_view('common/footer');
    }

    public function detail() {
        $data = array();
        //get parameters
        $must = array('order_id');
        $fields = array();
        $this->checkParams('get', $must, $fields);
        $params = $data['params'] = $this->params;
        //get order
        $data['single'] = $this->mBase->getSingle('orders', 'id', $params['order_id']);
        if (!$data['single']) {
            $this->showMsg(1000, '订单不存在');
            return;
        }
        //get order details
        $condition = array(
            'AND' => array("order_id='" . $params['order_id'] . "'"),
        );
        $data['details'] = $this->mBase->getList('orders_detail', $condition);
        if ($data['details']) {
            foreach ($data['details'] as &$row) {
                $t = $this->mBase->getSingle('products', 'id', $row['product_id']);
                $row['product_no'] = $t['sku'];
                $row['unit'] = $t['unit'];
                $row['spec'] = $t['spec'];
                $row['spec_packing'] = $t['spec_packing'];
            }
            unset($row);
        }
        //get userinfo
        $data['current_user'] = array();
        if ($data['single']['uid']) {
            $data['current_user'] = $this->mBase->getSingle('users', 'id', $data['single']['uid']);
        }
        //get deal order info
        $data['deal_store'] = array();
        if ($data['single']['deal_store_id']) {
            $store = $this->mBase->getSingle('stores', 'id', $data['single']['deal_store_id']);
            $arr = array('prov', 'city', 'district');
            foreach ($arr as $v) {
                if ($store[$v]) {
                    $cache_key = 'AREA_' . $store[$v];
                    $t = Common_Cache::get($cache_key);
                    if (!$t) {
                        $t = $this->mBase->getSingle('areas', 'id', $store[$v]);
                        Common_Cache::save($cache_key, $t, 86400);
                    }
                    $store[$v] = $t ? $t['name'] : '';
                }
            }
            $data['deal_store'] = $store;
        }
        //get deal stores
        $condition = array(
            'AND' => array('site_id=' . $data['single']['site_id'], 'is_del=0'),
        );
        $data['deal_stores'] = $this->mBase->getList('stores', $condition, '*', 'id ASC');
        //get store info
        $data['store'] = array();
        if ($data['single']['store_id']) {
            $store = $this->mBase->getSingle('stores', 'id', $data['single']['store_id']);
            $arr = array('prov', 'city', 'district');
            foreach ($arr as $v) {
                if ($store[$v]) {
                    $cache_key = 'AREA_' . $store[$v];
                    $t = Common_Cache::get($cache_key);
                    if (!$t) {
                        $t = $this->mBase->getSingle('areas', 'id', $store[$v]);
                        Common_Cache::save($cache_key, $t, 86400);
                    }
                    $store[$v] = $t ? $t['name'] : '';
                }
            }
            $data['store'] = $store;
        }
        if ($data['single']['delivery_type'] != 0) {
            //get district id
            $district_id = 0;
            $district = $this->mBase->getSingle('areas', 'name', $data['single']['district']);
            if ($district) {
                $district_id = intval($district['id']);
            }
            //get all delivery places of current city
            $condition = array(
                'AND' => array('is_delivery=1'),
            );
            if ($district_id) {
                $data['label'] = $district['name'];
                $condition = array(
                    'AND' => array('district=' . $district_id, 'is_delivery=1'),
                );
            } else {
                $data['label'] = $data['single']['city'];
                $condition = array(
                    'AND' => array('site_id=' . $data['single']['site_id'], 'is_delivery=1'),
                );
            }
            $data['stores'] = $this->mBase->getList('stores', $condition, '*', 'id ASC');
        }
        //get order action
        $condition = array(
            'AND' => array("order_id='" . $params['order_id'] . "'"),
        );
        $data['actions'] = $this->mBase->getList('orders_action', $condition, '*', 'id ASC');
        //get site
        $data['site'] = $this->sites[$data['single']['site_id']];
        //Common Data
        $data['order_status_types'] = $this->order_status_types;
        $data['pay_types'] = getConfig('pay_types');
        $data['pay_status_types'] = getConfig('pay_status_types');
        $data['order_date_types'] = getConfig('order_date_types');
        //display templates
        $tags['active_top_tag'] = $this->active_top_tag;
        $tags['order_status_types'] = $this->order_status_types;
        $tags['active_menu_tag'] = 'status_' . $data['single']['order_status'];
        $this->_view('common/header', $tags);
        $this->_view('order/order_detail', $data);
        $this->_view('common/footer');
    }

    public function order_print() {
        $data = array();
        //get parameters
        $must = array('order_id');
        $fields = array();
        $this->checkParams('get', $must, $fields);
        $params = $data['params'] = $this->params;
        //get order
        $data['single'] = $this->mBase->getSingle('orders', 'id', $params['order_id']);

        //get order details
        $condition = array(
            'AND' => array("order_id='" . $params['order_id'] . "'"),
        );
        $data['details'] = $this->mBase->getList('orders_detail', $condition);
        if ($data['details']) {
            foreach ($data['details'] as &$row) {
                $t = $this->mBase->getSingle('products', 'id', $row['product_id']);
                $row['product_no'] = $t['sku'];
                $row['unit'] = $t['unit'];
                $row['spec'] = $t['spec'];
                $row['spec_packing'] = $t['spec_packing'];
                $row['sku'] = $t['sku'];
                $row['product_pin'] = $t['product_pin'];
            }
            unset($row);
        }
        //get userinfo
        $data['current_user'] = array();
        if ($data['single']['uid']) {
            $data['current_user'] = $this->mBase->getSingle('users', 'id', $data['single']['uid']);
        }
        //get store info
        $data['store'] = array();
        if ($data['single']['store_id']) {
            $store = $this->mBase->getSingle('stores', 'id', $data['single']['store_id']);
            $arr = array('prov', 'city', 'district');
            foreach ($arr as $v) {
                if ($store[$v]) {
                    $cache_key = 'AREA_' . $store[$v];
                    $t = Common_Cache::get($cache_key);
                    if (!$t) {
                        $t = $this->mBase->getSingle('areas', 'id', $store[$v]);
                        Common_Cache::save($cache_key, $t, 86400);
                    }
                    $store[$v] = $t ? $t['name'] : '';
                }
            }
            $data['store'] = $store;
        }
        //get order action
        $condition = array(
            'AND' => array("order_id='" . $params['order_id'] . "'"),
        );
        $data['actions'] = $this->mBase->getList('orders_action', $condition, '*', 'id ASC');
        //get site
        $data['site'] = $this->sites[$data['single']['site_id']];
        //Common Data
        $data['order_status_types'] = getConfig('order_status_types');
        $data['pay_types'] = getConfig('pay_types');
        $data['pay_status_types'] = getConfig('pay_status_types');
        //display templates
        $tags['active_top_tag'] = $this->active_top_tag;
        $tags['active_menu_tag'] = 'site_' . $data['single']['site_id'];
        $tags['sites'] = $this->sites;
        $this->_view('order/order_print', $data);
    }

    public function order_print_muti() {
        $data = array();
        //get parameters
        $must = array('order_ids');
        $fields = array();
        $this->checkParams('get', $must, $fields);
        $params = $data['params'] = $this->params;

        //get orders
        $order_ids = $params['order_ids'];
        $order_list_tmp = $this->mBase->getList('orders', array('AND' => array('id in (' . $order_ids . ')')));
        if (!$order_list_tmp) {
            $this->showMsg(1000, '订单不存在');
            return;
        }

        $order_list = array();
        foreach ($order_list_tmp as $single_order) {
            $order_list[$single_order['id']]['single'] = $single_order;

            //get userinfo
            $order_list[$single_order['id']]['current_user'] = array();
            if ($single_order['uid']) {
                $order_list[$single_order['id']]['current_user'] = $this->mBase->getSingle('users', 'id', $single_order['uid']);
            }

            //get store info
            $order_list[$single_order['id']]['store'] = array();
            if ($single_order['store_id']) {
                $store = $this->mBase->getSingle('stores', 'id', $single_order['store_id']);
                $arr = array('prov', 'city', 'district');
                foreach ($arr as $v) {
                    if ($store[$v]) {
                        $cache_key = 'AREA_' . $store[$v];
                        $t = Common_Cache::get($cache_key);
                        if (!$t) {
                            $t = $this->mBase->getSingle('areas', 'id', $store[$v]);
                            Common_Cache::save($cache_key, $t, 86400);
                        }
                        $store[$v] = $t ? $t['name'] : '';
                    }
                }
                $order_list[$single_order['id']]['store'] = $store;
            }
        }

        //get order details
        $order_details = $this->mBase->getList('orders_detail', array('AND' => array('order_id in (' . $order_ids . ')')));
        foreach ($order_details as $single_detail) {
            $t = $this->mBase->getSingle('products', 'id', $single_detail['product_id']);
            $single_detail['product_no'] = $t['sku'];
            $single_detail['unit'] = $t['unit'];
            $single_detail['spec'] = $t['spec'];
            $single_detail['spec_packing'] = $t['spec_packing'];
            $single_detail['sku'] = $t['sku'];
            $single_detail['product_pin'] = $t['product_pin'];
            $order_list[$single_detail['order_id']]['details'][] = $single_detail;
        }

        //get order action
        $data['actions'] = $this->mBase->getList('orders_action', array('AND' => array('order_id in (' . $order_ids . ')')), '*', 'id ASC');
        //order_list
        $data['order_list'] = $order_list;

        //Common Data
        $data['order_status_types'] = getConfig('order_status_types');
        $data['pay_types'] = getConfig('pay_types');
        $data['pay_status_types'] = getConfig('pay_status_types');
        //display templates
        $tags['sites'] = $this->sites;
        $this->_view('order/order_print_muti', $data);
    }

    public function create_product_stat() {
        $data = array();
        //get parameters
        $must = array('order_ids');
        $fields = array();
        $this->checkParams('get', $must, $fields);
        $params = $data['params'] = $this->params;
        //get orders
        $order_ids = $params['order_ids'];
        $order_detail_list = $this->mBase->getList('orders_detail', array('AND' => array('order_id in (' . $order_ids . ')')));
        if (!$order_detail_list) {
            $this->showMsg(1000, '订单不存在');
            return;
        }

        $product_ids = array();
        foreach ($order_detail_list as $single_detail) {
            $product_ids[] = $single_detail['product_id'];
        }
        $product_ids = implode(',', $product_ids);
        $condition = array(
            'AND' => array("id in ({$product_ids})")
        );
        $products = array();
        $res = $this->mBase->getList('products', $condition, '*', 'sku ASC');
        if ($res) {
            foreach ($res as $row) {
                $products[$row['id']] = $row;
            }
        }
        foreach ($order_detail_list as $row) {
            if (isset($products[$row['product_id']]['amount'])) {
                $products[$row['product_id']]['amount'] += $row['amount'];
                $products[$row['product_id']]['price_total'] += $row['price_single'] * $row['amount'];
            } else {
                $products[$row['product_id']]['amount'] = $row['amount'];
                $products[$row['product_id']]['price_single'] = $row['price_single'];
                $products[$row['product_id']]['price_total'] = $row['price_single'] * $row['amount'];
            }
        }
        $data['products'] = $products;
        //display templates
        $this->_view('order/order_product_stat', $data);
    }

    public function order_label_print_muti() {
        $data = array();
        //get parameters
        $must = array('order_ids');
        $fields = array();
        $this->checkParams('get', $must, $fields);
        $params = $data['params'] = $this->params;

        //get orders
        $order_ids = $params['order_ids'];
        $order_detail_list = $this->mBase->getList('orders_detail', array('AND' => array('order_id in (' . $order_ids . ')')));
        if (!$order_detail_list) {
            $this->showMsg(1000, '订单不存在');
            return;
        }

        $product_ids = array();
        foreach ($order_detail_list as $single_detail) {
            $product_ids[] = $single_detail['product_id'];
        }
        $product_ids = implode(',', $product_ids);

        $product_list_tmp = $this->mBase->getList('products', array('AND' => array("id in ({$product_ids})")), 'id,sku,product_pin,title,price,spec,spec_packing,unit');

        $product_list = array();
        foreach ($product_list_tmp as $single_product) {
            if ($single_product['product_pin'] < 10000) {
                $product_list[$single_product['id']] = $single_product;
            }
        }
        unset($product_list_tmp);

        $label_list = array();
        foreach ($order_detail_list as $single_detail) {
            $product_id = $single_detail['product_id'];

            if (isset($product_list[$product_id])) {
                $label_list[$product_id]['product'] = $product_list[$product_id];
                $label_list[$product_id]['order'] = $single_detail;
            }
        }

        $data['label_list'] = $label_list;

        //display templates
        $this->_view('order/order_label_print_muti', $data);
    }

    public function doAction() {
        $ret = array('err_no' => 1000, 'err_msg' => '系统错误');
        do {
            //get parameters
            $must = array('order_id', 'status');
            $this->checkParams('post', $must);
            $params = $this->params;
            $status = intval($params['status']);
            //check parameters
            $order_status_types = $this->order_status_types;
            if (!array_key_exists($status, $order_status_types)) {
                $ret = array('err_no' => 1000, 'err_msg' => '非法操作');
                break;
            }
            $order = $this->mBase->getSingle('orders', 'id', $params['order_id']);
            if (!$order) {
                $ret = array('err_no' => 1000, 'err_msg' => '订单不存在');
                break;
            }
            //Check Stock Number If Action==27
            $is_stock = true;
            $condition = array(
                'AND' => array('order_id=' . $params['order_id']),
            );
            $details = $this->mBase->getList('orders_detail', $condition, '*', 'id ASC');
            if ($details && $order['deal_store_id']) {
                foreach ($details as $row) {
                    $whereMap = array(
                        'AND' => array('store_id=' . $order['deal_store_id'], 'product_id=' . $row['product_id']),
                    );
                    $t = $this->mBase->getList('products_stock', $whereMap, 'amount', 'id DESC', 0, 1);
                    if (!$t) {
                        $is_stock = false;
                        break;
                    }
                    $s = current($t);
                    if ($s['amount'] < $row['amount']) {
                        $is_stock = false;
                        break;
                    }
                }
            }
            $is_stock = true;
            if (!$is_stock) {
                $store = $this->mBase->getSingle('stores', 'id', $order['deal_store_id']);
                $store_name = $store ? $store['name'] : '未知仓库';
                $ret = array('err_no' => 1000, 'err_msg' => '订单“' . $order['id'] . '”在“' . $store_name . '”库存不足，不能出库');
                break;
            }
            $data = array(
                'order_status' => $status,
            );
            $this->mBase->update('orders', $data, array('id' => $order['id']));
            //Add Order Action
            $is_show = 1;
            $des = '系统操作';
            if ($status == 21) {
                $des = '您的订单已被确认';
            } elseif ($status == 27) {
                $des = '您的订单已出库';
            } elseif ($status == 20) {
                $des = '您的订单已完成，欢迎您再次光临';
            } else {
                $des = '您的订单正在处理中';
            }
            $data = array(
                'order_id' => $order['id'],
                'status' => $status,
                'des' => $des,
                'is_show' => $is_show,
                'create_eid' => parent::$user['id'],
                'create_name' => parent::$user['username'],
                'create_time' => time(),
            );
            $this->mBase->insert('orders_action', $data);
            //Status=27, When Confirm Delivery, Minus Real Stock
            if ($status == 27) {
                if ($order['deal_store_id']) {
                    $this->load->model('Product_model', 'mProduct');
                    if ($details) {
                        foreach ($details as $row) {
                            $product = $this->mBase->getSingle('products', 'id', $row['product_id']);
                            //update set stock
                            $current = $this->mProduct->updateStock('product', $order['deal_store_id'], $product, 0 - $row['amount']);
                            //add stock log
                            $data = array(
                                'store_id' => $order['deal_store_id'],
                                'product_id' => $row['product_id'],
                                'type' => 2,
                                'change' => $row['amount'],
                                'current' => $current,
                                'create_eid' => self::$user['id'],
                                'create_name' => self::$user['username'],
                                'create_time' => time(),
                            );
                            $this->mBase->insert('products_stock_log', $data);
                        }
                    }
                }
            }
            //Status=10 or 11, When Remove or Cancel Order, RollBack Virtual Stock
            if ($status == 10 || $status == 11) {
                //Allow Rollback Only Current Order Status in (0,1,2)
                if (in_array($order['order_status'], array(0, 1, 2))) {
                    $this->load->model('Product_model', 'mProduct');
                    $condition = array(
                        'AND' => array('order_id=' . $params['order_id']),
                    );
                    $details = $this->mProduct->getList('orders_detail', $condition, '*', 'id ASC');
                    if ($details) {
                        foreach ($details as $row) {
                            $this->mProduct->rollbackStock($row['product_id'], $order['site_id'], $row['amount']);
                        }
                    }
                }
            }
            $ret = array('err_no' => 0, 'err_msg' => '操作成功');
        } while (0);
        $this->output($ret);
    }

    public function doActionMuti() {
        $ret = array('err_no' => 1000, 'err_msg' => '系统错误');
        do {
            //get parameters
            $must = array('order_ids', 'status');
            $this->checkParams('post', $must);
            $params = $this->params;
            $status = intval($params['status']);
            //check parameters
            $order_status_types = $this->order_status_types;
            if (!array_key_exists($status, $order_status_types)) {
                $ret = array('err_no' => 1000, 'err_msg' => '非法操作');
                break;
            }

            $check_rst = true;
            $order_ids = explode(',', $params['order_ids']);
            foreach ($order_ids as $single_order_id) {
                $order = $this->mBase->getSingle('orders', 'id', $single_order_id);
                if (!$order) {
                    $ret = array('err_no' => 1000, 'err_msg' => '订单' . $single_order_id . '不存在');
                    $check_rst = false;
                    break;
                }

                if ($status == 21) {
                    if ($order['order_status'] >= 10) {
                        $ret = array('err_no' => 1000, 'err_msg' => '订单' . $single_order_id . '当前状态无法确认订单');
                        $check_rst = false;
                        break;
                    }
                    if (!($order['pay_type'] == 1 || ($order['pay_type'] > 1 && $order['pay_status'] == 1))) {
                        $ret = array('err_no' => 1000, 'err_msg' => '订单' . $single_order_id . '当前未支付，无法确认订单');
                        $check_rst = false;
                        break;
                    }
                }
                if ($status == 27) {
                    if ($order['order_status'] != 21) {
                        $ret = array('err_no' => 1000, 'err_msg' => '订单' . $single_order_id . '当前状态无法出库');
                        $check_rst = false;
                        break;
                    }
                }
                if ($status == 20) {
                    if ($order['order_status'] != 27) {
                        $ret = array('err_no' => 1000, 'err_msg' => '订单' . $single_order_id . '当前状态无法完成');
                        $check_rst = false;
                        break;
                    }
                }
                if ($status == 10) {
                    if ($order['order_status'] == 10 || $order['order_status'] == 11) {
                        $ret = array('err_no' => 1000, 'err_msg' => '订单' . $single_order_id . '当前状态无法删除');
                        $check_rst = false;
                        break;
                    }
                }
                if ($status == 11) {
                    if ($order['order_status'] == 10 || $order['order_status'] == 11) {
                        $ret = array('err_no' => 1000, 'err_msg' => '订单' . $single_order_id . '当前状态无法取消');
                        $check_rst = false;
                        break;
                    }
                }
            }

            if ($check_rst) {
                foreach ($order_ids as $single_order_id) {
                    $data = array(
                        'order_status' => $status,
                    );
                    $this->mBase->update('orders', $data, array('id' => $single_order_id));
                    //Add Order Action
                    $data = array(
                        'order_id' => $single_order_id,
                        'status' => $status,
                        'des' => parent::$user['username'] . '操作订单',
                        'is_show' => 1,
                        'create_eid' => parent::$user['id'],
                        'create_name' => parent::$user['username'],
                        'create_time' => time(),
                    );
                    $this->mBase->insert('orders_action', $data);
                }
                $ret = array('err_no' => 0, 'err_msg' => '操作成功');
            }
        } while (0);
        $this->output($ret);
    }

    public function doDiscount() {
        $ret = array('err_no' => 1000, 'err_msg' => '系统错误');
        do {
            //get parameters
            $must = array('order_id', 'discount');
            $this->checkParams('post', $must);
            $params = $this->params;
            $discount = floatval($params['discount']);
            //check parameters
            $order = $this->mBase->getSingle('orders', 'id', $params['order_id']);
            if (!$order) {
                $ret = array('err_no' => 1000, 'err_msg' => '订单不存在');
                break;
            }
            if ($order['order_status'] >= 10 || $order['pay_status'] > 0) {
                $ret = array('err_no' => 1000, 'err_msg' => '订单金额已不可变更');
                break;
            }
            $data = array(
                'price' => $order['price'] + $order['price_minus'] - $discount,
                'price_minus' => $discount,
            );
            $this->mBase->update('orders', $data, array('id' => $order['id']));
            //Add Order Action
            $data = array(
                'order_id' => $order['id'],
                'des' => parent::$user['username'] . '手工设置订单优惠' . $discount . '元',
                'is_show' => 0,
                'create_eid' => parent::$user['id'],
                'create_name' => parent::$user['username'],
                'create_time' => time(),
            );
            $this->mBase->insert('orders_action', $data);
            $ret = array('err_no' => 0, 'err_msg' => '操作成功');
        } while (0);
        $this->output($ret);
    }

    public function doSetDealStore() {
        $ret = array('err_no' => 1000, 'err_msg' => '系统错误');
        do {
            //get parameters
            $must = array('order_id', 'deal_store_id');
            $this->checkParams('post', $must);
            $params = $this->params;
            $order_id = $params['order_id'];
            $deal_store_id = intval($params['deal_store_id']);
            //check parameters
            $order = $this->mBase->getSingle('orders', 'id', $order_id);
            if (!$order) {
                $ret = array('err_no' => 1000, 'err_msg' => '订单不存在');
                break;
            }
            $store = $this->mBase->getSingle('stores', 'id', $deal_store_id);
            if (!$store) {
                $ret = array('err_no' => 1000, 'err_msg' => '仓库不存在');
                break;
            }
            $data = array(
                'deal_store_id' => $deal_store_id,
            );
            $this->mBase->update('orders', $data, array('id' => $order_id));
            //Add Order Action
            $data = array(
                'order_id' => $order['id'],
                'des' => '此订单被设置为从' . $store['name'] . '出库',
                'is_show' => 0,
                'create_eid' => parent::$user['id'],
                'create_name' => parent::$user['username'],
                'create_time' => time(),
            );
            $this->mBase->insert('orders_action', $data);
            $ret = array('err_no' => 0, 'err_msg' => '操作成功');
        } while (0);
        $this->output($ret);
    }

    public function doSetStore() {
        $ret = array('err_no' => 1000, 'err_msg' => '系统错误');
        do {
            //get parameters
            $must = array('order_id', 'store_id');
            $this->checkParams('post', $must);
            $params = $this->params;
            $order_id = $params['order_id'];
            $store_id = intval($params['store_id']);
            //check parameters
            $order = $this->mBase->getSingle('orders', 'id', $order_id);
            if (!$order) {
                $ret = array('err_no' => 1000, 'err_msg' => '订单不存在');
                break;
            }
            $store = $this->mBase->getSingle('stores', 'id', $store_id);
            if (!$store) {
                $ret = array('err_no' => 1000, 'err_msg' => '门店不存在');
                break;
            }
            $data = array(
                'store_id' => $store_id,
            );
            $this->mBase->update('orders', $data, array('id' => $order_id));
            //Add Order Action
            $data = array(
                'order_id' => $order['id'],
                'des' => parent::$user['username'] . '将此订单分配到' . $store['name'],
                'is_show' => 0,
                'create_eid' => parent::$user['id'],
                'create_name' => parent::$user['username'],
                'create_time' => time(),
            );
            $this->mBase->insert('orders_action', $data);
            $ret = array('err_no' => 0, 'err_msg' => '操作成功');
        } while (0);
        $this->output($ret);
    }

    public function export() {
        @set_time_limit(1800);
        $outdata=array();
        isset($_SESSION['outdata'])?($outdata=$_SESSION['outdata']):($outdata=array());
        if(!empty($_SESSION['outdata'])){
            foreach ($outdata as $k => $v) {
               $outData[$k][] = $k+1;  
               $outData[$k][] = '\''.$v['id'];
               $outData[$k][] = $v['price'];
               $username='';
               if(!array_key_exists('username',$v)){
                   $users=$this->mBase->getSingle('users','id',$v['uid']);
                   $username=isset($users['username'])?$users['username']:'';
               }else{
                   $username=preg_match('/\d+/',$v['username'])?"'".$v['username']:$v['username'];
               }
               $outData[$k][] =$username;
               $outData[$k][] = $v['receiver'];                
                //订单状态取出名称:0未支付订单,1已支付订单,2货到付款订单,10已删除订单,11已取消订单,20已完成订单,21已确认订单27已出库订单,
               $outData[$k][] = ($v['order_status']=='0')?"未支付订单":(($v['order_status']=='1')?"已支付订单":(($v['order_status']=='2')?"货到付款订单":(($v['order_status']=='10')?"已删除订单":(($v['order_status']=='11')?"已取消订单":(($v['order_status']=='20')?"已完成订单":(($v['order_status']=='21')?"已确认订单":(($v['order_status']=='27')?"已出库订单":"未知")))))));           
               $outData[$k][] = $v['address'];                      
               $outData[$k][] = $v['create_time']?date('Y-m-d H:i:s',$v['create_time']):'';
               $date_day='';
               if($v['date_day']!=='0000-00-00'){
                    $date_day=$v['date_day'].(($v['date_noon']=='1')?'- 上午':'- 下午'); 
               }else{
                    $date_day='不限';
               }
               $outData[$k][] =  $date_day;
               $storename='';
               if(!array_key_exists('send_name',$v)){
                    if(array_key_exists('deal_store_id',$v)){
                        $store=$this->mBase->getSingle('stores','id',$v['deal_store_id']); 
                        $storename=isset($store['name'])?$store['name']:'';
                    }
               }else{
                   $storename=$v['send_name']?$v['send_name']:'';
               }
               $outData[$k][] =$storename; 
            }
            $header = array('序号', '订单号', '订单总价','所属用户', '收货人','订单状态','详细地址','订单生成时间','预计配送时间','配送门店');
            $this->exportExcel($header, $outData);               
        }else{
            $url=site_url('order/order');
            echo "<script language='javascript' type='text/javascript'>alert('没有数据无法导出');window.location.href='$url';</script>";
        }
    }
}
