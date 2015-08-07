<link href="<?php echo base_url('static/css/datetimepicker.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="">数据统计</a></li>
            <li><a href="">销售数据</a></li>
            <li><a href="">商品销量统计</a></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('stat/order/product'); ?>" method="get" id="search_form">
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
                        <input type="text" class="form-control input-sm" name="start" id="start" value="<?php echo $params['start']; ?>" >
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
                    <div class="form-group" style="width:100px;margin-left:5px;">
                         <input type="button" class="btn btn-info btn-sm btn-block" id="export" value="导出Excel">
                    </div>
                </form>
            </div>
            <?php if( $results ){ ?>
                <table class="table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th width="15%">商品编号</th>
                            <th width="10%">商品货号</th>
                            <th width="30%">商品名称</th>
                            <th width="15%">商品分类</th>
                            <th width="10%">商品规格</th>
                            <th width="10%">商品单价</th>
                            <th width="10%">商品销量</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $k=>$v) { ?>
                        <?php if( isset($products[$k]) ){ ?>
                        <?php $product = $products[$k]; ?>
                        <tr>
                            <td><?php echo $product['sku']; ?></td>
                            <td><?php echo $product['product_pin']; ?></td>
                            <td><?php echo $product['title']; ?></td>
                            <td><?php echo isset($cates[$product['category_id']]) ? $cates[$product['category_id']]['name'] : ''; ?></td>
                            <td><?php echo $product['spec']; ?></td>
                            <td>￥<?php echo sprintf('%.2f', $product['price']); ?></td>
                            <td><?php echo $v; ?></td>
                        </tr>
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

    $('#search_form option').change(function(event) {
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