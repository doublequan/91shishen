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
<div class="Ucenter mt20 area">
	<div class="leftnav">
		<dl class="clearfix">
			<dt>惠生活生鲜</dt>
			<dd>
				<ul>
				<?php if( $categories ) { ?>
				<?php foreach( $categories as $row ){ ?>
					<?php if( $category['id']==$row['id'] ){ ?>
					<li class="cur"><span>→</span><a href="<?php echo base_url('archive/list_'.$row['id'].'.html'); ?>"><?php echo $row['name']; ?></a></li>
					<?php } else { ?>
					<li><a href="<?php echo base_url('archive/list_'.$row['id'].'.html'); ?>"><?php echo $row['name']; ?></a></li>
					<?php } ?>
				<?php } ?>
				<?php } ?>
				</ul>
			</dd>
		</dl>
	</div>
	<div class="mylist">
		<div class="aboutus">
			<div class="title"><?php echo $single['title']; ?></div>
			<div><?php echo $single['content']; ?></div>
		</div>
	</div>
</div>
<?php $this->load->view('common/footer')?>
</body>
</html>