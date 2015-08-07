<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                供应商信息
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
                $form_action = site_url('products/supplier/add');
                if(isset($supplier_id) && !empty($supplier_id)){
                    $form_action = site_url('products/supplier/edit');
                }
            ?>
            <form action="<?php echo $form_action; ?>" method="post" name="supplier" id="supplier_form">
                <?php if(isset($supplier_id) && !empty($supplier_id)){ ?>
                <input type="hidden" name="id" value="<?php echo set_value('supplier_id',@$supplier_id); ?>">
                <?php } ?>
                <div class="form-horizontal">
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="bg-info form-square-title">
                                基本信息
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="sup_name" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                供应商名称
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="sup_name" class="form-control" value="<?php echo set_value('sup_name', @$sup_name); ?>" id="sup_name">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sup_phone" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                联系电话
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="sup_phone" class="form-control" value="<?php echo set_value('sup_phone', @$sup_phone); ?>" id="sup_phone">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="sup_fax" class="col-sm-3 control-label form_label">
                                传真
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="sup_fax" class="form-control" value="<?php echo set_value('sup_fax', @$sup_fax); ?>" id="sup_fax">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sup_addr" class="col-sm-3 control-label form_label">
                                联系地址
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="sup_addr" class="form-control" value="<?php echo set_value('sup_addr', @$sup_addr); ?>" id="sup_addr">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="sup_account_name" class="col-sm-3 control-label form_label">
                                开户名称
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="sup_account_name" class="form-control" value="<?php echo set_value('sup_account_name', @$sup_account_name); ?>" id="sup_account_name">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sup_account_bank" class="col-sm-3 control-label form_label">
                                开户行
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="sup_account_bank" class="form-control" value="<?php echo set_value('sup_account_bank', @$sup_account_bank); ?>" id="sup_account_bank">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="sup_account_num" class="col-sm-3 control-label form_label">
                                账号
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="sup_account_num" class="form-control" value="<?php echo set_value('sup_account_num', @$sup_account_num); ?>" id="sup_account_num">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sup_tax_num" class="col-sm-3 control-label form_label">
                                供应商税号
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="sup_tax_num" class="form-control" value="<?php echo set_value('sup_tax_num', @$sup_tax_num); ?>" id="sup_tax_num">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="sup_gszc_num" class="col-sm-3 control-label form_label">
                                工商注册号
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="sup_gszc_num" class="form-control" value="<?php echo set_value('sup_gszc_num', @$sup_gszc_num); ?>" id="sup_gszc_num">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sup_wsxk_num" class="col-sm-3 control-label form_label">
                                卫生许可证
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="sup_wsxk_num" class="form-control" value="<?php echo set_value('sup_wsxk_num', @$sup_wsxk_num); ?>" id="sup_wsxk_num">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="sup_splt_num" class="col-sm-3 control-label form_label">
                                食品流通许可
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="sup_splt_num" class="form-control" value="<?php echo set_value('sup_splt_num', @$sup_splt_num); ?>" id="sup_splt_num">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sup_gmp_num" class="col-sm-3 control-label form_label">
                                GMP证书号
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="sup_gmp_num" class="form-control" value="<?php echo set_value('sup_gmp_num', @$sup_gmp_num); ?>" id="sup_gmp_num">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="sup_scxk_num" class="col-sm-3 control-label form_label">
                                生产许可证
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="sup_scxk_num" class="form-control" value="<?php echo set_value('sup_scxk_num', @$sup_scxk_num); ?>" id="sup_scxk_num">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sup_website" class="col-sm-3 control-label form_label">
                                供应商网站
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="sup_website" class="form-control" value="<?php echo set_value('sup_website', @$sup_website); ?>" id="sup_website">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="sup_comment" class="col-sm-3 control-label form_label">
                                备注
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="sup_comment" class="form-control" value="<?php echo set_value('sup_comment', @$sup_comment); ?>" id="sup_comment">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <p class="bg-info form-square-title">
                                联系人信息
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="contact_name" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                联系人
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="contact_name" class="form-control" value="<?php echo set_value('contact_name', @$contact_name); ?>" id="contact_name">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="contact_email" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                联系人邮箱
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="contact_email" class="form-control" value="<?php echo set_value('contact_email', @$contact_email); ?>" id="contact_email">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="contact_mobile" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                联系人手机
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="contact_mobile" class="form-control" value="<?php echo set_value('contact_mobile', @$contact_mobile); ?>" id="contact_mobile">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="contact_phone" class="col-sm-3 control-label form_label">
                                联系人电话
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="contact_phone" class="form-control" value="<?php echo set_value('contact_phone', @$contact_phone); ?>" id="contact_phone">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="contact_qq" class="col-sm-3 control-label form_label">
                                联系人QQ
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="contact_qq" class="form-control" value="<?php echo set_value('contact_qq', @$contact_qq); ?>" id="contact_qq">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="contact_alww" class="col-sm-3 control-label form_label">
                                联系人旺旺
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="contact_alww" class="form-control" value="<?php echo set_value('contact_alww', @$contact_alww); ?>" id="contact_alww">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <p class="bg-info form-square-title">
                                物流地址信息
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="logistics_recipient" class="col-sm-3 control-label form_label">
                                收货人
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="logistics_recipient" class="form-control" value="<?php echo set_value('logistics_recipient', @$logistics_recipient); ?>" id="logistics_recipient">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="logistics_mobile" class="col-sm-3 control-label form_label">
                                收货人手机
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="logistics_mobile" class="form-control" value="<?php echo set_value('logistics_mobile', @$logistics_mobile); ?>" id="logistics_mobile">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="logistics_phone" class="col-sm-3 control-label form_label">
                                收货人电话
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="logistics_phone" class="form-control" value="<?php echo set_value('logistics_phone', @$logistics_phone); ?>" id="logistics_phone">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="logistics_postcode" class="col-sm-3 control-label form_label">
                                邮编
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="logistics_postcode" class="form-control" value="<?php echo set_value('logistics_postcode', @$logistics_postcode); ?>" id="logistics_postcode">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="logistics_country" class="col-sm-3 control-label form_label">
                                国家
                            </label>
                            <div class="col-sm-9 form_input">
                                <select name="logistics_country" id="logistics_country" class="form-control">
                                    <option value="中国">中国</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="province_select" class="col-sm-3 control-label form_label">
                                省/直辖市
                            </label>
                            <div class="col-sm-9 form_input">
                                <select name="logistics_province_id" class="form-control" id="province_select">
                                    <option value="">请选择省/直辖市</option>
                                <?php foreach ($province_list as $province) { ?>
                                    <option value="<?php echo $province['id']; ?>" <?php echo set_select('logistics_province_id', @$logistics_province_id, ($province['id']==@$logistics_province_id)?TRUE:FALSE); ?> ><?php echo $province['name']; ?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="city_select" class="col-sm-3 control-label form_label">
                                城市
                            </label>
                            <div class="col-sm-9 form_input">
                                <select name="logistics_city_id" id="city_select" class="form-control">
                                    <option value="">请选择</option>
                                <?php 
                                if(!empty($city_list)){
                                    foreach ($city_list as $city) {
                                ?>
                                    <option value="<?php echo $city['id']; ?>" <?php echo set_select('logistics_city_id', @$logistics_city_id, ($city['id']==@$logistics_city_id)?TRUE:FALSE); ?> ><?php echo $city['name']; ?></option>
                                <?php }} ?>    
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="area_select" class="col-sm-3 control-label form_label">
                                区县
                            </label>
                            <div class="col-sm-9 form_input">
                                <select name="logistics_area_id" class="form-control" id="area_select">
                                    <option value="">请选择</option>
                                <?php foreach ($area_list as $area) { ?>
                                    <option value="<?php echo $area['id']; ?>" <?php echo set_select('logistics_area_id', @$logistics_area_id, ($area['id']==@$logistics_area_id)?TRUE:FALSE); ?> ><?php echo $area['name']; ?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="logistics_addr" class="col-sm-3 control-label form_label">
                                详细地址
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="logistics_addr" class="form-control" value="<?php echo set_value('logistics_addr', @$logistics_addr); ?>" id="logistics_addr">
                            </div>
                        </div>
                    </div>

                    <div class="form-group form_btn_line">
                        <div class="col-sm-2 col-sm-offset-5 text-center">
                            <input type="submit" class="btn btn-primary btn-block" value="提 交"> 
                        </div>
                    </div>        
                    <div class="row" style="height:50px;"></div>   
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

    $('#supplier_form').bootstrapValidator(validate_rules.supplier);
});

</script>
