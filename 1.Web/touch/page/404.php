<?php
header("HTTP/1.1 404 Not Found");
header("Status: 404 Not Found");
require_once('../config.php'); 
$site_url = $site['url'];
$script = array('404');
$page['title'] = "页面未找到";
header("Refresh:3;url=$site_url");
include '../layout/header.php'; 
?>

<div class="bg-ivory layout page-content">
	
	<div class="err_404 text-center">
		<div>
			<img src="<?=$site_url.'/assets/images/err404.png';?>" alt="Page 404">
		</div>
		<div class="err_list clearfix">
		<p><i class="icon-warn"></i>页面未找到！<a href="<?=$site_url?>"><i class="icon-footprint"></i> 返回首页</a></p>
		
		</div>
	</div>
</div>

<?php include '../layout/footer.php';?>