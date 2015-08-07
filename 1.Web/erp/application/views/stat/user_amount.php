<link href="<?php echo base_url('static/css/datetimepicker.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('products/good'); ?>"><span class="glyphicon glyphicon-home"></span> 商品管理</a></li>
            <li><a href="">数据统计</a></li>
            <li><a href="">订单量统计</a></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('stat/user/amount'); ?>" method="get" id="search_form">
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
            <?php if( $results ){ ?>
            <div class="panel-body">
                <div id="highchart_container" style="width:100%;height:400px;"></div>
            </div>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th width="10%" rowspan="2">日期</th>
                        <?php foreach( $os_types as $os=>$type ){ ?>
                        <th width="18%" colspan="2"><?php echo $type; ?></th>
                        <?php } ?>
                    </tr>
                    <tr>
                        <?php foreach( $os_types as $os=>$type ){ ?>
                        <th width="9%">增量</th>
                        <th width="9%">总量</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $k=>$row) {?>
                    <tr>
                        <td><?php echo $k; ?></td>
                        <?php foreach( $os_types as $os=>$type ){ ?>
                        <td><?php echo $row[$os]['change']; ?></td>
                        <td><?php echo $row[$os]['total']; ?></td>
                        <?php } ?>
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
    });
    
    $('#end').datetimepicker({
        autoclose: 1,
        format: "yyyy-mm-dd",
        language: "zh",
        maxView: 3,
        minView: 2,
    });
    
    var options = {
        chart: {
            type: 'line'
        },
        title: {
            text: "用户增长量统计"
        },
        xAxis: {
            categories: <?php echo json_encode($xAxis); ?>,
        },
        yAxis: {
            title: {
                text: '正常用户数(个)'
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