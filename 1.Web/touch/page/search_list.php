<?php
require_once('../config.php'); 
	 $site_url = $site['url'];
$script = array('search_list');
$page['title'] = '搜索结果';
if($_GET)
{
	$words =  urldecode($_REQUEST['words']);
	$page_num = isset($_REQUEST['page'])? $_REQUEST['page'] : 1 ;

	if(!isset($words) || trim($words) == "")
	{
		header("Location: $site_url./page/404.php");
	}
}
else
{
	header("Location: $site_url./page/404.php");
}

include '../layout/header.php'; 
?>

<div class="search_list" data-search-words="<?=$words?>" data-page-num="<?=$page_num?>">
	<div class="box" id="search_list-box">
		
	</div>
	<div id="pager-box" class="pager">
		
	</div>
	<script type="text/html" id="search_list-tpl">
		{{# if(d.length != 0) { }}
			<ul class="category-lump">
			{{# for (var i in d) { }}
			<li class="clearfix">
			<div class="goods-thumb f-left">
				<div class="imgurl">
					<a href="javascript:;" data-id="{{ d[i].id }}" data-name="{{ d[i].title }}" data-mold="goods"><img src="{{ d[i].thumb }}" alt="title"></a>
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
		{{# } else { }}

			<div class="well">
				很抱歉！未找到为：“<label class="text-red"><?=$words?></label>” 的商品！
			</div>
			<div class="return-btn">
			<a class="btn btn-blue padding" href="<?=$site_url .'/page/search.php'?>"><i class="icon-back"></i>继续查找</a>
			</div>
		{{# } }}
	</script>
	<script type="text/html" id="pager-tpl" >
	{{# if(d.length != 0) { }}
		<ul class="clearfix">
			{{# if(<?=$page_num?> !== 1){ }}
			<li><a href="javascript:;" data-search-words="<?=$words?>" data-page-num="1">首页</a></li>
			<li><a href="javascript:;" data-search-words="<?=$words?>" data-page-num="{{ d.prev }}">上一页</a></li>
			{{# } }}
			<!--
			{{# for(var x in d.pageArray) { }}
				{{# if(d.pageArray[x] === <?=$page_num?>) { }}
			<li class="active"><a href="javascript:;"  data-search-words="<?=$words?>" data-page-num="{{ d.pageArray[x] }}">{{ d.pageArray[x] }}</a></li>
				{{# } else { }}
			<li><a href="javascript:;" data-search-words="<?=$words?>" data-page-num="{{ d.pageArray[x] }}">{{ d.pageArray[x] }}</a></li>
				{{# } }}
			{{# } }}

			-->
			{{# if(<?=$page_num?> !== d.pages) { }}
			<li><a href="javascript:;" data-search-words="<?=$words?>" data-page-num="{{ d.next }}">下一页</a></li>
			<li><a href="javascript:;" data-search-words="<?=$words?>" data-page-num="{{ d.pages }}">最后</a></li>
			{{# } }}
		</ul>
	{{# } }}
	</script>
</div>

<?php include '../layout/footer.php';?>