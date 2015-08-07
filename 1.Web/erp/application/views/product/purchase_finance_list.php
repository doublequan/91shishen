<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('products/product'); ?>">商品管理</a></li>
            <li><a href="<?php echo site_url('products/purchase_finance'); ?>">财务单</a></li>
            <li class="active">财务单列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('products/purchase_finance'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">财务单状态：</p>
                    </div>
                    <div class="form-group">
                        <select name="status" class="form-control input-sm" id="status">
                        <?php foreach ($statusMap as $statusMap_key => $statusMap_value) { ?>
                            <option value="<?php echo $statusMap_key; ?>" <?php echo $statusMap_key==$params['status']?'selected':''; ?>><?php echo $statusMap_value; ?></option>
                        <?php } ?>  
                        </select>
                    </div>
                    <div class="form-group">
                        <p class="form-control-static input-sm">&nbsp;&nbsp;财务单编号：</p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control input-sm" name="keyword" value="<?php echo $params['keyword']; ?>" style="width:200px;">
                    </div>
                    <div class="form-group" style="width:80px;">
                        <input type="submit" class="btn btn-primary btn-sm btn-block" value="查 询">
                    </div>

                    <!-- <a class="btn btn-primary btn-sm" id="add_form_btn" href="#add_form_page" kesrc="#add_form_page">添加财务单</a> -->
                </form>
            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th style="width:120px;">
                            财务单编号
                        </th>
                        <th style="width:120px;">
                            采购单编号
                        </th>
                        <th style="width:80px;">
                            申请金额
                        </th>
                        <th style="width:80px;">
                            支付方式
                        </th>
                        <th style="width:80px;">
                            申请员工
                        </th>
                        <th style="width:80px;">
                            状态
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
                            <?php echo $single_info['purchase_id']; ?>
                        </td>
                        <td>
                            ￥<?php echo $single_info['money_apply']; ?>
                        </td>
                        <td>
                            <?php 
                            switch($single_info['pay_type']){
                                case 1:
                                    echo '现金支付';
                                    break;
                                case 2:
                                    echo '支付宝支付';
                                    break;
                                case 3:
                                    echo '银行转账';
                                    break;
                                default:
                                    echo '<span class="label label-default">未知</span>';
                                    break;
                            }
                            ?>
                        </td>
                        <td>
                            <?php echo @$single_info['apply_name']; ?>
                        </td>
                        <td>
                            <?php 
                            switch($single_info['status']){
                                case 0:
                                    echo '<span class="label label-info">新申请</span>';
                                    break;
                                case 1:
                                    echo '<span class="label label-primary">已提交</span>';
                                    break;
                                case 2:
                                    echo '<span class="label label-warning">已审核</span>';
                                    break;
                                case 3:
                                    echo '<span class="label label-success">已结算</span>';
                                    break;
                                default:
                                    echo '<span class="label label-default">未知</span>';
                                    break;
                            }
                            ?>
                        </td>
                        <td style="padding:6px;">
                            <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-xs btn-info check_form_link"
                                purchase_finance_id="<?php echo $single_info['id']; ?>">查看</a>
                            <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-xs btn-danger edit_form_link" 
                                purchase_finance_id="<?php echo $single_info['id']; ?>">编辑</a>
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

<iframe src="" id="add_form_page" class="iframe_dialog" style="height:450px;"></iframe>

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
        href: "?<?php echo 'status='.$params['status'].'&page=';?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });

    $('#search_form select').change(function(event) {
        $('#search_form').submit();
    });
    
    $("a.check_form_link").click(function() {
        var purchase_finance_id = $(this).attr('purchase_finance_id');
        if(purchase_finance_id){
            $('iframe#add_form_page').attr('src', "<?php echo site_url('products/purchase_finance/detail').'?purchase_id='; ?>" + purchase_finance_id);
            $("a.check_form_link").fancybox({
                'hideOnContentClick': true,
                'padding':0,
            });
        }
    });

    $("a.edit_form_link").click(function() {
        var purchase_finance_id = $(this).attr('purchase_finance_id');
        if(purchase_finance_id){
            $('iframe#add_form_page').attr('src', "<?php echo site_url('products/purchase_finance/edit').'?purchase_id='; ?>" + purchase_finance_id);
            $("a.edit_form_link").fancybox({
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