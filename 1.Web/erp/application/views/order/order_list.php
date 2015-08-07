<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/datetimepicker.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('order/order'); ?>">订单管理</a></li>
            <li class="active"><?php echo $params['status']==-1 ? '全部订单' : $order_status_types[$params['status']]; ?>列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <form class="form-inline" action="<?php echo site_url('order/order'); ?>" method="get" id="search_form">
        <div class="panel panel-default">
            <div class="panel-heading" style="height:50px;">
                <input type="hidden" name="status" value="<?php echo $params['status']; ?>">
                <input type="hidden" name="size" value="<?php echo $params['size']; ?>">
                <div class="form-group" style="padding-left:10px;">
                    订单号：
                    <input id="keyword" type="text" class="form-control input-sm" name="keyword" value="<?php echo $params['keyword'] ? $params['keyword'] : ''; ?>" placeholder="订单号" style="width:200px;">
                </div>            
                <div class="form-group" style="width: 80px;">
                     <input type="submit" class="btn btn-primary btn-sm btn-block" value="查 询">
                </div>               
                <div class="form-group" style="width:80px;">
                     <a class="btn btn-primary btn-sm btn-block" id="adv_search_btn" href="#adv_search_dialog" kesrc="#adv_search_dialog">高级搜索</a>
                </div>
                <div class="form-group" style="width:80px;margin-left:20px;">
                     <input type="button" class="btn btn-info btn-sm btn-block" id="export" value="导出Excel">
                </div>                 
            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th width="3%" class="text-center">
                            <input type="checkbox" id="check_all_chk">
                        </th>
                        <th width="4%">
                            #
                        </th>
                        <th width="13%">
                            订单号
                        </th>
                        <th width="7%">
                            订单总价
                        </th>
                        <th width="10%">
                            所属用户
                        </th>
                        <th width="7%">
                            收货人
                        </th>
                        <th width="9%">
                            订单状态
                        </th>
                        <th width="12%">
                            预计配送时间
                        </th>
                        <th width="12%">
                            订单生成时间
                        </th>
                        <th width="12%">
                            配送门店
                        </th>
                        <th width="10%">
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
                            <input type="checkbox" class="check_single_chk" value="<?php echo $single_info['id']; ?>">
                        </td>
                        <td>
                            <?php echo ($k+1); ?>
                        </td>
                        <td>
                            <!-- <?php echo $single_info['order_id']; ?> -->
                            <?php echo $single_info['order_id']; ?>
                        </td>
                        <td>
                            ¥<?php echo $single_info['price']; ?>
                        </td>
                        <td>
                            <?php echo isset($users[$single_info['uid']]) ? $users[$single_info['uid']] : '未知用户'; ?>
                        </td>
                        <td>
                            <?php echo $single_info['receiver']; ?>
                        </td>
                        <td>
                            <?php echo isset($order_status_types[$single_info['order_status']]) ? $order_status_types[$single_info['order_status']] : '未知状态'; ?>
                        </td>
                        <td>
                            <?php echo $single_info['date_day']; ?> -
                            <?php echo $single_info['date_noon']==1 ? '上午' : ($single_info['date_noon']==2 ? '下午' : '不限'); ?>
                        </td>
                        <td>
                            <?php echo date('Y-m-d H:i:s',$single_info['create_time']); ?>
                        </td>
                        <td>
                            <?php echo isset($stores[$single_info['store_id']]) ? $stores[$single_info['store_id']]['name'] : ''; ?>
                        </td>
                        <td row_id="<?php echo $single_info['id']; ?>" style="padding:6px;">
                            <a href="<?php echo site_url('order/order/detail?order_id='.$single_info['id']); ?>" class="btn btn-xs btn-success">详情</a>
                            <a href="<?php echo site_url('order/order/order_print?order_id='.$single_info['id']); ?>" 
                                 class="btn btn-xs btn-info" target="_blank">配送单</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="panel-footer">
                <button type="button" class="btn btn-sm btn-danger" id="createProductStat">生成商品汇总单</button>
                <button type="button" class="btn btn-sm btn-info" id="createPurchase">单生成采购单</button>
                <button type="button" class="btn btn-sm btn-info" id="mutiPrintBtn">批量打印配送单</button>
                <button type="button" class="btn btn-sm btn-info" id="mutiLabelPrintBtn">批量打印商品标签</button>
                <?php if( !($params['status']==10 || $params['status']==11) ) { ?>
                    <?php if($params['status'] == 0){ ?>
                    <button type="button" class="btn btn-sm btn-primary mutiDealOrderBtn" status="21">批量确认所选订单</button>
                    <?php } elseif($params['status'] == 1 || $params['status'] == 2) { ?>
                    <button type="button" class="btn btn-sm btn-primary mutiDealOrderBtn" status="21">批量确认所选订单</button>
                    <?php } elseif($params['status'] == 21) { ?>
                    <button type="button" class="btn btn-sm btn-primary mutiDealOrderBtn" status="27">所选订单批量出库</button>
                    <?php } elseif($params['status'] == 27) { ?>
                    <button type="button" class="btn btn-sm btn-primary mutiDealOrderBtn" status="20">批量完成所选订单</button>
                    <?php } ?>
                    <div class='pull-right'>
                        <button type="button" class="btn btn-sm btn-danger mutiDealOrderBtn" status="11">批量取消订单</button>
                        <button type="button" class="btn btn-sm btn-danger mutiDealOrderBtn" status="10">批量删除订单</button>
                    </div>
                <?php } ?>
            </div>
            <div class="col-md-12 text-right">
                <ul id="" class="pagination"></ul>
            </div>
            <?php }else{ ?>
            <div class="alert alert-warning col-md-12 text-center" role="alert">查询结果为空！</div>
            <?php } ?>
        </div>
        </form>
    </div>
