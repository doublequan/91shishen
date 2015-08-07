<?php
/**
 * The footer.php file of wechat *
 * @copyright Copyright Since 2014 HSH
 * @license ZPL (http://zpl.pub/v1)
 * @author cuiqg <cuiqg@100hl.cn>
 */
defined('BASEPATH') || exit('Access denied');
?>
	<script src="<?=STATICPATH?>js/jquery-1.11.1.min.js"></script>
	<script src="<?=STATICPATH?>js/bootstrap.min.js"></script>
	<script src="<?=STATICPATH?>js/piecon.min.js"></script>
	<?php
		if(isset($page) && in_array('home', $page))
		{

			
		}
		echo "<script src='".STATICPATH."js/script.js'></script>".PHP_EOL;
	?>
	<script>
		 $(function(){
		 	<?php
		 	echo "hsh.init();".PHP_EOL;
		 	foreach($page as $script)
		 	{
		 		echo "hsh.$script();".PHP_EOL;
		 	}
		 	?>
		  });
	</script>
	</body>
</html>