<?php
require_once('../config.php'); 
	 $site_url = $site['url'];
$script = array('category_list');
$page['title'] = '分类';

include '../layout/header.php'; 
?>

<div class="bg-ivory layout page-content">
	
	<div class="category-list clearfix">
	<script type="text/html" id="category-list-temp">
	
	{{# for(var i = 0, len = d.length; i < len; i++)  { }}
		<div class="category-list-lump">
			<dl>
			<dt class="imgurl"><a href="javascript:;" data-id="{{ d[i].id }}" data-mold="category" data-name="{{ d[i].name }}"><img src="{{ d[i].thumb }}" alt="{{ d[i].name }}"></a></dt>
			<dd><a href="javascript:;" data-id="{{ d[i].id }}" data-mold="category" data-name="{{ d[i].name }}">{{ d[i].name }}</a></dd>
			</dl>
		</div>
	{{# } }}	
	</script>
	<div id="category-list-box" class="box"></div>
	</div>
</div>

<?php include '../layout/footer.php';?>