<?php
/**
 * 通用底部
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-21
 */
defined('BASEPATH') || exit('Access denied');
?>

<div id="foot">
	<div class="area">
		<div class="info">
			<dl>
				<dt><a href="/archive/list_3.html" target="_blank">购物指南</a></dt>
				<dd><a href="/archive/14.html" target="_blank">会员注册协议</a></dd>
				<dd><a href="/archive/1.html" target="_blank">会员购物流程</a></dd>
				<dd><a href="/archive/2.html" target="_blank">会员积分说明</a></dd>
				<dd><a href="/archive/6.html" target="_blank">配送范围及运费</a></dd>
			</dl>
			<dl>
				<dt><a href="/archive/list_4.html" target="_blank">支付帮助</a></dt>
				<dd><a href="/archive/15.html" target="_blank">会员卡密码修改</a></dd>
				<dd><a href="/archive/4.html" target="_blank">会员卡使用说明</a></dd>
			</dl>
			<dl>
				<dt><a href="/archive/list_5.html" target="_blank">售后服务</a></dt>
				<dd><a href="/archive/5.html" target="_blank">货到付款</a></dd>
				<dd><a href="/archive/10.html" target="_blank">售后服务</a></dd>
				<dd><a href="/archive/7.html" target="_blank">退换货说明</a></dd>
			</dl>
			<dl>
				<dt><a href="/archive/list_7.html" target="_blank">惠生活信息</a></dt>
				<dd><a href="/archive/11.html" target="_blank">会员注册章程</a></dd>
				<dd><a href="/archive/9.html" target="_blank">联系客服</a></dd>
				<dd><a href="/archive/8.html" target="_blank">关于我们</a></dd>
				<dd><a href="/archive/22.html" target="_blank">重大记事</a></dd>
			</dl>
			<dl>
				<dt><a href="/archive/list_8.html" target="_blank">招贤纳士</a></dt>
				<dd><a href="/archive/23.html" target="_blank">市场</a></dd>
				<dd><a href="/archive/21.html" target="_blank">产品</a></dd>
				<dd><a href="/archive/20.html" target="_blank">线下运营</a></dd>
				<dd><a href="/archive/19.html" target="_blank">大客户</a></dd>
				<dd><a href="/archive/18.html" target="_blank">供应链</a></dd>
				<dd><a href="/archive/17.html" target="_blank">技术</a></dd>
				<dd><a href="/archive/16.html" target="_blank">财务</a></dd>
			</dl>
		</div>
		<div class="phone">
			热线电话：400-025-9089&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;周一至周日：9:00-17:35&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;QQ客服：4000259089
		</div>
		<div class="copyright">
			<a href="http://www.miitbeian.gov.cn/" target="_blank">苏ICP备13024189号-1</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&copy; 江苏惠生活电子商务股份有限公司 版权所有
		</div>
		<p style="display:none;">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
	</div>
</div>
<div class="fix-rr">
    <a href="javascript:void(0);"><div id="qqkf" class="sidefixed wb">客服在线</div></a>
    <div id="web_div" class="sidefixed wb1" >
    <div class="fixclose weibo"><img src="/static/images/weibo.png" alt=""></div>
    </div>
    <div id="wx_div" class="sidefixed wb2">
    <div class="fixclose weixin"><img src="/static/images/weixin.png" alt=""></div>
    </div>
    <div id="goTop" class="sidefixed wb3" ></div>
</div>
<script src="<?=STATICURL?>js/jquery-1.9.1.min.js"></script>
<script src="<?=STATICURL?>layer/layer.min.js"></script>
<script src="<?=STATICURL?>js/common.js"></script>
<script src="http://wpa.b.qq.com/cgi/wpa.php" charset="utf-8"></script>
<script type="text/javascript">
//分类效果
$(function(){

	//客服代码
	BizQQWPA.addCustom({aty: '0', a: '0', nameAccount: 4000259089, selector: 'qqkf'});

	<?php if(empty($is_home)):?>
	$("#topnav").hover(function(){
		$(".hsh_sort").show();
	},function(){
		$(".hsh_sort").hide();
	});
	<?php endif;?>
	$(".sidefixed").hover(function(){
        $(this).addClass("active").siblings().removeClass("active");
    });
    $(".fix-rr").mouseleave(function(){
        $(".sidefixed").removeClass("active");
    });
    $("#goTop").click(function(){
		$("html,body").animate({scrollTop :0}, 800);
		return false;
	});
});
</script>

<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F2123c8400ca60adf0dc8617761dfa16e' type='text/javascript'%3E%3C/script%3E"));
</script>