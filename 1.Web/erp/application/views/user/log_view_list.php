<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('user/user'); ?>">用户管理</a></li>
            <li><a href="<?php echo site_url('user/log/score'); ?>">用户数据统计</a></li>
            <li class="active">产品访问量统计</li>
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
                            序号
                        </th>
                        <th style="width:300px;">
                            产品名称
                        </th>
                        <th style="width:100px;">
                            访问量
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
                            <?php echo $single_info['product_name']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['num']; ?>
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
