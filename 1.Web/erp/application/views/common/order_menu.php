<div class="inner">
    <div class="nav-vertical">
        <ul class="accordion">
            <li id="one"> 
            	<a href="#one">订单管理<span class="caret"></span></a>
                <ul class="sub-menu">
                	<?php foreach ($order_status_types as $k=>$v) { ?>
		            <li><a href="<?php echo site_url('order/order?status='.$k); ?>" <?php if(@$active_menu_tag==('status_'.$k)) echo 'class="active"'; ?>><?php echo $v; ?></a></li>
		            <?php } ?>
	          	</ul>
            </li>
            <li id="two"> 
                <a href="#two">会员卡管理<span class="caret"></span></a>
                <ul class="sub-menu">
                    <li><a href="<?php echo site_url('order/card'); ?>" <?php if(@$active_menu_tag==('card')) echo 'class="active"'; ?>>会员卡订单列表</a></li>
                </ul>
            </li>
            <li id="two"> 
                <a href="#two">评论管理<span class="caret"></span></a>
                <ul class="sub-menu">
                    <li><a href="<?php echo site_url('order/comment'); ?>" <?php if(@$active_menu_tag==('comment')) echo 'class="active"'; ?>>评论列表</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
