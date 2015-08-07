<!doctype html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8">
		<meta name="robots" content="noindex,nofollow">
		<meta name="renderer" content="webkit">
		<meta http-equiv="Cache-Control" content="no-siteapp" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimal-ui">
		<link rel="shortcut icon" type="image/ico" href="<?=STATICPATH?>images/favicon.ico" />
		<title>PAGE NOT FOUND</title>
		<link rel="stylesheet" href="<?=STATICPATH?>css/bootstrap.min.css"/>
		<link rel="stylesheet" href="<?=STATICPATH?>css/bootstrap-theme.min.css"/>
		<link rel="stylesheet" href="<?=STATICPATH?>css/style.css"/>
	</head>
	<body>
        <div class="container">
            <h1><?php echo $heading; ?></h1>
            <blockquote>
                <?php echo $message; ?>
            </blockquote>
            
        </div>
    <script src="<?=STATICPATH?>js/jquery-1.11.1.min.js"></script>
	<script src="<?=STATICPATH?>js/bootstrap.min.js"></script>
	<script src="<?=STATICPATH?>js/piecon.min.js"></script>
	<?php
		echo "<script src='".STATICPATH."js/script.js'></script>".PHP_EOL;
	?>
	<script>
		 $(function(){

		 	hsh.init();
		 	hsh.error();
		  });
	</script>
	</body>
</html>