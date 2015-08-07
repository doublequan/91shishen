<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                品牌信息
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
                $form_action = site_url('products/brand/add');
                if(isset($brand_id) && !empty($brand_id)){
                    $form_action = site_url('products/brand/edit');
                }
            ?>
            <form action="<?php echo $form_action; ?>" method="post" id="brand_form" enctype="multipart/form-data">
                <?php if(isset($brand_id) && !empty($brand_id)){ ?>
                <input type="hidden" name="id" value="<?php echo set_value('brand_id',@$brand_id); ?>">
                <?php } ?>
                <div class="form-horizontal">
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                品牌名称
                            </label>
                            <div class="col-sm-8 form_input">
                                <input type="text" name="name" class="form-control" value="<?php echo set_value('name', @$name); ?>" id="name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="url" class="col-sm-3 control-label form_label">
                                品牌网址
                            </label>
                            <div class="col-sm-8 form_input">
                                <input type="text" name="url" class="form-control" value="<?php echo set_value('url', @$url); ?>" id="url">
                            </div>
                        </div>
                    
                        <div class="form-group">
                            <label for="logo" class="col-sm-3 control-label form_label">
                                品牌Logo
                            </label>
                            <div class="col-sm-8 form_input">
                                <input type="file" name="logo" class="form-control" value="<?php echo set_value('logo', @$logo); ?>" id="logo">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sort" class="col-sm-3 control-label form_label">
                                排序
                            </label>
                            <div class="col-sm-8 form_input">
                                <input type="text" name="sort" class="form-control" value="<?php echo set_value('sort', @$sort); ?>" id="sort">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-sm-3 control-label form_label">
                                品牌描述
                            </label>
                            <div class="col-sm-8 form_input">
                                <textarea class="form-control" name="description" id="description" rows="7"><?php echo set_value('description', @$description); ?></textarea>
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

<script src="<?php echo base_url('static/js/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function($) {
    window.setTimeout(function(){ 
        $('.form_body > div.alert:first').hide(); 
    },3000);

    var get_area_list = function(city_id){
        var area_control = "<?php echo site_url('area/getAreaList'); ?>";
        $.get(area_control, {'city_id': city_id}, function(data){
            var data = $.parseJSON(data);
            var options = [];
            $.each(data, function(idx, area){
                options.push('<option value="' + area.id + '">' + area.name + '</option>');
            });
            $('#area_select').empty().append(options.join('')).parent('div').show();
        });
    }

    var get_city_list = function(province_id){
        var area_control = "<?php echo site_url('area/getCityList'); ?>";
        $.get(area_control, {'province_id': province_id}, function(data){
            var data = $.parseJSON(data);
            var options = [];
            var current_city_id;
            $.each(data, function(idx, city){
                if(idx == 0){
                    current_city_id = city.id;
                }
                options.push('<option value="' + city.id + '">' + city.name + '</option>');
            });
            $('#city_select').empty().append(options.join('')).parent('div').show();
            if(current_city_id)
                get_area_list(current_city_id);
        });
    }
    
    $('#province_select').change(function(){
        var province_id = $(this).val();
        var city_id = get_city_list(province_id);

    });
    $('#city_select').change(function(){
        var city_id = $(this).val();
        get_area_list(city_id);
    });

    $('#brand_form').bootstrapValidator(validate_rules.brand);
});
</script>
