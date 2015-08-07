<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('user/user'); ?>">用户管理</a></li>
            <li><a href="<?php echo site_url('user/user'); ?>">用户信息</a></li>
            <li class="active"><?php echo $title; ?>列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('user/coupon'); ?>" method="get" id="search_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">优惠码：</p>
                    </div>
                    <div class="form-group">
                         <input type="text" class="form-control input-sm" name="keyword" value="<?php echo $params['keyword']; ?>" placeholder="优惠码">
                    </div>
                    <div class="form-group" style="padding-left:15px;">
                        <p class="form-control-static input-sm">所属用户：</p>
                    </div>
                    <div class="form-group">
                         <input type="text" class="form-control input-sm" name="username" value="<?php echo $params['username']; ?>" placeholder="用户名">
                    </div>                                                          
                    <div class="form-group" style="width:80px;">
                        <input type="submit" class="btn btn-primary btn-sm btn-block" value="查 询">
                    </div>
                    <a class="btn btn-primary btn-sm" id="add_form_btn" href="<?php echo site_url('user/coupon/add'); ?>">添加<?php echo $title; ?></a>
                </form>
                
            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th style="width:40px;">
                            编号
                        </th>
                        <th style="width:100px;">
                            所属用户
                        </th>
                        <th style="width:100px;">
                            优惠码
                        </th>
                        <th style="width:90px;">
                            使用限制金额
                        </th>
                        <th style="width:90px;">
                            总额度
                        </th>
                        <th style="width:90px;">
                            剩余额度
                        </th>
                        <th style="width:200px;">
                            可用日期
                        </th>
                        <th style="width:70px;">
                            状态
                        </th>
                        <th style="width:50px;">
                            锁定
                        </th>
                        <th style="width:130px;">
                            添加时间
                        </th>
                        <th style="width:60px;">
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
                            <?php echo ($k+1); ?>
                        </td>
                        <td>
                            <a href="?keyword=<?php echo $params['keyword']; ?>&username=<?php echo $params['username']; ?>&uid=<?php echo $single_info['uid']; ?>"><?php echo $single_info['username']; ?></a>
                        </td>
                        <td>
                            <?php echo $single_info['coupon_code']; ?>
                        </td>
                        <td>
                            <?php echo $single_info['coupon_limit'] ? '￥'.$single_info['coupon_limit'] : '不限'; ?>
                        </td>
                        <td>
                            ￥<?php echo $single_info['coupon_total']; ?>
                        </td>
                        <td>
                            ￥<?php echo $single_info['coupon_balance']; ?>
                        </td>
                        <td>
                            <?php if( $single_info['start'] && $single_info['end'] ){ ?>
                            <?php echo date('Y-m-d',$single_info['start']); ?> 到 <?php echo date('Y-m-d',$single_info['end']); ?>
                            <?php } elseif( !$single_info['start'] && $single_info['end'] ){ ?>
                            <?php echo date('Y-m-d',$single_info['end']); ?>截至
                            <?php } elseif( $single_info['start'] && !$single_info['end'] ){ ?>
                            <?php echo date('Y-m-d',$single_info['start']); ?>开始
                            <?php } elseif( !$single_info['start'] && !$single_info['end'] ){ ?>
                            日期不限
                            <?php } ?>
                        </td>
                        <td>
                            <?php 
                            switch($single_info['status']){
                                case 1:
                                    echo '<span class="label label-success">'.$statusMap[1].'</span>';
                                    break;
                                case 2:
                                    echo '<span class="label label-warning">'.$statusMap[2].'</span>';
                                    break;
                                case 3:
                                    echo '<span class="label label-danger">'.$statusMap[3].'</span>';
                                    break;
                                default:
                                    echo '<span class="label label-default">未知状态</span>';
                                    break;
                            }
                            ?>
                        </td>
                        <td>
                            <?php if( $single_info['is_lock'] ){ ?>
                            <span class="label label-warning">是</span>
                            <?php } else { ?>
                            <span class="label label-default">否</span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php echo date('Y-m-d H:i:s',$single_info['create_time']); ?>
                        </td>
                        <td row_id="<?php echo $single_info['id']; ?>">
                            <a href="#" class="btn btn-xs btn-danger delete_link">删除</a>
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
        href: "?<?php echo 'type='.$params['type'].'&keyword='.$params['keyword'].'&username='.$params['username'].'&page=';?>{{number}}",//add by sunhui
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
        }
    });

    $(".delete_link").click(function(){
        var tThis = $(this);
        if(confirm("此删除不可恢复，请谨慎操作。您确定删除此代金券吗？")){
            var id = tThis.parent('td').attr('row_id');
            if(id){
                $.get("<?php echo site_url('user/coupon/delete'); ?>", {'id':id}, function(data){
                    var data = $.parseJSON(data);
                    if(data && data.err_no==0){
                        tThis.parent().parent().hide(600, function(){
                            tThis.parent().parent().remove();
                            alert('删除成功！');
                        });
                    } else {
                        alert('删除失败！'+err_msg);
                    }
                });
            }
        }
    });
});
</script>