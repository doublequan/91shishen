<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/datetimepicker.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('products/good'); ?>"><span class="glyphicon glyphicon-home"></span> 商品管理</a></li>
            <li><a href="<?php echo site_url('products/good'); ?>">原料商品</a></li>
            <li class="active">原料价格列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('products/good_price'); ?>" method="get" id="search_form">
                    <input type="hidden" name="good_id" id="good_id" value="<?php echo $params['good_id']; ?>">
                    <div class="form-group">
                        <p class="form-control-static input-sm">
                            原料名称：
                            <?php echo empty($single['name'])?'无商品信息，请查询其他商品！':'<span class="font-bold">'.$single['name'].'</span>'; ?>
                        </p>
                    </div>

                    <div class="form-group">
                        <p class="form-control-static input-sm">&nbsp;&nbsp;开始日期：</p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control input-sm" name="start" id="start" 
                            value="<?php echo $params['start']; ?>" >
                    </div>

                    <div class="form-group">
                        <p class="form-control-static input-sm">&nbsp;&nbsp;结束日期：</p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control input-sm" name="end" id="end" 
                            value="<?php echo $params['end']; ?>" >
                    </div>
                    <div class="form-group" style="width:80px;">
                        <input type="submit" class="btn btn-primary btn-sm btn-block" value="查 询">
                    </div>

                    <a class="btn btn-primary btn-sm" id="add_form_btn" href="#add_form_page" kesrc="#add_form_page">修改原料价格</a>
                </form>
            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <div class="panel-body">
                <div id="highchart_container" style="width:100%;height:400px;"></div>
            </div>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th style="width:30px;">
                            #
                        </th>
                        <th style="width:70px;">
                            原料编号
                        </th>
                        <th style="width:160px;">
                            原料名称
                        </th>
                        <th style="width:80px;">
                            修改后价格
                        </th>
                        <th style="width:80px;">
                            修改时间
                        </th>
                        <th style="width:80px;">
                            修改员工
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $idx = 0;
                        foreach ($results as $single_info) {
                    ?>
                    <tr>
                        <td>
                            <?php echo ++$idx; ?>
                        </td>
                        <td>
                            <?php echo $single['id']; ?>
                        </td>
                        <td>
                            <?php echo $single['name']; ?>
                        </td>
                        <td>
                            ￥<?php echo $single_info['price']; ?>
                        </td>
                        <td>
                            <?php echo date('Y-m-d H:i:s', $single_info['create_time']); ?>
                        </td>
                        <td>
                            <?php echo $single_info['create_name']; ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php }else{ ?>
            <div class="alert alert-warning col-md-12 text-center" role="alert">查询结果为空！</div>
            <?php } ?>
        </div>
    </div>
</div>

<div id="add_form_page" style="height:300px;width:600px;">
    <div class="col-md-12 bg-primary" style="margin-bottom:20px;">
        <h4>
            <span class="glyphicon glyphicon-circle-arrow-right"></span>
            修改原料价格
        </h4>
    </div>
    <form action="<?php echo site_url('products/good_price/doAdd'); ?>" method="post" id="price_form" class="form-horizontal">
        <input type="hidden" name="good_id" value="<?php echo $params['good_id']; ?>">
        <div class="alert alert-success" id="success_alert" role="alert" style="display:none;"></div>
        <div class="alert alert-danger" id="danger_alert" role="alert" style="display:none;"></div>
        <div class="form-group" style="margin-left:0;margin-right:0;">
            <label for="name" class="col-sm-3 control-label form_label">
                当前价格                
            </label>
            <div class="col-sm-7">
                <p class="form-control-static"><?php echo $single['price']; ?></p>
            </div>
        </div>
        <div class="form-group" style="margin-left:0;margin-right:0;">
            <label for="price" class="col-sm-3 control-label form_label">
                <span><font color="red">*</font></span>
                修改后价格
            </label>
            <div class="col-sm-7 form_input">
                <input type="text" name="price" class="form-control" id="price">
            </div>
        </div>

        <div class="form-group form_btn_line" style="margin-left:0;margin-right:0;">
            <div class="col-sm-4 col-sm-offset-4 text-center">
                <input type="submit" class="btn btn-primary btn-block" value="提 交"> 
            </div>
        </div>
    </form>
</div>

<script src="<?php echo base_url('static/js/jquery.fancybox.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/bootstrap-datetimepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/highcharts.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#start').datetimepicker({
        autoclose: 1,
        format: "yyyy-mm-dd",
        language: "zh",
        maxView: 3,
        minView: 2,
        startView: 2,
    });
    
    $('#end').datetimepicker({
        autoclose: 1,
        format: "yyyy-mm-dd",
        language: "zh",
        maxView: 3,
        minView: 2,
        startView: 2,
    });

    $("#start, #end").change(function(){
        if($("#start").val() && $("#end").val()){
            var start = parseInt(new Date($("#start").val()).getTime());
            var end = parseInt(new Date($("#end").val()).getTime());
            if(start >= end){
                $(this).val('');
                return alert("开始日期需小于结束日期！");
            }
        }
    });
    
    var options = {
        chart: {
            type: 'spline'
        },
        title: {
            text: "原料名称：<b><?php echo $single['name']; ?></b>"
        },
        xAxis: {
            type: 'datetime',
            dateTimeLabelFormats: {
                day: '%b%e日'
            },
        },
        yAxis: {
            title: {
                text: '修改后价格 (元)'
            },
        },
        tooltip:{
            xDateFormat: '%Y年%m月%d日 %H:%M',
            pointFormat: '修改后价格：{point.y:.2f} 元'
        },
        series: [{
            name: "<?php echo $single['name']; ?>",
            data: <?php echo json_encode($chart_data); ?>,
        }]
    };

    $('#highchart_container').highcharts(options);

    $("a#add_form_btn").click(function() {
        var good_id = $('#good_id').val();
        if(good_id){
            $("a#add_form_btn").fancybox({
                'hideOnContentClick': true,
                'padding':0,
                'afterClose': function(){
                    window.parent.location.reload();
                },
            });
        }
    });

    $('#price_form')
        .bootstrapValidator(validate_rules.vip_price)
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
                    },1000);
                }
            }, 'json');
        });
    
});
</script>