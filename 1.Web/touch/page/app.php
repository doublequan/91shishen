<?php
require_once('../config.php'); 
	 $site_url = $site['url'];
$script = array('app');
$page['title'] = '客户端';
include '../layout/header.php'; 
?>
<div class="app">
	<div class="app-banner">
		<img src="../assets/images/app_banner.png" alt="">
	</div>
	
	<div class="app-download">

			<a href="https://itunes.apple.com/cn/app/hui-sheng-huo+/id939934888?mt=8" target="_top" class="btn btn-red padding" id="ios-app"><i class="icon-shouji"></i> iPhone下载</a>

			<a  href= "http://static.100hl.cn/app/android/100hl_1_0_0.apk?v=20141110" target="_top" class="btn btn-red padding" id="android-app"><i class="icon-shouji"></i> 安卓下载</a>

	</div>
	<br class="clearfix">
	<div class="app-phone">
		<img src="../assets/images/phone.png" alt="">
	</div>
</div>

<?php include '../layout/footer.php';?>