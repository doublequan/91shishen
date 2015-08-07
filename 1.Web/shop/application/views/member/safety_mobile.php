<?php
/**
 * 修改手机
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-24
 */
defined('BASEPATH') || exit('Access denied');
?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title>修改手机 - 个人中心 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/myhome.css"/>
</head>
<body>
<?php $this->load->view('common/header')?>
<div class="breadcrumb area">
	<a href="<?=base_url()?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url('member/home/index')?>">个人中心</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url('member/safety/index')?>">账号安全</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;修改手机
</div>
<div class="Ucenter mt20 area">
	<?php $this->load->view('member/menu')?>
	<div class="mylist">
		<div class="password">
			<div class="title">修改手机</div>
			<div class="pass">
				<ul>
					<li><label>手机号：</label><input type="text" id="mobile" maxlength="11" value="<?=$data['mobile']?>" /><span>请输入要验证的手机号码</span></li>
					<li><label>验证码：</label><input type="text" id="verify" maxlength="6" /><span><input type="button" value="发送" class="tpb" style="margin-left:0;" onclick="getVerify()" /></span></li>
					<li><input type="button" value="确定" class="tpb" onclick="verifyMobile()" /><input type="button" value="返回" class="tpb2" onclick="goBack()" /></li>
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
//获取短信验证码
function getVerify(){
	var mobile = $.trim($("#mobile").val());
	if( ! isMobile(mobile)){
		layer.tips("提示：手机号格式错误！", $("#mobile"), {time:2});
		$("#mobile").focus();
		return false;
	}
	layer.load("发送中...");
	$.ajax({
		url: "<?=base_url('member/safety/ajax_mobile')?>",
		type: "post",
		dataType: "json",
		data: {"mobile":mobile},
		success: function(data){
			if(data.err_no == 0){
				layer.msg("发送验证短信成功！", 2, 1);
			}else if(data.err_no == 1003){
				layer.msg("请"+data.results+"秒后再进行发送！", 2, 5);
			}else{
				layer.msg("操作失败请重试！", 2, 5);
			}
		}
	});
}
//验证手机
function verifyMobile(){
	var mobile = $.trim($("#mobile").val());
	var verify = $.trim($("#verify").val());
	if( ! isMobile(mobile)){
		layer.tips("提示：手机号格式错误！", $("#mobile"), {time:2});
		$("#mobile").focus();
		return false;
	}else if( ! isMobileVerify(verify)){
		layer.tips("提示：验证码格式错误！", $("#verify"), {time:2});
		$("#verify").focus();
		return false;
	}
	layer.load("提交中...");
	$.ajax({
		url: "<?=base_url('member/safety/ajax_verify')?>",
		type: "post",
		dataType: "json",
		data: {"mobile":mobile,"verify":verify},
		success: function(data){
			if(data.err_no == 0){
				layer.msg("验证手机成功！", 2, 1, function(){ goBack();});
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