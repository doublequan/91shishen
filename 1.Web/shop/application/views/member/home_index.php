<?php
/**
 * 个人中心
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
<title>个人中心 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/myhome.css"/>
</head>
<body>
<?php $this->load->view('common/header')?>
<div class="breadcrumb area">
	<a href="<?=base_url()?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;个人中心
</div>
<div class="Ucenter mt20 area">
	<?php $this->load->view('member/menu')?>
	<div class="mylist">
		<div class="personal">
			<table>
				<tr>
					<td>账户余额<br /><b id="balance"><img src="<?=STATICURL?>layer/skin/default/xubox_loading2.gif" width="20" height="20" /></b><br /><a href="<?=base_url('member/logs/money')?>">查看资金记录</a></td>
					<td>我的积分<br /><b><?=$userdata['score']?></b><br /><a href="<?=base_url('member/logs/score')?>">查看积分记录</a></td>
					<td>我的代金券<br /><b><?=$userdata['cash_coupon_number']?></b>&nbsp;张<br /><a href="<?=base_url('member/coupon/index/cash')?>">查看代金券</a></td>
				</tr>
			</table>
		</div>
		<div class="msg mt10">
			消息提示：
			<a href="<?=base_url('member/order/index/normal')?>">进行中订单(<?=$userdata['order_number']?>)</a>
			<a href="<?=base_url('member/message/index')?>">未读消息(<?=$userdata['message_number']?>)</a>
			<a href="<?=base_url('member/order/index/comment')?>">待评价商品(<?=$userdata['comment_number']?>)</a>
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
							<a href="<?=base_url("member/order/detail/{$v['id']}")?>">查看订单</a><br />
							<span id="order_action_<?=$v['id']?>"><?php if($v['order_status']==0):?><a href="javascript:;" onclick="delOrder('<?=$v['id']?>')">取消订单</a><?php endif;?></span>
						</td>
					</tr>
				</tbody>
				<?php endforeach;endif;?>
			</table>
		</div>
		<div class="mypage"><?=$pager?></div>
		<div class="myhistory">
			<div class="title">浏览记录</div>
			<div class="history">
				<a class="prev" href="javascript:;" id="thumb_prev"><em>上一页</em></a>
				<a class="next" href="javascript:;" id="thumb_next"><em>下一页</em></a>
				<div class="sbox" style="width:900px;overflow:hidden;">
					<ul id="thumb_list">
					<?php if(!empty($view)):foreach($view as $v):?>
						<li>
							<a href="<?=base_url("goods_{$v['product_id']}.html")?>" target="_blank"><img src="<?=$v['thumb']?>" title="<?=$v['seo_title']?>" style="width:168px;height:168px;" /></a>
							<h2><a href="<?=base_url("goods_{$v['product_id']}.html")?>" target="_blank"><?=$v['title']?></a></h2><p><em>￥<?=$v['price']?></em>&nbsp;&nbsp;（<?=$v['spec']?>）</p>
						</li>
					<?php endforeach;endif;?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('common/footer')?>
<script type="text/javascript" src="<?=STATICURL?>js/jquery.carouFredSel-6.2.1-packed.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	getBalance();
	$('#thumb_list').carouFredSel({
		auto: false,
		prev: '#thumb_prev',
		next: '#thumb_next',
	});
});
//更新余额
function getBalance(){
	$.ajax({
			url: "<?=base_url('member/home/ajax_balance')?>",
			type: "post",
			dataType: "json",
			success: function(data){
				$("#balance").html("￥"+data.results);
			}
		});
}
//取消订单
function delOrder(order_id){
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
					$("#order_action_"+order_id).hide();
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