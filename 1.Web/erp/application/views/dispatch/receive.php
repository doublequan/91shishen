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
                调度单入库，单号： <?php echo $single['id']; ?>
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert" style="display:none;"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert" style="display:none;"></div>
            <form action="<?php echo site_url('dispatch/index/doConfirmReceive'); ?>" method="post" id="receive_form">
                <input type="hidden" name="dispatch_id" value="<?php echo $single['id']; ?>">
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
                    <?php if( isset($details[1]) && $details[1] ){ ?>
                    <p class="bg-info form-square-title">原料入库</p>
                    <div class="panel panel-default">
                        <table class="table table-striped table-hover table-center">
                            <thead>
                                <tr>
                                    <th width="30%">原料名称</th>
                                    <th width="10%">计量方式</th>
                                    <th width="10%">计量单位</th>
                                    <th width="12%">最小单位数量</th>
                                    <th width="12%">最新采购价</th>
                                    <th width="13%">计划调度数量</th>
                                    <th width="13%">实际入库数量</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach( $details[1] as $k=>$row ){ ?>
                                <tr>
                                    <td>
                                        <input type="hidden" name="detail_id[]" value="<?php echo $row['detail_id']; ?>">
                                        <?php echo $row['name']; ?>
                                    </td>
                                    <td>
                                        <?php echo isset($good_method_types[$row['method']]) ? $good_method_types[$row['method']] : ''; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['unit']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['amount']; ?>
                                    </td>
                                    <td>
                                        ￥<?php echo $row['price']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['amount_plan']; ?>
                                    </td>
                                    <td style="padding:3px;">
                                        <input type="text" class="form-control input-sm text-center amount_real" style="width:80px;" name="amount_real[]" value="<?php echo $row['amount_plan']; ?>">
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } ?>

                    <?php if( isset($details[2]) && $details[2] ){ ?>
                    <p class="bg-info form-square-title">商品入库</p>
                    <div class="panel panel-default">
                        <table class="table table-striped table-hover table-center">
                            <thead>
                                <tr>
                                    <th width="16%">商品编号</th>
                                    <th width="10%">商品货号</th>
                                    <th width="30%">商品名称</th>
                                    <th width="10%">规格</th>
                                    <th width="8%">当前价格</th>
                                    <th width="13%">计划调度数量</th>
                                    <th width="13%">实际入库数量</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach( $details[2] as $k=>$row ){ ?>
                                <tr>
                                    <td>
                                        <input type="hidden" name="detail_id[]" value="<?php echo $row['detail_id']; ?>">
                                        <?php echo $row['sku']; ?>
                                    </td>
                                    <td><?php echo $row['product_pin']; ?></td>
                                    <td><?php echo $row['title']; ?></td>
                                    <td><?php echo $row['spec']; ?></td>
                                    <td><label>￥</label><?php echo $row['price']; ?></td>
                                    <td><?php echo $row['amount_plan']; ?></td>
                                    <td style="padding:3px;">
                                        <input type="text" class="form-control input-sm text-center amount_real" style="width:80px;" name="amount_real[]" value="<?php echo $row['amount_plan']; ?>">
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } ?>

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
$(document).ready(function($) {
    $('#receive_form').bootstrapValidator().on('success.form.bv', function(e) {
        //检查实际采购价格
        var check_rst = true;
        $('.price_real').each( function(){
            var t = $(this).val();
            if( t.length==0 ){
                $(this).focus();
                check_rst = false;
                return false;
            }
        });
        if(check_rst == false){
            alert('实际采购价格不能为空，免费可以填写0');
            $('#confirmReceive').removeAttr('disabled');
            return false;
        }
        //检查实际采购数量
        var check_rst = true;
        $('input.amount_real').each( function(){
            var t = $(this).val();
            if( t.length==0 ){
                $(this).focus();
                check_rst = false;
                return false;
            }
        });
        if(check_rst == false){
            alert('实际采购数量不能为空，采购为空可以填写0');
            $('#confirmReceive').removeAttr('disabled');
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
                $('#success_alert').empty().text('调度单入库成功！').show();
                window.setTimeout(function(){
                    window.parent.$.fancybox.close('fade');
                    window.parent.location.href = "<?php echo site_url('dispatch/index?status=3'); ?>";
                },2000);
            }
        }, 'json');
    });
});
</script>
</body>
</html>