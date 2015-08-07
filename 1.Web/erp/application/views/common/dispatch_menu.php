<div class="inner">
    <div class="nav-vertical">
        <ul class="accordion">
            <li id="one"> 
                <a href="#one">我的调度单<span class="caret"></span></a>
                <ul class="sub-menu">
                    <li><a href="<?php echo site_url('dispatch/my'); ?>" <?php if(@$active_menu_tag==('my')) echo 'class="active"'; ?>>我的调度单</a></li>
                    <li><a href="<?php echo site_url('dispatch/my/add'); ?>" <?php if(@$active_menu_tag==('my_add')) echo 'class="active"'; ?>>新建调度单</a></li>
                </ul>
            </li>
            <li id="two"> 
            	<a href="#two">调度单管理<span class="caret"></span></a>
                <ul class="sub-menu">
                	<?php foreach ($statusMap as $k=>$v) { ?>
                    <?php if( $k ){ ?>
		            <li><a href="<?php echo site_url('dispatch/index?status='.$k); ?>" <?php if(@$active_menu_tag==('index_status_'.$k)) echo 'class="active"'; ?>><?php echo $v; ?></a></li>
		            <?php } ?>
                    <?php } ?>
	          	</ul>
            </li>
        </ul>
    </div>
</div>
