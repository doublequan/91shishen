<?php
/**
 * 修改密码
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-30
 */
defined('BASEPATH') || exit('Access denied');
?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title>修改密码 - 个人中心 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/myhome.css"/>
</head>
<body>
<?php $this->load->view('common/vip_header')?>
<div class="breadcrumb area">
	<a href="<?=base_url()?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url('vip/home/index')?>">个人中心</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url('vip/safety/index')?>">账号安全</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;修改密码
</div>
<div class="Ucenter mt20 area">
	<?php $this->load->view('vip/menu')?>
	<div class="mylist">
		<div class="password">
			<div class="title">修改密码</div>
			<div class="pass">
				<ul>
					<li><label>旧密码：</label><input type="password" id="old_pass" /><span>[6-20]位字母数字或特殊符号</span></li>
					<li><label>新密码：</label><input type="password" id="password" /><span>[6-20]位字母数字或特殊符号</span></li>
					<li><label>确认密码：</label><input type="password" id="password2" /><span>请重复输入新密码</span></li>
					<li><input type="button" value="确定" class="tpb" onclick="updPass()" /><input type="button" value="返回" class="tpb2" onclick="goBack()" /></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('common/footer')?>
<script src="<?=STATICURL?>js/verify.js"></script>
<script type="text/javascript">
//返回
function goBack(){
	location.href = "<?=base_url('vip/safety/index')?>";
}
//修改密码
function updPass(){
	var old_pass = $.trim($("#old_pass").val());
	var password = $.trim($("#password").val());
	var password2 = $.trim($("#password2").val());
	if( ! isPassword(old_pass)){
		layer.tips("提示：旧密码格式错误！", $("#old_pass"), {time:2});
		$("#old_pass").focus();
		return false;
	}
	if( ! isPassword(password)){
		layer.tips("提示：新密码格式错误！", $("#password"), {time:2});
		$("#password").focus();
		return false;
	}
	if(password2 != password){
		layer.tips("提示：两次输入的密码不一致！", $("#password2"), {time:2});
		$("#password2").focus();
		return false;
	}
	layer.load("提交中...");
	$.ajax({
		url: "<?=base_url('vip/safety/ajax_passwd')?>",
		type: "post",
		dataType: "json",
		data: {"old_pass":old_pass,"password":password},
		success: function(data){
			if(data.err_no == 0){
				layer.msg("修改密码成功！", 2, 1, function(){ goBack();});
			}else if(data.err_no == 1003){
				layer.msg("旧密码错误！", 2, 5);
			}else{
				layer.msg("操作失败请重试！", 2, 5);
			}
		}
	});
}
</script>
</body>
</html>