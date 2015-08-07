<?php
require_once('../config.php'); 
	 $site_url = $site['url'];
$script = array('search','shake');
$page['title'] = '搜索';

include '../layout/header.php'; 
?>

<div class="search">
	<div class="search-box">
		
	</div>
	<form>
		<div class="input-box text-left line">
			<input type="text" class="col6" name="words"  id="search-words" autocomplete="off" x-webkit-speech x-webkit-grammar="builtin:translate" value="" placeholder="">
			<button type="button" id="btn-search" class="col2"><i class="icon-search"></i></button>

		</div>
	</form>
</div>
<div class="shake">
	<div class="shake-desc box">
		<label class="text-red">不知道选择什么要不“摇”一下！</label>
	</div>
	<div class="shake-img">
		<div class="imgurl">
			<img src="../assets/images/shake.png" alt="shake">
		</div>
	</div>

</div>
<?php include '../layout/footer.php';?>