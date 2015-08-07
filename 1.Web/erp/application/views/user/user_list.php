<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/datetimepicker.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('user/user'); ?>">用户管理</a></li>
            <li class="active">用户列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading" style="height:50px;">
                <form class="form-inline" action="<?php echo site_url('user/user'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">搜索：</p>
                    </div>
                    <div class="form-group">
                        <select name="status" class="form-control input-sm">
                            <?php foreach ($statusMap as $k=>$v) { ?>
                            <option value="<?php echo $k; ?>"<?php echo $k==$params['status'] ? ' selected="selected"' : ''; ?>><?php echo trim($v); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                         <input type="text" class="form-control input-sm" name="keyword" value="<?php echo $params['keyword']; ?>" placeholder="关键词" />
                    </div>
                    <div class="form-group" style="width:80px;">
                        <input type="submit" class="btn btn-primary btn-sm btn-block" value="查 询">
                    </div>
                    <div class="form-group" style="margin-left:10px;width:80px;">
                         <a class="btn btn-primary btn-sm btn-block" id="adv_search_btn" href="#adv_search_dialog" kesrc="#adv_search_dialog">高级搜索</a>
                    </div>
                    <div class="form-group" style="width:80px;margin-left:30px;">
                        <input type="submit" class="btn btn-primary btn-sm btn-block" id="export" value="导出Excel">
                    </div>                                      

                    <a class="btn btn-primary btn-sm" id="add_form_btn" href="#add_form_page" kesrc="#add_form_page">快速添加用户</a>
                </form>
                
            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <table class="table table-striped table-hover text-center" style="font-size:12px;">
                <thead>
                    <tr>
                        <th width="3%" class="text-center">
                            <input type="checkbox" id="check_all_chk">
                        </th>
                        <th width="3%">
                            #
                        </th>
                        <th width="12%">
                            用户名
                        </th>
                        <th width="20%" colspan="2">
                            手机号
                        </th>
                        <th width="20%" colspan="2">
                            邮箱
                        </th>
                        <th width="10%">
                            会员卡号
                        </th>
                        <th width="7%">
                            来源
                        </th>
                        <th width="25%">
                            操作
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach ($results as $k=>$row) {
                    ?>
                    <tr>
                        <td>
                            <input type="checkbox" class="check_single_chk" value="<?php echo $row['id']; ?>">
                        </td>
                        <td>
                            <?php echo ($k+1); ?>
                        </td>
                        <td>
                            <?php echo $row['username']; ?>
                        </td>
                        <td>
                            <?php echo $row['mobile']; ?>
                        </td>
                        <td>
                            <?php if( $row['mobile_status']==1 ){ ?>
                            <span class="label label-primary">已验证</span>
                            <?php } else { ?>
                            <span class="label label-default">未验证</span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php echo $row['email']; ?>
                        </td>
                        <td>
                            <?php if( $row['email_status']==1 ){ ?>
                            <span class="label label-primary">已验证</span>
                            <?php } else { ?>
                            <span class="label label-default">未验证</span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php echo $row['cardno']; ?>
                        </td>
                        <td>
                            <?php echo isset($os_types[$row['create_os']]) ? $os_types[$row['create_os']] : ''; ?>
                        </td>
                        <td row_id="<?php echo $row['id']; ?>" style="padding:6px;">
                            <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-xs btn-success check_form_link">编辑</a>
                            <?php if( $params['status']==1 ){ ?>
                            <a href="#" class="btn btn-xs btn-warning lock_link">锁定</a>
                            <?php } ?>
                            <?php if( $params['status']==3 ){ ?>
                            <a href="#" class="btn btn-xs btn-warning unlock_link">锁</a>
                            <?php } ?>
                            <?php if( $params['status']!=2 ){ ?>
                            <a href="#" class="btn btn-xs btn-danger delete_link">删除</a>
                            <?php } ?>
                            <a href="<?php echo site_url('user/address?uid='.$row['id']); ?>" target="_blank" class="btn btn-xs btn-info">地址</a>
                            <a href="<?php echo site_url('user/coupon?uid='.$row['id']); ?>" target="_blank" class="btn btn-xs btn-info">代金券</a>
                            <a href="<?php echo site_url('order/order?status=-1&username='.urlencode($row['uname'])); ?>" target="_blank" class="btn btn-xs btn-info">订单</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="panel-footer">
                <?php if( $params['status']!=3 ){ ?>
                <button type="button" class="btn btn-sm btn-warning" id="allLock">批量锁定</button>
                <?php } ?>
                <?php if( $params['status']!=2 ){ ?>
                <button type="button" class="btn btn-sm btn-danger" id="allDelete">批量删除</button>
                <?php } ?>
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

<div id="adv_search_dialog" style="height:350px;width:600px;display:none;padding:0 15px;">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                用户高级搜索
            </h4>
        </div>
    </div>

    <form class="form-horizontal" action="<?php echo site_url('user/user'); ?>" method="get" style="margin-top:15px;">
        <div class="form-group form-group-sm">
            <label for="" class="col-sm-2 control-label">
                关键词
            </label>
            <div class="col-sm-8">
                <input type="text" name="keyword" class="form-control" value="<?php echo $params['keyword']; ?>" placeholder="用户名/邮箱/手机号码/会员卡号">
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label for="" class="col-sm-2 control-label">
                用户状态
            </label>
            <div class="col-sm-8">
                <select name="status" class="form-control">
                    <?php if( $statusMap ) { ?>
                    <?php foreach( $statusMap as $k=>$v ) { ?>
                    <option value="<?php echo $k; ?>"<?php echo $k==$params['status'] ? ' selected' : ''; ?>><?php echo $v; ?></option>
                    <?php } ?>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label for="" class="col-sm-2 control-label">
                手机状态
            </label>
            <div class="col-sm-2">
                <label><input type="radio" name="mobile_status" value="-1"<?php echo $params['mobile_status']==-1 ? ' checked' : ''; ?>> 不限</label>
            </div>
            <div class="col-sm-2">
                <label><input type="radio" name="mobile_status" value="0"<?php echo $params['mobile_status']==0 ? ' checked' : ''; ?>> 未验证</label>
            </div>
            <div class="col-sm-2">
                <label><input type="radio" name="mobile_status" value="1"<?php echo $params['mobile_status']==1 ? ' checked' : ''; ?>> 已验证</label>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label for="" class="col-sm-2 control-label">
                邮箱状态
            </label>
            <div class="col-sm-2">
                <label><input type="radio" name="email_status" value="-1"<?php echo $params['email_status']==-1 ? ' checked' : ''; ?>> 不限</label>
            </div>
            <div class="col-sm-2">
                <label><input type="radio" name="email_status" value="0"<?php echo $params['email_status']==0 ? ' checked' : ''; ?>> 未验证</label>
            </div>
            <div class="col-sm-2">
                <label><input type="radio" name="email_status" value="1"<?php echo $params['email_status']==1 ? ' checked' : ''; ?>> 已验证</label>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label for="" class="col-sm-2 control-label">
                注册来源
            </label>
            <div class="col-sm-8">
                <select name="os" class="form-control">
                    <option value="">不限</option>
                    <?php foreach ($os_types as $k=>$v) { ?>
                    <option value="<?php echo $k; ?>"<?php echo $k==$params['os'] ? ' selected' : ''; ?>><?php echo trim($v); ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label for="" class="col-sm-2 control-label">
                注册时间
            </label>
            <div class="col-sm-3">
                <input type="text" name="create_time_start" class="form-control" readOnly id="create_time_start"
                    value="<?php echo $params['create_time_start']; ?>">
            </div>
            <div class="col-sm-1 text-center">
                <p class="form-control-static"> - </p>
            </div>
            <div class="col-sm-3">
                <input type="text" name="create_time_end" class="form-control" readOnly id="create_time_end"
                    value="<?php echo $params['create_time_end']; ?>">
            </div>
        </div>
        <div class="form-group form-group-sm">
            <div class="col-sm-offset-4 col-sm-2">
                <button type="submit" class="btn btn-primary btn-sm btn-block">查询</button>
            </div>
            <div class="col-sm-2">
                <a href="javascript:void(0);" class="btn btn-default btn-sm btn-block" id="adv_dialog_cancel">重置</a>
            </div>
        </div>
    </form>
</div>

<iframe src="" id="add_form_page"></iframe>

<script src="<?php echo base_url('static/js/jquery.fancybox.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/jquery.twbsPagination.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/bootstrap-datetimepicker.js'); ?>" type="text/javascript"></script>
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
        href: "?<?php echo $url.'&page='; ?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });

    $('#create_time_start, #create_time_end').datetimepicker({
        language:  'zh',
        format: 'yyyy-mm-dd HH:ii:ss',
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minuteStep: 1,
        minView: 0,
        maxView: 3,
    });

    $("#create_time_start, #create_time_end").change(function(){
        if($("#create_time_start").val() && $("#create_time_end").val()){
            var create_time_start = parseInt(new Date($("#create_time_start").val()).getTime());
            var create_time_end = parseInt(new Date($("#create_time_end").val()).getTime());
            if(create_time_start > create_time_end){
                $(this).val('');
                return alert("开始时间需小于结束时间！");
            }
        }
    });

    $('#search_form select').change(function(event) {
        $('#search_form').submit();
    });

    $('a#adv_search_btn').click(function(){
        $("a#adv_search_btn").fancybox();
    });

    $('a#adv_dialog_cancel').click(function(){
        $('#adv_search_dialog form input').val('');
        $('#adv_search_dialog form select option:first-child').attr('selected', true);
    });

    $('a#add_form_btn').click(function(){
        $('iframe#add_form_page').attr('src', "<?php echo site_url('user/user/add'); ?>").css('height', '440px');
        $("a#add_form_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            'afterClose': function(){
                window.parent.location.reload();
            },
        });
    });

    // $('a#import_form_btn').click(function(){
    //     $('iframe#add_form_page').attr('src', "<?php echo site_url('user/user/import'); ?>");
    //     $("a#import_form_btn").fancybox({
    //         'hideOnContentClick': true,
    //         'padding':0,
    //         'afterClose': function(){
    //             window.parent.location.reload();
    //         },
    //     });
    // });
        
    $("a.check_form_link").click(function() {
        var row_id = $(this).parent('td').attr('row_id');   
        if(row_id){
            $('iframe#add_form_page').attr('src', "<?php echo site_url('user/user/edit'); ?>" + '?id=' + row_id).css('height', '640px');
            $("a.check_form_link").fancybox({
                'hideOnContentClick': true,
                'padding':0,
                'afterClose': function(){
                    window.parent.location.reload();
                },
            });
        }
    });

    function doEditStatus( tThis, status ){
        var row_id = tThis.parent('td').attr('row_id');
        if(row_id){
            $.get("<?php echo site_url('user/user/doEditStatus'); ?>", {'id':row_id,'status':status}, function(data){
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

    $(".delete_link").click(function(){
        if( confirm('此操作不可恢复，您确定要删除此用户吗？') ){
            doEditStatus($(this), 2);
        }
    });

    $(".lock_link").click(function(){
        if( confirm('您确定要锁定此用户吗？锁定后用户不可登录！') ){
            doEditStatus($(this), 3);
        }
    });

    $(".unlock_link").click(function(){
        if( confirm('您确定要解除锁定此用户吗？') ){
            doEditStatus($(this), 1);
        }
    });

    $('#allLock').click(function(){
        var ids = new Array();
        $('.check_single_chk').each(function(){
            if( $(this).attr('checked')=='checked' ){
                ids.push($(this).val());
            }
        });
        if( ids.length==0 ){
            alert('请至少选择一个用户');
            return false;
        }
        if( confirm('确定锁定所选用户？') ){
            setMulStatus(3,ids.toString());
        }
    });

    $('#allDelete').click(function(){
        var ids = new Array();
        $('.check_single_chk').each(function(){
            if( $(this).attr('checked')=='checked' ){
                ids.push($(this).val());
            }
        });
        if( ids.length==0 ){
            alert('请至少选择一个用户');
            return false;
        }
        if( confirm('确定删除所选用户？') ){
            setMulStatus(2,ids.toString());
        }
    });

    function setMulStatus( status, ids ){
        var url = '<?php echo site_url('user/user/doEditStatusMulti'); ?>';
        var params = {
            'status'    : status,
            'ids'       : ids.toString()
        };
        $.post(url, params, function(data){
            var data = $.parseJSON(data);
            if( data && data.err_no==0 ){
                alert('操作成功！');
                window.location.reload();
            } else {
                alert('操作失败！');
            }
        });
    }

    $('#export').click(function(){
        var act = "<?php echo site_url('user/user'); ?>";
        var tmp = "<?php echo site_url('user/user/export'); ?>";
        $('#search_form').attr('action',tmp).submit();
        setTimeout(function(){ $('#search_form').attr('action',act);},3000);        
    });       
});
</script>