<?php
/**
 * 文章菜单
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-29
 */
defined('BASEPATH') || exit('Access denied');
?>
<div class="leftnav">
	<dl class="clearfix">
		<dt>惠生活信息</dt>
		<dd>
			<ul>
			<?php if(!empty($categories)):foreach($categories as $v):?>
				<?php if($category['id']==$v['id']):?>
				<li class="cur"><span>→</span><?=$v['name']?></li>
				<?php else:?>
				<li><a href="<?php echo base_url('archive/list_'.$v['id'].'.html')?>"><?=$v['name']?></a></li>
				<?php endif;?>
			<?php endforeach;endif;?>
			</ul>
		</dd>
	</dl>
</div>
