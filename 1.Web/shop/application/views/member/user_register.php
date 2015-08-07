<?php
/**
 * 注册
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-21
 */
defined('BASEPATH') || exit('Access denied');
?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title>注册 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/register.css"/>
</head>
<body>
<?php $this->load->view('common/login_header')?>
<div class="reg area">
	<div class="box">
		<h1>欢迎注册</h1>
		<dl class="clearfix">
			<dt><em>*</em>登录账号：</dt>
			<dd><input type="text" id="username" placeholder="[6-20]位小写字母或数字且以字母开头" /></dd>
		</dl>
		<dl class="clearfix">
			<dt><em>*</em>设置密码：</dt>
			<dd><input type="password" id="password" class="pw" placeholder="[6-20]位字母数字或特殊符号" /></dd>
		</dl>
		<dl class="clearfix">
			<dt><em>*</em>确认密码：</dt>
			<dd><input type="password" id="password2" class="pw" placeholder="请再次输入您要设置的密码" /></dd>
		</dl>
		<dl class="clearfix" id="verify_mobile">
			<dt><em>*</em>手机号码：</dt>
			<dd>
				<input type="text" id="mobile" maxlength="11" class="mob" placeholder="请输入您要绑定的手机号码" />
				<br />或 <a href="javascript:;" onclick="switchEmail()">绑定邮箱</a>
			</dd>
		</dl>
		<dl class="clearfix" id="verify_email" style="display:none;">
			<dt><em>*</em>邮箱账号：</dt>
			<dd>
				<input type="text" id="email" placeholder="请输入您要绑定的邮箱账号" />
				<br />或 <a href="javascript:;" onclick="switchMobile()">绑定手机</a>
			</dd>
		</dl>
		<dl class="clearfix">
			<dt>会员卡号：</dt>
			<dd>
				<input type="text" id="cardno" placeholder="请输入您的会员卡号" /><br />
				<label><input id="agreement" type="checkbox" checked class="checkbox" />我已阅读并同意<a href="http://100hl.com/archive/14.html" target="_blank">《惠生活注册协议》</a></label>
			</dd>
		</dl>
		<dl class="clearfix">
			<dt></dt>
			<dd><input type="submit" value="注册" onclick="doRegister()" class="s" /></dd>
		</dl>
		<div class="login">已有账号？<a href="<?=base_url('member/user/login')?>">直接登录</a></div>
	</div>
	<div class="mobtext">
		<div class="text">
			<h2>手机快速注册</h2>
			<p>敬请期待</p>
		</div>
	</div>
</div>
<?php $this->load->view('common/footer')?>
<script src="<?=STATICURL?>js/verify.js"></script>
<script type="text/javascript">
var verify_type = "mobile";
//绑定手机
function switchMobile(){
	verify_type = "mobile";
	$("#verify_email").hide();
	$("#verify_mobile").show();
}
//绑定邮箱
function switchEmail(){
	verify_type = "email";
	$("#verify_mobile").hide();
	$("#verify_email").show();
}
//注册
function doRegister(){
	var username = $.trim($("#username").val());
	var password = $.trim($("#password").val());
	var password2 = $.trim($("#password2").val());
	var mobile = $.trim($("#mobile").val());
	var email = $.trim($("#email").val());
	var cardno = $.trim($("#cardno").val());
	var agreement = $("#agreement").prop("checked");
	if( ! isUsername(username)){
		layer.tips("提示：账号格式错误！", $("#username"), {time:2});
		$("#username").focus();
		return false;
	}
	if( ! isPassword(password)){
		layer.tips("提示：密码格式错误！", $("#password"), {time:2});
		$("#password").focus();
		return false;
	}
	if(password2 != password){
		layer.tips("提示：两次输入的密码不一致！", $("#password2"), {time:2});
		$("#password2").focus();
		return false;
	}
	if(verify_type == "mobile" && ! isMobile(mobile)){
		layer.tips("提示：手机号格式错误！", $("#mobile"), {time:2});
		$("#mobile").focus();
		return false;
	}
	if(verify_type == "email" && ! isEmail(email)){
		layer.tips("提示：邮箱格式错误！", $("#email"), {time:2});
		$("#email").focus();
		return false;
	}
	if(cardno != "" && ! isCard(cardno)){
		layer.tips("提示：会员卡号格式错误！", $("#cardno"), {time:2});
		$("#cardno").focus();
		return false;
	}
	if(agreement == false){
		layer.tips("提示：请阅读并同意注册协议！", $("#agreement"), {time:2});
		return false;
	}
	layer.load("提交中...");
	$.ajax({
		url: "<?=base_url('member/user/ajax_reg')?>",
		type: "post",
		dataType: "json",
		data: {"username":username, "password":password, "mobile":mobile, "email":email, "cardno":cardno},
		success: function(data){
			if(data.err_no == 0){
				layer.msg("注册成功！", 2, 1, function(){ location.href="<?=base_url('member/home/index')?>";});
			}else if(data.err_no == 1004){
				layer.msg(data.results, 2, 5);
			}else{
				layer.msg("注册失败请重试！", 2, 5);
			}
		}
	});
}
</script>
</body>
</html>