<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('user/user'); ?>">用户管理</a></li>
            <li><a href="<?php echo site_url('user/user'); ?>">用户信息</a></li>
            <li class="active">平安会员卡绑定</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading" style="height:50px;">
                <form class="form-inline" action="<?php echo site_url('user/card/pingan'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">搜索：</p>
                    </div>
                    <div class="form-group">
                         <input type="text" class="form-control input-sm" name="keyword" value="<?php echo $params['keyword']; ?>" placeholder="会员卡卡号" />
                    </div>
                    <div class="form-group" style="width:80px;">
                        <input type="submit" class="btn btn-primary btn-sm btn-block" value="查 询">
                    </div>
                </form>
            </div>
            <?php if(!empty($results)){ ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="35%">卡号</th>
                        <th width="20%">是否绑定</th>
                        <th width="20%">绑定用户</th>
                        <th width="20%">绑定时间</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $k=>$row) {?>
                    <tr>
                        <td><?php echo ($k+1); ?></td>
                        <td><?php echo $params['keyword'] ? str_replace($params['keyword'], '<font color="red">'.$params['keyword'].'</font>', $row['cardno']) : $row['cardno']; ?></td>
                        <td><?php echo $row['is_bind']==1 ? '已绑定' : '未使用'; ?></td>
                        <td><?php echo $row['uid'] && isset($users[$row['uid']]) ? $users[$row['uid']]['username'] : ''; ?></td>
                        <td><?php echo $row['bind_time'] ? date('Y-m-d H:i:s',$row['bind_time']) : ''; ?></td>
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
        href: "?<?php echo '&page=';?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });
});
</script>
