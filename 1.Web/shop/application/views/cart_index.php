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
<?php $this->load->view('common/login_header')?>
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
			<tr><th style="width:30px;"><input type="checkbox" onclick="pselCart()" id="pselCart" /></th><th class="thA">常规商品</th><th class="thB">单价</th><th class="thC">数量</th><th class="thD">小计</th><th class="thE">操作</th></tr>
		</tbody>
		<?php
		$total_price = 0.00;
		$total_num = 0;
		if( $data ){
			foreach( $data as $k=>$v ){
				$row_price = $v['price']*($v['free'] ? $v['amount']-1 : $v['amount']);
				if( isset($v['minus']) ){
					$row_price -= $v['minus'];
				}
				$total_price += $row_price;
				$total_num += $v['amount'];
		?>
		<tbody id="product_<?=$k?>" class="product_list">
			<tr class="bd">
				<td><input type="checkbox" name="product_ids[]" value="<?=$k?>" /></td>
				<td>
					<div class="pt clearfix">
						<a href="<?=base_url("goods_{$k}.html")?>" target="_blank">
							<img src="<?=$v['thumb']?>" title="<?=$v['title']?>" width="60" height="60" />
						</a>
						<a href="<?=base_url("goods_{$k}.html")?>" target="_blank">
							<?=$v['title']?>
							<?php ?>
						</a>
						<p><?php echo $v['free_label'] ? $v['free_label'] : ''; ?></p>
					</div>
				</td>
				<td id="sell_price_<?=$k?>">￥<?=$v['price']?></td>
				<td>
					<div class="num">
						<em class="tp1" onclick="updCart(<?=$k?>, 'dec')">-</em>
						<input type="text" id="number_<?=$k?>" class="count" value="<?=$v['amount']?>" onchange="updCart(<?=$k?>)" />
						<em class="tp2" onclick="updCart(<?=$k?>, 'inc')">+</em>
					</div>
				</td>
				<td id="single_price_<?=$k?>">￥<?=sprintf('%.2f', $row_price)?></td>
				<td class="lh16"><a href="javascript:;" onclick="delCart(<?=$k?>)">删除</a></td>
			</tr>
		</tbody>
		<?php
			}
		}
		?>
		<tbody>
			<td colspan="6">
				<div class="total">
					<a class="btn" style="margin-right:10px;" href="javascript:;" onclick="pdelCart()">批量删除</a><a class="btn" href="javascript:;" onclick="emptyCart()">清空购物车</a>共&nbsp;<b id="total_number"><?=$total_num?></b>&nbsp;件商品，总计：<em id="total_price">￥<?=sprintf('%.2f', $total_price)?></em>
				</div>
			</td>
		</tbody>
	</table>
	<div class="js">
		【单笔订单实付金额满<?=SHIPPING_FREELIMIT?>元免运费】
		<a class="btn2" href="<?=base_url()?>">继续购物</a>
		<a class="btn" href="javascript:;" onclick="doCheck()">订单结算</a>
	</div>
</div>
<?php $this->load->view('common/footer')?>
<script type="text/javascript">
//商品总数量
var total_number = <?=$total_num?>;
//批量选中
function pselCart(){
	var is_selected = $("#pselCart").prop("checked");
	$("input[name='product_ids[]']").each(function(){
		$(this).prop("checked", is_selected);
	});
}
//批量删除
function pdelCart(){
	var product_ids = new Array();
	$("input[name='product_ids[]']").each(function(){
		if($(this).prop("checked")){
			product_ids.push($(this).val());
		}
	});
	if(product_ids == ""){
		layer.alert("请选择要删除的商品！", 5);
		return false;
	}
	layer.confirm("确定批量删除选中的商品？", function(){
		layer.load("提交中...");
		$.ajax({
			url: "<?=base_url('cart/manage/pdel')?>",
			type: "post",
			dataType: "json",
			data: {"product_ids":product_ids},
			success: function(data){
				if(data.err_no == 0){
					for(i = 0; i < product_ids.length; i++){
						$("#product_"+product_ids[i]).remove();
					}
					$("#total_price").html('￥'+data.results.total_price);
					$("#total_number").html(data.results.total_number);
					total_number = data.results.total_number;
					layer.msg("批量删除商品成功！", 2, 1);
				}else{
					layer.msg("批量删除商品失败！", 2, 5);
				}
			}
		});
	});
}
//删除单商品
function delCart(product_id){
	var n_pattern = /^[1-9][0-9]*$/;
	if( ! n_pattern.test(product_id)){
		return false;
	}
	layer.confirm("确定从购物车中删除该商品？", function(){
		layer.load("提交中...");
		$.ajax({
			url: "<?=base_url('cart/manage/del')?>",
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
			url: "<?=base_url('cart/manage/empty')?>",
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
		url: "<?=base_url('cart/manage/upd')?>",
		type: "post",
		dataType: "json",
		data: {"product_id":product_id, "amount":buy_number},
		success: function(data){
			if(data.err_no == 0){
				$("#number_"+product_id).val(buy_number);
				if( (action=="inc" && data.results.total_price>=59) || (action=="dec" && data.results.total_price<59) ){
					window.location.reload();
				} else {
					$("#sell_price_"+product_id).html('￥'+data.results.price);
					$("#single_price_"+product_id).html('￥'+data.results.single_price);
					$("#total_price").html('￥'+data.results.total_price);
					$("#total_number").html(data.results.total_number);
				}
			}else if(data.err_no == 1003){
				layer.msg("该商品已下架！", 2, 5);
			}else if(data.err_no == 1004){
				layer.msg("该商品库存不足！", 2, 5);
			}else{
				layer.msg("更新商品数量失败！", 2, 5);
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
		location.href = "<?=base_url('member/order/check')?>";
	}
}
</script>
</body>
</html>