<?php
/**
 * 文章列表
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
<title><?php if(empty($category['seo_title'])):?><?=$category['name']?><?php else:?><?=$category['seo_title']?><?php endif;?> - 文章中心 - <?=$siteName?></title>
<meta name="keywords" content="<?=$category['seo_keywords']?>" />
<meta name="description" content="<?=$category['seo_description']?>" />
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/myhome.css"/>
</head>
<body>
<?php $this->load->view('common/header')?>
<div class="breadcrumb area">
	<a href="<?=base_url()?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url("article/index/{$category['id']}")?>"><?=$category['name']?></a>
</div>
<div class="Ucenter mt20 area">
<?php $this->load->view('article_menu')?>
	<div class="mylist">
		<div class="newslist">
			<!--<div class="title"><?=$category['name']?></div>-->
			<ul class="clearfix">
				<li class="th"><span class="ty1">文章标题</span><span class="ty3">发布时间</span></li>
				<?php if(!empty($results)):foreach($results as $v):?>
				<li>
					<span class="ty1"><a href="<?=base_url("archive/{$v['id']}.html")?>"><?=$v['title']?></a></span>
					<span class="ty3"><?=date('Y-m-d H:i',$v['create_time'])?></span>
				</li>
				<?php endforeach;endif;?>
			</ul>
		</div>
		<div class="mypage mt20">
			<?php if( isset($pager['pageArray']) && !empty($pager['pageArray']) ){ ?>
			<a href="<?php echo base_url('archive/list_'.$category['id'].'.html')?>">首页</a>
			<?php foreach( $pager['pageArray'] as $v ) { ?>
			<a href="<?php echo base_url('archive/list_'.$category['id'].'_'.$v.'.html')?>"<?php if( $pager['page']==$v ){ ?> class="page_number cur"<?php } ?>><?php echo $v; ?></a>
			<?php } ?>
			<a href="<?php echo base_url('archive/list_'.$category['id'].'_'.$pager['pages'].'.html')?>">末页</a>
			<?php } ?>
		</div>
	</div>
</div>
<?php $this->load->view('common/footer')?>
</body>
</html>