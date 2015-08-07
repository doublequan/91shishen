<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('products/product'); ?>">商品管理</a></li>
            <li><a href="<?php echo site_url('products/stock'); ?>">库存管理</a></li>
            <li class="active">添加库存</li>
        </ol>
    </div>
</div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert" style="display:none;"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert" style="display:none;"></div>

            <form action="<?php echo site_url('products/stock/doAdd'); ?>" method="post" id="stock_form">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="" class="col-md-1 control-label form_label" style="width: 100px;">
                            <span><font color="red">*</font></span>
                            入库门店
                        </label>
                        <div class="col-md-3">
                            <select name="in_prov" class="form-control" id="in_prov">
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="in_city" class="form-control" id="in_city">
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="in_store" class="form-control" id="in_store">
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-md-1 control-label form_label" style="width: 100px;">
                            原料列表
                        </label>
                        <div class="col-md-10">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <a href="#add_good_page" kesrc="#add_good_page" class="btn btn-primary btn-sm" id="add_good_btn">添加原料</a>
                                </div>
                                <table class="table table-striped table-hover table-center" id="goods_table">
                                    <thead>
                                        <tr>
                                            <th width="10%">原料编号</th>
                                            <th width="25%">原料名称</th>
                                            <th width="10%">最新采购价</th>
                                            <th width="10%">计量方式</th>
                                            <th width="10%">计量单位</th>
                                            <th width="10%">最小单位数量</th>
                                            <th width="15%">添加数量</th>
                                            <th width="10%">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-md-1 control-label form_label" style="width: 100px;">
                            商品列表
                        </label>
                        <div class="col-md-10">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <a href="#add_product_page" kesrc="#add_product_page" class="btn btn-primary btn-sm" id="add_product_btn">添加商品</a>
                                </div>
                                <table class="table table-striped table-hover table-center" id="products_table">
                                    <thead>
                                        <tr>
                                            <th width="15%">商品编号</th>
                                            <th width="15%">商品货号</th>
                                            <th width="25%">商品名称</th>
                                            <th width="10%">所属分类</th>
                                            <th width="10%">当前价格</th>
                                            <th width="15%">添加数量</th>
                                            <th width="10%">操作</th>
                                            <!--<th width="20%" id="product_title" style="display:none;"></th>-->
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                
                    <div class="col-md-12 form-group">
                        <div class="col-md-2 col-md-offset-4 text-center">
                            <button type="submit" class="btn btn-primary btn-block" id="submit_btn">提 交</button>  
                        </div>
                        <div class="col-md-2 text-center">
                            <a class="btn btn-default btn-block" href="<?php echo site_url('products/stock'); ?>">返回列表</a>  
                        </div>
                    </div>
                </div>               
            </form>
        </div>
    </div>

<iframe src="<?php echo site_url('products/product/muti_select_dialog'); ?>" 
    id="add_product_page" class="iframe_dialog" style="height:580px;"></iframe>
<iframe src="<?php echo site_url('products/good/muti_select_dialog'); ?>" 
    id="add_good_page" class="iframe_dialog" style="height:580px;"></iframe>
