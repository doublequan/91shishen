<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('enterprise/company'); ?>">企业管理</a></li>
            <li><a href="<?php echo site_url('enterprise/store'); ?>">门店</a></li>
            <li class="active">门店列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading" style="height:50px;">
                <form class="form-inline" action="<?php echo site_url('enterprise/store'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">搜索：</p>
                    </div>
                    <div class="form-group">
                        <select name="site_id" class="form-control input-sm">
                            <option value="">所属网站</option>
                        <?php foreach ($sites as $site) { ?>
                            <option value="<?php echo $site['id']; ?>" <?php echo $site['id']==$search_site?'selected':''; ?>><?php echo trim($site['name']); ?></option>
                        <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="type_key" class="form-control input-sm">
                            <option value="">门店类别</option>
                        <?php foreach ($typeMap as $typeMap_key => $typeMap_name) {?>
                            <option value="<?php echo $typeMap_key; ?>" <?php echo $typeMap_key==$type_key?'selected':''; ?>><?php echo $typeMap_name; ?></option>
                        <?php } ?> 
                        </select>
                    </div>
                    <!-- <div class="form-group">
                         <input type="submit" class="btn btn-primary btn-sm" value="查 询">
                    </div> -->

                    <a class="btn btn-primary btn-sm" id="add_form_btn" href="#add_form_page" kesrc="#add_form_page">添加门店</a>
                </form>
                
            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th style="width:120px;">
                            门店名称
                        </th>
                        <th style="width:100px;">
                            所属网站
                        </th>
                        <th style="width:100px;">
                            城市
                        </th>
                        <th style="width:130px;">
                            地址
                        </th>
                        <th style="width:60px;">
                            加工中心
                        </th>
                        <th style="width:60px;">
                            销售门店
                        </th>
                        <th style="width:60px;">
                            仓库
                        </th>
                        <th style="width:60px;">
                            自提点
                        </th>
                        <th style="width:60px;">
                            配送点
                        </th>
                        <th style="width:100px;">
                            操作
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach ($results as $single_info) {
                    ?>
                    <tr>
                        <td>
                            <?php echo $single_info['name']; ?>
                        </td>
                        <td>
                            <?php echo $sites[$single_info['site_id']]['name']; ?>
                        </td>
                        <td>
                            <?php echo $province_list[$single_info['prov']]['name'].' '.$city_list[$single_info['city']]['name']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['address']; ?>
                        </td>
                        <td>
                            <?php 
                            if(@$single_info['is_process'])
                                echo '<span class="label label-primary">是</span>';
                            else
                                echo '<span class="label label-default">否</span>';
                            ?>
                        </td>
                        <td>
                            <?php 
                            if(@$single_info['is_sell'])
                                echo '<span class="label label-primary">是</span>';
                            else
                                echo '<span class="label label-default">否</span>';
                            ?>
                        </td>
                        <td>
                            <?php 
                            if(@$single_info['is_storage'])
                                echo '<span class="label label-primary">是</span>';
                            else
                                echo '<span class="label label-default">否</span>';
                            ?>
                        </td>
                        <td>
                            <?php 
                            if(@$single_info['is_pickup'])
                                echo '<span class="label label-primary">是</span>';
                            else
                                echo '<span class="label label-default">否</span>';
                            ?>
                        </td>
                        <td>
                            <?php 
                            if(@$single_info['is_delivery'])
                                echo '<span class="label label-primary">是</span>';
                            else
                                echo '<span class="label label-default">否</span>';
                            ?>
                        </td>
                        <td row_id="<?php echo $single_info['id']; ?>" style="padding:6px;">
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

<iframe src="" id="add_form_page" style="height:580px;"></iframe>

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
        href: "?<?php echo 'site_id='.$params['site_id'].'&type_name='.$params['type_key'].'&page=';?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });

    $('#search_form select').change(function(event) {
        $('#search_form').submit();
    });

    $('a#add_form_btn').click(function(){
        $('iframe#add_form_page').attr('src', "<?php echo site_url('enterprise/store/add'); ?>");
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
            $('iframe#add_form_page').attr('src', "<?php echo site_url('enterprise/store/edit'); ?>" + '?id=' + row_id);
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
        var row_id = $(this).parent('td').attr('row_id');
        if(row_id){
            if(confirm("是否确定删除门店？")){
                $.get("<?php echo site_url('enterprise/store/delete'); ?>", {'row_id': row_id}, function(data){
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