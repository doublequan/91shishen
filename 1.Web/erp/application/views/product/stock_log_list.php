
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('products/product'); ?>">商品管理</a></li>
            <li><a href="<?php echo site_url('products/stock'); ?>">库存</a></li>
            <li class="active">库存记录</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('products/stock/log_list'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static">所属门店：</p>
                    </div>
                    <div class="form-group">
                        <p class="form-control-static"><strong><?php echo $params['qt']=='good' ? '[非标准]原料' : '[标准化]商品'; ?></strong></p>
                    </div>
                    <div class="form-group" style="padding-left:15px;">
                        <p class="form-control-static">门店：</p>
                    </div>
                    <div class="form-group">
                        <p class="form-control-static"><strong><?php echo $params['store_name']; ?></strong></p>
                    </div>
                    <div class="form-group" style="padding-left:15px;">
                        <p class="form-control-static">原料/商品：</p>
                    </div>
                    <div class="form-group">
                        <p class="form-control-static"><strong><?php echo $params['item_name']; ?></strong></p>
                    </div>
                </form>
            </div>
            <?php 
            if(!empty($results)){ 
                if($params['qt'] == 'good'){
            ?>
                <table class="table table-striped table-hover text-center">
                    <thead>
                        <tr>
                            <th width="10%">
                                原料编号
                            </th>
                            <th width="25%">
                                原料名称
                            </th>
                            <th width="10%">
                                货品编号
                            </th>
                            <th width="10%">
                                变更数量
                            </th>
                            <th width="10%">
                                当前余量
                            </th>
                            <th width="10%">
                                变更员工
                            </th>
                            <th style="width:100px;">
                                变更时间
                            </th>
                            <th width="10%">
                                状态
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            foreach ($results as $single_info) {
                        ?>
                        <tr>
                            <td>
                                <?php echo $single_info['good_id']; ?>
                            </td>
                            <td>
                                <?php echo $existMap[$single_info['good_id']]['name']; ?>
                            </td>
                            <td>
                                <?php echo $single_info['good_no']; ?>
                            </td>
                            <td>
                                <?php echo $single_info['change']; ?>
                            </td>
                            <td>
                                <?php echo $single_info['current']; ?>
                            </td>
                            <td>
                                <?php echo @$single_info['create_name']; ?>
                            </td>
                            <td>
                                <?php echo date('Y-m-d H:i:s',$single_info['create_time']); ?>
                            </td>
                            <td>
                                <?php 
                                switch($single_info['type']){
                                    case 1:
                                        echo '<span class="label label-info">入库</span>';
                                        break;
                                    case 2:
                                        echo '<span class="label label-primary">出库</span>';
                                        break;
                                    case 3:
                                        echo '<span class="label label-success">订单</span>';
                                        break;
                                    case 4:
                                        echo '<span class="label label-warning">损耗</span>';
                                        break;
                                    default:
                                        echo '<span class="label label-default">未知</span>';
                                        break;
                                }
                                ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php }elseif($params['qt'] == 'product'){ ?>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width:150px;">
                                商品编号
                            </th>
                            <th style="width:100px;">
                                商品名称
                            </th>
                            <th style="width:100px;">
                                商品编号
                            </th>
                            <th style="width:80px;">
                                变更数量
                            </th>
                            <th style="width:80px;">
                                当前余量
                            </th>
                            <th style="width:100px;">
                                变更员工
                            </th>
                            <th style="width:100px;">
                                变更时间
                            </th>
                            <th style="width:80px;">
                                状态
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            foreach ($results as $single_info) {
                        ?>
                        <tr>
                            <td>
                                <?php echo $single_info['product_id']; ?>
                            </td>
                            <td>
                                <?php echo $existMap[$single_info['product_id']]['title']; ?>
                            </td>
                            <td>
                                <?php echo $single_info['product_no']; ?>
                            </td>
                            <td>
                                <?php echo $single_info['change']; ?>
                            </td>
                            <td>
                                <?php echo $single_info['current']; ?>
                            </td>
                            <td>
                                <?php echo @$single_info['create_name']; ?>
                            </td>
                            <td>
                               <?php echo date('Y-m-d H:i:s',$single_info['create_time']); ?>
                            </td>
                            <td>
                                <?php 
                                switch($single_info['type']){
                                    case 1:
                                        echo '<span class="label label-info">入库</span>';
                                        break;
                                    case 2:
                                        echo '<span class="label label-primary">出库</span>';
                                        break;
                                    case 3:
                                        echo '<span class="label label-success">订单</span>';
                                        break;
                                    case 4:
                                        echo '<span class="label label-warning">损耗</span>';
                                        break;
                                    default:
                                        echo '<span class="label label-default">未知</span>';
                                        break;
                                }
                                ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
            <div class="col-md-12 text-right">
                <ul id="" class="pagination"></ul>
            </div>
            <?php }else{ ?>
            <div class="alert alert-warning col-md-12 text-center" role="alert">查询结果为空！</div>
            <?php } ?>
        </div>
    </div>
</div>

<iframe src="" id="add_form_page" class="iframe_dialog" style="height:500px;"></iframe>

<script src="<?php echo base_url('static/js/jquery.fancybox.js'); ?>" type="text/javascript"></script>
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
        href: "?<?php echo 'qt='.$params['qt'].'&store_id='.$params['store_id'].'&item_id='.$params['item_id'].'&page=';?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });

    $('#search_form select').change(function(event) {
        $('#search_form').submit();
    });
    
});
</script>