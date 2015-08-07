<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('user/user'); ?>">用户管理</a></li>
            <li><a href="<?php echo site_url('user/user'); ?>">用户信息</a></li>
            <li class="active">用户设备列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <?php 
                if(!empty($results)){ 
            ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th style="width:40px;">
                            #
                        </th>
                        <th style="width:80px;">
                            所属用户
                        </th>
                        <th style="width:120px;">
                            设备ID
                        </th>
                        <th style="width:60px;">
                            Push
                        </th>
                        <th style="width:80px;">
                            系统类型
                        </th>
                        <th style="width:80px;">
                            系统版本
                        </th>
                        <th style="width:80px;">
                            APP版本
                        </th>
                        <th style="width:80px;">
                            设备品牌
                        </th>
                        <th style="width:80px;">
                            设备分辨率
                        </th>
                        <th style="width:80px;">
                            网络类型
                        </th>
                        <th style="width:120px;">
                            最后使用时间
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
                            <?php echo isset($users[$single_info['uid']]) ? $users[$single_info['uid']] : '未知用户'; ?>
                        </td>
                        <td>
                            <?php echo $single_info['deviceid']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['pushtoken'] ? '开启' : '关闭'; ?>
                        </td>
                        <td>
                            <?php echo isset($os_types[$single_info['os']]) ? $os_types[$single_info['os']] : '未知系统'; ?>
                        </td>
                        <td>
                            <?php echo $single_info['os_str']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['sv']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['brand']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['screen']; ?>
                        </td>
                        <td>
                            <?php echo isset($net_types[$single_info['net']]) ? $net_types[$single_info['net']] : '未知系统'; ?>
                        </td>
                        <td>
                            <?php echo date('Y-m-d H:i:s',$single_info['modify_time']); ?>
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
        href: "?<?php echo '&page=';?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });
});
</script>
