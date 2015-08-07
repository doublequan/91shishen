<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="">数据统计</a></li>
            <li><a href="">销售数据</a></li>
            <li><a href="">销售额统计</a></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('stat/invite/amount'); ?>" method="get" id="search_form">
                    <input type="hidden" name="act" id="act" value="">
                    <div class="form-group" style="width:100px;margin-left:5px;">
                         <input type="button" class="btn btn-info btn-sm btn-block" id="export" value="导出Excel">
                    </div>
                </form>
            </div>
            <?php if(!empty($results)){ ?>
                <table class="table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th width="10%">序号</th>
                            <th width="15%">员工姓名</th>
                            <th width="25%">用户订单数</th>
                            <th width="25%">用户消费金额</th>
                            <th width="25%">用户订单单价</th>
                        </tr>

                    </thead>
                    <tbody>
                        <?php $i=0; ?>
                        <?php foreach ($results as $k=>$row) { ?>
                        <tr>
                            <td><?php echo ++$i; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['total']; ?></td>
                            <td>￥<?php echo sprintf('%.2f', $row['price']); ?></td>
                            <td>￥<?php echo sprintf('%.2f', $row['price']/$row['total']); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php }else{ ?>
            <div class="alert alert-warning col-md-12 text-center" role="alert">查询结果为空！</div>
            <?php } ?>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#export').click(function(){
        $('#act').val('export');
        $('#search_form').submit();
        $('#act').val('');
    });
});
</script>