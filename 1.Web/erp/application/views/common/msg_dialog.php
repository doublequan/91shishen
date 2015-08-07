<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">
<script src="<?php echo base_url('static/js/jquery.min.js'); ?>" type="text/javascript"></script>
<div class="container-fluid" style="width:500px; height:200px;">
    <div class="row">
        <div class="col-md-12 form_body" style="width:400px; height:50px;">
            <?php if( $err_no==0 ){ ?>
            <div class="alert alert-success text-left" id="success_alert" role="alert">操作成功，<?php echo $err_msg; ?></div>
            <?php } else { ?>
            <div class="alert alert-danger text-left" id="danger_alert" role="alert">操作失败，<?php echo $err_msg; ?></div>
            <?php } ?>
        </div>
    </div>
</div>