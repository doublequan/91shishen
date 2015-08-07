<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">
<script src="<?php echo base_url('static/js/jquery.fancybox.js'); ?>" type="text/javascript"></script>

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('enterprise/company'); ?>">企业管理</a></li>
            <li><a href="<?php echo site_url('permission/permission'); ?>">权限</a></li>
            <li class="active">权限列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading" style="height:50px;">
                <a class="btn btn-primary btn-sm" id="add_form_btn" href="#add_form_page" kesrc="#add_form_page">添加权限</a>
            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th style="width:50px;">
                            权限编号
                        </th>
                        <th style="width:150px;">
                            权限名称
                        </th>
                        <th style="width:150px;">
                            权限KEY
                        </th>
                        <th style="width:150px;">
                            所属权限组
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
                            <?php echo $single_info['id']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['name']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['key']; ?>
                        </td>
                        <td>
                            <?php echo $groupMap[$single_info['group_id']]['name']; ?>
                        </td>
                        <td row_id="<?php echo $single_info['id']; ?>" style="padding:6px;">
                            <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-xs btn-success check_form_link">查看/编辑</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php }else{ ?>
            <div class="alert alert-warning col-md-12 text-center" role="alert">查询结果为空！</div>
            <?php } ?>
        </div>
    </div>
</div>

<iframe src="" id="add_form_page" style="height:450px;width:600px;"></iframe>

<script type="text/javascript">
$(function(){
    $('a#add_form_btn').click(function(){
        $('iframe#add_form_page').attr('src', "<?php echo site_url('permission/permission/add'); ?>");
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
            $('iframe#add_form_page').attr('src', "<?php echo site_url('permission/permission/edit'); ?>" + '?id=' + row_id);
            $("a.check_form_link").fancybox({
                'hideOnContentClick': true,
                'padding':0,
                'afterClose': function(){
                    window.parent.location.reload();
                },
            });
        }
    });

});
</script>