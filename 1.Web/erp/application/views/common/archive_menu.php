<div class="inner">
    <div class="nav-vertical">
        <ul class="accordion">
            <li id="one"> 
            	<a href="#one">内容管理<span class="caret"></span></a>
                <ul class="sub-menu">
                	<li><a href="<?php echo site_url('archive/archive'); ?>" <?php if(@$active_menu_tag=='archive') echo 'class="active"'; ?>>内容列表</a></li>
                    <li><a href="<?php echo site_url('archive/archive/add'); ?>" <?php if(@$active_menu_tag=='archive_add') echo 'class="active"'; ?>>内容编辑</a></li>
                    <li><a href="<?php echo site_url('archive/category'); ?>" <?php if(@$active_menu_tag=='category') echo 'class="active"'; ?>>分类列表</a></li>
                </ul>
            </li>
            <li id="two"> 
            	<a href="#two">碎片管理<span class="caret"></span></a>
                <ul class="sub-menu">
                    <li><a href="<?php echo site_url('archive/frag'); ?>" <?php if(@$active_menu_tag=='frag_place_list') echo 'class="active"'; ?>>碎片位置列表</a></li>
	          	</ul>
            </li>
            <li id="three"> 
                <a href="#three">专题管理<span class="caret"></span></a>
                <ul class="sub-menu">
                    <li><a href="<?php echo site_url('archive/special'); ?>" <?php if(@$active_menu_tag=='special') echo 'class="active"'; ?>>专题列表</a></li>
                </ul>
            </li>
            <li id="four"> 
                <a href="#four">促销管理<span class="caret"></span></a>
                <ul class="sub-menu">
                    <li><a href="<?php echo site_url('archive/promotion'); ?>" <?php if(@$active_menu_tag=='promotion') echo 'class="active"'; ?>>促销活动列表</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
					