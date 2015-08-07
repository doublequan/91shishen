<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/datetimepicker.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                新增员工
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert" style="display:none;"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert" style="display:none;"></div>

            <form action="<?php echo site_url('employee/employee/doAdd'); ?>" method="post" id="employee_form">
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
                                <span><font color="red">*</font></span>
                                姓名
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="text" name="username" class="form-control" id="username">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="account" class="col-md-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                登录账号名
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="text" name="account" class="form-control" id="account">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="pass" class="col-md-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                登录密码
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="password" name="pass" class="form-control" id="pass">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="repass" class="col-md-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                重复密码
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="password" name="repass" class="form-control" id="repass">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="" class="col-md-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                性别
                            </label>
                            <div class="col-md-9 form_input">
                                <label class="radio-inline">
                                    <input type="radio" name="gender" class="gender" value="1" checked> 男
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="gender" class="gender" value="2"> 女
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="idcard" class="col-md-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                身份证号
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="text" name="idcard" class="form-control" id="idcard">
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
                                <input type="text" name="mobile" class="form-control"id="mobile">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="birthday" class="col-md-3 control-label form_label">
                                出生日期
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="text" name="birthday" class="form-control" readOnly id="birthday">
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
                                <input type="text" name="email" class="form-control" id="email">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="address" class="col-md-3 control-label form_label">
                                住址
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="text" name="address" class="form-control" id="address">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p class="bg-info form-square-title">
                                职位信息
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="company_id" class="col-md-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                所属公司
                            </label>
                            <div class="col-md-9 form_input">
                                <select name="company_id" class="form-control" id="company_id">
                                    <option value="0">请选择所属公司</option>
                                <?php 
                                if(!empty($companys)){
                                    foreach ($companys as $company) { 
                                ?>
                                    <option value="<?php echo $company['id']; ?>"><?php echo trim($company['name']); ?></option>
                                <?php }}?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="dept_id" class="col-md-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                所属部门
                            </label>
                            <div class="col-md-9 form_input">
                                <select name="dept_id" class="form-control" id="dept_id">
                                    <option value="">请选择所属部门</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="hire_date" class="col-md-3 control-label form_label">
                                入职日期
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="text" name="hire_date" class="form-control" readOnly id="hire_date">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="expire_date" class="col-md-3 control-label form_label">
                                合同到期日
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="text" name="expire_date" class="form-control" id="expire_date" readOnly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="quit_date" class="col-md-3 control-label form_label">
                                离职日期
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="text" name="quit_date" class="form-control" id="quit_date" readOnly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p class="bg-info form-square-title">
                                角色信息
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="" class="col-md-1 control-label form_label">
                                <span><font color="red">*</font></span>
                                角色
                            </label>
                            <div class="col-md-10 form_input">
                                <?php foreach( $roles as $k=>$row ){ ?>
                                    <label class="checkbox-inline role_ids">
                                        <input type="checkbox" name="role_ids[]" value="<?php echo $k; ?>"> <?php echo $row['name']; ?>
                                    </label>
                                <?php } ?>
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
var deptMap = <?php echo $deptMap ? json_encode($deptMap) : '[]'; ?>;
$(document).ready(function($) {
    $('#company_id').change(function(){
        var html = '<option value="0">请选择所属部门</option>';
        var company_id = $(this).val();
        var depts = deptMap[company_id];
        if( depts!=undefined ){
            for( var i in depts ){
                var row = depts[i];
                html += '<option value="'+row.id+'">'+row.name+'</option>';
            }
        }
        $('#dept_id').html(html);
    });

    $('#birthday').datetimepicker({
        language:  'zh',
        format: 'yyyy-mm-dd',
        autoclose: 1,
        todayHighlight: 0,
        startView: 4,
        minView: 2,
        maxView: 4,
    });
    
    $('#hire_date, #expire_date, #quit_date').datetimepicker({
        language:  'zh',
        format: 'yyyy-mm-dd',
        autoclose: 1,
        todayHighlight: 0,
        startView: 3,
        minView: 2,
        maxView: 3,
    });

    $("#hire_date, #expire_date").change(function(){
        if($("#hire_date").val() && $("#expire_date").val()){
            var hire_date = parseInt(new Date($("#hire_date").val()).getTime());
            var expire_date = parseInt(new Date($("#expire_date").val()).getTime());
            if(hire_date >= expire_date){
                $(this).val('');
                return alert("入职日期需小于合同到期日！");
            }
        }
    });

    $('#employee_form').bootstrapValidator(validate_rules.employee_add).on('success.form.bv', function(e) {
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
                },1000);
            }
        }, 'json');
    });
});
</script>
</body>
</html>
