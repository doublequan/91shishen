<link href="<?php echo base_url('static/css/bootstrap.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                编辑用户组
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert"></div>
            <form action="<?php echo site_url('user/group/doEdit'); ?>" method="post" id="group_form">
                <input type="hidden" name="id" value="<?php echo $single['id']; ?>">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="url" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                用户组名称
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="name" class="form-control" value="<?php echo $single['name']; ?>" id="name">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="logo" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                优惠率
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="discount" class="form-control" value="<?php echo $single['discount']; ?>" id="discount">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="logo" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                最低积分
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="min" class="form-control" value="<?php echo $single['min']; ?>" id="min">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sort" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                最高积分
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="max" class="form-control" value="<?php echo $single['max']; ?>" id="max">
                                <span class="help-block">0为不限</span>
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

<script src="<?php echo base_url('static/js/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function($) {
    window.setTimeout(function(){ 
        $('#success_alert, #danger_alert').hide(); 
    },4000);
    
    $('.alert').hide();

    $('#group_form').bootstrapValidator(validate_rules.group_add).on('success.form.bv', function(e) {
        e.preventDefault();
        var $form = $(e.target);
        var bv = $form.data('bootstrapValidator');
        $.post($form.attr('action'), $form.serialize(), function(rst_json) {
            if(rst_json.err_no != 0){
                $('#danger_alert').empty().text(rst_json.err_msg).show();
                return;
            }
            else{
                $('#success_alert').empty().text('修改成功！').show();
                window.setTimeout(function(){
                    window.parent.location.reload();
                    window.parent.$.fancybox.close('fade');
                },2000);
            }
        }, 'json');
    });
});

</script>
