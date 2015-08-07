<?php
/**
 * 充值记录
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-28
 */
defined('BASEPATH') || exit('Access denied');
?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title>充值记录 - 个人中心 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/myhome.css"/>
</head>
<body>
<?php $this->load->view('common/header')?>
<div class="breadcrumb area">
	<a href="<?=base_url()?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url('member/home/index')?>">个人中心</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;充值记录
</div>
<div class="Ucenter mt20 area">
	<?php $this->load->view('member/menu')?>
	<div class="mylist">
		<div class="favorite">
			<div class="title">充值记录</div>
			<div class="flist mt10">
				<table>
					<tr><th>充值单号</th><th>充值金额</th><th>充值方式</th><th>状态</th><th>创建时间</th><th>完成时间</th><th>操作</th></tr>
				<?php if(!empty($data)):foreach($data as $v):?>
					<tr id="logs_<?=$v['id']?>">
						<td><?=$v['id']?></td>
						<td><?=$v['money']?></td>
						<td>支付宝</td>
						<td><?php if($v['status']==0):?>未完成<?php elseif($v['status']==1):?>充值成功<?php else:?>充值失败<?php endif;?></td>
						<td><?=date('Y-m-d H:i', $v['create_time'])?></td>
						<td><?php if($v['success_time']>0):?><?=date('Y-m-d H:i', $v['success_time'])?><?php else:?>-<?php endif;?></td>
						<td><?php if($v['status']==0):?><a href="<?=base_url("member/payment/check/{$v['id']}")?>">查看</a><?php else:?>-<?php endif;?></td>
					</tr>
				<?php endforeach;endif;?>
				</table>
			</div>
		</div>
		<div class="mypage"><?=$pager?></div>
	</div>
</div>
<?php $this->load->view('common/footer')?>
</body>
</html>