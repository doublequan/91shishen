<?php
/**
 * 代金券
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-10
 */
defined('BASEPATH') || exit('Access denied');
?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title>我的代金券 - 个人中心 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/myhome.css"/>
</head>
<body>
<?php $this->load->view('common/header')?>
<div class="breadcrumb area">
	<a href="<?=base_url()?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url('member/home/index')?>">个人中心</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;我的代金券
</div>
<div class="Ucenter mt20 area">
	<?php $this->load->view('member/menu')?>
	<div class="mylist">
		<div class="ticket">
			<div class="title">我的代金券</div>
			<div class="t_tab mt10 clearfix">
				<ul>
					<a href="<?=base_url('member/coupon/index/cash/all')?>"><li <?php if($type=='all'):?>class="cur"<?php endif;?>>所有代金券</li></a>
					<a href="<?=base_url('member/coupon/index/cash/normal')?>"><li <?php if($type=='normal'):?>class="cur"<?php endif;?>>可使用代金券</li></a>
					<a href="<?=base_url('member/coupon/index/cash/used')?>"><li <?php if($type=='used'):?>class="cur"<?php endif;?>>已使用代金券</li></a>
					<a href="<?=base_url('member/coupon/index/cash/dued')?>"><li <?php if($type=='dued'):?>class="cur"<?php endif;?>>已过期代金券</li></a>
				</ul>
			</div>
			<div class="t_content">
				<div class="contA mt10">
					<table>
						<tr><th>代金券</th><th>面值</th><th>余额</th><th>有效时间</th><th>状况</th><th>操作</th></tr>
					<?php if(!empty($data)):foreach($data as $v):?>
						<tr id="coupon_<?=$v['id']?>">
							<td class="tdA"><?=$v['coupon_code']?></td>
							<td class="tdC">￥<?=$v['coupon_total']?></td>
							<td class="tdE">￥<?=$v['coupon_balance']?></td>
							<td class="tdG">
							<?php if( $v['start'] && $v['end'] ){ ?>
							<?php echo date('Y-m-d H:i',$v['start']); ?> / <?php echo date('Y-m-d H:i',$v['end']); ?>
							<?php } elseif( !$v['start'] && $v['end'] ){ ?>
							<?php echo date('Y-m-d H:i',$v['end']); ?>前有效
							<?php } elseif( $v['start'] && !$v['end'] ){ ?>
							<?php echo date('Y-m-d H:i',$v['start']); ?>起有效
							<?php } elseif( !$v['start'] && !$v['end'] ){ ?>
							不限
							<?php } ?>
							</td>
							<td class="tdE"><?php if($v['coupon_balance']==0):?>已使用<?php else:?>未使用<?php endif;?></td>
							<td class="tdF"><a href="<?=base_url('cart/index')?>" target="_blank">立即使用</a><br><a href="#" target="_blank">使用说明</a></td>
						</tr>
					<?php endforeach;endif;?>
					</table>
					<div class="mypage"><?=$pager?></div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('common/footer')?>
<script type="text/javascript">
//删除
function delCoupon(coupon_id){
	$.ajax({
		url: "<?=base_url('member/coupon/ajax_del_coupon')?>",
		type: "post",
		dataType: "json",
		data: {"coupon_id":coupon_id},
		success: function(data){
			if(data.err_no == 0){
				$("#coupon_"+coupon_id).hide();
				layer.msg("删除代金券成功！", 2, 1);
			}else{
				layer.msg("删除代金券失败！", 2, 5);
			}
		}
	});
}
</script>
</body>
</html>