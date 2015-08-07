<?php
/**
 * 跳转页面
 * @author LiuPF<mail@phpha.com>
 * @date 2014-9-21
 */
defined('BASEPATH') || exit('Access denied');
?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<meta http-equiv="refresh" content="3;url=<?=$url?>">
<title><?=$message?> - 系统提示 - <?=$siteName?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/gwc.css"/>
</head>
<body>
<?php $this->load->view('common/login_header')?>
<div class="success area">
	<p>提示：<?=$message?></p>
	<p>（系统将在 3 秒后自动跳转）<br /><a href="<?=$url?>">【立即跳转】</a>&nbsp;&nbsp;<a href="/">【返回首页】</a></p>
</div>
<?php $this->load->view('common/footer')?>
</body>
</html>