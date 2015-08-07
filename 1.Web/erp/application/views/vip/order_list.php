<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('vip/product'); ?>">大客户管理</a></li>
            <li><a href="<?php echo site_url('vip/order'); ?>">大客户订单</a></li>
            <li class="active"><?php echo $params['status']==-1 ? '全部订单' : $statusMap[$params['status']]; ?>列表</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <form class="form-inline" action="<?php echo site_url('vip/order'); ?>" method="get" id="search_form">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="form-group">
                    <p class="form-control-static input-sm">订单号：</p>
                </div>
                <div class="form-group">
                     <input type="text" class="form-control input-sm" name="keyword" style="width:200px;" 
                        value="<?php echo $params['keyword']; ?>" placeholder="订单编号">
                </div>
                <div class="form-group" style="width:80px;">
                    <input type="submit" class="btn btn-primary btn-sm btn-block" value="查 询">
                </div>

            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th style="width:20px;" class="text-center">
                            <input type="checkbox" id="check_all_chk">
                        </th>
                        <th style="width:20px;">
                            #
                        </th>
                        <th style="width:150px;">
                            订单编号
                        </th>
                        <th style="width:50px;">
                            订单总价
                        </th>
                        <th style="width:100px;">
                            所属用户
                        </th>
                        <th style="width:120px;">
                            配送时间要求
                        </th>
                        <th style="width:100px;">
                            收货人
                        </th>
                        <th style="width:120px;">
                            订单生成时间
                        </th>
                        <th style="width:120px;">
                            操作
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach ($results as $k=>$single_info) {
                    ?>
                    <tr>
                        <td>
                            <input type="checkbox" class="check_single_chk" value="<?php echo $single_info['id']; ?>">
                        </td>
                        <td>
                            <?php echo ($k+1); ?>
                        </td>
                        <td>
                            <?php echo $single_info['order_id']; ?>
                        </td>
                        <td>
                            ￥<?php echo $single_info['price']; ?>
                        </td>
                        <td>
                            <?php echo isset($users[$single_info['uid']]) ? $users[$single_info['uid']] : '未知用户'; ?>
                        </td>
                        <td>
                            <?php echo empty($single_info['delivery_time'])?'未知时间':date('Y-m-d H:i:s',$single_info['delivery_time']); ?>
                        </td>
                        <td>
                            <?php echo $single_info['receiver']; ?>
                        </td>
                        <td>
                            <?php echo date('Y-m-d H:i:s',$single_info['create_time']); ?>
                        </td>
                        <td row_id="<?php echo $single_info['id']; ?>" style="padding:6px;">
                            <a href="<?php echo site_url('vip/order/detail?order_id='.$single_info['id']); ?>" 
                                class="btn btn-xs btn-success">订单详情</a>
                            <a href="<?php echo site_url('vip/order/order_print?order_id='.$single_info['id']); ?>" 
                                 class="btn btn-xs btn-info" target="_blank">打印配送单</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="panel-footer">
                <button type="button" class="btn btn-sm btn-primary" id="createPurchase">根据所选订单生成采购单</button>
                <button type="button" class="btn btn-sm btn-primary" id="mutiPrintBtn">根据所选订单批量打印配送单</button>
                <div class="form-group pull-right" style="padding-left:10px;">
                    <select name="size" class="form-control input-sm">
                        <option value="20"<?php echo $params['size']==20 ? ' selected' : ''; ?>>每页显示20条</option>
                        <option value="50"<?php echo $params['size']==50 ? ' selected' : ''; ?>>每页显示50条</option>
                        <option value="100"<?php echo $params['size']==100 ? ' selected' : ''; ?>>每页显示100条</option>
                        <option value="200"<?php echo $params['size']==200 ? ' selected' : ''; ?>>每页显示200条</option>
                        <option value="500"<?php echo $params['size']==500 ? ' selected' : ''; ?>>每页显示500条</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12 text-right">
                <ul id="" class="pagination"></ul>
            </div>
            <?php }else{ ?>
            <div class="alert alert-warning col-md-12 text-center" role="alert">查询结果为空！</div>
            <?php } ?>
        </div>
        </form>
    </div>
</div>

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
        href: "?<?php echo 'status='.$params['status'].'&keyword='.$params['keyword'].'&page=';?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });

    $('#search_form select').change(function(event) {
        $('#search_form').submit();
    });

    $("#mutiPrintBtn").click(function(){
        var order_ids = [];
        $('.check_single_chk').each(function(){
            if( $(this).attr('checked')=='checked' ){
                order_ids.push($(this).val());
            }
        });
        if( order_ids.length==0 ){
            alert('请至少选择一个订单');
            return false;
        }

        var muti_print_url = "<?php echo site_url('vip/order/order_print_muti'); ?>";
        muti_print_url += ('?order_ids=' + order_ids.join(','));
        window.open(muti_print_url);
    });
});
</script>