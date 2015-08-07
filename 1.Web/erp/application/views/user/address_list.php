<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('user/user'); ?>">用户管理</a></li>
            <li><a href="<?php echo site_url('user/user'); ?>">用户信息</a></li>
            <li class="active">收货址列表</li>
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
                        <th style="width:150px;">
                            所属用户
                        </th>
                        <th style="width:120px;">
                            地区
                        </th>
                        <th style="width:200px;">
                            地址
                        </th>
                        <th style="width:60px;">
                            邮编
                        </th>
                        <th style="width:60px;">
                            收货人
                        </th>
                        <th style="width:80px;">
                            座机电话
                        </th>
                        <th style="width:80px;">
                            移动电话
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
                            <?php echo isset($areas[$single_info['prov']]) ? $areas[$single_info['prov']] : '未知省份'; ?> -
                            <?php echo isset($areas[$single_info['city']]) ? $areas[$single_info['city']] : '未知城市'; ?> -
                            <?php echo isset($areas[$single_info['district']]) ? $areas[$single_info['district']] : '未知区县'; ?>
                        </td>
                        <td>
                            <?php echo $single_info['address']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['zip']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['receiver']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['tel']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['mobile']; ?>
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
