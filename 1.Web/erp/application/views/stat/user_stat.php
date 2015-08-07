<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('products/good'); ?>"><span class="glyphicon glyphicon-home"></span> 商品管理</a></li>
            <li><a href="">数据统计</a></li>
            <li><a href="">用户分布统计</a></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('stat/user/stat'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">统计选项：</p>
                    </div>
                    <div class="form-group">
                        <select name="type" class="form-control">
                            <?php if( $types ) { ?>
                            <?php foreach( $types as $k=>$v ) { ?>
                            <option value="<?php echo $k; ?>"<?php echo $k==$params['type'] ? ' selected' : ''; ?>><?php echo $v; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group" style="width:80px;">
                        <input type="submit" class="btn btn-primary btn-sm btn-block" value="查 询">
                    </div>
                </form>
            </div>
            <?php if( $head && $results ){ ?>
            <div class="panel-body">
                <div id="highchart_container" style="width:100%;height:400px;"></div>
            </div>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <?php foreach( $head as $v ){ ?>
                        <th width="<?php echo intval(100/count($head)); ?>%"><?php echo $v; ?></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row) {?>
                    <tr>
                        <?php foreach( $row as $v ){ ?>
                        <td><?php echo $v; ?></td>
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

<script src="<?php echo base_url('static/js/highcharts.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#search_form select').change(function(event) {
        $('#search_form').submit();
    });

    var options = {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: '<?php echo isset($chart_title) ? $chart_title : ''; ?>'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.2f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    format: '<b>{point.name}</b>: {point.percentage:.2f} %'
                }
            }
        },
        series: [{
            type: 'pie',
            name: '占比',
            data: <?php echo json_encode($series); ?>
        }]
    };

    $('#highchart_container').highcharts(options);
});
</script>