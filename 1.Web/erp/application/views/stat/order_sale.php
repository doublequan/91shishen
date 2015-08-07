<link href="<?php echo base_url('static/css/datetimepicker.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="">数据统计</a></li>
            <li><a href="">销售数据</a></li>
            <li><a href="">销售额统计</a></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('stat/order/sale'); ?>" method="get" id="search_form">
                    <input type="hidden" name="act" id="act" value="">
                    <div class="form-group">
                        <p class="form-control-static input-sm">分站：</p>
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
                        <p class="form-control-static input-sm">&nbsp;&nbsp;支付方式：</p>
                    </div>
                    <div class="form-group">
                        <select name="pay_type" id="pay_type" class="form-control">
                            <option value="0">不限</option>
                            <?php if( $pay_types ) { ?>
                            <?php foreach( $pay_types as $k=>$v ) { ?>
                            <?php if( $k ) { ?>
                            <option value="<?php echo $k; ?>"<?php echo $k==$params['pay_type'] ? ' selected' : ''; ?>><?php echo $v; ?></option>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <p class="form-control-static input-sm">&nbsp;&nbsp;物流方式：</p>
                    </div>
                    <div class="form-group">
                        <select name="delivery_type" id="delivery_type" class="form-control">
                            <option value="-1">不限</option>
                            <?php if( $delivery_types ) { ?>
                            <?php foreach( $delivery_types as $k=>$v ) { ?>
                            <option value="<?php echo $k; ?>"<?php echo $k==$params['delivery_type'] ? ' selected' : ''; ?>><?php echo $v; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <p class="form-control-static input-sm">&nbsp;&nbsp;日期：</p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control input-sm" name="start" id="start" value="<?php echo $params['start']; ?>" style="width:100px;">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control input-sm" name="end" id="end" value="<?php echo $params['end']; ?>" style="width:100px;">
                    </div>

                    <div class="form-group" style="width:100px;margin-left:10px;">
                        <input type="submit" class="btn btn-primary btn-sm btn-block" value="查 询">
                    </div>
                    <div class="form-group" style="width:100px;margin-left:5px;">
                         <input type="button" class="btn btn-info btn-sm btn-block" id="export" value="导出Excel">
                    </div>
                </form>
            </div>
            <?php if(!empty($results)){ ?>
                <table class="table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th rowspan="2" width="13%" style="vertical-align:middle;">日期</th>
                            <th rowspan="2" width="9%" style="vertical-align:middle;">销售额</th>
                            <th rowspan="2" width="9%" style="vertical-align:middle;">客单价</th>
                            <th rowspan="2" width="9%" style="vertical-align:middle;">订单数</th>
                            <th rowspan="2" width="9%" style="vertical-align:middle;">商品详情数</th>
                            <th rowspan="2" width="9%" style="vertical-align:middle;">商品总金额</th>
                            <th rowspan="2" width="9%" style="vertical-align:middle;">储单数</th>
                            <th rowspan="2" width="9%" style="vertical-align:middle;">储单金额</th>
                            <th width="24%" colspan="<?php echo count($pay_types); ?>">支付方式</th>
                        </tr>
                        <tr>
                            <?php foreach ($pay_types as $k=>$v) { ?>
                            <th width="8%"><?php echo $v; ?></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $k=>$row) { ?>
                        <tr>
                            <td><?php echo $k; ?></td>
                            <td style="color:red;">￥<?php echo sprintf('%.2f', $row['price']); ?></td>
                            <td>￥<?php echo sprintf('%.2f',$row['price']/$row['total']); ?></td>
                            <td><?php echo $row['total']; ?></td>
                            <td><?php echo $row['details']; ?></td>
                            <td>￥<?php echo sprintf('%.2f', $row['price_total']); ?></td>
                            <td><?php echo $row['total_card']; ?></td>
                            <td>￥<?php echo sprintf('%.2f', $row['price_card']); ?></td>
                            <?php foreach ($pay_types as $k=>$v) { ?>
                            <td>￥<?php echo isset($row['pay_types'][$k]) ? sprintf('%.2f',$row['pay_types'][$k]) : '0.00'; ?></td>
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
<script src="<?php echo base_url('static/js/highstock.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#search_form select').change(function() {
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
});
</script>