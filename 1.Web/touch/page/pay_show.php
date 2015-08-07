<?php
require_once('../config.php'); 
	 $site_url = $site['url'];
$script = array('pay_show');
$page['title'] = '订单确认';
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : 0;
include '../layout/header.php'; 
?>

<div class="pay_show" data-order-id="<?=$order_id?>">
	<div class="well">订单编号为：<b><?=base64_decode($order_id) ?></b></div>
	<div id="pay_show-box">
	</div>
	<div class="pay_show-btn">
		<a href="<?=$site_url .'/page/cash.php?order_id='.$order_id?>" class="btn btn-blue padding"><i class="icon-shop"></i> 前往收银台</a>
	</div>
<script type="text/html" id="pay_show-tpl">

{{# if( d.length != 0) { }}
	<div class="product_list">
		<h3>商品信息</h3>
		<ul class="clearfix">
			{{# for(var x in d.details) { }}
				<li>
				<dl>
					<dt>名称：<label>{{ d.details[x].product_name }}</label></dt>
					<dd class="text-red">单价： <label>{{ d.details[x].price_single }}</label> &frasl; 数量：<label>{{ d.details[x].amount }}</label> &frasl; 小计：<label>{{ d.details[x].price_total }}</label></dd>
				</dl>
				</li>
			{{# } }}
				<li>
					<b>总价： </b><label>{{ d.price_total }}</label>
				</li>
				
				<li>
				{{# if( d.delivery_type == 1) { }}
					<dl>
						<dt>收货地址：<label>{{ d.prov }}</label> &frasl; <label>{{ d.city }}</label> &frasl; <label>{{ d.district }}</label> &frasl; <label>{{ d.street }}</label></dt>
						<dd style="padding-left:5em;">{{ d.address }}  {{# if( d.zip != "") { }}<label>[ {{ d.zip }} ]</label> {{# } }}</dd>
						<dd>收货人：<label> {{ d.receiver }} </label>  <label>( {{ d.mobile }} )</label></dd>
					</dl>
				{{# } else{ }}
					<b>收货: </b><label>用户自提</label>
				{{# } }}
				</li>
				
				<li>
					运费:<label> {{ d.price_shipping }} </label>
				</li>
				<li>折扣金额：<label>{{ d.price_discount }}</label> </li>
				<li>优惠金额：<label>{{ d.price_minus }}</label> </li>
				<li>代金券：<label>{{ d.price_cash }}</label> </li>
				<li>应付金额：<label>{{ d.price }}</label> </li>
				<li>支付方式：
				{{# if(d.pay_type == "1") { }}
				<label>货到付款</label>
				{{# } else if(d.pay_type == "2") { }}
				<label>支付宝</label>
				{{# } else if(d.pay_type == "3") { }}
				<label>会员卡</label>
				{{# } else { }}
				<label>未知</label>
				{{# } }}
				</label>
				 </li>
		</ul>
	</div>

{{# } }}
</script>
</div>
<?php include '../layout/footer.php';?>