<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('vip/product'); ?>">大客户管理</a></li>
            <li><a href="<?php echo site_url('vip/user'); ?>">大客户用户</a></li>
            <li class="active">大客户用户列表</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('vip/user'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">公司名称：</p>
                    </div>
                    <div class="form-group">
                        <select name="company_id" class="form-control input-sm">
                            <option value="">全部</option>
                            <?php foreach ($companys as $value) { ?>
                            <option value="<?php echo $value['id']; ?>" <?php echo $value['id']==$params['company_id']?'selected':''; ?>><?php echo $value['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <a class="btn btn-primary btn-sm" id="add_form_btn" href="#add_form_page" kesrc="#add_form_page">添加大客户用户</a>
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
                        <th style="width:120px;">
                            用户名
                        </th>
                        <th style="width:120px;">
                            所属公司
                        </th>
                        <th style="width:70px;">
                            折扣率
                        </th>
                        <th style="width:90px;">
                            手机号
                        </th>
                        <th style="width:90px;">
                            职位
                        </th>
                        <th style="width:80px;">
                            招商员工
                        </th>
                        <th style="width:80px;">
                            负责员工
                        </th>
                        <th style="width:70px;">
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
                            <?php echo $single_info['username']; ?>
                        </td>
                        <td>
                            <?php echo isset($companys[$single_info['company_id']]) ? $companys[$single_info['company_id']]['name'] : '公司数据错误'; ?>
                        </td>
                        <td>
                            <?php echo $single_info['discount'].'%'; ?>
                        </td>
                        <td>
                            <?php echo $single_info['mobile']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['position']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['deal_name']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['charge_name']; ?>
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

<iframe src="" id="add_form_page" style="height:500px;"></iframe>

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
        href: "?<?php echo 'company_id='.$params['company_id'].'&page=';?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });

    $('#search_form select').change(function(event) {
        $('#search_form').submit();
    });

    $('a#add_form_btn').click(function(){
        $('iframe#add_form_page').attr('src', "<?php echo site_url('vip/user/add'); ?>");
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
            $('iframe#add_form_page').attr('src', "<?php echo site_url('vip/user/edit'); ?>" + '?id=' + row_id);
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