<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                采购单入库
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert" style="display:none;"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert" style="display:none;"></div>
            <form action="<?php echo site_url('products/purchase/doConfirmReceive'); ?>" method="post" id="purchase_form">
                <input type="hidden" name="purchase_id" value="<?php echo $single['id']; ?>">
                <div class="form-horizontal">
                	<div class="row express_line">
                    	<div class="form-group col-md-6">
                    	    <label for="" class="col-md-3 control-label form_label">
                    	        采购单号
                    	    </label>
                    	    <div class="col-md-9">
                                <p class="form-control-static"><?php echo $single['id']; ?></p>
                    	    </div>
                    	</div>
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
                    </div>             
                    <?php if( isset($details[1]) ){ ?>
                    <p class="bg-info form-square-title">原料入库</p>
                    <div class="panel panel-default">
                        <table class="table table-striped table-hover table-center" id="goods_table">
                            <thead>
                                <tr>
                                    <th style="width:5%;">#</th>
                                    <th style="width:30%;">原料名称</th>
                                    <th style="width:10%;">计量单位</th>
                                    <th style="width:10%;">计划采购价</th>
                                    <th style="width:15%;">计划采购数量</th>
                                    <th style="width:15%;">实际采购价</th>
                                    <th style="width:15%;">实际采购数量</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach( $details[1] as $k=>$row) { ?>
                                <tr>
                                    <td>
                                        <?php echo ($k+1); ?>
                                        <input type="hidden" name="detail_id[]" value="<?php echo $row['id']; ?>">
                                    </td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['unit']; ?></td>
                                    <td><label>￥</label><?php echo $row['price_plan']; ?></td>
                                    <td><?php echo $row['amount_plan']; ?></td>
                                    <td style="padding:3px;">
                                        <label>￥</label>
                                        <input type="text" class="form-control input-sm text-center price_real" style="width:60px;" name="price_real[]" value="<?php echo $row['price_plan']; ?>">
                                    </td>
                                    <td style="padding:3px;">
                                        <input type="text" class="form-control input-sm text-center amount_real" style="width:60px;" name="amount_real[]" value="<?php echo $row['amount_plan']; ?>">
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } ?>

                    <?php if( isset($details[2]) ){ ?>
                    <p class="bg-info form-square-title">商品入库</p>
                    <div class="panel panel-default">
                        <table class="table table-striped table-hover table-center" id="products_table">
                            <thead>
                                <tr>
                                    <th style="width:5%;">#</th>
                                    <th style="width:30%;">商品名称</th>
                                    <th style="width:10%;">规格</th>
                                    <th style="width:10%;">计划采购价</th>
                                    <th style="width:15%;">计划采购数量</th>
                                    <th style="width:15%;">实际采购价</th>
                                    <th style="width:15%;">实际采购数量</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach( $details[2] as $k=>$row) { ?>
                                <tr>
                                    <td>
                                        <?php echo ($k+1); ?>
                                        <input type="hidden" name="detail_id[]" value="<?php echo $row['id']; ?>">
                                    </td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['spec']; ?></td>
                                    <td><label>￥</label><?php echo $row['price_plan']; ?></td>
                                    <td><?php echo $row['amount_plan']; ?></td>
                                    <td style="padding:3px;">
                                        <label>￥</label>
                                        <input type="text" class="form-control input-sm text-center price_real" style="width:60px;" name="price_real[]" value="<?php echo $row['price_plan']; ?>">
                                    </td>
                                    <td style="padding:3px;">
                                        <input type="text" class="form-control input-sm text-center amount_real" style="width:60px;" name="amount_real[]" value="<?php echo $row['amount_plan']; ?>">
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

<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">
<script src="<?php echo base_url('static/js/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function($) {
    //初始化界面时需要执行
    setTableKeyDown("#products_table", "input[type='text']", 2);
    setTableKeyDown("#goods_table", "input[type='text']", 2);

    $('#purchase_form').bootstrapValidator(validate_rules.purchase).on('success.form.bv', function(e) {
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
                $('#success_alert').empty().text('采购单入库成功！').show();
                window.setTimeout(function(){
                    window.parent.$.fancybox.close('fade');
                    window.parent.location.href = "<?php echo site_url('products/purchase?type=1&status=3'); ?>";
                },2000);
            }
        }, 'json');
    });
});
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
</script>
