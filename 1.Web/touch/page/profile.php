<?php
require_once('../config.php'); 
	 $site_url = $site['url'];
$script = array('my');
$page['title'] = '我的';
include '../layout/header.php'; 
?>

<div class="profile clearfix">
	<div class="profile-box">
		<div class="profile-avatar">
			<img src="<?=$site_url.'./assets/images/imgurl.png'?>" alt="hsh.avatar"/>
		</div>
		<div class="profile-info clearfix">
			<h4><i class="icon-profile"></i>用户信息</h4>
			<a href="javascript:;" id="get-more" class="profile-info-more f-right">获取更多<i class="icon-moreandroid"></i></a>
			<ul>
				<li>用户名: <label id="username"></label></li>
				<li>卡　号: <label id="cardno"></label></li>
			</ul>
			<ul class="profile-more" data-show="no">
				<li>手　机: <label id="mobile"></label><label id="mobile_status" class="status-yes"></label></li>
				<li>邮　箱: <label id="email"></label><label id="email_status" class="status-no"></label></li>
				<li>企　鹅: <label id="qq"></label></li>
				<li>电　话: <label id="tel"></label></li>
				<li>创建IP: <label id="create_ip"></label></li>
				<li>创建地址: <label id="create_time"></label></li>
				<li>登录IP: <label id="login_ip"></label></li>
				<li>登录时间: <label id="login_time"></label></li>
				
			</ul>
		</div>
		<div class="profile-order">
			<h4><i class="icon-list"></i>订单列表</h4>
			<ul>
				<li><a href="javascript:;" data-pay-status="0" class="pay-status"><i class="icon-right"></i>未支付</a></li>
				<li><a href="javascript:;" data-pay-status="1" class="pay-status"><i class="icon-right"></i>已支付</a></li>
				<li><a href="javascript:;" data-pay-status="2" class="pay-status"><i class="icon-right"></i>已退款</a></li>
			</ul>
		</div>

		<div class="profile-handle">
			<h4><i class="icon-more"></i>更多操作</h4>
			<ul>
				<li><a href="<?=$site_url .'/page/address.php'?>"><i class="icon-right"></i>收货地址</a></li>
				<li><a href="<?=$site_url .'/page/fav_list.php'?>"><i class="icon-right"></i>收藏列表</a></li>
			</ul>
		</div>
	</div>	
</div>
<?php include '../layout/footer.php';?>