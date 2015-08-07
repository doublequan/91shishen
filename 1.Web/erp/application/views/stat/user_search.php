<link href="<?php echo base_url('static/css/datetimepicker.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('stat/user/user_total'); ?>">数据统计</a></li>
            <li><a href="<?php echo site_url('stat/user/user_total'); ?>">用户数据</a></li>
            <li class="active">用户搜索关键词排行</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                &nbsp;&nbsp;统计日期：<?php echo $day; ?>
            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <div class="panel-body">
                <div id="highchart_container" style="width:100%;height:700px;"></div>
            </div>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th style="width:30px;">
                            #
                        </th>
                        <th style="width:120px;">
                            关键词
                        </th>
                        <th style="width:100px;">
                            搜索数量
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
                            <?php echo urldecode($single_info['name']); ?>
                        </td>
                        <td>
                            <?php echo $single_info['number']; ?>
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
<script src="<?php echo base_url('static/js/highcharts.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {

    var options = {
        chart: {
            type: 'bar'
        },
        credits:{
            enabled: false
        },
        title: {
            text: "<b>用户搜索关键词排行</b>"
        },
        xAxis: {                                                           
            categories: <?php echo urldecode($chart_data_name); ?>,
            title: {                                                       
                text: null                                                 
            }                                                              
        },  
        yAxis: {                                                           
            min: 0,                                                        
            title: {                                                       
                text: 'Population (millions)',                             
                align: 'high'                                              
            },                                                             
            labels: {                                                      
                overflow: 'justify'                                        
            }                                                              
        },
        plotOptions: {                                                     
            bar: {                                                         
                dataLabels: {                                              
                    enabled: true                                          
                }                                                          
            }                                                              
        },
       
        series: [{
            name: "收藏量",
            data: <?php echo urldecode($chart_data_value); ?>,
        }]
    };

    $('#highchart_container').highcharts(options);

});
</script>