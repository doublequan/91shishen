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
        <img src="http://static.100hl.cn/2014/11/1415747488346184.jpg" />
    </div>
</div>
<?php $this->load->view('common/footer')?>
</body>
</html>