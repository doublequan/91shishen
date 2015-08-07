
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('order/order'); ?>">订单管理</a></li>
            <li><a href="<?php echo site_url('order/card'); ?>">会员卡管理</a></li>
            <li class="active">会员卡订单列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('order/card'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">订单状态：</p>
                    </div>
                    <div class="form-group">
                        <select name="status" class="form-control input-sm" id="status">
                            <option value="0">全部订单</option>
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
                        <th width="5%">
                            订单编号
                        </th>
                        <th width="10%">
                            用户名
                        </th>
                        <th width="5%">
                            性别
                        </th>
                        <th width="10%">
                            手机号码
                        </th>
                        <th width="20%">
                            详细地址
                        </th>
                        <th width="15%">
                            申购详情
                        </th>
                        <th width="15%">
                            申请时间
                        </th>
                        <th width="5%">
                            状态
                        </th>
                        <th width="15%">
                            操作
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach ($results as $k=>$row) {
                    ?>
                    <tr>
                        <td>
                            <?php echo $row['id']; ?></a>
                        </td>
                        <td>
                            <?php echo $row['username']; ?>
                        </td>
                        <td>
                            <?php echo $row['gender']==1 ? '男' : ($row['gender']==2 ? '女' : '未知'); ?>
                        </td>
                        <td>
                            <?php echo $row['mobile']; ?>
                        </td>
                        <td>
                            <?php echo $row['address']; ?>
                        </td>
                        <td>
                            <?php if( $row['details'] ){ ?>
                            <?php foreach( $row['details'] as $k=>$v ){ ?>
                            <?php echo $k; ?>元 ： <?php echo $v; ?>张<br />
                            <?php } ?>
                            <?php } ?>
                        </td>
                        <td>
                            <?php echo $row['create_time'] ? date('Y-m-d H:i:s',$row['create_time']) : ''; ?>
                        </td>
                        <td>
                            <?php echo isset($statusMap[$row['status']]) ? $statusMap[$row['status']] : '已删除'; ?>
                        </td>
                        <td row_id="<?php echo $row['id']; ?>" style="padding:6px;">
                            <?php if($row['status'] == 1) { ?>
                            <button type="button" class="btn btn-xs btn-info op_btn" status="2">确认订单</button>
                            <button type="button" class="btn btn-xs btn-success op_btn" status="3">完成订单</button>
                            <?php }elseif($row['status'] == 2) { ?>
                            <button type="button" class="btn btn-xs btn-success op_btn" status="3">完成订单</button>
                            <?php } ?>
                            <a href="javascipt:viod(0);" class="btn btn-xs btn-danger delete_link">删除</a>
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

    $(".op_btn").click(function(){
        var row_id = $(this).parent('td').attr('row_id');
        var status = $(this).attr('status');
        
        if(row_id && status){
            $.post("<?php echo site_url('order/card/updateStatus'); ?>", {'id':row_id,'status':status}, function(data){
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

    $(".delete_link").click(function(){
        if( confirm('此操作不可恢复，您确定要删除此会员卡订单吗？') ){
            var tThis = $(this);
            var row_id = tThis.parent('td').attr('row_id');
            if(row_id){
                $.get("<?php echo site_url('order/card/delete'); ?>", {'id':row_id}, function(data){
                    var data = $.parseJSON(data);
                    if(data && data.err_no==0){
                        tThis.parent().parent().hide(600, function(){
                            tThis.parent().parent().remove();
                            alert('操作成功！');
                        });
                    } else {
                        alert('操作失败！');
                    }
                });
            }
        }
    });
});
</script>