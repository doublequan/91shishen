<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb" style="margin-bottom:0;">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('system/system'); ?>">系统设置</a></li>
            <li><a href="<?php echo site_url('system/cache'); ?>">缓存管理</a></li>
            <li class="active">系统缓存</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12 form_body" style="padding-top:0px;">
        <div class="alert alert-success" id="success_alert" role="alert"></div>
        <div class="alert alert-danger" id="danger_alert" role="alert"></div>
        <div class="panel-body">
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th style="width:40px;">
                            #
                        </th>
                        <th style="width:200px;">
                            Cache KEY
                        </th>
                        <th style="width:150px;">
                            操作
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $i = 0;
                        foreach ($config as $k=>$t) {
                        $i++;
                    ?>
                    <tr>
                        <td>
                            <?php echo $i; ?>
                        </td>
                        <td>
                            <?php echo $k; ?>
                        </td>
                        <td row_id="<?php echo $k; ?>">
                            <a href="#" class="delete_link">删除缓存</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script type="text/javascript">
$(document).ready(function($) {
    window.setTimeout(function(){ 
        $('#success_alert, #danger_alert').hide(); 
    },4000);
    
    $('.alert').hide();

    $(".delete_link").click(function(){
        var tThis = $(this);
        var key = tThis.parent('td').attr('row_id');
        if(key){
            $.get("<?php echo site_url('system/cache/delete'); ?>", {'key':key}, function(data){
                var data = $.parseJSON(data);
                if(data && data.err_no==0){
                    $('#success_alert').empty().text('缓存删除成功！').show();
                } else {
                    $('#danger_alert').empty().text('操作失败！'+rst_json.err_msg).show();
                }
            });
        }
    });
});

</script>
