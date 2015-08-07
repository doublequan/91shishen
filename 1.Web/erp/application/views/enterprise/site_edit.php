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
                编辑网站
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert" style="display:none;"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert" style="display:none;"></div>
            <p class="bg-info form-square-title">网站基础信息：</p>
            <form action="<?php echo site_url('enterprise/site/doEdit'); ?>" id="form" method="post">
                <input type="hidden" name="id" value="<?php echo $single['id']; ?>">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="name" class="col-sm-3 control-label form_label" style="width:120px;">
                                <span><font color="red">*</font></span>
                                网站名称
                            </label>
                            <div class="col-sm-8 form_input">
                                <input type="text" name="name" class="form-control" id="name" value="<?php echo $single['name']; ?>">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="name" class="col-sm-3 control-label form_label" style="width:120px;">
                                <span><font color="red">*</font></span>
                                所属省市
                            </label>
                            <div class="col-sm-4 form_input">
                                <select name="prov" class="form-control" id="prov"></select>
                            </div>
                            <div class="col-sm-4 form_input">
                                <select name="city" class="form-control" id="city"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <label for="name" class="col-sm-2 control-label form_label" style="width:120px;">
                                <span><font color="red">*</font></span>
                                默认门店
                            </label>
                            <div class="col-sm-3 form_input">
                                <select class="form-control" id="default_prov"></select>
                            </div>
                            <div class="col-sm-3 form_input">
                                <select class="form-control" id="default_city"></select>
                            </div>
                            <div class="col-sm-4 form_input">
                                <select name="default_store" class="form-control" id="default_store"></select>
                            </div>
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

<script src="<?php echo base_url('static/js/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
var provs = <?php echo $provs ? json_encode($provs) : '[]'; ?>;
var citys = <?php echo $citys ? json_encode($citys) : '[]'; ?>;
var storeMap = <?php echo $storeMap ? json_encode($storeMap) : '[]'; ?>;
$(document).ready(function($) {
    var initArea = function(prov_id, city_id){
        //初始化省份
        var options = [];
        options.push('<option value="">选择省份</option>');
        $.each(provs, function(i, prov){
            options.push('<option value="'+prov.id+'"'+( prov.id==prov_id ? ' selected' : '' )+'>'+prov.name+'</option>');
        });
        $('#prov').empty().append(options.join(''));
        //初始化城市
        var options = [];
        options.push('<option value="">选择城市</option>');
        var arr = citys[prov_id];
        if( arr!=undefined ){
            $.each(arr, function(i, city){
                options.push('<option value="'+city.id+'"'+( city.id==city_id ? ' selected' : '' )+'>'+city.name+'</option>');
            });
        }
        $('#city').empty().append(options.join(''));
    }
    initArea(<?php echo $single['prov']; ?>,<?php echo $single['city']; ?>);
    $('#prov').change(function(){
        var prov_id = $('#prov').val();
        initArea(prov_id,0);
    });

    var initStore = function(prov_id, city_id, store_id){
        //初始化省份
        var options = [];
        options.push('<option value="">默认门店所属省份</option>');
        $.each(provs, function(i, prov){
            options.push('<option value="'+prov.id+'"'+( prov.id==prov_id ? ' selected' : '' )+'>'+prov.name+'</option>');
        });
        $('#default_prov').empty().append(options.join(''));
        //初始化城市
        var options = [];
        options.push('<option value="">默认门店所属城市</option>');
        var arr = citys[prov_id];
        if( arr!=undefined ){
            $.each(arr, function(i, city){
                options.push('<option value="'+city.id+'"'+( city.id==city_id ? ' selected' : '' )+'>'+city.name+'</option>');
            });
        }
        $('#default_city').empty().append(options.join(''));
        //初始化门店
        var options = [];
        options.push('<option value="">默认门店</option>');
        var arr = storeMap[city_id];
        if( arr!=undefined ){
            $.each(arr, function(i, store){
                options.push('<option value="'+store.id+'"'+( store.id==store_id ? ' selected' : '' )+'>'+store.name+'</option>');
            });
        }
        $('#default_store').empty().append(options.join(''));
    }
    initStore(<?php echo $store['prov']; ?>,<?php echo $store['city']; ?>,<?php echo $single['default_store']; ?>);
    $('#default_prov').change(function(){
        var prov_id = $('#default_prov').val();
        initStore(prov_id,0,0);
    });
    $('#default_city').change(function(){
        var prov_id = $('#default_prov').val();
        var city_id = $('#default_city').val();
        initStore(prov_id,city_id,0);
    });

    $('#form').bootstrapValidator(validate_rules.site).on('success.form.bv', function(e) {
        e.preventDefault();
        var $form = $(e.target);
        var bv = $form.data('bootstrapValidator');
        $.post($form.attr('action'), $form.serialize(), function(rst_json) {
            if(rst_json.err_no != 0){
                $('#danger_alert').empty().text(rst_json.err_msg).show();
                return;
            } else {
                $('#success_alert').empty().text('编辑成功！').show();
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