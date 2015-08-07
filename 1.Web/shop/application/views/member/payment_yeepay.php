<?php
/**
 * 易宝会员卡支付
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
<title>会员卡支付 - 个人中心 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/details.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/myhome.css"/>
</head>
<body>
<?php $this->load->view('common/header')?>
<div class="breadcrumb area">
	<a href="<?=base_url()?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url('member/home/index')?>">个人中心</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url('member/order/index')?>">我的订单</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;会员卡支付
</div>
<div class="cz_box area" style="margin-top:20px;">
	<div class="cz_t">会员卡支付</div>
	<div class="czform">
		<ul>
			<li><span>　订单编号：</span><b><?=$data['id']?></b></li>
			<li><span>　订单金额：</span><b><?=$data['price']?>&nbsp;元</b></li>
			<li><span>会员卡卡号：</span><input type="text" class="in_text2" id="card_no" maxlength="23" disabled value="<?=$data['card_no']?>" /></li>
			<li><span>会员卡密码：</span><input type="password" class="in_text2" id="card_pw" maxlength="20" /></li>
			<li class="tip">
				<p class="t3">
					<input type="button" onclick="doPay()" class="tpb2" value="确定">
					<input type="button" onclick="goBack()" class="tpb2" value="返回">
				</p>
			</li>
		</ul>
	</div>
	<p class="cztip">
		温馨提示：<br />
		支付成功后，可能存在延迟现象，一般1到5分钟内到账，如有问题，请咨询客服。
	</p>
</div>
<?php $this->load->view('common/footer')?>
<script type="text/javascript">
var hash_token = "<?=$hash_token?>";
var pay_no = "<?=$data['id']?>";
var money = "<?=$data['price']?>";
//返回
function goBack(){
	location.href = '<?=base_url("member/order/detail/{$data['id']}")?>';
}
//在线支付
function doPay(){
	var card_no = $('#card_no').val();
	var card_pw = $('#card_pw').val();
	var regNum = /^\d$/;
	var regPwd = /^[\u4e00-\u9fa5-－_.a-zA-Z0-9]{6,20}$/;
	if(regNum.test(card_no) || card_no.length > 25 || card_no.length < 6){
		layer.alert("会员卡卡号格式不正确！", 5);
		return false;
	}
	if( ! regPwd.test(card_pw)){
		layer.alert("会员卡密码格式不正确！", 5);
		return false;
	}
	layer.load("提交中...");
	$.ajax({
		url: "<?=base_url('member/payment/ajax_yeepay')?>",
		type: "post",
		dataType: "json",
		data: {"card_no":card_no, "card_pw":card_pw, "pay_no":pay_no, "money":money, "hash_token":hash_token},
		success: function(data){
			if(data.err_no == 0){
				layer.msg("支付成功！", 2, 1, function(){ location.href = '<?=base_url("member/order/detail/{$data['id']}")?>';});
			}else if(data.err_no == 1004){
				layer.msg("支付失败："+data.err_msg+"！", 2, 5);
			}else{
				layer.msg("支付失败，请刷新后重试！", 2, 5);
			}
		}
	});
}
$(document).ready(function(){
	formatCardNo("card_no");
});
//卡号验证
var _length = 0;
function formatCardNo(id){
	var id = document.getElementById(id);
	id.value = formatCardNoString(id.value);
}
function formatCardNoString(cardNo){
	var str = (cardNo).replace(/[^\d]/g, "");
	var maxlen = 25;
	if (str.length < maxlen) {
		maxlen = str.length;
	}
	var temp = "";
	for ( var i = 0; i < maxlen; i++) {
		temp = temp + str.substring(i, i + 1);
		if (i != 0 && (i + 1) % 4 == 0 && i != 24) {
			temp = temp + " ";
		}
	}
	return temp;
}
</script>
</body>
</html>