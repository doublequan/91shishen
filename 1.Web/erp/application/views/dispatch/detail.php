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
                调度单详情
            </h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 form_body">
            <div class="form-horizontal">
                <p class="bg-info form-square-title">门店信息</p>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="" class="col-md-3 control-label form_label" style="width: 100px;">
                            出库门店
                        </label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                            <?php echo isset($areas[$out_store_info['prov']]) ? $areas[$out_store_info['prov']]['name'] : ''; ?>
                            <?php echo isset($areas[$out_store_info['city']]) ? $areas[$out_store_info['city']]['name'] : ''; ?>
                            <?php echo $out_store_info['name']; ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="" class="col-md-3 control-label form_label" style="width: 100px;">
                            入库门店
                        </label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                            <?php echo isset($areas[$in_store_info['prov']]) ? $areas[$in_store_info['prov']]['name'] : ''; ?>
                            <?php echo isset($areas[$in_store_info['city']]) ? $areas[$in_store_info['city']]['name'] : ''; ?>
                            <?php echo $in_store_info['name']; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <?php if(count($dispatch_goods) > 0){ ?>
                <p class="bg-info form-square-title">原料信息</p>
                <div class="panel panel-default">
                    <table class="table table-striped table-hover table-center" id="goods_table">
                        <thead>
                            <tr>
                                <th width="10%">原料编号</th>
                                <th width="30%">原料名称</th>
                                <th width="14%">计量方式</th>
                                <th width="13%">计量单位</th>
                                <th width="13%">最小单位数量</th>
                                <th width="10%">最新采购价</th>
                                <th width="10%">调度数量</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($dispatch_goods as $value) { ?>
                            <tr good_id="<?php echo $value['id']; ?>">
                                <td>
                                    <?php echo $value['id']; ?>
                                </td>
                                <td>
                                    <?php echo $value['name']; ?>
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
                                    ￥<?php echo $value['price']; ?>
                                </td>
                                <td>
                                    <?php echo $amount_arr['good'][$value['id']]; ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php } ?>

                <?php if(count($dispatch_products) > 0){ ?>
                <p class="bg-info form-square-title">商品信息</p>
                <div class="panel panel-default">
                    <table class="table table-striped table-hover table-center" id="products_table">
                        <thead>
                            <tr>
                                <th width="20%">商品编号</th>
                                <th width="15%">商品货号</th>
                                <th width="30%">商品名称</th>
                                <th width="15%">规格</th>
                                <th width="10%">当前价格</th>
                                <th width="10%">调度数量</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($dispatch_products as $value) { ?>
                            <tr product_id="<?php echo $value['id']; ?>">
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
                                    <?php echo $value['spec']; ?>
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
</body>
</html>