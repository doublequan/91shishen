<?php
require_once('../config.php'); 
	 $site_url = $site['url'];
$script = array('address_add');
$page['title'] = '地址添加';

include '../layout/header.php'; 
?>

<div class="address-add">
	<div id="address-add-box">
		<ul>
			
			<li><label>收货人：</label><input type="text" class="address-add-box-name" > </li>
			<li><label>省市：</label>
			<select id="address-add-box-province">
			</select> 
			<select id="address-add-box-city">
			</select>
			<select id="address-add-box-area">
			</select>
			</li>
			<li>
				<label>街道：</label><select id="address-add-box-street"></select>
			</li>
			<li>
				<label>地址：</label><input type="text" class="address-add-box-address">
			</li>
			<li>
				<label>邮编：</label><input type="number" class="address-add-box-postcode" maxlength="6" value="210000"></li>
			</li>
			<li>
				<label>固定电话：</label><input type="number" class="address-add-box-tel" maxlength="11"></li>
			</li>
			<li>
				<label>手机号码：</label><input type="number" class="address-add-box-mobile" maxlength="11"></li>
			</li>
			<li>
				<label>设为默认：</label><input type="radio" class="address-add-box-default" name="is_default" value="1" checked="checked">是
				<input type="radio" name="is_default" class="address-add-box-default" value="0" >否
			</li>
		</ul>
		<div class="address-add-btn-box">
			<a href="javascript:;" id="address-add-btn" class="btn btn-red padding"><i class="icon-add"></i>添加地址</a>
		</div>
	</div>
</div>
<?php include '../layout/footer.php';?>