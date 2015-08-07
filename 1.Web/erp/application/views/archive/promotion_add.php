<link href="<?php echo base_url('static/css/datetimepicker.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('archive/archive'); ?>">内容管理</a></li>
            <li><a href="<?php echo site_url('archive/promotion'); ?>">促销管理</a></li>
            <li class="active">添加促销活动</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12 form_body">
        <div class="alert alert-success" id="success_alert" role="alert" style="display:none;"></div>
        <div class="alert alert-danger" id="danger_alert" role="alert" style="display:none;"></div>
        <form action="<?php echo site_url('archive/promotion/doAdd'); ?>" method="post" id="promotion_form">
            <div class="form-horizontal">
                <div class="form-group">
                    <label for="" class="col-md-1 control-label form_label" style="width: 130px;">
                        <span><font color="red">*</font></span>
                        促销活动名称
                    </label>
                    <div class="col-md-3">
                        <input type="text" name="name" class="form-control" id="name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-md-1 control-label form_label" style="width: 130px;">
                        <span><font color="red">*</font></span>
                        促销触发条件
                    </label>
                    <div class="col-md-3">
                        <label class="radio-inline">
                            <input type="radio" name="trigger" value="1" checked> 用户下单
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="trigger" value="2"> 用户登录
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="trigger" value="3"> 用户注册
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-md-1 control-label form_label" style="width: 130px;">
                        <span><font color="red">*</font></span>
                        用户参与次数
                    </label>
                    <div class="col-md-3">
                        <input type="text" name="limit_user" class="form-control" id="limit_user" value="1">
                    </div>
                    <div class="col-md-7">
                        <p class="form-control-static">为0则为不限制用户参与活动的次数</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-md-1 control-label form_label" style="width: 130px;">
                        <span><font color="red">*</font></span>
                        促销限制类型
                    </label>
                    <div class="col-md-3">
                        <label class="radio-inline">
                            <input type="radio" name="limit_type" class="limit_type" value="1" checked> 消费金额
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="limit_type" class="limit_type" value="2"> 指定商品
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="limit_type" class="limit_type" value="3"> 加钱换购
                        </label>
                    </div>
                </div>
                <div class="form-group" id="limit_1">
                    <label for="" class="col-md-1 control-label form_label" style="width: 130px;">
                        设置消费金额
                    </label>
                    <div class="col-md-3">
                        <input type="text" name="limit_price" class="form-control" value="0">
                    </div>
                    <div class="col-md-7">
                        <p class="form-control-static" style="font-weight:700;color:red;">注意：用户单笔订单满多少钱才可以享受此优惠活动，0为不限制！</p>
                    </div>
                </div>
                <div class="form-group" id="limit_2" style="display:none;">
                    <label for="" class="col-md-1 control-label form_label" style="width: 130px;">
                        设置指定商品
                    </label>
                    <div class="col-md-3">
                        <input type="text" name="limit_product" class="form-control" placeholder="例如：132,257">
                    </div>
                    <div class="col-md-7">
                        <p class="form-control-static" style="font-weight:700;color:red;">注意：指定活动限购商品，仅需要输入商品ID，多个商品ID使用英文逗号分隔；例如：132,257</p>
                    </div>
                </div>
                <div class="form-group" id="limit_3" style="display:none;">
                    <label for="" class="col-md-1 control-label form_label" style="width: 130px;">
                        加钱换购商品
                    </label>
                    <div class="col-md-3">
                        <input type="text" name="limit_addition" class="form-control" placeholder="例如：132:0.01,257:1">
                    </div>
                    <div class="col-md-7">
                        <p class="form-control-static" style="font-weight:700;color:red;">注意：指定支持加钱换购的商品，仅需要输入商品ID和加购的价格；例如：132:0.01,257:1</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-md-1 control-label form_label" style="width: 130px;">
                        <span><font color="red">*</font></span>
                        促销回馈类型
                    </label>
                    <div class="col-md-3">
                        <label class="radio-inline">
                            <input type="radio" name="give_type" class="give_type" value="1" checked> 现金回馈
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="give_type" class="give_type" value="2"> 商品回馈
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="give_type" class="give_type" value="3"> 积分回馈           
                        </label>
                    </div>
                </div>
                <div class="form-group" id="give_1">
                    <label for="" class="col-md-1 control-label form_label" style="width: 130px;">
                        设置回馈金额
                    </label>
                    <div class="col-md-3">
                        <input type="text" name="give_price" class="form-control" value="0">
                    </div>
                    <div class="col-md-7">
                        <p class="form-control-static" style="font-weight:700;color:red;">注意：满多少减多少？请慎重设置！</p>
                    </div>
                </div>
                <div class="form-group" id="give_2" style="display:none;">
                    <label for="" class="col-md-1 control-label form_label" style="width: 130px;">
                        设置回馈商品
                    </label>
                    <div class="col-md-3">
                        <input type="text" name="give_product" class="form-control" placeholder="例如：1100231,1231123">
                    </div>
                    <div class="col-md-7">
                        <p class="form-control-static" style="font-weight:700;color:red;">注意：指定回馈商品，仅需要输入商品ID，多个商品ID使用英文逗号分隔；例如：132,257</p>
                    </div>
                </div>
                <div class="form-group" id="give_3" style="display:none;">
                    <label for="" class="col-md-1 control-label form_label" style="width: 130px;">
                        设置回馈积分
                    </label>
                    <div class="col-md-3">
                        <input type="text" name="give_score" class="form-control" value="0">
                    </div>
                </div>
                <div class="form-group">
                    <label for="datetime" class="col-md-1 control-label form_label" style="width: 130px;">
                        活动开始日期
                    </label>
                    <div class="col-md-3">
                        <input type="text" name="day_start" class="form-control" id="day_start" readOnly>
                    </div>
                    <div class="col-md-5">
                        <p class="form-control-static">不选择开始日期则为不限</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="datetime" class="col-md-1 control-label form_label" style="width: 130px;">
                        活动结束日期
                    </label>
                    <div class="col-md-3">
                        <input type="text" name="day_end" class="form-control" id="day_end" readOnly>
                    </div>
                    <div class="col-md-5">
                        <p class="form-control-static">不选择结束日期则为不限</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="datetime" class="col-md-1 control-label form_label" style="width: 130px;">
                        <span><font color="red">*</font></span>
                        活动可用网站
                    </label>
                    <div class="col-md-10">
                        <?php if( isset($sites) && !empty($sites) ){ ?>
                        <?php foreach( $sites as $k=>$row ){ ?>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="sites[]" value="<?php echo $k; ?>"><?php echo $row['name']; ?>
                        </label>
                        <?php } ?>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-md-12 form-group form_btn_line">
                    <div class="col-md-2 col-md-offset-4 text-center">
                        <button type="submit" class="btn btn-primary btn-block" id="submit_btn">提 交</button>  
                    </div>
                    <div class="col-md-2 text-center">
                        <a class="btn btn-default btn-block" href="<?php echo site_url('archive/promotion'); ?>">返回列表</a>  
                    </div>
                </div>
            </div>               
        </form>
    </div>
</div>

<script src="<?php echo base_url('static/js/bootstrap-datetimepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function($) {
    $('.limit_type').change(function(){
        var t = parseInt($(this).val());
        for( var i=1; i<=3; i++ ){
            if( i==t ){
                $('#limit_'+i).show();
            } else {
                $('#limit_'+i).hide();
            }
        }
    });

    $('.give_type').change(function(){
        var t = parseInt($(this).val());
        for( var i=1; i<=3; i++ ){
            if( i==t ){
                $('#give_'+i).show();
            } else {
                $('#give_'+i).hide();
            }
        }
    });

    $('#day_start,#day_end').datetimepicker({
        language:  'zh',
        format: 'yyyy-mm-dd',
        autoclose: 1,
        todayHighlight: 0,
        startView: 2,
        minView: 2,
        maxView: 3,
    });

    $('#promotion_form').bootstrapValidator(validate_rules.promotion).on('success.form.bv', function(e) {
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
                $('#success_alert').empty().text('添加成功！').show();
                window.setTimeout(function(){
                    window.location.href = "<?php echo site_url('archive/promotion'); ?>";
                },2000);
            }
        }, 'json');
    });
});

</script>
