<?php
require_once('../../config.php'); 

$site_url = $site['url'];
$script = array('special');

$page['title'] = "专题活动";
$special_id = isset($_REQUEST['id'])? $_REQUEST['id']: 0;

include '../../layout/header.php'; 
?>


<div class="layout special-content">
	
    <div class="special" data-special-id="<?=$special_id?>" >
           <div id="special-box"></div>
    </div>
    <script id="special-tpl" type="text/html">
    	<div class="special-img ">
    		<img src="{{ d.banner }}" alt="{{ d.name }}">
    	</div>
    	<div class="special-list clearfix">
    	<ul>
    	{{# for(var x = 0, len = d.products.length; x < len ; x++) { }}
    		<li class="col4">
    		<a href="http://m.100hl.com/page/goods.php?id={{ d.products[x].id }}" title="{{ d.products[x].title }}">
	    		<div class="list-box">
	    			<img src="{{ d.products[x].thumb }}" alt="{{ d.products[x].title }}">
	    			<div title="{{ d.products[x].title }}">{{ d.products[x].title }}</div>
	    		</div>
    		</a> 
    			<!--
    			<dd> 规格: {{ d.products[x].spec }} </dd>
    			<dd><del>市场价:{{ d.products[x].price_market }} </del></dd>
    			<dd>惠生活价: {{ d.products[x].price }} </dd>
    			-->
    		</li>

    	{{# } }}
    	</ul>
    	</div>
    </script>
</div>

<?php include '../../layout/footer.php';?>