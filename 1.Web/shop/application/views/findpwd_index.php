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
			<dd><input type="text" id="username" placeholder="请输入您的登录账号" /></dd>
		</dl>
		<dl class="clearfix" id="verify_mobile">
			<dt><em>*</em>手机号码：</dt>
			<dd>
				<input type="text" id="mobile" maxlength="11" class="mob" placeholder="请输入您绑定的手机号码" />
				<br />或 <a href="javascript:;" onclick="switchEmail()">切换邮箱</a>
			</dd>
		</dl>
		<dl class="clearfix" id="verify_email" style="display:none;">
			<dt><em>*</em>邮箱账号：</dt>
			<dd>
				<input type="text" id="email" placeholder="请输入您绑定的邮箱账号" />
				<br />或 <a href="javascript:;" onclick="switchMobile()">切换手机</a>
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
//提交信息
function doSubmit(){
	var username = $.trim($("#username").val());
	var mobile = $.trim($("#mobile").val());
	var email = $.trim($("#email").val());
	if( ! isUsername(username)){
		layer.tips("提示：账号格式错误！", $("#username"), {time:2});
		$("#username").focus();
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
	layer.load("提交中...");
	$.ajax({
		url: "<?=base_url('findpwd/ajax_submit')?>",
		type: "post",
		dataType: "json",
		data: {"username":username, "mobile":mobile, "email":email, "type":verify_type},
		success: function(data){
			if(data.err_no == 0){
				if(verify_type == "mobile"){
					layer.msg("验证短信发送成功，请注意查收！", 2, 1, function(){ location.href = "<?=base_url('findpwd/reset/mobile')?>"+"?username="+username+"&mobile="+mobile});
				}else{
					layer.msg("验证邮件发送成功，请注意查收！", 2, 1, function(){ location.href = "<?=base_url('findpwd/reset/email')?>"+"?username="+username+"&email="+email});
				}
			}else if(data.err_no == 1003){
				if(verify_type == "mobile"){
					layer.msg("登录账号或手机号码错误！", 2, 5);
				}else{
					layer.msg("登录账号或邮箱账号错误！", 2, 5);
				}
			}else if(data.err_no == 1004){
				layer.msg("请"+data.results+"秒后再进行操作！", 2, 5);
			}else{
				layer.msg("操作失败请重试！", 2, 5);
			}
		}
	});
}
</script>
</body>
</html>