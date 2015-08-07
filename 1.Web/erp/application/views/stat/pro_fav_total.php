<link href="<?php echo base_url('static/css/datetimepicker.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('stat/user/user_total'); ?>">数据统计</a></li>
            <li><a href="<?php echo site_url('stat/product/pro_fav_total'); ?>">商品数据</a></li>
            <li class="active">收藏排行</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('stat/product/pro_fav_total'); ?>" method="get" id="search_form">
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
                        <p class="form-control-static input-sm">&nbsp;&nbsp;日期：</p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control input-sm" name="day" id="day" 
                            value="<?php echo $params['day']; ?>" >
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
                <div id="highchart_container" style="width:100%;height:700px;"></div>
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
                        <th style="width:80px;">
                            商品编码
                        </th>
                        <th style="width:120px;">
                            商品名称
                        </th>
                        <th style="width:100px;">
                            收藏数量
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
                            <?php echo $sites[$params['site_id']]['name']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['sku']; ?>
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

    $('#day').datetimepicker({
        autoclose: 1,
        format: "yyyy-mm-dd",
        language: "zh",
        maxView: 3,
        minView: 2,
        startView: 2,
        todayHighlight: 1
    });
    
    var options = {
        chart: {
            type: 'bar'
        },
        credits:{
            enabled: false
        },
        title: {
            text: "商品总收藏排行：<b><?php echo $sites[$params['site_id']]['name']; ?></b>"
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