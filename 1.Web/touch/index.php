<?php require_once('config.php'); $script = array('home'); $site_url = $site['url'];?>
<!doctype html>
<html lang="zh-cn">
	<head>
		<meta charset = "utf-8">
		<title><?=$site['name']?> - <?=$site['subname'] ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, minimal-ui">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
		<meta name="format-detection" content="telphone=no, email=no">
		<meta name="apple-itunes-app" content="app-id=939934888">
		<!-- <link rel="canonical" href="webkit"> -->
		<link rel="apple-touch-icon" href="//static.100hl.cn/static/apple-touch-icon.png">
		<link rel="shortcut icon" href="//static.100hl.cn/static/favicon.ico">
		<link rel="dns-prefetch" href="//static.100hl.cn">
		<link rel="stylesheet" href="./assets/css/layer.css"/>
		<link rel="stylesheet" href="./assets/css/base.css?v=1.0.5"/>
	</head>
	<body>
	
	<div class="layout bg-ivory">
			<!--
			<div class="alert bg-red text-center" id="download-box">
				<a href="javascript:;" class="alert-close" id="download-close"><i class="icon-close"></i></a>
				<div>
					<img src="">
					<p>
					<a href="javascript:;" class="btn btn-white" id="download-btn" data-url="http://100hl.com"><i class="icon-pic"></i>下载客户端</a>
					</p>
				</div>
			</div>
			-->
			<div id="banner-box" class="banner"></div>
			<script type="text/html" id="banner-temp">
			{{# for(var i = 0 ,len = d.length; i < len; i++){ }}
				<a href="javascript:;" data-id="{{ d[i].id }}" data-url="{{ d[i].url }}" data-type="{{ d[i].type }}" data-name="{{ d[i].title }}" ><img src="{{ d[i].img }}" alt="{{ d[i].title }}"></a>
			{{# } }}
			</script>
			<div class="header text-center">
				
				<div class="header-box">
				<a href="javascript:;" class="logo">
					<img src="./assets/images/logo.png" alt="<?=$site['name']?>" />
				</a>
					<a href="javascript:;" id="location-btn"><i class="icon-location"></i><label id="location-name">南京</label></a>
				</div>
				<!-- 地址选择容器 -->
				<div id="location-box" class="clearfix">
				</div>
	
				<div class="search">
					<form>
						<div class="input-box text-left line">
							<input type="text" class="col6" name="words"  id="search-words" autocomplete="off" x-webkit-speech x-webkit-grammar="builtin:translate">
							<button type="button" id="btn-search" class="col2"><i class="icon-search"></i></button>

						</div>
					</form>
				</div>
			</div>
	


	<!-- 地址选择模版 -->
	<script type="text/html" id="location-temp">
			<ul>
				{{# for(var i =0, len = d.length; i < len; i++){ }}
				<li>
					<a href="javascript:;" data-id="{{ d[i].id }}" data-name="{{ d[i].name }}"><i class="icon-tag"></i>{{d[i].name }}</a>
				</li>
				{{# } }}
			</ul>
	</script>
	
	<!-- 四宫格 -->
	<div class="market">
		<script type="text/html" id="market-temp">
		{{# for(var i = 0 , len = d.length ; i < len; i ++) { }}
			<div class="market-sub">
				<div class="col4 f-left">
					<div class="imgurl">
						<a href="javascript:;" data-id="{{ d[i].id }}" data-url="{{ d[i].url }}" data-type="{{ d[i].type }}" data-name="{{ d[i].title }}" ><img src="{{ d[i].img }}" alt="{{ d[i].title }}"></a>
					</div>
				</div>
				<div class="f-right col4">
					<div class="market-des">
					<a href="javascript:;" data-id="{{ d[i].id }}" data-url="{{ d[i].url }}" data-type="{{ d[i].type }}" data-name="{{ d[i].title }}" ><h4>{{ d[i].title }}</h4></a>
					<p>{{ d[i].des }}</p>
					</div>
				</div>
			</div>
		{{# } }}
		</script>
		
		<div id="market-box" class="row"></div>
	</div>


	<div class="goods-new clearfix">
		<div class="goods-title">
			<div class="row">
				<div class="col4 text-left"><h4><i class="icon-attention "></i>商品推荐</h4></div>
				<!-- <div class="col4 text-right"><a href="javascript:;">更多 <i class="icon-right"></i></a></div> -->
			</div>
		</div>

		<div id="goods-new-box" class="box"></div>
		<script type="text/html" id="goods-new-temp">
			<ul >
				{{# for (var i = 0 , len = d.length; i < len ; i++ ) { }}
					<li>
						<dl>
							<dt class="imgurl"><a href="javascript:;" data-id="{{ d[i].id }}" data-name="{{ d[i].title }}" data-mold="goods"><img src="{{ d[i].img}}" alt=""></a></dt>
							<dd class="text-center"><a href="javascript:;" data-id="{{ d[i].id }}" data-name="{{ d[i].title }}" data-mold="goods">{{ d[i].title }}</a></dd>
						</dl>
					 </li>
				{{# } }}
			</ul>
		</script>
	</div>
	</div>
<?php include './layout/footer.php' ?>