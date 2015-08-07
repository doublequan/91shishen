<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/datetimepicker.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                编辑个人信息
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert"></div>

            <form action="<?php echo site_url('my/updateInfo'); ?>" method="post" id="my_info_form">
                <input type="hidden" name="id" value="<?php echo @$single['id']; ?>">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="bg-info form-square-title">
                                基本信息
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="username" class="col-md-3 control-label form_label">
                                姓名
                            </label>
                            <div class="col-md-9 form_input">
                                <p class="form-control-static"><?php echo $single['username']; ?></p>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="idcard" class="col-md-3 control-label form_label">
                                身份证号
                            </label>
                            <div class="col-md-9 form_input">
                                <p class="form-control-static"><?php echo $single['idcard']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="mobile" class="col-md-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                手机号
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="text" name="mobile" class="form-control" id="mobile" value="<?php echo @$single['mobile']; ?>">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="birthday" class="col-md-3 control-label form_label">
                                出生日期
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="text" name="birthday" class="form-control" readOnly id="birthday" 
                                    value="<?php echo (isset($single['birthday']) && $single['birthday']!='0000-00-00')?$single['birthday']:''; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="email" class="col-md-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                邮箱
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="text" name="email" class="form-control" id="email" value="<?php echo @$single['email']; ?>">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="address" class="col-md-3 control-label form_label">
                                住址
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="text" name="address" class="form-control" id="address" value="<?php echo @$single['address']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p class="bg-info form-square-title">
                                修改密码
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="oldpass" class="col-md-3 control-label form_label">
                                老密码
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="password" name="oldpass" class="form-control" id="oldpass">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="col-md-12 form_input">
                                <p class="form-control-static" style="color:red;font-weight:700;">不修改密码请留空</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="pass" class="col-md-3 control-label form_label">
                                新密码
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="password" name="pass" class="form-control" id="pass">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="repass" class="col-md-3 control-label form_label">
                                再次输入
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="password" name="repass" class="form-control" id="repass">
                            </div>
                        </div>
                    </div>
                    <div class="form-group form_btn_line">
                        <div class="col-md-2 col-md-offset-5 text-center">
                            <button type="submit" class="btn btn-primary btn-block" id="submit_btn">提 交</button> 
                        </div>
                    </div>        
                </div> 
            </form>
        </div>
    </div>
</div>

<script src="<?php echo base_url('static/js/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/bootstrap-datetimepicker.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function($) {
    $('.alert, .dept_id_option').hide();

    $('#birthday').datetimepicker({
        language:  'zh',
        format: 'yyyy-mm-dd',
        autoclose: 1,
        todayHighlight: 0,
        startView: 4,
        minView: 2,
        maxView: 4,
    });

    $('#my_info_form').bootstrapValidator(validate_rules.my_info).on('success.form.bv', function(e) {
        var oldpass = $('#oldpass').val();
        if( oldpass.length>0 ){
            var pass = $('#pass').val();
            var repass = $('#repass').val();
            if( pass.length<6 || repass.length<6 ){
                alert('密码不能少于6位');
                return false;
            }
            if( pass!=repass ){
                alert('两次输入的密码不一致');
                return false;
            }
        }
        e.preventDefault();
        var $form = $(e.target);
        var bv = $form.data('bootstrapValidator');

        $.post($form.attr('action'), $form.serialize(), function(rst_json) {
            if(rst_json.err_no != 0){
                $('#danger_alert').empty().text(rst_json.err_msg).show();
                return;
            } else {
                $('#success_alert').empty().text('修改成功'+(rst_json.is_logout?'，请重新登陆':"'")+'！').show();
                window.setTimeout(function(){
                    window.parent.location.reload();
                    window.parent.$.fancybox.close('fade');
                },2000);
            }
        }, 'json');
    });
});
</script>
