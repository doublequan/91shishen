<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('vip/product'); ?>">大客户管理</a></li>
            <li><a href="<?php echo site_url('vip/product'); ?>">大客户商品</a></li>
            <li class="active">商品列表</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('vip/product'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">所属分类：</p>
                    </div>
                    <div class="form-group">
                        <select name="category_id" class="form-control input-sm input-sm" id="category_id">
                            <option value="">全部分类</option>
                            <?php foreach ($categorys as $key => $value) { ?>
                                <option value="<?php echo $key; ?>" <?php echo $params['category_id']==$key?'selected="selected"':''; ?>><?php echo $value['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <p class="form-control-static input-sm">&nbsp;&nbsp;关键字：</p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control input-sm" name="keyword" value="<?php echo $params['keyword']; ?>">
                    </div>
                    
                    <div class="form-group" style="width:80px;">
                        <input type="submit" class="btn btn-primary btn-sm btn-block" value="查 询">
                    </div>

                    <a class="btn btn-primary btn-sm" id="add_form_btn" href="<?php echo site_url('vip/product/add'); ?>">添加商品</a>
                </form>
            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th style="width:30px;">
                            #
                        </th>
                        <th style="width:70px;">
                            商品编码
                        </th>
                        <th style="width:160px;">
                            商品名称
                        </th>
                        <th style="width:100px;">
                            所属类别
                        </th>
                        <!-- <th style="width:100px;">
                            商品品牌
                        </th> -->
                        <th style="width:80px;">
                            商品规格
                        </th>
                        <th style="width:80px;">
                            包装规格
                        </th>
                        <th style="width:80px;">
                            计价单位
                        </th>
                        <th style="width:80px;">
                            销售价
                        </th>
                        <th style="width:120px;">
                            操作
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $idx = 0;
                        foreach ($results as $single_info) {
                    ?>
                    <tr>
                        <td style="text-align:center;">
                            <?php echo ++$idx; ?>
                        </td>
                        <td>
                            <?php echo $single_info['sku']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['title']; ?>
                        </td>
                        <td>
                            <?php echo $categorys[$single_info['category_id']]['name']; ?>
                        </td>
                        <!-- <td>
                            <?php echo isset($brands[$single_info['brand_id']])?$brands[$single_info['brand_id']]['name']:''; ?>
                        </td> -->
                        <td>
                            <?php echo $specs[$single_info['spec']]['name']; ?>
                        </td>
                        <td>
                            <?php echo $specs[$single_info['spec_packing']]['name']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['unit']; ?>
                        </td>
                        <td>
                            ￥<?php echo $single_info['price']; ?>
                        </td>
                        <td product_id="<?php echo $single_info['id']; ?>" style="padding:6px;">
                            <a class="btn btn-xs btn-success" href="<?php echo site_url('vip/product/edit').'?id='.$single_info['id']; ?>">编辑</a>
                            <a class="btn btn-xs btn-warning" href="<?php echo site_url('vip/price').'?product_id='.$single_info['id']; ?>" target="_blank">价格变动</a>
                            <a class="btn btn-xs btn-danger delete_link" href="#">删除</a>
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
        href: "?<?php echo '&category_id='.$params['category_id'].'&keyword='.$params['keyword'].'&page=';?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });

    var category_selected = "<?php echo isset($params['category_id'])?$params['category_id']:0; ?>";
    var category_site_id = "<?php echo isset($params['site_id'])?$params['site_id']:0; ?>";

    $('#category_id option.cate_ops[value="' + category_selected + '"]').attr('selected', true);
    $('#category_id option.cate_ops[site_id="' + category_site_id + '"]').show();

    $('#search_form select').change(function(event) {
        $('#search_form').submit();
    });
    
    $(".delete_link").click(function(){
        if(confirm("是否确定删除商品？")){
            var product_id = $(this).parent('td').attr('product_id');
            if(product_id){
                $.get("<?php echo site_url('vip/product/delete'); ?>", {'product_id': product_id}, function(data){
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