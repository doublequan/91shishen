<?php
/**
 * 订单提交成功页面|支付宝
 * @author LiuPF<mail@phpha.com>
 * @date 2014-10-10
 */
defined('BASEPATH') || exit('Access denied');
?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title>提交订单成功 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/gwc.css"/>
</head>
<body>
<?php $this->load->view('common/login_header')?>
<div class="zt area">
	<a href="<?=base_url('cart/index')?>"><img src="<?=STATICURL?>images/img26.jpg" /></a>
	<div class="right step3" >
		<div class="bg"><div class="w"></div></div>
		<span class="tp1">查看购物车</span>
		<span class="tp2">填写订单</span>
		<span class="tp3">付款，完成购买</span>
	</div>
</div>
<div class="success area">
	<p>
		<?php if($data['order_status']==0):?>您的订单已经提交成功，请尽快进行支付。<?php else:?>您的订单已经支付成功，请等待系统确认。<?php endif;?><br />
		我们预计将在<span><?=$data['date_day']?>&nbsp;<?php if($data['date_noon']==1):?>上午<?php else:?>下午<?php endif;?></span>给您送货上门。
	</p>
	<p>
		<?php if($data['order_status']==0):?><a href="javascript:;" onclick="doPay()" class="btnPay">立即支付</a>&nbsp;<?php endif;?>
		[<a href="<?=base_url("member/order/detail/{$data['id']}")?>">订单明细</a>]&nbsp;
		[<a href="<?=base_url('member/order/index')?>">订单中心</a>]
	</p>
	<form action="<?=base_url('member/payment/alipay')?>" method="post" target="_blank">
		<input type="hidden" name="pay_no" value="<?=$data['id']?>" />
		<input type="hidden" name="money" value="<?=$data['price']?>" />
		<input type="hidden" name="hash_token" value="<?=$data['hash_token']?>" />
	</form>
</div>
<div class="success2 area">
	<p>订单金额：￥<?=$data['price']?>（支付宝）</p>
	<div class="box">
		<p class="t">送货至：<?=$data['receiver']?>&nbsp;<?=$data['prov'].$data['city'].$data['district'].$data['street'].$data['address']?>&nbsp;<?=$data['mobile']?></p>
		<p>订单编号：<?=$data['id']?></p>
		<p>订单金额：￥<?=$data['price']?></p>
		<p>收货时间：<?=$data['date_day']?>&nbsp;<?php if($data['date_noon']==1):?>上午<?php else:?>下午<?php endif;?>
			<?php if($data['date_type']==1):?>（正常收货）<?php elseif($data['date_type']==2):?>（仅工作日）<?php elseif($data['date_type']==3):?>（仅周末）<?php elseif($data['date_type']==4):?>（指定时间）<?php endif;?>
		</p>
		<p>配送方式：<?php if($data['delivery_type']==1):?>惠生活物流<?php else:?>店铺自提<?php endif;?></p>
	</div>
</div>
<?php $this->load->view('common/footer')?>
<script type="text/javascript">
//在线支付
function doPay(){
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
				location.href = '<?=base_url("member/order/detail/{$data['id']}")?>';
			}, no: function(){
				layer.alert('请刷新页面后重新进行支付！', 5, function(){ location.reload();});
			}
		}
	});
}
</script>
</body>
</html>