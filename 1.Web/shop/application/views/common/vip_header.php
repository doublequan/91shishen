<?php
/**
 * VIP通用头部
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-26
 */
defined('BASEPATH') || exit('Access denied');
?>
<div class="topnav">
	<div class="area">
		<div class="welcome flt">您好，欢迎光临惠生活！<span class="select cur" onMouseOver="$('#city_select').show()" onMouseOut="$('#city_select').hide()">
		<?php if(empty($_COOKIE['SITEID']) || intval($_COOKIE['SITEID']) == 1){ echo '南京'; }elseif(intval($_COOKIE['SITEID']) == 2){ echo '扬州'; }elseif(intval($_COOKIE['SITEID']) == 3){ echo '马鞍山'; } ?>
		<em></em><div class="city_select" id="city_select">
			<div class="title"><b>选择城市</b></div>
			<ul>
				<a href="javascript:;" onclick="switchSite(1)"><li>N 南京</li></a>
				<a href="javascript:;" onclick="switchSite(2)"><li>Y 扬州</li></a>
				<a href="javascript:;" onclick="switchSite(3)"><li>M 马鞍山</li></a>
			</ul>
		</div></span>站</div>
		<div class="favorite"><a href="javascript:;" onclick="addFav('惠生活','http://hsh.com')">加入收藏</a></div>
		<ul class="fr">
		<?php $vipinfo = $this->session->userdata('vipinfo');if(!empty($vipinfo)):?>
			<li class="reg"><a href="<?=base_url('vip/home/index')?>"><?=$vipinfo['username']?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?=base_url('vip/user/logout')?>">退出</a></li>
		<?php else:?>
			<li class="reg"><a href="<?=base_url('vip/user/login')?>">登录</a></li>
		<?php endif;?>
			<li class="phone"><em></em>400-025-9089</li>
		</ul>
	</div>
</div>
<div class="logo area">
	<a href="<?=base_url()?>"><img src="<?=STATICURL?>images/logo.png" width="218px" height="72px" /></a>
</div>
<div class="nav">
	<div class="area">
		<div class="home fl"><a href="<?=base_url('vip/home/index')?>" <?php if($currMenu=='home_index'):?>class="cur"<?php endif;?>><em>主页</em></a></div>
		<div class="navlist fl">
			<a href="<?=base_url('vip/product/index')?>">全部商品</a>
			<a href="<?=base_url('vip/product/booking')?>">商品定制</a>
			<a href="<?=base_url('vip/product/booklog')?>">定制记录</a>
		</div>
		<div class="shopping">
			<div class="bgl">
			<a href="<?=base_url('vip/cart/index')?>" style="color:#fff;">
				<div class="bgr">
					<div class="number" id="cart_number"><?=$vipCartNum?></div>
					<span class="simg"></span>购物车
				</div>
			</a>
			</div>
		</div>
	</div>
</div>
