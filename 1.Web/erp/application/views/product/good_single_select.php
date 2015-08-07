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
                    <form class="form-inline" action="<?php echo site_url('products/good/single_select_dialog'); ?>" method="get" id="search_form">
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
                            <td width="5%">#</td>
                            <th width="35%">原料名称</th>
                            <th width="12%">最新采购价</th>
                            <th width="12%">计量方式</th>
                            <th width="12%">计量单位</th>
                            <th width="12%">最小单位数量</th>
                            <th width="12%">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach( $results as $k=>$row ){ ?>
                        <tr good_id="<?php echo $row['id']; ?>" good_name="<?php echo $row['name']; ?>">
                            <td>
                                <?php echo ($k+1); ?>
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
                            <td>
                                <a href="#" class="check_link">选择</a>
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
    
    $('.check_link').click(function(){
        window.parent.document.getElementById('good_id').value = $(this).parent().parent('tr').attr('good_id');
        window.parent.document.getElementById('good_name').value = $(this).parent().parent('tr').attr('good_name');
        //window.parent.document.getElementById('good_info').value = $(this).parent().parent('tr').attr('good_info');
        window.parent.$.fancybox.close();
    });

    $('#goods_list_table tbody tr').dblclick(function(){
        window.parent.document.getElementById('good_id').value = $(this).attr('good_id');
        window.parent.document.getElementById('good_name').value = $(this).attr('good_name');
        //window.parent.document.getElementById('good_info').value = $(this).parent().parent('tr').attr('good_info');
        window.parent.$.fancybox.close('fade');
    });
});
</script>
</body>
</html>
