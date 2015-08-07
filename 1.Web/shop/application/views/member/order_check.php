<?php
/**
 * 预提交订单
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-5
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
<?php $this->load->view('common/login_header')?>
<div class="zt area">
	<a href="<?=base_url('cart/index')?>"><img src="<?=STATICURL?>images/img26.jpg" /></a>
	<div class="right step2" >
		<div class="bg"><div class="w"></div></div>
		<span class="tp1">查看购物车</span>
		<span class="tp2">填写订单</span>
		<span class="tp3">付款，完成购买</span>
	</div>
</div>
<div class="address mt10 area" id="yourAddress">
	<div class="t">您在 <?php echo $prov ? $prov['name'] : ''; ?> <?php echo $city ? $city['name'] : ''; ?> 的收货地址</div>
	<ul>
	<?php if(!empty($address)):foreach($address as $v):?>
		<li><label>
			<input type="radio" name="address_id" value="<?=$v['id']?>" id="address_<?=$v['id']?>" <?php if($v['is_default']==1):?>checked<?php endif;?> />
			<?=$v['prov_name']?>&nbsp;&nbsp;<?=$v['city_name']?>&nbsp;&nbsp;<?=$v['district_name']?>&nbsp;&nbsp;<?=$v['street_name']?>&nbsp;&nbsp;<?=$v['address']?>（收货人：<?=$v['receiver']?>&nbsp;&nbsp;手机：<?=$v['mobile']?>&nbsp;&nbsp;邮编：<?=$v['zip']?>）
		</label></li>
	<?php endforeach;endif;?>
	</ul>
	<a href="javascript:;" class="btn" id="add_address_button" onclick="addAddress()">+添加收货地址</a>
	<div class="add" id="add_address" style="display:none;">
		<ul>
			<li><label>收货人姓名：</label><input type="text" class="name" id="receiver" /><span id="tip_receiver" style="display:none;"></span></li>
			<li><label>区县街道：</label>
				<strong>
					<?php echo $prov ? $prov['name'] : ''; ?> -
					<?php echo $city ? $city['name'] : ''; ?> -
				</strong>
				<select id="area_id" onchange="getStreets()">
					<option value="0">请选择区县</option>
					<?php if( $district ){ ?>
					<?php foreach( $district as $row ){ ?>
					<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
					<?php } ?>
					<?php } ?>
				</select>
				<select id="street_id" style="display:none;">
				</select>
				<span id="tip_area" style="display:none;"></span>
			</li>
			<li><label>地址：</label><input type="text" class="dq" id="address" /><span id="tip_address" style="display:none;"></span></li>
			<li><label>邮编：</label><input type="text" class="yb" id="zip" maxlength="6" /><span id="tip_zip" style="display:none;"></span></li>
			<li><label>固定电话：</label><input type="text" class="dh" id="tel" /><span id="tip_tel" style="display:none;"></span></li>
			<li><label>手机号码：</label><input type="text" class="sj" id="mobile" maxlength="11" /><span id="tip_mobile" style="display:none;"></span></li>
			<li>
				<label>设为默认：</label><label><input type="radio" name="is_default" style="width:15px;" value="1" />是</label>&nbsp;&nbsp;&nbsp;&nbsp;
				<label><input type="radio" name="is_default" style="width:15px;" value="0" />否</label>
			</li>
			<li><input type="submit" id="submit" class="tj" onclick="doAddAddress()" value="提交" /><input type="button" id="cancel" class="tj" style="margin-left:15px;" onclick="cancelAddAddress()" value="取消" /></li>
		</ul>
	</div>
</div>
<div class="sp_list area">
	<div id="receive_time" class="receive_time">
		<div class="t">配送方式</div>
		<div class="address">
			<ul>
				<li>
					<label><input type="radio" name="delivery_type" checked value="1" onclick="cancelStore()" />惠生活物流</label>
					&nbsp;&nbsp;
					<label><input type="radio" name="delivery_type" value="0" onclick="selectStore()" id="tipStore" />门店自提</label>
				</li>
			<div id="store_list" style="display:none;">
				<li style="height:30px;line-height:30px;">
					联系人：<input type="text" id="my_name" style="border:1px solid #d4d4d4;height:22px;width:150px;text-align:center;line-height:22px;" />&nbsp;&nbsp;
					手机号：<input type="text" id="my_mobile" maxlength="11" style="border:1px solid #d4d4d4;height:22px;width:150px;text-align:center;line-height:22px;" />
				</li>
			<?php if(!empty($store)):foreach($store as $v):?>
				<li><label>
					<input type="radio" name="store_id" value="<?=$v['id']?>" onclick="checkStore(this)" />
					<?=$v['name']?>&nbsp;&nbsp;地址：<?=$v['prov_name']?><?=$v['city_name']?><?=$v['district_name']?><?=$v['address']?>&nbsp;&nbsp;营业时间：<?=$v['open_time']?>
				</label></li>
			<?php endforeach;endif;?></div>
			</ul>
		</div>
		<div class="t">收货时间</div>
		<div class="address">
			<ul>
				<li>
					<label><input type="radio" name="receive_type" value="1" checked onclick="cancelDate()" />正常收货</label>
					<label><input type="radio" name="receive_type" value="2" onclick="cancelDate()" />仅工作日</label>
					<label><input type="radio" name="receive_type" value="3" onclick="cancelDate()" />仅周末</label>
					<label><input type="radio" name="receive_type" value="4" onclick="showDate()" />选择时间</label>
				</li>
				<li id="show_date" style="padding-left:5px;display:none;">
					日期：<input type="text" class="time laydate-icon" readonly id="receive_date" value="<?=(date('H')<11)?date('Y-m-d',strtotime('+0 day')):date('Y-m-d',strtotime('+1 day'))?>" />
					<span style="display:none">
						<input type="radio" name="receive_ma" value="1" />上午
						<input type="radio" name="receive_ma" value="2" checked> />下午
					</span>
				</li>
			</ul>
		</div>
	</div>
	<div class="t">商品清单</div>
	<table>
		<tr><th class="thA">商品</th><th class="thB">单价</th><th class="thC">数量</th><th class="thD">小计</th><th class="thE">合计</th></tr>
		<?php $i=1;if(!empty($product)):foreach($product as $k=>$v):?>
		<tr class="bd">
			<td>
				<div class="pt clearfix">
					<a href="<?=base_url("goods_{$k}.html")?>" target="_blank"><img src="<?=$v['thumb']?>" style="width:60px;height:60px;" /></a>
					<a href="<?=base_url("goods_{$k}.html")?>" target="_blank"><?=$v['title']?></a>
					<p><?php echo $v['free_label'] ? $v['free_label'] : ''; ?></p>
				</div>
			</td>
			<td>￥<?=sprintf('%.2f',$v['price'])?></td>
			<td><?=$v['amount']?></td>
			<td>￥<?=sprintf('%.2f',$v['single_price'])?></td>
			<?php if($i==1):?><td rowspan="<?=count($product)?>">￥<?=sprintf('%.2f',$total_price-$discount_price)?></td><?php endif;?>
		</tr>
		<input type="hidden" name="product_id[]" value="<?=$k?>" />
		<input type="hidden" name="amount[]" value="<?=$v['amount']?>" />
		<?php $i++;endforeach;endif;?>
		<tr><td colspan="5">
			<div class="total2 clearfix">
				<div class="bz">
					<a class="btn" id="add_note_button" href="javascript:;" onclick="addNote()">添加备注</a>
					<a class="btn" id="use_receipt_button" href="javascript:;" onclick="useReceipt()">获取发票</a>
					<a class="btn" id="use_cash_button" href="javascript:;" onclick="useCoupon()">使用代金券</a>
					<div class="bzbox" id="add_note" style="display:none;">
						<p>订单备注：</p>
						<textarea name="bz" id="note"></textarea>
					</div>
				</div>
				<div class="right" id="summary_info">
					商品总价：+￥<?=sprintf('%.2f',$total_price)?><br />
					运费：+￥<span id="shipping_price" data="<?php echo sprintf('%.2f',$freight); ?>"><?=sprintf('%.2f',$freight)?></span><br />
					折扣金额：-￥<span><?=sprintf('%.2f',$discount_price)?></span><br />
					使用代金券：-￥<span id="used_cash_money">0.00</span>
				</div>
			</div>
		</td></tr>
	</table>
	<div id="use_receipt" class="use_receipt" style="display:none;">
		<div class="t">发票信息</div>
		<div class="address">
			<ul id="fa_piao">
				<li>发票抬头：<input type="text" class="name" id="receipt_title" /></li>
				<li>发票内容：
					<input type="radio" name="receipt_des" value="明细" />明细&nbsp;
					<input type="radio" name="receipt_des" value="办公用品" />办公用品&nbsp;
					<input type="radio" name="receipt_des" value="技术咨询" />技术咨询&nbsp;
					<input type="radio" name="receipt_des" value="技术研发" />技术研发&nbsp;
					<input type="radio" name="receipt_des" value="鲜活农产品" />鲜活农产品&nbsp;
					<input type="radio" name="receipt_des" value="蔬菜" />蔬菜&nbsp;
					<input type="radio" name="receipt_des" value="鸡蛋" />鸡蛋&nbsp;
					<input type="radio" name="receipt_des" value="冻品" />冻品&nbsp;
					
				</li>
			</ul>
		</div>
	</div>
	<div id="use_cash" style="display:none;">
		<div class="t">可用代金券</div>
		<div class="address">
			<ul>
			<?php if(!empty($cash_coupon)):foreach($cash_coupon as $v):?>
				<li><label>
					<input type="radio" onclick="checkCoupon('<?=$v["id"]?>')" name="cash_coupon_id" value="<?=$v['id']?>" />
					编号：<?=$v['coupon_code']?>
					&nbsp;&nbsp;&nbsp;总金额：￥<?=$v['coupon_total']?>
					&nbsp;&nbsp;&nbsp;可用金额：￥<?=$v['coupon_balance']?>
					&nbsp;&nbsp;&nbsp;有效期：
					<?php if( $v['start'] && $v['end'] ){ ?>
					<?php echo date('Y-m-d H:i',$v['start']); ?> / <?php echo date('Y-m-d H:i',$v['end']); ?>
					<?php } elseif( !$v['start'] && $v['end'] ){ ?>
					<?php echo date('Y-m-d H:i',$v['end']); ?>前有效
					<?php } elseif( $v['start'] && !$v['end'] ){ ?>
					<?php echo date('Y-m-d H:i',$v['start']); ?>起有效
					<?php } elseif( !$v['start'] && !$v['end'] ){ ?>
					不限
					<?php } ?>
					<input type="hidden" value="<?=$v['coupon_balance']?>" id="coupon_<?=$v['id']?>" />
				</label></li>
			<?php endforeach;endif;?>
			</ul>
		</div>
	</div>
	<div id="deny_coupons" style="display:none;">
		<div class="t">不可用代金券</div>
		<div class="address">
			<ul>
			<?php if(!empty($deny_coupon)):foreach($deny_coupon as $v):?>
				<li><label>
					<span style="width:15px;">&nbsp;</span>
					编号：<?="<span style='width:100px;display:inline-block;'>{$v['coupon_code']}</span>"?>
					&nbsp;&nbsp;&nbsp;总金额：<?="<span style='width:45px;display:inline-block;'>￥{$v['coupon_total']}</span>"?>
					&nbsp;&nbsp;&nbsp;单笔最低消费金额：
					<?="<span style='width:45px;display:inline-block;'>"?>
						<?php if($v['coupon_limit']>$total_price):?>
							<?="<font color='red'>￥{$v['coupon_limit']}</font>"?>
						<?php else:?>
							<?=(intval($v['coupon_limit'])==0)?'不限':('￥'.$v['coupon_limit'])?>
						<?php endif;?>
					<?="</span>"?>
					&nbsp;&nbsp;&nbsp;可用金额：<?="<span style='width:45px;display:inline-block;'>"?><?=(($v['coupon_balance']<=0)?"<font color='red'>￥{$v['coupon_balance']}</font>":'￥'.$v['coupon_balance'])?><?="</span>"?>
					&nbsp;&nbsp;&nbsp;有效期：
					<?php if(strtotime(date('Y-m-d H:i:s',time()))>$v['end'] && $v['end']>0):?>
						<?="<font color='red'>"?>
							<?php if( $v['start'] && $v['end'] ){ ?>
							<?php echo date('Y-m-d H:i',$v['start']); ?> / <?php echo date('Y-m-d H:i',$v['end']); ?>
							<?php } elseif( !$v['start'] && $v['end'] ){ ?>
							<?php echo date('Y-m-d H:i',$v['end']); ?>前有效
							<?php } elseif( $v['start'] && !$v['end'] ){ ?>
							<?php echo date('Y-m-d H:i',$v['start']); ?>起有效
							<?php } elseif( !$v['start'] && !$v['end'] ){ ?>
							不限
							<?php } ?>
						<?="</font>"?>
					<?php else:?>
						<?php if( $v['start'] && $v['end'] ){ ?>
						<?php echo date('Y-m-d H:i',$v['start']); ?> / <?php echo date('Y-m-d H:i',$v['end']); ?>
						<?php } elseif( !$v['start'] && $v['end'] ){ ?>
						<?php echo date('Y-m-d H:i',$v['end']); ?>前有效
						<?php } elseif( $v['start'] && !$v['end'] ){ ?>
						<?php echo date('Y-m-d H:i',$v['start']); ?>起有效
						<?php } elseif( !$v['start'] && !$v['end'] ){ ?>
						不限
						<?php } ?>
					<?php endif;?>	
				</label></li>
			<?php endforeach;endif;?>
			</ul>
		</div>
	</div>	
	<div class="t">付款方式</div>
	<div class="address" id="pay_method"><ul><li>
		<label><input type="radio" name="pay_type" value="1" />货到付款</label>&nbsp;&nbsp;
		<label><input type="radio" name="pay_type" value="2" />支付宝</label>&nbsp;&nbsp;
		<label><input type="radio" name="pay_type" value="3" />会员卡</label>
	</li></ul></div>
	<div class="sbox">
		<p>订单金额：￥<span id="total_price"><?=sprintf('%.2f',$summary)?></span>元</p><a class="btn" href="javascript:;" onclick="submitOrder()">提交订单</a>
	</div>
	<div class="tip">
		<p style="color:red;">11:00之前的订单，当日送达（20:00之前）；11:00之后的订单，次日送达。</p>
	</div>
</div>
<?php $this->load->view('common/footer')?>
<script src="<?=STATICURL?>laydate/laydate.js"></script>
<script src="<?=STATICURL?>js/area.js"></script>
<script src="<?=STATICURL?>js/verify.js"></script>
<script type="text/javascript">
var date = new Date();
var hour = date.getHours();
var tomorrow = "<?=date('Y-m-d',strtotime('+1 day'))?>";
var hash_token = "<?=$hash_token?>";
var goods_price = <?=$total_price?>; //商品总价
var discount_price = <?=$discount_price?>; //折扣金额
var old_shipping_price = <?=$freight?>; //默认运费
var shipping_price = <?=$freight?>; //实际运费
var real_price = goods_price - discount_price + shipping_price; //实付总价
var cash_coupon = 0.00; //使用代金券金额
var cash_coupon_total = 0.00; //所选代金券总额
var use_receipt = 0; //获取发票
var use_store = 0; //门店自提
var product_id = new Array();
var amount = new Array();
var endTime = 11;  //截单时间为每天11点

$(document).ready(function(){
	$("input[name='product_id[]']").each(function(){
		product_id.push($(this).val());
	});
	$("input[name='amount[]']").each(function(){
		amount.push($(this).val());
	});
	$("input[name='cash_coupon_id']").each(function(){
		$(this).prop("checked", false);
	});
});
//格式化收货时间
laydate({
	elem: "#receive_date",
	istoday: false,
	festival: true,
	min: "<?=(date('H')<11)?date('Y-m-d', strtotime('+0 day')):date('Y-m-d', strtotime('+1 day'))?>",
	max: "<?=(date('H')<11)?date('Y-m-d', strtotime('+6 day')):date('Y-m-d', strtotime('+7 day'))?>",
	choose: function(dates){
		/*	都改成每天下午送了
		var receive_ma =  $("input[name='receive_ma']:checked").val();
		if(hour >= 12 && dates == tomorrow){
			$("input[name='receive_ma'][value='2']").prop('checked', true);
			$("input[name='receive_ma'][value='1']").prop('disabled', true);
		}else{
			$("input[name='receive_ma'][value='1']").prop('disabled', false);
		}
		*/
	}
});
//选取门店
function selectStore(){
	$("#store_list").show();
	$("#yourAddress").hide('fast');
}
//检查门店
function checkStore(tThis){
	var is_all_free = <?php echo $is_all_free ? 1 : 0; ?>;
	if( is_all_free ){
		layer.tips("任选一款非0元购商品自提即可免运费", tThis);
	} else {
		use_store = 1;
		shipping_price = 0.00;
		real_price = goods_price - discount_price - cash_coupon + shipping_price;
		$("#shipping_price").html(shipping_price.toFixed(2));
		$("#total_price").html(real_price.toFixed(2));
	}
}
//取消门店
function cancelStore(){
	$("#yourAddress").show('fast');
	use_store = 0;
	shipping_price = old_shipping_price;
	real_price = goods_price - discount_price - cash_coupon + shipping_price;
	$("#shipping_price").html(shipping_price.toFixed(2));
	$("#total_price").html(real_price.toFixed(2));
	$("#store_list").hide();
	$("input[name='store_id']").each(function(){
		$(this).prop("checked", false);
	});
}
//添加备注
function addNote(){
	$("#add_note_button").attr("onclick","cancelAddNote()").html("取消备注");
	$("#add_note").show();
	$("#postscript").focus();
}
//取消添加
function cancelAddNote(){
	$("#add_note").hide();
	$("#note").val("");
	$("#add_note_button").attr("onclick","addNote()").html("添加备注");
}
//获取发票
function useReceipt(){
	use_receipt = 1;
	$("#use_receipt_button").attr("onclick","cancelReceipt()").html("取消发票");
	$("#use_receipt").show();
}
//取消发票
function cancelReceipt(){
	use_receipt = 0;
	$("#use_receipt").hide();
	$("#receipt_title").val("");
	$("input[name='receipt_des']").each(function(){
		$(this).prop("checked", false);
	});
	$("#use_receipt_button").attr("onclick","useReceipt()").html("获取发票");
}
//自定义收货时间
function showDate(){
	//$("input[name='receive_ma'][value='1']").prop("checked", true);
	$("#show_date").show();
}
//取消自定义时间
function cancelDate(){
	$("#show_date").hide();
	$("#receive_date").val("");
	$("input[name='receive_ma']").each(function(){
		$(this).prop("checked", false);
	});
}
//使用代金券
function useCoupon(){
	$("#use_cash_button").attr("onclick","cancelCoupon()").html("取消代金券");
	$("#use_cash").show();
	<?php if(!empty($deny_coupon)):?>
	// $("#deny_coupons").show();//如果有不可用代金券则显示(暂时隐藏"不可用代金券"列表功能(需要时开启即可))
	<?php endif;?>
}
//检查代金券
function checkCoupon(coupon_id){
	cash_coupon = parseFloat($("#coupon_"+coupon_id).val());
	cash_coupon_total = cash_coupon;
	cash_coupon = Math.min(cash_coupon, (goods_price - discount_price));
	var real_pay = goods_price - discount_price - cash_coupon;
	if(real_pay < 59 && use_store == 0){
		//实际支付不满59元收取运费5元
		shipping_price = 5.00;
	}else{
		shipping_price = 0.00;
	}
	real_price = real_pay + shipping_price;
	$("#shipping_price").html(shipping_price.toFixed(2));
	$("#used_cash_money").html(cash_coupon.toFixed(2));
	$("#total_price").html(real_price.toFixed(2));
}
//取消代金券
function cancelCoupon(){
	cash_coupon = 0.00;
	$("#use_cash").hide();
	// $("#deny_coupons").hide();//暂时隐藏"不可用代金券"列表功能(需要时开启即可)
	$("input[name='cash_coupon_id']").each(function(){
		$(this).prop("checked", false);
	});
	$("#use_cash_button").attr("onclick","useCoupon()").html("使用代金券");
	var real_pay = goods_price - discount_price - cash_coupon;
	if(real_pay < 59 && use_store == 0){
		//实际支付不满59元收取运费5元
		shipping_price = 5.00;
	}else{
		shipping_price = 0.00;
	}
	real_price = real_pay + shipping_price;
	$("#shipping_price").html(shipping_price.toFixed(2));
	$("#used_cash_money").html(cash_coupon.toFixed(2));
	$("#total_price").html(real_price.toFixed(2));
}
//添加街道
var streets = <?php echo $street ? json_encode($street) : '[]'; ?>;
function getStreets(){
	var html = '<option value="0">请选择街道</option>';
	var area_id = $("#area_id option:selected").val();
	var arr = streets[area_id];
	if( arr && arr!=undefined ){
		for( var i in arr ){
			var item = arr[i];
			html += '<option value="'+item.id+'">'+item.name+'</option>';
		}
		$("#street_id").html(html).show();
	} else {
		$("#street_id").html(html).hide();
	}
}
//添加地址
function addAddress(){
	$("#receiver").val("");
	$("#province_id").val("");
	$("#city_id").val("");
	$("#area_id").val("");
	$("#address").val("");
	$("#zip").val("");
	$("#tel").val("<?=$userdata['tel']?>");
	$("#mobile").val("<?=$userdata['mobile']?>");
	$("input[name='is_default'][value='1']").prop("checked", "checked");
	$("#submit").attr("onclick", "doAddAddress()");
	getStreets();
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
	var province_id = '<?php echo $prov ? $prov["id"] : 0 ?>';
	var city_id = '<?php echo $city ? $city["id"] : 0 ?>';
	var receiver = $.trim($("#receiver").val());
	var area_id = $("#area_id option:selected").val();
	var street_id = $("#street_id option:selected").val();
	var address = $.trim($("#address").val());
	var zip = $.trim($("#zip").val());
	var tel = $.trim($("#tel").val());
	var mobile = $.trim($("#mobile").val());
	var is_default = $("input[name='is_default']:checked").val();
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
	}
	var street_num = $("#street_id option").size();
	if(street_id == "" && street_num > 1){
		$("#tip_area").html("请选择街道").show();
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
	/* if(zip == ""){
		$("#tip_zip").html("请输入收货人所在地邮编").show();
		$("#zip").focus();
		return false;
	}else if( ! isZipcode(zip)){
		$("#tip_zip").html("邮编格式不正确").show();
		$("#zip").focus();
		return false;
	}else{
		$("#tip_zip").hide();
	} */
	if(tel != "" && ! isTelephone(tel)){
		$("#tip_tel").html("电话格式不正确").show();
		$("#tel").focus();
		return false;
	}else{
		$("#tip_tel").hide();
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
		url: "<?=base_url('member/address/ajax_add')?>",
		type: "post",
		dataType: "json",
		data: {"receiver":receiver, "prov":province_id, "city":city_id, "district":area_id, "street":street_id, "address":address, "zip":zip, "tel":tel, "mobile":mobile, "is_default":is_default},
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
	var delivery_type =  $("input[name='delivery_type']:checked").val();
	var store_id = $("input[name='store_id']:checked").val();
	var receive_type =  $("input[name='receive_type']:checked").val();
	var receive_date =  $.trim($("#receive_date").val());
	var receive_ma =  $("input[name='receive_ma']:checked").val();
	var pay_type = $("input[name='pay_type']:checked").val();
	var receipt_title = $.trim($("#receipt_title").val());
	var receipt_des = $("input[name='receipt_des']:checked").val();
	var cash_coupon_id = $("input[name='cash_coupon_id']:checked").val();
	var note = $.trim($("#note").val());
	var my_name = $.trim($("#my_name").val());
	var my_mobile = $.trim($("#my_mobile").val());
	if(address_id == undefined && use_store == 0){
		layer.alert("请选择收货地址！", 5);
		$("#add_address_button").focus();
		$("#add_address_button").css({"background-color":"red"});
		return false;
	}
	if(delivery_type == "0"){
		if(my_name == ""){
			layer.alert("请填写自提联系人！", 5);
			$("#my_name").focus();
			$("#my_name").after("<span style='color:red;'>请填入联系人!</span>");
			return false;
		}
		if( ! isMobile(my_mobile)){
			layer.alert("手机号码格式错误！", 5);
			$("#my_mobile").focus();
			$("#my_mobile").after("<span style='color:red;'>请填入手机号!</span>");
			return false;
		}
		if(store_id == undefined){
			layer.alert("请选择自提门店！", 5);
			return false;
		}
	}
	if(receive_type == "4"){
		if(receive_date == ""){
			layer.alert("请选择收货日期！", 5);
			return false;
		}else if(receive_ma == undefined){
			layer.alert("请选择上午或下午！", 5);
			return false;
		}else if(hour >= 12 && receive_date == tomorrow && receive_ma == '1'){
			layer.alert("收货时间最早只能选择明天下午！", 5);
			return false;
		}
	}
	if(use_receipt == 1){
		if(receipt_title == ""){
			layer.alert("请填写发票抬头！", 5);
			$("#fa_piao li:first").focus().after("<span style='color:red;'>请填写发票抬头！</span>");
			return false;
		}else if(receipt_des == undefined){
			layer.alert("请选择发票内容！", 5);
			return false;
		}
	}
	if(pay_type == undefined){
		layer.alert("请选择该订单的付款方式！", 5);
		$("#pay_method").focus().css("border","1px solid red");
		return false;
	}
	layer.load("提交中...");
	$.ajax({
		url: "<?=base_url('member/order/ajax_submit')?>",
		type: "post",
		dataType: "json",
		data: {"hash_token":hash_token, "address_id":address_id, "pay_type":pay_type, "cash_coupon_id":cash_coupon_id, "cash_coupon":cash_coupon,
				"product_id":product_id, "amount":amount, "real_price":real_price, "goods_price":goods_price, "note":note,
				"delivery_type":delivery_type, "store_id":store_id, "discount_price":discount_price,
				"is_receipt":use_receipt, "receipt_title":receipt_title, "receipt_des":receipt_des,
				"receive_type":receive_type, "receive_date":receive_date, "receive_ma":receive_ma, "my_name":my_name, "my_mobile":my_mobile
		},
		success: function(data){
			if(data.err_no == 0){
				location.href = "<?=base_url('member/order/success')?>/" + data.results;
			}else if(data.err_no == 1003){
				layer.msg("订单中部分商品库存不足或已下架！", 2, 5);
			}else{
				layer.msg("提交订单失败请重试！", 2, 5);
			}
		}
	});
}
</script>
</body>
</html>