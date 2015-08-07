<?php
/**
 * 商品详情
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
<title><?=$data['title']?><?php if(!empty($data['seo_title'])):?> - <?=$data['seo_title']?><?php endif;?> - 商品中心 - <?=$siteName?></title>
<meta name="keywords" content="<?=$data['seo_keywords']?>" />
<meta name="description" content="<?=$data['seo_description']?>" />
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/page.css"/>
</head>
<body>
<?php $this->load->view('common/header')?>
<div class="breadcrumb area">
	<a href="<?=base_url()?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;
	<a href="<?=base_url("cat_{$category['id']}.html")?>"><?=$category['name']?></a>
</div>
<div class="page area">
	<div class="left">
		<div class="pic" style="height:470px;">
			<li><img src="<?php echo $photo ? $photo[0]['img'] : $data['thumb']; ?>" alt="<?=$data['title']?>" id="curr_image" style="width:440px;height:440px;" /></li>
			<div class="zf">
				<p>商品编号：<em><?=$data['sku']?></em>&nbsp;&nbsp;|&nbsp;&nbsp;分享：</p><div class="bdsharebuttonbox"><a href="#" class="bds_more" data-cmd="more"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#" class="bds_renren" data-cmd="renren" title="分享到人人网"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a><a href="#" class="bds_tqf" data-cmd="tqf" title="分享到腾讯朋友"></a><a href="#" class="bds_sqq" data-cmd="sqq" title="分享到QQ好友"></a></div>
			</div>
		</div>
		<div class="picl" style="height:480px;overflow:hidden;">
			<a class="top" href="javascript:;" id="thumb_prev">∧</a>
			<ul id="thumb_list">
				<?php if(!empty($photo)):foreach($photo as $v):?>
				<li class="item"><a href="javascript:;" onclick="showImage('<?=$v['img']?>')"><img src="<?=$v['thumb']?>" style="width:90px;height:90px;" /></a></li>
				<?php endforeach;endif;?>
			</ul>
			<a class="bottom" href="javascript:;" id="thumb_next">∨</a>
		</div>
	</div>
	<div class="attribute">
		<h1><?=$data['title']?></h1>
		<p><?=$data['seo_title']?></p> 
	<dl class="clearfix"><dt>规　　格：</dt><dd><?=$data['spec']?></dd></dl>
		<?php if( $shuang == 1 ){ ?>
		<dl class="clearfix"><dt>惠生活价：</dt><dd><em class="price" style=" text-decoration:line-through;">￥<?=$data['price_market']?></em></dd></dl>
		<dl class="clearfix"><dt>双 12 价：</dt><dd><em class="price">￥<?=$data['price']?></em>（赠送积分：<?=floor($data['price'])?>）</dd></dl>
		<?php }elseif( $zuhe == 1 ){ ?>
		<dl class="clearfix"><dt>单 购 价：</dt><dd><em class="price" style=" text-decoration:line-through;">￥<?=$data['price_market']?></em></dd></dl>
		<dl class="clearfix"><dt>组 合 价：</dt><dd><em class="price">￥<?=$data['price']?></em>（赠送积分：<?=floor($data['price'])?>）</dd></dl>
		<?php }elseif( $tejia == 1 ){ ?>
		<dl class="clearfix"><dt>市 场 价：</dt><dd><em class="price" style=" text-decoration:line-through;">￥<?=$data['price_market']?></em></dd></dl>
		<dl class="clearfix"><dt>惠生活价：</dt><dd><em class="price">￥<?=$data['price']?></em>（赠送积分：<?=floor($data['price'])?>）</dd></dl>
		<?php } else { ?>
		<dl class="clearfix"><dt>惠生活价：</dt><dd><em class="price">￥<?=$data['price']?></em>（赠送积分：<?=floor($data['price'])?>）</dd></dl>
		<?php } ?>		
		<dl class="clearfix"><dt>商品评论：</dt><dd><a href="#comments">查看评论</a></dd></dl>
		<dl class="clearfix"><dt>商品品牌：</dt><dd><?=$data['brand']?></dd></dl>
		<dl class="clearfix"><dt>促销信息：</dt><dd>单笔订单实付金额满<?=SHIPPING_FREELIMIT?>元免运费</dd></dl>
		<dl class="clearfix">
			<dt>配送时间：</dt>
			<?php if( $data['type']==0 ){ ?>
			<dd>11:00之前的订单，当日送达（20:00之前）；<br />11:00之后的订单，次日送达。</dd>
			<?php } else { ?>
			<dd>先下单后采购,每周六上午统一发货<br />周五11点后所下订单下周六发货</dd>
			<?php } ?>
		</dl>
		<!-- <dl class="clearfix"><dt>库存数量：</dt><dd><?php if(empty($stock['stock'])):?>0<?php elseif($stock['stock']==-1):?>无限制<?php else:?><?=$stock['stock']?><?php endif;?></dd></dl> -->
		<dl class="clearfix">
			<dt>购买数量：</dt>
			<dd>
				<em class="tp1" onclick="updCart('dec')">-</em>
				<input type="text" class="count" name="buy_number" value="1" onblur="this.value=(isNaN(parseInt(this.value))?1:Math.max(1,parseInt(this.value)));" />
				<em class="tp2" onclick="updCart('inc')">+</em></dd>
		</dl>
		<div class="btns clearfix">
		<?php if(empty($stock)):?>
			<a class="btn-sc" href="javascript:;">已下架</a>
		<?php elseif($stock['stock'] == 0):?>	
			<a class="btn-sc" href="javascript:;">暂时缺货</a>
		<?php else:?>
			<a class="btn-gwc" href="javascript:;" onclick="addCart()">加入购物车</a>
		<?php endif;?>
			<a class="btn-sc" href="javascript:;" onclick="addFavorite()">收藏该商品</a>
		</div>
	</div>
</div>
<div class="histroy area clearfix">
	<div class="title"><span>浏览过此商品的顾客也浏览了以下商品</span></div>
	<div class="box">
		<ul class="merlist clearfix" id="recommend">
		<?php if(!empty($recommend['total'])):foreach($recommend['data'] as $v):?>
			<li>
				<a href="<?=base_url("goods_{$v['id']}.html")?>" target="_blank"><img class="scrollLoading" data-url="<?=$v['thumb']?>" src="/static/images/1px.gif" title="<?=$v['seo_title']?>" style="width:188px;height:188px;" /></a>
				<h2><a href="<?=base_url("goods_{$v['id']}.html")?>" target="_blank"><?=$v['title']?></a></h2>
				<p class="txt"><a href="<?=base_url("goods_{$v['id']}.html")?>" target="_blank"><?php echo $v['seo_title'] ? $v['seo_title'] : '&nbsp;&nbsp;'; ?></a></p>
				<p class="prices"><em>￥<?=$v['price']?></em>&nbsp;&nbsp;（<?=$v['spec']?>）</p>
			</li>
		<?php endforeach;endif;?>
		</ul>
	</div>
	<div class="hislist">
		<div class="hisbtn">
			<span>
			<?php for($i=1;$i<=ceil($recommend['total']/6);$i++):?>
				<em id="switch_recommend_<?=$i?>" class="recommend <?php if($i==1):?>cur<?php endif;?>" onclick="switchRecommend(<?=$i?>)"></em>
			<?php endfor;?>
			</span>
		</div>
		<div class="details">商品详情 DETAILS</div>
		<div class="clearfix content"><?=$data['content']?></div>
	</div>
</div>
<div class="commentlist area" id="comments">
	<div class="comment_t">商品评论 COMMENT</div>
	<div id="comments_list">
	<?php if(!empty($comment['total'])):foreach($comment['data'] as $v):?>
		<dl class="clearfix">
			<dt><img src="<?=getAvatar($v['uid'])?>" width="65" height="65" /></dt>
			<dd>
				<p class="username"><span><?=$v['username']?></span></p>
				<p class="usercomlist"><?=$v['content']?></p>
				<p class="userctime"><?=date('Y-m-d H:i',$v['create_time'])?></p>
			</dd>
		</dl>
	<?php endforeach;endif;?>
	</div>
<?php if(!empty($comment['total'])):?>
	<div class="mypage">
		<a href="#comments" onclick="getComment(1)">首页</a>
	<?php for($i=1;$i<=ceil($comment['total']/5);$i++):?>
		<a href="#comments" id="comment_page_<?=$i?>" onclick="getComment(<?=$i?>)" class="page_number <?php if($i==1):?>cur<?php endif;?>"><?=$i?></a>
	<?php endfor;?>
		<a href="#comments" onclick="getComment(<?=ceil($comment['total']/5)?>)">末页</a>
	</div>
<?php endif;?>
</div>
<?php $this->load->view('common/footer')?>
<script src="<?=STATICURL?>js/jquery.carouFredSel-6.2.1-packed.js"></script>
<script src="<?=STATICURL?>js/jquery.scrollLoading-min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#thumb_list').carouFredSel({
		auto: false,
		prev: '#thumb_prev',
		next: '#thumb_next',
		direction: 'down'
	});
	//延迟加载
	$(".scrollLoading").scrollLoading();
});
//切换图片
function showImage(image){
	$("#curr_image").attr("src", image);
}
//更改数量
function updCart(action){
	var buy_number = $("input[name='buy_number']").val();
	buy_number = isNaN(parseInt(buy_number)) ? 1 : Math.max(1, parseInt(buy_number));
	if(action == "inc"){
		buy_number += 1;
	}else if(action == "dec"){
		buy_number -= 1;
		buy_number = Math.max(1, buy_number);
	}
	$("input[name='buy_number']").val(buy_number);
}
//切换推荐商品
function switchRecommend(page){
	var product_id = <?=$data['id']?>;
	$.ajax({
		url: "<?=base_url('goods/ajax_recommend')?>",
		type: "post",
		dataType: "json",
		data: {"product_id":product_id, "page":page},
		success: function(data){
			if(data.err_no == 0){
				$(".recommend").removeClass("cur");
				$("#switch_recommend_"+page).addClass("cur");
				var goods = "";
				$.each(data.results, function(k, v){
					goods += '<li><a href="<?=base_url("goods/detail")?>/'+v.id+'" target="_blank"><img src="'+v.thumb+'" title="'+v.seo_title+'" style="width:188px;height:188px;" /></a><h2><a href="<?=base_url("goods/detail")?>/'+v.id+'" target="_blank">'+v.title+'</a></h2><p class="txt"><a href="<?=base_url("goods/detail")?>/'+v.id+'" target="_blank">'+v.seo_title+'</a></p><p class="prices"><em>￥'+v.price+'</em>&nbsp;&nbsp;（'+v.spec+'）</p></li>';
				});
				$("#recommend").html(goods);
			}
		}
	});
}
//获取评论
function getComment(page){
	var product_id = <?=$data['id']?>;
	$.ajax({
		url: "<?=base_url('comment/ajax_get')?>",
		type: "post",
		dataType: "json",
		data: {"product_id":product_id, "page":page},
		success: function(data){
			if(data.err_no == 0){
				$(".page_number").removeClass("cur");
				$("#comment_page_"+page).addClass("cur");
				var comment = "";
				$.each(data.results, function(k, v){
					comment += '<dl class="clearfix"><dt><img src="'+v.header+'" width="65" height="65" /></dt><dd><p class="username"><span>'+v.username+'</span></p><p class="usercomlist">'+v.content+'</p><p class="userctime">'+v.time+'</p></dd></dl>';
				});
				$("#comments_list").html(comment);
			}
		}
	});
}
//加入购物车
function addCart(){
	var product_id = <?=$data['id']?>;
	var buy_number = $("input[name='buy_number']").val();
	var n_pattern = /^[1-9][0-9]*$/;
	if( ! n_pattern.test(buy_number)){
		layer.msg("购买数量不正确！", 2, 5);
		$("input[name='buy_number']").val(1);
		return false;
	}
	layer.load("提交中...");
	$.ajax({
		url: "<?=base_url('cart/manage/add')?>",
		type: "post",
		dataType: "json",
		data: {"product_id":product_id, "amount":buy_number},
		success: function(data){
			if(data.err_no == 0){
				$("#cart_number").html(data.results.total_number);
				$.layer({
					shade: [0.3, '#000'],
					area: ['auto','auto'],
					dialog: {
						msg: '添加到购物车成功！',
						type: 1,
						btns: 2,
						btn: ['购物车', '继续购物'],
						yes: function(){
							location.href = "<?=base_url('cart/index')?>";
						}, no: function(){
							// location.href = "<?=base_url()?>";
						}
					}
				});
			}else if(data.err_no == 1003){
				layer.msg("该商品已下架！", 2, 5, function(){ location.reload();});
			}else if(data.err_no == 1004){
				layer.msg("该商品已库存不足！", 2, 5);
			}else{
				layer.msg("添加到购物车失败！", 2, 5);
			}
		}
	});
}
//加入收藏
function addFavorite(){
	var product_id = <?=$data['id']?>;
	layer.load("提交中...");
	$.ajax({
		url: "<?=base_url('member/favorite/ajax_add')?>",
		type: "post",
		dataType: "json",
		data: {"product_id":product_id},
		success: function(data){
			if(data.err_no == 0){
				layer.msg("添加收藏成功！", 2, 1);
			}else if(data.err_no == 1010){
				layer.confirm("请先登录再进行添加收藏！", function(){
					location.href = "<?=base_url('member/user/login')?>?referer=<?=current_url()?>";
				});
			}else if(data.err_no == 1004){
				layer.alert("该商品已经添加到收藏，请勿重复收藏！", 5);
			}else{
				layer.msg("添加收藏失败！", 2, 5);
			}
		}
	});
}
</script>
<script type="text/javascript">
window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"0","bdSize":"16"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];
</script>
</body>
</html>