<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                选择原料
            </h4>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <form class="form-inline" action="<?php echo site_url('products/good/muti_select_dialog'); ?>" method="get" id="search_form">
                        <div class="form-group">
                            <p class="form-control-static input-sm">所属分类：</p>
                        </div>
                        <div class="form-group">
                            <select name="category_id" class="form-control input-sm input-sm" id="category_id">
                                <option value="">全部分类</option>
                                <?php echo getTreeOptions($category_list); ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <p class="form-control-static input-sm">&nbsp;&nbsp;原料名称：</p>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control input-sm" name="keyword" value="<?php echo $params['keyword']; ?>">
                        </div>
                        
                        <div class="form-group">
                             <input type="submit" class="btn btn-primary btn-sm" value="查 询">
                        </div>
                    </form>
                </div>
                <?php 
                    if(!empty($results)){ 
                ?>
                <table class="table table-striped table-hover table-condensed text-center" id="goods_list_table">
                    <thead>
                        <tr>
                            <th width="5%">选择</th>
                            <th width="35%">原料名称</th>
                            <th width="15%">最新采购价</th>
                            <th width="15%">计量方式</th>
                            <th width="15%">计量单位</th>
                            <th width="15%">最小单位数量</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach( $results as $row ){ ?>
                        <tr good_id="<?php echo $row['id']; ?>"
                            good_name="<?php echo $row['name']; ?>"
                            price="<?php echo $row['price'] ? '￥'.$row['price'] : '未录入价格'; ?>"
                            method="<?php echo isset($methodMap[$row['method']]) ? $methodMap[$row['method']] : ''; ?>"
                            unit="<?php echo $row['unit']; ?>"
                            amount="<?php echo $row['amount']; ?>"
                            good_brand="<?php echo isset($brands[$row['brand_id']]) ? $brands[$row['brand_id']]['name'] : ''; ?>">
                            <td>
                                <input type="checkbox" name="good_chk" class="good_chk">
                            </td>
                            <td>
                                <?php echo $row['name']; ?>
                            </td>
                            <td>
                                <?php echo $row['price'] ? '￥'.$row['price'] : '未录入价格'; ?>
                            </td>
                            <td>
                                <?php echo isset($methodMap[$row['method']]) ? $methodMap[$row['method']] : ''; ?>
                            </td>
                            <td>
                                <?php echo $row['unit']; ?>
                            </td>
                            <td>
                                <?php echo $row['amount']; ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="col-md-12 text-right">
                    <ul id="" class="pagination"></ul>
                </div>
                <?php }else{ ?>
                <div class="alert alert-warning col-md-12 text-center" role="alert">查询结果为空！</div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('static/js/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/jquery.twbsPagination.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">

function generateGoodTr(good_id, good_name, price, method, unit, amount, good_amount){
    var tr_str = [];
    tr_str.push('<tr good_id="' + good_id + '">');
    tr_str.push('<td>' + good_id + '<input type="hidden" class="goods_id" name="goods_id[]" value="' + good_id + '"></td>');
    tr_str.push('<td>' + good_name + '</td>');
    tr_str.push('<td>' + price + '</td>');
    tr_str.push('<td>' + method + '</td>');
    tr_str.push('<td>' + unit + '</td>');
    tr_str.push('<td>' + amount + '</td>');
    tr_str.push('<td><input type="text" class="form-control input-sm goods_amount" style="width:60px;text-align:center;" name="goods_amount[]" value="' + good_amount + '"></td>');
    tr_str.push('<td><a href="javascript:void(0)" class="delete_tr_link">删除</a></td>');
    return tr_str.join('');
}

$(function(){
    $('.pagination').twbsPagination({
        totalPages: <?php echo isset($pager['pages'])?$pager['pages']:1; ?>,
        visiblePages: 5,
        startPage: <?php echo isset($pager['page'])?$pager['page']:1; ?>,
        first: '首页',
        prev: '上一页',
        next: '下一页',
        last: '尾页',
        href: "?<?php echo 'category_id='.$params['category_id'].'&keyword='.$params['keyword'].'&page=';?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });

    var category_selected = "<?php echo isset($params['category_id'])?$params['category_id']:0; ?>";
    $('#category_id option.cate_ops[value="' + category_selected + '"]').attr('selected', true);

    $('#search_form select').change(function(event) {
        $('#search_form').submit();
    });
    
    var delete_tr = function(){
        $(this).parent().parent('tr').remove();
    }
    
    $.each(window.parent.$('#goods_table tbody tr'), function(index, val) {
        if(val){
            var good_id = val.getAttribute('good_id');
            if(good_id){
                $('#goods_list_table tbody tr[good_id="'+good_id+'"]').children('td').children('.good_chk').prop('checked', true);
            }
        }
    });

    $('.good_chk').click(function(){
        var $tr_line = $(this).parent().parent('tr');
        var good_id = $tr_line.attr('good_id');

        if($(this).prop('checked')){
            var $good_trs = window.parent.$('#goods_table tbody tr');

            var is_exist = false;
            $.each($good_trs, function(idx, value){
                var good_id_checked = value.getAttribute('good_id');

                if(good_id_checked == good_id){
                    is_exist = true;
                    return false;
                }
            });

            if(is_exist){
                alert('原料已选择！');
                $(this).prop('checked', false);
                return;
            }
            var good_name = $tr_line.attr('good_name');
            var price = $tr_line.attr('price');
            var method = $tr_line.attr('method');
            var unit = $tr_line.attr('unit');
            var amount = $tr_line.attr('amount');
            var good_amount = $tr_line.attr('good_amount');
            if(!good_amount) good_amount = '';

            var tr_str = generateGoodTr(good_id, good_name, price, method, unit, amount, good_amount);
            window.parent.$('#goods_table tbody').append(tr_str);
            window.parent.$('.delete_tr_link').unbind('click', delete_tr).bind('click', delete_tr);
        }
        else{
            window.parent.$('#goods_table tbody tr[good_id="' + good_id + '"]').remove();
        }
    });
});
</script>
</body>
</html>