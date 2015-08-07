<?php
require_once('../config.php'); 
	 $site_url = $site['url'];
$script = array('order');
$page['title'] = '订单列表';

$pay_status = isset($_GET['pay_status']) ? $_GET['pay_status'] : 0;
$page_num = isset($_GET['page']) ? $_GET['page'] : 1;
include '../layout/header.php'; 
?>

<div data-status-id="<?=$pay_status?>" class="order" data-page-num="<?=$page_num?>">
	<div id="order-box" class="order-box">
	</div>
	<div id="pager-box" class="pager"></div>

	<br class="clearfix">
</div>

<script type="text/html" id="order-tpl">
{{# if(d.length > 0) { }}
<ul class="clearfix">
	{{# for(var x in d) { }}
	<li>
		<dl>
			<dt><i class="icon-list "></i>订单编号：<b><a href="<?=$site_url.'/page/order_info.php?order_id='?>{{ d[x].id  }}">{{ d[x].id }}</a></b>
			</dt>
			<dd>订单信息：[金额]：<label class="text-green">{{d[x].price}}</label> 
			[支付方式]：

			{{# if(d[x].pay_type == 1) { }}
				<label class="text-red">货到付款</label>
			{{# } else if(d[x].pay_type == 2){ }}
				<label class="text-red">支付宝</label>
			{{# } else if(d[x].pay_type == 3) { }}
				<label class="text-red">会员卡</label>
			{{# } }}
			</dd>
		</dl>
	</li>
	{{# } }}
</ul>
{{# } else { }}

<div  class="well">当前“状态”下，订单为空！</div>
{{# } }}
</script>
<script type="text/html" id="pager-tpl">

{{# if(d.length != 0) { }}
			<ul class="clearfix">
			{{# if(<?=$page_num?> !== 1){ }}
			<li><a href="javascript:;" data-page-num="1">首页</a></li>
			<li><a href="javascript:;" data-page-num="{{ d.prev }}">上一页</a></li>
			{{# } }}
			<!--
			{{# for(var x in d.pageArray) { }}
				{{# if(d.pageArray[x] === <?=$page_num?>) { }}
			<li class="active"><a href="javascript:;"  data-page-num="{{ d.pageArray[x] }}">{{ d.pageArray[x] }}</a></li>
				{{# } else { }}
			<li><a href="javascript:;" data-page-num="{{ d.pageArray[x] }}">{{ d.pageArray[x] }}</a></li>
				{{# } }}
			{{# } }}

			-->
			{{# if(<?=$page_num?> !== d.pages) { }}
			<li><a href="javascript:;"  data-page-num="{{ d.next }}">下一页</a></li>
			<li><a href="javascript:;"  data-page-num="{{ d.pages }}">最后</a></li>
			{{# } }}
		</ul>
{{# } }}
</script>
<?php include '../layout/footer.php';?>