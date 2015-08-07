<?php
/**
 * 个人资料
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-11
 */
defined('BASEPATH') || exit('Access denied');
?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title>个人资料 - 个人中心 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/myhome.css"/>
</head>
<body>
<?php $this->load->view('common/header')?>
<div class="breadcrumb area">
	<a href="<?=base_url()?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url('member/home/index')?>">个人中心</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;个人资料
</div>
<div class="Ucenter mt20 area">
	<?php $this->load->view('member/menu')?>
	<div class="mylist">
		<div class="myinfo">
			<div class="title">个人资料</div>
			<div class="info clearfix">
				<div class="left"><a href="<?=base_url('member/profile/header')?>"><img src="<?=getAvatar($userInfo['uid'])?>" style="width:150px;height:150px;" />编辑头像</a></div>
				<div class="center">
					<ul>
						<li><label>账　　号：</label><input type="text" class="tpa" disabled name="username" value="<?=$data['username']?>" /></li>
						<li><label>手　　机：</label><input type="text" class="tpa" disabled name="mobile" value="<?=$data['mobile']?>" /></li>
						<li><label>邮　　箱：</label><input type="text" class="tpa" disabled name="email" value="<?=$data['email']?>" /></li>
						<li><label>电　　话：</label><input type="text" class="tpa" id="tel" name="tel" value="<?=$data['tel']?>" /></li>
						<li><label>性　　别：</label><label><input type="radio" name="gender" class="ra" value="1" <?php if($data['gender']==1):?>checked<?php endif;?> />男</label>&nbsp;&nbsp;
							<label><input type="radio" name="gender" class="ra" value="2" <?php if($data['gender']==2):?>checked<?php endif;?> />女</label></li>
						<li><label>生　　日：</label>
							<?php if(empty($data['birthday'])):$birthday=explode('-',date('Y-m-d'));else:$birthday = explode('-',$data['birthday']);endif;?>
							<select id="year">
								<?php for($i=date('Y');$i>=1950;$i--){?>
								<option value="<?=$i?>" <?php if($i==$birthday[0]):?>selected<?php endif;?> ><?=$i?></option>
								<?php }?>
							</select>
							<select id="month">
								<?php for($i=1;$i<=12;$i++){?>
								<option value="<?=$i?>" <?php if($i==$birthday[1]):?>selected<?php endif;?> ><?=$i?></option>
								<?php }?>
							</select>
							<select id="day">
								<?php for($i=1;$i<=date('t');$i++){?>
								<option value="<?=$i?>" <?php if($i==$birthday[2]):?>selected<?php endif;?> ><?=$i?></option>
								<?php }?>
							</select>
						</li>
						<li><label>会员卡号：</label><input type="text" class="tpa" disabled value="<?=$data['cardno']?>" /></li>
						<li><label>QQ号码：</label><input type="text" class="tpa" id="qq" name="qq" value="<?=$data['qq']?>" /></li>
						<li><input type="submit" class="tpb" value="保存" onclick="doSubmit()" /></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('common/footer')?>
<script src="<?=STATICURL?>js/verify.js"></script>
<script type="text/javascript">
//提交
function doSubmit(){
	var gender = $("input[name='gender']:checked").val();
	var year = $("#year option:selected").val();
	var month = $("#month option:selected").val();
	var day = $("#day option:selected").val();
	var tel = $.trim($("#tel").val());
	var qq = $.trim($("#qq").val());
	var birthday = year + '-' + month + '-' + day;
	if(tel != "" && ! isTelephone(tel)){
		layer.msg("电话格式不正确", 2, 5);
		$("#tel").focus();
		return false;
	}else if(qq != "" && ! isQq(qq)){
		layer.msg("QQ格式不正确", 2, 5);
		$("#qq").focus();
		return false;
	}
	layer.load("提交中...");
	$.ajax({
		url: "<?=base_url('member/profile/ajax_upd')?>",
		type: "post",
		dataType: "json",
		data: {"gender":gender, "birthday":birthday, "tel":tel, "qq":qq},
		success: function(data){
			if(data.err_no == 0){
				layer.msg("修改个人资料成功！", 2, 1, function(){ location.reload();});
			}else{
				layer.msg("修改个人资料失败！", 2, 5);
			}
		}
	});
}
</script>
</body>
</html>