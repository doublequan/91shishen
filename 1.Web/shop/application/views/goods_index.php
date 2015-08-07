<?php
/**
 * 商品列表
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-4
 */
defined('BASEPATH') || exit('Access denied');
?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title><?php if(empty($category['seo_title'])):?><?=$category['name']?><?php else:?><?=$category['seo_title']?><?php endif;?> - 商品中心 - <?=$siteName?></title>
<meta name="keywords" content="<?=$category['seo_keywords']?>" />
<meta name="description" content="<?=$category['seo_description']?>" />
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/index.css"/>
</head>
<body>
<?php $this->load->view('common/header')?>
<div class="breadcrumb area">
	<a target="_blank" href="<?=base_url()?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a target="_blank" href="<?=base_url("cat_{$category['id']}.html")?>"><?=$category['name']?></a>
</div>
<div class="cf mt20 area" id="cf">
	<div class="left">
		<div class="left_nav">
			<div class="column"><?=$category['name']?></div>
			<dl class="clearfix">
			<?php if(!empty($categories)):foreach($categories as $v):?>
				<dt><a target="_blank" href="<?=base_url("cat_{$v['id']}.html")?>"><?=$v['name']?></a></dt>
				<dd>
				<?php $k=1;if(!empty($v['children'])):foreach($v['children'] as $v2):?>
					<?php if($k>1):?><em>|</em><?php endif;?>
					<a target="_blank" href="<?=base_url("cat_{$v2['id']}.html")?>"><?=$v2['name']?></a>
				<?php $k++;endforeach;endif;?>
				</dd>
			<?php endforeach;endif;?>
			</dl>
		</div>
	</div>
	<div class="column_r">
		<!--
		<div class="fa">
			<img src="<?=$category['thumb_web']?>" width="988" height="288" />
		</div>
		-->
		<div class="sort mt20 clearfix">
			<div class="tp tp1">排序：</div>
			<div class="tp tp2">
				<?php if($sorttype['sort']=='id'&&$sorttype['type']=='asc'):?>
					<a href="<?=base_url("cat_{$category['id']}.html?sort=id&type=desc&page={$page}")?>">默认<em class="org_up"></em></a>
				<?php elseif($sorttype['sort']=='id'&&$sorttype['type']=='desc'):?>
					<a href="<?=base_url("cat_{$category['id']}.html?sort=id&type=asc&page={$page}")?>">默认<em class="org_down"></em></a>
				<?php else:?>
					<a href="<?=base_url("cat_{$category['id']}.html?sort=id&type=desc&page={$page}")?>">默认<em class="gray_2"></em></a>
				<?php endif;?>
			</div>
			<div class="tp tp3">
				<?php if($sorttype['sort']=='sold'&&$sorttype['type']=='asc'):?>
					<a href="<?=base_url("cat_{$category['id']}.html?sort=sold&type=desc&page={$page}")?>">销量<em class="org_up"></em></a>
				<?php elseif($sorttype['sort']=='sold'&&$sorttype['type']=='desc'):?>
					<a href="<?=base_url("cat_{$category['id']}.html?sort=sold&type=asc&page={$page}")?>">销量<em class="org_down"></em></a>
				<?php else:?>
					<a href="<?=base_url("cat_{$category['id']}.html?sort=sold&type=desc&page={$page}")?>">销量<em class="gray_2"></em></a>
				<?php endif;?>
			</div>
			<div class="tp tp4">
				<?php if($sorttype['sort']=='comment'&&$sorttype['type']=='asc'):?>
					<a href="<?=base_url("cat_{$category['id']}.html?sort=comment&type=desc&page={$page}")?>">评论<em class="org_up"></em></a>
				<?php elseif($sorttype['sort']=='comment'&&$sorttype['type']=='desc'):?>
					<a href="<?=base_url("cat_{$category['id']}.html?sort=comment&type=asc&page={$page}")?>">评论<em class="org_down"></em></a>
				<?php else:?>
					<a href="<?=base_url("cat_{$category['id']}.html?sort=comment&type=desc&page={$page}")?>">评论<em class="gray_2"></em></a>
				<?php endif;?>
			</div>
			<div class="tp tp4">
				<?php if($sorttype['sort']=='price'&&$sorttype['type']=='asc'):?>
					<a href="<?=base_url("cat_{$category['id']}.html?sort=price&type=desc&page={$page}")?>">价格<em class="org_up"></em></a>
				<?php elseif($sorttype['sort']=='price'&&$sorttype['type']=='desc'):?>
					<a href="<?=base_url("cat_{$category['id']}.html?sort=price&type=asc&page={$page}")?>">价格<em class="org_down"></em></a>
				<?php else:?>
					<a href="<?=base_url("cat_{$category['id']}.html?sort=price&type=asc&page={$page}")?>">价格<em class="gray_2"></em></a>
				<?php endif;?>
			</div>
		</div>
		<div class="fb mt10">
			<ul class="merlist clearfix">
			<?php if(!empty($data)):foreach($data as $v):?>
				<li>
					<a target="_blank" href="<?=base_url("goods_{$v['id']}.html")?>">
						<img class="scrollLoading" data-url="<?=$v['thumb']?>" src="/static/images/1px.gif" title="<?=$v['seo_title']?>" width="238" height="238" />
						<h2><?=$v['title']?></h2>
					</a>
					<p class="txt"><?php echo $v['seo_title'] ? $v['seo_title'] : '&nbsp;&nbsp;'; ?></p>
					<p class="prices"><em>￥<?=$v['price']?></em>&nbsp;&nbsp;（<?=$v['spec']?>）</p>
					<div class="buyBox">
						<input type="text" class="count" value="1"/>
						<div class="countBox">
							<em class="add"></em>
							<em class="subtract"></em>
						</div>
						<input type="submit" class="sub" value="加入购物车" product_id="<?php echo $v['id']; ?>" />
					</div>
				</li>
			<?php endforeach;endif;?>
			</ul>
		</div>
		<div class="mypage mt20"><?=$pager?></div>
	</div>
