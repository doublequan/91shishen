<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('user/user'); ?>">用户管理</a></li>
            <li><a href="<?php echo site_url('user/user'); ?>">用户信息</a></li>
            <li class="active">用户组列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading" style="height:50px;">
                <form class="form-inline" action="<?php echo site_url('user/user'); ?>" method="get" id="search_form">
                    <a class="btn btn-primary btn-sm" id="add_form_btn" href="#add_form_page" kesrc="#add_form_page">添加用户组</a>
                </form>
            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th style="width:40px;">
                            #
                        </th>
                        <th style="width:50px;">
                            用户组编号
                        </th>
                        <th style="width:200px;">
                            用户组名称
                        </th>
                        <th style="width:150px;">
                            用户组折扣率
                        </th>
                        <th style="width:150px;">
                            用户组积分范围
                        </th>
                        <th style="width:140px;">
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
                            <?php echo $single_info['id']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['name']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['discount'].'%'; ?>
                        </td>
                        <td>
                            <?php echo $single_info['min']; ?> ~ <?php echo $single_info['max']; ?>
                        </td>
                        <td row_id="<?php echo $single_info['id']; ?>">
                            <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-xs btn-success check_form_link">查看/编辑</a>
                            <a href="#" class="btn btn-xs btn-danger delete_link">删 除</a>
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

<iframe src="" id="add_form_page" style="height:320px;"></iframe>

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
        href: "?<?php echo 'page=';?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });

    $('a#add_form_btn').click(function(){
        $('iframe#add_form_page').attr('src', "<?php echo site_url('user/group/add'); ?>");
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
            $('iframe#add_form_page').attr('src', "<?php echo site_url('user/group/edit'); ?>" + '?id=' + row_id);
            $("a.check_form_link").fancybox({
                'hideOnContentClick': true,
                'padding':0,
                'afterClose': function(){
                    window.parent.location.reload();
                },
            });
        }
    });

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