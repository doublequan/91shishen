<?php
require_once('../config.php'); 
	 $site_url = $site['url'];
$script = array('category');
if($_GET)
{
	$name =  $_REQUEST['name'];
	$id = $_REQUEST['id'];

	$page_num = isset($_REQUEST['page'])? $_REQUEST['page'] : 1 ;

	if(!isset($name) || !isset($id))
	{
		header("Location: $site_url./page/404.php");
	}
	$page['title'] = $name;
}
else
{
	header("Location: $site_url./page/404.php");
}


include '../layout/header.php'; 
?>

<div class="bg-ivory layout page-content">
	
	<div class="category clearfix" data-category-id="<?=$id?>" data-page-num="<?=$page_num?>">
	<script type="text/html" id="category-temp">
		<ul class="category-lump">
		{{# for (var i = 0, len = d.length; i < len; i++ ) { }}
		
			<li class="clearfix">
			<div class="goods-thumb f-left">
				<div class="imgurl">
					<a href="javascript:;" data-id="{{ d[i].id }}" data-name="{{ d[i].title }}" data-mold="goods"><img src="{{ d[i].thumb }}" alt="{{ d[i].title }}"></a>
				</div>
			</div>
				<div class="f-right category-desc">
					<dl>
						<dt><a href="javascript:;" data-id="{{ d[i].id }}" data-name="{{ d[i].title }}" data-mold="goods">{{ d[i].title }}</a></dt>
						<dd>价格: &yen; {{ d[i].price }}</dd>
						<dd>规格: {{ d[i].spec}}</dd>
					</dl>
				</div>
			</li>
		{{# } }}
		</ul>
	</script>
	<div id="category-box" class="box"></div>
	</div>
	<div id="pager-box" class="pager">
		
	</div>
	
	<script type="text/html" id="pager-tpl" >
		<ul class="clearfix">
			{{# if(<?=$page_num?> !== 1){ }}
			<li><a href="javascript:;" data-category-id="<?=$id?>" data-category-name="<?=$name?>" data-page-num="1">首页</a></li>
			<li><a href="javascript:;" data-category-id="<?=$id?>" data-category-name="<?=$name?>" data-page-num="{{ d.prev }}">上一页</a></li>
			{{# } }}
			<!--
			{{# for(var x in d.pageArray) { }}
				{{# if(d.pageArray[x] === <?=$page_num?>) { }}
			<li class="active"><a href="javascript:;"  data-category-id="<?=$id?>" data-category-name="<?=$name?>" data-page-num="{{ d.pageArray[x] }}">{{ d.pageArray[x] }}</a></li>
				{{# } else { }}
			<li><a href="javascript:;" data-category-id="<?=$id?>" data-category-name="<?=$name?>" data-page-num="{{ d.pageArray[x] }}">{{ d.pageArray[x] }}</a></li>
				{{# } }}
			{{# } }}

			-->
			{{# if(<?=$page_num?> !== d.pages) { }}
			<li><a href="javascript:;" data-category-id="<?=$id?>" data-category-name="<?=$name?>" data-page-num="{{ d.next }}">下一页</a></li>
			<li><a href="javascript:;" data-category-id="<?=$id?>" data-category-name="<?=$name?>" data-page-num="{{ d.pages }}">最后</a></li>
			{{# } }}
		</ul>
	</script>
</div>

<?php include '../layout/footer.php';?>