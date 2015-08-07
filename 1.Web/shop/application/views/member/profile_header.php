<?php
/**
 * 上传头像
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
<title>上传头像 - 个人中心 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/myhome.css"/>
</head>
<body>
<?php $this->load->view('common/header')?>
<div class="breadcrumb area">
	<a href="<?=base_url()?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url('member/home/index')?>">个人中心</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;修改头像
</div>
<div class="Ucenter mt20 area">
	<?php $this->load->view('member/menu')?>
	<div class="mylist">
		<div class="myinfo">
			<div class="title">上传头像</div>
			<div style="height:550px;">
				<div style="float:left;margin-left:30px;width:610px;">
					<div class="sctx">	
						<div style="width:600px;height:450px;float:left;border:solid 1px #EFEFEF;">
							<div id="altContent">
								<h1>AvatarUpload</h1>
								<p><a href="http://www.adobe.com/go/getflashplayer">Get Adobe Flash player</a></p>
							</div>
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('common/footer')?>
<script src="<?=STATICURL?>js/verify.js"></script>
<script type="text/javascript" src="<?=STATICURL?>avatar/js/swfobject.js"></script>
<script type="text/javascript">
var flashvars = {
	js_handler:"callback",
	swfID:"avatarEdit",
	picSize:"2048000",
	sourceAvatar:"<?=getAvatar($userInfo['uid'])?>",
	avatarLabel:"头像预览，请注意清晰度！",
	avatarAPI:"<?=base_url('member/profile/upload')?>",
	avatarSize:"180,180",
	avatarSizeLabel:"我的头像"
};
var params = {
	menu: "false",
	scale: "noScale",
	allowFullscreen: "true",
	allowScriptAccess: "always",
	bgcolor: "",
	wmode: "transparent"
};
var attributes = {
	id:"AvatarUpload"
};
swfobject.embedSWF(
	"<?=STATICURL?>avatar/avatarUpload.swf", 
	"altContent", "100%", "100%", "10.0.0", 
	"expressInstall.swf", 
	flashvars, params, attributes
);	
function callback(obj){
	if(obj.type == "avatarSuccess"){
		$("#cur_avatar").attr("src", "<?=getAvatar($userInfo['uid'])?>?"+Math.random());
		layer.msg("上传头像成功！", 2, 1, function(){ location.reload();});
	}else if(obj.type == "avatarError"){
		layer.msg("上传头像失败！", 2, 5, function(){ location.reload();});
	}
	//if(obj.type == "init") //Flash初始化完成
	//if(obj.type == "FileSelectCancel") //取消选取本机图片
	if(obj.type == "cancel"){
		//取消编辑头像
		window.location.reload();
	}
	console.log(obj);
}
</script>
</body>
</html>