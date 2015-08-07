<div class="inner">
    <div class="nav-vertical">
        <ul class="accordion">
            <li id="one"> 
                <a href="#one">系统设置<span class="caret"></span></a>
                <ul class="sub-menu">
                    <li><a href="<?php echo site_url('system/system'); ?>" <?php if(@$active_menu_tag=='system') echo 'class="active"'; ?>>系统设置</a></li>
                    <li><a href="<?php echo site_url('my/info'); ?>" <?php if(@$active_menu_tag=='my_info') echo 'class="active"'; ?>>个人信息</a></li>
                    <li><a href="<?php echo site_url('system/task'); ?>" <?php if(@$active_menu_tag=='task') echo 'class="active"'; ?>>任务处理人</a></li>
                    <li><a href="<?php echo site_url('system/area'); ?>" <?php if(@$active_menu_tag=='area') echo 'class="active"'; ?>>地区管理</a></li>
                </ul>
            </li>
            <li id="two"> 
                <a href="#two">缓存管理<span class="caret"></span></a>
                <ul class="sub-menu">
                    <li><a href="<?php echo site_url('system/cache/config'); ?>" <?php if(@$active_menu_tag=='cache_config') echo 'class="active"'; ?>>系统缓存</a></li>
                    <li><a href="<?php echo site_url('system/cache/user'); ?>" <?php if(@$active_menu_tag=='cache_user') echo 'class="active"'; ?>>用户缓存</a></li>
                </ul>
            </li>
            <li id="three"> 
                <a href="#three">日志管理<span class="caret"></span></a>
                <ul class="sub-menu">
                    <li><a href="<?php echo site_url('system/log'); ?>" <?php if(@$active_menu_tag=='log_list') echo 'class="active"'; ?>>客户端日志</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>  
