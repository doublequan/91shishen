<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('archive/archive'); ?>">内容管理</a></li>
            <li class="active">内容列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading" style="height:50px;">
                <form class="form-inline" action="<?php echo site_url('archive/archive'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">搜索：</p>
                    </div>
                    <div class="form-group">
                        <select name="category_id" class="form-control input-sm">
                            <option value="0">不限内容分类</option>
                            <?php foreach ($categorys as $k=>$row) { ?>
                            <option value="<?php echo $k; ?>"<?php echo $k==$params['category_id'] ? ' selected="selected"' : ''; ?>><?php echo trim($row['name']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control input-sm" name="keyword" value="<?php echo $params['keyword']; ?>">
                    </div>
                    <div class="form-group" style="width:80px;">
                         <input type="submit" class="btn btn-primary btn-sm btn-block" value="查 询">
                    </div>
                    <a class="btn btn-primary btn-sm" id="add_form_btn" href="<?php echo site_url('archive/archive/add'); ?>">添加内容</a>
                </form>
            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="10%">内容编号</th>
                        <th width="15%">所属分类</th>
                        <th width="40%">内容标题</th>
                        <th width="15%">模板类型</th>
                        <th width="15%">操作</th>
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
                            <?php echo isset($categorys[$single_info['category_id']]) ? $categorys[$single_info['category_id']]['name'] : ''; ?>
                        </td>
                        <td>
                            <?php echo $single_info['title']; ?>
                        </td>
                        <td>
                            <?php 
                                if($single_info['template_id']==2)
                                    echo '<span class="label label-primary">通栏内容</span>';
                                else
                                    echo '<span class="label label-default">带分类内容</span>';
                            ?>
                        </td>
                        <td row_id="<?php echo $single_info['id']; ?>" style="padding:6px;">
                            <a href="<?php echo site_url('archive/archive/edit?id='.$single_info['id']); ?>" class="btn btn-xs btn-success">查看/编辑</a>
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
        href: "?<?php echo 'category_id='.$params['category_id'].'&keyword='.$params['keyword'].'&page=';?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });

    $('#search_form select').change(function(event) {
        $('#search_form').submit();
    });

    $(".delete_link").click(function(){
        if( confirm('此操作不可恢复，您确定要删除此内容吗？') ){
            var tThis = $(this);
            var row_id = tThis.parent('td').attr('row_id');
            if(row_id){
                $.get("<?php echo site_url('archive/archive/delete'); ?>", {'id':row_id}, function(data){
                    var data = $.parseJSON(data);
                    if(data && data.err_no==0){
                        tThis.parent().parent().hide(600, function(){
                            tThis.parent().parent().remove();
                            alert('删除成功！');
                        });
                    } else {
                        alert('删除失败！');
                    }
                });
            }
        }
    });
});
</script>