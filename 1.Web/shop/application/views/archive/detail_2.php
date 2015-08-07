<?php defined('BASEPATH') || exit('Access denied'); ?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title><?php echo empty($single['seo_title']) ? $single['title'] : $single['seo_title']; ?> - <?php echo $siteName; ?></title>
<meta name="keywords" content="<?php echo $single['seo_keywords']; ?>" />
<meta name="description" content="<?php echo $single['seo_description']; ?>" />
<link rel="stylesheet" href="<?php echo STATICURL; ?>css/reset.css"/>
<link rel="stylesheet" href="<?php echo STATICURL; ?>css/myhome.css"/>
</head>
<body>
<?php $this->load->view('common/header')?>
<div class="breadcrumb area">
    <a href="<?php echo base_url(); ?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<?php echo $single['title']; ?>
</div>
<div class="Ucenter area">
    <?php echo $single['content']; ?>
</div>
<?php $this->load->view('common/footer')?>
</body>
</html>