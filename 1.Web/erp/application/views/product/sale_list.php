<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('products/product'); ?>">商品管理</a></li>
            <li><a href="<?php echo site_url('products/sale'); ?>">门店零售</a></li>
            <li class="active">门店零售单列表</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('products/sale'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">请选择所属网站：</p>
                    </div>
                    <div class="form-group">
                        <select name="site_id" class="form-control input-sm">
                            <option value="0">全部网站</option>
                        <?php foreach ($sites as $k=>$row) { ?>
                            <option value="<?php echo $k; ?>" <?php echo $k==$params['site_id'] ? 'selected' : ''; ?>><?php echo $row['name']; ?></option>
                        <?php } ?>
                        </select>
                    </div>
                    <div class="form-group" style="padding-left:15px;">
                        <p class="form-control-static input-sm">请选择所属门店：</p>
                    </div>
                    <div class="form-group">
                        <select name="store_id" class="form-control input-sm">
                            <option value="0">全部门店</option>
                        <?php foreach ($stores as $k=>$row) { ?>
                            <option value="<?php echo $k; ?>" <?php echo $k==$params['store_id'] ? 'selected' : ''; ?>><?php echo $row['name']; ?></option>
                        <?php } ?>
                        </select>
                    </div>
                    <a class="btn btn-primary btn-sm" id="add_form_btn" href="<?php echo site_url('products/sale/add'); ?>">添加零售</a>
                </form>
                
            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th style="width:5%;">
                            #
                        </th>
                        <th style="width:150px;">
                            销售单编号
                        </th>
                        <th style="width:150px;">
                            门店
                        </th>
                        <th style="width:100px;">
                            实收总金额
                        </th>
                        <th style="width:100px;">
                            商品/原材料总价
                        </th>
                        <th style="width:100px;">
                            优惠金额
                        </th>
                        <th style="width:150px;">
                            销售时间
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach ($results as $idx => $single_info) {
                    ?>
                    <tr>
                        <td>
                            <?php echo ++$idx; ?> 
                        </td>
                        <td>
                            <?php echo $single_info['id']; ?> 
                        </td>
                        <td>
                            <?php 
                            $out_str = $store_list[$single_info['out_store']]['name'];
                            //$out_str .= '（'.$city_list[$single_info['out_city']]['name'].'）';
                            echo $out_str; 
                            ?>
                        </td>
                        <td>
                            <?php 
                            $in_str = $store_list[$single_info['in_store']]['name'];
                            //$in_str .= '（'.$city_list[$single_info['in_city']]['name'].'）';
                            echo $in_str; 
                            ?>
                        </td>
                        <td>
                            <?php echo date('Y-m-d', strtotime($single_info['datetime'])) ; ?>
                        </td>
                        <td>
                            <?php 
                            switch($single_info['status']){
                                case 0:
                                    echo '<span class="label label-info">新建</span>';
                                    break;
                                case 1:
                                    echo '<span class="label label-primary">已确认</span>';
                                    break;
                                case 2:
                                    echo '<span class="label label-info">运输中</span>';
                                    break;
                                case 3:
                                    echo '<span class="label label-success">完成</span>';
                                    break;
                                default:
                                    echo '<span class="label label-default">未知</span>';
                                    break;
                            }
                            ?>
                        </td>
                        <td style="padding:6px;">
                            <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-xs btn-info check_link"
                                single_id="<?php echo $single_info['id']; ?>">查看</a>
                            <a href="<?php echo site_url('products/dispatch/edit').'?id='.$single_info['id']; ?>" class="btn btn-xs btn-primary">编辑</a>
                            <a href="<?php echo site_url('products/dispatch/dispatch_print').'?id='.$single_info['id']; ?>" class="btn btn-xs btn-success" target="_blank">打印调度单</a>
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

<iframe src="" id="add_form_page" style="height:660px;width:1000px;"></iframe>

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
        href: "?<?php echo 'site_id='.@$site_id.'&type_name='.@$type_name.'&page=';?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });

    $('#search_form select').change(function(event) {
        $('#search_form').submit();
    });

    $('a.check_link').click(function(){
        var single_id = $(this).attr('single_id');
        $('iframe#add_form_page').attr('src', "<?php echo site_url('products/dispatch/detail').'?id='; ?>" + single_id);
        $("a.check_link").fancybox({
            'hideOnContentClick': true,
            'padding':0,
        });
    });
});
</script>