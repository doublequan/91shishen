<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/datetimepicker.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('products/product'); ?>">商品管理</a></li>
            <li><a href="<?php echo site_url('products/dispatch'); ?>">调度单</a></li>
            <li class="active">添加调度单</li>
        </ol>
    </div>
</div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert" style="display:none;"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert" style="display:none;"></div>

            <form action="<?php echo site_url('products/dispatch/doAdd'); ?>" method="post" id="dispatch_form">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="" class="col-md-1 control-label form_label" style="width: 100px;">
                            <span><font color="red">*</font></span>
                            出货门店
                        </label>
                        <div class="col-md-3">
                            <select name="out_prov" class="form-control" id="out_prov">
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="out_city" class="form-control" id="out_city">
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="out_store" class="form-control" id="out_store">
                            </select>
                        </div>
                    </div>
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
                            <span><font color="red">*</font></span>
                            负责员工
                        </label>
                        <div class="col-md-3">
                            <input type="text" name="employee_name" class="form-control" id="employee_name" readOnly>
                            <input type="hidden" name="delivery_eid" class="form-control" id="employee_id">
                        </div>
                        <div class="col-md-3">
                            <a href="#add_employee_page" kesrc="#add_employee_page" class="btn btn-default" id="add_employee_btn">选择员工</a>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="datetime" class="col-md-1 control-label form_label" style="width: 100px;">
                            <span><font color="red">*</font></span>
                            调度日期
                        </label>
                        <div class="col-md-3">
                            <input type="text" name="datetime" class="form-control" id="datetime" readOnly>
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
                                            <th width="15%">调度数量</th>
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
                                            <th width="15%">调度数量</th>
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
                            <a class="btn btn-default btn-block" href="<?php echo site_url('products/dispatch'); ?>">返回列表</a>  
                        </div>
                    </div>
                </div>               
            </form>
        </div>
    </div>

<iframe src="<?php echo site_url('employee/employee/select_dialog'); ?>" 
    id="add_employee_page" class="iframe_dialog" style="height:560px;"></iframe>
<iframe src="<?php echo site_url('products/product/muti_select_dialog'); ?>" 
    id="add_product_page" class="iframe_dialog" style="height:580px;"></iframe>
<iframe src="<?php echo site_url('products/good/muti_select_dialog'); ?>" 
    id="add_good_page" class="iframe_dialog" style="height:580px;"></iframe>
<script src="<?php echo base_url('static/js/jquery.fancybox.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/bootstrap-datetimepicker.js'); ?>" type="text/javascript"></script>
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
var provs = <?php echo $provs ? json_encode($provs) : '[]'; ?>;
var citys = <?php echo $citys ? json_encode($citys) : '[]'; ?>;
var stores = <?php echo $stores ? json_encode($stores) : '[]'; ?>;
$(document).ready(function($) {
    $('#datetime').datetimepicker({
        language:  'zh',
        format: 'yyyy-mm-dd',
        autoclose: 1,
        todayHighlight: 0,
        startView: 2,
        minView: 2,
        maxView: 3,
    });

    $("#datetime").change(function(){
        $('#dispatch_form')
            .bootstrapValidator('updateStatus', 'datetime', 'NOT_VALIDATED')
            .bootstrapValidator('validateField', 'datetime');
    });

    $('a#add_employee_btn').click(function(){
        $("a#add_employee_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            'afterClose': function(){
                $('#dispatch_form')
                    .bootstrapValidator('updateStatus', 'employee_name', 'NOT_VALIDATED')
                    .bootstrapValidator('validateField', 'employee_name');
            },
        });
    });

    $('a#add_product_btn').click(function(){
        var store_id = parseInt($('#out_store').val());
        if( !store_id ){
            alert('请先选择出货门店');
            return false;
        }
        var store_name = $('#out_store option:selected').text();
        $("a#add_product_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            'afterClose': function(){
                /**
                var arr = new Array();
                $('#products_table tbody .products_id').each( function(){
                    var id = $(this).val();
                    arr.push(id);
                });
                if( arr ){
                    var url = '<?php echo site_url('products/dispatch/getStock'); ?>';
                    var params = {
                        'type'      : 'product',
                        'store_id'  : store_id,
                        'ids'       : arr.toString()
                    };
                    $.get(url, params, function(data){
                        if( data.err_no==0 ){
                            var stocks = data.stocks;
                            $('#products_table tbody tr').each( function(i){
                                $(this).append('<td class="stock">'+stocks[i]+'</td>');
                            });
                            $('#product_title').html(store_name+'库存').show();
                        } else {
                            $('#product_title').html('').hide();
                            alert(data.err_msg);
                        }
                    },'json');
                }
                */
            },
        });
    });
    $('a#add_good_btn').click(function(){
        var store_id = parseInt($('#out_store').val());
        if( !store_id ){
            alert('请先选择出货门店');
            return false;
        }
        $("a#add_good_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
        });
    });

    var initOutStore = function(prov_id, city_id, store_id){
        //初始化省份
        var options = [];
        options.push('<option value="">选择省份</option>');
        $.each(provs, function(i, prov){
            options.push('<option value="'+prov.id+'"'+( prov.id==prov_id ? ' selected' : '' )+'>'+prov.name+'</option>');
        });
        $('#out_prov').empty().append(options.join(''));
        //初始化城市
        var options = [];
        options.push('<option value="">选择城市</option>');
        var arr = citys[prov_id];
        if( arr!=undefined ){
            $.each(arr, function(i, city){
                options.push('<option value="'+city.id+'"'+( city.id==city_id ? ' selected' : '' )+'>'+city.name+'</option>');
            });
        }
        $('#out_city').empty().append(options.join(''));
        //初始化门店
        var options = [];
        options.push('<option value="">选择门店</option>');
        var arr = stores[city_id];
        if( arr!=undefined ){
            $.each(arr, function(i, store){
                options.push('<option value="'+store.id+'"'+( store.id==store_id ? ' selected' : '' )+'>'+store.name+'</option>');
            });
        }
        $('#out_store').empty().append(options.join(''));
    }
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
    initOutStore(320000,320100,0);
    initInStore(320000,320100,0);
    $('#out_prov').change(function(){
        var prov_id = $(this).val();
        initOutStore(prov_id,0,0);
    });
    $('#out_city').change(function(){
        var prov_id = parseInt($('#out_prov').val());
        var city_id = $(this).val();
        initOutStore(prov_id,city_id,0);
    });
    $('#in_prov').change(function(){
        var prov_id = $(this).val();
        initInStore(prov_id,0,0);
    });
    $('#in_city').change(function(){
        var prov_id = parseInt($('#in_prov').val());
        var city_id = $(this).val();
        initInStore(prov_id,city_id,0);
    });

    $('#dispatch_form').bootstrapValidator(validate_rules.dispatch).on('success.form.bv', function(e) {
        var out_store_num = $('#out_store').val();
        var in_store_num = $('#in_store').val();
        if( out_store_num==in_store_num ){
            alert('出货门店与入库门店相同，请重新选择！');
            return false;
        }
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
                    window.location.href = "<?php echo site_url('products/dispatch'); ?>";
                },2000);
            }
        }, 'json');
    });
});
</script>