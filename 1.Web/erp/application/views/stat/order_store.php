<link href="<?php echo base_url('static/css/datetimepicker.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="">数据统计</a></li>
            <li><a href="">销售数据</a></li>
            <li><a href="">门店销售统计</a></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('stat/order/store'); ?>" method="get" id="search_form">
                    <input type="hidden" name="act" id="act" value="">
                    <div class="form-group">
                        <p class="form-control-static input-sm">所属分站：</p>
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

                    <div class="form-group" style="width:100px;margin-left:10px;">
                        <input type="submit" class="btn btn-primary btn-sm btn-block" value="查 询">
                    </div>
                </form>
            </div>
            <?php if(!empty($results)){ ?>
                <table class="table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th width="25%">日期</th>
                            <th width="30%">配送点</th>
                            <th width="15%">订单数</th>
                            <th width="15%">订单总额</th>
                            <th width="15%">平均订单额</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $day=>$arr) { ?>
                        <?php $i = 0; ?>
                        <?php foreach ($arr as $row) { ?>
                        <tr>
                            <?php if( $i==0 ){ ?>
                            <td rowspan="<?php echo count($arr); ?>" style="vertical-align:middle;"><?php echo $day; ?></td>
                            <?php } ?>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['total']; ?></td>
                            <td>￥<?php echo sprintf('%.2f',$row['price']); ?></td>
                            <td>￥<?php echo sprintf('%.2f',$row['price']/$row['total']); ?></td>
                        </tr>
                        <?php $i++; ?>
                        <?php } ?>
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