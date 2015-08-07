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
                    选择供应商
                </h4>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <form class="form-inline" action="<?php echo site_url('products/supplier/single_select_dialog'); ?>" method="get" id="search_form">
                            <div class="form-group">
                                <p class="form-control-static input-sm">供应商名称：</p>
                            </div>
                            <div class="form-group">
                                 <input type="text" class="form-control input-sm" name="sup_name" value="<?php echo @$sup_name; ?>">
                            </div>
                            <div class="form-group">
                                 <input type="submit" class="btn btn-primary btn-sm" value="查 询">
                            </div>
                        </form>
                    </div>
                    <?php 
                        if(!empty($results)){ 
                    ?>
                    <table class="table table-striped table-hover table-condensed text-center" id="suppliers_list_table">
                        <thead>
                            <tr>
                                <th style="width:30px;">
                                    #
                                </th>
                                <th style="width:140px;">
                                    供应商名称
                                </th>
                                <th style="width:100px;">
                                    供应商电话
                                </th>
                                <th style="width:100px;">
                                    联系人
                                </th>
                                <th style="width:100px;">
                                    联系人手机
                                </th>
                                <th style="width:120px;">
                                    联系人邮箱
                                </th>
                                <th style="width:50px;">
                                    操作
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $idx = 0;
                                foreach ($results as $idx => $single_info) {
                            ?>
                            <tr supplier_id="<?php echo $single_info['id']; ?>" supplier_name="<?php echo $single_info['sup_name']; ?>">
                                <td>
                                    <?php echo ++$idx; ?>
                                </td>
                                <td>
                                    <?php echo $single_info['sup_name']; ?>
                                </td>
                                <td>
                                    <?php echo $single_info['sup_phone']; ?>
                                </td>
                                <td>
                                    <?php echo $single_info['contact_name']; ?>
                                </td>
                                <td>
                                    <?php echo $single_info['contact_mobile']; ?>
                                </td>
                                <td>
                                    <?php echo $single_info['contact_email']; ?>
                                </td>
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
            totalPages: <?php echo $total_pages; ?>,
            visiblePages: 5,
            startPage: <?php echo $page; ?>,
            first: '首页',
            prev: '上一页',
            next: '下一页',
            last: '尾页',
            href: "?<?php echo 'site_id='.@$site_id.'stype='.@$stype.'search='.@$search.'&page=';?>{{number}}",
            onPageClick: function (event, page) {
                $('#page-content').text('Page ' + page);
            }
        });

        $('.check_link').click(function(){
            window.parent.document.getElementById('supplier_id').value = $(this).parent().parent('tr').attr('supplier_id');
            window.parent.document.getElementById('supplier_name').value = $(this).parent().parent('tr').attr('supplier_name');
            window.parent.$.fancybox.close();
        });

        $('#suppliers_list_table tbody tr').dblclick(function(){
            window.parent.document.getElementById('supplier_id').value = $(this).attr('supplier_id');
            window.parent.document.getElementById('supplier_name').value = $(this).attr('supplier_name');
            window.parent.$.fancybox.close('fade');
        });
    });
    </script>

</body>
</html>

