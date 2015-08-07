<div class="inner">
    <div class="nav-vertical">
        <ul class="accordion">
            <li id="one"> 
            	<a href="#one">大客户商品<span class="caret"></span></a>
                <ul class="sub-menu">
                	<li><a href="<?php echo site_url('vip/product'); ?>" <?php if(@$active_menu_tag=='product') echo 'class="active"'; ?>>商品列表</a></li>
                    <li><a href="<?php echo site_url('vip/product/add'); ?>" <?php if(@$active_menu_tag=='product_add') echo 'class="active"'; ?>>添加商品</a></li>
                	<li><a href="<?php echo site_url('vip/custom'); ?>" <?php if(@$active_menu_tag=='custom') echo 'class="active"'; ?>>商品定制</a></li>
                </ul>
            </li>
            <li id="two"> 
            	<a href="#two">大客户订单<span class="caret"></span></a>
                <ul class="sub-menu">
                    <li><a href="<?php echo site_url('vip/order'); ?>" <?php if(@$active_menu_tag=='status_-1') echo 'class="active"'; ?>>全部订单</a></li>
                    <li><a href="<?php echo site_url('vip/order').'?status=0'; ?>" <?php if(@$active_menu_tag=='status_0') echo 'class="active"'; ?>>新提交订单</a></li>
                    <li><a href="<?php echo site_url('vip/order').'?status=1'; ?>" <?php if(@$active_menu_tag=='status_1') echo 'class="active"'; ?>>已支付订单</a></li>
		            <li><a href="<?php echo site_url('vip/order').'?status=20'; ?>" <?php if(@$active_menu_tag=='status_20') echo 'class="active"'; ?>>已完成订单</a></li>
	          	</ul>
            </li>
            <li id="three"> 
            	<a href="#three">大客户信息<span class="caret"></span></a>
        		<ul class="sub-menu">
		            <li><a href="<?php echo site_url('vip/user'); ?>" <?php if(@$active_menu_tag=='user') echo 'class="active"'; ?>>大客户列表</a></li>
		            <li><a href="<?php echo site_url('vip/company'); ?>" <?php if(@$active_menu_tag=='company') echo 'class="active"'; ?>>大客户公司</a></li>
		            <li><a href="<?php echo site_url('vip/category'); ?>" <?php if(@$active_menu_tag=='category') echo 'class="active"'; ?>>商品分类</a></li>
		            <li><a href="<?php echo site_url('vip/industry'); ?>" <?php if(@$active_menu_tag=='industry') echo 'class="active"'; ?>>行业列表</a></li>
		            <li><a href="<?php echo site_url('vip/user/log_login'); ?>" <?php if(@$active_menu_tag=='log_login') echo 'class="active"'; ?>>登录统计</a></li>
	          	</ul>
            </li>
        </ul>
    </div>
</div>
					