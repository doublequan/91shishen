<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('products/product'); ?>">商品管理</a></li>
            <li><a href="<?php echo site_url('products/loss'); ?>">损耗</a></li>
            <li class="active">损耗列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('products/loss'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">损耗类型：</p>
                    </div>
                    <div class="form-group">
                        <select name="type_id" class="form-control input-sm" id="type_id">
                        <?php foreach ($types as $types_key => $value) { ?>
                            <option value="<?php echo $types_key; ?>" <?php echo $params['type_id']==$types_key?'selected':''; ?>><?php echo $value['name']; ?></option>
                        <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <p class="form-control-static input-sm">&nbsp;&nbsp;分类：</p>
                    </div>
                    <div class="form-group">
                        <select name="t" class="form-control input-sm" id="t">
                            <option value="1" <?php echo $params['t']==1?'selected':''; ?>>原料</option>
                            <option value="2" <?php echo $params['t']==2?'selected':''; ?>>商品</option>
                        </select>
                    </div>

                    <a class="btn btn-primary btn-sm" id="add_form_btn" href="<?php echo site_url('products/loss/add'); ?>">添加损耗信息</a>
                </form>
            </div>
            <?php 
            if(!empty($results)){
                if($params['t'] == 1){
            ?>
                <table class="table table-striped table-hover text-center">
                    <thead>
                        <tr>
                            <th style="width:30px;">
                                #
                            </th>
                            <th style="width:130px;">
                                原料名称
                            </th>
                            <th style="width:60px;">
                                总量
                            </th>
                            <th style="width:60px;">
                                损失量
                            </th>
                            <th style="width:60px;">
                                剩余量
                            </th>
                            <th style="width:60px;">
                                单品采购价
                            </th>
                            <th style="width:80px;">
                                责任员工
                            </th>
                            <th style="width:80px;">
                                损耗类型
                            </th>
                            <th style="width:60px;">
                                损耗说明
                            </th>
                            <!-- <th style="width:80px;">
                                操作
                            </th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $idx = 0;
                            foreach ($results as $single_info) {
                        ?>
                        <tr>
                            <td>
                                <?php echo ++$idx; ?>
                            </td>
                            <td>
                                <?php echo $single_info['good_name']; ?>
                            </td>
                            <td>
                                <?php echo $single_info['amount_total']; ?>
                            </td>
                            <td>
                                <?php echo $single_info['amount_loss']; ?>
                            </td>
                            <td>
                                <?php echo $single_info['amount_left']; ?>
                            </td>
                            <td>
                                ￥<?php echo $single_info['price']; ?>
                            </td>
                            <td>
                                <?php echo $single_info['respon_name']; ?>
                            </td>
                            <td>
                                <?php echo $types[$single_info['type_id']]['name']; ?>
                            </td>
                            <td>
                                <a href="#" class="check_link" title="损耗说明" data-content="<?php echo $single_info['des']; ?>">查看</a>
                            </td>
                            <!-- <td loss_id="<?php echo $single_info['id']; ?>" style="padding:6px;">
                                <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-xs btn-danger check_form_link">编 辑</a>
                            </td> -->
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php } elseif($params['t'] == 2){ ?>
                <table class="table table-striped table-hover text-center">
                    <thead>
                        <tr>
                            <td style="width:30px;">
                                #
                            </td>
                            <th style="width:130px;">
                                商品名称
                            </th>
                            <th style="width:70px;">
                                总量
                            </th>
                            <th style="width:70px;">
                                损失量
                            </th>
                            <th style="width:70px;">
                                剩余量
                            </th>
                            <th style="width:70px;">
                                单品采购价
                            </th>
                            <th style="width:80px;">
                                责任员工
                            </th>
                            <th style="width:80px;">
                                损耗类型
                            </th>
                            <th style="width:80px;">
                                损耗说明
                            </th>
                            <!-- <th style="width:80px;">
                                操作
                            </th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $idx = 0;
                            foreach ($results as $single_info) {
                        ?>
                        <tr>
                            <td>
                                <?php echo ++$idx; ?>
                            </td>
                            <td>
                                <?php echo $single_info['product_name']; ?>
                            </td>
                            <td>
                                <?php echo $single_info['amount_total']; ?>
                            </td>
                            <td>
                                <?php echo $single_info['amount_loss']; ?>
                            </td>
                            <td>
                                <?php echo $single_info['amount_left']; ?>
                            </td>
                            <td>
                                <?php echo $single_info['price']; ?>
                            </td>
                            <td>
                                <?php echo $single_info['respon_name']; ?>
                            </td>
                            <td>
                                <?php echo $types[$single_info['type_id']]['name']; ?>
                            </td>
                            <td>
                                <a href="#" class="check_link" title="损耗说明" data-content="<?php echo $single_info['des']; ?>">查看</a>
                            </td>
                            <!-- <td loss_id="<?php echo $single_info['id']; ?>" style="padding:6px;">
                                <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-xs btn-danger check_form_link">编 辑</a>
                            </td> -->
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

<!-- <iframe src="" id="add_form_page" class="iframe_dialog" style="height:670px;"></iframe> -->

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
        href: "?<?php echo 't='.$params['t'].'&type_id='.$params['type_id'].'&page=';?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });

    $('.check_link').popover({
        'content' : $(this).attr('data-content'),
        'title'   : $(this).attr('title'),
        'placement': 'left',
        'trigger' : 'hover',
    });

    $('#search_form select').change(function(event) {
        $('#search_form').submit();
    });
    
    // $('a#add_form_btn').click(function(){
    //     $('iframe#add_form_page').attr('src', "<?php echo site_url('products/loss/add'); ?>");
    //     $("a#add_form_btn").fancybox({
    //         'hideOnContentClick': true,
    //         'padding':0,
    //         'afterClose': function(){
    //             window.parent.location.reload();
    //         },
    //     });
    // });
        
    // $("a.check_form_link").click(function() {
    //     var loss_id = $(this).parent('td').attr('loss_id');
    //     if(loss_id){
    //         $('iframe#add_form_page').attr('src', "<?php echo site_url('products/loss/edit').'?id='; ?>" + loss_id);
    //         $("a.check_form_link").fancybox({
    //             'hideOnContentClick': true,
    //             'padding':0,
    //             'afterClose': function(){
    //                 window.parent.location.reload();
    //             },
    //         });
    //     }
    // });

});



</script>