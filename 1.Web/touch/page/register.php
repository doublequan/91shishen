<?php
require_once('../config.php'); 
	 $site_url = $site['url'];
$script = array('register');
$page['title'] = '注册';
include '../layout/header.php'; 
?>

<div class="layout page-content">
	
	<div class="login-box">
		<!-- 
		<div class="input-group">
			<label>用户名:</label>
			<input type="text" id="username" placeholder="用户名,6-20位字母或者数字组成"  maxlength="20" autocomplete="off" autofocus="autofocus" required="required" >
		</div> 
		-->
		<div class="input-group send-verify">
			<label>手机号:</label>
			<input type="number" maxlength="11" id="mobile" placeholder="手机号码" required="required" autofocus="autofocus">
			<!--<a href="javascript:;" id="btn-verify" disabled="disabled" class="btn btn-red"><i class="icon-send"></i><laber id="verify-text">验证码</laber></a>
			-->
		</div>
		<!--
		<div class="input-group">
			<label>验证码:</label>
			<input type="number" maxlength="6" id="mobile_verify" placeholder="验证码" required="required" >
		</div>
		-->
		<!--
		<div class="input-group">
			<label>邮　箱:</label>
			<input type="email" id="email" placeholder="电子邮箱" required="required" >
		</div>
		-->
		<!--
		<div class="input-group">
			<label>会员卡:</label>
			<input type="number" maxlength="16" id="card" placeholder="可不填" autocomplete="off">
		</div>
		-->
		<div class="input-group">
			<label>密　码:</label>
			<input type="password" id="password" placeholder="密码,长度至少6位" required="required" >
		</div>
		<div class="input-group">
			<label>重　复:</label>
			<input type="password" id="repeat_password" placeholder="重复密码" required="required">
		</div>
        <div class="input-group">
			<label>邀请码:</label>
			<input type="text" id="inviter" placeholder="邀请码">
		</div>
	</div>
	<br class="clearfix"/>
	<div class="login-btn text-center">
		<a href="javascript:;" id="btn-register" class="btn btn-white"><i class="icon-forward"></i>注册</a>
	</div>
	<div class="register-btn text-center">
		
		<p>已有账号？<a href="<?=$site_url.'/page/login.php'?>"><i class="icon-link"></i>前往登录</a></p>
	</div>
</div>
<?php include '../layout/footer.php';?>