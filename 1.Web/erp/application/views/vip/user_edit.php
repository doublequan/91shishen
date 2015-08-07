<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                编辑大客户信息
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert"></div>
            <form action="<?php echo site_url('vip/user/doEdit'); ?>" method="post" id="user_form">
                <input type="hidden" name="id" value="<?php echo $single['id']; ?>">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="username" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                用户名
                            </label>
                            <div class="col-sm-9 form_input">
                                <p class="form-control-static"><?php echo $single['username']; ?></p>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                所属公司
                            </label>
                            <div class="col-sm-9 form_input">
                                <select name="company_id" class="form-control input-sm input-sm" id="company_id">
                                    <option value="">选择所属公司</option>
                                <?php foreach ($companys as $value) { ?>
                                    <option value="<?php echo $value['id']; ?>" <?php echo $single['company_id']==$value['id']?'selected':''; ?>><?php echo trim($value['name']); ?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="discount" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                默认折扣率
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="discount" class="form-control" id="discount" value="<?php echo $single['discount']; ?>">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="mobile" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                手机号码
                            </label>
                            <div class="col-sm-9 form_input">
                                <p class="form-control-static"><?php echo $single['mobile']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="pass" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                密码
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="password" name="pass" class="form-control" id="pass">
                                <span class="help-block">不重置用户密码可不填</span>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="repass" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                重复密码
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="password" name="repass" class="form-control" id="repass">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="position" class="col-sm-3 control-label form_label">
                                用户职位
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="position" class="form-control" id="position" value="<?php echo $single['position']; ?>">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="" class="col-sm-3 control-label form_label">
                                招商员工
                            </label>
                            <div class="col-sm-6 form_input">
                                <input type="text" name="deal_name" class="form-control" id="deal_name" readOnly
                                     value="<?php echo $single['deal_name']; ?>">
                                <input type="hidden" name="deal_eid" class="form-control" id="deal_eid"
                                     value="<?php echo $single['deal_eid']; ?>">
                            </div>
                            <div class="col-sm-3 form_input">
                                <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-default btn-block" id="add_deal_btn">选择员工</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="" class="col-sm-3 control-label form_label">
                                负责员工
                            </label>
                            <div class="col-sm-6 form_input">
                                <input type="text" name="charge_name" class="form-control" id="charge_name" readOnly
                                     value="<?php echo $single['charge_name']; ?>">
                                <input type="hidden" name="charge_eid" class="form-control" id="charge_eid"
                                     value="<?php echo $single['charge_eid']; ?>">
                            </div>
                            <div class="col-sm-3 form_input">
                                <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-default btn-block" id="add_charge_btn">选择员工</a>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form_btn_line">
                        <div class="col-sm-2 col-sm-offset-5 text-center">
                            <input type="submit" class="btn btn-primary btn-block" value="提 交"> 
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<iframe src="" id="add_form_page" style="height:500px;width:900px;"></iframe>

<script src="<?php echo base_url('static/js/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/jquery.fancybox.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function($) {
    window.setTimeout(function(){ 
        $('#success_alert, #danger_alert').hide(); 
    },4000);
    
    $('.alert').hide();

    $('a#add_deal_btn').click(function(){
        $('iframe#add_form_page').attr('src', "<?php echo site_url('employee/employee/select_dialog').'?ename=deal_name&eid=deal_eid'; ?>");
        $("a#add_deal_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            'afterClose': function(){
                $('#store_form').bootstrapValidator('updateStatus', 'deal_name', 'NOT_VALIDATED')
                                .bootstrapValidator('validateField', 'deal_name');
            },
        });
    });

    $('a#add_charge_btn').click(function(){
        $('iframe#add_form_page').attr('src', "<?php echo site_url('employee/employee/select_dialog').'?ename=charge_name&eid=charge_eid'; ?>");
        $("a#add_charge_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            'afterClose': function(){
                $('#store_form').bootstrapValidator('updateStatus', 'charge_name', 'NOT_VALIDATED')
                                .bootstrapValidator('validateField', 'charge_name');
            },
        });
    });

    $('#user_form')
        .bootstrapValidator(validate_rules.vip_user_edit)
        .on('success.form.bv', function(e) {
            if($('#pass').val()!==$('#repass').val()){
                alert('两次输入的密码不一致！');
                $('#submit_btn').removeAttr('disabled');
                return false;
            }
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
