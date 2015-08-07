<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">
<script src="<?php echo base_url('static/js/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                编辑内容分类
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert"></div>
            <form action="<?php echo site_url('archive/category/doEdit'); ?>" id="category_form" method="post">
                <input type="hidden" name="id" value="<?php echo $single['id']; ?>">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            分类名称
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="name" class="form-control input-sm" id="name" value="<?php echo $single['name']; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            分类别名
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="alias" class="form-control input-sm" id="alias" value="<?php echo $single['alias']; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sort" class="col-sm-2 control-label form_label">
                            排序
                        </label>
                        <div class="col-sm-3 form_input">
                            <input type="text" name="sort" class="form-control input-sm" id="sort" value="<?php echo $single['sort']; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label form_label">
                            SEO标题
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="seo_title" class="form-control input-sm" id="seo_title" value="<?php echo $single['seo_title']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label form_label">
                            SEO关键词
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="seo_keywords" class="form-control input-sm" id="seo_keywords" value="<?php echo $single['seo_keywords']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label form_label">
                            SEO描述
                        </label>
                        <div class="col-sm-9 form_input">
                            <textarea name="seo_description" id="seo_description" rows="3" class="form-control input-sm"><?php echo $single['seo_description']; ?></textarea>
                        </div>
                    </div>

                    <div class="form-group form_btn_line">
                        <div class="col-sm-2 col-sm-offset-5 text-center">
                            <button type="submit" class="btn btn-primary btn-block" id="submit_btn">提 交</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function($) {
    window.setTimeout(function(){ 
        $('#success_alert, #danger_alert').hide(); 
    },4000);
    
    $('.alert').hide();

    $('#category_form').bootstrapValidator(validate_rules.archive_category).on('success.form.bv', function(e) {
        e.preventDefault();
        var $form = $(e.target);
        var bv = $form.data('bootstrapValidator');
        $.post($form.attr('action'), $form.serialize(), function(rst_json) {
            if(rst_json.err_no != 0){
                $('#danger_alert').empty().text(rst_json.err_msg).show();
                return;
            }
            else{
                $('#success_alert').empty().text('修改成功！').show();
                window.setTimeout(function(){
                    window.parent.location.reload();
                    window.parent.$.fancybox.close('fade');
                },2000);
            }
        }, 'json');
    });
});

</script>
