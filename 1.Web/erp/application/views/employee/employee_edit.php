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
                编辑员工信息
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert" style="display:none;"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert" style="display:none;"></div>

            <form action="<?php echo site_url('employee/employee/doEdit'); ?>" method="post" id="employee_form">
                <input type="hidden" name="id" value="<?php echo @$single['id']; ?>">
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
                            <label for="account" class="col-md-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                登录账号
                            </label>
                            <div class="col-md-9 form_input">
                                <p class="form-control-static"><?php echo $single['account']; ?></p>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="username" class="col-md-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                姓名
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="text" name="username" class="form-control" id="username" value="<?php echo @$single['username']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="pass" class="col-md-3 control-label form_label">
                                登录密码
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="password" name="pass" class="form-control" id="pass" placeholder="不修改请留空">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="repass" class="col-md-3 control-label form_label">
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
                                    <input type="radio" name="gender" class="gender" value="1" <?php echo @$single['gender']==1?'checked':''; ?>> 男
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="gender" class="gender" value="2" <?php echo @$single['gender']==2?'checked':''; ?>> 女
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="idcard" class="col-md-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                身份证号
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="text" name="idcard" class="form-control" id="idcard" value="<?php echo @$single['idcard']; ?>">
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
                                <input type="text" name="mobile" class="form-control" id="mobile" value="<?php echo @$single['mobile']; ?>">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="birthday" class="col-md-3 control-label form_label">
                                出生日期
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="text" name="birthday" class="form-control" readOnly id="birthday" 
                                    value="<?php echo (isset($single['birthday']) && $single['birthday']!='0000-00-00')?$single['birthday']:''; ?>">
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
                                <input type="text" name="email" class="form-control" id="email" value="<?php echo @$single['email']; ?>">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="address" class="col-md-3 control-label form_label">
                                住址
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="text" name="address" class="form-control" id="address" value="<?php echo @$single['address']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="email" class="col-md-6 control-label form_label">
                                邀请码
                            </label>
                            <div class="col-md-6 form_input">
                                <p class="form-control-static"><?php echo $single['invite_code']; ?></p>
                            </div>
                        </div>
                        <div class="form-group col-md-9">
                            <label for="address" class="col-md-2 control-label form_label">
                                邀请连接
                            </label>
                            <div class="col-md-10 form_input">
                                <input type="text" class="form-control" value="http://www.100hl.com/member/user/register?invite_code=<?php echo $single['invite_code']; ?>">
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
                                    <option value="">请选择所属公司</option>
                                <?php 
                                if(!empty($companys)){
                                    foreach ($companys as $company) { 
                                ?>
                                    <option value="<?php echo $company['id']; ?>" <?php echo @$single['company_id']==$company['id']?'selected':''; ?>><?php echo trim($company['name']); ?></option>
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
                                <?php 
                                if(!empty($depts)){
                                    foreach ($depts as $dept) {
                                ?>
                                    <option value="<?php echo $dept['id']; ?>" class="dept_id_option" company_id="<?php echo $dept['company_id']; ?>" <?php echo @$single['dept_id']==$dept['id']?'selected style="display:block;"':''; ?>><?php echo $dept['name']; ?></option>
                                <?php }} ?> 
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
                                <input type="text" name="hire_date" class="form-control" readOnly id="hire_date" 
                                    value="<?php echo (isset($single['hire_date']) && $single['hire_date']!='0000-00-00')?$single['hire_date']:''; ?>">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="expire_date" class="col-md-3 control-label form_label">
                                合同到期日
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="text" name="expire_date" class="form-control" id="expire_date" readOnly 
                                    value="<?php echo (isset($single['expire_date']) && $single['expire_date']!='0000-00-00')?$single['expire_date']:''; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="quit_date" class="col-md-3 control-label form_label">
                                离职日期
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="text" name="quit_date" class="form-control" id="quit_date" readOnly 
                                    value="<?php echo (isset($single['quit_date']) && $single['quit_date']!='0000-00-00')?$single['quit_date']:''; ?>">
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
                            <label for="duty" class="col-md-1 control-label form_label">
                                <span><font color="red">*</font></span>
                                角色
                            </label>
                            <div class="col-md-10 form_input">
                                <?php 
                                    $roles_id_arr = explode(',', $single['roles']);
                                    foreach( $roles as $k=>$row ) { 
                                ?>
                                    <label class="checkbox-inline role_ids">
                                        <input type="checkbox" name="role_ids[]" value="<?php echo $k; ?>" <?php echo in_array($k, $roles_id_arr)?'checked':''; ?>> <?php echo $row['name']; ?>
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
    function initDept( company_id, dept_id ){
        var html = '<option value="0">请选择所属部门</option>';
        var depts = deptMap[company_id];
        if( depts!=undefined ){
            for( var i in depts ){
                var row = depts[i];
                html += '<option value="'+row.id+'"'+(row.id==dept_id?' selected':'')+'>'+row.name+'</option>';
            }
        }
        $('#dept_id').html(html);
    }
    initDept(<?php echo $single['company_id']; ?>,<?php echo $single['dept_id']; ?>);
    $('#company_id').change(function(){
        var company_id = $(this).val();
        initDept(company_id,0);
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

    $('#employee_form').bootstrapValidator(validate_rules.employee_edit).on('success.form.bv', function(e) {
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
                },1000);
            }
        }, 'json');
    });
});
</script>
</body>
</html>