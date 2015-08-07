<?php
/**
 * 订单提交成功页面
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
<title>提交订单成功 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/gwc.css"/>
</head>
<body>
<?php $this->load->view('common/vip_login_header')?>
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
	<p>您的订单已经提交成功，请等待系统确认。<br />我们预计最早将在<span><?=date('m月d日 H:i',$data['arrive_time'])?></span>前给您送货上门。</p>
	<p>
		[<a href="<?=base_url("vip/order/detail/{$data['id']}")?>">订单明细</a>]&nbsp;
		[<a href="<?=base_url('vip/order/index')?>">订单中心</a>]</p>
</div>
<div class="success2 area">
	<p>订单金额：￥<?=$data['price']?></p>
	<div class="box">
		<p class="t">送货至：<?=$data['receiver']?>&nbsp;<?=$data['prov'].$data['city'].$data['district'].$data['address']?>&nbsp;<?=$data['mobile']?></p>
		<p>订单编号：<?=$data['id']?></p>
		<p>订单金额：￥<?=$data['price']?></p>
		<p>预计送达时间：<?=date('Y-m-d H:i',$data['arrive_time'])?></p>
		<p>配送方式：惠生活物流</p>
	</div>
</div>
<?php $this->load->view('common/footer')?>
</body>
</html>