<?php
/**
 * 订单详情
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
<title>订单详情 - 个人中心 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/details.css"/>
</head>
<body>
<?php $this->load->view('common/vip_header')?>
<div class="breadcrumb area">
	<a href="<?=base_url()?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url('vip/home/index')?>">个人中心</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url('vip/order/index')?>">我的订单</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;订单详情
</div>
<div class="details mt10 area">
	<div class="tipbox">
		<div class="t1">
			<span>
				<a href="javascript:window.print();">打印订单</a>
			<?php if($data['order_status']==1):?>
				<a href="javascript:;" class="gray" onclick="cancelOrder('<?=$data['id']?>')">取消订单</a>
			<?php endif;?>
			</span>
			订单号：<?=$data['id']?><em>状态：</em><?=$data['status_detail']?>
		</div>
	</div>
</div>
<div class="de_stbd mt20 area">
	<div class="box">
		<div class="t">订单信息</div>
		<div class="add">
			<h2>收货人信息</h2>
			<p>收&nbsp;货&nbsp;人&nbsp;：<?=$data['receiver']?></p>
			<p>地　　址：<?=$data['prov']?><?=$data['city']?><?=$data['district']?><?=$data['address']?></p>
			<p>手机号码：<?=$data['mobile']?></p>
		</div>
		<div class="add">
			<h2>配送方式</h2>
			<p>物　　流：惠生活物流</p>
		</div>
	</div>
	<div class="table">
		<table>
			<tr><th class="thC">商品编号</th><th class="thA">商品名称</th><th class="thC">单价</th><th class="thD">数量</th><th class="thE">小计</th></tr>
			<?php if(!empty($detail)):foreach($detail as $v):?>
			<tr class="bd">
				<td><?=$v['sku']?></td>
				<td>
					<div class="pt clearfix">
						<?=$v['product_name']?>
					</div>
				</td>
				<td>￥<?=$v['price_single']?></td>
				<td><?=$v['amount']?></td>
				<td>￥<?=$v['price_total']?></td>
			</tr>
			<?php endforeach;endif;?>
		</table>
		<div class="total2 clearfix">
			商品总价：+￥<?=$data['price']?><br>
			<div class="total"><span>应付总额：￥<?=$data['price']?></span></div>
		</div>
	</div>
</div>
<?php $this->load->view('common/footer')?>
<script type="text/javascript">
//取消订单
function cancelOrder(order_id){
	layer.confirm("确定取消？", function(){
		layer.load("提交中...");
		$.ajax({
			url: "<?=base_url('vip/order/ajax_cancel')?>",
			type: "post",
			dataType: "json",
			data: {"order_id":order_id},
			success: function(data){
				if(data.err_no == 0){
					layer.msg("取消订单成功！", 2, 1, function(){ location.reload();});
				}else{
					layer.msg("取消订单失败！", 2, 5);
				}
			}
		});
	});
}
</script>
</body>
</html>