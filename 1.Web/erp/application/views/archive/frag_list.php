<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('archive/archive'); ?>">内容管理</a></li>
            <li><a href="<?php echo site_url('archive/frag'); ?>">碎片管理</a></li>
            <li class="active">碎片详情列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading" style="height:50px;">
                <form class="form-inline" action="<?php echo site_url('archive/frag'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">
                            碎片位置：<b><?php echo $place['name']; ?></b>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                        </p>
                    </div>
                    <div class="form-group">
                        <p class="form-control-static input-sm">
                            所属网站：<b><?php echo isset($sites[$place['site_id']]) ? $sites[$place['site_id']]['name'] : '未知网站'; ?></b>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                        </p>
                    </div>
                    <div class="form-group">
                        <p class="form-control-static input-sm">
                            所属系统：<b><?php echo isset($os_types[$place['os']]) ? $os_types[$place['os']] : '未知系统'; ?></b>
                        </p>
                    </div>
                    <?php if( !$place['is_lock'] ){ ?>
                    <a class="btn btn-primary btn-sm" id="add_form_btn" href="#add_form_page" kesrc="#add_form_page">添加此位置碎片</a>
                    <?php } else { ?>
                    <a class="btn btn-default btn-sm" id="add_form_btn">位置锁定，不可添加</a>
                    <?php } ?>
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
                        <th style="width:100px;">
                            碎片类型
                        </th>
                        <th style="width:150px;">
                            碎片名称
                        </th>
                        <th style="width:90px;">
                            排序
                        </th>
                        <th style="width:90px;">
                            最后更新员工
                        </th>
                        <th style="width:120px;">
                            最后更新时间
                        </th>
                        <th style="width:100px;">
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
                            <?php echo isset($frag_types[$single_info['type']]) ? $frag_types[$single_info['type']] : '未知类型'; ?>
                        </td>
                        <td>
                            <?php echo $single_info['name']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['sort']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['modify_name']; ?>
                        </td>
                        <td>
                            <?php echo date('Y-m-d H:i:s',$single_info['modify_time']); ?>
                        </td>
                        <td row_id="<?php echo $single_info['id']; ?>" style="padding:6px;">
                            <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-xs btn-success check_form_link">查看/编辑</a>&nbsp;&nbsp;
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

<iframe src="" id="add_form_page" style="height:560px;"></iframe>

<script src="<?php echo base_url('static/js/jquery.fancybox.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(function(){

    $('a#add_form_btn').click(function(){
        $('iframe#add_form_page').attr('src', "<?php echo site_url('archive/frag/frag_add?place_id='.$place['id']); ?>");
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
            $('iframe#add_form_page').attr('src', "<?php echo site_url('archive/frag/frag_edit'); ?>" + '?id=' + row_id);
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
        if(confirm("此删除不可恢复，请谨慎操作。您确定删除碎片码？")){
            var id = tThis.parent('td').attr('row_id');
            if(id){
                $.get("<?php echo site_url('archive/frag/frag_delete'); ?>", {'id':id}, function(data){
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