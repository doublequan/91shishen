<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('products/product'); ?>">商品管理</a></li>
            <li><a href="<?php echo site_url('products/purchase'); ?>">采购单</a></li>
            <li class="active">新增采购单</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12 form_body">
        <div class="alert alert-success" id="success_alert" role="alert" style="display:none;"></div>
        <div class="alert alert-danger" id="danger_alert" role="alert" style="display:none;"></div>

        <form action="<?php echo site_url('products/purchase/doAdd'); ?>" method="post" id="purchase_form">
            <div class="form-horizontal">
            	<div class="row">
            		<div class="form-group col-md-6">
            		    <label for="" class="col-md-3 control-label form_label">
            		        <span><font color="red">*</font></span>
            		        采购类型
            		    </label>
            		    <div class="col-md-9">
            		    	<select name="type" class="form-control" id="type">
            		    	    <option value="">选择采购类型</option>
            		    	    <option value="1">自主采购</option>
            		    	    <option value="2">供应商采购</option>
            		    	</select>
            		    </div>
            		</div>
            		<div class="form-group col-md-6" id="supplier_line">
            			<label for="" class="col-md-3 control-label form_label">
            		        供应商
            		    </label>
            			<div class="col-md-6">
            			    <input type="text" name="supplier_name" class="form-control" id="supplier_name" readOnly>
            			    <input type="hidden" name="supplier_id" class="form-control" id="supplier_id">
            			</div>
            			<div class="col-md-3">
            			    <a href="#add_supplier_page" kesrc="#add_supplier_page" class="btn btn-default btn-block" id="add_supplier_btn">选择供应商</a>
            			</div>
            		</div>
            	</div>

            	<div class="row">
            		<div class="form-group col-md-6">
            		    <label for="checkout_type" class="col-md-3 control-label form_label">
            		        <span><font color="red">*</font></span>
            		        支付方式
            		    </label>
            		    <div class="col-md-9">
            		        <select name="checkout_type" class="form-control" id="checkout_type">
            		            <option value="">选择支付方式</option>
            		            <option value="1">转账</option>
            		            <option value="2">现金</option>
            		            <option value="3">支票</option>
            		            <option value="0">其他</option>
            		        </select>
            		    </div>
            		</div>
            		<div class="form-group col-md-6">
            		    <label for="price_borrow" class="col-md-3 control-label form_label">
            		        <span><font color="red">*</font></span>
            		        预借金额(元)
            		    </label>
            		    <div class="col-md-9">
            		        <input type="text" class="form-control" name="price_borrow" id="price_borrow">
            		    </div>
            		</div>
            	</div>
            	
            	<div class="row">
	            	<div class="form-group col-md-6">
	            	    <label for="" class="col-md-3 control-label form_label">
	            	        <span><font color="red">*</font></span>
	            	        入库门店
	            	    </label>
	            	    <div class="col-md-3">
	            	        <select name="in_prov" class="form-control" id="in_prov" style="padding-right:10px;">
	            	        </select>
	            	    </div>
	            	    <div class="col-md-3">
	            	        <select name="in_city" class="form-control" id="in_city">
	            	        </select>
	            	    </div>
	            	    <div class="col-md-3">
	            	        <select name="store_id" class="form-control" id="in_store">
	            	        </select>
	            	    </div>
	            	</div>
	            	<div class="form-group col-md-6">
	            	    <label for="price_fee" class="col-md-3 control-label form_label">
	            	        <span><font color="red">*</font></span>
	            	        运费金额(元)
	            	    </label>
	            	    <div class="col-md-9">
	            	        <input type="text" class="form-control" name="price_fee" id="price_fee">
	            	    </div>
	            	</div>
	            </div>

	            <div class="row">
	            	<div class="form-group col-md-6">
	            	    <label for="express_id" class="col-md-3 control-label form_label">
	            	        快递公司
	            	    </label>
	            	    <div class="col-md-9">
	            	        <select name="express_id" class="form-control" id="express_id">
	            	            <option value="">选择快递公司</option>
	            	        </select>
	            	    </div>
	            	</div>
	            	<div class="form-group col-md-6">
	            	    <label for="express_no" class="col-md-3 control-label form_label">
	            	        物流编号
	            	    </label>
	            	    <div class="col-md-9">
	            	        <input type="text" class="form-control" name="express_no" id="express_no">
	            	    </div>
	            	</div>
	            </div>

	            <div class="row">
	            	<div class="form-group col-md-6">
	            	    <label for="express_id" class="col-md-3 control-label form_label">
	            	        是否开发票
	            	    </label>
	            	    <div class="col-md-9">
	            	        <select name="express_id" class="form-control" id="express_id">
	            	            <option value="0">否</option>
	            	            <option value="1">是</option>
	            	        </select>
	            	    </div>
	            	</div>
	            	<div class="form-group col-md-6 express_line">
	            	    <label for="invoice_title" class="col-md-3 control-label form_label">
	            	        发票抬头
	            	    </label>
	            	    <div class="col-md-9">
	            	        <input type="text" class="form-control" name="invoice_title" id="invoice_title">
	            	    </div>
	            	</div>
	            </div>

	            <div class="row express_line">
	            	<div class="form-group col-md-6">
	            	    <label for="invoice_content" class="col-md-3 control-label form_label">
	            	        发票类型
	            	    </label>
	            	    <div class="col-md-9">
	            	        <input type="text" class="form-control" name="invoice_content" id="invoice_content">
	            	    </div>
	            	</div>
            	</div>

                <div class="form-group col-md-12">
                    <label for="" class="col-md-1 control-label form_label">
                        原料列表
                    </label>
                    <div class="col-md-11">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <a href="#add_good_page" kesrc="#add_good_page" class="btn btn-primary btn-sm" id="add_good_btn">添加原料</a>
                            </div>
                            <table class="table table-striped table-hover table-center" id="goods_table">
                                <thead>
                                    <tr>
                                        <th width="10%">
                                            原料编号
                                        </th>
                                        <th width="20%">
                                            原料名称
                                        </th>
                                        <th width="10%">
                                            原料最新价格
                                        </th>
                                        <th width="10%">
                                            计价方式
                                        </th>
                                        <th width="10%">
                                            计价单位
                                        </th>
                                        <th width="10%">
                                            最小数量
                                        </th>
                                        <th width="20%">
                                            采购数量
                                        </th>
                                        <th width="10%">
                                            操作
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label for="" class="col-md-1 control-label form_label">
                        商品列表
                    </label>
                    <div class="col-md-11">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <a href="#add_product_page" kesrc="#add_product_page" class="btn btn-primary btn-sm" id="add_product_btn">添加商品</a>
                            </div>
                            <table class="table table-striped table-hover table-center" id="products_table">
                                <thead>
                                    <tr>
                                        <th width="15%">
                                            商品编号
                                        </th>
                                        <th width="15%">
                                            商品货号
                                        </th>
                                        <th width="25%">
                                            商品名称
                                        </th>
                                        <th width="10%">
                                            所属分类
                                        </th>
                                        <th width="10%">
                                            当前销售价
                                        </th>
                                        <th width="10%">
                                            采购数量
                                        </th>
                                        <th width="15%">
                                            操作
                                        </th>
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
                        <a class="btn btn-default btn-block" href="<?php echo site_url('products/purchase'); ?>">返回列表</a>  
                    </div>
                </div>
            </div>               
        </form>
    </div>
