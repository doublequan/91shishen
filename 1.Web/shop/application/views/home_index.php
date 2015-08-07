<?php
/**
 * 首页
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-14
 */
defined('BASEPATH') || exit('Access denied');
?>
<!DOCTYPE html>
<html>
<head lang="zh-CN">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title>惠生活生鲜商城 - <?=$siteName?></title>
<meta name="keywords" content="惠生活,江苏生鲜超市,网上生鲜商城,进口食品,生鲜,水果,厨房用品,零食,牛奶" />
<meta name="description" content="江苏惠生活是江浙沪地区最方便快捷的生鲜购物网站，在线销售原味农场天然有机农产品，以及新鲜水果、放心蔬菜、肉禽蛋奶、海鲜水产、休闲食品、酒水茶冲、零食饮料、粮油副食等多种商品，客服电话：400-025-9089" />
<link rel="shortcut icon" href="<?=FILE_DOMAIN?>static/favicon.ico" />
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/myhome.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/index.css?20141213"/>
</head>
<body>
<?php $this->load->view('common/header')?>
<div class="cA area" id="cA">
	<div class="focus">
		<div id="banner" class="slideBox">
			<div class="hd">
				<ul>
					<?php if(isset($fragments[1]) && !empty($fragments[1])):foreach($fragments[1] as $k=>$v):?>
					<li><?=($k+1)?></li>
					<?php endforeach;endif;?>
				</ul>
			</div>
			<div class="bd">
				<ul>
					<?php if(isset($fragments[1]) && !empty($fragments[1])):foreach($fragments[1] as $k=>$v):?>
					<li><a href="<?=$v['url']?>" target="_blank"><img src="<?=$v['img']?>" width="688" height="238" alt="<?=$v['des']?>" /></a></li>
					<?php endforeach;endif;?>
				</ul>
			</div>
			<a class="prev" href="javascript:void(0)"></a>
			<a class="next" href="javascript:void(0)"></a>
		</div>
	</div>
	<div class="login">
		<div class="lbox">
			<?php $userinfo = $this->session->userdata('userinfo');if(!empty($userinfo)):?>
			<p><?php echo iconv_substr($userinfo['username'],0,6,"UTF-8"); ?>**，欢迎访问惠生活！</p>
			<div class="member-logout clearfix">
				<a href="<?=base_url('member/user/logout')?>" class="dl">退出</a><a href="<?=base_url('member/home/index')?>" class="zc">个人中心</a>
			</div>
			<?php else:?>
			<p>您好，欢迎访问惠生活！</p>
			<div class="member-logout clearfix">
				<a href="<?=base_url('member/user/login')?>" class="dl">登录</a><a href="<?=base_url('member/user/register')?>" class="zc">注册</a>
			</div>
			<?php endif;?>
			<div class="bcustomer">
				<a href="<?=base_url('vip/user/login')?>"><em></em>大客户入口</a>
			</div>
		</div>
		<div class="newsBox lbox mt10" id="archive">
			<div class="news_tab hd">
		    	<ul>
		        	<li>公告</li>
		        	<li>资讯</li>
		        </ul>
	        </div>
	        <div class="news_list bd">
		    	<ul>
		    		<?php if( isset($archives[1]) && !empty($archives[1]) ){ ?>
		    		<?php foreach( $archives[1] as $row ){ ?>
		        	<li><a href="<?php echo base_url('archive/'.$row['id'].'.html'); ?>" target="_blank"><?php echo $row['title']; ?></a></li>
		        	<?php } ?>
		        	<?php } ?>
		        </ul>
		        <ul>
		        	<?php if( isset($archives[2]) && !empty($archives[2]) ){ ?>
		    		<?php foreach( $archives[2] as $row ){ ?>
		        	<li><a href="<?php echo base_url('archive/'.$row['id'].'.html'); ?>" target="_blank"><?php echo $row['title']; ?></a></li>
		        	<?php } ?>
		        	<?php } ?>
		        </ul>
	        </div>
	    </div>
	</div>
