<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('vip/product'); ?>">大客户管理</a></li>
            <li><a href="<?php echo site_url('vip/product'); ?>">大客户商品</a></li>
            <li class="active">大客户定制商品列表</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('vip/custom'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">状态：</p>
                    </div>
                    <div class="form-group">
                        <select name="status" class="form-control input-sm">
                            <?php foreach ($statusMap as $key => $value) { ?>
                            <option value="<?php echo $key; ?>" <?php echo $key==$params['status']?'selected':''; ?>><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <p class="form-control-static input-sm">&nbsp;&nbsp;定制单编号：</p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control input-sm" name="keyword" value="<?php echo $params['keyword']; ?>">
                    </div>
                    
                    <div class="form-group" style="width:80px;">
                         <input type="submit" class="btn btn-primary btn-sm btn-block" value="查 询">
                    </div>

                    <a class="btn btn-primary btn-sm" id="add_form_btn" href="#add_form_page" kesrc="#add_form_page">添加定制商品</a>
                </form>
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
                        <th style="width:90px;">
                            定制单编号
                        </th>
                        <th style="width:150px;">
                            所属公司
                        </th>
                        <th style="width:70px;">
                            所属用户
                        </th>
                        <th style="width:110px;">
                            生成时间
                        </th>
                        <th style="width:70px;">
                            处理员工
                        </th>
                        <th style="width:110px;">
                            处理时间
                        </th>
                        <th style="width:60px;">
                            状态
                        </th>
                        <th style="width:80px;">
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
                            <?php echo $single_info['show_id']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['company_name']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['username']; ?>
                        </td>
                         <td>
                            <?php echo date('Y-m-d H:i:s',$single_info['create_time']); ?>
                        </td>
                       <td>
                            <?php echo $single_info['deal_name']; ?>
                        </td>
                        <td>
                            <?php echo empty($single_info['deal_time'])?'': date('Y-m-d H:i:s',$single_info['deal_time']) ?>                          
                        </td>
                        <td>
                            <?php 
                            switch($single_info['status']){
                                case 1:
                                    echo '<span class="label label-info">新建</span>';
                                    break;
                                case 2:
                                    echo '<span class="label label-success">已确认</span>';
                                    break;
                                case 3:
                                    echo '<span class="label label-default">已删除</span>';
                                    break;
                                default:
                                    echo '<span class="label label-default">未知</span>';
                                    break;
                            }
                            ?>
                        </td>
                        <td row_id="<?php echo $single_info['id']; ?>" style="padding:6px;">
                            <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-xs btn-success check_form_link">查看/编辑</a>
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

<iframe src="" id="add_form_page" style="height:600px;"></iframe>

<script src="<?php echo base_url('static/js/jquery.fancybox.js'); ?>" type="text/javascript"></script>
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
        href: "?<?php echo 'status='.$params['status'].'&keyword='.$params['keyword'].'&page=';?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });

    $('#search_form select').change(function(event) {
        $('#search_form').submit();
    });

    $('a#add_form_btn').click(function(){
        $('iframe#add_form_page').attr('src', "<?php echo site_url('vip/custom/add'); ?>");
        $("a#add_form_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            'afterClose': function(){
                window.parent.location.reload();
            },
        });
    });

    $("a.check_form_link").click(function() {
        var row_id = $(this).parent('td').attr('row_id');   
        if(row_id){
            $('iframe#add_form_page').attr('src', "<?php echo site_url('vip/custom/edit'); ?>" + '?id=' + row_id);
            $("a.check_form_link").fancybox({
                'hideOnContentClick': true,
                'padding':0,
                'afterClose': function(){
                    window.parent.location.reload();
                },
            });
        }
    });

});



</script>