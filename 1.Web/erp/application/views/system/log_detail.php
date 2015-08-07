<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('system/system'); ?>">系统设置</a></li>
            <li><a href="<?php echo site_url('system/log'); ?>">日志列表</a></li>
            <li class="active"><?php echo $date; ?> 日志详情</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <?php if(!empty($logs)){ ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th style="width:40px;">
                            #
                        </th>
                        <th style="width:50px;">
                            系统
                        </th>
                        <th style="width:120px;">
                            请求时间
                        </th>
                        <th style="width:80px;">
                            IP
                        </th>
                        <th style="width:200px;">
                            API
                        </th>
                        <th style="width:60px;">
                            错误代码
                        </th>
                        <th style="width:200px;">
                            错误代码描述
                        </th>
                        <th style="width:100px;">
                            请求耗时（秒）
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $k=>$row) { ?>
                    <tr>
                        <td><?php echo ($k+1); ?></td>
                        <td title="<?php echo $row['os']; ?>"><?php echo isset($os_types[$row['os']]) ? $os_types[$row['os']] : 'Unknown'; ?></td>
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo $row['ip']; ?></td>
                        <td><?php echo $row['api']; ?></td>
                        <td><?php echo $row['err_no']; ?></td>
                        <td><?php echo $row['err_msg']; ?></td>
                        <td><?php echo $row['time']; ?></td>
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
});
</script>