</div>
<div class="cB mt10 area" id="cB">
	<div class="left">
		<div class="title">
			<span><em>1F</em>新品上架</span>
		</div>
		<div class="mscroll">
			<a class="prev" href="javascript:;" id="thumb_prev"><em>上一页</em></a>
			<a class="next" href="javascript:;" id="thumb_next"><em>下一页</em></a>
			<div class="box" style="width:750px;overflow:hidden;">
				<ul id="thumb_list">
				<?php if(!empty($newest)):foreach($newest as $v):?>
					<li>
						<a href="<?=base_url("goods_{$v['id']}.html")?>" target="_blank"><img class="scrollLoading" data-url="<?=$v['thumb']?>" src="/static/images/1px.gif"  title="<?=$v['seo_title']?>" width="238" height="238" /></a>
						<p class="et txtcenter"><?=$v['title']?></p>
						<h3 class="txtcenter"><?=$v['seo_title']?></h3>
						<p class="prices txtcenter"><em>￥<?=$v['price']?></em></p>
					</li>
				<?php endforeach;endif;?>
				</ul>
			</div>
		</div>
	</div>
	<div class="right">
		<div class="title2"><span>今日最实惠</span></div>
		<?php if( isset($fragments[54]) && !empty($fragments[54]) ){ ?>
		<?php $row = $fragments[54][0]; ?>
		<div class="pp">
			<a href="<?php echo $row['url']; ?>" target="_blank">
				<img class="scrollLoading" data-url="<?php echo $row['img']; ?>" src="/static/images/1px.gif" title="<?php echo $row['des']; ?>" width="340" height="340" />
				<i></i>
				<p class="txt"><?php echo $row['des']; ?></p>
				<p class="prices"><span>￥<em><?php echo $row['extend']; ?></em></span></p>
			</a>
		</div>
		<?php } ?>
	</div>
</div>
<div class="cC mt10 area" id="cc">
	<div class="title"><span><em>2F</em>促销专区</span></div>
	<div class="left">
		<ul class="clearfix">
		<?php if(isset($fragments[17]) && !empty($fragments[17])):foreach($fragments[17] as $k=>$v):?>
			<li <?php if($k==3):?>class="last"<?php endif;?>>
				<a href="<?php echo $v['url']; ?>" target="_blank">
				<img class="scrollLoading" data-url="<?=$v['img']?>" src="/static/images/1px.gif" title="<?=$v['title'].' - '.$v['des']?>" <?php if($k==3):?>width="588"<?php else:?>width="188"<?php endif;?> height="188" />
				<?php if(isset($v['title']) && ($v['title'] != "")):?><i></i><?php endif;?>
				<?php if(isset($v['title']) && ($v['title'] != "")):?><h2><?=$v['title']?></h2><?php endif;?>
				<?php if(isset($v['des']) && ($v['des'] != "")):?><span><?=$v['des']?></span><?php endif;?>
				</a>
			</li>
		<?php endforeach;endif;?>
		</ul>
	</div>
	<div class="center">
		<ul>
		<?php if(isset($fragments[18])):?>
			<?php $v=$fragments[18][0]; ?>
			<li>
				<a href="<?php echo $v['url']; ?>" target="_blank">
				<img class="scrollLoading" data-url="<?=$v['img']?>" src="/static/images/1px.gif" title="<?=$v['title'].' - '.$v['des']?>" width="388" height="388" />
				<i></i>
				<h2><?=$v['title']?></h2>
				<span><?=$v['des']?></span></a>
			</li>
		<?php endif;?>
		</ul>
	</div>
	<div class="right">
		<ul class="clearfix">
		<?php if(isset($fragments[19]) && !empty($fragments[19])):foreach($fragments[19] as $v):?>
			<li>
				<a href="<?php echo $v['url']; ?>" target="_blank">
				<img class="scrollLoading" data-url="<?=$v['img']?>" src="/static/images/1px.gif"  title="<?=$v['title'].' - '.$v['des']?>" width="188" height="188" />
				<i></i>
				<h2><?=$v['title']?></h2>
				<span><?=$v['des']?></span></a>
			</li>
		<?php endforeach;endif;?>
		</ul>
	</div>
