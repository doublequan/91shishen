<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                采购单详情
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="form-horizontal">
            	<div class="row">
            		<div class="form-group col-md-6">
            		    <label for="" class="col-md-3 control-label form_label">
            		        采购类型
            		    </label>
            		    <div class="col-md-9">
                            <p class="form-control-static">
                            <?php 
                            if($single['type']==1) 
                                echo '自主采购';
                            elseif($single['type']==2)
                                echo '供应商采购';
                            ?>
                            </p>
            		    </div>
            		</div>
            		<div class="form-group col-md-6" id="supplier_line">
            			<label for="" class="col-md-3 control-label form_label">
            		        供应商
            		    </label>
            			<div class="col-md-9">
                            <p class="form-control-static">
                            <?php echo empty($supplier_info['sup_name'])?'':$supplier_info['sup_name']; ?>
                            </p>
            			</div>
            		</div>
            	</div>

            	<div class="row">
            		<div class="form-group col-md-6">
            		    <label for="checkout_type" class="col-md-3 control-label form_label">
            		        支付方式
            		    </label>
            		    <div class="col-md-9">
                            <p class="form-control-static">
                            <?php 
                            if($single['checkout_type']==1) 
                                echo '转账';
                            elseif($single['checkout_type']==2)
                                echo '现金';
                            elseif($single['checkout_type']==3)
                                echo '支票';
                            elseif($single['checkout_type']==0)
                                echo '其他';
                            ?>
                            </p>
            		        
            		    </div>
            		</div>
            		<div class="form-group col-md-6">
            		    <label for="price_borrow" class="col-md-3 control-label form_label">
            		        预借金额(元)
            		    </label>
            		    <div class="col-md-9">
                            <p class="form-control-static">
                            <?php echo empty($single['price_borrow'])?'':$single['price_borrow']; ?>
                            </p>
            		    </div>
            		</div>
            	</div>
            	
            	<div class="row">
                	<div class="form-group col-md-6">
                	    <label for="" class="col-md-3 control-label form_label">
                	        入库门店
                	    </label>
                	    <div class="col-md-9">
                            <p class="form-control-static">
                            <?php echo $store_prov.' '.$store_city.'： '.$store_info['name']; ?>
                            </p>
                	    </div>
                	</div>
                	<div class="form-group col-md-6">
                	    <label for="price_fee" class="col-md-3 control-label form_label">
                	        运费金额(元)
                	    </label>
                	    <div class="col-md-9">
                            <p class="form-control-static">
                	        <?php echo empty($single['price_fee'])?'':$single['price_fee']; ?>
                            </p>
                	    </div>
                	</div>
                </div>

                <div class="row">
                	<div class="form-group col-md-6">
                	    <label for="express_id" class="col-md-3 control-label form_label">
                	        快递公司
                	    </label>
                	    <div class="col-md-9">
                            <p class="form-control-static">
                            <?php echo empty($single['express_id'])?'':$single['express_id']; ?>
                            </p>
                	    </div>
                	</div>
                	<div class="form-group col-md-6">
                	    <label for="express_no" class="col-md-3 control-label form_label">
                	        物流编号
                	    </label>
                	    <div class="col-md-9">
                            <p class="form-control-static">
                	        <?php echo $single['express_no']; ?>
                            </p>
                	    </div>
                	</div>
                </div>

                <div class="row">
                	<div class="form-group col-md-6">
                	    <label for="express_id" class="col-md-3 control-label form_label">
                	        是否开发票
                	    </label>
                	    <div class="col-md-9">
                            <p class="form-control-static">
                            <?php 
                            if($single['express_id']==0) 
                                echo '否';
                            elseif($single['express_id']==1)
                                echo '是';
                            ?>
                            </p>
                	    </div>
                	</div>
                	<div class="form-group col-md-6 express_line">
                	    <label for="invoice_title" class="col-md-3 control-label form_label">
                	        发票抬头
                	    </label>
                	    <div class="col-md-9">
                            <p class="form-control-static">
                	        <?php echo $single['invoice_title']; ?>
                            </p>
                	    </div>
                	</div>
                </div>

                <div class="row express_line">
                	<div class="form-group col-md-6">
                	    <label for="invoice_content" class="col-md-3 control-label form_label">
                	        发票类型
                	    </label>
                	    <div class="col-md-9">
                            <p class="form-control-static">
                            <?php echo $single['invoice_content']; ?>
                            </p>
                	    </div>
                	</div>
            	</div>
                
                <?php if(count($purchase_goods) > 0){ ?>
                <div class="form-group col-md-12">
                    <label for="" class="col-md-1 control-label form_label">
                        原料
                    </label>
                    <div class="col-md-11">
                        <div class="panel panel-default">
                            <table class="table table-striped table-hover table-center" id="goods_table">
                                <thead>
                                    <tr>
                                        <th style="width:80px;">
                                            原料编号
                                        </th>
                                        <th style="width:150px;">
                                            原料名称
                                        </th>
                                        <th style="width:80px;">
                                            采购价
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
                                        <th style="width:100px;">
                                            采购数量
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($purchase_goods as $value) { ?>
                                    <tr>
                                        <td>
                                            <?php echo $value['id']; ?>
                                        </td>
                                        <td>
                                            <?php echo $value['name']; ?>
                                        </td>
                                        <td>
                                            ￥<?php echo $value['price']; ?>
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
                                        <td>
                                            <?php echo $amount_arr['good'][$value['id']]; ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php if(count($purchase_products) > 0){ ?>
                <div class="form-group col-md-12">
                    <label for="" class="col-md-1 control-label form_label">
                        商品
                    </label>
                    <div class="col-md-11">
                        <div class="panel panel-default">
                            <table class="table table-striped table-hover table-center" id="products_table">
                                <thead>
                                    <tr>
                                        <th width="15%">商品编号</th>
                                        <th width="15%">商品货号</th>
                                        <th width="35%">商品名称</th>
                                        <th width="15%">计价单位</th>
                                        <th width="10%">销售价</th>
                                        <th width="10%">采购数量</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($purchase_products as $value) { ?>
                                    <tr>
                                        <td>
                                            <?php echo $value['sku']; ?>
                                        </td>
                                        <td>
                                            <?php echo $value['product_pin']; ?>
                                        </td>
                                        <td>
                                            <?php echo $value['title']; ?>
                                        </td>
                                        <td>
                                            <?php echo $value['unit'];?>
                                        </td>
                                        <td>
                                            ￥<?php echo $value['price']; ?>
                                        </td>
                                        <td>
                                            <?php echo $amount_arr['product'][$value['id']]; ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <div class="col-md-12 form-group">
                    <div class="col-md-2 col-md-offset-5 text-center">
                        <input type="button" class="btn btn-default btn-block close_btn" value="返回列表">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('static/js/jquery.min.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function($) {
    $('.close_btn').click(function(){
        window.parent.$.fancybox.close('fade');
    });
});
</script>
