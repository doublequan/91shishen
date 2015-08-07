<div class="inner">
    <div class="nav-vertical">
        <ul class="accordion">
            <li id="one"> 
                <a href="#one">用户数据<span class="caret"></span></a>
                <ul class="sub-menu">
                    <li><a href="<?php echo site_url('stat/user/amount'); ?>" <?php if(@$active_menu_tag=='user_amount') echo 'class="active"'; ?>>用户数统计</a></li>
                    <li><a href="<?php echo site_url('stat/user/stat'); ?>" <?php if(@$active_menu_tag=='user_stat') echo 'class="active"'; ?>>用户比例统计</a></li>
                </ul>
            </li>
            <li id="two"> 
                <a href="#two">销售数据<span class="caret"></span></a>
                <ul class="sub-menu">
                    <li><a href="<?php echo site_url('stat/order/card'); ?>" <?php if(@$active_menu_tag=='order_card') echo 'class="active"'; ?>>储值会员统计</a></li>
                    <li><a href="<?php echo site_url('stat/order/sale'); ?>" <?php if(@$active_menu_tag=='order_sale') echo 'class="active"'; ?>>销售额统计</a></li>
                    <li><a href="<?php echo site_url('stat/order/amount'); ?>" <?php if(@$active_menu_tag=='order_amount') echo 'class="active"'; ?>>订单量统计</a></li>
                    <li><a href="<?php echo site_url('stat/order/store'); ?>" <?php if(@$active_menu_tag=='order_store') echo 'class="active"'; ?>>门店订单统计</a></li>
                    <li><a href="<?php echo site_url('stat/order/product'); ?>" <?php if(@$active_menu_tag=='order_product') echo 'class="active"'; ?>>商品销量统计</a></li>
                </ul>
            </li>
            <li id="three"> 
                <a href="#three">支付数据<span class="caret"></span></a>
                <ul class="sub-menu">
                    <li><a href="<?php echo site_url('stat/pay/alipay'); ?>" <?php if(@$active_menu_tag=='pay_alipay') echo 'class="active"'; ?>>支付宝支付统计</a></li>
                    <li><a href="<?php echo site_url('stat/pay/yeepay'); ?>" <?php if(@$active_menu_tag=='pay_yeepay') echo 'class="active"'; ?>>会员卡支付统计</a></li>
                </ul>
            </li>
            <li id="four"> 
                <a href="#four">邀请数据<span class="caret"></span></a>
                <ul class="sub-menu">
                    <li><a href="<?php echo site_url('stat/invite/amount'); ?>" <?php if(@$active_menu_tag=='invite_amount') echo 'class="active"'; ?>>邀请用户数量统计</a></li>
                    <li><a href="<?php echo site_url('stat/invite/sale'); ?>" <?php if(@$active_menu_tag=='invite_sale') echo 'class="active"'; ?>>邀请用户销售统计</a></li>
                </ul>
            </li>
            <li id="ten"> 
                <a href="#ten">测试统计<span class="caret"></span></a>
                <ul class="sub-menu">
                    <li><a href="<?php echo site_url('stat/test/product'); ?>" <?php if(@$active_menu_tag=='test_product') echo 'class="active"'; ?>>无详情商品</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
					