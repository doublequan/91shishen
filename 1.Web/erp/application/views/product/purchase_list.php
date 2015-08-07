<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('products/product'); ?>">商品管理</a></li>
            <li><a href="<?php echo site_url('products/purchase'); ?>">采购管理</a></li>
            <li class="active">采购单列表</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading" style="height:50px;">
                <form class="form-inline" action="<?php echo site_url('products/purchase'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">采购类型：</p>
                    </div>
                    <div class="form-group">
                        <select name="type" class="form-control input-sm">
                        <?php foreach ($typeMap as $single_type_key => $single_type) { ?>
                            <option value="<?php echo $single_type_key; ?>" <?php echo $single_type_key==$params['type']?'selected':''; ?>><?php echo trim($single_type); ?></option>
                        <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <p class="form-control-static input-sm">&nbsp;&nbsp;采购单状态：</p>
                    </div>
                    <div class="form-group">
                        <select name="status" class="form-control input-sm">
                        <?php foreach ($statusMap as $single_status_key => $single_status) { ?>
                            <option value="<?php echo $single_status_key; ?>" <?php echo $single_status_key==$params['status']?'selected':''; ?>><?php echo trim($single_status); ?></option>
                        <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <p class="form-control-static input-sm">&nbsp;&nbsp;采购单编号：</p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control input-sm" name="keyword" value="<?php echo $params['keyword']; ?>" style="width:200px;">
                    </div>
                    <div class="form-group" style="width:80px;">
                        <input type="submit" class="btn btn-primary btn-sm btn-block" value="查 询">
                    </div>

                    <a class="btn btn-primary btn-sm" id="add_form_btn" href="<?php echo site_url('products/purchase/add'); ?>">新增采购单</a>
                </form>
                
            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th width="5%" class="text-center">
                            <input type="checkbox" id="check_all_chk">
                        </th>
                    	<th width="15%" style="text-align:center;">
                            采购单编号
                        </th>
                        <th width="14%">
                            入库门店
                        </th>
                        <th width="9%">
                            采购类型
                        </th>
                        <th width="9%">
                            支付方式
                        </th>
                        <th width="12%">
                            预借金额
                        </th>
                        <th width="8%" style="text-align:right;padding-right:30px;">
                            状态
                        </th>
                        <th width="8%" style="text-align:left;padding-left:30px;">
                            单据
                        </th>
                        <th width="30%">
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
                            <?php if( $single_info['finance_status']==2 ){ ?>
                            <input type="checkbox" name="check_single_chk" class="check_single_chk" value="<?php echo $single_info['id']; ?>">
                            <?php } else { ?>
                            <input type="checkbox" value="0" disabled>
                            <?php } ?>
                        </td>
                        <td>
                            <?php echo $single_info['id']; ?>
                        </td>
                        <td>
                            <?php echo $store_list[$single_info['store_id']]['name']; ?>
                        </td>
                        <td>
                            <?php echo $typeMap[$single_info['type']];?>
                        </td>
                        <td>
                            <?php echo $checkoutMap[$single_info['checkout_type']];?>
                        </td>
                        <td>
                            ￥<?php echo $single_info['price_borrow']; ?>
                        </td>
                        <td class="text-right" style="padding-right:10px;">
                            <?php if( $single_info['finance_status']==0 ){ ?>
                            <span class="label label-danger" title="缺少财务单">财务单不存在</span>
                            <?php } elseif( $single_info['finance_status']==1 ){ ?>
                            <span class="label label-warning" title="财务单未审核">财务单未审核</span>
                            <?php } elseif( $single_info['finance_status']==2 ){ ?>
                            <span class="label label-success" title="财务单已审核">财务单已审核</span>
                            <?php } elseif( $single_info['finance_status']==3 ){ ?>
                            <span class="label label-info" title="财务单已结算">财务单已结算</span>
                            <?php } ?>
                        </td>
                        <td row_id="<?php echo $single_info['id']; ?>" class="text-left" style="padding:6px;padding-left:10px;">
                            <?php if( $single_info['finance_status']==0 ){ ?>
                            <a class="btn btn-xs btn-primary add_finance_link" kesrc="#add_form_page"
                                href="#add_form_page" purchase_id="<?php echo $single_info['id']; ?>">填写财务单</a>
                            <?php } else { ?>
                            <a class="btn btn-xs btn-default check_finance_link" kesrc="#add_form_page"
                                href="#add_form_page" purchase_id="<?php echo $single_info['id']; ?>">查看财务单</a>
                            <?php } ?>
                        </td>
                        <td row_id="<?php echo $single_info['id']; ?>" style="padding:6px;">
                            <a class="btn btn-xs btn-default check_link" href="#add_form_page" kesrc="#add_form_page">查看</a>
                        <?php if ( $params['status']==0 ) { ?>
                            <a class="btn btn-xs btn-primary" href="<?php echo site_url('products/purchase/edit').'?id='.$single_info['id']; ?>" >编辑</a>
                        <?php } elseif ( $params['status']==2 ) { ?>
                        <?php } elseif ( $params['status']==3 ) { ?>
                            <a class="btn btn-xs btn-primary receive" href="#add_form_page" kesrc="#add_form_page">入库</a>
                        <?php } elseif ( $params['status']==4 ) { ?>
                            <a class="btn btn-xs btn-primary checkout" href="#add_form_page" kesrc="#add_form_page">结算</a>
                        <?php } ?>
                            <a class="btn btn-xs btn-info" target="_blank" href="<?php echo site_url('products/purchase/purchase_print').'?id='.$single_info['id']; ?>">打印采购单</a>
                            <a href="#" class="btn btn-xs btn-danger delete_link">删除</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="panel-footer">
                <?php if( $params['status']==0 ){ ?>
                <button type="button" class="btn btn-sm btn-primary doAction" action="submit">提交审核所选采购单</button>
                <?php } elseif ( $params['status']==1 ) { ?>
                <button type="button" class="btn btn-sm btn-primary doAction" action="approve">批准所选采购单</button>
                <?php } elseif ( $params['status']==2 ) { ?>
                <button type="button" class="btn btn-sm btn-primary doAction" action="purchase">开始执行所选采购单</button>
                <?php } else { ?>
                <button type="button" class="btn btn-sm btn-default" onclick="javascript:alert('Good Luck');">暂无操作</button>
                <?php } ?>
                <button type="button" class="btn btn-sm btn-primary" id="mutiPrintBtn">批量打印所选采购单</button>
                <div class="form-group text-right" style="float:right;">
                    任务移交给：
                    <select name="transfer_eid" id="transfer_eid" class="input-sm">
                        <?php foreach ($employees as $row) { ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['username']; ?></option>
                        <?php } ?>
                    </select>  
                    <button type="button" class="btn btn-sm btn-primary doAction" action="transfer">转移给所选用户处理</button>
                </div>
            </div>
            <div class="col-md-12 text-right">
                <ul id="" class="pagination"></ul>
            </div>
            <?php }else{ ?>
            <div class="alert alert-warning col-md-12 text-center" role="alert">查询结果为空！</div>
            <?php } ?>
        </div>
    </div>
</div>

<iframe src="" id="add_form_page"></iframe>

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
        href: "?<?php echo 'type='.$params['type'].'&status='.$params['status'].'&page=';?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });

    $(".doAction").click(function(){
        var action = $(this).attr('action');
        var transfer_eid = 0;
        var msg = '';
        if( action=='submit' ){
            msg = '提交审核所选采购单';
        } else if( action=='approve' ) {
            msg = '批准所选采购单';
        } else if( action=='purchase' ) {
            msg = '开始执行所选采购单';
        } else if( action=='transfer' ) {
            transfer_eid = $('#transfer_eid').val();
            msg = '所选采购单转移给 '+($('#transfer_eid option:selected').text())+'';
        }
        if( confirm('确定'+msg+'？') ){
            var ids = new Array();
            $('.check_single_chk').each(function(){
                if( $(this).attr('checked')=='checked' ){
                    ids.push($(this).val());
                }
            });
            if( ids.length==0 ){
                alert('请至少选择一个采购单');
                return false;
            }
            $.post("<?php echo site_url('products/purchase/doAction'); ?>", {'id':ids.toString(),'action':action,'transfer_eid':transfer_eid}, function(data){
                var data = $.parseJSON(data);
                if(data && data.err_no==0){
                    alert('操作成功！');
                    window.location.href = "<?php echo site_url('products/purchase?type='.$params['type'].'&status='.$params['status']); ?>";
                } else {
                    alert('操作失败！');
                }
            });
        }
    });

    $('#search_form select').change(function(event) {
        $('#search_form').submit();
    });

    $('a.check_link').click(function(){
        var single_id = $(this).parent('td').attr('row_id');
        $('iframe#add_form_page')
            .attr('src', "<?php echo site_url('products/purchase/detail').'?id='; ?>" + single_id)
            .css('height','640px');
        $("a.check_link").fancybox({
            'hideOnContentClick': true,
            'padding':0,
        });
    });

    $('a.receive').click(function(){
        var single_id = $(this).parent('td').attr('row_id');
        $('iframe#add_form_page')
            .attr('src', "<?php echo site_url('products/purchase/receive').'?id='; ?>" + single_id)
            .css('height','640px');
        $("a.receive").fancybox({
            'hideOnContentClick': true,
            'padding':0,
        });
    });

    $('a.checkout').click(function(){
        var single_id = $(this).parent('td').attr('row_id');
        $('iframe#add_form_page')
            .attr('src', "<?php echo site_url('products/purchase_finance/checkout').'?purchase_id='; ?>" + single_id)
            .css('height','640px');
        $("a.checkout").fancybox({
            'hideOnContentClick': true,
            'padding':0,
        });
    });

    $('a.add_finance_link').click(function(){
        $('iframe#add_form_page')
            .attr('src', "<?php echo site_url('products/purchase_finance/add').'?purchase_id='; ?>" + $(this).attr('purchase_id'))
            .css('height','450px');
        $("a.add_finance_link").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            'afterClose': function(){
                window.parent.location.reload();
            },
        });
    });

    $("a.check_finance_link").click(function() {
        $('iframe#add_form_page')
            .attr('src', "<?php echo site_url('products/purchase_finance/detail').'?purchase_id='; ?>" + $(this).attr('purchase_id'))
            .css('height','450px');
        $("a.check_finance_link").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            'afterClose': function(){
                window.parent.location.reload();
            },
        });
    });

    $(".delete_link").click(function(){
        if( confirm('此操作不可恢复，您确定删除吗？') ){
            var tThis = $(this);
            var row_id = tThis.parent('td').attr('row_id');
            if(row_id){
                $.get("<?php echo site_url('products/purchase/delete'); ?>", {'id': row_id}, function(data){
                    var data = $.parseJSON(data);
                    if(data && data.err_no==0){
                        tThis.parent().parent().hide(600, function(){
                            tThis.parent().parent().remove();
                            alert('操作成功！');
                        });
                    } else {
                        alert('操作失败！');
                    }
                });
            }
        }
    });

    $("#mutiPrintBtn").click(function(){
        var purchase_ids = [];
        $('.check_single_chk').each(function(){
            if( $(this).attr('checked')=='checked' ){
                purchase_ids.push($(this).val());
            }
        });
        if( purchase_ids.length==0 ){
            alert('请至少选择一个采购单');
            return false;
        }

        var muti_print_url = "<?php echo site_url('products/purchase/purchase_print_muti'); ?>";
        muti_print_url += ('?purchase_ids=' + purchase_ids.join(','));
        window.open(muti_print_url);
    });

});
</script>