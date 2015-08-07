<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('user/user'); ?>">用户管理</a></li>
            <li><a href="<?php echo site_url('user/log/score'); ?>">用户数据统计</a></li>
            <li class="active">用户<?php echo $title; ?>使用统计</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <?php 
                if(!empty($results)){ 
            ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th style="width:40px;">
                            序号
                        </th>
                        <th style="width:150px;">
                            所属用户
                        </th>
                        <th style="width:160px;">
                            使用订单号
                        </th>
                        <th style="width:120px;">
                            <?php echo $title; ?>优惠码
                        </th>
                        <th style="width:120px;">
                            <?php echo $title; ?>已用额度
                        </th>
                        <th style="width:120px;">
                            <?php echo $title; ?>当前余额
                        </th>
                        <th style="width:120px;">
                            调整时间
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach ($results as $k=>$single_info) {
                    ?>
                    <tr>
                        <td>
                            <?php echo ($k+1); ?>
                        </td>
                        <td>
                            <?php echo isset($users[$single_info['uid']]) ? $users[$single_info['uid']] : '未知用户'; ?>
                        </td>
                        <td>
                            <?php echo $single_info['order_id']; ?>
                        </td>
                        <td>
                            <?php echo isset($coupons[$single_info['coupon_id']]) ? $coupons[$single_info['coupon_id']] : '未知优惠码'; ?>
                        </td>
                        <td>
                            ￥<?php echo $single_info['coupon_use']; ?>
                        </td>
                        <td>
                            ￥<?php echo $single_info['coupon_balance']; ?>
                        </td>
                        <td>
                            <?php echo date('Y-m-d H:i:s',$single_info['create_time']); ?>
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
$(function(){
    $('.pagination').twbsPagination({
        totalPages: <?php echo isset($pager['pages'])?$pager['pages']:1; ?>,
        visiblePages: 5,
        startPage: <?php echo isset($pager['page'])?$pager['page']:1; ?>,
        first: '首页',
        prev: '上一页',
        next: '下一页',
        last: '尾页',
        href: "?<?php echo '&page=';?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });
});
</script>
