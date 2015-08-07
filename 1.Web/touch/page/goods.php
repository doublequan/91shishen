<?php
require_once('../config.php'); 

$site_url = $site['url'];
$script = array('goods');

if($_GET)
{
	$id = $_REQUEST['id'];

	if(!isset($id))
	{
		header("Location: $site_url./page/404.php");
	}
	$page['title'] = "商品详情";
}
else
{
	header("Location: $site_url./page/404.php");
}

include '../layout/header.php'; 
?>

<div class="layout page-content">
	
	<div class="goods clearfix" data-goods-id="<?=$id?>">
	<script type="text/html" id="goods-temp">
	<div class="godos-detail-header clearfix">
		<div class="goods-detail-img">
			<div class="imgurl">
				<img src="{{ d.thumb }}" alt="{{ d.title }}">
			</div>
			<div class="goods-detail-img-desc">
				<span> <label>&yen;{{ d.price }}</label>  <label>{{ d.spec }}</label></span>
			</div>
		</div>

		<div class="goods-detail-desc-box clearfix">
		<div class="goods-detail-desc">
			<h4>{{ d.title }}</h4>
			<ul>
				<li>价格:<label class="text-red">&yen; {{ d.price }}</label></li>
				<li>规格:<label class="text-red">{{ d.spec }}</label></li>
				<li>市场价:<label class="text-red">&yen; {{ d.price_market }}</label></li>
				<li>类型:
						{{# if ( d.type == '0') { }}
								普通商品
						{{# } else if (d.type == '1') { }}
								预售商品
						{{# } else if (d.type == '2') { }}
								团购商品
						{{# } else if (d.type == '3') { }}
								积分商品
						{{# } }}
				</li>
				<li>能否购买:

				{{# if(d.is_stock) { }}
					<label class="text-green">能购买</label>
				{{# } else{ }}
					<label class="text-red">不能购买</label>
				{{# } }}
				</li>
			</ul>
		</div>

		<div class="goods-detail-buy">
			<div class="goods-detail-buy-box clearfix">
			<ul>
				<li><a href="javascript:;" id="sub-amount"><i class="icon-back"></i></a></li>
				<li><input type="number" min="1" id="goods-detail-amount" value="1"></li>
				<li><a href="javascript:;" id="add-amount"><i class="icon-right"></i></a></li>
			</ul>
			</div>
			<br class="clearfix">
			<div class="goods-detail-buy-btn">
				{{# if(d.is_stock) { }}
				<a href="javascript:;" class="btn btn-red padding" id="add-cart" data-goods-id="{{ d.id }}"><i class="icon-goods"></i> 加入购物车</a>
				{{# } else { }}
				<a href="javascript:;" class="btn btn-white pointer padding" id="add-cart" data-goods-id="{{ d.id }}"><i class="icon-goods"></i> 加入购物车</a>
				{{# } }}
			</div>
		</div>

		</div>
		</div>
	<br class="clearfix">
		<div class="goods-detail-content clearfix">
			<div class="goods-detail-content-title">
			<h4> 产品详情 </h4>
			</div>
			<div class="goods-detail-contetn-content">
			{{ d.content }}
			</div>
		</div>
		
	</script>
	<div id="goods-box"></div>
	</div>
</div>

<?php include '../layout/footer.php';?>