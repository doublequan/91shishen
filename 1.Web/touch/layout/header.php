<!doctype html>
<html lang="zh-cn">
	<head>
		<meta charset = "utf-8">
		<title><?=$page['title']?> - <?=$site['subname'] ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, minimal-ui">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
		<meta name="format-detection" content="telphone=no, email=no">
		<link rel="canonical" href="">
		<link rel="dns-prefetch" href="//static.100hl.cn">
		<link rel="stylesheet" href="<?=$site_url."/assets/css/layer.css"?>"/>
        <?php 
        if(isset($script) && in_array('invite', $script))
        {

            echo "<link rel='stylesheet' href='".$site_url."/assets/css/onepage-scroll.css'/>".PHP_EOL;
        }

        ?>
		<link rel="stylesheet" href="<?=$site_url."/assets/css/base.css?v=1.0.5"?>"/>
	</head>
	<body>

	<div class="layout">
		<div class="page-header bg-red">
			
			<div class="row">
				<div class="col1">
				<?php
					switch ($script[0]) {
						case 'login':
							echo <<<End
<a href="javascript:;" data-url="$site_url/page/category_list.php" id="go-path"><i class="icon-list"></i></a>
End;
							break;
						default:	
							echo '<a href="javascript:;" id="go-history"><i class="icon-back"></i></a>'.PHP_EOL;
							break;
					}
					
				?>
				</div>
				<div class="col6">
					<span><?=$page['title'];?></span>
				</div>
				<div class="col1">
				<?php
				switch ($script[0]) {
					case 'category':
						echo <<<End
<a href="javascript:;" data-url="$site_url/page/search.php" id="go-path"><i class="icon-search"></i></a>
End;
						break;
					case 'category_list':
						echo <<<End
<a href="javascript:;" data-url="$site_url/page/search.php" id="go-path"><i class="icon-search"></i></a>
End;
						break;
						
					case 'search':
						echo <<<End
<a href="javascript:;" data-url="$site_url" id="go-path"><i class="icon-home"></i></a>
End;
						break;
						case 'search_list':
						echo <<<End
<a href="javascript:;" data-url="$site_url/page/search.php" id="go-path"><i class="icon-search"></i></a>
End;
						break;
					case 'goods':
						echo <<<End
<a href="javascript:;" data-url="$site_url/page/goods.php" id="add-favor"><i class="icon-favor"></i></a>
End;
					break; 
					case 'my':
						echo <<<End
<a href="javascript:;" data-url="$site_url/page/goods.php" id="do-logout"><i class="icon-forward"></i></a>
End;
					break;

					case 'login':
						echo <<<End
<a href="javascript:;" data-url="$site_url/page/register.php" id="go-path"><i class="icon-edit"></i></a>
End;
					break;

					default:
						echo <<<End
<a href="javascript:;" data-url="$site_url" id="go-path"><i class="icon-home"></i></a>
End;
					break;
				};
				?>
				</div>
			</div>
			
		</div>
	</div>	