</div>

<?php if(!empty($product)):foreach( $product as $k=>$arr ): ?>
<div class="cD mt10 area" id="cd">
    <div class="title mt10">
    	<span><em><?php echo ($k+3); ?>F</em><a href="<?=base_url("cat_{$arr['id']}.html")?>" target="_blank"><?php echo $arr['name'];?></a></span>
    </div>
    <div class="prouductsBox mt10">
    	<div class="fl floor_body">
    		<ul>
    			<?php if(isset($fragments[$k+21]) && !empty($fragments[$k+21])):foreach($fragments[$k+21] as $k=>$v):?>
	        	<li><a href="<?=$v['url']?>" target="_blank"><img class="scrollLoading" data-url="<?=$v['img']?>" src="/static/images/1px.gif" width="200" height="540" /></a></li>
	        	<?php endforeach;endif;?>
        	</ul>
        </div>
        <div class="right">
			<ul class="merlist clearfix">
				<?php if(!empty($arr['list'])):foreach($arr['list'] as $v):?>
				<li>
					<a href="<?=base_url("goods_{$v['id']}.html")?>" target="_blank">
						<img class="scrollLoading" data-url="<?=$v['thumb']?>" src="/static/images/1px.gif" title="<?=$v['seo_title']?>" />
						<h2><?=$v['title']?></h2>
						<p class="txt txtcenter"><?php echo $v['seo_title'] ? $v['seo_title'] : '&nbsp;&nbsp;'; ?></p>
						<p class="prices txtcenter"><em>￥<?=$v['price']?></em>（<?=$v['spec']?>）</p>
					</a>
				</li>
				<?php endforeach;endif;?>
         	</ul>
        </div>
    </div>
</div>
<?php endforeach;endif;?>
<!-- switch city -->
<div class="switch_city">
	<dl></dl>
	<a href="javascript:;" onclick="switchCity('1')"><dt id="city_1" <?php if(SITEID==1):?>class="curr"<?php endif;?>>南京<span></span></dt></a>
	<a href="javascript:;" onclick="switchCity('2')"><dt id="city_2" <?php if(SITEID==2):?>class="curr"<?php endif;?>>扬州<span></span></dt></a>
	<a href="javascript:;" onclick="switchCity('3')"><dt id="city_3" <?php if(SITEID==3):?>class="curr"<?php endif;?>>马鞍山<span></span></dt></a>
</div>
<?php $this->load->view('common/footer')?>
<script src="<?=STATICURL?>js/jquery.carouFredSel-6.2.1-packed.js"></script>
<script src="<?=STATICURL?>js/jquery.scrollLoading-min.js"></script>
<script src="<?=STATICURL?>js/jquery.SuperSlide.2.1.1.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#thumb_list').carouFredSel({
		auto: false,
		prev: '#thumb_prev',
		next: '#thumb_next'
	});
	//延迟加载
	$(".scrollLoading").scrollLoading();
	//滚动
	$("#banner").slide({mainCell:".bd ul",effect:"left",autoPlay:true,delayTime:1000});
	$("#archive").slide({trigger:"click"});
	$(".prouductsBox").slide({mainCell:".floor_body ul",autoPlay:true,autoPage:true,effect:"left"});
	//选择城市
	var welcome = getCookie('WELCOME');
	if(welcome == null){
		$.layer({
			type: 1,
			shade: [0.3, '#000'],
			area: ['auto', 'auto'],
			title: false,
			border: [0],
			page: {dom : '.switch_city'},
			close: function(){
				setCookie('WELCOME', '1');
			}
		});
	}
});
function switchCity(city){
	setCookie('WELCOME', city);
	switchSite(city);
}
</script>
</body>
</html>