<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                公司信息
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <?php if(isset($_GET['rst']) && $_GET['rst'] == 'edit_success'){ ?>
            <div class="alert alert-success" role="alert">修改成功！</div>
            <?php } elseif(isset($_GET['rst']) && $_GET['rst'] == 'add_success'){ ?>
            <div class="alert alert-success" role="alert">添加成功！</div>
            <?php } ?>

            <?php 
                $validation_errors = validation_errors();
                if(!empty($validation_errors)) {
            ?>
            <div class="alert alert-danger" id="form_val_errors">
                <?php echo $validation_errors; ?>
            </div>
            <?php } ?>

            <?php 
                $form_action = site_url('enterprise/company/add');
                if(isset($company_id) && !empty($company_id)){
                    $form_action = site_url('enterprise/company/edit');
                }
            ?>
            <form action="<?php echo $form_action; ?>" method="post" name="company" id="company_form">
                <?php if(isset($company_id) && !empty($company_id)){ ?>
                <input type="hidden" name="id" value="<?php echo set_value('company_id',@$company_id); ?>">
                <?php } ?>
                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            公司名称
                        </label>
                        <div class="col-sm-9">
                            <input type="text" name="name" class="form-control" value="<?php echo set_value('name', @$name); ?>" id="name">
                        </div>
                    </div>
                    <!--
                    <div class="form-group">
                        <label for="" class="col-md-2 control-label form_label">
                            公司经理
                        </label>
                        <div class="col-sm-7 form_input">
                            <input type="text" class="form-control" id="employee_name" readOnly
                                value="<?php echo !empty($manager_info)?$manager_info['username']:''; ?>">
                            <input type="hidden" name="manager" class="form-control" id="employee_id"
                                value="<?php echo !empty($manager_info)?$manager_info['id']:''; ?>">
                        </div>
                        <div class="col-sm-2 form_input">
                            <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-default btn-block" id="add_employee_btn">选择员工</a>
                        </div>
                    </div>
                    -->

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            所属城市
                        </label>
                        <div class="col-sm-9 form_input">
                            <div class="row">
                                <div class="col-sm-6">
                                    <select name="province_id" class="form-control" id="province_select">
                                        <option value="">请选择省区</option>
                                    <?php foreach ($province_list as $province) { ?>
                                        <option value="<?php echo $province['id']; ?>" <?php echo set_select('province_id', @$province_id, ($province['id']==@$province_id)?TRUE:FALSE); ?> ><?php echo $province['name']; ?></option>
                                    <?php } ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <select name="city_id" id="city_select" class="form-control">
                                        <option value="">请选择</option>
                                    <?php 
                                    if(!empty($city_list)){
                                        foreach ($city_list as $city) {
                                    ?>
                                        <option value="<?php echo $city['id']; ?>" <?php echo set_select('city_id', @$city_id, ($city['id']==@$city_id)?TRUE:FALSE); ?> ><?php echo $city['name']; ?></option>
                                    <?php }} ?>    
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            公司地址
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="address" class="form-control" value="<?php echo set_value('address', @$address); ?>" id="address">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone" class="col-sm-2 control-label form_label">
                            公司电话
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="phone" class="form-control" value="<?php echo set_value('phone', @$phone); ?>" id="phone">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label form_label">
                            备注
                        </label>
                        <div class="col-sm-9 form_input">
                            <textarea name="comment" class="form-control" rows="3"><?php echo set_value('comment', @$comment); ?></textarea>
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
        $('.alert').hide(); 
    },4000); 

    $('a#add_employee_btn').click(function(){
        $('iframe#add_form_page').attr('src', "<?php echo site_url('employee/employee/select_dialog'); ?>");
        $("a#add_employee_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            // 'afterClose': function(){
            //     $('#company_form').bootstrapValidator('updateStatus', 'employee_name', 'NOT_VALIDATED')
            //                     .bootstrapValidator('validateField', 'employee_name');
            // },
        });
    });

    $('#province_select').change(function(){
        var province_id = $(this).val();
        var area_control = "<?php echo site_url('area/getCityList'); ?>";
        $.get(area_control, {'province_id': province_id}, function(data){
            var data = $.parseJSON(data);
            var options = [];
            options.push('<option value="">选择城市</option>')
            $.each(data, function(idx, city){
                options.push('<option value="' + city.id + '">' + city.name + '</option>');
            });
            $('#city_select').empty().append(options.join('')).parent('div').show();
        });
    });

    $('#company_form').bootstrapValidator(validate_rules.company);
});

</script>
