<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                财务单结算
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert" style="display:none;"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert" style="display:none;"></div>

            <form action="<?php echo site_url('products/purchase_finance/doCheckout'); ?>" method="post" id="single_finance_form">
                <input type="hidden" name="purchase_id" value="<?php echo $purchase['id']; ?>">
                <input type="hidden" name="finance_id" value="<?php echo $single['id']; ?>">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="bg-info form-square-title">
                                基本信息
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="col-sm-4 control-label form_label">
                                采购单编号
                            </label>
                            <div class="col-sm-8 form_input">
                                <input type="text" name="single_id" class="form-control" readOnly value="<?php echo $single['id']; ?>">
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="col-sm-4 control-label form_label">
                                财务单编号
                            </label>
                            <div class="col-sm-8 form_input">
                                <input type="text" class="form-control" readOnly value="<?php echo str_replace('PUR', 'FIN', $single['id']); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="col-sm-4 control-label form_label">
                                计划商品金额(元)
                            </label>
                            <div class="col-sm-8 form_input">
                                <p class="form-control-static"><?php echo $single['money_buy']; ?></p>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="money_apply" class="col-sm-4 control-label form_label">
                                采购申请金额(元)
                            </label>
                            <div class="col-sm-8 form_input">
                                <p class="form-control-static"><?php echo $single['money_apply']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="col-sm-4 control-label form_label">
                                实际商品金额(元)
                            </label>
                            <div class="col-sm-8 form_input">
                                <p class="form-control-static"><?php echo $money_real; ?></p>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="col-sm-4 control-label form_label">
                                采购批准金额(元)
                            </label>
                            <div class="col-sm-8 form_input">
                                <p class="form-control-static"><?php echo $single['money_approve']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p class="bg-info form-square-title">
                                支付信息
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="col-sm-4 control-label form_label">
                                支付方式
                            </label>
                            <div class="col-sm-8 form_input">
                                <label class="radio-inline">
                                    <input type="radio" name="pay_type" class="pay_type" value="1"<?php echo $single['pay_type']==1 ? ' checked' : ''; ?>> 现金
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="pay_type" class="pay_type" value="2"<?php echo $single['pay_type']==2 ? ' checked' : ''; ?>> 支付宝
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="pay_type" class="pay_type" value="3"<?php echo $single['pay_type']==3 ? ' checked' : ''; ?>> 银行转账
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="pay_type" class="pay_type" value="4"<?php echo $single['pay_type']==4 ? ' checked' : ''; ?>> 支票
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="col-sm-4 control-label form_label">
                                <span><font color="red">*</font></span>
                                实付金额(元)
                            </label>
                            <div class="col-sm-8 form_input">
                                <input type="text" name="money_pay" class="form-control" id="money_pay" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6 alipay"<?php echo $single['pay_type']!=2 ? ' style="display:none;"' : ''; ?>>
                            <label for="pay_alipay" class="col-sm-4 control-label form_label">
                                支付宝账号
                            </label>
                            <div class="col-sm-8 form_input">
                                <input type="text" name="pay_alipay" class="form-control" id="pay_alipay">
                            </div>
                        </div>
                        <div class="form-group col-sm-6 bank"<?php echo $single['pay_type']!=3 ? ' style="display:none;"' : ''; ?>>
                            <label for="pay_bank" class="col-sm-4 control-label form_label">
                                开户行
                            </label>
                            <div class="col-sm-8 form_input">
                                <input type="text" name="pay_bank" class="form-control" id="pay_bank">
                            </div>
                        </div>
                        <div class="form-group col-sm-6 bank"<?php echo $single['pay_type']!=3 ? ' style="display:none;"' : ''; ?>>
                            <label for="pay_bankno" class="col-sm-4 control-label form_label">
                                银行账号
                            </label>
                            <div class="col-sm-8 form_input">
                                <input type="text" name="pay_bankno" class="form-control" id="pay_bankno">
                            </div>
                        </div>
                    </div>
                    <?php if( isset($details[1]) ){ ?>
                    <p class="bg-info form-square-title">已采购原料</p>
                    <div class="panel panel-default">
                        <table class="table table-striped table-hover table-center">
                            <thead>
                                <tr>
                                    <th style="width:5%;">#</th>
                                    <th style="width:10%;">原料ID</th>
                                    <th style="width:25%;">原料名称</th>
                                    <th style="width:15%;">计划采购价</th>
                                    <th style="width:15%;">计划采购数量</th>
                                    <th style="width:15%;">实际采购价</th>
                                    <th style="width:15%;">实际采购数量</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach( $details[1] as $k=>$row) { ?>
                                <tr>
                                    <td><?php echo ($k+1); ?></td>
                                    <td><?php echo $row['good_id']; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><label>￥</label><?php echo $row['price_plan']; ?></td>
                                    <td><?php echo $row['amount_plan']; ?></td>
                                    <td><label>￥</label><?php echo $row['price_real']; ?></td>
                                    <td><?php echo $row['amount_real']; ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } ?>

                    <?php if( isset($details[2]) ){ ?>
                    <p class="bg-info form-square-title">已采购商品</p>
                    <div class="panel panel-default">
                        <table class="table table-striped table-hover table-center">
                            <thead>
                                <tr>
                                    <th style="width:5%;">#</th>
                                    <th style="width:10%;">商品ID</th>
                                    <th style="width:25%;">商品名称</th>
                                    <th style="width:15%;">计划采购价</th>
                                    <th style="width:15%;">计划采购数量</th>
                                    <th style="width:15%;">实际采购价</th>
                                    <th style="width:15%;">实际采购数量</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach( $details[2] as $k=>$row) { ?>
                                <tr>
                                    <td><?php echo ($k+1); ?></td>
                                    <td><?php echo $row['product_id']; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><label>￥</label><?php echo $row['price_plan']; ?></td>
                                    <td><?php echo $row['amount_plan']; ?></td>
                                    <td><label>￥</label><?php echo $row['price_real']; ?></td>
                                    <td><?php echo $row['amount_real']; ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } ?>
                    <div class="form-group form_btn_line">
                        <div class="col-sm-2 col-sm-offset-5 text-center">
                            <input type="submit" class="btn btn-primary btn-block" value="提交财务单"> 
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
    $('.pay_type').change(function(){
        var pay_type = parseInt($(this).val());
        if( pay_type==1 || pay_type==4 ){
            $('.alipay').hide();
            $('.bank').hide();
        } else if( pay_type==2 ) {
            $('.alipay').show();
            $('.bank').hide();
        } else if( pay_type==3 ) {
            $('.alipay').hide();
            $('.bank').show();
        }
    });
    $('#single_finance_form')
    .bootstrapValidator({
        message: 'This value is not valid',
        fields: {
            money_pay: {
                message: '请输入实付金额！',
                validators: {
                    notEmpty: {
                        message: '请输入实付金额！'
                    }
                }
            },
        }
    })
    .on('success.form.bv', function(e) {
        e.preventDefault();
        var $form = $(e.target);
        var bv = $form.data('bootstrapValidator');

        $('.alert').hide();
        $.post($form.attr('action'), $form.serialize(), function(rst_json) {
            if(rst_json.err_no != 0){
                $('#danger_alert').empty().text(rst_json.err_msg).show();
                return;
            }
            else{
                $('#success_alert').empty().text('结算成功！').show();
                window.setTimeout(function(){
                    window.parent.$.fancybox.close('fade');
                    window.parent.location.href = "<?php echo site_url('products/purchase?type=1&status=4'); ?>";
                },2000);
            }
        }, 'json');
    });
});
</script>