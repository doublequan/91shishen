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
                    选择商品
                </h4>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <form class="form-inline" action="<?php echo site_url('products/product/single_select_dialog'); ?>" method="get" id="search_form">
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
                            
                            <div class="form-group">
                                 <input type="submit" class="btn btn-primary btn-sm" value="查 询">
                            </div>
                        </form>
                    </div>
                    <?php 
                        if(!empty($results)){ 
                    ?>
                    <table class="table table-striped table-hover table-condensed text-center" id="products_list_table">
                        <thead>
                            <tr>
                                <th style="width:30px;">
                                    #
                                </th>
                                <th style="width:70px;">
                                    商品编码
                                </th>
                                <th style="width:150px;">
                                    商品名称
                                </th>
                                <th style="width:90px;">
                                    所属类别
                                </th>
                                <th style="width:80px;">
                                    商品类型
                                </th>
                                <th style="width:80px;">
                                    销售价
                                </th>
                                <th style="width:80px;">
                                    市场价
                                </th>
                                <th style="width:80px;">
                                    上架网站数
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $idx = 0;
                                foreach ($results as $single_info) {
                            ?>
                            <tr product_id="<?php echo $single_info['id']; ?>"
                                product_sku="<?php echo $single_info['sku']; ?>"
                                product_title="<?php echo $single_info['title']; ?>"
                                category_name="<?php echo $category_map[$single_info['category_id']]['name']; ?>"
                                price="<?php echo $single_info['price']; ?>"
                                price_market="<?php echo $single_info['price_market']; ?>"
                                is_del="<?php echo $single_info['is_del']; ?>">
                                <td>
                                    <?php echo ++$idx; ?>
                                </td>
                                <td>
                                    <?php echo $single_info['sku']; ?>
                                </td>
                                <td>
                                    <?php echo $single_info['title']; ?>
                                </td>
                                <td>
                                    <?php echo $category_map[$single_info['category_id']]['name']; ?>
                                </td>
                                <td>
                                    <?php 
                                    if($single_info['type'] == 0) echo '普通';
                                    if($single_info['type'] == 1) echo '预售';
                                    if($single_info['type'] == 2) echo '团购';
                                    ?>
                                </td>
                                <td>
                                    ￥<?php echo $single_info['price']; ?>
                                </td>
                                <td>
                                    ￥<?php echo $single_info['price_market']; ?>
                                </td>
                                <td>
                                    <?php if( $single_info['siteNum']==0 ){ ?>
                                    <span class="label label-danger"><?php echo $single_info['siteNum']; ?></span>
                                    <?php } else { ?>
                                    <span class="label label-success"><?php echo $single_info['siteNum']; ?></span>
                                    <?php } ?>
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
        
        $('.check_link').click(function(){
            window.parent.document.getElementById('product_id').value = $(this).parent().parent('tr').attr('product_id');
            window.parent.document.getElementById('product_title').value = $(this).parent().parent('tr').attr('product_title');
            window.parent.$.fancybox.close();
        });

        $('#products_list_table tbody tr').dblclick(function(){
            window.parent.document.getElementById('product_id').value = $(this).attr('product_id');
            window.parent.document.getElementById('product_title').value = $(this).attr('product_title');
            window.parent.$.fancybox.close('fade');
        });
    });
    </script>

</body>
</html>
