<div class="inner">
    <div class="nav-vertical">
        <ul class="accordion">
            <li id="one"> 
                <a href="#one">系统概况<span class="caret"></span></a>
                <ul class="sub-menu">
                    <li><a href="<?php echo site_url('home/index'); ?>" <?php if(@$active_menu_tag=='index') echo 'class="active"'; ?>>系统概况</a></li>
                </ul>
            </li>
            <li id="two"> 
                <a href="#two">任务管理<span class="caret"></span></a>
                <ul class="sub-menu">
                    <?php foreach ($task_status_types as $k=>$v) { ?>
                    <li><a href="<?php echo site_url('home/task?status='.$k); ?>" <?php if(@$active_menu_tag==('task_'.$k)) echo 'class="active"'; ?>><?php echo $v; ?>任务</a></li>
                    <?php } ?>
                </ul>
            </li>
        </ul>
    </div>
</div>  
