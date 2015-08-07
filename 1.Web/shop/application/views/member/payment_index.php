<?php
/**
 * 充值中心
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
<title>充值中心 - 个人中心 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/myhome.css"/>
</head>
<body>
<?php $this->load->view('common/login_header')?>
<div class="cz_zt area">
	<img src="<?=STATICURL?>images/img33.jpg" />
	<div class="right step1" >
		<div class="bg"><div class="w"></div></div>
		<span class="tp1">填写充值金额</span>
		<span class="tp2">在线支付</span>
		<span class="tp3">充值完成</span>
	</div>
</div>
<div class="cz_box area">
	<div class="cz_t">填写充值金额</div>
	<div class="czform">
		<ul>
			<li><span>　充值金额：</span><input type="text" class="in_text2" id="money" /></li>
			<li><span>会员卡卡号：</span><input type="text" class="in_text2" id="card_no" maxlength="23" value="<?=$card_no?>" onkeyup="formatCardNo('card_no')" onchange="formatCardNo('card_no')" /></li>
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
	location.href = "<?=base_url('member/logs/charge')?>";
}
//在线支付
function doPay(){
	var money = parseFloat($("#money").val());
	var card_no = $("#card_no").val();
	var card_pw = $("#card_pw").val();
	var hash_token = "<?=$hash_token?>";
	var regNum = /^\d$/;
	var regPwd = /^[\u4e00-\u9fa5-－_.a-zA-Z0-9]{6,20}$/;
	if(isNaN(money) || money < 10 || money > 50000){
		layer.alert("充值金额不正确！", 5);
		return false;
	}
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
		url: "<?=base_url('member/payment/ajax_submit')?>",
		type: "post",
		dataType: "json",
		data: {"card_no":card_no, "card_pw":card_pw, "money":money, "hash_token":hash_token},
		success: function(data){
			if(data.err_no == 0){
				layer.msg("支付成功！", 2, 1, function(){ location.href = "<?=base_url('member/order/detail')?>"+'/'+data.results.id;});
			}else{
				layer.msg("支付失败，请刷新后重试！", 2, 5);
			}
		}
	});
}
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