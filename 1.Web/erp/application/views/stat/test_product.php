<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/bootstrap-switch.min.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="">数据统计</a></li>
            <li><a href="">测试统计</a></li>
            <li class="active">无详情商品列表</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('products/product'); ?>" method="get" id="search_form">
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
                        <p class="form-control-static input-sm">&nbsp;&nbsp;商品名称：</p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control input-sm" name="search" value="<?php echo $params['search']; ?>">
                    </div>
                    
                    <div class="form-group" style="width:80px;">
                         <input type="submit" class="btn btn-primary btn-sm btn-block" value="查 询">
                    </div>

                    <a class="btn btn-primary btn-sm" id="add_form_btn" href="<?php echo site_url('products/product/add'); ?>">添加商品</a>
                </form>
            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th width="3%">
                            #
                        </th>
                        <th width="10%">
                            商品编号
                        </th>
                        <th width="10%">
                            商品货号
                        </th>
                        <th width="25%">
                            商品名称
                        </th>
                        <th width="10%">
                            所属类别
                        </th>
                        <th width="25%">
                            前台地址
                        </th>
                        <th width="17%">
                            操作
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $idx = 0;
                        foreach ($results as $row) {
                    ?>
                    <tr>
                        <td>
                            <?php echo ++$idx; ?>
                        </td>
                        <td>
                            <?php echo $row['sku']; ?>
                        </td>
                        <td>
                            <?php echo $row['product_pin']; ?>
                        </td>
                        <td>
                            <?php echo $row['title']; ?>
                        </td>
                        <td>
                            <?php echo $category_map[$row['category_id']]['name']; ?>
                        </td>
                        <td>
                            <a target="_blank" href="http://www.100hl.com/goods_<?php echo $row['id'] ?>.html">http://www.100hl.com/goods_<?php echo $row['id'] ?>.html</a>
                        </td>
                        <td product_id="<?php echo $row['id']; ?>">
                            <a class="btn btn-xs btn-success" href="<?php echo site_url('products/product/edit').'?id='.$row['id']; ?>">编辑</a>
                            <a class="btn btn-xs btn-danger delete_link" href="javascript:void(0);">删除</a>
                            <a class="btn btn-xs btn-info" href="<?php echo site_url('products/stock').'?qt=product&item_id='.$row['id']; ?>">库存</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="panel-footer">
                <!--
                <button type="button" class="btn btn-sm btn-primary doSell" data="up">商品批量上架</button>
                <button type="button" class="btn btn-sm btn-primary doSell" data="down">商品批量下架</button>
                -->
            </div>
            <div class="col-md-12 text-right">
                <ul id="" class="pagination"></ul>
            </div>
            <?php }else{ ?>
            <div class="alert alert-warning col-md-12 text-center" role="alert">查询结果为空！</div>
            <?php } ?>
        </div>
    </div>
</div>

<script src="<?php echo base_url('static/js/jquery.fancybox.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/jquery.twbsPagination.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.pagination').twbsPagination({
        totalPages: <?php echo isset($pager['pages'])?$pager['pages']:1; ?>,
        visiblePages: 5,
        startPage: <?php echo isset($pager['page'])?$pager['page']:1; ?>,
        first: '首页',
        prev: '上一页',
        next: '下一页',
        last: '尾页',
        href: "?<?php echo 'category_id='.$params['category_id'].'&search='.$params['search'].'&page=';?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });

    var category_selected = "<?php echo isset($params['category_id'])?$params['category_id']:0; ?>";
    $('#category_id option.cate_ops[value="' + category_selected + '"]').attr('selected', true);

    $('#search_form select').change(function(event) {
        $('#search_form').submit();
    });

    $("a.check_form_link").click(function() {
        var product_id = $(this).parent('td').attr('product_id');
        if(product_id){
            $('iframe#add_form_page').attr('src', "<?php echo site_url('products/product/edit'); ?>" + '?id=' + product_id);
            $("a.check_form_link").fancybox({
                'hideOnContentClick': true,
                'padding':0,
                'afterClose': function(){
                    window.parent.location.reload();
                },
            });
        }
    });

    $(".delete_link").click(function(){
        if(confirm("是否确定删除商品？")){
            var product_id = $(this).parent('td').attr('product_id');
            if(product_id){
                $.get("<?php echo site_url('products/product/delete'); ?>", {'product_id': product_id}, function(data){
                    var data = $.parseJSON(data);
                    if(data && data.error){
                        alert(data.msg);
                    }
                    else{
                        alert("删除成功！");
                    }
                    window.location.reload();
                });
            }
        }
    });

});
</script>