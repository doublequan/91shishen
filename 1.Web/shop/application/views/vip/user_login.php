<?php
/**
 * 用户登录
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-25
 */
defined('BASEPATH') || exit('Access denied');
?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title>登录 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/register.css"/>
</head>
<body>
<?php $this->load->view('common/vip_login_header')?>
<div class="viplogin area">
	<div class="left">
		<h1>VIP大客户登录</h1>
		<dl class="clearfix">
			<dt class="imob"></dt>
			<dd><input type="text" id="username" placeholder="请输入登录账号"/></dd>
		</dl>
		<dl class="clearfix">
			<dt class="ipas"></dt>
			<dd><input type="password" id="password" placeholder="请输入登录密码"/></dd>
		</dl>
		<!--<div class="autologin">
			<label><input type="checkbox"/>记住登录名</label>
		</div>-->
		<input type="submit" class="isub" onclick="doLogin()" value="登录"/>
		<!--<div class="tip">
			<span>第三方账户登录：&nbsp;<img src="<?=STATICURL?>images/12.gif" />&nbsp;&nbsp;<img src="<?=STATICURL?>images/13.gif" /></span>
		</div>-->
	</div>
	<div class="right">
		<h2>您还不是惠生活生鲜商城大客户用户</h2>
		<p>若想成为惠生活生鲜商城大客户用户<br />请联系&nbsp;400-025-9089</p>
	</div>
</div>
<?php $this->load->view('common/footer')?>
<script src="<?=STATICURL?>js/verify.js"></script>
<script type="text/javascript">
//回车键登录
function keydown(e){
	var currKey = 0, e = e || event;
	if(e.keyCode == 13){
		doLogin();
	}
}
document.onkeydown = keydown;
//登录
function doLogin(){
	var username = $.trim($("#username").val());
	var password = $.trim($("#password").val());
	var pattern = /^[a-z][a-z0-9]{2,19}$/;
	if( !pattern.test(username) ){
		layer.tips("提示：账号格式错误！", $("#username"), {time:2});
		$("#username").focus();
		return false;
	}else if( ! isPassword(password)){
		layer.tips("提示：密码格式错误！", $("#password"), {time:2});
		$("#password").focus();
		return false;
	}
	layer.load("提交中...");
	$.ajax({
		url: "<?=base_url('vip/user/ajax_login')?>",
		type: "post",
		dataType: "json",
		data: {"username":username, "password":password},
		success: function(data){
			if(data.err_no == 0){
				layer.msg("登录成功！", 2, 1, function(){ location.href="<?=$referer?>";});
			}else if(data.err_no == 1003){
				layer.msg("用户名或密码错误！", 2, 5);
			}else if(data.err_no == 1010){
				layer.msg("该账号已被禁用！", 2, 5);
			}else{
				layer.msg("登录失败请重试！", 2, 5);
			}
		}
	});
}
</script>
</body>
</html>