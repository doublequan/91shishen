<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                添加街道信息
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert"></div>

            <form action="<?php echo site_url('system/area/doStreetAdd'); ?>" method="post" id="add_street_form">
                <div class="form-horizontal">
                    <div class="form-group">
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
                    <div class="form-group">
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
                    <div class="form-group">
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
                    <div class="form-group">
                        <label for="street" class="col-md-3 control-label form_label">
                            <span><font color="red">*</font></span>
                            街道名称
                        </label>
                        <div class="col-md-9 form_input">
                            <input type="text" name="street" class="form-control" id="street">
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
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function($) {
    $('.alert').hide();

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

    $('#add_street_form')
        .bootstrapValidator({
            message: '输入格式错误，请检查',
            fields: {
                prov: {
                    validators: {
                        notEmpty: {
                            message: '请选择所在省/直辖市'
                        },
                    }
                },
                city: {
                    validators: {
                        notEmpty: {
                            message: '请选择所在城市'
                        },
                    }
                },
                district: {
                    validators: {
                        notEmpty: {
                            message: '请选择所在区县'
                        },
                    }
                },
                street: {
                    validators: {
                        notEmpty: {
                            message: '请填写街道名称'
                        },
                        stringLength: {
                            enabled: true,
                            min: 1,
                            max: 250,
                            message: '街道名称格式错误'
                        },
                    }
                },
            }
        })
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
                    },2000);
                }
            }, 'json');
        });
});

</script>
