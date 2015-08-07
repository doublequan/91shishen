<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('enterprise/company'); ?>">企业管理</a></li>
            <li><a href="<?php echo site_url('enterprise/company'); ?>">公司</a></li>
            <li class="active">公司列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('enterprise/company/index'); ?>" method="get">
                    <div class="form-group">
                        <p class="form-control-static input-sm">搜索：</p>
                    </div>
                    <div class="form-group">
                         <input type="text" class="form-control input-sm" name="search" value="<?php echo @$search; ?>">
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
                        <th style="width:30px;">
                            #
                        </th>
                        <th style="width:160px;">
                            公司名称
                        </th>
                        <th style="width:120px;">
                            所属城市
                        </th>
                        <th style="width:180px;">
                            公司地址
                        </th>
                        <th style="width:100px;">
                            公司电话
                        </th>
                        <th style="width:80px;">
                            备注
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
                            <?php echo isset($search) && !empty($search) ? str_replace($search, '<font color="red">'.$search.'</font>', $single_info['name']) : $single_info['name']; ?>
                        </td>
                        <td>
                            <?php echo $province_list[$single_info['province_id']]['name'].' '.$city_list[$single_info['city_id']]['name']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['address']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['phone']; ?>
                        </td>
                        <td>
                            <?php if(empty($single_info['comment'])) { ?>
                                无
                            <?php }else{ ?>
                                <a href="#" class="popover_link" title="公司备注" data-content="<?php echo $single_info['comment']; ?>">查看备注</a>
                            <?php } ?>
                        </td>
                        <td company_id="<?php echo $single_info['id']; ?>" style="padding:6px;">
                            <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-xs btn-success check_form_link">查看/编辑</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php }else{ ?>
            <div class="alert alert-warning col-md-12 text-center" role="alert">查询结果为空！</div>
            <?php } ?>
        </div>
    </div>
</div>

<iframe src="" id="add_form_page" style="height:560px;"></iframe>

<script src="<?php echo base_url('static/js/jquery.fancybox.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
    $('.popover_link').popover({
        'content' : $(this).attr('data-content'),
        'title'   : $(this).attr('title'),
        'placement': 'left',
        'trigger' : 'hover',
    });

    var form_url = "<?php echo site_url('enterprise/company/form'); ?>";
    $('a#add_form_btn').click(function(){
        $('iframe#add_form_page').attr('src', form_url);
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
            $('iframe#add_form_page').attr('src', form_url + '?company_id=' + company_id);
            $("a.check_form_link").fancybox({
                'hideOnContentClick': true,
                'padding':0,
                'afterClose': function(){
                    window.parent.location.reload();
                },
            });
        }
    });

    // $(".delete_link").click(function(){
    //     var company_id = $(this).parent('td').attr('company_id');

    //     if(company_id){
    //         $.get("<?php echo site_url('enterprise/company/delete'); ?>", {'company_id': company_id}, function(data){
    //             var data = $.parseJSON(data);

    //             if(data && data.error){
    //                 alert(data.msg);
    //             }
    //             else{
    //                 alert("删除成功！");
    //             }
    //             window.location.reload();
    //         });
    //     }

    // });
});

</script>