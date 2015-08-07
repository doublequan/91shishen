<?php
/**
 * 找回密码
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
<title>找回密码 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/register.css"/>
</head>
<body>
<?php $this->load->view('common/login_header')?>
<div class="reg area">
	<div class="middle">
		<h1>找回密码 - <?=$siteName?></h1>
		<dl class="clearfix">
			<dt><em>*</em>登录账号：</dt>
			<dd><input type="text" id="username" value="<?=$username?>" placeholder="请输入您的登录账号" /></dd>
		</dl>
		<dl class="clearfix" >
			<dt><em>*</em>手机号码：</dt>
			<dd>
				<input type="text" id="mobile" value="<?=$mobile?>" placeholder="请输入您绑定的手机号码" />
			</dd>
		</dl>
		<dl class="clearfix" >
			<dt><em>*</em>验证码：</dt>
			<dd>
				<input type="text" id="verify" maxlength="6" placeholder="请输入验证码" />
			</dd>
		</dl>
		<dl class="clearfix" >
			<dt><em>*</em>设置密码：</dt>
			<dd>
				<input type="password" id="password"  maxlength="20"  placeholder="[6-20]位字母数字或特殊符号" />
			</dd>
		</dl>
		<dl class="clearfix" `>
			<dt><em>*</em>确认密码：</dt>
			<dd>
				<input type="password" id="password2"  maxlength="20"  placeholder="请确认输入登录密码" />
			</dd>
		</dl>
		<dl class="clearfix">
			<dt></dt>
			<dd><input type="submit" value="提交" onclick="doSubmit()" class="s" /></dd>
		</dl>
		<div class="login">已有账号？<a href="<?=base_url('member/user/login')?>">直接登录</a></div>
	</div>
</div>
<?php $this->load->view('common/footer')?>
<script src="<?=STATICURL?>js/verify.js"></script>
<script type="text/javascript">
//提交信息
function doSubmit(){
	var username = $.trim($("#username").val());
	var mobile = $.trim($("#mobile").val());
	var verify = $.trim($("#verify").val());
	var password = $.trim($("#password").val());
	var password2 = $.trim($("#password2").val());
	if( ! isUsername(username)){
		layer.tips("提示：登录账号格式错误！", $("#username"), {time:2});
		$("#username").focus();
		return false;
	}
	if( ! isMobile(mobile)){
		layer.tips("提示：邮箱格式错误！", $("#mobile"), {time:2});
		$("#mobile").focus();
		return false;
	}
	if(verify == ""){
		layer.tips("提示：请输入验证码！", $("#verify"), {time:2});
		$("#verify").focus();
		return false;
	}
	if( ! isPassword(password)){
		layer.tips("提示：密码格式错误！", $("#password"), {time:2});
		$("#password").focus();
		return false;
	}
	if(password != password2){
		layer.tips("提示：两次输入的密码不一致！", $("#password2"), {time:2});
		$("#password2").focus();
		return false;
	}
	layer.load("提交中...");
	$.ajax({
		url: "<?=base_url('findpwd/ajax_mobile')?>",
		type: "post",
		dataType: "json",
		data: {"username":username, "mobile":mobile, "verify":verify, "password":password},
		success: function(data){
			if(data.err_no == 0){
				layer.msg("修改密码成功！", 2, 1, function(){ location.href = "<?=base_url('member/user/login')?>"});
			}else if(data.err_no == 1003){
				layer.msg("账号或邮箱错误！", 2, 5);
			}else if(data.err_no == 1004){
				layer.msg("验证码错误或已过期！", 2, 5);
			}else{
				layer.msg("操作失败请重试！", 2, 5);
			}
		}
	});
}
</script>
</body>
</html>