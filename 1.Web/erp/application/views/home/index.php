
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('home/index'); ?>">我的桌面</a></li>
            <li class="active">系统概况</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">系统概况</h3>
            </div>
            <table class="table table-hover text-right">
                <tbody>
                    <tr>
                        <td class="col-md-4">
                            商品
                        </td>
                        <td>
                            <font color="red"><?php echo $system_info['product_num']; ?></font> 种
                            ( <a href="<?php echo site_url('products/product'); ?>">查看</a> )
                        </td>
                    </tr>
                    <tr>
                        <td>
                            原料
                        </td>
                        <td>
                            <font color="red"><?php echo $system_info['good_num']; ?></font> 种
                            ( <a href="<?php echo site_url('products/good'); ?>">查看</a> )
                        </td>
                    </tr>
                    <tr>
                        <td>
                            大客户
                        </td>
                        <td>
                            <font color="red"><?php echo $system_info['vip_user_num']; ?></font> 个
                            ( <a href="<?php echo site_url('vip/user'); ?>">查看</a> )
                        </td>
                    </tr>
                    <tr>
                        <td>
                            用户
                        </td>
                        <td>
                            <font color="red"><?php echo $system_info['user_num']; ?></font> 个
                            ( <a href="<?php echo site_url('user/user'); ?>">查看</a> )
                        </td>
                    </tr>
                    <tr>
                        <td>
                            网站
                        </td>
                        <td>
                            <font color="red"><?php echo $system_info['site_num']; ?></font> 个
                            ( <a href="<?php echo site_url('enterprise/site'); ?>">查看</a> )
                        </td>
                    </tr>
                    <tr>
                        <td>
                            员工
                        </td>
                        <td>
                            <font color="red"><?php echo $system_info['employee_num']; ?></font> 个
                            ( <a href="<?php echo site_url('employee/employee'); ?>">查看</a> )
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">订单概况</h3>
            </div>
            <table class="table table-hover text-right">
                <tbody>
                    <tr>
                        <td class="col-md-4">
                            全部订单
                        </td>
                        <td>
                            <font color="red"><?php echo $order_info['order_num']; ?></font> 个
                            ( <a href="<?php echo site_url('order/order'); ?>">查看</a> )
                        </td>
                    </tr>
                    <tr>
                        <td>
                            未支付订单
                        </td>
                        <td>
                            <font color="red"><?php echo $order_info['order_nopay_num']; ?></font> 个
                            ( <a href="<?php echo site_url('order/order').'?status=0'; ?>">查看</a> )
                        </td>
                    </tr>
                    <tr>
                        <td>
                            已支付订单
                        </td>
                        <td>
                            <font color="red"><?php echo $order_info['order_pay_num']; ?></font> 个
                            ( <a href="<?php echo site_url('order/order').'?status=1'; ?>">查看</a> )
                        </td>
                    </tr>
                    <tr>
                        <td>
                            大客户订单
                        </td>
                        <td>
                            <font color="red"><?php echo $order_info['vip_order_num']; ?></font> 个
                            ( <a href="<?php echo site_url('vip/order'); ?>">查看</a> )
                        </td>
                    </tr>
                    <tr>
                        <td>
                            大客户新订单
                        </td>
                        <td>
                            <font color="red"><?php echo $order_info['vip_order_nopay_num']; ?></font> 个
                            ( <a href="<?php echo site_url('vip/order').'?status=0'; ?>">查看</a> )
                        </td>
                    </tr>
                    <tr>
                        <td>
                            大客户已支付订单
                        </td>
                        <td>
                            <font color="red"><?php echo $order_info['vip_order_pay_num']; ?></font> 个
                            ( <a href="<?php echo site_url('vip/order').'?status=1'; ?>">查看</a> )
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">最新支付成功订单</h3>
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
                        <th style="width:100px;">
                            所属网站
                        </th>
                        <th style="width:140px;">
                            订单号
                        </th>
                        <th style="width:100px;">
                            订单总价
                        </th>
                        <th style="width:120px;">
                            所属用户
                        </th>
                        <th style="width:100px;">
                            物流状态
                        </th>
                        <th style="width:120px;">
                            订单生成时间
                        </th>
                        <th style="width:100px;">
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
                            <?php echo $sites[$single_info['site_id']]['name']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['id']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['price']; ?>
                        </td>
                        <td>
                            <?php echo isset($users[$single_info['uid']]) ? $users[$single_info['uid']] : '未知用户'; ?>
                        </td>
                        <td>
                            <?php echo $single_info['delivery_type']==0 ? '用户自提' : '惠生活物流'; ?>
                        </td>
                        <td>
                            <?php echo date('Y-m-d H:i:s',$single_info['create_time']); ?>
                        </td>
                        <td style="padding:6px;">
                            <a class="btn btn-success btn-xs" href="<?php echo site_url('order/order/detail?order_id='.$single_info['id']); ?>" >查看订单详情</a>                            
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
        if( confirm('此操作不可恢复，您确定要删除此用户组吗？') ){
            var tThis = $(this);
            var row_id = tThis.parent('td').attr('row_id');
            if(row_id){
                $.get("<?php echo site_url('user/group/delete'); ?>", {'id':row_id,'status':status}, function(data){
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