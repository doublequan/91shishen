<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('enterprise/company'); ?>">企业管理</a></li>
            <li><a href="<?php echo site_url('employee/employee'); ?>">员工</a></li>
            <li class="active">员工列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading" style="height:50px;">
                <form class="form-inline" action="<?php echo site_url('employee/employee'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">所属公司：</p>
                    </div>
                    <div class="form-group">
                        <select name="company_id" class="form-control input-sm">
                            <option value="0">不限公司</option>
                            <?php if( $companys ){ ?>
                            <?php foreach( $companys as $k=>$row ){ ?>
                            <option value="<?php echo $k; ?>"<?php echo $k==$params['company_id'] ? ' selected' : ''; ?>><?php echo $row['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group" style="padding-left:10px;">
                        <p class="form-control-static input-sm">所属部门：</p>
                    </div>
                    <div class="form-group">
                        <select name="dept_id" class="form-control input-sm">
                            <option value="0">不限部门</option>
                            <?php if( $deptMap ){ ?>
                            <?php foreach( $deptMap as $k=>$row ){ ?>
                            <option value="<?php echo $k; ?>"<?php echo $k==$params['dept_id'] ? ' selected' : ''; ?>><?php echo $row['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group" style="padding-left:10px;">
                        <p class="form-control-static input-sm">员工姓名：</p>
                    </div>
                    <div class="form-group">
                         <input type="text" class="form-control input-sm" name="username" value="<?php echo $params['username']; ?>" placeholder="员工姓名">
                    </div>
                    <div class="form-group" style="width:80px;">
                         <input type="submit" class="btn btn-primary btn-sm btn-block" value="查 询">
                    </div>
                    <a class="btn btn-primary btn-sm" id="add_form_btn" href="#add_form_page" kesrc="#add_form_page">添加员工</a>
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
                        <th style="width:80px;">
                            员工姓名
                        </th>
                        <th style="width:90px;">
                            登录名
                        </th>
                        <th style="width:90px;">
                            手机号
                        </th>
                        <th style="width:140px;">
                            所属公司
                        </th>
                        <th style="width:120px;">
                            部门
                        </th>
                        <th style="width:100px;">
                            操作
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach ($results as $idx=>$single_info) {
                    ?>
                    <tr>
                        <td>
                            <?php echo ++$idx; ?>
                        </td>
                        <td>
                            <?php echo $params['username'] ? str_replace($params['username'], '<font color="red">'.$params['username'].'</font>', $single_info['username']) : $single_info['username']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['account']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['mobile']; ?>
                        </td>
                        <td>
                            <?php echo $companys[$single_info['company_id']]['name']; ?>
                        </td>
                        <td>
                            <?php echo $depts[$single_info['dept_id']]['name']; ?>
                        </td>
                        <td row_id="<?php echo $single_info['id']; ?>" style="padding:6px;">
                            <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-xs btn-success check_form_link">查看/编辑</a>
                            <a href="#" class="btn btn-xs btn-danger delete_link">员工离职</a>
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

<iframe src="" id="add_form_page" style="height:660px;width:1000px;"></iframe>
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
        href: "?<?php echo 'company_id='.@$company_id.'&dept_id='.@$dept_id.'&username='.@$username.'&page=';?>{{number}}",
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });

    $('#search_form select').change(function(event) {
        $('#search_form').submit();
    });

    $('a#add_form_btn').click(function(){
        $('iframe#add_form_page').attr('src', "<?php echo site_url('employee/employee/add'); ?>");
        $("a#add_form_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            'afterClose': function(){
                window.parent.location.reload();
            },
        });
    });
        
    $("a.check_form_link").click(function() {
        var row_id = $(this).parent('td').attr('row_id');
        if(row_id){
            $('iframe#add_form_page').attr('src', "<?php echo site_url('employee/employee/edit'); ?>" + '?id=' + row_id);
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
        var row_id = $(this).parent('td').attr('row_id');
        if(confirm("确定将该员工离职？提示：此操作将删除该员工所有权限且不能更改，请谨慎操作！")){
            $.post("<?php echo site_url('employee/employee/delete'); ?>", {'row_id': row_id}, function(data){
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
});
</script>