<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('system/system'); ?>">系统设置</a></li>
            <li class="active">设置任务处理人</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel-body">
            <ul class="nav nav-tabs" role="tablist">
                <?php if( $task_types ){ ?>
                <?php $i=0; ?>
                <?php foreach( $task_types as $type=>$v ){ ?>
                <li<?php echo $i==0 ? ' class="active"' : ''; ?>><a href="#tab_content_<?php echo $i; ?>" role="tab" data-toggle="tab"><?php echo $v; ?>处理人</a></li>
                <?php $i++; ?>
                <?php } ?>
                <?php } ?>
                <li><a class="btn btn-primary btn-sm" id="add_form_btn" href="#add_form_page" kesrc="#add_form_page"><strong>新增任务处理人</strong></a></li>
            </ul>
            <div class="tab-content">
                <p class="bg-info form-square-title">任务到达时将会按照排序选择第一位员工分发，如无员工，则为ID编号为1的员工</p>
                <?php if( $task_types ){ ?>
                <?php $i=0; ?>
                <?php foreach( $task_types as $type=>$v ){ ?>
                <div class="tab-pane<?php echo $i==0 ? ' active' : ''; ?>" id="tab_content_<?php echo $i; ?>">
                    <table class="table table-striped table-hover text-center">
                        <thead>
                            <tr>
                                <th width="10%">序号</th>
                                <th width="30%">员工姓名</th>
                                <th width="40%">
                                    排序&nbsp;&nbsp;
                                    <button type="button" class="btn btn-sm btn-primary resort" data="<?php echo $type; ?>">重置排序</button>
                                </th>
                                <th width="20%">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if( isset($results[$type]) && $results[$type] ){ ?>
                            <?php $total = count($results[$type])-1; ?>
                            <?php foreach ($results[$type] as $k=>$row) { ?>
                            <tr>
                                <td><?php echo ($k+1); ?></td>
                                <td><?php echo $row['username']; ?></td>
                                <td>
                                    <input type="text" class="form-control input-sm text-center sort_<?php echo $type; ?>" eid="<?php echo $row['eid']; ?>" style="margin:0 auto;width:150px;" value="<?php echo $row['sort']; ?>">
                                </td>
                                <td row_id="<?php echo $row['id']; ?>">
                                    <a href="#" class="delete_link">删除</a>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php $i++; ?>
                <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<iframe src="" id="add_form_page" style="height:660px;"></iframe>
<script src="<?php echo base_url('static/js/jquery.fancybox.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function($) {
    $('.resort').click(function(){
        if( confirm('您确定变更排序吗？') ){
            var type = parseInt($(this).attr('data'));
            var arr = new Array();
            $('.sort_'+type).each( function(){
                var eid = $(this).attr('eid');
                var sort = $(this).val();
                arr.push(eid+':'+sort);
            });
            var params = {
                'type' : type,
                'sort' : arr.join()
            };
            $.ajax({
                type: 'POST',
                url:  '<?php echo site_url("system/task/resort"); ?>',
                data: params,
                dataType: 'json',
                success: function(msg){
                    if( msg.err_no==0 ){
                        alert('操作成功');
                        window.location.reload();
                    } else {
                        alert(msg.err_msg);
                    }
                }
            });
        }
    });

    $('a#add_form_btn').click(function(){
        $('iframe#add_form_page').attr('src', "<?php echo site_url('system/task/add'); ?>");
        $("a#add_form_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            'afterClose': function(){
                window.parent.location.reload();
            },
        });
    });

    $(".delete_link").click(function(){
        if( confirm('您确定删除此处理员工吗？') ){
            var row_id = $(this).parent('td').attr('row_id');
            if( row_id ){
                var params = {
                    'id' : row_id
                };
                $.ajax({
                    type: 'POST',
                    url:  '<?php echo site_url("system/task/delete"); ?>',
                    data: params,
                    dataType: 'json',
                    success: function(msg){
                        if( msg.err_no==0 ){
                            alert('删除成功');
                            window.location.reload();
                        } else {
                            alert(msg.err_msg);
                        }
                    }
                });
            }
        }
    });
});

</script>
