<?php
/**
 * 充值完成
 * @author LiuPF<mail@phpha.com>
 * @date 2014-10-5
 */
defined('BASEPATH') || exit('Access denied');
?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title><?php if($data['status']==1):?>充值完成<?php else:?>充值未完成<?php endif;?> - 个人中心 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/myhome.css"/>
</head>
<body>
<?php $this->load->view('common/login_header')?>
<div class="cz_zt area">
	<img src="<?=STATICURL?>images/img33.jpg" />
	<div class="right <?php if($data['status']==1):?>step3<?php else:?>step2<?php endif;?>" >
		<div class="bg"><div class="w"></div></div>
		<span class="tp1">填写充值金额</span>
		<span class="tp2">在线支付</span>
		<span class="tp3">充值完成</span>
	</div>
</div>
<div class="cz_box area">
	<div class="cz_t"><?php if($data['status']==1):?>充值完成<?php else:?>充值未完成<?php endif;?></div>
	<div class="czform">
		<ul>
			<li><span>充值账户：</span><b><?=$userInfo['username']?></b></li>
			<li><span>充值金额：</span><b><?=$data['money']?></b>&nbsp;元</li>
			<li><span>充值方式：</span><?php if($data['pay_type']==1):?>易宝支付<?php elseif($data['pay_type']==2):?>支付宝<?php endif;?></li>
			<li><span>充值状态：</span><?php if($data['status']==0):?>未完成<?php elseif($data['status']==1):?>充值成功<?php else:?>充值失败<?php endif;?></li>
			<li><span>创建时间：</span><?=date('Y-m-d H:i', $data['create_time'])?></li>
			<li><span>完成时间：</span><?php if($data['success_time']>0):?><?=date('Y-m-d H:i', $data['success_time'])?><?php else:?>-<?php endif;?></li>
			<li class="tip">
				<p class="t3">
					<?php if($data['status']==0):?><input type="button" onclick="doPayment()" class="tpb2" value="重新支付"><?php endif;?>
					<input type="button" onclick="goBack()" class="tpb2" value="返回">
				</p>
			</li>
		</ul>
	</div>
	<p class="cztip">
		 温馨提示：<br />
		 1. 充值成功后，余额可能存在延迟现象，一般1到5分钟内到账，如有问题，请咨询客服；<br />
		 2. 充值金额输入值必须是不小于10且不大于50000的正整数；<br />
		 3. 您只能用储蓄卡进行充值，如遇到任何支付问题可以查看在线支付帮助；<br />
		 4. 充值完成后，您可以进入账户充值记录页面进行查看余额充值状态。
	</p>
</div>
<?php $this->load->view('common/footer')?>
<script type="text/javascript">
//返回
function goBack(){
	location.href = "<?=base_url('member/logs/payment')?>";
}
//重新支付
function doPayment(){
	location.href = '<?=base_url("member/payment/check/{$data['id']}")?>';
}
</script>
</body>
</html>