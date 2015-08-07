<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 form_header bg-primary">
                <h4>
                    <span class="glyphicon glyphicon-circle-arrow-right"></span>
                    选择员工
                </h4>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading" style="height:50px;">
                        <form class="form-inline" action="<?php echo site_url('employee/employee/select_dialog'); ?>" method="get" id="search_form">
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
                        </form>
                    </div>
                    <?php 
                        if(!empty($results)){ 
                    ?>
                    <table class="table table-striped table-hover table-condensed text-center" id="employee_table">
                        <thead>
                            <tr>
                                <th style="width:100px;">
                                    员工姓名
                                </th>
                                <th style="width:100px;">
                                    手机号
                                </th>
                                <th style="width:100px;">
                                    邮箱
                                </th>
                                <th style="width:150px;">
                                    所属公司
                                </th>
                                <th style="width:150px;">
                                    部门
                                </th>
                                <!-- <th style="width:100px;">
                                    角色
                                </th> -->
                                <th style="width:80px;">
                                    操作
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                foreach ($results as $single_info) {
                            ?>
                            <tr employee_id="<?php echo $single_info['id']; ?>" employee_name="<?php echo $single_info['username']; ?>">
                                <td>
                                    <?php echo $params['username'] ? str_replace($params['username'], '<font color="red">'.$params['username'].'</font>', $single_info['username']) : $single_info['username']; ?>
                                </td>
                                <td>
                                    <?php echo $single_info['mobile']; ?>
                                </td>
                                <td>
                                    <?php echo $single_info['email']; ?>
                                </td>
                                <td>
                                    <?php echo $companys[$single_info['company_id']]['name']; ?>
                                </td>
                                <td>
                                    <?php echo $depts[$single_info['dept_id']]['name']; ?>
                                </td>
                                <!-- <td>
                                    <?php echo $single_info['roles_name']; ?>
                                </td> -->
                                <td>
                                    <a href="#" class="check_link">选择</a>
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
    </div>
    <script src="<?php echo base_url('static/js/jquery.min.js'); ?>" type="text/javascript"></script>
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
            href: "?<?php echo 'company_id='.@$company_id.'&site_id='.@$site_id.'&username='.@$username.'&page=';?>{{number}}",
            onPageClick: function (event, page) {
                $('#page-content').text('Page ' + page);
            }
        });

        $('#search_form select').change(function(event) {
            $('#search_form').submit();
        });
        
        var ename_ipt = 'employee_name';
        var eid_ipt = 'employee_id';
        var url_sreach = window.location.search;

        if(url_sreach.length > 2){
            url_sreach = url_sreach.substr(1);
            url_sreach = url_sreach.split('&');

            $.each(url_sreach, function(index, val) {
                 var tmp_arr = val.split('=');
                 if(tmp_arr[0] == 'ename'){
                    ename_ipt = tmp_arr[1];
                 }
                 else if(tmp_arr[0] == 'eid'){
                    eid_ipt = tmp_arr[1];
                 }
            });
        }

        $('.check_link').click(function(){
            window.parent.document.getElementById(eid_ipt).value = $(this).parent().parent('tr').attr('employee_id');
            window.parent.document.getElementById(ename_ipt).value = $(this).parent().parent('tr').attr('employee_name');
            window.parent.$.fancybox.close();
        });

        $('#employee_table tbody tr').dblclick(function(){
            window.parent.document.getElementById(eid_ipt).value = $(this).attr('employee_id');
            window.parent.document.getElementById(ename_ipt).value = $(this).attr('employee_name');
            window.parent.$.fancybox.close('fade');
        });
    });
    </script>

</body>
</html>
