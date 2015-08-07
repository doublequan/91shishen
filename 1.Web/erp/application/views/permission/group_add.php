<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                添加权限组
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert"></div>

            <form action="<?php echo site_url('permission/group/doAdd'); ?>" id="group_form" method="post">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label form_label">
                            <span><font color="red">*</font></span>
                            权限组名称
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="name" class="form-control" id="name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="key" class="col-sm-3 control-label form_label">
                            <span><font color="red">*</font></span>
                            权限组KEY
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="key" class="form-control" id="key">
                            <span class="help-block">使用3-20位英文字母</span>
                        </div>
                    </div>
                    
                    <div class="form-group form_btn_line">
                        <div class="col-sm-2 col-sm-offset-5 text-center">
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
<script type="text/javascript">
$(document).ready(function($) {
    $('.alert').hide();

    $('#group_form')
        .bootstrapValidator(validate_rules.permission_group)
        .on('success.form.bv', function(e) {
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
