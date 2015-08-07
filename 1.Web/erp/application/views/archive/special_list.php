<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('archive/archive'); ?>">内容管理</a></li>
            <li><a href="<?php echo site_url('archive/special'); ?>">专题管理</a></li>
            <li class="active">专题列表</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading" style="height:50px;">
                <a class="btn btn-primary btn-sm" id="add_form_btn" href="<?php echo site_url('archive/special/add'); ?>">新建专题</a>
            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th style="width:5%;">
                            编号
                        </th>
                        <th style="width:30%;">
                            活动名称
                        </th>
                        <th style="width:10%;">
                            调用别名
                        </th>
                        <th style="width:15%;">
                            活动开始日期
                        </th>
                        <th style="width:15%;">
                            活动结束日期
                        </th>
                        <th style="width:15%;">
                            前台调用
                        </th>
                        <th style="width:10%;">
                            操作
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($results as $k=>$row) {
                    ?>
                    <tr>
                        <td>
                            <?php echo ($k+1); ?>
                        </td>
                        <td>
                            <?php echo $row['name']; ?>
                        </td>
                        <td>
                            <?php echo $row['alias']; ?>
                        </td>
                        <td>
                            <?php echo $row['day_start'] ? $row['day_start'] : '不限'; ?>
                        </td>
                        <td>
                            <?php echo $row['day_end'] ? $row['day_end'] : '不限'; ?>
                        </td>
                        <td>
                            /special/<?php echo $row['alias'] ?>
                        </td>
                        <td single_id="<?php echo $row['id']; ?>" style="padding:6px;">
                            <!-- <a href="#add_form_page" kesrc="#add_form_page" class="check_form_link">查看</a> -->
                            <a class="btn btn-xs btn-success" href="<?php echo site_url('archive/special/edit').'?id='.$row['id']; ?>">编辑</a>
                            <a class="btn btn-xs btn-danger delete_link" href="javascript:void(0);">删除</a>
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

<iframe src="" id="add_form_page" style="height:400px;"></iframe>

<script src="<?php echo base_url('static/js/jquery.fancybox.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
    $("a.check_form_link").click(function() {
        var row_id = $(this).parent('td').attr('row_id');       
        if(row_id){
            $('iframe#add_form_page').attr('src', "<?php echo site_url('archive/special/edit'); ?>" + '?id=' + row_id);
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
        var tThis = $(this);
        if(confirm("此删除不可恢复，请谨慎操作。您确定删除该专题吗？")){
            var id = tThis.parent('td').attr('row_id');
            if(id){
                $.get("<?php echo site_url('archive/special/delete'); ?>", {'id':id}, function(data){
                    var data = $.parseJSON(data);
                    if(data && data.err_no==0){
                        tThis.parent().parent().hide(600, function(){
                            tThis.parent().parent().remove();
                            alert('删除成功！');
                        });
                    } else {
                        alert('删除失败！'+err_msg);
                    }
                });
            }
        }
    });
});
</script>