</div>

<div id="adv_search_dialog" style="height:auto;width:700px;display:none;padding:0 15px;">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                订单高级搜索
            </h4>
        </div>
    </div>

    <form class="form-horizontal" action="<?php echo site_url('order/order'); ?>" method="get" style="margin-top:15px;" id="adv_search_form">
        <div class="form-group form-group-sm">
            <label for="" class="col-sm-2 control-label">
                订单状态
            </label>
            <div class="col-sm-8">
                <select name="status" class="form-control">
                    <?php if( $order_status_types ) { ?>
                    <?php foreach( $order_status_types as $k=>$v ) { ?>
                    <option value="<?php echo $k; ?>"<?php echo $k==$params['status'] ? ' selected' : ''; ?>><?php echo $v; ?></option>
                    <?php } ?>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label for="" class="col-sm-2 control-label">
                分站/配送门店
            </label>
            <div class="col-sm-4">
                <select name="site_id" id="site_id" class="form-control">
                    <option value="0">不限</option>
                    <?php if( $sites ) { ?>
                    <?php foreach( $sites as $k=>$row ) { ?>
                    <option value="<?php echo $k; ?>"<?php echo $k==$params['site_id'] ? ' selected' : ''; ?>><?php echo $row['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                </select>
            </div>
            <div class="col-sm-4">
                <select name="store_id" id="store_id" class="form-control">
                    <option value="0">不限</option>
                </select>
            </div>
        </div>   
        <div class="form-group form-group-sm">
            <label for="" class="col-sm-2 control-label">
                出仓门店
            </label>
            <div class="col-sm-4">
                <select name="deal_site_id" id="deal_site_id" class="form-control">
                    <option value="0">不限</option>
                    <?php if( $sites ) { ?>
                    <?php foreach( $sites as $k=>$row ) { ?>
                    <option value="<?php echo $k; ?>"<?php echo $k==$params['deal_site_id'] ? ' selected' : ''; ?>><?php echo $row['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                </select>
            </div>
            <div class="col-sm-4">
                <select name="deal_store_id" id="deal_store_id" class="form-control">
                    <option value="0">不限</option>
                </select>
            </div>
        </div>           
        <div class="form-group form-group-sm">
            <label for="" class="col-sm-2 control-label">
                支付状态
            </label>
            <div class="col-sm-8">
                <select name="pay_status" class="form-control">
                    <option value="-1">不限</option>
                    <?php foreach ($pay_status_types as $k=>$v) { ?>
                    <option value="<?php echo $k; ?>"<?php echo $k==$params['pay_status'] ? ' selected' : ''; ?>><?php echo trim($v); ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label for="" class="col-sm-2 control-label">
                订单生成日期
            </label>
            <div class="col-sm-4">
                <input type="text" name="order_strat_date" class="form-control" id="order_strat_date"
                    value="<?php echo $params['order_strat_date']; ?>" readonly>
            </div>
            <div class="col-sm-4">
                <input type="text" name="order_end_date" class="form-control" id="order_end_date"
                    value="<?php echo $params['order_end_date']; ?>" readonly>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label for="" class="col-sm-2 control-label">
                配送日期
            </label>
            <div class="col-sm-4">
                <input type="text" name="date_day" class="form-control" id="date_day"
                    value="<?php echo $params['date_day']; ?>" readonly>
            </div>
            <label class="col-sm-1" style="width:100px;">
                <input type="radio" name="date_noon" value="0"<?php echo $params['date_noon']==0 ? ' checked' : ''; ?>> 不限时段
            </label>
            <label class="col-sm-1" style="width:80px;">
                <input type="radio" name="date_noon" value="1"<?php echo $params['date_noon']==1 ? ' checked' : ''; ?>> 上午
            </label>
            <label class="col-sm-1" style="width:80px;">
                <input type="radio" name="date_noon" value="2"<?php echo $params['date_noon']==2 ? ' checked' : ''; ?>> 下午
            </label>
        </div>
        <div class="form-group form-group-sm">
            <label for="" class="col-sm-2 control-label">
                订单价格区间
            </label>
            <div class="col-sm-2">
                <input type="text" name="price_from" class="form-control" id="price_from" placeholder="￥"
                    value="<?php echo $params['price_from']; ?>">
            </div>
            <div class="col-sm-1 text-center">
                <p class="form-control-static"> - </p>
            </div>
            <div class="col-sm-2">
                <input type="text" name="price_to" class="form-control" id="price_to" placeholder="￥"
                    value="<?php echo $params['price_to']; ?>">
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label for="" class="col-sm-2 control-label">
                下单用户
            </label>
            <div class="col-sm-5">
                <input type="text" name="username" class="form-control" id="username" placeholder="用户名"
                    value="<?php echo $params['username']; ?>">
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label for="" class="col-sm-2 control-label">
                支付方式
            </label>
           <label class="col-sm-1" style="width:100px;">
                <input type="radio" name="pay_type" class="" id="pay_type"
                    value="1">货到付款
            </label>
            <label class="col-sm-1" style="width:80px;">
                <input type="radio" name="pay_type" class="" id="pay_type"
                    value="2">支付宝
            </label>
            <label class="col-sm-1" style="width:120px;">
                <input type="radio" name="pay_type" class="" id="pay_type"
                    value="3">会员卡支付
            </label>
        </div>
        <div class="form-group form-group-sm">
            <label for="" class="col-sm-2 control-label">
                产品名称
            </label>
            <div class="col-sm-5">
                <input id="product_name" type="text" class="form-control" name="product_name" value="<?php echo $params['product_name'] ? $params['product_name'] : ''; ?>" placeholder="产品名称">                    
            </div>           
        </div>                  
        <div class="form-group form-group-sm">
            <label for="" class="col-sm-2 control-label">
                每页数量
            </label>
            <div class="col-sm-4">
                <select name="size" class="form-control">
                    <option value="20"<?php echo $params['size']==20 ? ' selected' : ''; ?>>每页显示20条</option>
                    <option value="50"<?php echo $params['size']==50 ? ' selected' : ''; ?>>每页显示50条</option>
                    <option value="100"<?php echo $params['size']==100 ? ' selected' : ''; ?>>每页显示100条</option>
                    <option value="200"<?php echo $params['size']==200 ? ' selected' : ''; ?>>每页显示200条</option>
                    <option value="500"<?php echo $params['size']==500 ? ' selected' : ''; ?>>每页显示500条</option>
                </select>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <div class="col-sm-offset-4 col-sm-2">
                <button type="submit" class="btn btn-primary btn-sm btn-block" name='advquery'>查询</button>
            </div>
            <div class="col-sm-2">
                <a href="javascript:void(0);" class="btn btn-default btn-sm btn-block" id="adv_dialog_cancel">重置</a>
            </div>
        </div>
    </form>
</div>

<form class="hidden" action="<?php echo site_url('products/purchase/create_from_user_order'); ?>" method="post" id="purchase_form">
</form>

<script src="<?php echo base_url('static/js/jquery.fancybox.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/jquery.twbsPagination.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/bootstrap-datetimepicker.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
var storeMap = <?php echo $storeMap ? json_encode($storeMap) : '[]'; ?>;
$(function(){
    $('.pagination').twbsPagination({
        totalPages: <?php echo isset($pager['pages'])?$pager['pages']:1; ?>,
        visiblePages: 5,
        startPage: <?php echo isset($pager['page'])?$pager['page']:1; ?>,
        first: '首页',
        prev: '上一页',
        next: '下一页',
        last: '尾页',
        href: "?<?php echo $url.'&page='; ?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });

    $('a#adv_search_btn').click(function(){
        $("a#adv_search_btn").fancybox();
    });

    $('a#adv_dialog_cancel').click(function(){
        $('#adv_search_dialog form input').val('');
        $('#adv_search_dialog form select option:first-child').attr('selected', true);
    });

    $('#order_strat_date, #order_end_date').datetimepicker({
        language:  'zh',
        format: 'yyyy-mm-dd HH:ii:ss',
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        hourStep: 1,
        minuteStep: 1,
        secondStep: 1,
        minView: 0,
        maxView: 3,
    });

    $('#date_day').datetimepicker({
        language:  'zh',
        format: 'yyyy-mm-dd',
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        maxView: 3,
    });

    $("#order_strat_date, #order_end_date").change(function(){
        if($("#order_strat_date").val() && $("#order_end_date").val()){
            var order_strat_date = parseInt(new Date($("#order_strat_date").val()).getTime());
            var order_end_date = parseInt(new Date($("#order_end_date").val()).getTime());
            if(order_strat_date > order_end_date){
                $(this).val('');
                return alert("开始时间需小于结束时间！");
            }
        }
    });

    var initStore = function(site_id,store_id){
        var options = [];
        options.push('<option value="0">不限</option>');
        var arr = storeMap[site_id];
        if( arr!=undefined ){
            $.each(arr, function(i, row){
                options.push('<option value="'+row.id+'"'+( row.id==store_id ? ' selected' : '' )+'>'+row.name+'</option>');
            });
        }
        $('#store_id').empty().append(options.join(''));
    }
    initStore(<?php echo $params['site_id']; ?>,<?php echo $params['store_id']; ?>);
    $('#site_id').change(function(){
        var site_id = $(this).val();
        initStore(site_id,0);
    });

    var initDealStore = function(deal_site_id,deal_store_id){
        var options = [];
        options.push('<option value="0">不限</option>');
        var arr = storeMap[deal_site_id];
        if( arr!=undefined ){
            $.each(arr, function(i, row){
                options.push('<option value="'+row.id+'"'+( row.id==deal_store_id ? ' selected' : '' )+'>'+row.name+'</option>');
            });
        }
        $('#deal_store_id').empty().append(options.join(''));
    }
    initDealStore(<?php echo $params['deal_site_id']; ?>,<?php echo $params['deal_store_id']; ?>);
    $('#deal_site_id').change(function(){
        var deal_site_id = $(this).val();
        initDealStore(deal_site_id,0);
    });    

    $('#createProductStat').click(function(){
        var order_ids = [];
        $('.check_single_chk').each(function(){
            if( $(this).attr('checked')=='checked' ){
                order_ids.push($(this).val());
            }
        });
        if( order_ids.length==0 ){
            alert('请至少选择一个订单');
            return false;
        }

        var muti_print_url = "<?php echo site_url('order/order/create_product_stat'); ?>";
        muti_print_url += ('?order_ids=' + order_ids.join(','));
        window.open(muti_print_url);
    });
    
    $("#createPurchase").click(function(){
        if( confirm('确定将所选订单生成采购单？') ){
            $('#purchase_form').empty();
            $('.check_single_chk').each(function(){
                if( $(this).attr('checked')=='checked' ){
                    $('#purchase_form').append('<input type="hidden" name="order_id[]" class="order_id" value="'+$(this).val()+'">');
                }
            });
            if( $('#purchase_form .order_id').length==0 ){
                alert('请至少选择一个订单');
                return false;
            }
            $('#purchase_form').submit();
        }
    });

    $("#mutiPrintBtn").click(function(){
        var order_ids = [];
        $('.check_single_chk').each(function(){
            if( $(this).attr('checked')=='checked' ){
                order_ids.push($(this).val());
            }
        });
        if( order_ids.length==0 ){
            alert('请至少选择一个订单');
            return false;
        }

        var muti_print_url = "<?php echo site_url('order/order/order_print_muti'); ?>";
        muti_print_url += ('?order_ids=' + order_ids.join(','));
        window.open(muti_print_url);
    });

    $("#mutiLabelPrintBtn").click(function(){
        var order_ids = [];
        $('.check_single_chk').each(function(){
            if( $(this).attr('checked')=='checked' ){
                order_ids.push($(this).val());
            }
        });
        if( order_ids.length==0 ){
            alert('请至少选择一个订单');ysuj
            return false;
        }

        var muti_print_url = "<?php echo site_url('order/order/order_label_print_muti'); ?>";
        muti_print_url += ('?order_ids=' + order_ids.join(','));
        window.open(muti_print_url);
    });

    $(".mutiDealOrderBtn").click(function(){
        var order_ids = [];
        $('.check_single_chk').each(function(){
            if( $(this).attr('checked')=='checked' ){
                order_ids.push($(this).val());
            }
        });
        if( order_ids.length==0 ){
            alert('请至少选择一个订单');
            return false;
        }

        var status = $(this).attr('status');
        var op_url = "<?php echo site_url('order/order/doActionMuti'); ?>";
        if( confirm('确定' + $(this).text() + '？') ){
            $.post(op_url, {'order_ids':order_ids.join(','),'status':status}, function(data){
                var data = $.parseJSON(data);
                if(data && data.err_no==0){
                    alert('操作成功！');
                    window.location.reload();
                } else {
                    var err_msg = '操作失败！';
                    if(data.err_msg){
                        err_msg += '失败原因：' + data.err_msg;
                    }
                    alert(err_msg);
                }
            });
        }
    });
    $('#export').click(function(){
        var act = "<?php echo site_url('order/order'); ?>";
        var tmp = "<?php echo site_url('order/order/export'); ?>";
        $('#search_form').attr('action',tmp).submit().attr('action',act);
    });   
});
</script>