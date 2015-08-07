<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="">调度管理</a></li>
            <li class="active">我的调度单</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('dispatch/my'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">状态：</p>
                    </div>
                    <div class="form-group">
                        <select name="status" class="form-control input-sm">
                        <?php foreach ($statusMap as $single_status_key => $single_status) { ?>
                            <option value="<?php echo $single_status_key; ?>" <?php echo $single_status_key==$params['status']?'selected':''; ?>><?php echo trim($single_status); ?></option>
                        <?php } ?>
                        </select>
                    </div>
                    <div class="form-group" style="padding-left:10px;">
                        <p class="form-control-static input-sm">调度单号：</p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control input-sm" name="keyword" value="<?php echo $params['keyword']; ?>" style="width:200px;" placeholder="调度单号">
                    </div>
                    <div class="form-group" style="width:80px;">
                        <input type="submit" class="btn btn-primary btn-sm btn-block" value="查 询">
                    </div>

                    <a class="btn btn-primary btn-sm" id="add_form_btn" href="<?php echo site_url('dispatch/my/add'); ?>">新建调度单</a>
                </form>
                
            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th width="4%" class="text-center">
                            <input type="checkbox" id="check_all_chk">
                        </th>
                        <th width="15%">
                            调度单编号
                        </th>
                        <th width="16%">
                            出货门店
                        </th>
                        <th width="16%">
                            入库门店
                        </th>
                        <th width="10%">
                            创建员工
                        </th>
                        <th width="10%">
                            最后处理员工
                        </th>
                        <th width="10%">
                            状态
                        </th>
                        <th width="19%">
                            操作
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach( $results as $k=>$row ){ ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="check_single_chk" class="check_single_chk" value="<?php echo $row['id']; ?>">
                        </td>
                        <td><?php echo $row['id']; ?> </td>
                        <td><?php echo isset($stores[$row['out_store']]) ? $stores[$row['out_store']]['name'] : ''; ?></td>
                        <td><?php echo isset($stores[$row['in_store']]) ? $stores[$row['in_store']]['name'] : ''; ?></td>
                        <td><?php echo $row['create_name']; ?> </td>
                        <td><?php echo $row['last_name']; ?> </td>
                        <td><?php echo isset($statusMap[$row['status']]) ? $statusMap[$row['status']] : ''; ?></td>
                        <td row_id="<?php echo $row['id']; ?>" style="padding:6px 6px 6px 30px;text-align:left;">
                            <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-xs btn-default check_link">查看</a>
                            <?php if( $row['status']==0 ){ ?>
                            <a href="<?php echo site_url('dispatch/my/edit').'?id='.$row['id']; ?>" class="btn btn-xs btn-primary">编辑</a>
                            <?php } ?>
                            <a href="<?php echo site_url('dispatch/detail/print_single').'?id='.$row['id']; ?>" class="btn btn-xs btn-success" target="_blank">打印</a>
                            <a href="javascipt:viod(0);" class="btn btn-xs btn-danger delete_link">删除</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="panel-footer">
                <button type="button" class="btn btn-sm btn-primary" id="mutiSubmitBtn">提交审核所选调度单</button>
                <button type="button" class="btn btn-sm btn-success" id="mutiPrintBtn">批量打印所选调度单</button>
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
        href: "?<?php echo 'status='.$params['status'].'&keyword='.urlencode($params['keyword']).'&page='; ?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });

    $('#search_form select').change(function(event) {
        $('#search_form').submit();
    });

    $('a.check_link').click(function(){
        var id = $(this).parent('td').attr('row_id');
        $('iframe#add_form_page').attr('src', "<?php echo site_url('dispatch/detail').'?id='; ?>" + id);
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

        var muti_print_url = "<?php echo site_url('dispatch/detail/print_muti'); ?>";
        muti_print_url += ('?dispatch_ids=' + dispatch_ids.join(','));
        window.open(muti_print_url);
    });

    $("#mutiSubmitBtn").click(function(){
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
        $.post("<?php echo site_url('dispatch/my/doSubmit'); ?>", {'ids':dispatch_ids.toString()}, function(data){
            var data = $.parseJSON(data);
            if(data && data.err_no==0){
                alert('操作成功！');
                window.location.reload();
            } else {
                alert('操作失败！');
            }
        });
    });

    $(".delete_link").click(function(){
        if( confirm('此操作不可恢复，您确定要删除此调度单吗？') ){
            var tThis = $(this);
            var row_id = tThis.parent('td').attr('row_id');
            if(row_id){
                $.get("<?php echo site_url('dispatch/my/delete'); ?>", {'id':row_id}, function(data){
                    var data = $.parseJSON(data);
                    if(data && data.err_no==0){
                        tThis.parent().parent().hide(600, function(){
                            tThis.parent().parent().remove();
                            alert('操作成功！');
                        });
                    } else {
                        alert(data.err_msg);
                    }
                });
            }
        }
        return false;
    });
});
</script>