<div class="inner">
    <div class="nav-vertical">
        <ul class="accordion">
            <li id="one"> 
            	<a href="#one">我的桌面<span class="caret"></span></a>
                <ul class="sub-menu">
                    <li><a href="<?php echo site_url('home/index'); ?>" <?php if(@$active_menu_tag=='index') echo 'class="active"'; ?>>系统概况</a></li>
                </ul>
            </li>
            <li id="two"> 
                <a href="#two">商品管理<span class="caret"></span></a>
                <ul class="sub-menu">
                    <li><a href="<?php echo site_url('products/product'); ?>" <?php if(@$active_menu_tag=='product') echo 'class="active"'; ?>>商品列表</a></li>
                </ul>
            </li>
            <li id="three"> 
                <a href="#three">订单管理<span class="caret"></span></a>
                <ul class="sub-menu">
                    <li><a href="<?php echo site_url('order/order'); ?>" <?php if(@$active_menu_tag=='order') echo 'class="active"'; ?>>订单列表</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>					
