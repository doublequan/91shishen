<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('products/product'); ?>">商品管理</a></li>
            <li><a href="<?php echo site_url('products/good'); ?>">原料</a></li>
            <li class="active">原料列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('products/good'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">所属分类：</p>
                    </div>
                    <div class="form-group">
                        <select name="category_id" class="form-control input-sm input-sm" id="category_id">
                            <option value="">全部分类</option>
                            <?php echo getTreeOptions($category_list); ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <p class="form-control-static input-sm">&nbsp;&nbsp;原料名称：</p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control input-sm" name="keyword" value="<?php echo $params['keyword']; ?>">
                    </div>
                    
                    <div class="form-group" style="width:80px;">
                         <input type="submit" class="btn btn-primary btn-sm btn-block" value="查 询">
                    </div>

                    <a class="btn btn-primary btn-sm" id="add_form_btn" href="#add_form_page" kesrc="#add_form_page" style="margin-left:10px;">添加原料</a>
                    <a class="btn btn-danger btn-sm" href="<?php echo site_url('products/process'); ?>" style="float:right;width:140px;">原料加工</a>
                </form>
            </div>
            <?php if( $results ){ ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <td width="5%">#</td>
                        <th width="30%">原料名称</th>
                        <th width="10%">最新采购价</th>
                        <th width="10%">计量方式</th>
                        <th width="10%">计量单位</th>
                        <th width="10%">最小单位数量</th>
                        <th width="25%">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach( $results as $k=>$row ){ ?>
                    <tr>
                        <td>
                            <?php echo ($k+1); ?>
                        </td>
                        <td>
                            <?php echo $params['keyword'] ? str_replace($params['keyword'], '<font color="red">'.$params['keyword'].'</font>', $row['name']) : $row['name']; ?>
                        </td>
                        <td>
                            <?php echo $row['price'] ? '￥'.$row['price'] : '未录入价格'; ?>
                        </td>
                        <td>
                            <?php echo isset($methodMap[$row['method']]) ? $methodMap[$row['method']] : ''; ?>
                        </td>
                        <td>
                            <?php echo $row['unit']; ?>
                        </td>
                        <td>
                            <?php echo $row['amount']; ?>
                        </td>
                        <td good_id="<?php echo $row['id']; ?>" style="padding:6px;">
                            <a class="btn btn-xs btn-warning" href="<?php echo site_url('products/good_price').'?good_id='.$row['id']; ?>" target="_blank">价格变动</a>
                            <a href="#add_form_page" kesrc="#add_form_page"
                                class="btn btn-xs btn-success check_form_link">编辑</a>
                            <a class="btn btn-xs btn-danger delete_link" href="javascript:void(0);">删除</a>
                            <a class="btn btn-xs btn-info" href="<?php echo site_url('products/stock').'?qt=good&item_id='.$row['id']; ?>">库存</a>
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

<iframe src="" id="add_form_page" class="iframe_dialog" style="height:560px;"></iframe>

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
        href: "?<?php echo 'category_id='.$params['category_id'].'&keyword='.$params['keyword'].'&page=';?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });

    var category_selected = "<?php echo isset($params['category_id'])?$params['category_id']:0; ?>";
    $('#category_id option.cate_ops[value="' + category_selected + '"]').attr('selected', true);

    $('#search_form select').change(function(event) {
        $('#search_form').submit();
    });
    
    $('a#add_form_btn').click(function(){
        $('iframe#add_form_page').attr('src', "<?php echo site_url('products/good/add'); ?>");
        $("a#add_form_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            'afterClose': function(){
                window.parent.location.reload();
            },
        });
    });
        
    $("a.check_form_link").click(function() {
        var good_id = $(this).parent('td').attr('good_id');
        if(good_id){
            $('iframe#add_form_page').attr('src', "<?php echo site_url('products/good/edit').'?id='; ?>" + good_id);
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
        var good_id = $(this).parent('td').attr('good_id');

        if(good_id && confirm('确定删除选中原料？')){
            $.get("<?php echo site_url('products/good/delete'); ?>", {'good_id': good_id}, function(data){
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