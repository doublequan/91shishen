<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                新建财务单
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert"></div>

            <form action="<?php echo site_url('products/purchase_finance/doAdd'); ?>" method="post" id="purchase_finance_form">
                <input type="hidden" name="purchase_id" value="<?php echo $purchase['id']; ?>">
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
                                <input type="text" name="purchase_id" class="form-control" readOnly value="<?php echo $purchase['id']; ?>">
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="col-sm-4 control-label form_label">
                                财务单编号
                            </label>
                            <div class="col-sm-8 form_input">
                                <input type="text" class="form-control" readOnly value="<?php echo str_replace('PUR', 'FIN', $purchase['id']); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="col-sm-4 control-label form_label">
                                采购商品金额(元)
                            </label>
                            <div class="col-sm-8 form_input">
                                <input type="text" name="money_buy" class="form-control" readOnly value="<?php echo $money_buy; ?>">
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="money_apply" class="col-sm-4 control-label form_label">
                                <span><font color="red">*</font></span>
                                申请金额(元)
                            </label>
                            <div class="col-sm-8 form_input">
                                <input type="text" name="money_apply" class="form-control" id="money_apply" value="<?php echo $money_buy; ?>">
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
                                    <input type="radio" name="pay_type" class="pay_type" value="1" checked> 现金
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="pay_type" class="pay_type" value="2"> 支付宝
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="pay_type" class="pay_type" value="3"> 银行转账
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="pay_type" class="pay_type" value="4"> 支票
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6 alipay" style="display:none;">
                            <label for="pay_alipay" class="col-sm-4 control-label form_label">
                                支付宝账号
                            </label>
                            <div class="col-sm-8 form_input">
                                <input type="text" name="pay_alipay" class="form-control" id="pay_alipay">
                            </div>
                        </div>
                        <div class="form-group col-sm-6 bank" style="display:none;">
                            <label for="pay_bank" class="col-sm-4 control-label form_label">
                                开户行
                            </label>
                            <div class="col-sm-8 form_input">
                                <input type="text" name="pay_bank" class="form-control" id="pay_bank">
                            </div>
                        </div>
                        <div class="form-group col-sm-6 bank" style="display:none;">
                            <label for="pay_bankno" class="col-sm-4 control-label form_label">
                                银行账号
                            </label>
                            <div class="col-sm-8 form_input">
                                <input type="text" name="pay_bankno" class="form-control" id="pay_bankno">
                            </div>
                        </div>
                    </div>
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
    window.setTimeout(function(){ 
        $('#success_alert, #danger_alert').hide(); 
    },4000);

    $('.alert').hide();

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
                    $('#success_alert').empty().text('添加成功！').show();
                    window.setTimeout(function(){
                        window.parent.location.reload();
                        window.parent.$.fancybox.close('fade');
                    },2000);
                }
            }, 'json');
        });
});

</script>
