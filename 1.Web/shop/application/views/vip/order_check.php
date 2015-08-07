<?php
/**
 * 预提交订单
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
<title>购物车结算 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/gwc.css"/>
</head>
<body>
<?php $this->load->view('common/vip_login_header')?>
<div class="zt area">
	<a href="<?=base_url('vip/cart/index')?>"><img src="<?=STATICURL?>images/img26.jpg" /></a>
	<div class="right step2" >
		<div class="bg"><div class="w"></div></div>
		<span class="tp1">查看购物车</span>
		<span class="tp2">填写订单</span>
		<span class="tp3">付款，完成购买</span>
	</div>
</div>
<div class="address mt10 area">
	<div class="t">收货信息</div>
	<ul>
	<?php if(!empty($address)):foreach($address as $v):?>
		<li><label>
			<input type="radio" name="address_id" value="<?=$v['id']?>" id="address_<?=$v['id']?>" <?php if($v['is_default']==1):?>checked<?php endif;?> />
			<?=$v['prov_name']?>&nbsp;&nbsp;<?=$v['city_name']?>&nbsp;&nbsp;<?=$v['district_name']?>&nbsp;&nbsp;<?=$v['address']?>（收货人：<?=$v['receiver']?>&nbsp;&nbsp;手机：<?=$v['mobile']?>&nbsp;&nbsp;邮编：<?=$v['zip']?>）
		</label></li>
	<?php endforeach;endif;?>
	</ul>
	<a href="javascript:;" class="btn" id="add_address_button" onclick="addAddress()">+添加收货地址</a>
	<div class="add" id="add_address" style="display:none;">
		<ul>
			<li><label>收货人姓名：</label><input type="text" class="name" id="receiver" /><span id="tip_receiver" style="display:none;"></span></li>
			<li><label>省市：</label>
				<select id="province_id" onchange="getCity()">
					<option value="">请选择省份</option>
				</select>
				<select id="city_id" onchange="getArea()">
					<option value="">请选择地市</option>
				</select>
				<select id="area_id">
				<option value="">请选择区县</option>
				</select>
				<span id="tip_area" style="display:none;"></span>
			</li>
			<li><label>地址：</label><input type="text" class="dq" id="address" /><span id="tip_address" style="display:none;"></span></li>
			<li><label>邮编：</label><input type="text" class="yb" id="zip" maxlength="6" /><span id="tip_zip" style="display:none;"></span></li>
			<li><label>手机号码：</label><input type="text" class="sj" id="mobile" maxlength="11" /><span id="tip_mobile" style="display:none;"></span></li>
			<li>
				<label>设为默认：</label><input type="radio" name="is_default" style="width:15px;" value="1" />是&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="is_default" style="width:15px;" value="0" />否
			</li>
			<li><input type="submit" id="submit" class="tj" onclick="doAddAddress()" value="提交" /><input type="button" id="cancel" class="tj" style="margin-left:15px;" onclick="cancelAddAddress()" value="取消" /></li>
		</ul>
	</div>
</div>
<div class="sp_list area">
	<div class="t">商品清单</div>
	<table>
		<tr><th class="thA">商品</th><th class="thB">单价</th><th class="thC">数量</th><th class="thD">小计</th><th class="thE">合计</th></tr>
		<?php $i=1;if(!empty($product)):foreach($product as $k=>$v):?>
		<tr class="bd">
			<td><div class="pt clearfix"><?=$v['title']?></div></td>
			<td>￥<?=sprintf('%.2f',$v['price'])?></td>
			<td><?=$v['amount']?></td>
			<td>￥<?=sprintf('%.2f',$v['single_price'])?></td>
			<?php if($i==1):?><td rowspan="<?=count($product)?>">￥<?=sprintf('%.2f',$total_price)?></td><?php endif;?>
		</tr>
		<input type="hidden" name="product_id[]" value="<?=$k?>" />
		<input type="hidden" name="amount[]" value="<?=$v['amount']?>" />
		<?php $i++;endforeach;endif;?>
		<tr><td colspan="5">
			<div class="total2 clearfix">
				<div class="bz">
					<a class="btn" id="add_note_button" href="javascript:;" onclick="addNote()">添加备注信息</a>
					<div class="bzbox" id="add_note" style="display:none;">
						<p>订单备注：</p>
						<textarea name="bz" id="note" name="postscript"></textarea>
					</div>
				</div>
				<div class="right" id="summary_info">商品总价：+￥<?=sprintf('%.2f',$total_price)?></div>
			</div>
		</td></tr>
	</table>
</div>
<div class="sp_list area">
	<div class="sbox">
		<p>订单金额：￥<span id="total_price"><?=sprintf('%.2f',$total_price)?></span>元</p><a class="btn" href="javascript:;" onclick="submitOrder()">提交订单</a>
	</div>
	<div class="tip">
		<p>当日 00:00 - 11:59 之间的订单，次日 11:00 前送达；<br />当日 12:00 - 23:59 之间的订单，次日 18:00 前送达！</p>
	</div>
</div>
<?php $this->load->view('common/footer')?>
<script src="<?=STATICURL?>js/viparea.js"></script>
<script src="<?=STATICURL?>js/verify.js"></script>
<script type="text/javascript">
var hash_token = "<?=$hash_token?>";
var real_price = <?=$total_price?>; //实付总价
var product_id = new Array();
var amount = new Array();
$(document).ready(function(){
	$("input[name='product_id[]']").each(function(){
		product_id.push($(this).val());
	});
	$("input[name='amount[]']").each(function(){
		amount.push($(this).val());
	});
});
//添加备注
function addNote(){
	$("#add_note_button").attr("onclick","cancelAddNote()").html("取消备注信息");
	$("#add_note").show();
	$("#postscript").focus();
}
//取消添加
function cancelAddNote(){
	$("#add_note").hide();
	$("#note").val("");
	$("#add_note_button").attr("onclick","addNote()").html("添加备注信息");
}
//添加地址
function addAddress(){
	$("#receiver").val("");
	$("#province_id").val("");
	$("#city_id").val("");
	$("#area_id").val("");
	$("#address").val("");
	$("#zip").val("");
	$("#mobile").val("<?=$vipdata['mobile']?>");
	$("input[name='is_default'][value='1']").prop("checked", "checked");
	$("#submit").attr("onclick", "doAddAddress()");
	getProvince();
	$("#add_address_button").hide();
	$("#add_address").show();
}
//取消添加
function cancelAddAddress(){
	$("#add_address").hide();
	$("#add_address_button").show();
}
//执行添加
function doAddAddress(){
	var receiver = $.trim($("#receiver").val());
	var province_id = $("#province_id option:selected").val();
	var city_id = $("#city_id option:selected").val();
	var area_id = $("#area_id option:selected").val();
	var address = $.trim($("#address").val());
	var zip = $.trim($("#zip").val());
	var mobile = $.trim($("#mobile").val());
	var is_default = $.trim($("input[name='is_default']:checked").val());
	if(receiver == ""){
		$("#tip_receiver").html("请填写收货人姓名").show();
		$("#receiver").focus();
		return false;
	}else{
		$("#tip_receiver").hide();
	}
	if(province_id == ""){
		$("#tip_area").html("请选择省份").show();
		return false;
	}else if(city_id == ""){
		$("#tip_area").html("请选择地市").show();
		return false;
	}else if(area_id == ""){
		$("#tip_area").html("请选择区县").show();
		return false;
	}else{
		$("#tip_area").hide();
	}
	if(address == ""){
		$("#tip_address").html("请填写详细地址").show();
		$("#address").focus();
		return false;
	}else{
		$("#tip_address").hide();
	}
	if(zip == ""){
		$("#tip_zip").html("请输入收货人所在地邮编").show();
		$("#zip").focus();
		return false;
	}else if( ! isZipcode(zip)){
		$("#tip_zip").html("邮编格式不正确").show();
		$("#zip").focus();
		return false;
	}else{
		$("#tip_zip").hide();
	}
	if(mobile == ""){
		$("#tip_mobile").html("请填写手机号，便于接收发货和收货通知").show();
		$("#mobile").focus();
		return false;
	}else if( ! isMobile(mobile)){
		$("#tip_mobile").html("手机格式不正确").show();
		$("#mobile").focus();
		return false;
	}else{
		$("#tip_mobile").hide();
	}
	layer.load("提交中...");
	$.ajax({
		url: "<?=base_url('vip/address/ajax_add')?>",
		type: "post",
		dataType: "json",
		data: {"receiver":receiver, "prov":province_id, "city":city_id, "district":area_id, "address":address, "zip":zip, "mobile":mobile, "is_default":is_default},
		success: function(data){
			if(data.err_no == 0){
				layer.msg("添加收货地址成功！", 2, 1, function(){ location.reload();});
			}else{
				layer.msg("添加收货地址失败！", 2, 5);
			}
		}
	});
}
//提交订单
function submitOrder(){
	var address_id = $("input[name='address_id']:checked").val();
	if(address_id == undefined){
		layer.alert("请选择该订单的收货地址！", 5);
		return false;
	}
	var note = $.trim($("#note").val());
	layer.load("提交中...");
	$.ajax({
		url: "<?=base_url('vip/order/ajax_submit')?>",
		type: "post",
		dataType: "json",
		data: {"hash_token":hash_token, "address_id":address_id, "product_id":product_id, "amount":amount, "real_price":real_price, "note":note},
		success: function(data){
			if(data.err_no == 0){
				location.href = "<?=base_url('vip/order/success')?>/" + data.results;
			}else{
				layer.msg("提交订单失败请重试！", 2, 5);
			}
		}
	});
}
</script>
</body>
</html>