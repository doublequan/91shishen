
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('order/order'); ?>">订单管理</a></li>
            <li><a href="<?php echo site_url('order/comment'); ?>">评论管理</a></li>
            <li class="active">评论列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('order/comment'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">评论状态：</p>
                    </div>
                    <div class="form-group">
                        <select name="status" class="form-control input-sm" id="status">
                            <option value="-1">全部评论</option>
                        <?php foreach ($statusMap as $k=>$v) { ?>
                            <option value="<?php echo $k; ?>"<?php echo $k==$params['status'] ? ' selected' : ''; ?>><?php echo trim($v); ?></option>
                        <?php } ?>
                        </select>
                    </div>
                    
                    <div class="form-group" style="width: 80px;">
                         <input type="submit" class="btn btn-primary btn-sm btn-block" value="查 询">
                    </div>
                </form>
            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th style="width:3%;">
                            #
                        </th>
                        <th style="width:12%;">
                            订单编号
                        </th>
                        <th style="width:15%;">
                            商品名称
                        </th>
                        <th style="width:10%;">
                            用户名
                        </th>
                        <th style="width:7%;">
                            评价等级
                        </th>
                        <th style="width:4%;">
                            评分
                        </th>
                        <th style="width:20%;">
                            评论详情
                        </th>
                        <th style="width:9%;">
                            评论时间
                        </th>
                        <th style="width:7%;">
                            状态
                        </th>
                        <th style="width:13%;">
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
                        <td>
                            <a href="<?php echo site_url('order/order/detail').'?order_id='.$single_info['order_id']; ?>" target="_blank"><?php echo $single_info['order_id']; ?></a>
                        </td>
                        <td>
                            <?php echo isset($products[$single_info['product_id']]) ? $products[$single_info['product_id']] : '商品关联错误'; ?>
                        </td>
                        <td>
                            <?php echo $single_info['username']; ?>
                        </td>
                        <td>
                            <?php 
                            switch($single_info['level']){
                                case 1:
                                    echo '<span class="label label-success">好评</span>';
                                    break;
                                case 2:
                                    echo '<span class="label label-warning">中评</span>';
                                    break;
                                case 3:
                                    echo '<span class="label label-danger">差评</span>';
                                    break;
                                default:
                                    echo '<span class="label label-default">未知</span>';
                                    break;
                            }
                            ?>
                        </td>
                        <td>
                            <?php echo $single_info['score']; ?>
                        </td>
                        <td>
                            <?php if(empty($single_info['content'])) {?>
                            无
                            <?php }else{ ?>
                            <a href="javascript:void(0);" class="check_link" title="评论详情" data-content="<?php echo $single_info['content']; ?>" ><?php echo mb_substr($single_info['content'], 0, 14).'..'; ?></a>
                            <?php } ?>
                        </td>
                        <td>
                            <?php echo date('Y-m-d',$single_info['create_time']); ?>
                        </td>
                        <td>
                            <?php 
                            switch($single_info['status']){
                                case 0:
                                    echo '<span class="label label-warning">未审核</span>';
                                    break;
                                case 1:
                                    echo '<span class="label label-success">已通过</span>';
                                    break;
                                case 2:
                                    echo '<span class="label label-warning">未通过</span>';
                                    break;
                                case 3:
                                    echo '<span class="label label-danger">已删除</span>';
                                    break;
                                default:
                                    echo '<span class="label label-default">未知</span>';
                                    break;
                            }
                            ?>
                        </td>
                        <td row_id="<?php echo $single_info['id']; ?>" style="padding:6px;">
                            <?php if($single_info['status']==0){ ?>
                            <button type="button" class="btn btn-xs btn-success op_btn" status="1">审核通过</button>
                            <button type="button" class="btn btn-xs btn-danger op_btn" status="2">不通过</button>
                            <?php }elseif($single_info['status'] == 1) { ?>
                            <button type="button" class="btn btn-xs btn-success op_btn" status="2">不通过</button>
                            <button type="button" class="btn btn-xs btn-danger op_btn" status="3">删除</button>
                            <?php }elseif($single_info['status'] == 2) { ?>
                            <button type="button" class="btn btn-xs btn-success op_btn" status="1">审核通过</button>
                            <button type="button" class="btn btn-xs btn-danger op_btn" status="3">删除</button>
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
        href: "?<?php echo 'status='.$params['status'].'&page=';?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });
    $('#status').change(function(event) {
        $('#search_form').submit();
    });
    $('.check_link').popover({
        content : $(this).attr('data-content'),
        html : true,
        trigger : 'hover',
        placement: 'left',
        title : $(this).attr('title'),
    });

    $(".op_btn").click(function(){
        var row_id = $(this).parent('td').attr('row_id');
        var status = $(this).attr('status');
        
        if(row_id && status){
            $.post("<?php echo site_url('order/comment/updateStatus'); ?>", {'id':row_id,'status':status}, function(data){
                var data = $.parseJSON(data);
                if(data && data.err_no==0){
                    alert('操作成功！');
                } else {
                    alert('操作失败！');
                }
                window.location.reload();
            });
        }
    });
});



</script>