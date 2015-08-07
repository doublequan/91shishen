<link href="<?php echo base_url('static/css/datetimepicker.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="">数据统计</a></li>
            <li><a href="">销售数据</a></li>
            <li><a href="">订单量统计</a></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('stat/order/amount'); ?>" method="get" id="search_form">
                    <input type="hidden" name="act" id="act" value="">
                    <div class="form-group">
                        <p class="form-control-static input-sm">&nbsp;&nbsp;所属分站：</p>
                    </div>
                    <div class="form-group">
                        <select name="site_id" id="site_id" class="form-control">
                            <?php if( $sites ) { ?>
                            <?php foreach( $sites as $k=>$row ) { ?>
                            <option value="<?php echo $k; ?>"<?php echo $k==$params['site_id'] ? ' selected' : ''; ?>><?php echo $row['name']; ?></option>
                            <?php } ?>
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
                    <div class="form-group" style="width:100px;margin-left:5px;">
                         <input type="button" class="btn btn-info btn-sm btn-block" id="export" value="导出Excel">
                    </div>
                </form>
            </div>
            <?php if( $results ){ ?>
            <div class="panel-body">
                <div id="highchart_container" style="width:100%;height:400px;"></div>
            </div>
            <table class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th width="20%">日期</th>
                        <th width="20%">总订单量</th>
                        <th width="20%">首次下单量</th>
                        <th width="20%">储值卡首次下单量</th>
                        <th width="20%">门店订单量</th>
                    </tr>
                </thead>
                <tbody>
                    <?php krsort($results); ?>
                    <?php foreach ($results as $k=>$row) {?>
                    <tr>
                        <td><?php echo $k; ?></td>
                        <td><?php echo $row['total']; ?></td>
                        <td><?php echo $row['first']; ?></td>
                        <td><?php echo $row['card']; ?></td>
                        <td><?php echo $row['store']; ?></td>
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
<script src="<?php echo base_url('static/js/highcharts.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#search_form select').change(function(event) {
        $('#search_form').submit();
    });

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
            if(start > end){
                $(this).val('');
                return alert("开始日期需小于结束日期！");
            }
        }
    });

    $('#export').click(function(){
        $('#act').val('export');
        $('#search_form').submit();
        $('#act').val('');
    });

    var options = {
        chart: {
            type: 'line'
        },
        title: {
            text: "<b><?php echo $site['name']; ?></b>：<?php echo $params['start'].' 至 '.$params['end']; ?> 订单量统计"
        },
        xAxis: {
            categories: <?php echo json_encode($dates); ?>,
        },
        yAxis: {
            title: {
                text: '订单数(个)'
            },
        },
        tooltip: {
            enabled: false,
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
        series: <?php echo json_encode($series); ?>,
    };
    $('#highchart_container').highcharts(options);
});
</script>