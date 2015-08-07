<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('products/product'); ?>">商品管理</a></li>
            <li><a href="<?php echo site_url('products/purchase'); ?>">采购单</a></li>
            <li class="active">编辑采购单</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12 form_body">
        <div class="alert alert-success" id="success_alert" role="alert" style="display:none;"></div>
        <div class="alert alert-danger" id="danger_alert" role="alert" style="display:none;"></div>

        <form action="<?php echo site_url('products/purchase/doEdit'); ?>" method="post" id="purchase_form">
            <input type="hidden" name="id" value="<?php echo $single['id']; ?>">
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
            		    	    <option value="1" <?php echo $single['type']==1?'selected':''; ?>>自主采购</option>
            		    	    <option value="2" <?php echo $single['type']==2?'selected':''; ?>>供应商采购</option>
            		    	</select>
            		    </div>
            		</div>
            		<div class="form-group col-md-6" id="supplier_line">
            			<label for="" class="col-md-3 control-label form_label">
            		        供应商
            		    </label>
            			<div class="col-md-6">
            			    <input type="text" name="supplier_name" class="form-control" id="supplier_name" readOnly
            			    	value="<?php echo empty($supplier_info['sup_name'])?'':$supplier_info['sup_name']; ?>">
            			    <input type="hidden" name="supplier_id" class="form-control" id="supplier_id"
            			    	value="<?php echo empty($supplier_info['id'])?'':$supplier_info['id']; ?>">
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
            		            <option value="1" <?php echo $single['checkout_type']==1?'selected':''; ?>>转账</option>
            		            <option value="2" <?php echo $single['checkout_type']==2?'selected':''; ?>>现金</option>
            		            <option value="3" <?php echo $single['checkout_type']==3?'selected':''; ?>>支票</option>
            		            <option value="0" <?php echo $single['checkout_type']==0?'selected':''; ?>>其他</option>
            		        </select>
            		    </div>
            		</div>
            		<div class="form-group col-md-6">
            		    <label for="price_borrow" class="col-md-3 control-label form_label">
            		        <span><font color="red">*</font></span>
            		        预借金额(元)
            		    </label>
            		    <div class="col-md-9">
            		        <input type="text" class="form-control" name="price_borrow" id="price_borrow"
            		        	value="<?php echo empty($single['price_borrow'])?'':$single['price_borrow']; ?>">
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
	            	        <input type="text" class="form-control" name="price_fee" id="price_fee"
	            	        	value="<?php echo empty($single['price_fee'])?'':$single['price_fee']; ?>">
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
	            	        <input type="text" class="form-control" name="express_no" id="express_no"
	            	        	value="<?php echo $single['express_no']; ?>">
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
	            	            <option value="0" <?php echo $single['express_id']==0?'selected':''; ?>>否</option>
	            	            <option value="1" <?php echo $single['express_id']==1?'selected':''; ?>>是</option>
	            	        </select>
	            	    </div>
	            	</div>
	            	<div class="form-group col-md-6 express_line">
	            	    <label for="invoice_title" class="col-md-3 control-label form_label">
	            	        发票抬头
	            	    </label>
	            	    <div class="col-md-9">
	            	        <input type="text" class="form-control" name="invoice_title" id="invoice_title"
	            	        	value="<?php echo $single['invoice_title']; ?>">
	            	    </div>
	            	</div>
	            </div>

	            <div class="row express_line">
	            	<div class="form-group col-md-6">
	            	    <label for="invoice_content" class="col-md-3 control-label form_label">
	            	        发票类型
	            	    </label>
	            	    <div class="col-md-9">
	            	        <input type="text" class="form-control" name="invoice_content" id="invoice_content"
	            	        	value="<?php echo $single['invoice_content']; ?>">
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
                                <?php foreach ($purchase_goods as $value) { ?>
                                    <tr good_id="<?php echo $value['id']; ?>">
                                        <td>
                                            <?php echo $value['id']; ?>
                                            <input type="hidden" name="goods_id[]" value="<?php echo $value['id']; ?>">
                                        </td>
                                        <td>
                                            <?php echo $value['name']; ?>
                                        </td>
                                        <td>
                                            <?php echo $value['price'] ? '￥'.$value['price'] : '未录入价格'; ?>
                                        </td>
                                        <td>
                                            <?php echo isset($good_method_types[$value['method']]) ? $good_method_types[$value['method']] : ''; ?>
                                        </td>
                                        <td>
                                            <?php echo $value['unit']; ?>
                                        </td>
                                        <td>
                                            <?php echo $value['amount']; ?>
                                        </td>
                                        <td style="padding:3px;">
                                            <input type="text" class="form-control input-sm goods_amount" style="width:60px;" 
                                            	name="goods_amount[]"  value="<?php echo $amount_arr['good'][$value['id']]; ?>">
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" class="delete_tr_link">删除</a>
                                        </td>
                                    </tr>
                                <?php } ?>
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
                                        <th width="15%">商品编号</th>
                                        <th width="10%">商品货号</th>
                                        <th width="30%">商品名称</th>
                                        <th width="15%">计价单位</th>
                                        <th width="10%">销售价</th>
                                        <th width="10%">采购数量</th>
                                        <th width="10%">操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($purchase_products as $value) { ?>
                                    <tr product_id="<?php echo $value['id']; ?>">
                                        <td>
                                            <?php echo $value['sku']; ?>
                                            <input type="hidden" name="products_id[]" value="<?php echo $value['id']; ?>">
                                        </td>
                                        <td>
                                            <?php echo $value['product_pin']; ?>
                                        </td>
                                        <td>
                                            <?php echo $value['title']; ?>
                                        </td>
                                        <td>
                                            <?php echo $value['unit']; ?>
                                        </td>
                                        <td>
                                            ￥<?php echo $value['price']; ?>
                                        </td>
                                        <td style="padding:3px;">
                                            <input type="text" class="form-control input-sm products_amount" style="width:60px;" 
                                            	name="products_amount[]" value="<?php echo $amount_arr['product'][$value['id']]; ?>">
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" class="delete_tr_link">删除</a>
                                        </td>
                                    </tr>
                                <?php } ?>
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

    $('.delete_tr_link').click(function(){
        $(this).parent().parent('tr').remove();
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
            // 'afterClose': function(){
                
            // },
        });
    });
    $('a#add_good_btn').click(function(){
        $("a#add_good_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            // 'beforeShow': function(){

            // },
            // 'afterClose': function(){
                
            // },
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
    initStore(<?php echo $store['prov']; ?>,<?php echo $store['city']; ?>,<?php echo $single['store_id']; ?>);
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
                    $('#success_alert').empty().text('修改成功！').show();
                    window.setTimeout(function(){
                        window.location.href = "<?php echo site_url('products/purchase'); ?>";
                    },2000);
                }
            }, 'json');
        });
});

</script>
