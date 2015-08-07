<?php
/**
 * 用户中心
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-26
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
<link rel="stylesheet" href="<?=STATICURL?>css/vip.css"/>
</head>
<body>
<?php $this->load->view('common/vip_header')?>
<div class="breadcrumb area">
	<a href="<?=base_url()?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;个人中心
</div>
<div class="Ucenter mt20 area">
	<?php $this->load->view('vip/menu')?>
	<div class="mylist">
		<div class="vip_tit"><span>上次登录时间：<?=date('Y-m-d H:i',$data['login_time'])?></span>您好，欢迎回来！</div>
		<div class="personal">
			<table>
				<tr>
					<td>预存款余额<br /><b>￥0.00</b></td>
					<td>我的积分<br><b>0</b></td>
					<td>订单交易总数<br /><b>0</b></td>
					<td>总消费额<br /><b>￥0.00</b></td>
				</tr>
			</table>
		</div>
		<div class="sp_list mt10 vip">
			<table>
				<tr><th class="thA">订单编号</th><th class="thB">总金额</th><th class="thC">下单日期</th><th class="thD">订单状态</th><th class="thD">操作</th></tr>
				<?php if(!empty($data['order'])):foreach($data['order'] as $v):?>
				<tr class="bd">
					<td><div class="vip_pid"><?=$v['id']?></div></td>
					<td><div class="vip_money">￥<?=$v['price']?></div></td>
					<td><div class="vip_date"><?=date('Y-m-d H:i',$v['create_time'])?></div></td>
					<td><div class="vip_os" id="order_status_<?=$v['id']?>"><?=$v['status_detail']?></div></td>
					<td class="lh16">
						<a href="<?=base_url("vip/order/detail/{$v['id']}")?>">查看订单</a><br />
						<span id="order_action_<?=$v['id']?>"><?php if($v['order_status']==0):?><a href="javascript:;" onclick="delOrder('<?=$v['id']?>')">取消订单</a><?php endif;?></span>
					</td>
				</tr>
				<?php endforeach;endif;?>
			</table>
		</div>
		<div class="mypage"><?=$pager?></div>
	</div>
</div>
<?php $this->load->view('common/footer')?>
<script type="text/javascript">
//取消订单
function delOrder(order_id){
	layer.confirm("确定取消？", function(){
		layer.load("提交中...");
		$.ajax({
			url: "<?=base_url('vip/order/ajax_del')?>",
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