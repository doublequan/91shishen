<?php
/**
 * 抵用券
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
<title>我的抵用券 - 个人中心 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/myhome.css"/>
</head>
<body>
<?php $this->load->view('common/header')?>
<div class="breadcrumb area">
	<a href="<?=base_url()?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url('member/home/index')?>">个人中心</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;我的抵用券
</div>
<div class="Ucenter mt20 area">
	<?php $this->load->view('member/menu')?>
	<div class="mylist">
		<div class="ticket">
			<div class="title">我的抵用券</div>
			<div class="t_tab mt10 clearfix">
				<ul>
					<a href="<?=base_url('member/coupon/index/discount/all')?>"><li <?php if($type=='all'):?>class="cur"<?php endif;?>>所有抵用券</li></a>
					<a href="<?=base_url('member/coupon/index/discount/normal')?>"><li <?php if($type=='normal'):?>class="cur"<?php endif;?>>未使用抵用券</li></a>
					<a href="<?=base_url('member/coupon/index/discount/used')?>"><li <?php if($type=='used'):?>class="cur"<?php endif;?>>已使用抵用券</li></a>
					<a href="<?=base_url('member/coupon/index/discount/dued')?>"><li <?php if($type=='dued'):?>class="cur"<?php endif;?>>已过期抵用券</li></a>
				</ul>
			</div>
			<div class="t_content">
				<div class="contA mt10">
					<table>
						<tr><th>抵用券</th><th>面值</th><th>最低订单金额</th><th>有效时间</th><th>状况</th><th>操作</th></tr>
					<?php if(!empty($data)):foreach($data as $v):?>
						<tr id="coupon_<?=$v['id']?>">
							<td class="tdA"><?=$v['coupon_code']?></td>
							<td class="tdC">￥<?=$v['coupon_total']?></td>
							<td class="tdE">￥<?=$v['coupon_limit']?></td>
							<td class="tdG"><?=date('Y-m-d H:i',$v['start'])?><br /><?=date('Y-m-d H:i',$v['end'])?></td>
							<td class="tdE"><?php if($v['status']==1):?>未使用<?php elseif($v['status']==2):?>已使用<?php elseif($v['status']==3):?>已过期<?php endif;?></td>
							<td class="tdF"><a href="<?=base_url('cart/index')?>" target="_blank">立即使用</a><br><a href="javascript:;">使用说明</a></td>
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
</body>
</html>