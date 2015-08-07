
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb" style="margin-bottom:0;">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('home/index'); ?>">我的桌面</a></li>
            <li><a href="<?php echo site_url('home/task?status=1'); ?>">任务管理</a></li>
            <li class="active">任务详情</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12 form_body" style="padding-top:0px;">
        <div class="panel-body">
            <div class="row">
                <p class="bg-info form-square-title">任务基础信息</p>
                <table class="table table-bordered table-hover">
                    <tbody>
                        <tr>
                            <th width="10%" class="text-right">任务编号</th>
                            <td width="15%"><?php echo $single['id']; ?></td>
                            <th width="10%" class="text-right">任务状态</th>
                            <td width="65%"><?php echo isset($task_status_types[$single['status']]) ? $task_status_types[$single['status']] : '未知任务状态'; ?></td>
                        </tr>
                        <tr>
                            <th class="text-right">任务类型</th>
                            <td><?php echo isset($task_types[$single['type']]) ? $task_types[$single['type']] : '未知任务类型'; ?></td>
                            <th class="text-right">任务详情</th>
                            <td>
                            <?php if( in_array($single['status'], array(1,2,3,4)) ){ ?>
                                <?php if( $single['type']==1 ){ ?>
                                有新的用户订单需要确认，订单号：<a target="_blank" href="<?php echo site_url('order/order/detail?order_id='.$single['business_id']); ?>"><?php echo $single['business_id']; ?></a>
                                <?php } elseif( $single['type']==2 ) { ?>
                                有新的大客户订单需要确认，订单号：<a target="_blank" href="<?php echo site_url('vip/order/detail?order_id='.$single['business_id']); ?>"><?php echo $single['business_id']; ?></a>
                                <?php } elseif( $single['type']==3 ) { ?>
                                有新的大客户定制需求需要确认，定制单号：<a target="_blank" href="<?php echo site_url('vip/custom?status=0&keyword='.$single['business_id']); ?>"><?php echo $single['business_id']; ?></a>
                                <?php } elseif( $single['type']==4 ) { ?>
                                有新的财务申请单需要审批，财务单号：<a target="_blank" href="<?php echo site_url('products/purchase_finance/detail?id='.$single['business_id']); ?>"><?php echo $single['business_id']; ?></a>
                                <?php } elseif( $single['type']==5 ) { ?>
                                有新的采购申请单需要审批，采购单号：<a target="_blank" href="<?php echo site_url('products/purchase/detail?id='.$single['business_id']); ?>"><?php echo $single['business_id']; ?></a>
                                <?php } elseif( $single['type']==6 ) { ?>
                                有新的调度申请单需要审批，调度单号：<a target="_blank" href="<?php echo site_url('products/dispatch/detail?id='.$single['business_id']); ?>"><?php echo $single['business_id']; ?></a>
                                <?php } ?>
                            <?php } else { ?>
                                <?php if( $single['type']==1 ){ ?>
                                用户订单号：<a target="_blank" href="<?php echo site_url('order/order/detail?order_id='.$single['business_id']); ?>"><?php echo $single['business_id']; ?></a>
                                <?php } elseif( $single['type']==2 ) { ?>
                                大客户订单号：<a target="_blank" href="<?php echo site_url('vip/order/detail?order_id='.$single['business_id']); ?>"><?php echo $single['business_id']; ?></a>
                                <?php } elseif( $single['type']==3 ) { ?>
                                大客户定制单号：<a target="_blank" href="<?php echo site_url('vip/custom?status=0&keyword='.$single['business_id']); ?>"><?php echo $single['business_id']; ?></a>
                                <?php } elseif( $single['type']==4 ) { ?>
                                财务申请单单号：<a target="_blank" href="<?php echo site_url('products/purchase_finance/detail?id='.$single['business_id']); ?>"><?php echo $single['business_id']; ?></a>
                                <?php } elseif( $single['type']==5 ) { ?>
                                采购申请单单号：<a target="_blank" href="<?php echo site_url('products/purchase/detail?id='.$single['business_id']); ?>"><?php echo $single['business_id']; ?></a>
                                <?php } elseif( $single['type']==6 ) { ?>
                                调度申请单单号：<a target="_blank" href="<?php echo site_url('products/dispatch/detail?id='.$single['business_id']); ?>"><?php echo $single['business_id']; ?></a>
                                <?php } ?>
                            <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right">任务创建人</th>
                            <td><?php echo $single['create_name']; ?></td>
                            <th class="text-right">任务创建时间</th>
                            <td colspan="3"><?php echo $single['create_time'] ? date('Y-m-d H:i:s',$single['create_time']) : ''; ?></td>
                        </tr>
                        <tr>
                            <th class="text-right">最后处理员工</th>
                            <td><?php echo $single['last_name']; ?></td>
                            <th class="text-right">最后处理时间</th>
                            <td colspan="3"><?php echo $single['last_time'] ? date('Y-m-d H:i:s',$single['last_time']) : ''; ?></td>
                        </tr>
                    </tbody>
                </table>
                <p class="bg-info form-square-title">任务处理</p>
                <div class="panel-heading form-inline" style="padding:0px 0px 10px;">
                <?php if( in_array($single['status'], array(1,2,3,4)) ){ ?>
                    <?php if( $single['type']==1 ){ ?>
                    <a class="btn btn-success btn-sm dealTask" next_status="5" only_task="0">确认用户订单和当前任务</a>
                    <a class="btn btn-success btn-sm dealTask" next_status="5" only_task="1">仅确认当前任务</a>
                    <?php } elseif( $single['type']==2 ) { ?>
                    <a class="btn btn-success btn-sm dealTask" next_status="5" only_task="0">确认大客户订单和当前任务</a>
                    <a class="btn btn-success btn-sm dealTask" next_status="5" only_task="1">仅确认当前任务</a>
                    <?php } elseif( $single['type']==3 ) { ?>
                    <a class="btn btn-success btn-sm dealTask" next_status="5" only_task="0">确认大客户定制需求和当前任务</a>
                    <a class="btn btn-success btn-sm dealTask" next_status="5" only_task="1">仅确认当前任务</a>
                    <?php } elseif( $single['type']==4 ) { ?>
                    <a class="btn btn-success btn-sm dealTask" next_status="5" only_task="0">确认财务申请和当前任务</a>
                    <a class="btn btn-success btn-sm dealTask" next_status="5" only_task="1">仅确认当前任务</a>
                    <?php } elseif( $single['type']==5 ) { ?>
                    <a class="btn btn-success btn-sm dealTask" next_status="5" only_task="0">确认采购申请和当前任务</a>
                    <a class="btn btn-success btn-sm dealTask" next_status="5" only_task="1">仅确认当前任务</a>
                    <?php } elseif( $single['type']==6 ) { ?>
                    <a class="btn btn-success btn-sm dealTask" next_status="5" only_task="0">确认调度申请和当前任务</a>
                    <a class="btn btn-success btn-sm dealTask" next_status="5" only_task="1">仅确认当前任务</a>
                    <?php } ?>
                    <?php if( $single['status']!=2 ){ ?>
                    <a class="btn btn-primary btn-sm dealTask" next_status="2" only_task="1">设置为进行中</a>
                    <?php } ?>
                    <?php if( $single['status']!=3 ){ ?>
                    <a class="btn btn-primary btn-sm dealTask" next_status="3" only_task="1">设置为待处理</a>
                    <?php } ?>
                    <?php if( $single['status']!=4 ){ ?>
                    <a class="btn btn-warning btn-sm dealTask" next_status="4" only_task="1">延期当前任务</a>
                    <?php } ?>
                    <a class="btn btn-danger btn-sm dealTask" next_status="6" only_task="1">放弃当前任务</a>
                    <div class="form-group text-right" style="float:right;">
                        任务移交给：
                        <select name="eid" id="eid" class="form-control input-sm">
                            <?php foreach ($employees as $k=>$row) { ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['username']; ?></option>
                            <?php } ?>
                        </select>     
                        <a class="btn btn-info btn-sm" status="21" id="transfer">确认移交</a> 
                    </div>
                <?php } else { ?>
                当前不可操作
                <?php } ?>
                </div>
                <p class="bg-info form-square-title">任务操作历史</p>
                <table class="table table-bordered table-hover table-center text-center">
                    <thead>
                        <tr>
                            <th width="5%">序号</th>
                            <th width="15%">操作</th>
                            <th width="50%">描述</th>
                            <th width="15%">操作人</th>
                            <th width="15%">操作时间</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if( $actions ){ ?>
                        <?php foreach ($actions as $k=>$row) { ?>
                        <tr>
                            <td><?php echo ($k+1); ?></td>
                            <td><?php echo isset($task_action_types[$row['action']]) ? $task_action_types[$row['action']] : '未知操作'; ?></td>
                            <td><?php echo $row['des']; ?></td>
                            <td><?php echo $row['create_name']; ?></td>
                            <td><?php echo date('Y-m-d H:i:s',$row['create_time']); ?></td>
                        </tr>
                        <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('static/js/jquery.fancybox.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/bootstrap-datetimepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function($) {
    $('.dealTask').click(function(){
        var next_status = parseInt($(this).attr('next_status'));
        var only_task = parseInt($(this).attr('only_task'));
        var params = {
            'id'            : '<?php echo $single["id"]; ?>',
            'type'          : '<?php echo $single["type"]; ?>',
            'next_status'   : next_status,
            'only_task'     : only_task
        };
        $.ajax({
            type: 'POST',
            url:  '<?php echo site_url("home/task/deal"); ?>',
            data: params,
            dataType: 'json',
            success: function(msg){
                if( msg.err_no==0 ){
                    alert('操作成功');
                    parent.location.reload();
                } else {
                    alert(msg.err_msg);
                }
            }
        });
    });

    $('#transfer').click(function(){
        var eid = $('#eid').val();
        var params = {
            'id'    : '<?php echo $single["id"]; ?>',
            'eid'   : eid,
        };
        if(eid){
            if(window.confirm('确认移交任务？')){
                $.post("<?php echo site_url('home/task/transfer'); ?>", params, function(data){
                    var data = $.parseJSON(data);
                    if(data && data.error){
                        alert(data.msg);
                    }
                    else{
                        alert("操作成功！");
                    }
                    window.location.reload();
                });
            }
        }

    });
});

</script>
