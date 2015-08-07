<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('vip/product'); ?>">大客户管理</a></li>
            <li><a href="<?php echo site_url('vip/company'); ?>">大客户公司</a></li>
            <li class="active">大客户公司列表</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('vip/company'); ?>" method="get">
                    <div class="form-group">
                        <p class="form-control-static input-sm">公司名称：</p>
                    </div>
                    <div class="form-group">
                         <input type="text" class="form-control input-sm" name="keyword" value="<?php echo $params['keyword']; ?>">
                    </div>
                    <div class="form-group" style="width:80px;">
                         <input type="submit" class="btn btn-primary btn-sm btn-block" value="查 询">
                    </div>

                    <a class="btn btn-primary btn-sm" id="add_form_btn" href="#add_form_page" kesrc="#add_form_page">添加公司信息</a>
                </form>
            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th style="width:40px;text-align:center;">
                            #
                        </th>
                        <th style="width:160px;">
                            公司名称
                        </th>
                        <th style="width:150px;">
                            所在城市
                        </th>
                        <th style="width:200px;">
                            公司地址
                        </th>
                        <th style="width:110px;">
                            公司电话
                        </th>
                        <th style="width:110px;">
                            所属行业
                        </th>
                        <th style="width:100px;">
                            公司规模
                        </th>
                        <th style="width:100px;">
                            操作
                        </th>
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
                            <?php echo $single_info['name']; ?>
                        </td>
                        <td>
                            <?php echo $province_list[$single_info['prov']]['name'].' '.$city_list[$single_info['city']]['name']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['address']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['tel']; ?>
                        </td>
                        <td>
                            <?php echo $industrys[$single_info['industry_id']]['name']; ?>
                        </td>
                        <td>
                            <?php echo $scaleMap[$single_info['scale']]; ?>
                        </td>
                        <td company_id="<?php echo $single_info['id']; ?>" style="padding:6px;">
                            <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-xs btn-success check_form_link">查看/编辑</a>
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

<iframe src="" id="add_form_page" style="height:500px;"></iframe>

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
        href: "?<?php echo 'keyword='.$params['keyword'].'&page=';?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });

    $('a#add_form_btn').click(function(){
        $('iframe#add_form_page').attr('src', "<?php echo site_url('vip/company/add'); ?>");
        $("a#add_form_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            'afterClose': function(){
                window.parent.location.reload();
            },
        });
    });
        
    $("a.check_form_link").click(function() {
        var company_id = $(this).parent('td').attr('company_id');
        if(company_id){
            $('iframe#add_form_page').attr('src', "<?php echo site_url('vip/company/edit'); ?>" + '?id=' + company_id);
            $("a.check_form_link").fancybox({
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