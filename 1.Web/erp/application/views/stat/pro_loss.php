<link href="<?php echo base_url('static/css/datetimepicker.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('stat/user'); ?>"><span class="glyphicon glyphicon-home"></span>统计信息</a></li>
            <li><a href="<?php echo site_url('stat/user'); ?>">用户统计</a></li>
            <li class="active">新增用户</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('stat/user/user_new'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">所属网站：</p>
                    </div>
                    <div class="form-group">
                        <select name="site_id" class="form-control input-sm" id="site_id">
                        <?php foreach ($sites as $site) { ?>
                            <option value="<?php echo $site['id']; ?>" <?php echo $site['id']==$params['site_id']?'selected':''; ?>><?php echo trim($site['name']); ?></option>
                        <?php } ?>
                        </select>
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
                        <th style="width:100px;">
                            网站名称
                        </th>
                        <th style="width:100px;">
                            新增用户
                        </th>
                        <th style="width:100px;">
                            统计时间
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
                            <?php echo $sites[$single_info['site_id']]['name']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['count']; ?>
                        </td>
                        <td>
                            <?php echo date('Y-m-d H:i', $single_info['create_time']); ?>
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

<script src="<?php echo base_url('static/js/bootstrap-datetimepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/highstock.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {

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
        chart: {
            type: 'line'
        },
        credits:{
            enabled: false
        },
        title: {
            text: "新增用户统计：<b><?php echo $sites[$single_info['site_id']]['name']; ?></b>"
        },
        xAxis: {
            dateTimeLabelFormats: {
                hour: '%H:%M',
            },
        },
        yAxis: {
            align: 'left',
        },
        rangeSelector:{
            inputEnabled: false,
            buttons: [{
                type: 'day',
                count: 1,
                text: '1天'
            },{
                type: 'day',
                count: 7,
                text: '1周'
            },{
                type: 'day',
                count: 30,
                text: '1月'
            },{
                type: 'all',
                text: '全部'
            }]
        },
        tooltip:{
            xDateFormat: '%Y年%m月%d日 %H:%M',
            pointFormat: '新增用户：{point.y} '
        },
        series: [{
            name: "",
            data: <?php echo json_encode($chart_data); ?>,
            marker : {
                enabled : true,
                radius : 4
            },
        }]
    };

    $('#highchart_container').highcharts('StockChart', options);

});
</script>