<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('enterprise/company'); ?>">企业管理</a></li>
            <li><a href="<?php echo site_url('permission/permission'); ?>">权限管理</a></li>
            <li class="active">添加角色</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12 form_body">
        <div class="alert alert-success" id="success_alert" role="alert" style="display:none;"></div>
        <div class="alert alert-danger" id="danger_alert" role="alert" style="display:none;"></div>

        <form action="<?php echo site_url('permission/role/doAdd'); ?>" id="permission_role_form" method="post">
            <div class="form-horizontal">
                <div class="row">
                    <div class="form-group col-sm-12">
                        <label for="name" class="col-md-1 control-label form_label">
                            <span><font color="red">*</font></span>
                            角色名称
                        </label>
                        <div class="col-md-5">
                            <input type="text" name="name" class="form-control" id="name">
                        </div>
                    </div>
                </div>
                <?php if( !empty($permissions) ){ ?>
                <div class="row">
                    <div class="form-group col-sm-12">
                        <label for="name" class="col-md-1 control-label form_label">
                            <span><font color="red">*</font></span>
                            分配权限
                        </label>
                        <div class="col-md-11">
                            <?php foreach( $permissions as $group_id=>$permission ){ ?>
                            <div class="panel panel-default panel-condensed">
                                <div class="panel-heading">
                                    权限组：<b><?php echo $groupMap[$group_id]['name']; ?></b>
                                    <label><b style="padding-left:10px;"><input type="checkbox" class="checkAll" value="<?php echo $group_id; ?>"> 全选</b></label>
                                </div>
                                <div class="panel-body">
                                    <?php foreach ($permission as $sin_permission) { ?>
                                    <label class="col-md-2" style="font-weight:normal">
                                        <input type="checkbox" name="permission_ids[]" class="permission_id group_<?php echo $group_id; ?>" value="<?php echo $sin_permission['id']; ?>">&nbsp;
                                        <?php echo $sin_permission['name']; ?>
                                    </label>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <div class="form-group form_btn_line">
                    <div class="col-sm-2 col-sm-offset-5 text-center">
                        <button type="submit" class="btn btn-primary btn-block" id="submit_btn">提 交</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function($) {
    $('.checkAll').change( function(){
        var group_id = parseInt($(this).val());
        if( $(this).attr('checked')=='checked' ){
            $('.group_'+group_id).attr('checked','checked');
        } else {
            $('.group_'+group_id).removeAttr('checked');
        }
    });
    $('#permission_role_form').bootstrapValidator(validate_rules.permission_role).on('success.form.bv', function(e) {
        var ids = new Array();
        $('.permission_id').each( function(){
            if( $(this).attr('checked')=='checked' ){
                ids.push($(this).val());
            }
        });
        if( ids.length==0 ){
            alert('请为角色分配至少一个权限');
            return false;
        }
        e.preventDefault();
        var $form = $(e.target);
        var bv = $form.data('bootstrapValidator');

        $.post($form.attr('action'), $form.serialize(), function(rst_json) {
            if(rst_json.err_no != 0){
                $('#danger_alert').empty().text(rst_json.err_msg).show();
                return;
            }
            else{
                $('#success_alert').empty().text('添加成功！').show();
                window.setTimeout(function(){
                    window.location.href = "<?php echo site_url('permission/role'); ?>";
                },2000);
            }
        }, 'json');
    });
});
</script>