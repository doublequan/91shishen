<?php
/**
 * 通用头部
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-3
 */
defined('BASEPATH') || exit('Access denied');
?>
<div class="gwc_top">
	<div class="area">
		<div class="left">
			<div class="logo_gwc"><a href="/"><img src="<?=STATICURL?>images/logo.png" width="218px" height="72px" /></a></div>
		</div>
		<div class="right">
		<?php $userinfo = $this->session->userdata('userinfo');if(!empty($userinfo)):?>
			<a href="<?=base_url('member/order/index')?>"><?=$userinfo['username']?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?=base_url('member/user/logout')?>">退出</a>&nbsp;&nbsp;|&nbsp;
		<?php else:?>
			<a href="<?=base_url('member/user/login')?>">登录</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?=base_url('member/user/register')?>">免费注册</a>&nbsp;&nbsp;|&nbsp;
		<?php endif;?>
			<a href="<?=base_url('member/home/index')?>">个人中心</a>&nbsp;&nbsp;|&nbsp;
			<a href="<?=base_url('archive/list_3.html')?>">帮助中心</a>&nbsp;
			<span class="phone"><em></em>400-025-9089</span>
		</div>
	</div>
</div>
