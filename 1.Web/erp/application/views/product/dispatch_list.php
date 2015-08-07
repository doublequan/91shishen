<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('products/product'); ?>">商品管理</a></li>
            <li><a href="<?php echo site_url('products/dispatch'); ?>">调度单</a></li>
            <li class="active">调度单列表</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('products/dispatch'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">调度单状态：</p>
                    </div>
                    <div class="form-group">
                        <select name="status" class="form-control input-sm">
                        <?php foreach ($statusMap as $single_status_key => $single_status) { ?>
                            <option value="<?php echo $single_status_key; ?>" <?php echo $single_status_key==$params['status']?'selected':''; ?>><?php echo trim($single_status); ?></option>
                        <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <p class="form-control-static input-sm">&nbsp;&nbsp;调度单编号：</p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control input-sm" name="keyword" value="<?php echo $params['keyword']; ?>" style="width:200px;">
                    </div>
                    <div class="form-group" style="width:80px;">
                        <input type="submit" class="btn btn-primary btn-sm btn-block" value="查 询">
                    </div>

                    <a class="btn btn-primary btn-sm" id="add_form_btn" href="<?php echo site_url('products/dispatch/add'); ?>">添加调度单</a>
                </form>
                
            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th style="width:20px;" class="text-center">
                            <input type="checkbox" id="check_all_chk">
                        </th>
                        <th style="width:100px;">
                            调度单编号
                        </th>
                        <th style="width:150px;">
                            出货门店
                        </th>
                        <th style="width:150px;">
                            入库门店
                        </th>
                        <th style="width:90px;">
                            调度日期
                        </th>
                        <th style="width:70px;">
                            状态
                        </th>
                        <th style="width:120px;">
                            操作
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach ($results as $idx => $single_info) {
                    ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="check_single_chk" class="check_single_chk" value="<?php echo $single_info['id']; ?>">
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
                        <td row_id="<?php echo $single_info['id']; ?>" style="padding:6px;">
                            <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-xs btn-info check_link"
                                single_id="<?php echo $single_info['id']; ?>">查看</a>
                            <a href="<?php echo site_url('products/dispatch/edit').'?id='.$single_info['id']; ?>" class="btn btn-xs btn-primary">编辑</a>
                            <a href="<?php echo site_url('products/dispatch/dispatch_print').'?id='.$single_info['id']; ?>" class="btn btn-xs btn-success" target="_blank">打印</a>
                            <a href="javascipt:viod(0);" class="btn btn-xs btn-danger delete_link">删除</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="panel-footer">
                <button type="button" class="btn btn-sm btn-primary" id="mutiPrintBtn">批量打印所选调度单</button>
            </div>

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

    $("#mutiPrintBtn").click(function(){
        var dispatch_ids = [];
        $('.check_single_chk').each(function(){
            if( $(this).attr('checked')=='checked' ){
                dispatch_ids.push($(this).val());
            }
        });
        if( dispatch_ids.length==0 ){
            alert('请至少选择一个调度单');
            return false;
        }

        var muti_print_url = "<?php echo site_url('products/dispatch/dispatch_print_muti'); ?>";
        muti_print_url += ('?dispatch_ids=' + dispatch_ids.join(','));
        window.open(muti_print_url);
    });

    $(".delete_link").click(function(){
        if( confirm('此操作不可恢复，您确定要删除此调度单吗？') ){
            var tThis = $(this);
            var row_id = tThis.parent('td').attr('row_id');
            if(row_id){
                $.get("<?php echo site_url('products/dispatch/delete'); ?>", {'id':row_id}, function(data){
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