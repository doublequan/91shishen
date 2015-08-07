<?php
require_once('../../config.php'); 

$site_url = $site['url'];
$script = array('shake_free');

$page['title'] = "摇一摇,0元购!";


include '../../layout/header.php'; 
?>


<div class="layout activity-content">
	
    
    
    <div class="shake_free-box">
        <div class="shake_free-title">
            摇一摇,0元购!
        </div>
        <div class="shake_free-image">
            <audio class="shake_free-audio-before"  preload="auto" src="//static.100hl.cn/static/audio/before.mp3"></audio>
            <audio class="shake_free-audio-end"  preload="auto" src="//static.100hl.cn/static/audio/end.mp3"></audio>
            <a href="javascript:;" id="shake_btn"> <img src="../../assets/images/shake.png" /> </a>
        </div>
        <div class="shake_free-cart">
            <a href="javascript:;" class="btn btn-red padding shake_free-cart-btn" data-goods-id=""><i class="icon-goods"></i> 加入购物车</a>
        </div>
        <div class="shake_free-desc">
            <h3>活动说明:</h3>
            <ul>
                <li>闲暇时刻摇一摇，0元产品淘一淘，就是要你满意~~~</li>
                <li>摇着不方便？点击图片也可以哦</li>
                <li>每人每天只能摇一款哦，想要更多？明儿接着摇喽=_=</li>
                <li>目前活动只支持南京本地，我们会继续努力的~~</li>
            </ul>
        </div>
    </div>
    
</div>

<?php include '../../layout/footer.php';?>