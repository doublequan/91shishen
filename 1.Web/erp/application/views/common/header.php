<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>惠生活ERP管理系统</title>
		<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
		<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">
		
		<script src="<?php echo base_url('static/js/jquery.min.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo base_url('static/js/bootstrap.min.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo base_url('static/js/common.js'); ?>" type="text/javascript"></script>
		<link rel="shortcut icon" href="<?php echo FILE_DOMAIN ?>static/favicon.ico" />
	</head>
	<body>
		<nav class="navbar navbar-default navbar-fixed-top layout_header" role="navigation">
		  	<div class="container-fluid">
			    <div class="navbar-header">
			    	<a class="navbar-brand" href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-fire"></span> 惠生活ERP</a>
			    </div>

			    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			      	<ul class="nav navbar-nav">
			        	<li <?php if(@$active_top_tag=='product') echo 'class="active"'; ?>><a href="<?php echo site_url('products/product'); ?>">商品管理</a></li>
			        	<li <?php if(@$active_top_tag=='vip') echo 'class="active"'; ?>><a href="<?php echo site_url('vip/product'); ?>">大客户管理</a></li>
			        	<li <?php if(@$active_top_tag=='user') echo 'class="active"'; ?>><a href="<?php echo site_url('user/user'); ?>">用户管理</a></li>
			        	<li <?php if(@$active_top_tag=='order') echo 'class="active"'; ?>><a href="<?php echo site_url('order/order'); ?>">订单管理</a></li>
			        	<li <?php if(@$active_top_tag=='dispatch') echo 'class="active"'; ?>><a href="<?php echo site_url('dispatch/index'); ?>">调度管理</a></li>
			        	<li <?php if(@$active_top_tag=='enterprise') echo 'class="active"'; ?>><a href="<?php echo site_url('enterprise/company'); ?>">企业管理</a></li>
			        	<li <?php if(@$active_top_tag=='archive') echo 'class="active"'; ?>><a href="<?php echo site_url('archive/archive'); ?>">内容管理</a></li>
			        	<li <?php if(@$active_top_tag=='stat') echo 'class="active"'; ?>><a href="<?php echo site_url('stat/user/amount'); ?>">数据统计</a></li>
			        	<li <?php if(@$active_top_tag=='system') echo 'class="active"'; ?>><a href="<?php echo site_url('system/system'); ?>">系统设置</a></li>
			      	</ul>
			      	<ul class="nav navbar-nav navbar-right">
			      		<li>
			        		<a href="http://www.100hl.com" target="_blank">商城首页</a>
			        	</li>
			        	<li>
			        		<a href="<?php echo site_url('my/info'); ?>">欢迎你，<?php echo $user['username']; ?></a>
			        	</li>
			        	<li>
			        		<a href="<?php echo site_url('home/task'); ?>">待办事项 <span class="badge"><?php echo $taskCount; ?></span></a>
			        	</li>
			        	<li>
			        		<a href="<?php echo site_url('auth/logout'); ?>">注销</a>
			        	</li>
			      	</ul>
			    </div>
		  	</div>
		</nav>
		<div class="layout_leftnav">
			<?php 
			if(!empty($active_top_tag)){
				require($active_top_tag.'_menu.php');
			}
			else{
				require('menu.php');
			}
			?>
		</div>
		<!-- Modal -->
		<div class="modal" id="loading_window" tabindex="-1" role="dialog" 
			aria-labelledby="loadingLabel" aria-hidden="true" data-backdrop="static">
		    <div class="modal-dialog" style="width:305px;">
		        <div class="modal-content">
			        <div class="modal-header">
			        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			            <h4 class="modal-title" id="loadingLabel"><b>处理中，请稍候...</b></h4>
			        </div>
			        <div class="modal-body" style="padding:0;">
			            <img class="img-circle" style="width:300px;background-color:transparent;" 
			            	src="<?php echo base_url('static/images/big_load.gif'); ?>">
			        </div>
		        </div>
		    </div>
		</div>
		<div class="layout_rightmain">
			<div class="inner">
				<div class="container-fluid">