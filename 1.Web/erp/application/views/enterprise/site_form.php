<link href="<?php echo base_url('static/css/bootstrap.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                网站信息
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
                $form_action = site_url('enterprise/site/add');
                if(isset($site_id) && !empty($site_id)){
                    $form_action = site_url('enterprise/site/edit');
                }
            ?>
            <form action="<?php echo $form_action; ?>" method="post" name="site" id="site_form">
                <?php if(isset($site_id) && !empty($site_id)){ ?>
                <input type="hidden" name="id" value="<?php echo set_value('site_id',@$site_id); ?>">
                <?php } ?>
                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            网站名称
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="name" class="form-control" value="<?php echo set_value('name', @$name); ?>" id="name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="domain" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            网站域名
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="domain" class="form-control" value="<?php echo set_value('domain', @$domain); ?>" id="domain">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            所属公司
                        </label>
                        <div class="col-sm-9 form_input">
                            <select name="company_id" class="form-control" id="company_id">
                                <option value="">请选择所属公司</option>
                            <?php foreach ($company_list as $company) { ?>
                                <option value="<?php echo $company['id']; ?>" <?php echo set_select('company_id', @$company_id, ($company['id']==@$company_id)?TRUE:FALSE); ?>><?php echo trim($company['name']); ?></option>
                            <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label form_label">
                            网站状态
                        </label>
                        <div class="col-sm-9 form_input">
                            <?php if(!isset($is_off)) $is_off = 0; ?>
                            <label class="radio-inline">
                                <input type="radio" name="is_off" class="is_off" value="0" <?php echo set_radio('is_off', '0', (@$is_off==0?TRUE:FALSE)); ?> /> 启用
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="is_off" class="is_off" value="1" <?php echo set_radio('is_off', '1', (@$is_off==1?TRUE:FALSE)); ?> /> 关闭
                            </label>
                        </div>
                    </div>
                    <br>

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

<script src="<?php echo base_url('static/js/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function($) {
    window.setTimeout(function(){ 
        $('.form_body > div.alert:first').hide(); 
    },3000);

    $('#site_form').bootstrapValidator(validate_rules.site);
});

</script>
