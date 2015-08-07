<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('products/product'); ?>">商品管理</a></li>
            <li><a href="<?php echo site_url('products/category'); ?>">分类</a></li>
            <li class="active">分类列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading" style="height:50px;">
                <form class="form-inline" action="<?php echo site_url('products/category'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">商品分类管理</p>
                    </div>
                    <a class="btn btn-primary btn-sm" id="add_form_btn" href="#add_form_page" kesrc="#add_form_page">添加分类</a>
                </form>
            </div>
            <div class="panel-body" style="padding:0;">
                <div class="row" >
                    <div class="col-md-12">
                        <div class="panel-body-nav">
                            <div class="text-center pull-left" style="width:70px;">排序</div>
                            <div class="pull-left" style="width:200px;padding-left:34px;">分类名称</div>
                            <div class="text-center pull-right" style="width:160px;">操作</div>
                        </div>
                    </div>
                </div>
                <div class="row fuelux">
                    <div class="col-md-12">
                        <?php 
                        function show_category_tree($results){
                            $item_str = '<ul id="myTree" role="tree" class="tree">';
                            foreach ($results as $tree_item) {
                                $item_str .= '<li aria-expanded="false" role="treeitem" class="tree-branch" id="treeitem_';
                                $item_str .= $tree_item['id'].'" aria-labelledby="treeitem_'.$tree_item['id'].'-label">';
                                $item_str .= '<div class="tree-branch-header"><button class="glyphicon icon-caret glyphicon-play"><span class="sr-only">查看</span></button>';
                                $item_str .= '<button class="tree-branch-name"><span class="glyphicon icon-folder glyphicon-folder-close"></span>';
                                $item_str .= '<span class="tree-label" id="treeitem_'.$tree_item['id'].'-label">'.$tree_item['name'].'</span></button>';
                                $item_str .= '<div class="category_tab pull-right text-center" style="width:70px;"><a class="btn btn-xs btn-danger delete_link" href="#" category_id="'.$tree_item['id'].'">删 除</a></div>';
                                $item_str .= '<div class="category_tab pull-right text-center" style="width:90px;"><a class="btn btn-xs btn-success check_form_link" href="#add_form_page" kesrc="#add_form_page" category_id="'.$tree_item['id'].'">查看/编辑</a></div>';
                                $item_str .= '<div class="category_tab pull-left" style="width:60px;"><input type="text" class="form-control category_sort input-sm" category_id="'.$tree_item['id'].'" category_sort="'.$tree_item['sort'].'" value="'.$tree_item['sort'].'"></div>';
                                $item_str .= '</div>';

                                if(!empty($tree_item['child'])){
                                    $item_str .= '<ul role="group" class="tree-branch-children hide">';
                                    $item_str .= show_category_tree($tree_item['child']);
                                    $item_str .= '</ul>';
                                }
                                $item_str .= '</li>';
                            }
                            $item_str .= '</ul>';
                            return $item_str;
                        }
                        if(!empty($results)){
                            echo show_category_tree($results);
                        }else {
                            echo '<div class="alert alert-warning" role="alert" style="margin-bottom:0;">无分类信息，请添加！</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<iframe src="" id="add_form_page" style="height:660px;width:900px;"></iframe>

<script src="<?php echo base_url('static/js/jquery.fancybox.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/fuelux.tree.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
    $('#myTree').tree()

    $('a#add_form_btn').click(function(){
        $('iframe#add_form_page').attr('src', "<?php echo site_url('products/category/add'); ?>");
        $("a#add_form_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            'afterClose': function(){
                window.parent.location.reload();
            },
        });
    });

    $("a.check_form_link").click(function() {
        var category_id = $(this).attr('category_id');
        if(category_id){
            $('iframe#add_form_page').attr('src', "<?php echo site_url('products/category/edit'); ?>" + '?id=' + category_id);
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

                $.post("<?php echo site_url('products/category/updateSort'); ?>", params, function(data){
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

    $(".delete_link").click(function(){
        var category_id = $(this).attr('category_id');
        if(category_id){
            if(confirm('是否确认删除商品分类？'))
            $.get("<?php echo site_url('products/category/delete'); ?>", {'category_id': category_id}, function(data){
                if(data && data.error){
                    alert(data.msg);
                }
                else{
                    alert("删除成功！");
                }
                window.location.reload();
            }, 'json');
        }

    });
});



</script>