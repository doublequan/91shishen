<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('products/product'); ?>">商品管理</a></li>
            <li><a href="<?php echo site_url('products/spec'); ?>">规格</a></li>
            <li class="active">规格列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('products/spec'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">规格类型：</p>
                    </div>
                    <div class="form-group">
                        <select name="type" class="form-control input-sm" id="type">
                            <?php foreach ($typeMap as $k=>$v) { ?>
                            <option value="<?php echo $k; ?>"<?php echo $k==$params['type'] ? ' selected' : ''; ?>><?php echo trim($v); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <a class="btn btn-primary btn-sm" id="add_form_btn" href="#add_form_page" kesrc="#add_form_page">新增规格</a>
                </form>
            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th style="width:40px;text-align:center;">
                            #
                        </th>
                        <th style="width:120px;">
                            规格名称
                        </th>
                        <th style="width:120px;">
                            计价单位
                        </th>
                        <th style="width:100px;">
                            创建员工
                        </th>
                        <th style="width:100px;">
                            添加时间
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
                            <?php echo $single_info['name']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['unit']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['create_name']; ?>
                        </td>
                        <td>
                            <?php echo date('Y-m-d H:i', $single_info['create_time']); ?>
                        </td>
                        <td spec_id="<?php echo $single_info['id']; ?>" style="padding:6px;">
                            <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-xs btn-success check_form_link">编 辑</a>
                            <a href="#" class="btn btn-xs btn-danger delete_link">删除</a>
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

<iframe src="" id="add_form_page" style="height:440px;width:600px;"></iframe>

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
        href: "?<?php echo 'type='.$params['type'].'&page='; ?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });

    $('#search_form select').change(function(event) {
        $('#search_form').submit();
    });

    $('a#add_form_btn').click(function(){
        $('iframe#add_form_page').attr('src', "<?php echo site_url('products/spec/add'); ?>");
        $("a#add_form_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            'afterClose': function(){
                window.parent.location.reload();
            },
        });
    });
        
    $("a.check_form_link").click(function() {
        var spec_id = $(this).parent('td').attr('spec_id');
        if(spec_id){
            $('iframe#add_form_page').attr('src', "<?php echo site_url('products/spec/edit'); ?>?id="+spec_id);
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
        var spec_id = $(this).parent('td').attr('spec_id');
        if( spec_id && confirm('确认删除规格信息？') ){
            $.get("<?php echo site_url('products/spec/delete'); ?>", {'id': spec_id}, function(data){
                var data = $.parseJSON(data);
                if( data && data.err_no ){
                    alert(data.err_msg);
                } else {
                    alert("删除成功！");
                }
                window.location.reload();
            });
        }
    });
    
});

</script>