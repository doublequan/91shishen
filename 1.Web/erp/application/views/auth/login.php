<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>惠生活进销存管理系统</title>
<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">
<style type="text/css">
body{
    background-color: #eeeeee;
    padding-top: 100px;
}
#login-header{
    color:#666666;
    padding-bottom: 15px;
}
.glyphicon-home{
    font-size: 20px;
}
.panel-footer{
    padding: 20px 15px;
}
.panel-default{
    box-shadow: 0 0 6px 2px rgba(0, 0, 0, 0.1);
    background: rgba(255, 255, 255, 0.65);
}
.panel-body{
    padding-top: 35px;
}
</style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 text-center" id="login-header">
            <h3><span class="glyphicon glyphicon-home"></span> 惠生活后台管理系统</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-4 col-xs-offset-4">
            <form role="form" action="<?php echo site_url('auth/doLogin'); ?>" method="post" id="login_form">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="alert alert-danger" id="danger_alert" role="alert" style="display:none;"></div>
                        <div class="form-group">
                            <label for="account">用户名</label>
                            <input type="text" class="form-control" name="account" id="account" placeholder="员工账号">
                        </div>
                        <div class="form-group">
                            <label for="pass">密码</label>
                            <input type="password" class="form-control" name="pass" id="pass" placeholder="密码" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="verify">验证码</label>
                            <div class="row">
                                <div class="col-xs-7">
                                    <input type="text" class="form-control" id="verify" name="verify">
                                </div>
                                <div class="col-xs-5">
                                    <p class="form-control-static">
                                        看不清，<a id="changeV" class="flk13" href="javascript:void(0)">换一张</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div id="verify_img"></div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button type="submit" class="btn btn-primary btn-block" id="login_btn">登 录</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?php echo base_url('static/js/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function($) {
    var input = $('#verify_img');
    function showVerify(){
        var date = new Date();
        var ttime = date.getTime();
        var url = "<?php echo base_url(); ?>" + '/verify/img.php?t='+ttime;
        /**
        img = ' <object id="verifyimg" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="160" height="60"> <param name="movie" value="'+URL+'"> <param name="quality" value="high"> <param name="wmode" value="transparent"> <embed src="'+URL+'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="160" height="60" wmode="transparent"></embed> </object>';
        $('#verifyimg').remove();
        */
        var img = '<img src="'+url+'" />';
        input.empty().html(img);
        return false;
    }
    showVerify();
    $("#changeV").click(function(){
        showVerify();
    });

    $('#login_form')
        .bootstrapValidator({
            message: 'This value is not valid',
            fields: {
                account: {
                    message: '请重新输入用户名！',
                    validators: {
                        notEmpty: {
                            message: '请输入用户名！'
                        },
                        stringLength: {
                            min: 3,
                            max: 20,
                            message: '用户名长度错误'
                        },
                        regexp: {
                            regexp: /^[a-z]{3,18}[0-9]{0,2}$/,
                            message: '用户名以英文开头且仅包含英文字母和数字'
                        }
                    }
                },
                pass: {
                    validators: {
                        notEmpty: {
                            message: '请输入密码！'
                        },
                    }
                },
                verify: {
                    validators: {
                        notEmpty: {
                            message: '请输入验证码！'
                        },
                    }
                },
            }
        })
        .on('success.form.bv', function(e) {
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');

            $.post($form.attr('action'), $form.serialize(), function(rst_json) {
                if(rst_json.err_no != 0){
                    $('#danger_alert').empty().text(rst_json.err_msg).show();
                    return;
                } else {
                    window.location.href = "<?php echo $backurl ? $backurl : site_url(); ?>";
                }
            }, 'json');
        });
});
</script>
</body>
</html>