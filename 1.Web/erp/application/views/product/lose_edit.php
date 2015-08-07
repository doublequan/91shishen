<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                损耗信息
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert"></div>

            <form action="<?php echo site_url('products/loss/doAdd'); ?>" method="post" id="loss_form">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="type_id" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            损耗类型
                        </label>
                        <div class="col-sm-9 form_input">
                            <select name="type_id" class="form-control" id="type_id">
                                <option value="">请选择损耗类型</option>
                            <?php foreach ($types as $value) { ?>
                                <option value="<?php echo $value['id']; ?>"><?php echo trim($value['name']); ?></option>
                            <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="des" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            损耗说明
                        </label>
                        <div class="col-sm-9 form_input">
                            <textarea class="form-control" name="des" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="t" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            产品类型
                        </label>
                        <div class="col-sm-9 form_input">
                            <select name="t" class="form-control" id="t">
                                <option value="1">原料</option>
                                <option value="2">商品</option>
                            </select>
                            <input type="hidden" name="id" id="id">
                        </div>
                    </div>

                    <div class="form-group good_line">
                        <label for="" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            原料名称
                        </label>
                        <div class="col-sm-7 form_input">
                            <input type="text" name="common_name[]" class="form-control" id="good_name" readonly>
                            <input type="hidden" name="good_id" id="good_id">
                        </div>
                        <div class="col-sm-2">
                            <a href="#good_select_dialog" class="btn btn-default btn-block" id="select_good_btn" kesrc="#good_select_dialog">选择原料</a>
                        </div>
                    </div>
                    <div class="form-group product_line">
                        <label for="" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            商品名称
                        </label>
                        <div class="col-sm-7 form_input">
                            <input type="text" name="common_name[]" class="form-control" id="product_title" readonly>
                            <input type="hidden" name="product_id" id="product_id">
                        </div>
                        <div class="col-sm-2">
                            <a href="#product_select_dialog" class="btn btn-default btn-block" id="select_product_btn" kesrc="#product_select_dialog">选择商品</a>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="amount_total" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            总量
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="amount_total" class="form-control" id="amount_total">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="amount_loss" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            损失量
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="amount_loss" class="form-control" id="amount_loss">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="amount_left" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            剩余量
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="amount_left" class="form-control" id="amount_left">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="price" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            单品采购价(元)
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="price" class="form-control" id="price">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="respon_eid" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            责任员工
                        </label>
                        <div class="col-sm-7 form_input">
                            <input type="text" name="employee_name" class="form-control" id="employee_name" readOnly>
                            <input type="hidden" name="respon_eid" class="form-control" id="employee_id">
                        </div>
                        <div class="col-sm-2 form_input">
                            <a href="#employee_select_dialog" kesrc="#employee_select_dialog" class="btn btn-default btn-block" id="add_employee_btn">选择员工</a>
                        </div>
                    </div>

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

<iframe id="good_select_dialog" class="iframe_dialog" style="height:550px;"
    src=""></iframe>
<iframe id="product_select_dialog" class="iframe_dialog" style="height:550px;"
    src=""></iframe>
<iframe id="employee_select_dialog" class="iframe_dialog" style="height:550px;"
    src=""></iframe>

<script src="<?php echo base_url('static/js/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/jquery.fancybox.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function($) {
    window.setTimeout(function(){ 
        $('#success_alert, #danger_alert').hide(); 
    },4000);

    $('#t').change(function(event) {
        if($(this).val() == 1){
            $('.good_line').show().find('input').val('');
            $('.product_line').hide().find('input').val('');
        }
        else if($(this).val() == 2){
            $('.product_line').show().find('input').val('');
            $('.good_line').hide().find('input').val('');
        }
    });
    $('#select_good_btn').click(function(){
        $('iframe#good_select_dialog').attr('src', "<?php echo site_url('products/good/single_select_dialog'); ?>");
        $("#select_good_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            'afterClose': function(){
                $('#loss_form')
                    .bootstrapValidator('updateStatus', 'common_name[]', 'NOT_VALIDATED')
                    .bootstrapValidator('validateField', 'common_name[]');
            },
        });
    });

    $('#select_product_btn').click(function(){
        $('iframe#product_select_dialog').attr('src', "<?php echo site_url('products/product/single_select_dialog'); ?>");
        $("#select_product_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            'afterClose': function(){
                $('#loss_form')
                    .bootstrapValidator('updateStatus', 'common_name[]', 'NOT_VALIDATED')
                    .bootstrapValidator('validateField', 'common_name[]');
            },
        });
    });

    $('a#add_employee_btn').click(function(){
        $('iframe#employee_select_dialog').attr('src', "<?php echo site_url('employee/employee/select_dialog'); ?>");
        $("a#add_employee_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            'afterClose': function(){
                $('#loss_form').bootstrapValidator('updateStatus', 'employee_name', 'NOT_VALIDATED')
                                .bootstrapValidator('validateField', 'employee_name');
            },
        });
    });

    $('.alert, .product_line').hide();

    $('#loss_form')
        .bootstrapValidator(validate_rules.loss)
        .on('success.form.bv', function(e) {
            e.preventDefault();

            var good_id = $('#good_id').val();
            var product_id = $('#product_id').val();

            if(good_id && !product_id){
                $('#id').val(good_id);
            }
            else if(!good_id && product_id){
                $('#id').val(product_id);
            }
            else{
                $('#danger_alert').empty().text('系统错误，请刷新页面重试！').show();
                return false;
            }

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
