<?php
/**
 * 商品列表
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-4
 */
defined('BASEPATH') || exit('Access denied');
?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title>手机惠生活|惠生活移动客户端下载 - <?=$siteName?></title>
<meta name="keywords" content="手机惠生活,惠生活移动客户端" />
<meta name="description" content="" />
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/download.css"/>
</head>
<body>
<?php $this->load->view('common/header')?>

<div class="download_Box">
	<div class="area appDownload">
    	<div class="fl" style="width:620px;">
        	<img src="<?=STATICURL?>images/phone.png" width="660">
        </div>
        <div class="fl">
        	<Div class="txtBox">
                <p class="tit">
                    惠生活
                </p>
                <p>
                    <img src="<?=STATICURL?>images/text.png" title="网购放心菜，就到惠生活" />
                </p>
            </Div>
            <div class="btnBox">
            	<Div class="fl btn">
                    <a href="https://itunes.apple.com/cn/app/hui-sheng-huo+/id939934888?mt=8" target="_blank" class="iphoneDownload">
                    </a>
                    <a href="https://itunes.apple.com/cn/app/hui-sheng-huo+/id939934888?mt=8" target="_blank" class="ipadDownload">
                    </a>
                    <a href="http://static.100hl.cn/app/android/100hl_1_0_0.apk?v=20141110" target="_blank" class="androidDownload">
                    </a>
                </Div>
                <div class="fl qrBox">
                	<img src="/static/images/qr_android.png" />
                </div>
                <div class="fl qrBox" style="margin-left:10px;">
                    <img src="/static/images/qr_ios.png" />
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('common/footer')?>
<script src="<?=STATICURL?>js/jquery.carouFredSel-6.2.1-packed.js"></script>
<script src="<?=STATICURL?>js/jquery.scrollLoading-min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#thumb_list').carouFredSel({
		auto: false,
		prev: '#thumb_prev',
		next: '#thumb_next',
		direction: 'down',
	});
	//延迟加载
	$(".scrollLoading").scrollLoading();
});
</script>
</body>
</html>