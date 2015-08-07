<?php defined('BASEPATH') || exit('Access denied'); ?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title>404 - 食材去哪了？ - <?php echo $siteName; ?></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link rel="stylesheet" href="<?php echo STATICURL; ?>css/reset.css"/>
<link rel="stylesheet" href="<?php echo STATICURL; ?>css/myhome.css"/>
<style type="text/css">
</style>
</head>
<body>
<?php $this->load->view('common/header')?>
<div class="Ucenter area">
    <p style="text-align:center;font-size:30px; font-weight:700; padding-top:20px;">你访问的页面不存在？食材去哪了？快去惠生活生鲜商城吧！！</p>
</div>
<?php $this->load->view('common/footer')?>
</body>
</html>