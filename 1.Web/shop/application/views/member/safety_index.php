<?php
/**
 * 账号安全
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-28
 */
defined('BASEPATH') || exit('Access denied');
?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title>账号安全 - 个人中心 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/myhome.css"/>
</head>
<body>
<?php $this->load->view('common/header')?>
<div class="breadcrumb area">
	<a href="<?=base_url()?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url('member/home/index')?>">个人中心</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;账号安全
</div>
<div class="Ucenter mt20 area">
	<?php $this->load->view('member/menu')?>
	<div class="mylist">
		<div class="mysafe">
			<div class="title">账号安全</div>
			<div class="sbox mt10">
				<dl class="clearfix">
					<dt><em class="true"></em>登录密码</dt>
					<dd class="da">互联网帐号存在被盗风险，建议您定期更改密码以保护帐号安全。</dd>
					<dd class="db"><a href="<?=base_url('member/safety/passwd')?>">修改密码</a></dd>
				</dl>
				<dl class="clearfix">
				<?php if($data['email_status']==1):?>
					<dt><em class="true"></em>邮箱验证</dt>
					<dd class="da">验证后，可用于找回登录密码。</dd>
					<dd class="db"><a href="<?=base_url('member/safety/email')?>">修改邮箱</a></dd>
				<?php else:?>
					<dt><em class="false"></em>邮箱验证</dt>
					<dd class="da">验证后，可用于找回登录密码。</dd>
					<dd class="db"><a href="<?=base_url('member/safety/email')?>">立即验证</a></dd>
				<?php endif;?>
				</dl>
				<dl class="clearfix">
				<?php if($data['mobile_status']==1):?>
					<dt><em class="true"></em>手机验证</dt>
					<dd class="da">验证后，可用于找回登录密码。</dd>
					<dd class="db"><a href="<?=base_url('member/safety/mobile')?>">修改手机</a></dd>
				<?php else:?>
					<dt><em class="false"></em>手机验证</dt>
					<dd class="da">验证后，可用于找回登录密码。</dd>
					<dd class="db"><a href="<?=base_url('member/safety/mobile')?>">立即验证</a></dd>
				<?php endif;?>
				</dl>
				<div class="tip">
					安全服务提示<br>
					1. 确认您登录的是 惠生活！有货网址    http://nj.100hl.cn/  注意防范进入钓鱼网站，别轻信各种即时通讯发的商品或链接，谨防诈骗。<br>
					2. 建议您安装杀毒软件，并定期更新操作系统等软件补丁，确保帐户及交易安全。
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('common/footer')?>
</body>
</html>