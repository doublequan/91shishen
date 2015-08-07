<?php
require_once('../config.php'); 
	 $site_url = $site['url'];
$script = array('cash');
$page['title'] = '收银台';

$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : 0;


include '../layout/header.php'; 
?>

<div data-order-id="<?=$order_id?>" class="cash">
		
		<div class="card-box">
			<div class="well">您选择了“会员卡支付”，请输入会员卡信息！</div>
			<ul>
				<li><label>会员卡号：</label><input type="text" name="card_number" id="card_number" value=""></li>
				<li><label>密码：</label><input type="password" name="card_password" id="card_password" value=""></li>
			</ul>
			<div class="card-btn">
				<a href="javascript:;" id="card-btn" class="btn btn-blue padding"><i class="icon-pay"></i> 确定支付</a>
			</div>
		</div>
		
		<div class="alipay-box">
			<div class="well">点击“立即支付”，会跳转到支付宝网站！</div>
			<div class="alipay-btn">
			<form action="<?=$site_url.'/plugin/alipay/alipayapi.php'?>" method="post" id="alipay-form" target="_blank">
				<input type="hidden" name="trade_no" value="">
				<input type="hidden" name="total_fee" value="">
				<button type="submit" id="alipay-btn" class="btn btn-blue padding"><i class="icon-recharge"></i> 立即支付</button>
			</form>
			</div>
		</div>
		<div class="common-box">
			<div class="cash-result"><i class="icon-emoji"></i><label>支付成功！</label></div>
			<div class="cash-tip clearfix">
				<ul>
					<li><a href="<?=$site_url?>" ><i class="icon-home"></i>返回首页</a></li>
					<li><a href="<?=$site_url .'/page/profile.php'?>" ><i class="icon-profile"></i>个人中心</a></li>
				</ul>
			</div>
		</div>
</div>

<?php include '../layout/footer.php';?>