</div>
<?php $this->load->view('common/footer')?>
<script src="<?=STATICURL?>js/jquery.scrollLoading-min.js"></script>
<script type="text/javascript">
function addCart( product_id, buy_number ){
	layer.load("提交中...");
	$.ajax({
		url: "<?php echo base_url('cart/manage/add'); ?>",
		type: "post",
		dataType: "json",
		data: {"product_id":product_id, "amount":buy_number},
		success: function(data){
			if(data.err_no == 0){
				$("#cart_number").html(data.results.total_number);
				//layer.msg("添加到购物车成功！", 2, 1);
				$.layer({
					shade: [0.3, '#000'],
					area: ['auto','auto'],
					dialog: {
						msg: '添加到购物车成功！',
						type: 1,
						btns: 2,
						btn: ['购物车', '继续购物'],
						yes: function(){
							location.href = "<?php echo base_url('cart/index'); ?>";
						}, no: function(){
							//location.href = "<?=base_url()?>";
						}
					}
				});
			}else{
				layer.msg("添加到购物车失败！", 2, 5);
			}
		}
	});
}
$(document).ready(function(){
	//延迟加载
	$(".scrollLoading").scrollLoading();
	//加入购物车
	$('.buyBox .add').click( function(){
		var tThis = $(this).parent().parent().find('.count');
		var curr = parseInt(tThis.val());
		curr = isNaN(curr) ? 1 : Math.max(1, curr);
		curr ++;
		tThis.val(curr);
	});
	$('.buyBox .subtract').click( function(){
		var tThis = $(this).parent().parent().find('.count');
		var curr = parseInt(tThis.val());
		curr = isNaN(curr) ? 1 : Math.max(1, curr);
		curr --;
		curr = Math.max(1, curr);
		tThis.val(curr);
	});
	$('.buyBox .sub').click( function(){
		var product_id = $(this).attr('product_id');
		var buy_number = parseInt($(this).parent().parent().find('.count').val());
		var n_pattern = /^[1-9][0-9]*$/;
		if( ! n_pattern.test(product_id)){
			layer.msg("商品参数错误", 2, 5);
		} else if( isNaN(buy_number) || buy_number<1 ){
			layer.msg("请填写正确的购买数量", 2, 5);
		} else {
			addCart(product_id,buy_number);
		}
		return false;
	});
});
</script>
</body>
</html>