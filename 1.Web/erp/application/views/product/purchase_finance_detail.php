<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                采购财务单详情
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
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
                        <div class="form-group col-sm-6">
                            <label class="col-sm-4 control-label form_label">
                                实际支付金额(元)
                            </label>
                            <div class="col-sm-8 form_input">
                                <p class="form-control-static"><?php echo $single['money_pay']; ?></p>
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
                                <p class="form-control-static"><?php echo isset($checkoutMap[$single['pay_type']]) ? $checkoutMap[$single['pay_type']] : '未知结算方式'; ?></p>
                            </div>
                        </div>
                        <?php if( $single['pay_type']==2 ){ ?>
                        <div class="form-group col-sm-6">
                            <label class="col-sm-4 control-label form_label">
                                支付宝账号
                            </label>
                            <div class="col-sm-8 form_input">
                                <p class="form-control-static"><?php echo $single['pay_alipay']; ?></p>
                            </div>
                        </div>
                        <?php } elseif( $single['pay_type']==3 ){ ?>
                        <div class="form-group col-sm-6">
                            <label class="col-sm-4 control-label form_label">
                                银行账号
                            </label>
                            <div class="col-sm-8 form_input">
                                <p class="form-control-static">
                                    <?php echo $single['pay_bank']; ?> -
                                    <?php echo $single['pay_bankno']; ?>
                                </p>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div> 
        </div>
    </div>
</div>