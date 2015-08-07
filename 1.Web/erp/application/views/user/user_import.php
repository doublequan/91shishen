<link href="<?php echo base_url('static/css/bootstrap.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">
<script src="<?php echo base_url('static/js/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                批量导入用户
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert"></div>
            <form action="<?php echo site_url('user/user/doAdd'); ?>" method="post" name="user" id="user_form" enctype="multipart/form-data">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="bg-info form-square-title">
                                基本信息
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="logo" class="col-sm-3 control-label form_label">
                                品牌Logo
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="file" name="logo" class="form-control" value="" id="logo" />
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sort" class="col-sm-3 control-label form_label">
                                排序
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="sort" class="form-control" value="<?php echo set_value('sort', @$sort); ?>" id="sort">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="name" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                用户组
                            </label>
                            <div class="col-sm-9 form_input">
                                <select name="group_id" class="form-control input-sm input-sm" id="group_id">
                                <?php foreach ($groups as $group) { ?>
                                    <option value="<?php echo $group['id']; ?>"><?php echo trim($group['name']); ?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="name" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                用户名
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="username" class="form-control" value="" id="username">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="url" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                邮箱
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="email" class="form-control" value="" id="email" />
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="logo" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                手机号码
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="mobile" class="form-control" value="" id="mobile" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="logo" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                密码
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="password" name="pass" class="form-control" value="" id="pass" />
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sort" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                重复密码
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="password" name="repass" class="form-control" value="" id="repass" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="logo" class="col-sm-3 control-label form_label">
                                会员卡卡号
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="cardno" class="form-control" value="" id="cardno" />
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sort" class="col-sm-3 control-label form_label">
                                用户折扣率
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="discount" class="form-control" value="" id="discount" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group form_btn_line">
                        <div class="col-sm-2 col-sm-offset-5 text-center">
                            <input type="submit" class="btn btn-primary btn-block" value="提 交" /> 
                        </div>
                    </div>
                                
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function($) {
    window.setTimeout(function(){ 
        $('#success_alert, #danger_alert').hide(); 
    },4000);
    
    $('.alert').hide();

    $('#user_form')
        .bootstrapValidator(validate_rules.user_add)
        .on('success.form.bv', function(e) {
            if($('#pass').val()!==$('#repass').val()){
                alert('两次输入的密码不一致！');
                $('#submit_btn').removeAttr('disabled');
                return false;
            }
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');

            $.post($form.attr('action'), $form.serialize(), function(rst_json) {
                if(rst_json.err_no != 0){
                    $('#danger_alert').empty().text(rst_json.err_msg).show();
                    return;
                }
                else{
                    $('#success_alert').empty().text('添加成功！').show();
                    window.setTimeout(function(){
                        window.parent.location.reload();
                        window.parent.$.fancybox.close('fade');
                    },2000);
                }
            }, 'json');
            
        });
});

</script>
