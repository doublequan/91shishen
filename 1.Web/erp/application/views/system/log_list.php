
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('system/system'); ?>">系统设置</a></li>
            <li><a href="<?php echo site_url('system/log'); ?>">日志列表</a></li>
            <li class="active">日志列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <?php if(!empty($paths)){ ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th style="width:50px;">
                            #
                        </th>
                        <th style="width:200px;">
                            日志时间
                        </th>
                        <th style="width:600px;">
                            操作
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($paths as $k=>$row) { ?>
                    <tr>
                        <td>
                            <?php echo ($k+1); ?>
                        </td>
                        <td>
                            <?php echo date('Y-m-d',strtotime($row['day'])); ?>
                        </td>
                        <td class="text-left">
                            <?php foreach( $row['hours'] as $hour ){ ?>
                            <a href="<?php echo site_url('system/log/detail?day='.$row['day'].'&hour='.$hour); ?>" ><?php echo str_replace($row['day'], '', $hour); ?>点 </a>
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

<script type="text/javascript">
$(function(){
});
</script>