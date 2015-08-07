<?php

require_once("../plugin/alipay/alipay.config.php");
require_once("../plugin/alipay/lib/alipay_notify.class.php");

require_once('../config.php'); 
	 $site_url = $site['url'];
$script = array('alipay');
$page['title'] = '支付结果';

include '../layout/header.php';
$out_trade_no = '';
$trade_no = '';
$result='';
$pay_static = 1;
$pay_date = Date("Y-m-d H:i:s");
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyReturn();
if($verify_result) {

	$pay_static = 0;
	//商户订单号
	$out_trade_no = $_GET['out_trade_no'];
	//支付宝交易号
	$trade_no = $_GET['trade_no'];
	//交易状态
	$result = $_GET['result'];
}
else {

	$pay_static = 1;
}
?>


<div data-pay-static="<?=$pay_static?>" data-result="<?=$result?>" data-date="<?=$pay_date?>" data-order-id="<?=$out_trade_no?>" data-teade-no="<?=$trade_no?>" class="alipay">
		
		<div class="alipay-result">
			<div class="well text-red hidden"><i class="icon-roundclosefill"></i> <label>支付失败！</label></div>
			<div class="alipay-hsh">
			 <a id="alipay-go" href="<?=$site_url?>" class="btn btn-blue padding"><i class="icon-home"></i> 返回首页</a>
			</div>
		</div>

</div>

<?php include '../layout/footer.php';?>