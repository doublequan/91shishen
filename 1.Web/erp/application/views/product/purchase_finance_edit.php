<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                编辑财务单信息
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert"></div>

            <form action="<?php echo site_url('products/purchase_finance/doEdit'); ?>" method="post" id="purchase_finance_form">
                <input type="hidden" name="id" value="<?php echo $single['id']; ?>">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="purchase_id" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            采购单编号
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="purchase_id" class="form-control" id="purchase_id" readOnly
                                value="<?php echo $single['purchase_id']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="money_apply" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            申请金额(元)
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="money_apply" class="form-control" id="money_apply"
                                value="<?php echo $single['money_apply']; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="money_apply" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            支付方式
                        </label>
                        <div class="col-sm-9 form_input">
                            <p class="form-control-static"><?php echo $checkoutMap[$single['pay_type']];?></p>
                            <input type="hidden" name="pay_type" value="<?php echo $single['pay_type']; ?>">
                        </div>
                    </div>

                    <?php if($single['pay_type'] == 0){ ?>
                    <div class="form-group">
                        <label for="pay_alipay" class="col-sm-2 control-label form_label">
                            付款支付宝账号
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="pay_alipay" class="form-control" id="pay_alipay"
                                value="<?php echo $single['pay_alipay']; ?>">
                        </div>
                    </div>
                    <?php } ?>

                    <?php if($single['pay_type'] == 1){ ?>
                    <div class="form-group">
                        <label for="pay_bank" class="col-sm-2 control-label form_label">
                            付款银行开户行
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="pay_bank" class="form-control" id="pay_bank"
                                value="<?php echo $single['pay_bank']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pay_bankno" class="col-sm-2 control-label form_label">
                            付款银行账号
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="pay_bankno" class="form-control" id="pay_bankno"
                                value="<?php echo $single['pay_bankno']; ?>">
                        </div>
                    </div>
                    <?php } ?>

                    <?php if($single['pay_type'] == 3){ ?>
                    <div class="form-group">
                        <label for="pay_checkno" class="col-sm-2 control-label form_label">
                            支票号码
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="pay_checkno" class="form-control" id="pay_checkno"
                                value="<?php echo $single['pay_checkno']; ?>">
                        </div>
                    </div>
                    <?php } ?>

                    <div class="form-group form_btn_line">
                        <div class="col-sm-2 col-sm-offset-5 text-center">
                            <input type="submit" class="btn btn-primary btn-block" value="提 交"> 
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
    window.setTimeout(function(){ 
        $('#success_alert, #danger_alert').hide(); 
    },4000);

    $('.alert').hide();

    $('#purchase_finance_form')
        .bootstrapValidator(validate_rules.purchase_finance)
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
                    $('#success_alert').empty().text('修改成功！').show();
                    window.setTimeout(function(){
                        window.parent.location.reload();
                        window.parent.$.fancybox.close('fade');
                    },2000);
                }
            }, 'json');
        });
});

</script>