</div>

<iframe src="<?php echo site_url('products/supplier/single_select_dialog'); ?>" 
    id="add_supplier_page" class="iframe_dialog" style="height:560px;"></iframe>
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
    tr_str.push('<td>' + product_sku + '<input type="hidden" name="products_id[]" value="' + product_id + '"></td>');
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
    $('select#type').change(function(event) {
    	if($(this).val() == 1){
    		$('#supplier_line').children().hide();
    	}
    	if($(this).val() == 2){
    		$('#supplier_line').children().show();
    	}
    });

    $('select#express_id').change(function(event) {
    	if($(this).val() == 0){
    		$('.express_line').children().hide();
    	}
    	if($(this).val() == 1){
    		$('.express_line').children().show();
    	}

    });

    $('a#add_supplier_btn').click(function(){
        $("a#add_supplier_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
        });
    });

    $('a#add_product_btn').click(function(){
        $("a#add_product_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            // 'beforeShow': function(){

            // },
            'afterClose': function(){
                setTableKeyDown("#products_table", "input[type='text']", 1);
            },
        });
    });
    $('a#add_good_btn').click(function(){
        $("a#add_good_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            // 'beforeShow': function(){

            // },
            'afterClose': function(){
                setTableKeyDown("#goods_table", "input[type='text']", 1);
            },
        });
    });

    var initStore = function(prov_id, city_id, store_id){
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
    initStore(320000,320100,0);
    $('#in_prov').change(function(){
        var prov_id = $(this).val();
        initStore(prov_id,0,0);
    });
    $('#in_city').change(function(){
        var prov_id = parseInt($('#in_prov').val());
        var city_id = $(this).val();
        initStore(prov_id,city_id,0);
    });

    $('#purchase_form')
        .bootstrapValidator(validate_rules.purchase)
        .on('success.form.bv', function(e) {
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
                alert('请输入采购数量！');
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
                        window.location.href = "<?php echo site_url('products/purchase'); ?>";
                    },2000);
                }
            }, 'json');
        });
});
</script>