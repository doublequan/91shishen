<?php
require_once('../config.php'); 
	 $site_url = $site['url'];
$script = array('login');
$page['title'] = '登录';
$backurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
include '../layout/header.php';
?>

<div class="layout page-content">
	<div class="login-logo text-center">
		<a href="javascript:;"><img src="../assets/images/logo.png" alt="<?=$page['title']?>"></a>
	</div>


	<div class="login-box">
		<div class="input-group">
			<label>用户名:</label>
			<input type="text" id="username" placeholder="用户名/手机号/邮箱">
		</div>

		<div class="input-group">
			<label>密　码:</label>
			<input type="password" id="password" placeholder="密码">
		</div>
	</div>
	<br class="clearfix"/>
	<div class="login-btn text-center">
		<input type="hidden" id="form-path" value="<?=$backurl ?>">
		<a href="javascript:;" id="btn-login" class="btn btn-red"><i class="icon-forward"></i>登录</a>
	</div>
	<div class="register-btn text-center">
		
		<p>没有账号？<a href="<?=$site_url.'/page/register.php?backurl='.$backurl?>"><i class="icon-link"></i>点击注册</a></p>
	</div>
</div>

<?php include '../layout/footer.php';?>