<?php defined('BASEPATH') || exit('Access denied'); ?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title><?php echo $single['name']; ?> - <?php echo $siteName; ?></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link rel="stylesheet" href="<?php echo STATICURL; ?>css/reset.css"/>
<link rel="stylesheet" href="<?php echo STATICURL; ?>css/myhome.css"/>
<link rel="stylesheet" href="<?php echo STATICURL; ?>css/special.css"/>
<style type="text/css">
	.detail .buy,.san{font-size:18px;}
	a{cursor:pointer;}
	.detail img{ display:block; width:290px; height:290px;}
	.container{ width:1208px; margin:20px auto; overflow:hidden;}
	.detail{ width:290px; height:450px; border:1px solid #d1d1d1; float:left; margin-bottom:10px; margin-right:10px;}
	.line{ background-image:url(imgs/line.png); background-position:bottom; width:290px; height:5px;}
	.word{font-size:16px; width:288px; line-height:30px;height:60px; text-align:center;overflow:hidden;}
	.word,.white,.san span{ color:#120000;}
	.buy{ overflow:hidden;}
	.red,.yellow{line-height:50px; float:left; text-align:center; color:white;}
	.red{ background-color:#b9000f; width:180px;}
	.yellow{ background-color:#ffa83b; width:110px;}
	.white{ width:56px; line-height:30px; background-color:white; margin-left:8px; margin-top:10px; text-align:center; }
	.white,.price{ float:left;}
	.you{ font-size:14px; color:#8f7d7b; margin-left:10px; margin-top:12px; float:left;}
	.san{ float:right; margin-right:10px; margin-top:10px;  text-decoration:line-through; color:red; }
</style>
</style>
</head>
<body>
<?php $this->load->view('common/header')?>
<div class="NN_banner area">
	<a href="javascript:void(0);">
    	<img src="<?php echo $single['banner_web']; ?>" width="1190" />
    </a>
</div>
<div class="container">
<?php if( $products ){ ?>

<?php foreach( $products as $row ){ ?>
    <div class="detail">
        <a href="<?php echo base_url("goods_{$row['id']}.html"); ?>" target="_black"><img data-url="<?php echo $row['thumb']; ?>" src="<?php echo $row['thumb']; ?>"></a>
        <div class="line"></div>
        <div class="word"><?php echo $row['spec']; ?></div>
        <div class="buy">
            <div class="red">
                <div class="white">组合价</div>
                <div class="price">&nbsp;: ￥<?php echo $row['price']; ?></div>
            </div>
            <a href="<?php echo base_url("goods_{$row['id']}.html"); ?>" target="_black" class="yellow">立即抢购</a>
        </div> 
        <div>
            <div class="you"><?php echo $row['title']; ?></div>
            <div class="san"><span>食材总价：￥<?php echo $row['price_market']; ?></span></div>
        </div>
    </div>   
<?php } ?>

<?php } ?>
</div>
<?php $this->load->view('common/footer')?>

<script src="<?php echo STATICURL; ?>js/jquery.scrollLoading-min.js"></script>
<script type="text/javascript">
function addCart( product_id ){
	var buy_number = 1;
	layer.load("提交中...");
	$.ajax({
		url: "<?php echo base_url('cart/manage/add'); ?>",
		type: "post",
		dataType: "json",
		data: {"product_id":product_id, "amount":buy_number},
		success: function(data){
			if(data.err_no == 0){
				$("#cart_number").html(data.results.total_number);
				//layer.msg("添加到购物车成功！", 2, 1);
				$.layer({
					shade: [0.3, '#000'],
					area: ['auto','auto'],
					dialog: {
						msg: '添加到购物车成功！',
						type: 1,
						btns: 2,
						btn: ['购物车', '继续购物'],
						yes: function(){
							location.href = "<?php echo base_url('cart/index'); ?>";
						}, no: function(){
							//location.href = "<?=base_url()?>";
						}
					}
				});
			}else{
				layer.msg("添加到购物车失败！", 2, 5);
			}
		}
	});
}
$(document).ready(function(){
	//延迟加载
	$(".scrollLoading").scrollLoading();
	//加入购物车
	$('.addCart').click( function(){
		var product_id = $(this).attr('product_id');
		var n_pattern = /^[1-9][0-9]*$/;
		if( ! n_pattern.test(product_id)){
			layer.msg("商品参数错误", 2, 5);
		} else {
			addCart(product_id);
		}
		return false;
	});
});
</script>
</body>
</html>