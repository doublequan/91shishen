<?php

//Global Config Veriables
$config = array();

//Order Status
$config['order_status_types'] = array(
	-1	=> '全部订单',
	0	=> '未支付订单',
	1	=> '已支付订单',
	2	=> '货到付款订单',
	//9	=> '已截单订单',
	21	=> '已确认订单',
	27	=> '已出库订单',
	20	=> '已完成订单',
	11	=> '已取消订单',
	10	=> '已删除订单',
	/**
	0	=> '新订单未支付',
	1	=> '支付成功订单',
	2	=> '支付失败/过期订单',
	3	=> '非法支付订单',
	10	=> '订单已经删除',
	11	=> '用户取消订单',
	12	=> '管理员取消订单',
	20	=> '订单成功完成',
	21	=> '订单已经确认',
	22	=> '采购/备货中',
	23	=> '采购完成，生产装配',
	24	=> '生产装配完成，等待分仓调度或者配送',
	25	=> '调度在途，运输过程中',
	26	=> '送达门店/分仓，等待终端物流配送或者用户自提',
	27	=> '终端物流配送中',
	28	=> '用户自提完成',
	29	=> '用户已签收',
	30	=> '退换货处理完成、退款完成',
	31	=> '用户申请退换货，等待处理',
	32	=> '系统/管理员申请退换货，等待处理',
	33	=> '退换货处理完成，等待付款',
	40	=> '召回处理完成、退款完成',
	41	=> '召回申请，待处理',
	42	=> '召回处理中，未付款',
	100	=> '其他',
	*/
);

//online pay types
$config['pay_types'] = array(
	0	=> '未知支付状态',
	1	=> '货到付款',
	2	=> '支付宝',
	3	=> '会员卡支付',
);

//online pay status types
$config['pay_status_types'] = array(
	0	=> '未支付',
	1	=> '已支付',
	2	=> '已退款',
);

//express types
$config['delivery_types'] = array(
	0	=> '用户自提',
	1	=> '惠生活物流',
);

$config['order_date_types'] = array(
	1	=> '工作日和周末均可',
	2	=> '仅限工作日',
	3	=> '仅限周末',
	4	=> '自定义送货日期',
);

//Device OS types
$config['os_types'] = array(
	0	=> 'Web',
	1	=> 'Android',
	2	=> 'iPhone',
	3	=> 'iPad',
	4	=> 'Touch',
);

//Device Net types
$config['net_types'] = array(
	0	=> '未知网络类型',
	1	=> 'Wifi',
	2	=> '2G',
	3	=> '3G',
	4	=> '4G',
);

//invoice types
$config['invoice_types'] = array('明细','办公用品','劳保','耗材');

//API Map
$config['apiMap'] = array(
	'/common/client/install',
	'/common/client/run',
	'/common/client/info',
	'/common/data/site',
	'/common/data/area',
	'/common/data/about',
	'/common/sms/send',
	'/user/account/login',
	'/user/account/register',
	'/user/account/modify',
	'/user/account/detail',
	'/user/account/sign',
	'/user/safe/reset',
	'/user/safe/forget',
	'/user/prefer/get',
	'/user/prefer/set',
	'/user/order/lists',
	'/user/order/detail',
	'/user/order/cancel',
	'/user/score/lists',
	'/user/address/lists',
	'/user/address/add',
	'/user/address/remove',
	'/user/address/modify',
	'/user/address/setdefault',
	'/user/fav/lists',
	'/user/fav/add',
	'/user/fav/remove',
	'/user/focus/lists',
	'/user/focus/add',
	'/user/focus/remove',
	'/user/coupon/lists',
	'/user/coupon/usable',
	'/user/msg/lists',
	'/user/msg/read',
	'/product/main/lists',
	'/product/main/detail',
	'/product/category',
	'/product/search',
	'/product/search/rand',
	'/product/comment/lists',
	'/product/comment/add',
	'/product/comment/remove',
	'/product/special/detail',
	'/product/promotion/free',
	'/pay/success',
	'/pay/alipay_notify',
	'/pay/yeepay_card',
	'/pay/yeepay_card_notify',
	'/cart/get',
	'/cart/sync',
	'/cart/add',
	'/cart/remove',
	'/order/add',
	'/order/one',
	'/frag/lists',
	'/vip/category/lists',
	'/vip/product/lists',
	'/vip/product/detail',
	'/vip/product/price',
	'/vip/user/login',
);

$config['task_types'] = array(
	1	=> '用户订单',
	2	=> '大客户订单',
	3	=> '大客户定制',
	4	=> '财务单申请',
	5	=> '采购单申请',
	6	=> '调度单申请',
);

$config['task_status_types'] = array(
	1	=> '未处理',
	2	=> '进行中',
	3	=> '待处理',
	4	=> '延期处理',
	5	=> '完成',
	6	=> '放弃',
);

$config['good_method_types'] = array(
	1	=> '计重',
	2	=> '计数',
);

$config['good_unit_types']= array(
	1	=> array('斤','千克','克','两','吨'),
	2	=> array('个','包','箱','盒','瓶'),
);