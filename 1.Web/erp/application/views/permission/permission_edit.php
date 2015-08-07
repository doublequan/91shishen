<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">
<script src="<?php echo base_url('static/js/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                编辑权限信息
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert" style="display:none;"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert" style="display:none;"></div>

            <form action="<?php echo site_url('permission/permission/doEdit'); ?>" id="permission_form" method="post">
                <input type="hidden" name="id" value="<?php echo $single['id']; ?>">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label form_label">
                            <span><font color="red">*</font></span>
                            权限名称
                        </label>
                        <div class="col-sm-8 form_input">
                            <input type="text" name="name" class="form-control" id="name" value="<?php echo $single['name']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="key" class="col-sm-3 control-label form_label">
                            <span><font color="red">*</font></span>
                            权限KEY
                        </label>
                        <div class="col-sm-8 form_input">
                            <input type="text" name="key" class="form-control" id="key" value="<?php echo $single['key']; ?>">
                            <span class="help-block">格式为“<strong style="color:red">controller</strong>”或者“<strong style="color:red">controller/method</strong>”</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label form_label">
                            <span><font color="red">*</font></span>
                            所属权限组
                        </label>
                        <div class="col-sm-8 form_input">
                            <select name="group_id" class="form-control">
                                <option value="">请选择所属权限组</option>
                            <?php foreach ($groups as $group) { ?>
                                <option value="<?php echo $group['id']; ?>" <?php echo ($group['id']==$single['group_id'])?'selected':''; ?>><?php echo trim($group['name']); ?></option>
                            <?php } ?>
                            </select>
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

<script type="text/javascript">
$(document).ready(function($) {
    $('#permission_form').bootstrapValidator(validate_rules.permission).on('success.form.bv', function(e) {
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