
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb" style="margin-bottom:0;">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('system/system'); ?>">系统设置</a></li>
            <li><a href="<?php echo site_url('system/cache'); ?>">缓存管理</a></li>
            <li class="active">用户缓存</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12 form_body" style="padding-top:0px;">
        <div class="panel-body">
            <div class="row">
                <p class="bg-info form-square-title">任务处理</p>
                <div class="panel-heading form-inline" style="padding:0px 0px 10px;">
                    客户端类型：
                    <select name="os" id="os" class="form-control input-sm">
                        <?php foreach ($os_types as $k=>$v) { ?>
                        <?php if( $k ){ ?>
                        <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>     
                    &nbsp;&nbsp;
                    <a class="btn btn-info btn-sm" id="delete_user_session">强制用户重新登录</a> 
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function($) {
    $('#delete_user_session').click(function(){
        if( confirm('您确定此操作码？将会强制所选客户端用户重新登录！') ){
            var os = parseInt($('#os').val());
            var params = {
                'os' : os
            };
            $.ajax({
                type: 'POST',
                url:  '<?php echo site_url("system/cache/delete_user_session"); ?>',
                data: params,
                dataType: 'json',
                success: function(msg){
                    if( msg.err_no==0 ){
                        alert('操作成功');
                    } else {
                        alert(msg.err_msg);
                    }
                }
            });
        }
    });
});

</script>
