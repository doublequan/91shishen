<div class="inner">
    <div class="nav-vertical">
        <ul class="accordion">
            <li id="one"> 
            	<a href="#one">用户信息<span class="caret"></span></a>
                <ul class="sub-menu">
		            <li><a href="<?php echo site_url('user/user'); ?>" <?php if(@$active_menu_tag=='user') echo 'class="active"'; ?>>用户列表</a></li>
		            <li><a href="<?php echo site_url('user/device'); ?>" <?php if(@$active_menu_tag=='device') echo 'class="active"'; ?>>用户设备</a></li>
		            <li><a href="<?php echo site_url('user/address'); ?>" <?php if(@$active_menu_tag=='address') echo 'class="active"'; ?>>收货地址</a></li>
		            <li><a href="<?php echo site_url('user/coupon'); ?>" <?php if(@$active_menu_tag=='coupon_cash') echo 'class="active"'; ?>>代金券</a></li>
		            <li><a href="<?php echo site_url('user/group'); ?>" <?php if(@$active_menu_tag=='group') echo 'class="active"'; ?>>用户组</a></li>
		            <li><a href="<?php echo site_url('user/card/pingan'); ?>" <?php if(@$active_menu_tag=='card_pingan') echo 'class="active"'; ?>>平安合作会员卡</a></li>
	          	</ul>
            </li>
            <li id="two"> 
            	<a href="#two">用户营销<span class="caret"></span></a>
                <ul class="sub-menu">
		            <!--<li><a href="<?php echo site_url('user/coupon?type=2'); ?>" <?php if(@$active_menu_tag=='coupon_discount') echo 'class="active"'; ?>>用户抵用券券</a></li>-->
		            <li><a href="<?php echo site_url('user/send/push'); ?>" <?php if(@$active_menu_tag=='send_push') echo 'class="active"'; ?>>移动设备推送</a></li>
		            <li><a href="<?php echo site_url('user/send/msg'); ?>" <?php if(@$active_menu_tag=='send_msg') echo 'class="active"'; ?>>站内消息群发</a></li>
		            <li><a href="<?php echo site_url('user/send/email'); ?>" <?php if(@$active_menu_tag=='send_email') echo 'class="active"'; ?>>邮件群发</a></li>
		            <li><a href="<?php echo site_url('user/send/sms'); ?>" <?php if(@$active_menu_tag=='send_sms') echo 'class="active"'; ?>>短信群发</a></li>
	          	</ul>
            </li>
            <li id="three"> 
            	<a href="#three">用户数据统计<span class="caret"></span></a>
              	<ul class="sub-menu">
		            <li><a href="<?php echo site_url('user/log/score'); ?>" <?php if(@$active_menu_tag=='log_score') echo 'class="active"'; ?>>用户积分统计</a></li>
		            <li><a href="<?php echo site_url('user/log/login'); ?>" <?php if(@$active_menu_tag=='log_login') echo 'class="active"'; ?>>用户登录统计</a></li>
		            <li><a href="<?php echo site_url('user/log/money'); ?>" <?php if(@$active_menu_tag=='log_money') echo 'class="active"'; ?>>资金调整统计</a></li>
		            <li><a href="<?php echo site_url('user/log/coupon'); ?>" <?php if(@$active_menu_tag=='log_coupon_cash') echo 'class="active"'; ?>>代金券使用统计</a></li>
		            <!--<li><a href="<?php echo site_url('user/log/coupon?type=2'); ?>" <?php if(@$active_menu_tag=='log_coupon_discount') echo 'class="active"'; ?>>优惠券使用统计</a></li>-->
		            <li><a href="<?php echo site_url('user/log/view'); ?>" <?php if(@$active_menu_tag=='log_view') echo 'class="active"'; ?>>产品访问量统计</a></li>
	          	</ul>
            </li>
        </ul>
    </div>
</div>
