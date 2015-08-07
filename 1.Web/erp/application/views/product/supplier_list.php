<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('products/product'); ?>">商品管理</a></li>
            <li><a href="<?php echo site_url('products/supplier'); ?>">供应商</a></li>
            <li class="active">供应商列表</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('products/supplier/index'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">供应商名称：</p>
                    </div>
                    <div class="form-group">
                         <input type="text" class="form-control input-sm" name="sup_name" value="<?php echo @$sup_name; ?>">
                    </div>
                    <div class="form-group" style="width:80px;">
                         <input type="submit" class="btn btn-primary btn-sm btn-block" value="查 询">
                    </div>
                    <a class="btn btn-primary btn-sm" id="add_form_btn" href="#add_form_page" kesrc="#add_form_page">添加供应商</a>
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
                        <th style="width:140px;">
                            供应商名称
                        </th>
                        <th style="width:100px;">
                            供应商电话
                        </th>
                        <th style="width:100px;">
                            联系人
                        </th>
                        <th style="width:100px;">
                            联系人手机
                        </th>
                        <th style="width:120px;">
                            联系人邮箱
                        </th>
                        <th style="width:120px;">
                            收货人
                        </th>
                        <th style="width:80px;">
                            操作
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $idx = 0;
                        foreach ($results as $idx => $single_info) {
                    ?>
                    <tr>
                        <td>
                            <?php echo ++$idx; ?>
                        </td>
                        <td>
                            <?php echo $single_info['sup_name']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['sup_phone']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['contact_name']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['contact_mobile']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['contact_email']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['logistics_recipient']; ?>
                        </td>
                        <td supplier_id="<?php echo $single_info['id']; ?>" style="padding:6px;">
                            <a class="btn btn-xs btn-success check_form_link" href="#add_form_page" kesrc="#add_form_page">编 辑</a>
                            <a class="btn btn-xs btn-danger delete_link" href="#">删 除</a>
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

<iframe src="" id="add_form_page" style="height:640px;"></iframe>

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
        href: "?<?php echo 'sup_name='.@$sup_name.'&page=';?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });

    var form_url = "<?php echo site_url('products/supplier/form'); ?>";
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
        var supplier_id = $(this).parent('td').attr('supplier_id');
        if(supplier_id){
            $('iframe#add_form_page').attr('src', form_url + '?supplier_id=' + supplier_id);
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
        var supplier_id = $(this).parent('td').attr('supplier_id');

        if(confirm("是否确定删除供应商？")){
            $.get("<?php echo site_url('products/supplier/delete'); ?>", {'supplier_id': supplier_id}, function(data){
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

    });
});



</script>