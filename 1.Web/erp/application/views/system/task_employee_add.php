<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                新增任务处理人
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert"></div>

            <form action="<?php echo site_url('system/task/doAdd'); ?>" method="post" id="task_form">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="" class="col-md-3 control-label form_label">
                            <span><font color="red">*</font></span>
                            任务类型
                        </label>
                        <div class="col-md-8 form_input">
                            <select name="type" class="form-control" id="type">
                            <?php foreach ($task_types as $k=>$v) { ?>
                                <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                            <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-md-3 control-label form_label">
                            <span><font color="red">*</font></span>
                            负责人
                        </label>
                        <div class="col-sm-6 form_input">
                            <input type="text" name="employee_name" class="form-control" id="employee_name" readOnly>
                            <input type="hidden" name="eid" class="form-control" id="employee_id">
                        </div>
                        <div class="col-sm-2 form_input">
                            <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-default btn-block" id="add_employee_btn">选择员工</a>
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

<iframe src="" id="add_form_page" style="width:900px;height:560px;"></iframe>

<script src="<?php echo base_url('static/js/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/jquery.fancybox.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function($) {
    $('.alert').hide();
    
    $('a#add_employee_btn').click(function(){
        $('iframe#add_form_page').attr('src', "<?php echo site_url('employee/employee/select_dialog'); ?>");
        $("a#add_employee_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            'afterClose': function(){
                $('#task_form').bootstrapValidator('updateStatus', 'employee_name', 'NOT_VALIDATED')
                                .bootstrapValidator('validateField', 'employee_name');
            },
        });
    });

    $('#task_form')
        .bootstrapValidator(validate_rules.task_employee)
        .on('success.form.bv', function(e) {
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');

            $.post($form.attr('action'), $form.serialize(), function(rst_json) {
                if(rst_json.err_no != 0){
                    $('#danger_alert').empty().text(rst_json.err_msg).show();
                    $('#submit_btn').removeAttr('disabled');
                    return;
                }
                else{
                    $('#danger_alert').hide();
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
