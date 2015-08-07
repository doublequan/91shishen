<?php
/**
 * 全部商品
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-30
 */
defined('BASEPATH') || exit('Access denied');
?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title><?php if(empty($category['name'])):$category['id']=0;?>全部商品<?php else:?><?=$category['name']?><?php endif;?> - 个人中心 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/vip.css"/>
</head>
<body>
<?php $this->load->view('common/vip_header')?>
<div class="breadcrumb area">
	<a href="<?=base_url()?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url('vip/home/index')?>">个人中心</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;全部商品
</div>
<div class="vip_all mt10 area">
	<div class="vtop"><?php if(empty($category['name'])):$category['id']=0;?>全部商品<?php else:?><?=$category['name']?><?php endif;?></div>
	<div class="v_nav">
		<ul>
			<?php if(empty($categories)):?>
			<li><span>商品分类：</span><a href="<?=base_url('vip/product/index')?>">返回全部分类</a></li>
			<?php else:?>
			<li>
				<span>商品分类：</span>
				<?php foreach($categories as $v):?>
				<a href="<?=base_url("vip/product/index/{$v['id']}")?>"><?=$v['name']?></a>
				<?php endforeach;?>
			</li>
			<?php endif;?>
		</ul>
	</div>
	<div class="vip_order">
		<span>价格：</span><input type="text" id="price_min" value="<?=$search['min']?>" />至<input type="text" id="price_max" value="<?=$search['max']?>" />元
		<a href="javascript:;" class="btn" onclick="doSearch()">确定</a>
		<a href="<?=base_url("vip/product/index/{$category['id']}?min=0&max=99")?>">0-99</a>
		<a href="<?=base_url("vip/product/index/{$category['id']}?min=100&max=999")?>">100-999</a>
	</div>
	<div class="sp_list mt10 area viplist">
		<table>
			<tbody>
			<tr>
				<th class="thB">商品编号</th>
				<th class="thC">商品名称</th>
				<th class="thD">商品详情</th>
				<th class="thE">价格</th>
				<th class="thF">购买数量</th>
				<th class="thG">操作</th>
			</tr>
			</tbody>
			<?php if(!empty($data)):foreach($data as $v):?>
			<tr>
				<td><?=$v['sku']?></td>
				<td><?=$v['title']?></td>
				<td><div class="text">商品规格：<?=$v['spec']?><br />包装形式：<?=$v['spec_packing']?></div></td>
				<td>￥<?=sprintf('%.2f',round($v['price']*$discount,2))?></td>
				<td><div class="num">
					<em class="tp1" onclick="updCart(<?=$v['id']?>, 'dec')">-</em>
					<input type="text" class="count" value="1" id="buy_number_<?=$v['id']?>" onchange="updCart(<?=$v['id']?>)" />
					<em class="tp2" onclick="updCart(<?=$v['id']?>, 'inc')">+</em>
				</div></td>
				<td>
					<a href="javascript:;" onclick="addCart(<?=$v['id']?>)" class="add">加入购物车</a>
					<a href="<?=base_url("vip/cart/index")?>" class="add red">立即结算</a>
				</td>

			</tr>
		<?php endforeach;endif;?>
		</table>
	</div>
	<div class="mypage"><?=$pager?></div>
</div>
<?php $this->load->view('common/footer')?>
<script type="text/javascript">
//商品搜索
function doSearch(){
	var price_max = parseInt($("#price_max").val());
	var price_min = parseInt($("#price_min").val());
	var category_id = "<?=$category['id']?>";
	var redirect = '<?=base_url("vip/product/index/{$category['id']}")?>'+'?min='+price_min+'&max='+price_max;
	if(price_min < 0){
		layer.tips("提示：最小金额应大于等于0元！", $("#price_min"), {time:2});
		return false;
	}else if(price_max <= price_min){
		layer.tips("提示：最大金额应大于最小金额！", $("#price_max"), {time:2});
		return false;
	}
	window.location.href = redirect;
}
//加入购物车
function addCart(product_id){
	var buy_number = parseInt($("#buy_number_"+product_id).val());
	if(buy_number <= 0){
		layer.msg("购买数量不正确！", 2, 5);
		$("#buy_number_"+product_id).val(1);
		return false;
	}
	layer.load("提交中...");
	$.ajax({
		url: "<?=base_url('vip/cart/manage/add')?>",
		type: "post",
		dataType: "json",
		data: {"product_id":product_id, "amount":buy_number},
		success: function(data){
			if(data.err_no == 0){
				$("#cart_number").html(data.results.total_number);
				layer.msg("添加到购物车成功！", 2, 1);
			}else{
				layer.msg("添加到购物车失败！", 2, 5);
			}
		}
	});
}
//更新单商品
function updCart(product_id, action){
	var n_pattern = /^[1-9][0-9]*$/;
	if( ! n_pattern.test(product_id)){
		return false;
	}
	var buy_number = $("#buy_number_"+product_id).val();
	buy_number = isNaN(parseInt(buy_number)) ? 1 : Math.max(1, parseInt(buy_number));
	//增减
	if(action == "inc"){
		buy_number += 1;
	}else if(action == "dec"){
		if(buy_number <= 1)	return false;
		buy_number -= 1;
		buy_number = Math.max(1, buy_number);
	}
	$("#buy_number_"+product_id).val(buy_number);
}
</script>
</body>
</html>