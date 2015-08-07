<?php
/**
 * 定制记录
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-30
 */
defined('BASEPATH') || exit('Access denied');
?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title>定制记录 - 个人中心 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/vip.css"/>
</head>
<body>
<?php $this->load->view('common/vip_header')?>
<div class="breadcrumb area">
	<a href="<?=base_url()?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url('vip/home/index')?>">个人中心</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;定制记录
</div>
<div class="vip_dz area">
	<div class="dzhistory">
		<table>
			<tr>
				<th>记录ID / 定制时间</th>
				<th>商品名称</th>
				<th>数量（份）</th>
				<th>备注信息</th>
			</tr>
		<?php if(!empty($data)):foreach($data as $v):?>
			<?php if(!empty($v['detail'])):$k=1;foreach($v['detail'] as $v2):?>
				<?php if($k==1):?>
				<tr>
					<td rowspan="<?=count($v['detail'])?>"><?=$v['id']?><br /><?=date('Y-m-d H:i',$v['create_time'])?></td>
					<td><?=$v2['name']?></td>
					<td><?=$v2['amount']?></td>
					<td><?=$v2['note']?></td>
				</tr>
				<?php else:?>
				<tr>
					<td><?=$v2['name']?></td>
					<td><?=$v2['amount']?></td>
					<td><?=$v2['note']?></td>
				</tr>
				<?php endif;?>
			<?php $k++;endforeach;endif;?>
		<?php endforeach;endif;?>
		</table>
	</div>
	<div class="mypage"><?=$pager?></div>
  </div>
<?php $this->load->view('common/footer')?>
</body>
</html>