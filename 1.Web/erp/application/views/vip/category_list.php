<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('vip/user'); ?>"><span class="glyphicon glyphicon-home"></span> 大客户管理</a></li>
            <li><a href="<?php echo site_url('vip/category'); ?>">大客户商品分类</a></li>
            <li class="active">分类列表</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading" style="height:50px;">
                <a class="btn btn-primary btn-sm" id="add_form_btn" href="#add_form_page" kesrc="#add_form_page">添加分类</a>
            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th style="width:60px;">
                            排序
                        </th>
                        <th style="width:150px;">
                            分类名称
                        </th>
                        <th style="width:80px;">
                            分类图标
                        </th>
                        <th style="width:120px;">
                            SEO标题
                        </th>
                        <th style="width:120px;">
                            关键词
                        </th>
                        <th style="width:80px;">
                            SEO描述信息
                        </th>
                        <th style="width:80px;">
                            操作
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach ($results as $idx=>$single_info) {
                    ?>
                    <tr>
                        <td style="padding:1px;">
                            <input type="text" class="form-control category_sort input-sm" category_id="<?php echo $single_info['id']; ?>"
                                 category_sort="<?php echo $single_info['sort']; ?>" value="<?php echo $single_info['sort']; ?>" style="width:60px;margin:0 auto;">
                        </td>
                        <td>
                            <?php echo $single_info['name']; ?>
                        </td>
                        <td>
                            <?php if(empty($single_info['thumb'])) {?>
                            无
                            <?php }else{ ?>
                            <a href="<?php echo $single_info['thumb']; ?>" target="_blank">查看图标</a>
                            <?php } ?>
                        </td>
                        <td>
                            <?php echo empty($single_info['seo_title'])?'无':$single_info['seo_title']; ?>
                        </td>
                        <td>
                            <?php echo empty($single_info['seo_keywords'])?'无':$single_info['seo_keywords']; ?>
                        </td>
                        <td>
                            <?php if(empty($single_info['seo_description'])) {?>
                            无
                            <?php }else{ ?>
                            <a href="javascript:void(0);" class="check_link" title="SEO描述" data-content="<?php echo $single_info['seo_description']; ?>" >查看描述</a>
                            <?php } ?>
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

<iframe src="" id="add_form_page" style="height:500px;width:900px;"></iframe>

<script src="<?php echo base_url('static/js/jquery.fancybox.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
    $('.check_link').popover({
        content : $(this).attr('data-content'),
        html : true,
        trigger : 'hover',
        placement: 'bottom',
        title : $(this).attr('title'),
    });

    $('a#add_form_btn').click(function(){
        $('iframe#add_form_page').attr('src', "<?php echo site_url('vip/category/add'); ?>");
        $("a#add_form_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            'afterClose': function(){
                window.parent.location.reload();
            },
        });
    });
    
    $("a.check_form_link").click(function() {
        var row_id = $(this).parent().attr('row_id');
        if(row_id){
            $('iframe#add_form_page').attr('src', "<?php echo site_url('vip/category/edit'); ?>" + '?id=' + row_id);
            $("a.check_form_link").fancybox({
                'hideOnContentClick': true,
                'padding':0,
                'afterClose': function(){
                    window.parent.location.reload();
                },
            });
        }
    });

    $('.category_sort').blur(function(event) {
        var category_sort_old = $(this).attr('category_sort');
        var category_sort = $(this).val();

        if(category_sort_old != category_sort){
            if(window.confirm('检查到分类排序值已经修改，是否提交改动？')){
                var category_id = $(this).attr('category_id');

                var params = {
                    'category_id': category_id,
                    'category_sort': category_sort,
                };

                $.post("<?php echo site_url('vip/category/updateSort'); ?>", params, function(data){
                    if(data && data.error){
                        alert(data.msg);
                    }
                    else{
                        alert("修改排序成功！");
                    }
                    window.location.reload();
                }, 'json');
            }
        }
    });

});

</script>