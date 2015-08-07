<?php
/**
 * 站内消息
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-29
 */
defined('BASEPATH') || exit('Access denied');
?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title>我的收藏 - 个人中心 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/myhome.css"/>
</head>
<body>
<?php $this->load->view('common/header')?>
<div class="breadcrumb area">
	<a href="<?=base_url()?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url('member/home/index')?>">个人中心</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;站内消息
</div>
<div class="Ucenter mt20 area">
	<?php $this->load->view('member/menu')?>
	<div class="mylist">
		<div class="favorite">
			<div class="title">站内消息</div>
			<div class="flist mt10">
				<table>
					<tr><th>标题</th><th>状态</th><th>时间</th><th>操作</th></tr>
				<?php if(!empty($data)):foreach($data as $v):?>
					<tr id="message_<?=$v['id']?>">
						<td class="tdB"><a href="javascript:;" onclick="readMsg(<?=$v['id']?>)"><?=$v['title']?></a></td>
						<td class="tdC" id="status_<?=$v['id']?>"><?php if($v['is_read']==1):?>已读<?php else:?>未读<?php endif;?></td>
						<td class="tdC"><?=date('Y-m-d H:i',$v['create_time'])?></td>
						<td class="tdE"><a href="javascript:;" onclick="delMsg(<?=$v['id']?>)">删除</a></td>
					</tr>
				<?php endforeach;endif;?>
				</table>
			</div>
		</div>
		<div class="mypage"><?=$pager?></div>
	</div>
</div>
<div id="message_content" style="width:300px;padding:20px;font-size:13px;text-align:left;line-height:30px;border:6px solid #999;display:none;"></div>
<?php $this->load->view('common/footer')?>
<script type="text/javascript">
//查看消息
function readMsg(message_id){
	$.ajax({
		url: "<?=base_url('member/message/ajax_get')?>",
		type: "post",
		dataType: "json",
		data: {"message_id":message_id},
		success: function(data){
			if(data.err_no == 0){
				$("#status_"+message_id).html("已读");
				$("#message_content").html(data.results.content);
				$.layer({
					type: 1,
					area: ['auto', 'auto'],
					title: false,
					border: [0],
					page: {dom : '#message_content'}
				});
			}else{
				layer.msg("读取消息失败！", 2, 5);
			}
		}
	});
}
//删除消息
function delMsg(message_id){
	layer.confirm("确定取消？", function(){
		layer.load("提交中...");
		$.ajax({
			url: "<?=base_url('member/message/ajax_del')?>",
			type: "post",
			dataType: "json",
			data: {"message_id":message_id},
			success: function(data){
				if(data.err_no == 0){
					$("#message_"+message_id).hide();
					layer.msg("删除成功！", 2, 1);
				}else{
					layer.msg("删除消息失败！", 2, 5);
				}
			}
		});
	});
}
</script>
</body>
</html>