<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="">数据统计</a></li>
            <li><a href="">支付宝支付统计</a></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('stat/pay/alipay'); ?>" method="get" id="search_form">
                    <input type="hidden" name="act" id="act" value="">
                    <div class="form-group">
                        <p class="form-control-static input-sm">自提门店：</p>
                    </div>
                    <div class="form-group">
                        <select name="store_id" id="store_id" class="form-control">
                            <option value="0">不限</option>
                            <?php if( $stores ) { ?>
                            <?php foreach( $stores as $k=>$row ) { ?>
                            <option value="<?php echo $k; ?>"<?php echo $k==$params['store_id'] ? ' selected' : ''; ?>><?php echo $row['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <p class="form-control-static input-sm">&nbsp;&nbsp;订单号：</p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control input-sm" name="keyword" value="<?php echo $params['keyword']; ?>">
                    </div>
                    <div class="form-group" style="width:100px;margin-left:10px;">
                        <input type="submit" class="btn btn-primary btn-sm btn-block" value="查 询">
                    </div>
                    <div class="form-group" style="width:100px;margin-left:5px;">
                         <input type="button" class="btn btn-info btn-sm btn-block" id="export" value="导出Excel">
                    </div>
                </form>
            </div>
            <?php if(!empty($results)){ ?>
            <table class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th width="20%">订单号</th>
                        <th width="12%">支付金额</th>
                        <th width="12%">支付状态</th>
                        <th width="20%">支付宝订单编号</th>
                        <th width="20%">支付用户账号</th>
                        <th width="16%">支付时间</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $k=>$row) { ?>
                    <tr>
                        <td><?php echo $params['keyword'] ? str_replace($params['keyword'], '<font color="red">'.$params['keyword'].'</font>', $row['order_id']) : $row['order_id']; ?></td>
                        <td style="color:red;">￥<?php echo sprintf('%.2f', $row['price']); ?></td>
                        <td><?php echo $row['pay_time'] ? '<font color="green">已支付</font>' : '<font color="red">未支付</font>'; ?></td>
                        <td><?php echo $row['trade_no']; ?></td>
                        <td><?php echo $row['buyer_email']; ?></td>
                        <td><?php echo $row['pay_time'] ? date('Y-m-d H:i:s',$row['pay_time']) : ''; ?></td>
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
        href: "?<?php echo 'store_id='.$params['store_id'].'&keyword='.urlencode($params['keyword']).'&page='; ?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });

    $('#search_form select').change(function() {
        $('#search_form').submit();
    });

    $('#export').click(function(){
        $('#act').val('export');
        $('#search_form').submit();
        $('#act').val('');
    });
});
</script>