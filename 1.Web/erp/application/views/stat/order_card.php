<link href="<?php echo base_url('static/css/datetimepicker.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="">数据统计</a></li>
            <li><a href="">销售数据</a></li>
            <li><a href="">储值卡会员订单量统计</a></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('stat/order/card'); ?>" method="get" id="search_form">
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
                </form>
            </div>
            <?php if( $results ){ ?>
            <div class="panel-body">
                <div id="highchart_container" style="width:100%;height:400px;"></div>
            </div>
            <table class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th width="50%">日期</th>
                        <th width="50%">销量</th>
                    </tr>
                </thead>
                <tbody>
                    <?php krsort($results); ?>
                    <?php foreach ($results as $k=>$v) {?>
                    <tr>
                        <td><?php echo $k; ?></td>
                        <td><?php echo $v; ?></td>
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
    
    var options = {
        chart: {
            type: 'spline'
        },
        title: {
            text: "<b><?php echo $site['name']; ?></b>：<?php echo $params['start'].' 至 '.$params['end']; ?> 储值卡会员订单量统计"
        },
        xAxis: {
            categories: <?php echo json_encode($dates); ?>,
        },
        yAxis: {
            title: {
                text: '修改后价格 (元)'
            },
        },
        tooltip:{
            xDateFormat: '%Y年%m月%d日',
            pointFormat: '订单量：{point.y} 单'
        },
        series: [{
            name: "<?php echo $site['name']; ?>",
            data: <?php echo json_encode($chart_data); ?>,
        }]
    };

    $('#highchart_container').highcharts(options);
});
</script>