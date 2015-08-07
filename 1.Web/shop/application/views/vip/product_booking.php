<?php
/**
 * 商品定制
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
<title>商品定制 - 个人中心 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/vip.css"/>
</head>
<body>
<?php $this->load->view('common/vip_header')?>
<div class="breadcrumb area">
	<a href="<?=base_url()?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url('vip/home/index')?>">个人中心</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;商品定制
</div>
<div class="vip_dz area">
	<div class="dz_tip">我们根据客户需求提供代购服务！（请填写需求商品信息）</div>
	<div class="sp_list mt10 vipdz">
		<table>
			<tr>
				<th class="thA">序号</th>
				<th class="thB">商品名称</th>
				<th class="thC">数量</th>
				<th class="thD">备注（特殊要求）</th>
			</tr>
			<tr>
				<td>1</td>
				<td><input class="tpA" type="text" name="product_name[]" /></td>
				<td><input class="tpB" type="text" name="book_number[]" onchange="checkNumber($(this))" /></td>
				<td><input class="tpC" type="text" name="note[]" /></td>
			</tr>
			<tr>
				<td>2</td>
				<td><input class="tpA" type="text" name="product_name[]" /></td>
				<td><input class="tpB" type="text" name="book_number[]" onchange="checkNumber($(this))" /></td>
				<td><input class="tpC" type="text" name="note[]" /></td>
			</tr>
			<tr>
				<td>3</td>
				<td><input class="tpA" type="text" name="product_name[]" /></td>
				<td><input class="tpB" type="text" name="book_number[]" onchange="checkNumber($(this))" /></td>
				<td><input class="tpC" type="text" name="note[]" /></td>
			</tr>
			<tr>
				<td>4</td>
				<td><input class="tpA" type="text" name="product_name[]" /></td>
				<td><input class="tpB" type="text" name="book_number[]" onchange="checkNumber($(this))" /></td>
				<td><input class="tpC" type="text" name="note[]" /></td>
			</tr>
			<tr>
				<td>5</td>
				<td><input class="tpA" type="text" name="product_name[]" /></td>
				<td><input class="tpB" type="text" name="book_number[]" onchange="checkNumber($(this))" /></td>
				<td><input class="tpC" type="text" name="note[]" /></td>
			</tr>
		</table>
	</div>
	<div class="submit">
		<input class="tpb" type="button" onclick="doBook()" value="确定">
		<input class="tpb2" type="button" onclick="goBack()" value="返回">
	</div>
  </div>
<?php $this->load->view('common/footer')?>
<script type="text/javascript">
var hash_token = "<?=$hash_token?>";
var product_name = new Array();
var book_number = new Array();
var note = new Array();
//提交定制
function doBook(){
	$("input[name='product_name[]']").each(function(){
		product_name.push($.trim($(this).val()));
	});
	$("input[name='book_number[]']").each(function(){
		book_number.push($.trim($(this).val()));
	});
	$("input[name='note[]']").each(function(){
		note.push($.trim($(this).val()));
	});
	layer.load("提交中...");
	$.ajax({
		url: "<?=base_url('vip/product/ajax_book')?>",
		type: "post",
		dataType: "json",
		data: {"product_name":product_name, "book_number":book_number, "note":note, "hash_token":hash_token},
		success: function(data){
			if(data.err_no == 0){
				layer.msg("提交成功！", 2, 1, function(){ location.href = "<?=base_url('vip/product/booklog')?>";});
			}else{
				layer.msg("提交失败请重试！", 2, 5);
			}
		}
	});
}
//检查数量
function checkNumber(Obj){
	var book_number = Obj.val();
	book_number = isNaN(parseInt(book_number)) ? 1 : Math.max(1, parseInt(book_number));
	Obj.val(book_number);
}
//返回
function goBack(){
	location.href = "<?=base_url('vip/home/index')?>";
}
</script>
</body>
</html>