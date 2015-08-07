<?php
/**
 * VIP用户中心菜单
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-26
 */
defined('BASEPATH') || exit('Access denied');
?>
<div class="leftnav">
	<div class="myintro">
			<div class="title">个人中心 <em>Ucenter</em></div>
			<div class="my">
				<a href="<?=base_url('vip/home/index')?>"><img id="cur_avatar" src="<?=getAvatar(0)?>" width="65" height="65" /></a>
				<div class="link">
					<?=$vipInfo['username']?>
					<a href="<?=base_url('vip/home/index')?>">个人中心</a>
				</div>
			</div>
		</div>
	<dl class="clearfix">
		<dt>交易管理</dt>
		<dd>
			<ul>
				<?php if($currMenu=='vip_order'):?><li class="cur"><span>→</span>我的订单</li><?php else:?><li><a href="<?=base_url('vip/order/index')?>">我的订单</a></li><?php endif;?>
			</ul>
		</dd>
	</dl>
	<dl class="clearfix">
		<dt>个人信息管理</dt>
		<dd>
			<ul>
				<?php if($currMenu=='vip_safety'):?><li class="cur"><span>→</span>账号安全</li><?php else:?><li><a href="<?=base_url('vip/safety/index')?>">账号安全</a></li><?php endif;?>
				<?php if($currMenu=='vip_address'):?><li class="cur"><span>→</span>地址管理</li><?php else:?><li><a href="<?=base_url('vip/address/index')?>">地址管理</a></li><?php endif;?>
			</ul>
		</dd>
	</dl>
</div>
