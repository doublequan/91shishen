<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                编辑街道名称
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert"></div>

            <form action="<?php echo site_url('system/area/doStreetEdit'); ?>" method="post" id="edit_street_form">
                <input type="hidden" name="id" value="<?php echo $single['id']; ?>">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="" class="col-md-3 control-label form_label">
                            <span><font color="red">*</font></span>
                            城市
                        </label>
                        <div class="col-md-9 form_input">
                            <p class="form-control-static"><?php echo $city['name']; ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-md-3 control-label form_label">
                            <span><font color="red">*</font></span>
                            区县
                        </label>
                        <div class="col-md-9 form_input">
                            <p class="form-control-static"><?php echo $district['name']; ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="street" class="col-md-3 control-label form_label">
                            <span><font color="red">*</font></span>
                            街道名称
                        </label>
                        <div class="col-md-9 form_input">
                            <input type="text" name="street" class="form-control" id="street" value="<?php echo @$single['name']; ?>">
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

<iframe src="" id="add_form_page" style="height:500px;width:900px;"></iframe>

<script src="<?php echo base_url('static/js/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function($) {
    $('.alert').hide();

    $('#edit_street_form')
        .bootstrapValidator({
            message: '输入格式错误，请检查',
            fields: {
                street: {
                    validators: {
                        notEmpty: {
                            message: '请填写街道名称'
                        },
                        stringLength: {
                            enabled: true,
                            min: 1,
                            max: 250,
                            message: '街道名称格式错误'
                        },
                    }
                },
            }
        })
        .on('success.form.bv', function(e) {
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');
            $('.alert').hide();

            $.post($form.attr('action'), $form.serialize(), function(rst_json) {
                console.log(rst_json);
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
