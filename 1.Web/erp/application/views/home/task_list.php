
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('home/index'); ?>">我的桌面</a></li>
            <li><a href="<?php echo site_url('home/task?status=1'); ?>">任务管理</a></li>
            <li class="active"><?php echo isset($task_status_types[$params['status']]) ? $task_status_types[$params['status']] : '未知'; ?>任务列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('home/task?status='.$params['status']); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">按任务类型筛选：</p>
                    </div>
                    <div class="form-group">
                        <select name="type" class="form-control input-sm" id="type">
                            <option value="0">全部类型</option>
                        <?php foreach ($task_types as $k=>$v) { ?>
                            <option value="<?php echo $k; ?>" <?php echo $k==$params['type'] ? 'selected' : ''; ?>><?php echo $v; ?></option>
                        <?php } ?>
                        </select>
                    </div>
                </form>
                
            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <table class="table table-bordered table-hover table-center text-center">
                <thead>
                    <tr>
                        <th style="width:3%;">
                            #
                        </th>
                        <th style="width:4%;">
                            类型
                        </th>
                        <th style="width:38%;">
                            任务详情
                        </th>
                        <th style="width:8%;">
                            创建员工
                        </th>
                        <th style="width:15%;">
                            创建时间
                        </th>
                        <th style="width:8%;">
                            最后处理员工
                        </th>
                        <th style="width:15%;">
                            最后处理时间
                        </th>
                        <th style="width:9%;">
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
                            <?php echo ($k+1); ?>
                        </td>
                        <td class="text-left">
                            <?php if( $single_info['type']==1 ){ ?>
                            <span class="label label-primary">用户订单</span>
                            <?php } elseif( $single_info['type']==2 ) { ?>
                            <span class="label label-primary">大客户订单</span>
                            <?php } elseif( $single_info['type']==3 ) { ?>
                            <span class="label label-danger">大客户定制</span>
                            <?php } elseif( $single_info['type']==4 ) { ?>
                            <span class="label label-warning">财务单</span>
                            <?php } elseif( $single_info['type']==5 ) { ?>
                            <span class="label label-success">采购单</span>
                            <?php } elseif( $single_info['type']==6 ) { ?>
                            <span class="label label-info">调度单</span>
                            <?php } ?>
                        </td>
                        <td class="text-left">
                            <?php if( $single_info['type']==1 ){ ?>
                            您有新的用户订单需要确认，订单号：<a target="_blank" href="<?php echo site_url('order/order/detail?order_id='.$single_info['business_id']); ?>"><?php echo $single_info['business_id']; ?></a>
                            <?php } elseif( $single_info['type']==2 ) { ?>
                            有新的大客户订单需要确认，订单号：<a target="_blank" href="<?php echo site_url('vip/order/detail?order_id='.$single_info['business_id']); ?>"><?php echo $single_info['business_id']; ?></a>
                            <?php } elseif( $single_info['type']==3 ) { ?>
                            有新的大客户定制需求需要确认，定制单号：<a target="_blank" href="<?php echo site_url('vip/custom?status=0&keyword='.$single_info['business_id']); ?>"><?php echo $single_info['business_id']; ?></a>
                            <?php } elseif( $single_info['type']==4 ) { ?>
                            有新的财务申请单需要审批，财务单号：<a target="_blank" href="<?php echo site_url('products/purchase_finance?keyword='.$single_info['business_id']); ?>"><?php echo $single_info['business_id']; ?></a>
                            <?php } elseif( $single_info['type']==5 ) { ?>
                            有新的采购申请单需要审批，采购单号：<a target="_blank" href="<?php echo site_url('products/purchase?keyword='.$single_info['business_id']); ?>"><?php echo $single_info['business_id']; ?></a>
                            <?php } elseif( $single_info['type']==6 ) { ?>
                            有新的调度申请单需要审批，调度单号：<a target="_blank" href="<?php echo site_url('products/dispatch?keyword='.$single_info['business_id']); ?>"><?php echo $single_info['business_id']; ?></a>
                            <?php } ?>
                        </td>
                        <td>
                            <?php echo $single_info['create_eid']==0 ? '系统' : $single_info['create_name']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['create_time'] ? date('Y-m-d H:i:s',$single_info['create_time']) : ''; ?>
                        </td>
                        <td>
                            <?php echo $single_info['last_name']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['last_time'] ? date('Y-m-d H:i:s',$single_info['last_time']) : ''; ?>
                        </td>
                        <td row_id="<?php echo $single_info['id']; ?>">
                            <?php if( in_array($single_info['status'], array(1,2,3,4)) ){ ?>
                            <a href="<?php echo site_url('home/task/detail?task_id='.$single_info['id']); ?>" class="btn btn-xs btn-default op_btn">立即处理</a>
                            <?php } else { ?>
                            <a href="<?php echo site_url('home/task/detail?task_id='.$single_info['id']); ?>" class="btn btn-xs btn-default op_btn">查看处理</a>
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
        href: "?<?php echo 'status='.$params['status'].'&type='.$params['type'].'&page=';?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });

    $('#search_form select').change(function(event) {
        $('#search_form').submit();
    });
});
</script>