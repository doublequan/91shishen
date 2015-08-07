<?php
/**
 * 文章详情
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-17
 */
defined('BASEPATH') || exit('Access denied');
?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title><?php if(empty($data['seo_title'])):?><?=$data['title']?><?php else:?><?=$data['seo_title']?><?php endif;?> - 文章中心 - <?=$siteName?></title>
<meta name="keywords" content="<?=$data['seo_keywords']?>" />
<meta name="description" content="<?=$data['seo_description']?>" />
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/myhome.css"/>
</head>
<body>
<?php $this->load->view('common/header')?>
<div class="breadcrumb area">
	<a href="<?=base_url()?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<?=$data['title']?>
</div>
<div class="Ucenter mt20 area">
<?php $this->load->view('article_menu')?>
	<div class="mylist">
		<div class="aboutus">
			<div class="title"><?=$data['title']?></div>
			<p><?=$data['content']?></p>
		</div>
	</div>
</div>
<?php $this->load->view('common/footer')?>
</body>
</html>