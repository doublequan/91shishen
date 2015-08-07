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
</style>
</head>
<body>
<?php $this->load->view('common/header')?>
<div class="NN_banner area">
	<a href="javascript:void(0);">
    	<img src="<?php echo $single['banner_web']; ?>" width="1190" />
    </a>
</div>
<?php if( $products ){ ?>
<div class="NN_list area mt20">
	<ul>
		<?php foreach( $products as $row ){ ?>
    	<li class="fl">
        	<a href="<?php echo base_url("goods_{$row['id']}.html"); ?>" target="_black">
        		<img class="scrollLoading" data-url="<?php echo $row['thumb']; ?>" src="/static/images/1px.gif" title="<?php echo $row['seo_title']; ?>" width="288" height="288" />
            </a>         
        	<a href="<?php echo base_url("goods_{$row['id']}.html"); ?>" target="_black">
              <div>
              	<p class="NN_title fl">
                	抢购价：<span>￥</span><?php echo $row['price']; ?>
                </p>
                <span class="nn_link fr addCart" product_id="<?php echo $row['id']; ?>">
                	立即抢购
                </span>
              </div>
            </a>         
        	<a href="<?php echo base_url("goods_{$row['id']}.html"); ?>" target="_black">
              <h3><?php echo $row['title']; ?></h3>
            </a>
        </li>
        <?php } ?>
    </ul>
</div>
<?php } ?>
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