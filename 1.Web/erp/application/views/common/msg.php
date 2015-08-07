<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_body">
            <?php if( $err_no==0 ){ ?>
            <div class="alert alert-success" id="success_alert" role="alert">操作成功，<?php echo $err_msg; ?>，<a href="javascript:history.back();">返回上一页</a></div>
            <?php } else { ?>
            <div class="alert alert-danger" id="danger_alert" role="alert">操作失败，<?php echo $err_msg; ?>，<a href="javascript:history.back();">返回上一页</a></div>
            <?php } ?>
        </div>
    </div>
</div>