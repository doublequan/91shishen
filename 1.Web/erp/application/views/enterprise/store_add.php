<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                新增门店
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert"></div>

            <form action="<?php echo site_url('enterprise/store/doAdd'); ?>" method="post" id="store_form">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="name" class="col-md-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                门店名称
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="text" name="name" class="form-control" id="name">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="" class="col-md-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                所属网站
                            </label>
                            <div class="col-md-9 form_input">
                                <select name="site_id" class="form-control" id="site_id">
                                    <option value="">请选择所属网站</option>
                                <?php foreach ($sites as $site) { ?>
                                    <option value="<?php echo $site['id']; ?>" company_id="<?php echo $site['company_id']; ?>"><?php echo trim($site['name']); ?></option>
                                <?php } ?>
                                </select>
                            </div>
                            <input type="hidden" name="company_id" id="company_id" value="0">
                        </div>
                        <!-- <div class="form-group col-md-6">
                            <label for="" class="col-md-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                所属公司
                            </label>
                            <div class="col-md-9 form_input">
                                <select name="company_id" class="form-control" id="company_id">
                                    <option value="">请选择所属公司</option>
                                <?php foreach ($companys as $company) { ?>
                                    <option value="<?php echo $company['id']; ?>"><?php echo trim($company['name']); ?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </div> -->
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="" class="col-md-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                负责人
                            </label>
                            <div class="col-sm-6 form_input">
                                <input type="text" name="employee_name" class="form-control" id="employee_name" readOnly>
                                <input type="hidden" name="manager" class="form-control" id="employee_id">
                            </div>
                            <div class="col-sm-2 form_input">
                                <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-default" id="add_employee_btn">选择员工</a>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tel" class="col-md-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                联系电话
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="text" name="tel" class="form-control" id="tel">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="" class="col-md-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                省/直辖市
                            </label>
                            <div class="col-md-9 form_input">
                                <select name="prov" class="form-control" id="prov">
                                    <option value="">请选择省/直辖市</option>
                                <?php foreach ($province_list as $province) { ?>
                                    <option value="<?php echo $province['id']; ?>"><?php echo $province['name']; ?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="" class="col-md-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                城市
                            </label>
                            <div class="col-md-9 form_input">
                                <select name="city" class="form-control" id="city">
                                    <option value="">请选择</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="" class="col-md-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                区县
                            </label>
                            <div class="col-md-9 form_input">
                                <select name="district" class="form-control" id="district">
                                    <option value="">请选择</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="address" class="col-md-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                地址
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="text" name="address" class="form-control" id="address">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="loc" class="col-md-3 control-label form_label">
                                经纬度坐标
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="text" name="loc" class="form-control" id="loc">
                                <p class="form-control-static">格式为“115.214,39.4122”，前面经度，后面纬度</p>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="open_time" class="col-md-3 control-label form_label">
                                营业时间
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="text" name="open_time" class="form-control" id="open_time" value="09:00 - 21:00">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="" class="col-md-3 control-label form_label">
                                加工中心
                            </label>
                            <div class="col-md-9 form_input">
                                <label class="radio-inline">
                                    <input type="radio" name="is_process" class="is_process" value="1"> 是
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="is_process" class="is_process" value="0" checked> 否
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="" class="col-md-3 control-label form_label">
                                销售门店
                            </label>
                            <div class="col-md-9 form_input">
                                <label class="radio-inline">
                                    <input type="radio" name="is_sell" class="is_sell" value="1"> 是
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="is_sell" class="is_sell" value="0" checked> 否
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="" class="col-md-3 control-label form_label">
                                仓库
                            </label>
                            <div class="col-md-9 form_input">
                                <label class="radio-inline">
                                    <input type="radio" name="is_storage" class="is_storage" value="1"> 是
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="is_storage" class="is_storage" value="0" checked> 否
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="" class="col-md-3 control-label form_label">
                                自提点
                            </label>
                            <div class="col-md-9 form_input">
                                <label class="radio-inline">
                                    <input type="radio" name="is_pickup" class="is_pickup" value="1"> 是
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="is_pickup" class="is_pickup" value="0" checked> 否
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="" class="col-md-3 control-label form_label">
                                配送点
                            </label>
                            <div class="col-md-9 form_input">
                                <label class="radio-inline">
                                    <input type="radio" name="is_delivery" class="is_delivery" value="1"> 是
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="is_delivery" class="is_delivery" value="0" checked> 否
                                </label>
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

<iframe src="" id="add_form_page" style="height:500px;width:900px;"></iframe>

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
                $('#store_form').bootstrapValidator('updateStatus', 'employee_name', 'NOT_VALIDATED')
                                .bootstrapValidator('validateField', 'employee_name');
            },
        });
    });

    $('#site_id').change(function(){
        var company_id = $(this).children('option:selected').attr('company_id');
        $('#company_id').val(company_id);
    });

    var get_area_list = function(city_id){
        var area_control = "<?php echo site_url('area/getAreaList'); ?>";
        $.get(area_control, {'city_id': city_id}, function(data){
            var data = $.parseJSON(data);
            var options = [];
            options.push('<option value="">请选择</option>');
            $.each(data, function(idx, area){
                options.push('<option value="' + area.id + '">' + area.name + '</option>');
            });
            $('#district').empty().append(options.join('')).parent('div').show();
        });
    }

    var get_city_list = function(province_id){
        var area_control = "<?php echo site_url('area/getCityList'); ?>";
        $.get(area_control, {'province_id': province_id}, function(data){
            var data = $.parseJSON(data);
            var options = [];
            var current_city_id;
            options.push('<option value="">请选择</option>');
            $.each(data, function(idx, city){
                if(idx == 0){
                    current_city_id = city.id;
                }
                options.push('<option value="' + city.id + '">' + city.name + '</option>');
            });
            $('#city').empty().append(options.join('')).parent('div').show();
            if(current_city_id)
                get_area_list(current_city_id);
        });
    }
    
    $('#prov').change(function(){
        var province_id = $(this).val();
        var city_id = get_city_list(province_id);

    });
    $('#city').change(function(){
        var city_id = $(this).val();
        get_area_list(city_id);
    });

    $('#store_form')
        .bootstrapValidator(validate_rules.store)
        .on('success.form.bv', function(e) {
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
                    },2000);
                }
            }, 'json');
        });
});

</script>
