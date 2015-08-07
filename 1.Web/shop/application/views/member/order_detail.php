<?php
/**
 * 订单详情
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-4
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
<?php $this->load->view('common/header')?>
<div class="breadcrumb area">
	<a href="<?=base_url()?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url('member/home/index')?>">个人中心</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url('member/order/index')?>">我的订单</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;订单详情
</div>
<div class="details mt10 area">
	<div class="tipbox">
		<form action="<?=base_url('member/payment/alipay')?>" method="post" target="_blank">
			<input type="hidden" name="pay_no" value="<?=$data['id']?>" />
			<input type="hidden" name="money" value="<?=$data['price']?>" />
			<input type="hidden" name="hash_token" value="<?=$data['hash_token']?>" />
		</form>
		<div class="t1">
			订单号：<?=$data['id']?><em>状态：</em><?=$data['status_detail']?>
			<span>
				<a href="javascript:window.print();">打印订单</a>
			<?php if($data['order_status']==0 && $data['pay_status']==0):?>
				<a href="javascript:;" onclick="doPay(<?=$data['pay_type']?>)">立即支付</a>
			<?php endif;?>
			<?php if($data['order_status'] == 0 || $data['order_status'] == 2 || ($data['order_status'] == 1 && ($data['pay_type'] == 1 || bccomp($data['price'], '0.00', 2) == 0))):?>
				<a href="javascript:;" class="gray" onclick="cancelOrder('<?=$data['id']?>')">取消订单</a>
			<?php endif;?>
			<?php if($data['order_status']==27):?>
				<a href="javascript:;" onclick="doConfirm('<?=$data['id']?>')">确认收货</a>
			<?php endif;?>
			</span>
		</div>
		<div class="t2">
		<?php $step='step1';if($data['order_status']==0):?>
			尊敬的客户：您的订单已经提交成功，请尽快进行支付。
		<?php elseif($data['order_status']==1):$step='step2';?>
			尊敬的客户：您的订单已经支付成功，请等待系统确认。
		<?php elseif($data['order_status']==20):$step='step5';?>
			尊敬的客户：您的订单已经完成。
		<?php elseif($data['order_status']==21):$step='step2';?>
			尊敬的客户：您的订单已经确认，我们将尽快为您发货。
		<?php elseif($data['order_status']>=22&&$data['order_status']<=25):$step='step3';?>
			尊敬的客户：您的订单已经出库，请等待配送。
		<?php elseif($data['order_status']>=26):$step='step4';?>
			尊敬的客户：您的订单已经开始配送，请做好收货准备。
		<?php endif;?>
		</div>
	</div>
	<?php if($data['order_status']<2||$data['order_status']>12):?>
	<div class="de_st area">
		<div class="step <?=$step?>">
			<div class="bg"><div class="w"></div></div>
			<span class="tp1"><em>提交订单</em><i><?=date('Y-m-d H:i',$data['create_time'])?></i></span>
		<?php if($data['pay_type']==1):?>
			<span class="tp2"><em>货到付款</em><i></i></span>
		<?php else:?>	
			<span class="tp2"><em>付款成功</em><i><?php if(!empty($data['pay_time'])):?><?=date('Y-m-d H:i',$data['pay_time'])?><?php endif;?></i></span>
		<?php endif;?>
			<span class="tp3"><em>商品出库</em><i><?php if(!empty($data['out_time'])):?><?=date('Y-m-d H:i',$data['out_time'])?><?php endif;?></i></span>
			<span class="tp4"><em>等待收货</em><i><?php if(!empty($data['start_time'])):?><?=date('Y-m-d H:i',$data['start_time'])?><?php endif;?></i></span>
			<span class="tp5"><em>完成</em><i><?php if(!empty($data['end_time'])):?><?=date('Y-m-d H:i',$data['end_time'])?><?php endif;?></i></span>
		</div>
	</div>
	<?php endif;?>
</div>
<?php if(!empty($express)):?>
<div class="de_stbd mt20 area">
	<div class="box">
		<div class="t">物流信息</div>
		<ul class="wl">
		<?php foreach($express as $v):?>
			<li><span><?=date('Y-m-d H:i',$v['create_time'])?></span><?=$v['des']?></li>
		<?php endforeach;?>
		</ul>
	</div>
</div>
<?php endif;?>
<div class="de_stbd mt20 area">
	<div class="box">
		<div class="t">订单信息</div>
		<div class="add">
			<h2>收货人信息</h2>
			<p>收 货 人：<?=$data['receiver']?></p>
			<p>手机号码：<?=$data['mobile']?></p>
			<p>
				预计送货时间：<?=$data['date_day']?>&nbsp;<?php if($data['date_noon']==1):?>上午<?php else:?>下午<?php endif;?>
				<?php if($data['date_type']==1):?>（正常收货）<?php elseif($data['date_type']==2):?>（仅工作日）<?php elseif($data['date_type']==3):?>（仅周末）<?php elseif($data['date_type']==4):?>（指定时间）<?php endif;?>
			</p>
			<p>收货地址：<?=$data['prov']?><?=$data['city']?><?=$data['district']?><?=$data['street']?><?=$data['address']?></p>
		</div>
		<div class="add">
			<h2>支付及配送方式</h2>
			<p>支付方式：<?=$data['paytype_detail']?></p>
			<p>运　　费：￥<?=$data['price_shipping']?></p>
			<p>配送方式：<?php if($data['delivery_type']==1):?>惠生活物流<?php else:?>店铺自提<?php endif;?></p>
		<?php if(!empty($store)):?>
			<p>店铺信息：<?=$store['name']?>（地址：<?=$store['prov_name']?><?=$store['city_name']?><?=$store['district_name']?><?=$store['address']?>&nbsp;&nbsp;联系人：<?=$store['manager']?>&nbsp;&nbsp;联系电话：<?=$store['tel']?>&nbsp;&nbsp;营业时间：<?=$store['open_time']?>）</p>
		<?php endif;?>
		</div>
		<div class="add">
			<h2>发票及订单备注</h2>
			<p>索取发票：<?php if($data['is_receipt']==1):?>是<?php else:?>否<?php endif;?></p>
		<?php if($data['is_receipt']==1):?>
			<p>发票抬头：<?=htmlspecialchars($data['receipt_title'])?></p>
			<p>发票内容：<?=htmlspecialchars($data['receipt_des'])?></p>
		<?php endif;?>
			<p>订单备注：<?=htmlspecialchars($data['note'])?></p>
		</div>
	</div>
	<div class="table">
	<a name="comments">
		<table>
			<tr><th class="thC">商品编号</th><th class="thA">商品名称</th><th class="thC">单价</th><th class="thD">数量</th><th class="thE">小计</th><th class="thF">操作</th></tr>
			<?php if(!empty($detail)):foreach($detail as $v):?>
			<tr class="bd">
				<td><?=$v['sku']?></td>
				<td>
					<div class="pt clearfix">
						<a href="<?=base_url("goods/detail/{$v['product_id']}")?>" target="_blank"><img src="<?=$v['thumb']?>" width="60" height="60" /></a>
						<br /><a href="<?=base_url("goods/detail/{$v['product_id']}")?>" target="_blank"><?=$v['product_name']?></a>
					</div>
				</td>
				<td>￥<?=$v['price_single']?></td>
				<td><?=$v['amount']?></td>
				<td>￥<?=$v['price_total']?></td>
				<td id="comment_<?=$v['product_id']?>">
				<?php if($data['order_status']==20):?>
					<?php if($v['is_comment']==1):?>
					已评价
					<?php else:?>
					<a class="btn" href="javascript:;" onclick="addComment('<?=$v['product_id']?>','<?=$data['id']?>')">添加评价</a>
					<?php endif;?>
				<?php else:?>
					-
				<?php endif;?>
				</td>
			</tr>
			<?php endforeach;endif;?>
		</table>
		<div class="total2 clearfix">
			商品总价：+￥<?=$data['price_total']?>
			<br />运费：+￥<?=$data['price_shipping']?>
			<br />折扣金额：-￥<?=$data['price_discount']?>
			<br />使用代金券：-￥<?=$data['price_cash']?>
			<br />免减金额：-￥<?php echo $data['price_minus']; ?>
			<div class="total"><span>应付总额：￥<?=$data['price']?></span></div>
		</div>
	</a>
	</div>
</div>
<div id="product_comment" class="product_comment">
	<span>评分：<input type="radio" name="score" value="1" />1分&nbsp;<input type="radio" name="score" value="2" />2分&nbsp;<input type="radio" name="score" value="3" />3分&nbsp;<input type="radio" name="score" value="4" />4分&nbsp;<input type="radio" name="score" value="5" checked />5分</span>
	<span class="comment">评论：<textarea id="comment"></textarea></span>
	<span><input type="button" class="tpb" value="提交" onclick="submitComment()" /><input type="button" class="tpb2" value="取消" onclick="cancelComment()" /></span>
</div>
<?php $this->load->view('common/footer')?>
<script type="text/javascript">
//评论框对象
var comment_obj = null;
//添加评论
function addComment(product_id, order_id){
	$("input.tpb").attr("onclick", "submitComment('"+product_id+"','"+order_id+"')");
	comment_obj = $.layer({
		type: 1,
		area: ['auto', 'auto'],
		closeBtn: [0, false],
		title: false,
		border: [0],
		page: {dom : '#product_comment'}
	});
}
//提交评论
function submitComment(product_id, order_id){
	var comment = $.trim($("#comment").val());
	var score = $("input[name='score']:checked").val();
	if(comment == ""){
		layer.alert("请填写评论内容！", 5);
		return false;
	}
	layer.load("提交中...");
	$.ajax({
		url: "<?=base_url('member/order/ajax_comment')?>",
		type: "post",
		dataType: "json",
		data: {"product_id":product_id, "order_id":order_id, "comment":comment, "score":score},
		success: function(data){
			if(data.err_no == 0){
				cancelComment();
				$("#comment_"+product_id).html("已评价");
				layer.msg("添加商品评价成功！", 2, 1);
			}else if(data.err_no == 1002){
				layer.msg("提交信息异常，请刷新后重试！", 2, 5);
			}else if(data.err_no == 1003){
				layer.msg("已评价或订单异常，请刷新后重试！", 2, 5);
			}else{
				layer.msg("操作失败请重试！", 2, 5);
			}
		}
	});
}
//取消评论
function cancelComment(){
	layer.close(comment_obj);
	$("#comment").val("");
	$("input[name='score'][value='5']").prop("checked", true);
}
//取消订单
function cancelOrder(order_id){
	layer.confirm("确定取消？", function(){
		layer.load("提交中...");
		$.ajax({
			url: "<?=base_url('member/order/ajax_cancel')?>",
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
//确认收货
function doConfirm(order_id){
	layer.confirm("确认收货？", function(){
		layer.load("提交中...");
		$.ajax({
			url: "<?=base_url('member/order/ajax_confirm')?>",
			type: "post",
			dataType: "json",
			data: {"order_id":order_id},
			success: function(data){
				if(data.err_no == 0){
					layer.msg("确认收货成功！", 2, 1, function(){ location.reload();});
				}else{
					layer.msg("操作失败请重试！", 2, 5);
				}
			}
		});
	});
}
//在线支付
function doPay(pay_type){
	if(pay_type == 3){
		//会员卡
		location.href = "<?=base_url("member/payment/yeepay/{$data['id']}")?>";
	}else if(pay_type == 2){
		//支付宝
		$("form").submit();
		$.layer({
			shade: [0.3, '#000'],
			area: ['auto','auto'],
			dialog: {
				msg: '请在新打开的窗口进行支付，支付完成前<br />请不要关闭该窗口。是否已经支付成功？',
				type: 4,
				btns: 2,
				btn: ['支付成功', '支付失败'],
				yes: function(){
					location.reload();
				}, no: function(){
					layer.alert('请刷新页面后重新进行支付！', 5, function(){ location.reload();});
				}
			}
		});
	}
}
</script>
</body>
</html>