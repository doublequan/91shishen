<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('products/product'); ?>">商品管理</a></li>
            <li><a href="<?php echo site_url('products/brand'); ?>">品牌</a></li>
            <li class="active">品牌列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('products/brand/index'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">品牌名称：</p>
                    </div>
                    <div class="form-group">
                         <input type="text" class="form-control input-sm" name="brand_name" value="<?php echo @$brand_name; ?>">
                    </div>
                    <div class="form-group" style="width:80px;">
                         <input type="submit" class="btn btn-primary btn-sm btn-block" value="查 询">
                    </div>

                    <a class="btn btn-primary btn-sm" id="add_form_btn" href="#add_form_page" kesrc="#add_form_page">添加品牌</a>
                </form>
            </div>
            <?php 
                if(!empty($info_list)){ 
            ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th style="width:30px;">
                            #
                        </th>
                        <th style="width:80px;">
                            品牌Logo
                        </th>
                        <th style="width:120px;">
                            品牌名称
                        </th>
                        <th style="width:150px;">
                            品牌网址
                        </th>
                        <th style="width:120px;">
                            品牌描述
                        </th>
                        <th style="width:40px;">
                            排序
                        </th>
                        <th style="width:80px;">
                            操作
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $idx = 0;
                        foreach ($info_list as $single_info) {
                    ?>
                    <tr>
                        <td>
                            <?php echo ++$idx; ?>
                        </td>
                        <td style="padding:0px;">
                            <?php if(!empty($single_info['logo'])){ ?>
                            <img src="<?php echo $upload_url_prefix.$single_info['logo']; ?>" alt="<?php echo $single_info['name']; ?>" style="height:35px;"> 
                            <?php } ?>
                        </td>
                        <td>
                            <?php echo $single_info['name']; ?>
                        </td>
                        <td>
                            <?php if(empty($single_info['url'])) {?>
                            无
                            <?php }else{ ?>
                            <a href="<?php echo $single_info['url']; ?>" target="_blank">查看链接</a>
                            <?php } ?>
                        </td>
                        <td>
                            <a href="#" class="popover_link" title="品牌描述" data-content="<?php echo $single_info['description']; ?>">查看品牌描述</a>
                        </td>
                        <td>
                            <?php echo $single_info['sort']; ?>
                        </td>
                        <td brand_id="<?php echo $single_info['id']; ?>" style="padding:6px;">
                            <a class="btn btn-xs btn-success edit_form_link" href="#add_form_page" kesrc="#add_form_page">编 辑</a>
                            <a class="btn btn-xs btn-danger delete_link" href="javascript:void(0);">删 除</a>
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
        totalPages: <?php echo $total_pages; ?>,
        visiblePages: 5,
        startPage: <?php echo $page; ?>,
        first: '首页',
        prev: '上一页',
        next: '下一页',
        last: '尾页',
        href: "?<?php echo 'brand_name='.@$brand_name.'&page=';?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });

    $('.popover_link').popover({
        'content' : $(this).attr('data-content'),
        'title'   : $(this).attr('title'),
        'placement': 'bottom',
        'trigger' : 'hover',
    });

    var form_url = "<?php echo site_url('products/brand/form'); ?>";
    $('a#add_form_btn').click(function(){
        $('iframe#add_form_page').attr('src', form_url);
        $("a#add_form_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            'afterClose': function(){
                window.parent.location.reload();
            },
        });
    });
        
    $("a.check_form_link").click(function() {
        var brand_id = $(this).parent('td').attr('brand_id');
        if(brand_id){
            $('iframe#add_form_page').attr('src', form_url + '?brand_id=' + brand_id);
            $("a.check_form_link").fancybox({
                'hideOnContentClick': true,
                'padding':0,
                'afterClose': function(){
                    window.parent.location.reload();
                },
            });
        }
    });

    $("a.edit_form_link").click(function() {
        var brand_id = $(this).parent('td').attr('brand_id');
        if(brand_id){
            $('iframe#add_form_page').attr('src', form_url + '?brand_id=' + brand_id);
            $("a.edit_form_link").fancybox({
                'hideOnContentClick': true,
                'padding':0,
                'afterClose': function(){
                    window.parent.location.reload();
                },
            });
        }
    });

    $(".delete_link").click(function(){
        var brand_id = $(this).parent('td').attr('brand_id');

        if(brand_id){
            if(confirm("是否确认删除品牌？")){
                $.get("<?php echo site_url('products/brand/delete'); ?>", {'brand_id': brand_id}, function(data){
                    var data = $.parseJSON(data);

                    if(data && data.error){
                        alert(data.msg);
                    }
                    else{
                        alert("删除成功！");
                    }
                    window.location.reload();
                });
            }
        }

    });
});



</script>