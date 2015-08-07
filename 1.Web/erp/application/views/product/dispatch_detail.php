<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">

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
                <div class="form-group col-md-6">
                    <label for="" class="col-md-3 control-label form_label" style="width: 100px;">
                        出货门店
                    </label>
                    <div class="col-md-8">
                        <p class="form-control-static">
                        <?php echo $out_prov.' '.$out_city.'： '.$out_store_info['name']; ?>
                        </p>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="" class="col-md-3 control-label form_label" style="width: 100px;">
                        <span><font color="red">*</font></span>
                        入库门店
                    </label>
                    <div class="col-md-8">
                        <p class="form-control-static">
                        <?php echo $in_prov.' '.$in_city.'： '.$in_store_info['name']; ?>
                        </p>
                    </div>
                </div>
            
                <div class="form-group col-md-6">
                    <label for="" class="col-md-3 control-label form_label" style="width: 100px;">
                        <span><font color="red">*</font></span>
                        负责员工
                    </label>
                    <div class="col-md-8">
                        <p class="form-control-static">
                        <?php echo $employee_info['username']; ?>
                        </p>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="datetime" class="col-md-3 control-label form_label" style="width: 100px;">
                        <span><font color="red">*</font></span>
                        调度日期
                    </label>
                    <div class="col-md-8">
                        <p class="form-control-static">
                        <?php echo date('Y-m-d', strtotime($single['datetime'])); ?>
                        </p>
                    </div>
                </div>

                <?php if(count($dispatch_goods) > 0){ ?>
                <div class="form-group">
                    <label for="" class="col-md-1 control-label form_label" style="width: 100px;">
                        原料列表
                    </label>
                    <div class="col-md-10">
                        <div class="panel panel-default">
                            <table class="table table-striped table-hover table-center" id="goods_table">
                                <thead>
                                    <tr>
                                        <th width="10%">原料编号</th>
                                        <th width="25%">原料名称</th>
                                        <th width="15%">最新采购价</th>
                                        <th width="10%">计量方式</th>
                                        <th width="10%">计量单位</th>
                                        <th width="15%">最小单位数量</th>
                                        <th width="15%">调度数量</th>
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

                <?php if(count($dispatch_products) > 0){ ?>
                <div class="form-group">
                    <label for="" class="col-md-1 control-label form_label" style="width: 100px;">
                        商品列表
                    </label>
                    <div class="col-md-10">
                        <div class="panel panel-default">
                            <table class="table table-striped table-hover table-center" id="products_table">
                                <thead>
                                    <tr>
                                        <th width="15%">商品编号</th>
                                        <th width="10%">商品货号</th>
                                        <th width="25%">商品名称</th>
                                        <th width="20%">所属分类</th>
                                        <th width="15%">当前价格</th>
                                        <th width="15%">调度数量</th>
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
                                            <?php echo isset($categorys[$value['category_id']]) ? $categorys[$value['category_id']]['name'] : ''; ?>
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
