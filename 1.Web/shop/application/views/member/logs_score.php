<?php
/**
 * 积分记录
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
<title>积分记录 - 个人中心 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/myhome.css"/>
</head>
<body>
<?php $this->load->view('common/header')?>
<div class="breadcrumb area">
	<a href="<?=base_url()?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url('member/home/index')?>">个人中心</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;积分记录
</div>
<div class="Ucenter mt20 area">
	<?php $this->load->view('member/menu')?>
	<div class="mylist">
		<div class="favorite">
			<div class="title">积分记录</div>
			<div class="flist mt10">
				<table>
					<tr><th>ID</th><th>变动积分</th><th>当前积分</th><th>备注信息</th><th>创建时间</th></tr>
				<?php if(!empty($data)):foreach($data as $v):?>
					<tr id="logs_<?=$v['id']?>">
						<td><?=$v['id']?></td>
						<td><?=$v['change']?></td>
						<td><?=$v['curr']?></td>
						<td><?=$v['reason']?></td>
						<td><?=date('Y-m-d H:i',$v['create_time'])?></td>
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