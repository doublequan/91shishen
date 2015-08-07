<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                新增规格
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert"></div>
            <form action="<?php echo site_url('products/spec/doAdd'); ?>" method="post" name="spec_form" id="spec_form">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label form_label">
                            <span><font color="red">*</font></span>
                            规格类型
                        </label>
                        <div class="col-sm-8 form_input">
                            <select name="type" class="form-control">
                            <?php foreach ($typeMap as $k=>$v) { ?>
                                <option value="<?php echo $k; ?>"><?php echo trim($v); ?></option>
                            <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label form_label">
                            <span><font color="red">*</font></span>
                            规格名称
                        </label>
                        <div class="col-sm-8 form_input">
                            <input type="text" name="name" class="form-control" value="" id="name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="url" class="col-sm-3 control-label form_label">
                            <span><font color="red">*</font></span>
                            计价单位
                        </label>
                        <div class="col-sm-8 form_input">
                            <input type="text" name="unit" class="form-control" value="" id="unit">
                        </div>
                    </div>
                </div>
                <div class="form-group form_btn_line">
                    <div class="col-sm-2 col-sm-offset-2 text-center">
                        <input type="submit" class="btn btn-primary btn-block" value="提 交" /> 
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

    $('#spec_form')
        .bootstrapValidator(validate_rules.spec_add)
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
