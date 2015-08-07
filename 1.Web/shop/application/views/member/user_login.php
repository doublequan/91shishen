<?php
/**
 * 登录
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
<title>登录 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/register.css"/>
</head>
<body>
<?php $this->load->view('common/login_header')?>
<div class="userlogin area">
	<div class="left"><img src="<?=STATICURL?>images/img32.jpg" /></div>
	<div class="right">
		<h1>登录惠生活</h1>
		<dl class="clearfix">
			<dt class="imob"></dt>
			<dd><input type="text" id="username" placeholder="请输入登录账号/手机/邮箱" /></dd>
		</dl>
		<dl class="clearfix">
			<dt class="ipas"></dt>
			<dd>
				<input type="password" id="password" placeholder="请输入登录密码" />
			</dd>
		</dl>
		<label style="height:30px;line-height:30px;margin-top:10px;float:left;"><input name="rememberPwd" type="checkbox" value="1" />&nbsp;记住密码</label>
		<input type="submit" class="isub" onclick="doLogin()" value="登录" />
		<div class="tip">
			<span>忘记登录密码？<a href="<?=base_url('findpwd')?>">找回密码</a></span>
			还没有惠生活账号？<a href="<?=base_url('member/user/register')?>">点击注册</a>
		</div>
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
	var rememberPwd = $("input[name='rememberPwd']").prop("checked");
	var username = $.trim($("#username").val());
	var password = $.trim($("#password").val());
	if( ! (isUsername(username) || isMobile(username) || isEmail(username)) ){
		layer.tips("提示：账号格式错误！", $("#username"), {time:2});
		$("#username").focus();
		return false;
	}else if( ! isPassword(password)){
		layer.tips("提示：密码格式错误！", $("#password"), {time:2});
		$("#password").focus();
		return false;
	}
	var rempwd = rememberPwd == true ? '1' : '0';
	layer.load("提交中...");
	$.ajax({
		url: "<?=base_url('member/user/ajax_login')?>",
		type: "post",
		dataType: "json",
		data: {"username":username, "password":password, "rempwd":rempwd},
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