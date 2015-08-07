<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                编辑部门信息
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert"></div>

            <form action="<?php echo site_url('enterprise/dept/doEdit'); ?>" id="dept_form" method="post">
                <div class="form-horizontal">
                    <input type="hidden" name="id" value="<?php echo @$single['id']; ?>">
                    <input type="hidden" name="father_id" id="father_id" value="">
                    <div class="form-group select_line">
                        <label for="" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            所属公司
                        </label>
                        <div class="col-sm-9 form_input">
                            <select name="company_id" class="form-control dept_select" id="company_id">
                                <option value="">请选择所属公司</option>
                            <?php 
                            if(!empty($companys)){
                                foreach ($companys as $company) { 
                            ?>
                                <option value="<?php echo $company['id']; ?>" father_id="0" <?php echo $company['id']==$single['company_id']?'selected':''; ?>><?php echo trim($company['name']); ?></option>
                            <?php }}?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group select_line">
                        <label for="" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            上级部门
                        </label>
                        <div class="col-sm-9 form_input">
                            <select name="father_id" class="form-control" id="father_id">
                                <option value="0">无</option>
                                <?php echo getDeptTreeOptions(@$department_list); ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="dept_name_line">
                        <label for="name" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            新部门名称
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="name" class="form-control" id="name" value="<?php echo $single['name']; ?>">
                        </div>
                    </div>

                    <div class="form-group" id="dept_leader_line">
                        <label for="name" class="col-sm-2 control-label form_label">
                            部门主管
                        </label>
                        <div class="col-sm-7 form_input">
                            <input type="text" class="form-control" id="employee_name" name="employee_name" readOnly  value="<?php echo $leader ? $leader['username'] : ''; ?>">
                            <input type="hidden" name="leader" class="form-control" id="employee_id" value="<?php echo $leader ? $leader['id'] : 0; ?>">
                        </div>
                        <div class="col-sm-2 form_input">
                            <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-default" id="add_form_btn">选择员工</a>
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

<iframe src="" id="add_form_page" style="height:600px;width:800px;"></iframe>

<script src="<?php echo base_url('static/js/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/jquery.fancybox.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function($) {
    window.setTimeout(function(){ 
        $('#success_alert, #danger_alert').hide(); 
    },4000);

    $('.alert, #father_id option.dept_ops').hide();

    var dept_selected = "<?php echo isset($single['father_id'])?$single['father_id']:0; ?>";
    var dept_company_id = "<?php echo isset($single['company_id'])?$single['company_id']:0; ?>";

    $('#father_id option.dept_ops[value="' + dept_selected + '"]').attr('selected', true);
    $('#father_id option.dept_ops[company_id="' + dept_company_id + '"]').show();

    $('#company_id').change(function(event) {
        var company_id = $(this).val();
        if(company_id){
            $('#father_id option.dept_ops').removeAttr('selected').hide();
            $('#father_id option.dept_ops[company_id="'+ company_id + '"]').show();
        }
    });

    $('a#add_form_btn').click(function(){
        $('iframe#add_form_page').attr('src', "<?php echo site_url('employee/employee/select_dialog'); ?>");
        $("a#add_form_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            //'afterClose': function(){
            //    $('#dept_form').bootstrapValidator('updateStatus', 'employee_name', 'NOT_VALIDATED')
            //                    .bootstrapValidator('validateField', 'employee_name');
            //},
        });
    });

    $('#dept_form')
        .bootstrapValidator(validate_rules.department)
        .on('success.form.bv', function(e) {
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');

            $('.alert').hide();
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
                    },1000);
                }
            }, 'json');
        });
});

</script>