<script src="<?php echo base_url('static/js/jquery.fancybox.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
function generateProductTr(product_info, category_name){
    var product_info = $.parseJSON(product_info);
    var product_id = product_info.id;
    var product_sku = product_info.sku;
    var product_pin = product_info.product_pin;
    var product_title = product_info.title;
    var price = product_info.price;

    var tr_str = [];
    tr_str.push('<tr product_id="' + product_id + '">');
    tr_str.push('<td>' + product_sku + '<input type="hidden" class="products_id" name="products_id[]" value="' + product_id + '"></td>');
    tr_str.push('<td>' + product_pin + '</td>');
    tr_str.push('<td>' + product_title + '</td>');
    tr_str.push('<td>' + category_name + '</td>');
    tr_str.push('<td>￥' + price + '</td>');
    tr_str.push('<td style="padding:3px;"><input type="text" class="form-control input-sm products_amount" style="width:60px;" name="products_amount[]"></td>');
    tr_str.push('<td><a href="javascript:void(0)" class="delete_tr_link">删除</a></td>');
    return tr_str.join('');
}
function setTableKeyDown(table, input, columns){
    $(table).keydown(function(e){
        var inputs = $(table + " " + input);
        var rows = inputs.length / columns;
        var focus = document.activeElement;
        var idx = 0;
        for(var idx=0; idx<inputs.length; idx++)
        {
            if(inputs[idx]===focus)break;
        }
        var newidx;
        switch (e.which)
        {
            case 37:    //左
               newidx = idx-1;
               break;
            case 38:    //上
               newidx = idx - columns;
               break;
            case 39:    //右
               newidx = idx + 1;
               break;
            case 40:    //下
               newidx = idx + columns;
               break;
            default:
               newidx = idx;
               return;
        }  
        //如果沒有超出范围，指到新的index
        if(newidx >= 0 && newidx < inputs.length)
        {
           inputs[newidx].focus();
        }
    });
}
var provs = <?php echo $provs ? json_encode($provs) : '[]'; ?>;
var citys = <?php echo $citys ? json_encode($citys) : '[]'; ?>;
var stores = <?php echo $stores ? json_encode($stores) : '[]'; ?>;
$(document).ready(function($) {
    $('a#add_good_btn').click(function(){
        $("a#add_good_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            'afterClose': function(){
                setTableKeyDown("#goods_table", "input[type='text']", 1);
            },
        });
    });
    $('a#add_product_btn').click(function(){
        $("a#add_product_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            'afterClose': function(){
                setTableKeyDown("#products_table", "input[type='text']", 1);
            },
        });
    });
    var initInStore = function(prov_id, city_id, store_id){
        //初始化省份
        var options = [];
        options.push('<option value="">选择省份</option>');
        $.each(provs, function(i, prov){
            options.push('<option value="'+prov.id+'"'+( prov.id==prov_id ? ' selected' : '' )+'>'+prov.name+'</option>');
        });
        $('#in_prov').empty().append(options.join(''));
        //初始化城市
        var options = [];
        options.push('<option value="">选择城市</option>');
        var arr = citys[prov_id];
        if( arr!=undefined ){
            $.each(arr, function(i, city){
                options.push('<option value="'+city.id+'"'+( city.id==city_id ? ' selected' : '' )+'>'+city.name+'</option>');
            });
        }
        $('#in_city').empty().append(options.join(''));
        //初始化门店
        var options = [];
        options.push('<option value="">选择门店</option>');
        var arr = stores[city_id];
        if( arr!=undefined ){
            $.each(arr, function(i, store){
                options.push('<option value="'+store.id+'"'+( store.id==store_id ? ' selected' : '' )+'>'+store.name+'</option>');
            });
        }
        $('#in_store').empty().append(options.join(''));
    }
    initInStore(320000,320100,0);
    $('#in_prov').change(function(){
        var prov_id = $(this).val();
        initInStore(prov_id,0,0);
    });
    $('#in_city').change(function(){
        var prov_id = parseInt($('#in_prov').val());
        var city_id = $(this).val();
        initInStore(prov_id,city_id,0);
    });

    $('#stock_form').bootstrapValidator(validate_rules.stock_add).on('success.form.bv', function(e) {
        if($('#goods_table tbody tr').length + $('#products_table tbody tr').length == 0){
            alert('请选择商品或原料！');
            $('#submit_btn').removeAttr('disabled');
            return false;
        }

        var check_rst = true;
        $.each($('input.goods_amount, input.products_amount'), function(idx, val) {
            if(!val.value){
                check_rst = false;
                return false;
            }
        });

        if(check_rst == false){
            alert('请输入调度数量！');
            $('#submit_btn').removeAttr('disabled');
            return false;
        }

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
                $('#success_alert').empty().text('添加成功！').show();
                window.setTimeout(function(){
                    window.location.href = "<?php echo site_url('products/stock'); ?>";
                },2000);
            }
        }, 'json');
    });
});
</script>