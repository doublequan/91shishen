<?php
/**
 * 用户中心菜单
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-21
 */
defined('BASEPATH') || exit('Access denied');
?>
<div class="leftnav">
	<div class="myintro">
		<div class="title">个人中心 <em>Ucenter</em></div>
		<div class="my">
			<a href="<?=base_url('member/home/index')?>"><img id="cur_avatar" src="<?=getAvatar($userInfo['uid'])?>" width="65" height="65" /></a>
			<div class="link">
				<a href="<?=base_url('member/profile/index')?>"><?=$userInfo['username']?></a>
				<a href="<?=base_url('member/profile/header')?>">修改头像</a>
			</div>
		</div>
	</div>
	<dl class="clearfix">
		<dt>交易管理</dt>
		<dd>
			<ul>
				<?php if($currMenu=='member_order'):?><li class="cur"><span>→</span><a href="<?=base_url('member/order/index')?>">我的订单</a></li><?php else:?><li><a href="<?=base_url('member/order/index')?>">我的订单</a></li><?php endif;?>
				<?php if($currMenu=='member_favorite'):?><li class="cur"><span>→</span><a href="<?=base_url('member/favorite/index')?>">我的收藏</a></li><?php else:?><li><a href="<?=base_url('member/favorite/index')?>">我的收藏</a></li><?php endif;?>
				<?php if($currMenu=='member_coupon_cash'):?><li class="cur"><span>→</span><a href="<?=base_url('member/coupon/index/cash')?>">我的代金券</a></li><?php else:?><li><a href="<?=base_url('member/coupon/index/cash')?>">我的代金券</a></li><?php endif;?>
			</ul>
		</dd>
	</dl>
	<dl class="clearfix">
		<dt>财务管理</dt>
		<dd>
			<ul>
				<!-- <li><a href="<?=base_url('member/payment/index')?>">会员卡充值</a></li> -->
				<li><a href="http://c.yeepay.com/prepay-userservice/initLogin.action" target="_blank">会员卡充值</a></li>
				<!-- <?php if($currMenu=='member_logs_charge'):?><li class="cur"><span>→</span><a href="<?=base_url('member/logs/charge')?>">充值记录</a></li><?php else:?><li><a href="<?=base_url('member/logs/charge')?>">充值记录</a></li><?php endif;?> -->
				<?php if($currMenu=='member_logs_money'):?><li class="cur"><span>→</span><a href="<?=base_url('member/logs/money')?>">资金记录</a></li><?php else:?><li><a href="<?=base_url('member/logs/money')?>">资金记录</a></li><?php endif;?>
				<?php if($currMenu=='member_logs_score'):?><li class="cur"><span>→</span><a href="<?=base_url('member/logs/score')?>">积分记录</a></li><?php else:?><li><a href="<?=base_url('member/logs/score')?>">积分记录</a></li><?php endif;?>
			</ul>
		</dd>
	</dl>
	<dl class="clearfix">
		<dt>信息管理</dt>
		<dd>
			<ul>
				<?php if($currMenu=='member_profile'):?><li class="cur"><span>→</span><a href="<?=base_url('member/profile/index')?>">个人资料</a></li><?php else:?><li><a href="<?=base_url('member/profile/index')?>">个人资料</a></li><?php endif;?>
				<?php if($currMenu=='member_safety'):?><li class="cur"><span>→</span><a href="<?=base_url('member/safety/index')?>">账号安全</a></li><?php else:?><li><a href="<?=base_url('member/safety/index')?>">账号安全</a></li><?php endif;?>
				<?php if($currMenu=='member_address'):?><li class="cur"><span>→</span><a href="<?=base_url('member/address/index')?>">地址管理</a></li><?php else:?><li><a href="<?=base_url('member/address/index')?>">地址管理</a></li><?php endif;?>
			</ul>
		</dd>
	</dl>
</div>
