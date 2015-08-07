<?php
require_once('../config.php'); 
	 $site_url = $site['url'];
$script = array('cart');
$page['title'] = '购物车';

include '../layout/header.php'; 
?>

<div class="cart">
	<div id="cart-box" class="box"></div>
</div>

<script type="text/html" id="cart-temp">
	
		{{# if (d.length == 0) { }}

		<div class="cart-empty">
				<span>( ⊙ o ⊙ )！购物车是空的！</span>
				<div class="cart-empty-btn">
				<a href="<?=$site_url.'/page/category_list.php'?>" id="go-path"  class="btn btn-red padding"><i class="icon-goods"></i>立即选购！</a>
				</div>
		</div>

		{{# } else { }}
		<div class="cart-list">

		<div class="well"> 尊敬的顾客，本商城的送货费为: “不满 <label id="shipping_freelimit" class="text-red"><?=$site['shipping_freelimit']?></label> 元，需加 <label id="shipping_fee" class="text-green"><?=$site['shipping_fee']?></label> 元”。请知悉！</div>
			<ul>
			{{# for(var x in d.products) { }}
				<li class="clearfix">
					<div class="cart-list-thumb">
						<div class="imgurl">
						<img src="{{ d.products[x].thumb }}" alt="{{ d.products[x].title }}">
						</div>
					</div>
					<div class="cart-list-desc">
						<div class="cart-list-desc-list">
							<dl>
								<dt><a href="javascript:;" data-mold="goods" data-name="{{ d.products[x].title }}" data-id="{{ d.products[x].product_id }}"> {{ d.products[x].title }}</a></dt>
								<dd>价格:<label> &yen; {{ d.products[x].price}}</label></dd>
							</dl>
						</div>
						<div class="cart-list-btn">
							<ul>
								<li><span> <a href="javascript:;" data-product-price="{{ d.products[x].price }}" class="sub-amount"><i class="icon-back"></i></a> <input type="number"  data-product-id="{{ d.products[x].product_id }}" class="goods-amount"  value="{{ d.products[x].amount }}"> <a href="javascript:;" data-product-price="{{ d.products[x].price }}" class="add-amount" ><i class="icon-right"></i></a></span></li>
								<li><a href="javascript:;" class="btn btn-blue padding-small del-product" data-product-id="{{ d.products[x].product_id }}" ><i class="icon-delete"></i>删除</a></li>
							</ul>
						</div>
					</div>
				</li>
			{{# } }}
			</ul>
		</div>
		<div class="cart-buy">
			<div class="cart-buy-amount">
				总件数:<label class="text-gray">{{ d.amount}}</label>件
			</div>
		</div>

		<div class="cart-btn">
			<a href="javascript:;" class="btn btn-red padding" id="firm-order"><i class="icon-recharge"></i>确认订单</a>
		</div>
		{{# } }}
</script>

<?php include '../layout/footer.php';?>