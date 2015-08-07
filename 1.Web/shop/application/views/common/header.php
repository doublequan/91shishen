<?php
/**
 * 通用头部
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-21
 */
defined('BASEPATH') || exit('Access denied');
?>
<div class="topnav">
	<div class="area">
		<div class="welcome flt">您好，欢迎光临惠生活！
		<span class="select cur" onMouseOver="$('#city_select').show()" onMouseOut="$('#city_select').hide()">
		<?php if(empty($_COOKIE['SITEID']) || intval($_COOKIE['SITEID']) == 1){ echo '南京'; }elseif(intval($_COOKIE['SITEID']) == 2){ echo '扬州'; }elseif(intval($_COOKIE['SITEID']) == 3){ echo '马鞍山'; } ?>
		<em></em><div class="city_select" id="city_select">
			<div class="title"><b>选择城市</b></div>
			<ul>
				<a href="javascript:;" onclick="switchSite(1)"><li>N 南京</li></a>
				<a href="javascript:;" onclick="switchSite(2)"><li>Y 扬州</li></a>
				<a href="javascript:;" onclick="switchSite(3)"><li>M 马鞍山</li></a>
			</ul>
		</div></span>站
		</div>
		<div class="favorite"><a href="javascript:;" onclick="addFav('惠生活','http://hsh.com')">加入收藏</a></div>
		<ul class="fr">
		<?php $userinfo = $this->session->userdata('userinfo');if(!empty($userinfo)):?>
			<li class="reg"><a href="<?=base_url('member/home/index')?>"><?=$userinfo['username']?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?=base_url('member/user/logout')?>">退出</a></li>
		<?php else:?>
			<li class="reg"><a href="<?=base_url('member/user/login')?>">登录</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?=base_url('member/user/register')?>">免费注册</a></li>
		<?php endif;?>
			<li class="phone"><em></em>400-025-9089</li>
		</ul>
	</div>
</div>
<div class="area">
<?php if(isset($top) && !empty($top)):?><a href="<?=$top[0]['url']?>"><img src="<?=$top[0]['img']?>" /></a><?php endif;?>
</div>
<div class="logo area">
	<a href="/">
		<?php if(isset($logo) && !empty($logo)):?><img src="<?=$logo[0]['img']?>" />
		<?php else:?>
		<img src="<?=STATICURL?>images/logo.png" />
		<?php endif;?>
	</a>
	<div class="hd-so l">
		<form method="get" action="<?=base_url('search/index')?>" onSubmit="return doSearch()">
			<div class="hd-so-bor pr">
				<input type="text" placeholder="请输入商品名称" autocomplete="off" class="inp1" name="s" id="search_keyword" <?php if(!empty($keyword)):?>value="<?=$keyword?>"<?php endif;?> />
				<input type="submit" class="btn1" value="搜 索" />
			</div>
			<div class="hd-so-hot">热门搜词：
				<?php if(isset($keywords)):foreach($keywords as $k=>$v):?>
				<a href="<?=$v['url']?>" target="_blank"<?php if($k==0):?> style="color:red;"<?php endif; ?>><?=$v['title']?></a>
				<?php endforeach;endif;?>
			</div>
		</form>
	</div>
</div>
<div class="nav">
	<div class="area">
		<div class="leftnav fl" id="topnav">
			<div class="fenlei">所有产品分类 <em></em></div>
			<!-- 商品分类 开始 -->
			<div class="hsh_sort" id="leftnav" <?php if(!empty($is_home)):?>style="display:block;"<?php endif;?>>
				<ul>
				<?php $k=1;if(!empty($allCategories)):foreach($allCategories as $v):?>
					<li>
						<a href="<?=base_url("cat_{$v['id']}.html")?>" target="_blank"><em class="tp<?=$k?>"></em><?=$v['name']?></a>
					<?php if(!empty($v['children'])):?>
						<div class="item" style="top:-1px;width:480px;">
							<div class="im">
							<?php foreach($v['children'] as $v2):?>
								<dl class="clearfix">
									<dt><a href="<?=base_url("cat_{$v2['id']}.html")?>" target="_blank"><?=$v2['name']?></a></dt>
								<?php if(!empty($v2['children'])):?>
									<dd>
									<?php foreach($v2['children'] as $v3):?>
										<a href="<?=base_url("cat_{$v3['id']}.html")?>" target="_blank"><?=$v3['name']?></a>
									<?php endforeach;?>
									</dd>
								<?php endif;?>
								</dl>
							<?php endforeach;?>
							</div>
						</div>
					<?php endif;?>
					</li>
				<?php $k++;endforeach;endif;?>
				</ul>
			</div>
			<!-- 商品分类 结束 -->
		</div>
		<div class="home fl"><a href="<?=base_url()?>"><em>主页</em></a></div>
		<div class="navlist fl">
			<?php if(isset($nav) && !empty($nav)):foreach($nav as $k=>$row):?>
			<a href="<?=$row['url']?>"><?php if($k==0):?><div id="cart_number" class="number">new</div><?php endif;?><?=$row['title']?></a>
			<?php endforeach;endif;?>
		</div>
		<div class="shopping">
			<div class="bgl">
			<a href="<?=base_url('cart/index')?>" style="color:#fff;">
				<div class="bgr">
					<div class="number" id="cart_number"><?=$cartNum?></div>
					<span class="simg"></span>购物车
				</div>
			</a>
			</div>
		</div>
	</div>
</div>
