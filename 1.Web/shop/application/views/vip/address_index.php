<?php
/**
 * VIP收货地址
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-27
 */
defined('BASEPATH') || exit('Access denied');
?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title>地址管理 - 个人中心 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/myhome.css"/>
</head>
<body>
<?php $this->load->view('common/vip_header')?>
<div class="breadcrumb area">
	<a href="<?=base_url()?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url('vip/home/index')?>">个人中心</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;地址管理
</div>
<div class="Ucenter mt20 area">
	<?php $this->load->view('vip/menu')?>
	<div class="mylist">
		<div class="address">
			<div class="title">地址管理</div>
			<div class="addlist">
				<table>
				<?php if(!empty($data)):foreach($data as $v):?>
					<tr id="address_<?=$v['id']?>">
						<td><?=$v['receiver']?> 收<br><?=$v['prov_name']?>&nbsp;&nbsp;<?=$v['city_name']?>&nbsp;&nbsp;<?=$v['district_name']?>&nbsp;&nbsp;<?=$v['address']?><br><?=$v['mobile']?></td>
						<td class="w180">
							<div class="edit">
								<p>
									<a href="javascript:;" onclick="updAddress(<?=$v['id']?>)">编辑</a>&nbsp;&nbsp;
									<a href="javascript:;" onclick="delAddress(<?=$v['id']?>)">删除</a>
								</p>
								<p class="set_default_button" id="set_default_button_<?=$v['id']?>" data="<?=$v['id']?>">
									<?php if($v['is_default']==1):?>当前默认地址<?php else:?><a href="javascript:;" class="btn" onclick="setDefault(<?=$v['id']?>)">设为默认</a><?php endif;?>
								</p>
							</div>
						</td>
					</tr>
				<?php endforeach;endif;?>
				</table>
			</div>
			<div class="title"><a href="javascript:;" id="add_address_button" onclick="addAddress()">添加地址</a></div>
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
	</div>
</div>
<?php $this->load->view('common/footer')?>
<script src="<?=STATICURL?>js/viparea.js"></script>
<script src="<?=STATICURL?>js/verify.js"></script>
<script type="text/javascript">
//添加地址
function addAddress(){
	$("#receiver").val("");
	$("#province_id").val("");
	$("#city_id").val("");
	$("#area_id").val("");
	$("#address").val("");
	$("#zip").val("");
	$("#mobile").val("<?=$vipdata['mobile']?>");
	$("input[name='is_default'][value='1']").prop("checked", true);
	$("#submit").attr("onclick", "doAddAddress()");
	getProvince();
	$("#add_address").show();
}
//取消添加
function cancelAddAddress(){
	$("#add_address").hide();
	$("#add_address_button").attr("onclick","addAddress()").html("添加地址");
}
//执行添加/更新
function doAddAddress(address_id){
	var receiver = $.trim($("#receiver").val());
	var province_id = $("#province_id option:selected").val();
	var city_id = $("#city_id option:selected").val();
	var area_id = $("#area_id option:selected").val();
	var address = $.trim($("#address").val());
	var zip = $.trim($("#zip").val());
	var mobile = $.trim($("#mobile").val());
	var email = $.trim($("#email").val());
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
	if(address_id == undefined){
		//添加
		var action_type = "添加";
		var post_url = "<?=base_url('vip/address/ajax_add')?>";
		var post_data = {"receiver":receiver, "prov":province_id, "city":city_id, "district":area_id, "address":address, "zip":zip, "mobile":mobile, "is_default":is_default};
	}else{
		//更新
		var action_type = "编辑";
		var post_url = "<?=base_url('vip/address/ajax_upd')?>";
		var post_data = {"id":address_id, "receiver":receiver, "prov":province_id, "city":city_id, "district":area_id, "address":address, "zip":zip, "mobile":mobile, "is_default":is_default};
	}
	layer.load("提交中...");
	$.ajax({
		url: post_url,
		type: "post",
		dataType: "json",
		data: post_data,
		success: function(data){
			if(data.err_no == 0){
				layer.msg(action_type+"地址成功！", 2, 1, function(){ location.reload();});
			}else{
				layer.msg(action_type+"地址失败！", 2, 5);
			}
		}
	});
}
//设为默认
function setDefault(address_id){
	layer.load("提交中...");
	$.ajax({
		url: "<?=base_url('vip/address/ajax_set')?>",
		type: "post",
		dataType: "json",
		data: {"address_id":address_id},
		success: function(data){
			if(data.err_no == 0){
				$(".set_default_button").each(function(){
					$(this).html("<a href=\"javascript:;\" class=\"btn\" onclick=\"setDefault("+$(this).attr("data")+")\">设为默认</a>");
				});
				$("#set_default_button_"+address_id).html("当前默认地址");
				layer.msg("设置默认地址成功！", 2, 1);
			}else{
				layer.msg("设置默认地址失败！", 2, 5);
			}
		}
	});
}
//更新地址
function updAddress(address_id){
	$.ajax({
		url: "<?=base_url('vip/address/ajax_get')?>",
		type: "post",
		dataType: "json",
		data: {"address_id":address_id},
		success: function(data){
			$("#add_address_button").removeAttr("onclick").html("编辑地址");
			getProvince(data.prov);
			getCity(data.city);
			getArea(data.district);
			$("#receiver").val(data.receiver);
			$("#address").val(data.address);
			$("#zip").val(data.zip);
			$("#mobile").val(data.mobile);
			$("#email").val(data.email);
			$("input[name='is_default'][value="+data.is_default+"]").prop("checked", true);
			$("#submit").attr("onclick", "doAddAddress("+address_id+")");
			$("#add_address").show();
		}
	});
}
//删除地址
function delAddress(address_id){
	layer.confirm("确定删除？", function(){
		layer.load("提交中...");
		$.ajax({
			url: "<?=base_url('vip/address/ajax_del')?>",
			type: "post",
			dataType: "json",
			data: {"address_id":address_id},
			success: function(data){
				if(data.err_no == 0){
					$("#address_"+address_id).hide();
					layer.msg("删除地址成功！", 2, 1);
				}else{
					layer.msg("删除地址失败！", 2, 5);
				}
			}
		});
	});
}
</script>
</body>
</html>