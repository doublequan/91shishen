	<div class="layout index-nav fixed-bottom">
		<ul class="row">
			<li class="col2">
				<a href="<?=$site_url?>" class="<?=( strpos($script[0],'home') !== false ? 'pitch' : 'not-pitch')?>">
					<i class="icon-home"></i><br>
					<span>首页</span>
				</a>
			</li>
			<li class="col2">
				<a href="<?=$site_url.'/page/category_list.php'?>" class="<?=( strpos($script[0],'category') !== false ? 'pitch' : 'not-pitch')?>">
				<i class="icon-cascades"></i><br>
				<span>分类</span>
				</a>
				
			</li>
			<li class="col2">
				<a href="<?=$site_url.'/page/cart.php'?>" class="<?=( strpos($script[0],'cart') !== false ? 'pitch' : 'not-pitch')?>">
					<i class="icon-cart"></i><br>
					<span>购物车</span>
				</a>
			</li>
			<li class="col2">
				<a href="<?=$site_url.'/page/profile.php'?>" class="<?=( strpos($script[0],'my') !== false ? 'pitch' : 'not-pitch')?>">
					<i class="icon-my"></i><br>
					<span>我的</span>
				</a>
			</li>
		</ul>
	</div>

	<div class="footer text-center bg-ivory">
		<ul>
			<li><a href="http://100hl.com"  id="download_app" target="_top"><i class="icon-link"></i>客户端</a></li>
		</ul>
		<p> &copy; <?=$site['subname'] ?> </p>
	</div>
	<script src="<?=$site_url."/assets/js/jquery-2.1.1.min.js"?>"></script>
	<script src="<?=$site_url."/assets/js/jquery.base64.js"?>"></script>
	<script src="<?=$site_url."/assets/js/jquery.cookie.js"?>"></script>
	<script src="<?=$site_url."/assets/js/layer.m.js"?>"></script>
	<script src="<?=$site_url."/assets/js/laytpl.js"?>"></script>
	<?php 
	if(isset($script) && in_array('invite', $script))
	{
		
	}

	?>
	<script src="<?=$site_url."/assets/js/main.js?v=1.1.0"?>"></script>
	<script>
	$(function(){
	<?php

		echo PHP_EOL."hsh.init();";
		if(isset($script))
		{
			foreach($script as $item)
			{
				echo PHP_EOL."hsh.$item();";
			}
		}
		else
		{
			echo PHP_EOL."hsh.home();";
		}
	?>
	});
	</script>
</body>
</html>