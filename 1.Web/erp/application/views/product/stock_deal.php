<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                <?php echo $params['type']==1 ? '原料：'.$single['name'] : '商品信息'.$single['title']; ?>&nbsp;&nbsp;&nbsp;&nbsp;手工设置库存
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert" style="display:none;"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert" style="display:none;"></div>
            <form action="<?php echo site_url('products/stock/doDeal'); ?>" method="post" id="stock_deal_form">
                <input type="hidden" name="type" value="<?php echo $params['type']; ?>">
                <input type="hidden" name="id" value="<?php echo $params['id']; ?>">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <label for="name" class="col-sm-2 control-label form_label" style="width:120px;">
                                <span><font color="red">*</font></span>
                                选择门店
                            </label>
                            <div class="col-sm-3 form_input">
                                <select class="form-control" id="prov_id"></select>
                            </div>
                            <div class="col-sm-3 form_input">
                                <select class="form-control" id="city_id"></select>
                            </div>
                            <div class="col-sm-4 form_input">
                                <select name="store_id" class="form-control" id="store_id"></select>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <label for="name" class="col-sm-2 control-label form_label" style="width:120px;">
                                <span><font color="red">*</font></span>
                                变化类型
                            </label>
                            <?php if($flag ==1){?>
                            <div class="col-sm-3 form_input">
                                <input type="radio" name="action" class="action" value="1" checked>添加库存
                            </div>
                            <?php }else{?>
                            <div class="col-sm-3 form_input">
                                <input type="radio" name="action" class="action" value="-1" checked>减少库存
                            </div>
                            <?php }?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <label for="name" class="col-sm-2 control-label form_label" style="width:120px;">
                                <span><font color="red">*</font></span>
                                变化数量
                            </label>
                            <div class="col-sm-3 form_input">
                                <input type="text" name="amount" id="amount" class="form-control" value="">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 form-group">
                        <div class="col-md-2 col-md-offset-5 text-center">
                            <input type="submit" class="btn btn-info btn-block close_btn" id="confirmReceive" value="确认入库">
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
var stores = <?php echo $stores ? json_encode($stores) : '[]'; ?>;
var area_data = <?php echo json_encode($area_data);?>;
var stores_id = <?php echo json_encode($stores_id);?>;
var type = <?php echo json_encode($type);?>;
$(document).ready(function($) {
    var initStore = function(prov_id, city_id, store_id){
        //初始化省份
        var options = [];
        options.push('<option value="">默认门店所属省份</option>');
        $.each(provs, function(i, prov){
            options.push('<option value="'+prov.id+'"'+( prov.id==prov_id ? ' selected' : '' )+'>'+prov.name+'</option>');
        });
        $('#prov_id').empty().append(options.join(''));
        //初始化城市
        var options = [];
        options.push('<option value="">默认门店所属城市</option>');
        var arr = citys[prov_id];
        if( arr!=undefined ){
            $.each(arr, function(i, city){
                options.push('<option value="'+city.id+'"'+( city.id==city_id ? ' selected' : '' )+'>'+city.name+'</option>');
            });
        }
        $('#city_id').empty().append(options.join(''));
        //初始化门店
        var options = [];
        options.push('<option value="'+stores_id+'">'+area_data+'</option>');
        var arr = stores[city_id];
        if( arr!=undefined ){
            $.each(arr, function(i, store){
                options.push('<option value="'+store.id+'"'+( store.id==store_id ? ' selected' : '' )+'>'+store.name+'</option>');
            });
        }
        $('#store_id').empty().append(options.join(''));
    }
    initStore(320000,320100,0);
    $('#prov_id').change(function(){
        var prov_id = $('#prov_id').val();
        initStore(prov_id,0,0);
    });
    $('#city_id').change(function(){
        var prov_id = $('#prov_id').val();
        var city_id = $('#city_id').val();
        initStore(prov_id,city_id,0);
    });

    $('#stock_deal_form').bootstrapValidator(validate_rules.stock_deal).on('success.form.bv', function(e) {
        e.preventDefault();
        var $form = $(e.target);
        var bv = $form.data('bootstrapValidator');

        $.post($form.attr('action'), $form.serialize(), function(rst_json) {
            if(rst_json.err_no != 0){
                $('#danger_alert').empty().text(rst_json.err_msg).show();
                $('#submit_btn').removeAttr('disabled');
                return;
            }
            else{
                $('#success_alert').empty().text('操作库存成功！').show();
                window.setTimeout(function(){
                    window.parent.$.fancybox.close('fade');
                    if(type ==1){
                        window.parent.location.href = "<?php echo site_url('products/stock?qt=good'); ?>";
                    }else{
                        window.parent.location.href = "<?php echo site_url('products/stock?qt=product'); ?>";
                    }
                },2000);
            }
        }, 'json');
    });
});
</script>
</body>
</html>