<?php
/**
 * 商品列表
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-25
 */
defined('BASEPATH') || exit('Access denied');
?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title><?=$keyword?> - 搜索 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/search.css"/>
</head>
<body>
<?php $this->load->view('common/header')?>
<div class="search mt10 area">
	<div class="left">
		<div class="box">
			<div class="column"><span>所有分类</span><?=$total_num?></div>
			<ul>
			<?php if(!empty($categories)):foreach($categories as $v):?>
				<li><span><a href="<?=base_url("search/index?s={$s}&cid={$v['category_id']}&sort={$sorttype['sort']}&type={$sorttype['type']}")?>"><?=$v['name']?></a></span><?=$v['category_num']?></li>
			<?php endforeach;endif;?>
			</ul>
		</div>
		<div class="hot">
			<div class="column"><span>本周热卖</span></div>
			<ul>
			<?php if(!empty($ranking)):foreach($ranking as $v):?>
				<li>
					<a href="<?=base_url("goods_{$v['id']}.html")?>" target="_blank">
					<img src="<?=$v['thumb']?>" title="<?=$v['seo_title']?>" width="168" height="168">
					<h2><?=$v['title']?></h2></a>
					<p><em>￥<?=$v['price']?></em>（<?=$v['spec']?>）</p>
				</li>
			<?php endforeach;endif;?>
			</ul>
		</div>
	</div>
	<div class="sbox">
		<div class="top">
			<div class="t">全部搜索结果 "<span><?=$keyword?></span>"<!--，你是不是想找 "<span>我的小苹果</span>"--></div>
			<!--<dl class="clear">
				<dt>已选条件：</dt>
				<dd>
					<a href="javascript:void(0)" class="s"><span>进口水果</span><em>x</em></a>
				</dd>
			</dl>-->
			<dl class="clear">
				<dt>商品分类：</dt>
				<dd>
				<?php if(!empty($categories)):foreach($categories as $v):?>
					<a href="<?=base_url("search/index?s={$s}&cid={$v['category_id']}&sort={$sorttype['sort']}&type={$sorttype['type']}")?>"><?=$v['name']?>&nbsp;(<?=$v['category_num']?>)</a>
				<?php endforeach;endif;?>
				</dd>
			</dl>
		</div>
		<div class="bottom">
			<div class="sort mt10 clearfix">
				<div class="tp tp1">排序：</div>
				<div class="tp tp2">
					<?php if($sorttype['sort']=='id'&&$sorttype['type']=='asc'):?>
						<a href="<?=base_url("search/index?s={$s}&sort=id&type=desc&page={$page}")?>">默认<em class="org_up"></em></a>
					<?php elseif($sorttype['sort']=='id'&&$sorttype['type']=='desc'):?>
						<a href="<?=base_url("search/index?s={$s}&sort=id&type=asc&page={$page}")?>">默认<em class="org_down"></em></a>
					<?php else:?>
						<a href="<?=base_url("search/index?s={$s}&sort=id&type=desc&page={$page}")?>">默认<em class="gray_2"></em></a>
					<?php endif;?>
				</div>
				<div class="tp tp3">
					<?php if($sorttype['sort']=='sold'&&$sorttype['type']=='asc'):?>
						<a href="<?=base_url("search/index?s={$s}&sort=sold&type=desc&page={$page}")?>">销量<em class="org_up"></em></a>
					<?php elseif($sorttype['sort']=='sold'&&$sorttype['type']=='desc'):?>
						<a href="<?=base_url("search/index?s={$s}&sort=sold&type=asc&page={$page}")?>">销量<em class="org_down"></em></a>
					<?php else:?>
						<a href="<?=base_url("search/index?s={$s}&sort=sold&type=desc&page={$page}")?>">销量<em class="gray_2"></em></a>
					<?php endif;?>
				</div>
				<div class="tp tp4">
					<?php if($sorttype['sort']=='comment'&&$sorttype['type']=='asc'):?>
						<a href="<?=base_url("search/index?s={$s}&sort=comment&type=desc&page={$page}")?>">评论<em class="org_up"></em></a>
					<?php elseif($sorttype['sort']=='comment'&&$sorttype['type']=='desc'):?>
						<a href="<?=base_url("search/index?s={$s}&sort=comment&type=asc&page={$page}")?>">评论<em class="org_down"></em></a>
					<?php else:?>
						<a href="<?=base_url("search/index?s={$s}&sort=comment&type=desc&page={$page}")?>">评论<em class="gray_2"></em></a>
					<?php endif;?>
				</div>
				<div class="tp tp4">
					<?php if($sorttype['sort']=='price'&&$sorttype['type']=='asc'):?>
						<a href="<?=base_url("search/index?s={$s}&sort=price&type=desc&page={$page}")?>">价格<em class="org_up"></em></a>
					<?php elseif($sorttype['sort']=='price'&&$sorttype['type']=='desc'):?>
						<a href="<?=base_url("search/index?s={$s}&sort=price&type=asc&page={$page}")?>">价格<em class="org_down"></em></a>
					<?php else:?>
						<a href="<?=base_url("search/index?s={$s}&sort=price&type=asc&page={$page}")?>">价格<em class="gray_2"></em></a>
					<?php endif;?>
				</div>
			</div>
			<div class="fb mt10">
				<ul class="merlist clearfix">
				<?php if(!empty($data)):foreach($data as $v):?>
					<li>
						<a href="<?=base_url("goods_{$v['id']}.html")?>">
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