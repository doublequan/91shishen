<?php
/**
 * 购物车
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-5
 */
defined('BASEPATH') || exit('Access denied');
?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title>购物车 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/gwc.css"/>
</head>
<body>
<?php $this->load->view('common/vip_login_header')?>
<div class="zt area">
	<img src="<?=STATICURL?>images/img26.jpg" />
		<div class="right step1" >
		<div class="bg"><div class="w"></div></div>
		<span class="tp1">查看购物车</span>
		<span class="tp2">填写订单</span>
		<span class="tp3">付款，完成购买</span>
	</div>
</div>
<div class="sp_list mt10 area">
	<table>
		<tbody>
			<tr><th class="thA">商品名称</th><th class="thB">单价</th><th class="thC">数量</th><th class="thD">小计</th><th class="thE">操作</th></tr>
		</tbody>
		<?php $total_price = 0.00;$total_num = 0;if(!empty($data)):foreach($data as $k=>$v):?>
		<tbody id="product_<?=$k?>" class="product_list">
			<tr class="bd">
				<td><div class="pt clearfix"><?=$v['title']?></div></td>
				<td id="sell_price_<?=$k?>">￥<?=$v['price']?></td>
				<td>
					<div class="num">
						<em class="tp1" onclick="updCart(<?=$k?>, 'dec')">-</em>
						<input type="text" id="number_<?=$k?>" class="count" value="<?=$v['amount']?>" onchange="updCart(<?=$k?>)" />
						<em class="tp2" onclick="updCart(<?=$k?>, 'inc')">+</em>
					</div>
				</td>
				<td id="single_price_<?=$k?>">￥<?=sprintf('%.2f', $v['price']*$v['amount'])?></td>
				<td class="lh16"><a href="javascript:;" onclick="delCart(<?=$k?>)">删除</a></td>
			</tr>
		</tbody>
		<?php $total_price += $v['price']*$v['amount'];$total_num += $v['amount'];endforeach;endif;?>
		<tbody>
			<td colspan="5">
				<div class="total">
					<a class="btn" href="javascript:;" onclick="emptyCart()">清空购物车</a>共&nbsp;<b id="total_number"><?=$total_num?></b>&nbsp;件商品，总计：<em id="total_price">￥<?=sprintf('%.2f', $total_price)?></em>
				</div>
			</td>
		</tbody>
	</table>
	<div class="js">
		<a class="btn2" href="<?=base_url('vip/product/index')?>">继续购物</a>
		<a class="btn" href="javascript:;" onclick="doCheck()">订单结算</a>
	</div>
</div>
<?php $this->load->view('common/footer')?>
<script type="text/javascript">
//商品总数量
var total_number = <?=$total_num?>;
//删除单商品
function delCart(product_id){
	var n_pattern = /^[1-9][0-9]*$/;
	if( ! n_pattern.test(product_id)){
		return false;
	}
	layer.confirm("确定从购物车中删除该商品？", function(){
		layer.load("提交中...");
		$.ajax({
			url: "<?=base_url('vip/cart/manage/del')?>",
			type: "post",
			dataType: "json",
			data: {"product_id":product_id},
			success: function(data){
				if(data.err_no == 0){
					$("#product_"+product_id).remove();
					$("#total_price").html('￥'+data.results.total_price);
					$("#total_number").html(data.results.total_number);
					total_number = data.results.total_number;
					layer.msg("删除商品成功！", 2, 1);
				}else{
					layer.msg("删除商品失败！", 2, 5);
				}
			}
		});
	});
}
//清空购物车
function emptyCart(){
	if(total_number <= 0){
		layer.alert("当前购物车为空，请先选购商品！", 5);
		return false;
	}
	layer.confirm("确定清空购物车？", function(){
		layer.load("提交中...");
		$.ajax({
			url: "<?=base_url('vip/cart/manage/empty')?>",
			type: "post",
			dataType: "json",
			success: function(data){
				if(data.err_no == 0){
					total_number = 0;
					$("#total_price").html('￥0.00');
					$("#total_number").html('0');
					$(".product_list").remove();
					layer.msg("清空购物车成功！", 2, 1);
				}else{
					layer.msg("清空购物车失败！", 2, 5);
				}
			}
		});
	});
}
//更新单商品
function updCart(product_id, action){
	var n_pattern = /^[1-9][0-9]*$/;
	if( ! n_pattern.test(product_id)){
		return false;
	}
	var buy_number = $("#number_"+product_id).val();
	buy_number = isNaN(parseInt(buy_number)) ? 1 : Math.max(1, parseInt(buy_number));
	//增减
	if(action == "inc"){
		buy_number += 1;
	}else if(action == "dec"){
		if(buy_number <= 1)	return false;
		buy_number -= 1;
		buy_number = Math.max(1, buy_number);
	}
	$.ajax({
		url: "<?=base_url('vip/cart/manage/upd')?>",
		type: "post",
		dataType: "json",
		data: {"product_id":product_id, "amount":buy_number},
		success: function(data){
			if(data.err_no == 0){
				$("#number_"+product_id).val(buy_number);
				$("#sell_price_"+product_id).html('￥'+data.results.price);
				$("#single_price_"+product_id).html('￥'+data.results.single_price);
				$("#total_price").html('￥'+data.results.total_price);
				$("#total_number").html(data.results.total_number);
			}
		}
	});
}
//结算
function doCheck(){
	if(total_number <= 0){
		layer.alert("当前购物车为空，请先选购商品！", 5);
		return false;
	}else{
		location.href = "<?=base_url('vip/order/check')?>";
	}
}
</script>
</body>
</html>