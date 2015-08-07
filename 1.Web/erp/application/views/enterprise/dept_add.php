<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                新增部门
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert"></div>

            <form action="<?php echo site_url('enterprise/dept/doAdd'); ?>" id="dept_form" method="post">
                <div class="form-horizontal">
                    <input type="hidden" name="father_id" id="father_id" value="">
                    <div class="form-group select_line">
                        <label for="" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            所属公司
                        </label>
                        <div class="col-sm-9 form_input">
                            <select name="company_id" class="form-control dept_select" id="company_id" onChange="getDeptChildren(this)">
                                <option value="">请选择所属公司</option>
                            <?php 
                            if(!empty($companys)){
                                foreach ($companys as $company) { 
                            ?>
                                <option value="<?php echo $company['id']; ?>" father_id="0"><?php echo trim($company['name']); ?></option>
                            <?php }}?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="dept_name_line">
                        <label for="name" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            新部门名称
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="name" class="form-control" id="name">
                        </div>
                    </div>

                    <div class="form-group" id="dept_leader_line">
                        <label for="name" class="col-sm-2 control-label form_label">
                            部门主管
                        </label>
                        <div class="col-sm-7 form_input">
                            <input type="text" class="form-control" name="employee_name" id="employee_name" readOnly>
                            <input type="hidden" name="leader" class="form-control" id="employee_id">
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

function getDeptChildren(event){
    if(!$(event).val()){
        $(event).parent().parent('.select_line').nextAll('.select_line').remove();
    }
    else{
        var company_id = $('#company_id').val();
        $('.dept_select').unbind('change');
        $(event).bind('change', function(){
            getDeptChildren(event);
        });
        $(event).parent().parent('.select_line').nextAll('.select_line').remove();
        if(company_id){
            var father_id = $(event).children('option:selected').attr('father_id');
            $('#father_id').attr('value', father_id);
            var post_url = "<?php echo site_url('enterprise/dept/getDeptChildren'); ?>";
            var post_params = {
                'company_id' : company_id,
                'father_id'  : father_id,
            };
            $.post(post_url, post_params, function(rst_data){
                var rst_json = $.parseJSON(rst_data);
                if(rst_json.err_no == 0){
                    var select_str = '<div class="form-group select_line">';
                    select_str += '<label class="col-sm-2 control-label form_label">上级部门</label>';
                    select_str += '<div class="col-sm-9 form_input">';
                    select_str += '<select class="form-control dept_select" onChange="getDeptChildren(this)">';
                    select_str += '<option value="" class="">选择上级部门</option>';
                    $.each(rst_json.info_list, function(idx, row){
                        select_str += '<option father_id="' + row.id + '">' + row.name + '</option>';
                    });
                    select_str += '</select></div></div>';

                    $('#dept_name_line').before(select_str);
                }
                else if(rst_json.err_no == 100){
                    //$('#danger_alert').empty().text(rst_json.err_msg).show();
                    return;
                }
                else{
                    $('#danger_alert').empty().text(rst_json.err_msg).show();
                    return;
                }
            });
        }
    }
    
}
$(document).ready(function($) {
    window.setTimeout(function(){ 
        $('.form_body div.alert').hide(); 
    },4000);

    $('.alert').hide();

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
