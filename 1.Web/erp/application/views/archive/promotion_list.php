<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('archive/archive'); ?>">内容管理</a></li>
            <li><a href="<?php echo site_url('archive/promotion'); ?>">促销管理</a></li>
            <li class="active">促销活动专题列表</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading" style="height:50px;">
                <a class="btn btn-primary btn-sm" id="add_form_btn" href="<?php echo site_url('archive/promotion/add'); ?>">新建活动专题</a>
            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th style="width:5%;">
                            编号
                        </th>
                        <th style="width:20%;">
                            活动名称
                        </th>
                        <th style="width:10%;">
                            触发条件
                        </th>
                        <th style="width:10%;">
                            限制条件
                        </th>
                        <th style="width:10%;">
                            回馈内容
                        </th>
                        <th style="width:15%;">
                            活动开始日期
                        </th>
                        <th style="width:15%;">
                            活动结束日期
                        </th>
                        <th style="width:15%;">
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
                            <?php echo ($k+1); ?>
                        </td>
                        <td>
                            <?php echo $row['name']; ?>
                        </td>
                        <td>
                            <?php echo $row['trigger']==1 ? '用户下单' : ( $row['trigger']==2 ? '用户登录' : ( $row['trigger']==3 ? '用户注册' : '' ) ); ?>
                        </td>
                        <td>
                            <?php echo $row['limit_type']==1 ? '消费金额' : ( $row['limit_type']==2 ? '指定商品' : ( $row['limit_type']==3 ? '加钱换购' : '' ) ); ?>
                        </td>
                        <td>
                            <?php echo $row['give_type']==1 ? '现金回馈' : ( $row['give_type']==2 ? '商品回馈' : ( $row['give_type']==3 ? '积分回馈' : '' ) ); ?>
                        </td>
                        <td>
                            <?php echo $row['day_start'] ? $row['day_start'] : '不限'; ?>
                        </td>
                        <td>
                            <?php echo $row['day_end'] ? $row['day_end'] : '不限'; ?>
                        </td>
                        <td row_id="<?php echo $row['id']; ?>" style="padding:6px;">
                            <a class="btn btn-xs btn-success" href="<?php echo site_url('archive/promotion/edit').'?id='.$row['id']; ?>">查看/编辑</a>
                            <a class="btn btn-xs btn-danger delete_link" href="javascript:void(0);">删除</a>
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

<script type="text/javascript">
$(function(){
    $(".delete_link").click(function(){
        var tThis = $(this);
        if(confirm("此删除不可恢复，请谨慎操作。您确定删除该促销活动吗？")){
            var id = tThis.parent('td').attr('row_id');
            if(id){
                $.get("<?php echo site_url('archive/promotion/delete'); ?>", {'id':id}, function(data){
                    var data = $.parseJSON(data);
                    if(data && data.err_no==0){
                        tThis.parent().parent().hide(600, function(){
                            tThis.parent().parent().remove();
                            alert('删除成功！');
                        });
                    } else {
                        alert('删除失败！'+err_msg);
                    }
                });
            }
        }
    });
});
</script>