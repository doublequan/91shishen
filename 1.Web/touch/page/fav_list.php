<?php
require_once('../config.php'); 
	 $site_url = $site['url'];
$script = array('fav_list');
$page['title'] = '商品收藏';

$page_num = isset($_REQUEST['page'])? $_REQUEST['page'] : 1 ;

include '../layout/header.php'; 
?>

<div class="fav_list" data-page-num = "<?=$page_num ?>">
	<div class="box" id="fav_list-box">
		
	</div>
	<div id="pager-box" class="pager">
		
	</div>
	<script type="text/html" id="fav_list-tpl">
		{{# if(d.length != 0) { }}
			<ul class="category-lump">
			{{# for (var i in d) { }}
			<li class="clearfix">
			<div class="goods-thumb f-left">
				<div class="imgurl">
					<a href="javascript:;" data-id="{{ d[i].id }}" data-name="{{ d[i].product_name }}" data-mold="goods"><img src="{{ d[i].thumb }}" alt="{{ d[i].product_name }}"></a>
				</div>
			</div>
				<div class="f-right category-desc">
					<dl>
						<dt><a href="javascript:;" data-id="{{ d[i].id }}" data-name="{{ d[i].product_name }}" data-mold="goods">{{ d[i].product_name }}</a></dt>
						<dd>价格: &yen; {{ d[i].price }}</dd>
						<dd style="padding-top:.6em;"><a href="javascript:;" id="del-favor" data-product-id="{{ d[i].id }}" class="btn btn-blue passing-small"><i class="icon-favor"></i>删除收藏 </a></dd>
					</dl>
				</div>
				</li>
			{{# } }}
			</ul>
		{{# } else { }}

			<div class="well">
				您尚未收藏商品！
			</div>
			<div class="return-btn">
			<a class="btn btn-blue padding" href="<?=$site_url .'/page/category_list.php'?>"><i class="icon-back"></i>去收藏</a>
			</div>
		{{# } }}
	</script>
	<script type="text/html" id="pager-tpl" >
	{{# if(d.length != 0) { }}
		<ul class="clearfix">
			{{# if(<?=$page_num?> !== 1){ }}
			<li><a href="javascript:;" data-page-num="1">首页</a></li>
			<li><a href="javascript:;" data-page-num="{{ d.prev }}">上一页</a></li>
			{{# } }}
			<!--
			{{# for(var x in d.pageArray) { }}
				{{# if(d.pageArray[x] === <?=$page_num?>) { }}
			<li class="active"><a href="javascript:;" data-page-num="{{ d.pageArray[x] }}">{{ d.pageArray[x] }}</a></li>
				{{# } else { }}
			<li><a href="javascript:;" data-page-num="{{ d.pageArray[x] }}">{{ d.pageArray[x] }}</a></li>
				{{# } }}
			{{# } }}

			-->
			{{# if(<?=$page_num?> !== d.pages) { }}
			<li><a href="javascript:;" data-page-num="{{ d.next }}">下一页</a></li>
			<li><a href="javascript:;" data-page-num="{{ d.pages }}">最后</a></li>
			{{# } }}
		</ul>
	{{# } }}
	</script>
</div>

<?php include '../layout/footer.php';?>