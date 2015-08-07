<?php
/**
 * VIP通用头部
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-26
 */
defined('BASEPATH') || exit('Access denied');
?>
<div class="gwc_top">
	<div class="area">
		<div class="left">
			<div class="logo_gwc"><a href="<?=base_url()?>"><img src="<?=STATICURL?>images/logo.png" width="218px" height="72px" /></a></div>
		</div>
		<div class="right">
		<?php $vipinfo = $this->session->userdata('vipinfo');if(!empty($vipinfo)):?>
			<a href="<?=base_url('vip/home/index')?>"><?=$vipinfo['username']?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?=base_url('vip/user/logout')?>">退出</a>&nbsp;&nbsp;|&nbsp;
		<?php else:?>
			<a href="<?=base_url('vip/user/login')?>">登录</a>&nbsp;&nbsp;|&nbsp;
		<?php endif;?>
			<a href="<?=base_url('help/index')?>">帮助中心</a>&nbsp;
			<span class="phone"><em></em>400-025-9089</span>
		</div>
	</div>
</div>
