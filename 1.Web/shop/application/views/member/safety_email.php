<?php
/**
 * 修改邮箱
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-17
 */
defined('BASEPATH') || exit('Access denied');
?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title>修改邮箱 - 个人中心 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/myhome.css"/>
</head>
<body>
<?php $this->load->view('common/header')?>
<div class="breadcrumb area">
	<a href="<?=base_url()?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url('member/home/index')?>">个人中心</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url('member/safety/index')?>">账号安全</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;修改邮箱
</div>
<div class="Ucenter mt20 area">
	<?php $this->load->view('member/menu')?>
	<div class="mylist">
		<div class="password">
			<div class="title">修改邮箱</div>
			<div class="pass">
				<ul>
					<li><label>邮箱：</label><input type="text" id="email" value="<?=$data['email']?>" /><span>请输入要验证的邮箱账号</span></li>
					<li><input type="button" value="确定" class="tpb" onclick="sendEmail()" /><input type="button" value="返回" class="tpb2" onclick="goBack()" /></li>
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
	location.href = "<?=base_url('member/safety/index')?>";
}
//发送验证邮件
function sendEmail(){
	var email = $.trim($("#email").val());
	if( ! isEmail(email)){
		layer.tips("提示：邮箱账号格式错误！", $("#email"), {time:2});
		$("#email").focus();
		return false;
	}
	layer.load("发送中...");
	$.ajax({
		url: "<?=base_url('member/safety/ajax_email')?>",
		type: "post",
		dataType: "json",
		data: {"email":email},
		success: function(data){
			if(data.err_no == 0){
				layer.msg("发送验证邮件成功！", 2, 1);
			}else if(data.err_no == 1003){
				layer.msg("请"+data.results+"秒后再进行发送！", 2, 5);
			}else{
				layer.msg("操作失败请重试！", 2, 5);
			}
		}
	});
}
</script>
</body>
</html>