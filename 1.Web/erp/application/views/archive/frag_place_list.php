<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('archive/archive'); ?>">内容管理</a></li>
            <li><a href="<?php echo site_url('archive/frag'); ?>">碎片管理</a></li>
            <li class="active">碎片位置列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('archive/frag'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">搜索：</p>
                    </div>
                    <div class="form-group">
                        <select name="site_id" class="form-control input-sm">
                            <option value="0">请选择所属网站</option>
                            <?php foreach ($sites as $k=>$row) { ?>
                            <option value="<?php echo $k; ?>"<?php echo $k==$params['site_id'] ? ' selected="selected"' : ''; ?>><?php echo trim($row['name']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="os" class="form-control input-sm">
                            <option value="">请选择所属系统类型</option>
                            <?php foreach ($os_types as $k=>$v) { ?>
                            <option value="<?php echo $k; ?>"<?php echo $k==$params['os'] ? ' selected="selected"' : ''; ?>><?php echo trim($v); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <a class="btn btn-primary btn-sm" id="add_form_btn" href="#add_form_page" kesrc="#add_form_page">添加碎片位置</a>
                </form>
                
            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th style="width:40px;">
                            编号
                        </th>
                        <th style="width:120px;">
                            所属网站
                        </th>
                        <th style="width:100px;">
                            系统类型
                        </th>
                        <th style="width:200px;">
                            碎片位置名称
                        </th>
                        <th style="width:80px;">
                            是否锁定
                        </th>
                        <th style="width:80px;">
                            碎片数量
                        </th>
                        <th style="width:150px;">
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
                            <?php echo isset($sites[$single_info['site_id']]) ? $sites[$single_info['site_id']]['name'] : '未知网站'; ?>
                        </td>
                        <td>
                            <?php echo isset($os_types[$single_info['os']]) ? $os_types[$single_info['os']] : '未知系统'; ?>
                        </td>
                        <td>
                            <?php echo $single_info['name']; ?>
                        </td>
                        <td>
                            <?php if( $single_info['is_lock'] ){ ?>
                            <span class="label label-danger">已锁定</span>
                            <?php } else { ?>
                            <span class="label label-default">未锁定</span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php echo $single_info['num']; ?>
                        </td>
                        <td row_id="<?php echo $single_info['id']; ?>" style="padding:6px;">
                            <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-xs btn-success check_form_link">查看/编辑</a>
                            <a href="<?php echo site_url('archive/frag/frag_list?place_id='.$single_info['id']); ?>" 
                                class="btn btn-xs btn-info delete_link">查看此位置碎片</a>
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
    $('#search_form select').change(function(event) {
        $('#search_form').submit();
    });

    $('a#add_form_btn').click(function(){
        $('iframe#add_form_page').attr('src', "<?php echo site_url('archive/frag/place_add'); ?>");
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
            $('iframe#add_form_page').attr('src', "<?php echo site_url('archive/frag/place_edit'); ?>" + '?id=' + row_id);
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