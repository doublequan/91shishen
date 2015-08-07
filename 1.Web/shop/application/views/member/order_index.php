<?php
/**
 * 我的订单
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-9
 */
defined('BASEPATH') || exit('Access denied');
?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title>我的订单 - 个人中心 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/myhome.css"/>
</head>
<body>
<?php $this->load->view('common/header')?>
<div class="breadcrumb area">
	<a href="<?=base_url()?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url('member/home/index')?>">个人中心</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;我的订单
</div>
<div class="Ucenter mt20 area">
	<?php $this->load->view('member/menu')?>
	<div class="mylist">
		<div class="ticket">
			<div class="title">我的订单</div>
			<div class="t_tab mt10 clearfix">
				<ul>
					<a href="<?=base_url('member/order/index/all')?>"><li <?php if($type=='all'):?>class="cur"<?php endif;?>>所有订单</li></a>
					<a href="<?=base_url('member/order/index/normal')?>"><li <?php if($type=='normal'):?>class="cur"<?php endif;?>>进行中订单</li></a>
					<a href="<?=base_url('member/order/index/complete')?>"><li <?php if($type=='complete'):?>class="cur"<?php endif;?>>已完成订单</li></a>
					<a href="<?=base_url('member/order/index/comment')?>"><li <?php if($type=='comment'):?>class="cur"<?php endif;?>>待评价订单</li></a>
				</ul>
			</div>
			<div class="sp_list mt10">
				<table>
					<tr><th class="thA">商品</th><th class="thB">实付款（元）</th><th class="thC">订单状态</th><th class="thD">操作</th></tr>
					<?php if(!empty($data)):foreach($data as $v):?>
					<tbody>
						<tr>
							<td colspan="4">
								<div class="dd">
									<span class="t">下单时间：<?=date('Y-m-d H:i:s',$v['create_time'])?></span>订单号：<?=$v['id']?>
								</div>
							</td>
						</tr>
						<tr class="bd">
							<td>
								<div class="tp">
								<?php foreach($v['goods'] as $g):?>
									<a href="<?=base_url("goods/detail/{$g['product_id']}")?>" target="_blank"><img src="<?=$g['thumb']?>" title="<?=$g['product_name']?>" style="width:100px;height:100px;" /></a>
								<?php endforeach;?>
								</div>
							</td>
							<td>￥<?=$v['price']?></td>
							<td id="order_status_<?=$v['id']?>"><?=$v['status_detail']?></td>
							<td class="lh16">
								<a href="<?=base_url("member/order/detail/{$v['id']}")?>">查看详情</a><br />
							<?php if($v['order_status'] == 0 || ($v['order_status'] == 1 && ($v['pay_type'] == 1 || bccomp($v['price'], '0.00', 2) == 0))):?>
								<span id="order_action_<?=$v['id']?>"><a href="javascript:;" onclick="cancelOrder('<?=$v['id']?>')">取消订单</a></span>
							<?php endif;?>
							<?php if($v['order_status'] == 20):?>
								<span><a href="<?=base_url("member/order/detail/{$v['id']}")?>#comments">我要评价</a></span>
							<?php endif;?>
							</td>
						</tr>
					</tbody>
					<?php endforeach;endif;?>
				</table>
			</div>
			<div class="mypage"><?=$pager?></div>
		</div>
	</div>
</div>
<?php $this->load->view('common/footer')?>
<script type="text/javascript">
//取消订单
function cancelOrder(order_id){
	layer.confirm("确定取消？", function(){
		layer.load("提交中...");
		$.ajax({
			url: "<?=base_url('member/order/ajax_cancel')?>",
			type: "post",
			dataType: "json",
			data: {"order_id":order_id},
			success: function(data){
				if(data.err_no == 0){
					$("#order_status_"+order_id).html("已取消");
					$("#order_action_"+order_id).remove();
					layer.msg("取消订单成功！", 2, 1);
				}else{
					layer.msg("取消订单失败！", 2, 5);
				}
			}
		});
	});
}
</script>
</body>
</html>