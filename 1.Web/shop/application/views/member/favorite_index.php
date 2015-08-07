<?php
/**
 * 我的收藏
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-21
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
	<a href="<?=base_url()?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url('member/home/index')?>">个人中心</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;我的收藏
</div>
<div class="Ucenter mt20 area">
	<?php $this->load->view('member/menu')?>
	<div class="mylist">
		<div class="favorite">
			<div class="title">我的收藏</div>
			<div class="flist mt10">
				<table>
					<tr><th>商品名称</th><th>价格</th><th>收藏时间</th><th>操作</th></tr>
				<?php if(!empty($data)):foreach($data as $v):?>
					<tr id="favorite_<?=$v['product_id']?>">
						<td class="tdB">
							<div class="pt">
								<div><a href="<?=base_url("goods/detail/{$v['product_id']}")?>" target="_blank"><img src="<?=$v['thumb']?>" title="<?=$v['title']?>" style="width:100px;height:100px;" /></a></div>
								<h2><a href="<?=base_url("goods/detail/{$v['product_id']}")?>" target="_blank"><?=$v['title']?></a></h2>
							</div>
						</td>
						<td class="tdC"><?=$v['price']?></td>
						<td class="tdC"><?=date('Y-m-d H:i',$v['create_time'])?></td>
						<td class="tdE"><a href="javascript:;" onclick="delFavorite(<?=$v['product_id']?>)">取消收藏</a></td>
					</tr>
				<?php endforeach;endif;?>
				</table>
			</div>
		</div>
		<div class="mypage"><?=$pager?></div>
	</div>
</div>
<?php $this->load->view('common/footer')?>
<script type="text/javascript">
//取消收藏
function delFavorite(product_id){
	layer.confirm("确定取消收藏该商品？", function(){
		layer.load("提交中...");
		$.ajax({
			url: "<?=base_url('member/favorite/ajax_del')?>",
			type: "post",
			dataType: "json",
			data: {"product_id":product_id},
			success: function(data){
				if(data.err_no == 0){
					$("#favorite_"+product_id).hide();
					layer.msg("取消收藏成功！", 2, 1);
					location.reload();
				}else{
					layer.msg("取消收藏失败！", 2, 5);
				}
			}
		});
	});
}
</script>
</body>
</html>