<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('products/product'); ?>">商品管理</a></li>
            <li><a href="<?php echo site_url('products/sale'); ?>">门店零售</a></li>
            <li class="active">添加门店零售单</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('products/product'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">
                            <span><font color="red">*</font></span>
                            商品货号：
                        </p>
                    </div>
                    <div class="form-group">
                        <input type="text" name="pin" class="form-control" id="pin" placeholder="请扫描或者输入货号">
                    </div>
                </form>
            </div>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th style="width:5%;">#</th>
                        <th style="width:35%;">名称</th>
                        <th style="width:15%;">单位</th>
                        <th style="width:15%;">数量</th>
                        <th style="width:15%;">单价</th>
                        <th style="width:15%;">操作</th>
                    </tr>
                </thead>
                <tbody id="details">
                </tbody>
            </table>
            <div class="panel-footer">
                <label for="sku">商品总价(￥)：</label>
                <input type="text" name="price_total" id="price_total" value="0.00" style="padding:5px; text-align:center;" disabled>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label for="sku">优惠金额(￥)：</label>
                <input type="text" name="price_discount" id="price_discount" value="0.00" style="padding:5px; text-align:center;">
                <button type="button" class="btn btn-sm btn-primary" id="addSale" style="float:right;">提交销售单</button>
            </div>
    </div>
</div>

<script src="<?php echo base_url('static/js/jquery.min.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
    $('#pin').focus();

    $("#pin").keyup( function(){
        var pin = $(this).val();
        if( pin.length>=4 ){
            var flag = true;
            $("#details .pin").each( function(){
                if( pin==$(this).val() ){
                    flag = false;
                    return;
                }
            });
            if( !flag ){
                return false;
            }
            $.get("<?php echo site_url('products/product/searchByPin'); ?>", {'pin':pin}, function(msg){
                if( msg && msg.err_no==0 && msg.results ){
                    var total = $("#details .pin").length;
                    var r = msg.results;
                    var html = '';
                    html += '<tr>';
                    html += '<td class="text-center">'+(total+1)+'<input type="hidden" name="pin" class="pin" value="'+r.product_pin+'"></td>';
                    html += '<td class="text-center">'+r.title+'</td>';
                    html += '<td>'+r.unit+'</td>';
                    html += '<td class="text-center"><input type="text" name="amount[]" class="text-center" value="1"></td>';
                    html += '<td>'+r.price+'</td>';
                    html += '<td><a href="" class="delete">删除</a></td>';
                    $('#details').append(html);
                    deleteRow();
                }
            },'json');
        }
    });
    
    $('#addSale').click( function(){
        alert('暂不实现');
    });

    function deleteRow(){
        $('.delete').click( function(){
            $(this).parent().parent().remove();
        });
    }
});
</script>