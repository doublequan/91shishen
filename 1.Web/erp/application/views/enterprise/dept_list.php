<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('enterprise/company'); ?>">企业管理</a></li>
            <li><a href="<?php echo site_url('enterprise/dept'); ?>">部门</a></li>
            <li class="active">部门列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('enterprise/dept'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">所属网站：</p>
                    </div>
                    <div class="form-group">
                        <select name="company_id" class="form-control input-sm" id="company_id" style="width:150px;">
                        <?php foreach ($companys as $company) { ?>
                            <option value="<?php echo $company['id']; ?>" <?php echo ($company['id'] == @$company_id)?'selected':''; ?>><?php echo trim($company['name']); ?></option>
                        <?php } ?>
                        </select>
                    </div>
                    
                    <a class="btn btn-primary btn-sm" id="add_form_btn" href="#add_form_page" kesrc="#add_form_page">添加部门</a>
                </form>
            </div>
            <div class="panel-body" style="padding:0;">
                <div class="row" >
                    <div class="col-md-12">
                        <div class="panel-body-nav">
                            <div class="pull-left" style="width:200px;padding-left:45px;">部门名称</div>
                            <div class="text-center pull-right" style="width:120px;">操作</div>
                        </div>
                    </div>
                </div>
                <div class="row fuelux">
                    <div class="col-md-12">
                        <?php 
                        function show_dept_tree($results){
                            $item_str = '<ul id="myTree" role="tree" class="tree">';
                            foreach ($results as $tree_item) {
                                $item_str .= '<li aria-expanded="false" role="treeitem" class="tree-branch" id="treeitem_';
                                $item_str .= $tree_item['id'].'" aria-labelledby="treeitem_'.$tree_item['id'].'-label">';
                                $item_str .= '<div class="tree-branch-header"><button class="glyphicon icon-caret glyphicon-play"><span class="sr-only">查看</span></button>';
                                $item_str .= '<button class="tree-branch-name"><span class="glyphicon icon-folder glyphicon-folder-close"></span>';
                                $item_str .= '<span class="tree-label" id="treeitem_'.$tree_item['id'].'-label">'.$tree_item['name'].'</span></button>';
                                $item_str .= '<div class="category_tab pull-right text-center" style="width:120px;"><a class="btn btn-xs btn-success check_form_link" href="#add_form_page" kesrc="#add_form_page" dept_id="'.$tree_item['id'].'">查看/编辑</a></div>';
                                $item_str .= '</div>';
                                if(!empty($tree_item['child'])){
                                    $item_str .= '<ul role="group" class="tree-branch-children hide">';
                                    $item_str .= show_dept_tree($tree_item['child']);
                                    $item_str .= '</ul>';
                                }
                                $item_str .= '</li>';
                            }
                            $item_str .= '</ul>';
                            return $item_str;
                        }
                        if(!empty($results)){
                            echo show_dept_tree($results);
                        }else {
                            echo '<div class="alert alert-warning" role="alert" style="margin-bottom:0;">无部门信息，请添加！</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<iframe src="" id="add_form_page" style="height:400px;width:900px;"></iframe>

<script src="<?php echo base_url('static/js/jquery.fancybox.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/fuelux.tree.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
    $('#myTree').tree()

    $('#company_id').change(function(event) {
        $('#search_form').submit();
    });

    $('a#add_form_btn').click(function(){
        $('iframe#add_form_page').attr('src', "<?php echo site_url('enterprise/dept/add'); ?>");
        $("a#add_form_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            'afterClose': function(){
                window.parent.location.reload();
            },
        });
    });
        
    $("a.check_form_link").click(function() {
        var dept_id = $(this).attr('dept_id');
        if(dept_id){
            $('iframe#add_form_page').attr('src', "<?php echo site_url('enterprise/dept/edit'); ?>" + '?id=' + dept_id);
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
        var dept_id = $(this).parent('td').attr('dept_id');

        if(dept_id){
            $.get("<?php echo site_url('department/delete'); ?>", {'dept_id': dept_id}, function(data){
                var data = $.parseJSON(data);

                if(data && data.error){
                    alert(data.msg);
                }
                else{
                    alert("删除成功！");
                }
                window.location.reload();
            });
        }

    });
});



</script>