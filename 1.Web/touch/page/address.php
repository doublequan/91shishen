<?php
require_once('../config.php'); 
	 $site_url = $site['url'];
$script = array('address');
$page['title'] = '地址列表';

include '../layout/header.php'; 
?>

<div class="address">
	<div id="address-box" class="box"></div>
</div>

<script type="text/html" id="address-temp">
	{{# if(d.list.length == 0 ) { }}
		<div class="address-empty">
			<span>:-O 没有收货地址，赶紧去添加吧！</span>
			<div class="address-empty-btn">
			<a href="<?=$site_url .'/page/address_add.php'?>" class="btn btn-red padding"><i class="icon-add"></i> 添加地址</a>
			</div>
		</div>
	{{# } else { }}
		<div class="address-list">
			<ul>
			{{# for ( var x in d.list) { }}
				<li data-id="{{ d.list[x].id }}">
					<p><label data-prov-id="{{ d.list[x].prov }}">{{ d.list[x].prov_name }}</label> <label data-city-id="{{ d.list[x].city }}">{{ d.list[x].city_name }}</label><label data-district-id="{{ d.list[x].district }}">{{ d.list[x].district_name }}</label> <label data-district-id="{{ d.list[x].street }}">{{ d.list[x].street_name }}</label> <label>{{ d.list[x].address }}</label><label>({{d.list[x].zip}})</label></p>
					<p>
					收货人：<label>{{ d.list[x].receiver }}</label>
					<label>{{ d.list[x].tel }}</label>
					<label>{{ d.list[x].mobile }}</label>
					{{# if(d.list[x].is_default == 1) { }}
						<label class="text-red">[默认地址]</label>
					{{# } }}
					</p>
				</li>
			{{# } }}
			</ul>
			<div class="address-empty-btn">
			<a href="<?=$site_url .'/page/address_add.php'?>" class="btn btn-red padding"><i class="icon-add"></i> 添加地址</a>
			</div>
		</div>
	{{# } }}
</script>

<?php include '../layout/footer.php';?>