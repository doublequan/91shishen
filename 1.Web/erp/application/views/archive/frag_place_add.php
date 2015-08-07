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
                添加碎片位置
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert"></div>
            <form action="<?php echo site_url('archive/frag/doAddPlace'); ?>" id="form" method="post">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="name" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                所属网站
                            </label>
                            <div class="col-sm-9 form_input">
                                <select name="site_id" class="form-control" id="site_id">
                                    <option value="">请选择所属网站</option>
                                <?php foreach ($sites as $row) { ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo trim($row['name']); ?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="name" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                系统类型
                            </label>
                            <div class="col-sm-9 form_input">
                                <select name="os" class="form-control" id="os">
                                    <option value="">请选择所属系统</option>
                                <?php foreach ($os_types as $k=>$v) { ?>
                                    <option value="<?php echo $k; ?>"><?php echo trim($v); ?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="name" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                位置名称
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="name" class="form-control" id="name">
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="name" class="col-sm-3 control-label form_label">
                                是否锁定
                            </label>
                            <div class="col-sm-9 form_input">
                                <label class="radio-inline">
                                    <input type="radio" name="is_lock" value="1"> 锁定
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="is_lock" value="0" checked> 不锁定
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <div class="col-sm-12 form_input">
                                <p class="form-control-static text-center">碎片名称仅作为管理参考，如“安卓APP首页Banner”</p>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <div class="col-sm-12 form_input">
                                <p class="form-control-static text-center">锁定后，不可新增内容；建议添加内容完成后锁定。</p>
                            </div>
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
    window.setTimeout(function(){ 
        $('#success_alert, #danger_alert').hide(); 
    },4000);
    
    $('.alert').hide();

    $('#form').bootstrapValidator(validate_rules.frag_place).on('success.form.bv', function(e) {
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
