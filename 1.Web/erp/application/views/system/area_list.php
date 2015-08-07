<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('system/system'); ?>">系统设置</a></li>
            <li><a href="<?php echo site_url('system/area'); ?>">地区管理</a></li>
            <li class="active">地区列表</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading" style="height:50px;">
                <form class="form-inline" action="<?php echo site_url('system/area'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">状态筛选：</p>
                    </div>
                    <div class="form-group">
                        <select name="disable" class="form-control input-sm">
                            <option value="0" <?php echo $params['disable']==0 ? ' selected' : ''; ?>>已启用地区列表</option>
                        </select>
                    </div>
                    <a class="btn btn-primary btn-sm" id="add_form_btn" style="margin-left:10px;"
                        href="#add_form_page" kesrc="#add_form_page">添加街道信息</a>
                    <a class="btn btn-primary btn-sm pull-right" id="add_city_btn" style="margin-left:10px;"
                        href="#add_city_dialog" kesrc="#add_city_dialog">启用新城市</a>
                </form>
            </div>
            <div class="panel-body" style="padding:0;">
                <div class="row" >
                    <div class="col-md-12">
                        <div class="panel-body-nav">
                            <div class="text-center pull-left" style="width:70px;">排序</div>
                            <div class="pull-left" style="width:240px;padding-left:34px;">名称</div>
                            <div class="text-center pull-right" style="width:120px;">操作</div>
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
                                if($tree_item['disable']==0 && $tree_item['deep']==2){
                                    $item_str .= '<div class="category_tab pull-right text-center" style="width:120px;"><a class="btn btn-xs btn-danger close_link" href="#" category_id="'.$tree_item['id'].'">关闭城市</a></div>';
                                }
                                // elseif($tree_item['disable']==1 && $tree_item['deep']==2){
                                //     $item_str .= '<div class="category_tab pull-right text-center" style="width:120px;"><a class="btn btn-xs btn-primary open_link" href="#" category_id="'.$tree_item['id'].'">启用城市</a></div>';
                                // }
                                elseif($tree_item['deep']==4){
                                    $item_str .= '<div class="category_tab pull-right text-center" style="width:120px;">';
                                    $item_str .= '<a class="btn btn-xs btn-success check_form_link" href="#add_form_page" kesrc="#add_form_page" category_id="'.$tree_item['id'].'">编辑</a>';
                                    $item_str .= '<a class="btn btn-xs btn-danger delete_link" style="margin-left:7px;" href="#" category_id="'.$tree_item['id'].'">删除</a>';
                                    $item_str .= '</div>';
                                }
                                
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
                            echo '<div class="alert alert-warning text-center" role="alert" style="margin-bottom:0;">查询结果为空！</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div id="add_city_dialog" style="height:300px;width:700px;display:none;padding:0 15px;">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                启用城市
            </h4>
        </div>
    </div>

    <form class="form-horizontal" action="<?php echo site_url('system/area/add_city'); ?>" 
            method="post" style="margin-top:15px;" id="add_city_form">
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">
                省/直辖市
            </label>
            <div class="col-sm-10">
                <select name="prov" class="form-control" id="prov">
                    <option value="">请选择省/直辖市</option>
                <?php foreach ($province_list as $province) { ?>
                    <option value="<?php echo $province['id']; ?>"><?php echo $province['name']; ?></option>
                <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-group city_select_line">
            <label for="" class="col-sm-2 control-label">
                城市
            </label>
            <div class="col-sm-10">
                <select name="city" class="form-control" id="city">
                    <option value="">请选择</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <div class="col-sm-offset-5 col-sm-2">
                <button type="submit" class="btn btn-primary btn-sm btn-block">启用城市</button>
            </div>
        </div>
    </form>
</div>

<iframe src="" id="add_form_page" style="height:500px;width:700px;"></iframe>

<script src="<?php echo base_url('static/js/jquery.fancybox.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/fuelux.tree.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
    $('#myTree').tree()

    $('a#add_city_btn').click(function(){
        $("a#add_city_btn").fancybox();
    });

    $('a#add_form_btn').click(function(){
        $('iframe#add_form_page').attr('src', "<?php echo site_url('system/area/street_add'); ?>");
        $("a#add_form_btn").fancybox({
            'afterClose': function(){
                window.parent.location.reload();
            },
        });
    });

    $('a.check_form_link').click(function(){
        var category_id = $(this).attr('category_id');
        $('iframe#add_form_page').attr('src', "<?php echo site_url('system/area/street_edit'); ?>" + '?id=' + category_id);
        $("a.check_form_link").fancybox({
            'afterClose': function(){
                window.parent.location.reload();
            },
        });
    });

    $('#search_form select').change(function(event) {
        $('#search_form').submit();
    });

    var get_city_list = function(province_id){
        var area_control = "<?php echo site_url('area/getCityList'); ?>";
        $.get(area_control, {'province_id': province_id}, function(data){
            var data = $.parseJSON(data);
            var options = [];
            var current_city_id;
            options.push('<option value="">请选择</option>');
            $.each(data, function(idx, city){
                if(idx == 0){
                    current_city_id = city.id;
                }
                options.push('<option value="' + city.id + '">' + city.name + '</option>');
            });
            $('#city').empty().append(options.join('')).parent('div').show();
        });
    }
    
    $('#prov').change(function(){
        var province_id = $(this).val();
        // var single_city = ['110000', '120000', '310000', '500000', '810000', '820000'];
        // if($.inArray(province_id, single_city) >= 0){
        //     $('.city_select_line').hide();
        // }
        // else{
        //     var city_id = get_city_list(province_id);
        // }
        var city_id = get_city_list(province_id);
    });

    $('.category_sort').blur(function(event) {
        var category_sort_old = $(this).attr('category_sort');
        var category_sort = $(this).val();

        if(category_sort_old != category_sort){
            if(window.confirm('检查到城市排序值已经修改，是否提交改动？')){
                var category_id = $(this).attr('category_id');

                var params = {
                    'category_id': category_id,
                    'category_sort': category_sort,
                };

                $.post("<?php echo site_url('system/area/updateSort'); ?>", params, function(data){
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

    $(".close_link").click(function(){
        var category_id = $(this).attr('category_id');
        if(category_id){
            if(confirm('是否确认关闭城市？'))
            $.post("<?php echo site_url('system/area/close_city'); ?>", {'category_id': category_id}, function(data){
                if(data && data.error){
                    alert(data.msg);
                }
                else{
                    alert("操作成功！");
                }
                window.location.reload();
            }, 'json');
        }
    });

    $('#add_city_form')
        .bootstrapValidator({
            prov: {
                validators: {
                    notEmpty: {
                        message: '请选择省/直辖市'
                    },
                }
            },
            city: {
                validators: {
                    notEmpty: {
                        message: '请选择城市'
                    },
                }
            },
        })
        .on('success.form.bv', function(e) {
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');

            $.post($form.attr('action'), $form.serialize(), function(rst_json) {
                if(rst_json.error != 0){
                    alert(rst_json.err_msg);
                    return false;
                }
                else{
                    alert('操作成功！');
                    window.location.reload();
                }
            }, 'json');
        });

    $(".delete_link").click(function(){
        var category_id = $(this).attr('category_id');
        if(category_id){
            if(confirm('是否确认删除街道信息？'))
            $.get("<?php echo site_url('system/area/delete'); ?>", {'category_id': category_id}, function(data){
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