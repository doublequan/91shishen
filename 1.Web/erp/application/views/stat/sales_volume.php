<link href="<?php echo base_url('static/css/datetimepicker.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('stat/user/user_total'); ?>">数据统计</a></li>
            <li><a href="<?php echo site_url('stat/order/sales_volume'); ?>">销售数据</a></li>
            <li class="active">销售额</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('stat/order/sales_volume'); ?>" method="get" id="search_form">
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

                    <div class="form-group">
                        <p class="form-control-static input-sm">&nbsp;&nbsp;查看维度：</p>
                    </div>
                    <div class="form-group">
                        <select name="dim" class="form-control input-sm" id="dim_select">
                            <option value="sites" <?php echo ($params['dim'] == 'sites')?'selected':''; ?>>网站</option>
                            <option value="stores" <?php echo ($params['dim'] == 'stores')?'selected':''; ?>>门店</option>
                        </select>
                    </div>
                    <div class="form-group" style="width:120px;margin-left:20px;">
                        <input type="submit" class="btn btn-primary btn-sm btn-block" value="查 询">
                    </div>
                </form>
            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <div class="panel-body">
                <div id="highchart_container" style="width:100%;height:600px;"></div>
            </div>
            <?php }else{ ?>
            <div class="alert alert-warning col-md-12 text-center" role="alert">查询结果为空！</div>
            <?php } ?>
        </div>
    </div>
</div>

<script src="<?php echo base_url('static/js/bootstrap-datetimepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/highstock.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {

    $('#dim_select').change(function(event) {
        $('#search_form').submit();
    });

    $('#start').datetimepicker({
        autoclose: 1,
        format: "yyyy-mm-dd",
        language: "zh",
        maxView: 3,
        minView: 2,
        startView: 2,
        todayHighlight: 1
    });
    
    $('#end').datetimepicker({
        autoclose: 1,
        format: "yyyy-mm-dd",
        language: "zh",
        maxView: 3,
        minView: 2,
        startView: 2,
        todayHighlight: 1
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
        title: {
            text: '订单总销售额 (已确认订单)',
        },
        xAxis: {
            categories: <?php echo json_encode($dates); ?>,
        },
        yAxis: {
            title: {
                text: ''
            },
        },
        tooltip: {
            xDateFormat: '%Y年%m月%d日',
        },
        legend: {
            borderWidth: 0
        },
        series: <?php echo json_encode($chart_data); ?>,
    };

    $('#highchart_container').highcharts(options);

});
</script>