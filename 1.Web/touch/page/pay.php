<?php
require_once('../config.php'); 
	 $site_url = $site['url'];
$script = array('pay');
$page['title'] = '订单结算';

include '../layout/header.php'; 
?>

<div class="pay">
	<div class="well">填写并核对订单</div>
	<div class="pay-address">
		<div class="pay-address-title"><i class="icon-form"></i>收货地址 [ <a href="<?=$site_url.'/page/address_add.php'?>" target="_blank">添加收货地址</a>]</div>
	<div id="address-box" >
		
	</div>
	</div>

	<div class="pay-delivery_type">
		<div class="pay-delivery_type-title"><i class="icon-form"></i>物流类型</div>
		<ul>
			<li><input type="radio" name="pay-delivery_type" value="1" checked="checked" class="pay-delivery_type" /><label>惠生活物流配送</label></li>
			<li><input type="radio" name="pay-delivery_type" value="0" class="pay-delivery_type"/><label>用户自提</label></li>
		</ul>
	</div>
	<div class="pay-store">
		<div class="pay-store-title">就近门店</div>
		<div id="store-box"></div>
	</div>
	<div class="pay-type">
		<div class="pay-type-title"><i class="icon-form"></i>支付方式</div>
		<div id="type-box">
			<ul>
				<li><input type="radio" name="pay-type" class="pay-type" value="3" checked="checked" /><label>会员卡支付</label></li>
				<li><input type="radio" name="pay-type" class="pay-type" value="2" /><label>支付宝支付</label></li>
				<li><input type="radio" name="pay-type" class="pay-type" value="1" /><label>货到付款</label></li>
			</ul>
		</div>
	</div>
	<div class="pay-datetime-list">
	<div class="pay-datetime-list-title"><i class="icon-form"></i>配送时间</div>
	<div id="pay-datetime-box">
		<ul>
			
			<li><input type="radio" name="pay-datetime" class="receipt_datetime" checked="checked" value='1'><label>正常收货</label></li>
			<li><input type="radio" name="pay-datetime" class="receipt_datetime" value='2'><label>仅工作日</label></li>
			<li><input type="radio" name="pay-datetime" class="receipt_datetime" value='3'><label>仅周末</label></li>
		</ul>

	</div>
	</div>
	<div class="pay-product-list">
		<div class="pay-product-list-title"><i class="icon-form"></i>商品清单</div>
		<div id="product-box"></div>
	</div>

	<div class="pay-coupon-list">
		
		<div class="pay-coupon-list-title"><i class="icon-ticket"></i>可用优惠券</div>
		<div id="coupon-box"></div>

	</div>

	<div class="pay-btn-box">
		
		<a href="javascript:;" class="btn btn-red padding" id="pay-btn"><i class="icon-pay"></i>支付订单</a>
	</div>
</div>

<script type="text/html" id="coupon-tpl">
	<ul>
		{{#if( d.list.length > 0 ) { }}
			<li> <input type="radio" name="coupin_list" class="coupon-list" value="0" checked="checked"> <label>取消使用优惠券</label></li>
			{{# for(var x in d.list) { }}
				
				<li> <input type="radio" name="coupin_list" class="coupon-list" value="{{ d.list[x].id }}"> <label>{{ d.list[x].coupon_code }}</label><label>( {{ d.list[x].coupon_limit }} )</label></li>
			
			{{# } }}
		{{# } }}
	</ul>
</script>

<script type="text/html" id="address-temp">

		{{# if( d.list.length == 0) { }}

			收货地址为空  <a href="<?=$site_url.'/page/address_add.php'?>">请添加<a>
		{{# } else { }}
			
			<ul>
				{{# for( var x in d.list) { }}
					<li>
					{{# if(d.list[x].is_default == 1) { }}
					<input type="radio" name="pay-address" checked="checked" class="pay-address-list" value="{{ d.list[x].id }}">
					{{# } else { }}
					<input type="radio" name="pay-address" class="pay-address-list" value="{{ d.list[x].id }}">
					{{# } }}
					<label>收货人：{{ d.list[x].receiver }}</label> 
					
                        {{# if(d.list[x].mobile == "") { }}
                            <label>( {{ d.list[x].tel }} )</label>
                        {{# } else { }}
					       <label>( {{ d.list[x].mobile }} )</label>
                       {{# }  }}
					<label>{{ d.list[x].address }}</label>
					</li>
				{{# } }}
			</ul>

		{{# } }}

</script>
<script type="text/html" id="product-temp">
<table>
<thead>
<tr>
<th colspan="2"> 商品</th>
<th>单价</th>
<th>数量</th>
<th>小计</th>
<th>库存</th>
</tr>
</thead>
<tbody>

{{# if( d.length != 0)  { }}
{{# for(var x in d.products) { }}
<tr data-product-id="{{ d.products[x].product_id }}">
<td class="product-thumb">{{d.products[x].product_id }}</td>
<td class="product-title">{{ d.products[x].title }}</td>
<td class="product-price">{{ d.products[x].price }}</td>
<td class="product-amount">{{ d.products[x].amount }}</td>
<td class="product-price">{{ (d.products[x].price * d.products[x].amount).toFixed(2) }}</td>
<td>
{{# if( d.products[x].is_stock ) { }}
	<label class="text-green"> 有 </label>
{{# } else { }}
	<label class="text-red"> 无 </label>
{{# } }}
</td>
</tr>
{{# } }}
</tbody>
<tfoot>
	<tr>
	<td>总价</td>
		<td colspan="2"></td>
		<td>{{ d.amount }}</td>
		<td>{{ d.price }}</td>
		<td></td>
	</tr>
</tfoot>
</table>
{{# } }}
</script>

<script type="text/html" id="store-tpl">
		<ul>
		{{# for(var x in d.stores) { }}
		<li><input type="radio" name="store-list" class="store-list" value="{{ d.stores[x].id }}">{{ d.stores[x].name }}</li>
		{{# } }}
		</ul>
</script>
<?php include '../layout/footer.php';?>