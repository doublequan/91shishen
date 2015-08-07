<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('stat/user/user_total'); ?>">数据统计</a></li>
            <li><a href="<?php echo site_url('stat/user/user_total'); ?>">用户数据</a></li>
            <li class="active">用户设备统计</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <?php 
                if(!empty($results)){ 
            ?>
            <div class="panel-body">
                <div id="highchart_container" style="width:100%;height:450px;"></div>
            </div>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th style="width:30px;">
                            #
                        </th>
                        <th style="width:100px;">
                            设备分类
                        </th>
                        <th style="width:100px;">
                            数量
                        </th>
                        <th style="width:100px;">
                            比例
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
                            <?php echo $single_info['name']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['number']; ?>
                        </td>
                        <td>
                            <?php echo sprintf('%.2f', $single_info['percent']*100).'%'; ?>
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

<script src="<?php echo base_url('static/js/highcharts.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/highcharts-3d.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#search_form select').change(function(event) {
        $('#search_form').submit();
    });

    var options = {
        // chart: {
        //     type: 'pie',
        //     options3d: {
        //         enabled: true,
        //         alpha: 45,
        //     }
        // },
        // title: {
        //     text: "用户设备统计"
        // },
        // tooltip: {
        //     pointFormat: '{series.name}: {point.percentage:.1f}'
        // },
        // plotOptions: {
        //     pie: {
        //         innerSize: 100,
        //         depth: 45
        //     }
        // },
        // series: [{
        //     name: '设备数量',
        //     data: <?php echo $chart_data; ?>
        // }],
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: 1,//null,
            plotShadow: false
        },
        title: {
            margin: 0,
            text: "用户设备统计"
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            type: 'pie',
            name: '设备数量',
            data: <?php echo $chart_data; ?>
        }]
    };

    $('#highchart_container').highcharts(options);
});
</script>