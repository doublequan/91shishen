<div class="inner">
    <div class="nav-vertical">
        <ul class="accordion">
            <li id="one"> 
            	<a href="#one">企业设置<span class="caret"></span></a>
                <ul class="sub-menu">
		            <li><a href="<?php echo site_url('enterprise/company'); ?>" <?php if(@$active_menu_tag=='company') echo 'class="active"'; ?>>公司列表</a></li>
		            <li><a href="<?php echo site_url('enterprise/site'); ?>" <?php if(@$active_menu_tag=='site') echo 'class="active"'; ?>>网站列表</a></li>
		            <li><a href="<?php echo site_url('enterprise/dept'); ?>" <?php if(@$active_menu_tag=='department') echo 'class="active"'; ?>>部门列表</a></li>
		            <li><a href="<?php echo site_url('enterprise/store'); ?>" <?php if(@$active_menu_tag=='store') echo 'class="active"'; ?>>门店列表</a></li>
	          	</ul>
            </li>
            <li id="two"> 
            	<a href="#two">员工管理<span class="caret"></span></a>
                <ul class="sub-menu">
		            <li><a href="<?php echo site_url('employee/employee'); ?>" <?php if(@$active_menu_tag=='employee') echo 'class="active"'; ?>>员工管理</a></li>
	          	</ul>
            </li>
            <li id="three"> 
            	<a href="#three">权限管理<span class="caret"></span></a>
              	<ul class="sub-menu">
                    <li><a href="<?php echo site_url('permission/group'); ?>" <?php if(@$active_menu_tag=='permission_group') echo 'class="active"'; ?>>权限组管理</a></li>
                    <li><a href="<?php echo site_url('permission/permission'); ?>" <?php if(@$active_menu_tag=='permission') echo 'class="active"'; ?>>权限管理</a></li>
		            <li><a href="<?php echo site_url('permission/role'); ?>" <?php if(@$active_menu_tag=='permission_role') echo 'class="active"'; ?>>角色列表</a></li>
	          	</ul>
            </li>
        </ul>
    </div>
</div>					
