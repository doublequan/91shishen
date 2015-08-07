<div class="inner">
    <div class="nav-vertical">
        <ul class="accordion">
            <li id="one"> 
            	<a href="#one">商品信息<span class="caret"></span></a>
                <ul class="sub-menu">
		            <li><a href="<?php echo site_url('products/product'); ?>" <?php if(@$active_menu_tag=='product') echo 'class="active"'; ?>>商品列表</a></li>
		            <li><a href="<?php echo site_url('products/product/add'); ?>" <?php if(@$active_menu_tag=='product_add') echo 'class="active"'; ?>>添加商品</a></li>
		            <li><a href="<?php echo site_url('products/good'); ?>" <?php if(@$active_menu_tag=='good') echo 'class="active"'; ?>>原料列表</a></li>
                    <li><a href="<?php echo site_url('products/sale'); ?>" <?php if(@$active_menu_tag=='sale') echo 'class="active"'; ?>>门店零售</a></li>
	          	</ul>
            </li>
            <li id="two"> 
            	<a href="#two">商品管理<span class="caret"></span></a>
                <ul class="sub-menu">
                    <li><a href="<?php echo site_url('products/purchase'); ?>" <?php if(@$active_menu_tag=='purchase') echo 'class="active"'; ?>>采购单列表</a></li>
                    <!-- <li><a href="<?php echo site_url('products/purchase/add'); ?>" <?php if(@$active_menu_tag=='purchase_add') echo 'class="active"'; ?>>添加采购单</a></li> -->
		            <!-- <li><a href="<?php echo site_url('products/dispatch/add'); ?>" <?php if(@$active_menu_tag=='dispatch_form') echo 'class="active"'; ?>>添加调度单</a></li> -->
                    <li><a href="<?php echo site_url('products/purchase_finance'); ?>" <?php if(@$active_menu_tag=='purchase_finance') echo 'class="active"'; ?>>财务单列表</a></li>
		            <li><a href="<?php echo site_url('products/stock'); ?>" <?php if(@$active_menu_tag=='stock') echo 'class="active"'; ?>>库存信息</a></li>
		            <!--<li><a href="<?php echo site_url('products/stock/log_list'); ?>" <?php if(@$active_menu_tag=='stock_log') echo 'class="active"'; ?>>库存记录</a></li>-->
		            <li><a href="<?php echo site_url('products/loss'); ?>" <?php if(@$active_menu_tag=='loss') echo 'class="active"'; ?>>损耗列表</a></li>
	          	</ul>
            </li>
            <li id="three"> 
            	<a href="#three">商品配置<span class="caret"></span></a>
              	<ul class="sub-menu">
    	            <li><a href="<?php echo site_url('products/supplier'); ?>" <?php if(@$active_menu_tag=='supplier') echo 'class="active"'; ?>>供应商管理</a></li>
    	            <li><a href="<?php echo site_url('products/category'); ?>" <?php if(@$active_menu_tag=='category') echo 'class="active"'; ?>>分类列表</a></li>
    	            <li><a href="<?php echo site_url('products/spec'); ?>" <?php if(@$active_menu_tag=='spec') echo 'class="active"'; ?>>规格列表</a></li>
    	            <li><a href="<?php echo site_url('products/brand'); ?>" <?php if(@$active_menu_tag=='brand') echo 'class="active"'; ?>>品牌列表</a></li>
    	            <li><a href="<?php echo site_url('products/loss_type'); ?>" <?php if(@$active_menu_tag=='loss_type') echo 'class="active"'; ?>>损耗类型</a></li>
              	</ul>
            </li>
        </ul>
    </div>
</div>					
