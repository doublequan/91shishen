<?php
	$data['page'] = array('home');
    $data['title'] = "Wechat For HSH";
	$this->load->vars($data);
?>
<?php $this->load->view('common/header');?>
<div class="home">
	<div class="container">
		
		<div class="col-md-8 col-md-offset-2 text-center inner">
			
			<h1 class="white">微信服务台</h1>
			<h2 class="white">你好 “惠生活” ！</h2>
		</div>

		<div class="row">
	<div class="col-md-6 col-md-offset-3 text-center">
	<a class="learn-more-btn" href="http://100hl.com/">惠生活生鲜商城</a>
	</div>
	</div>
	</div>
</div>


<?php $this->load->view('common/footer');?>