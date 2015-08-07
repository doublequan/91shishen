<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('products/product'); ?>">商品管理</a></li>
            <li><a href="<?php echo site_url('products/stock'); ?>">库存管理</a></li>
            <li class="active">库存列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('products/stock'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">&nbsp;&nbsp;类型：</p>
                    </div>
                    <div class="form-group">
                        <select name="qt" class="form-control input-sm" id="qt">
                            <option value="good"<?php echo 'good'==$params['qt'] ? ' selected' : ''; ?>>[非标准] - 原料</option>
                            <option value="product"<?php echo 'product'==$params['qt'] ? 'selected' :''; ?>>[标准化] - 商品</option>
                        </select>
                    </div>
                    <div class="form-group" style="padding-left:15px;">
                        <p class="form-control-static input-sm">所属门店：</p>
                    </div>
                    <div class="form-group">
                        <select name="store_id" class="form-control input-sm input-sm" id="store_id">
                            <option value="0">全部门店</option>
                        <?php foreach( $stores as $k=>$row ) { ?>
                            <option value="<?php echo $k; ?>"<?php echo $k==$params['store_id'] ? ' selected' : ''; ?>><?php echo $row['name']; ?></option>
                        <?php } ?>
                        </select>
                    </div>
                    <div class="form-group" style="padding-left:15px;">
                        <p class="form-control-static input-sm">关键词：</p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control input-sm" name="keyword" value="<?php echo $params['keyword']; ?>">
                    </div>
                    <div class="form-group" style="width:80px;">
                         <input type="submit" class="btn btn-primary btn-sm btn-block" value="查 询">
                    </div>
                    <div class="form-group" style="width:80px;">
                         <input type="button" class="btn btn-info btn-sm btn-block" id="export" value="导出Excel">
                    </div>
                    <a class="btn btn-danger btn-sm" id="add_form_btn" href="<?php echo site_url('products/stock/add'); ?>">添加库存</a>
                </form>
            </div>
            <?php 
            if(!empty($results)){ 
                if($params['qt'] == 'good'){
            ?>
                <table class="table table-striped table-hover text-center">
                    <thead>
                        <tr>
                            <th width="5%">编号</th>
                            <th width="25%">原料名称</th>
                            <th width="15%">门店</th>
                            <th width="10%">库存数量</th>
                            <th width="10%">入库实时价格</th>
                            <th width="15%">库存变化时间</th>
                            <th width="20%">库存操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $row) { ?>
                        <tr>
                            <td>
                                <?php echo $row['good_id']; ?>
                            </td>
                            <td>
                                <?php if( $params['keyword'] ){ ?>
                                <?php echo str_replace($params['keyword'],'<font color="red">'.$params['keyword'].'</font>',$row['name']); ?>
                                <?php } else { ?>
                                <?php echo $row['name']; ?>
                                <?php } ?>
                            </td>
                            <td>
                                <?php echo $stores[$row['store_id']]['name']; ?>
                            </td>
                            <td>
                                <?php echo $row['amount']; ?>
                            </td>
                            <td>
                                ￥<?php echo $row['price']; ?>
                            </td>
                            <td>
                                <?php echo $row['change_time'] ? date('Y-m-d H:i:s', $row['change_time']) : ''; ?>
                            </td>
                            <td row_id="<?php echo $row['good_id']; ?>" type="1" data="<?php echo $stores[$row['store_id']]['name'];?>" store_id="<?php  echo $row['store_id'];?>">
                                <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-success btn-xs dealStock" flag='1'>添加库存</a>
                                <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-danger btn-xs dealStock" flag='2'>减少库存</a>
                                <a href="<?php echo $row['log_url']; ?>" class="btn btn-xs btn-info">库存变化记录</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php }elseif($params['qt'] == 'product'){ ?>
                <table class="table table-striped table-hover text-center">
                    <thead>
                        <tr>
                            <th width="10%">商品编号</th>
                            <th width="10%">商品货号</th>
                            <th width="15%">商品名称</th>
                            <th width="12%">门店</th>
                            <th width="8%">库存数量</th>
                            <th width="10%">入库实时价格</th>
                            <th width="15%">库存变化时间</th>
                            <th width="20%">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            foreach ($results as $row) {
                        ?>
                        <tr>
                            <td>
                                <?php echo $row['sku']; ?>
                            </td>
                            <td>
                                <?php echo $row['product_pin']; ?>
                            </td>
                            <td>
                                <?php if( $params['keyword'] ){ ?>
                                <?php echo str_replace($params['keyword'],'<font color="red">'.$params['keyword'].'</font>',$row['title']); ?>
                                <?php } else { ?>
                                <?php echo $row['title']; ?>
                                <?php } ?>
                            </td>
                            <td>
                                <?php echo $stores[$row['store_id']]['name']; ?>
                            </td>
                            <td>
                                <?php echo $row['amount']; ?>
                            </td>
                            <td>
                                ￥<?php echo $row['price']; ?>
                            </td>
                            <td>
                                <?php echo $row['change_time'] ? date('Y-m-d H:i:s', $row['change_time']) : ''; ?>
                            </td>
                            <td row_id="<?php echo $row['product_id']; ?>" type="2" data="<?php echo $stores[$row['store_id']]['name'];?>" store_id="<?php  echo $row['store_id'];?>">
                                <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-success btn-xs dealStock" flag="1">添加库存</a>
                                <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-danger btn-xs dealStock" flag="2">减少库存</a>
                                <a href="<?php echo $row['log_url']; ?>" class="btn btn-xs btn-info">库存变化记录</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
            <div class="col-md-12 text-right">
                <ul id="" class="pagination"></ul>
            </div>
            <?php }else{ ?>
            <div class="alert alert-warning col-md-12 text-center" role="alert">查询结果为空！</div>
            <?php } ?>
        </div>
    </div>
</div>

<iframe src="" id="add_form_page" style="height:400px;width:1000px;"></iframe>
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
        href: "?<?php echo 'qt='.$params['qt'].'&store_id='.$params['store_id'].'&keyword='.urlencode($params['keyword']).'&page=';?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });
    $('#search_form select').change(function(event){
        $('#search_form').submit();
    });
    $('#export').click(function(){
        var act = "<?php echo site_url('products/stock'); ?>";
        var tmp = "<?php echo site_url('products/stock/export'); ?>";
        $('#search_form').attr('action',tmp).submit().attr('action',act);
    });
    $('a.dealStock').click(function(){
        var type = $(this).parent('td').attr('type');
        var id = $(this).parent('td').attr('row_id');
        var flag = $(this).attr('flag');
        var data = $(this).parent('td').attr('data');
        var store_id = $(this).parent('td').attr('store_id');
        $('iframe#add_form_page')
            .attr('src', "<?php echo site_url('products/stock/deal').'?type='; ?>"+type+"&id="+id+"&flag="+flag+"&data="+data+"&store_id="+store_id)
            .css('height','400px');
        $("a.dealStock").fancybox({
            'hideOnContentClick': true,
            'padding':0,
        });
    });
});
</script>