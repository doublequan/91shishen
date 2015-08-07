<?php defined('BASEPATH') || exit('Access denied'); ?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title><?php echo $single['name']; ?> - <?php echo $siteName; ?></title>
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
    <div class="pic">
        <img src="/static/images/caipu_banner.jpg" />
    </div>
    <p style="text-align:center;font-size:30px; font-weight:700; padding-top:20px;">新版菜谱，敬请期待</p>
</div>
<?php $this->load->view('common/footer')?>
</body>
</html>