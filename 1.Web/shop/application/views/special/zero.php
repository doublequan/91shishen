<?php defined('BASEPATH') || exit('Access denied'); ?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title><?php echo $single['name']; ?> - <?php echo $siteName; ?></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link rel="stylesheet" href="<?php echo STATICURL; ?>css/reset.css"/>
<link rel="stylesheet" href="<?php echo STATICURL; ?>css/myhome.css"/>
<style type="text/css">
*{padding:0;margin:0;}
img{border:0;}
li{list-style:none;}
a{text-decoration:none;}
.zhengyue{width:1176px;margin:0 auto;overflow:hidden;}
.zhengyue .header{margin:0 auto;}
.shuoming{width:1177px;background-color:#fef17f;overflow:hidden;margin:0 auto;position:relative;}
.shuoming li{font-size:14px;color:#222222;font-family:"黑体";margin-left:20px;line-height:34px;}
.shuoming .leftt{text-align: left;}
.zhengfirst{width:1176px;margin:0 auto;}
.zhengfirst ul{width:1176px;margin:0 auto;overflow:hidden;}
.zhengfirst li{width:580px;height:320px;background-color:#FFF;overflow:hidden;float:left;}
.zhengfirst .firsttext{float:right;display:block;width:260px;}
.zhengfirst .firsttext .riqi{display:block;text-align:center;line-height:35px;width:260px;height:35px;background-color:#394575;color:#FFF;font-size:16px;font-family:"微软雅黑";}
.zhengfirst .firsttext .mingzi{font-size:22px;color:#ff5a00;text-overflow:clip;overflow:hidden;white-space:nowrap;margin-left:20px;margin-top:20px;}
.zhengfirst .firsttext font{width:240px;display:block;margin-left:20px;font-size:14px;color:#666666;font-family:微软雅黑;line-height:28px;}
.zhengsecond{width:1176px;margin:0 auto;}
.zhengsecond ul{width:1176px;margin:0 auto;overflow:hidden;}
.zhengsecond li{width:580px;height:290px;background-color:#f8ebe5;overflow:hidden;float:left;}
.zhengsecond .firsttext{float:right;display:block;width:290px;}
.zhengsecond .firsttext .mingzi{margin-left:6px;font-size:24px;color:#ff5a00;line-height:40px;text-overflow:clip;overflow:hidden;white-space:nowrap;margin-top:40px;font-family:黑体;}
.zhengsecond .firsttext font{display:block;font-size:14px;color:#666666;font-family:微软雅黑;line-height:28px;}
.zhengthird{width:1176px;margin:0 auto;overflow:hidden;}
.zhengthird .tejiaqu{width:1176px;margin:0 auto;overflow:hidden;background-color:#FFF;}
.zhengthird li{float:left;margin-top:20px;margin-left:20px;background-color:#f8ebe5;width:269px;height:336px;margin-bottom:20px;}
</style>
</head>
<body>
<?php $this->load->view('common/header')?>

<div class="zhengyue">
	<div class="header" style="height:725px;">
		<img style="width:1176px;display:block;margin:0 auto;" src="http://nj.100hl.cn/views/default/skin/default/images/header03_02.png" />
		<div class="shuoming">
			<div class="leftt">
				<img style="margin-left:20px;margin-bottom:10px;" src="http://nj.100hl.cn/views/default/skin/default/images/hdxz_05_03.png" />
				<ul>
					<li>1、活动时间：2014年11月1日-2014年11月30日；</li>
					<li>2、每个0元购产品每户用户仅可领取一次(以ID及地址等识别)，敬请期待“0元购”产品将在下周开抢；</li>
					<li>3、若订单中有0元购商品，取消该订单则视为放弃0元购该商品资格；</li>
					<li>4、退货、拒收则一并作废0元购资格，0元购产品作收回处理。对于退货、拒收的定义为：订单的1／4价值及以上退货或拒收，即视为退货、拒收。</li>
					<li>5、如有虚假交易、以营利为目的的交易或违反诚信原则的交易，惠生活将取消其对应的活动参与资格。</li>
				</ul>
			</div>
			<img style="position:absolute;right:10px;top:0;" src="http://nj.100hl.cn/views/default/skin/default/images/shijianduan_03.png" />
		</div>
	</div>
	<div class="zhengfirst">
		<img src="http://nj.100hl.cn/views/default/skin/default/images/lyq_03.png" />
		<ul>
			<li>
				<div style="position:relative;float:left;">
					<img style="position:absolute;" src="http://nj.100hl.cn/views/default/skin/default/images/lyqb_03.png" />
					<img src="http://nj.100hl.cn/upload/2014/06/05/20140605032243512.jpg" width="320" height="320"/>
				</div>
				<span href="#" class="firsttext">
					<div class="riqi">第一期：11月01日-11月08日</div>
					<div class="mingzi">雀巢丝滑拿铁咖啡饮料268ml</div>
					<font>
						<p>口感如丝般柔滑</p>
						<p>数量：<span style="color:#cc0000;font-size:18px;">1个</span></p>
						<p>规格：<span style="color:#cc0000;font-size:18px;">268ml/瓶</span></p>
						<p>价格：<span style="color:#ff5a00;font-size:18px;line-height:40px;">￥<span style="font-size:30px;">0</span></span> <span style="text-decoration:line-through;">￥4.88</span></p>
					</font>
					<div style='cursor:pointer;position:relative;'id='myimage'>
						<?php if( 1==2 ){ ?>
						<img style="margin-top:34px;" src="http://nj.100hl.cn/views/default/skin/default/images/yijiarugouwuche_03.png" onclick="" id="image">
						<?php } ?>
						<img style="margin-top:34px;" src="http://nj.100hl.cn/views/default/skin/default/images/mianfeiq_03.png" onclick="javascript:joinCart(2185);" id='image' />
					</div>
				</span>
			</li>
			<li style="margin-left:16px;">
				<div style="position:relative;float:left;">
					<img style="position:absolute;" src="http://nj.100hl.cn/views/default/skin/default/images/lyqb_03.png" />
					<img src="http://nj.100hl.cn/upload/2014/10/11/20141011051901896.jpg" width="320" height="320"/>
				</div>
				<span class="firsttext">
					<div class="riqi">第二期：11月09日-11月15日</div>
					<div class="mingzi">亚洲渔港-深海鳕鱼排（10枚）</div>
					<font>
						<p>新鲜鳕鱼肉秘制，搭配营养面包屑</p>
						<p>数量：<span style="color:#cc0000;font-size:18px;">1个</span></p>
						<p>规格：<span style="color:#cc0000;font-size:18px;">310g/袋</span></p>
						<p>价格：<span style="color:#ff5a00;font-size:18px;line-height:40px;">￥<span style="font-size:30px;">0</span></span> <span style="text-decoration:line-through;">￥14.90</span></p>
					</font>
					<img style="margin-top:34px;" src="http://nj.100hl.cn/views/default/skin/default/images/jingqingqidai_03.png" />
				</span>
			</li>
		</ul>
	</div>
	<div class="zhengsecond">
		<img style="margin-top:20px;" src="http://nj.100hl.cn/views/default/skin/default/images/wuzheyouhui_07.png" />
		<ul>
			<li>
				<a href="/goods_1804.html" style="display:block;float:left;">
					<img src="http://nj.100hl.cn/upload/2014/07/18/20140718102824683.jpg" width="290" height="290"/>
				</a>
				<a href="/goods_1804.html" class="firsttext">
					<div class="mingzi">百加得冰锐预调酒7合1 275ml*7</div>
					<span style="margin-left:6px;line-height:30px;margin-top:10px;font-size:18px;color:#3b9808;font-family:微软雅黑;">百加得冰锐系列 ，组合装，更尽兴</span>
					<font style="margin-left:6px;font-size:20px;margin-top:40px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="color:#ff5a00;font-size:50px;line-height:40px;font-family:Impact;">49.90<span style="font-size:30px;">/份</span></span></font>
					<a href="/goods_1804.html">
						<img style="margin-top:24px;" src="http://nj.100hl.cn/views/default/skin/default/images/lijiqianggou_10.png" />
					</a>
				</a>
			</li>
			<li style="margin-left:16px;">
				<a href="/goods_1832.html" style="display:block;float:left;">
					<img src="http://nj.100hl.cn/upload/2014/07/24/20140724043129378.jpg" width="290" height="290"/>
				</a>
				<a href="/goods_1832.html" class="firsttext">
					<div class="mingzi">聚隆福酱香鸭翅 150g</div>
					<span style="margin-left:6px;line-height:30px;margin-top:10px;font-size:18px;color:#3b9808;font-family:微软雅黑;">聚隆福-金陵一绝</span>
					<font style="margin-left:6px;font-size:20px;margin-top:40px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="color:#ff5a00;font-size:50px;line-height:40px;font-family:Impact;">6.60<span style="font-size:30px;">/份</span></span></font>
					<a href="/goods_1832.html">
						<img style="margin-top:24px;" src="http://nj.100hl.cn/views/default/skin/default/images/lijiqianggou_10.png" />
					</a>
				</a>
			</li> 
		</ul>
	</div>

  <div class="zhengthird"> 
   <img style="margin-top:20px;" src="http://nj.100hl.cn/views/default/skin/default/images/tejiashangpin_17.png" /> 
   <div class="tejiaqu"> 
    <ul> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/2368"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/10/21/20141021021457860.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">南丰贡桔 ≥1kg</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">8.60</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/2368"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/2349"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/10/20/20141020050746594.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">广西恭城脆柿</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">6.80</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/2349"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/730"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/01/25/20140125110346153.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">平和琯溪红心蜜柚 ≥0.9kg</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">10.90</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/730"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/678"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/01/24/20140124025719450.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">陕西皇冠梨 ≧1kg</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">8.20</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/678"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/432"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/03/12/20140312020334261.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">新疆阿克苏冰糖心苹果 ≥1kg</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">16.80</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/432"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/19"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/01/25/20140125110711105.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">四川安岳黄柠檬</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">3.28</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/19"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/2371"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/10/22/20141022103736288.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">甘肃天水黄元帅苹果 ≥1kg</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">16.50</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/2371"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/442"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2013/11/30/20131130014736682.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">印度尼西亚蛇皮果 ≧0.5kg</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">24.00</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/442"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/11"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2013/09/22/20130922051907430.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">菲律宾进口香蕉 ≧1kg</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">11.76</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/11"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/1654"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/05/10/20140510043938473.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">美国进口柠檬</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">16.90</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/1654"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/2370"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/10/22/20141022103251571.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">美国进口青提 ≥1kg</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">32.90</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/2370"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/764"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/01/25/20140125043306937.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">库尔勒香梨特级（箱装约13斤）</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">78.00</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/764"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/1681"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/05/26/20140526032816129.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">红琊山草鸡蛋（30枚）</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">33.80</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/1681"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/2038"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/08/25/20140825035321868.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">科尔沁原味菲力牛排（儿童装、番茄酱）</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">29.90</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/2038"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/2031"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/08/25/20140825030502985.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">科尔沁黑椒牛仔骨</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">29.90</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/2031"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/2027"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/08/25/20140825024845964.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">科尔沁黑椒沙朗牛排</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">29.90</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/2027"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/2024"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/08/25/20140825024030997.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">科尔沁牛肉丸（清香原味）</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">13.90</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/2024"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/2291"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/10/13/20141013030915830.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">元鑫黑椒牛排</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">29.00</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/2291"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/2292"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/10/13/20141013030929795.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">元鑫牛肉饼</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">29.00</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/2292"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/824"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/03/11/20140311112704354.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">肋排</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">22.00</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/824"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/1974"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/08/22/20140822105549207.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">雨润西施骨</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">20.90</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/1974"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/1979"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/10/24/20141024012323132.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">雨润五花肉块</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">16.50</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/1979"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/314"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2013/10/16/20131016061230748.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">去颈前排（小排）</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">15.00</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/314"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/873"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/01/27/20140127045142892.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">鸡翅中（6-8个/袋 约0.25kg）</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">8.90</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/873"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/2336"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/10/29/20141029033839257.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">亚洲渔港-大黄鸭紫薯包（12枚）</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">34.90</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/2336"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/2340"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/10/11/20141011053252463.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">亚洲渔港-猪头奶黄包（15枚）</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">25.00</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/2340"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/2338"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/10/11/20141011052632129.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">亚洲渔港-水晶虾饺（20枚）</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">44.80</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/2338"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/2121"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/10/29/20141029034006151.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">台湾贡丸-香菇味350g</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">16.50</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/2121"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/2122"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/09/02/20140902014208502.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">台湾贡丸-葱香味185g</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">12.20</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/2122"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/2364"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/10/29/20141029033947221.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">狮子头</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">5.50</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/2364"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/1952"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/08/21/20140821033349590.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">康师傅红烧牛肉面105g</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">3.20</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/1952"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/1988"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/09/29/20140929084850124.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">娃哈哈桂圆莲子八宝粥360g</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">3.20</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/1988"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/1954"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/08/21/20140821034749513.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">康师傅红烧牛肉干拌面122g</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">4.70</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/1954"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/2255"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/10/27/20141027100240961.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">香港宝之素银耳莲子港式即食甜品 200g</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">10.20</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/2255"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/2252"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/09/26/20140926015036230.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">香港宝之素椰汁黑糯米港式即食甜品 200g+25g</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">10.90</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/2252"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/2254"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/09/26/20140926015628991.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">香港宝之素西米芦荟港式即食甜品 200g</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">9.50</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/2254"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/619"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2013/12/25/20131225050931711.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">旺仔牛奶245ml*12罐</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">51.50</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/619"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/618"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2013/12/25/20131225042343622.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">特仑苏纯牛奶250ml*12</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">62.80</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/618"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/2395"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/10/31/20141031045753984.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">多美鲜全脂牛奶1L</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">13.90</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/2395"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/1650"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/04/22/20140422031003941.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">伊利金典有机奶250ml*12</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">68.80</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/1650"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/2372"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/10/23/20141023041412767.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">Hochwald好沃德全脂牛奶</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">10.90</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/2372"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/617"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/04/23/20140423101144777.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">莫斯利安原味酸牛奶200ml*12</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">59.90</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/617"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/2380"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/10/31/20141031032502567.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">德亚低脂牛奶1L</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">18.10</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/2380"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/2379"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/10/31/20141031032232856.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">欧德宝超高温处理部分脱脂牛奶1L</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">14.00</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/2379"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/1929"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/08/20/20140820020532828.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">美汁源果粒奶优芒果450ml*15</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">57.20</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/1929"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/1930"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/08/20/20140820020752170.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">美汁源果粒奶优香蕉味450ml*15</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">57.20</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/1930"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/2249"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/09/25/20140925052211482.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">台湾三点一刻炭烧奶茶120g （20g*6）</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">10.90</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/2249"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
     <li style="position:relative;"> <a href="http://nj.100hl.cn/site/products/id/1845"> <img style="border:3px solid #ff5a00;width:263px;height:263px;" src="http://nj.100hl.cn/upload/2014/07/29/20140729033800584.jpg" /> 
       <div class="thirdtext"> 
        <span style="display:block;width:263px;margin-left:6px;text-overflow: ellipsis;overflow:hidden;white-space:nowrap;font-size:16px;color:#ff5a00;line-height:26px;font-family:黑体">华通柠檬冻干 40g</span> 
        <span style="line-height:30px;margin-left:6px;font-size:16px;color:#ff5a00;font-family:微软雅黑;">惠生活价:￥<span style="font-family:Impact;font-size:26px;">29.80</span><b>/</b>份</span> 
       </div> </a><a href="http://nj.100hl.cn/site/products/id/1845"><img style="position:absolute;right:0;bottom:0;" src="http://nj.100hl.cn/views/default/skin/default/images/ljgm_24.png" /></a>  </li> 
    </ul> 
   </div> 
  </div> 
</div>
<?php $this->load->view('common/footer')?>

<script type="text/javascript">
function addCart( product_id ){
	var buy_number = 1;
	layer.load("提交中...");
	$.ajax({
		url: "<?php echo base_url('cart/manage/add'); ?>",
		type: "post",
		dataType: "json",
		data: {"product_id":product_id, "amount":buy_number},
		success: function(data){
			if(data.err_no == 0){
				$("#cart_number").html(data.results.total_number);
				//layer.msg("添加到购物车成功！", 2, 1);
				$.layer({
					shade: [0.3, '#000'],
					area: ['auto','auto'],
					dialog: {
						msg: '添加到购物车成功！',
						type: 1,
						btns: 2,
						btn: ['购物车', '继续购物'],
						yes: function(){
							location.href = "<?php echo base_url('cart/index'); ?>";
						}, no: function(){
							//location.href = "<?=base_url()?>";
						}
					}
				});
			}else{
				layer.msg("添加到购物车失败！", 2, 5);
			}
		}
	});
}
$(document).ready(function(){
	//加入购物车
	$('.addCart').click( function(){
		var product_id = $(this).attr('product_id');
		var n_pattern = /^[1-9][0-9]*$/;
		if( ! n_pattern.test(product_id)){
			layer.msg("商品参数错误", 2, 5);
		} else {
			addCart(product_id);
		}
		return false;
	});
});
</script>
</body>
</html>