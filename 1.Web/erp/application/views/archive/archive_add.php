<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('archive/archive'); ?>">内容管理</a></li>
            <li><a href="<?php echo site_url('archive/add'); ?>">内容编辑</a></li>
            <li class="active">添加内容信息</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-success" id="success_alert" role="alert" style="display:none;"></div>
        <div class="alert alert-danger" id="danger_alert" role="alert" style="display:none;"></div>

        <form action="<?php echo site_url('archive/archive/doAdd'); ?>" method="post" id="archive_form" class="form-horizontal">
            <div class="row">
                <div class="col-sm-12">
                    <p class="bg-info form-square-title">
                        内容基本信息
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6">
                    <label for="sku" class="col-sm-3 control-label form_label">
                        <span><font color="red">*</font></span>
                        标题
                    </label>
                    <div class="col-sm-9 form_input">
                        <input type="text" name="title" class="form-control" id="title">
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label for="title" class="col-sm-3 control-label form_label">
                        <span><font color="red">*</font></span>
                        所属分类
                    </label>
                    <div class="col-sm-9 form_input">
                        <select name="category_id" class="form-control" id="category_id">
                        <?php foreach ($categorys as $row) { ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo trim($row['name']); ?></option>
                        <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6">
                    <label for="" class="col-sm-3 control-label form_label">
                        排序
                    </label>
                    <div class="col-sm-9 form_input">
                        <input type="text" name="sort" class="form-control" id="sort">
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label class="col-sm-3 control-label form_label">
                        模板类型
                    </label>
                    <div class="col-sm-9 form_input">
                        <label class="radio-inline">
                            <input type="radio" name="template_id" value="1" checked> 带分类内容
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="template_id" value="2"> 通栏内容
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p class="bg-info form-square-title">
                        详细内容
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="ueditor_container col-sm-12">
                    <textarea id="content" name="content" style="width:100%;height:400px;"></textarea> 
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <p class="bg-info form-square-title">
                       SEO选项
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6">
                    <label for="seo_title" class="col-sm-2 control-label form_label">
                        SEO标题
                    </label>
                    <div class="col-sm-9 form_input">
                        <input type="text" name="seo_title" class="form-control">
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label for="seo_keywords" class="col-sm-3 control-label form_label">
                        SEO关键词
                    </label>
                    <div class="col-sm-9 form_input">
                        <input type="text" name="seo_keywords" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-12">
                    <label for="seo_description" class="col-sm-1 control-label form_label">
                        SEO描述
                    </label>
                    <div class="col-sm-11 form_input">
                        <textarea name="seo_description" rows="3" class="form-control"></textarea>
                    </div>
                </div>
            </div>

            <div class="form-group form_btn_line">
                <div class="col-sm-3 col-sm-offset-5 text-center">
                    <input type="submit" class="btn btn-primary btn-block" id="submit_btn" value="提 交"> 
                </div>
            </div> 
        </form>
    </div>
</div>

<iframe src="" id="select_dialog" class="iframe_dialog" style="height:500px;"></iframe>

<script src="<?php echo base_url('static/js/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/jquery.fancybox.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo base_url('static/kindeditor/themes/default/default.css'); ?>" />
<script charset="utf-8" src="<?php echo base_url('static/kindeditor/kindeditor-min.js'); ?>"></script>
<script charset="utf-8" src="<?php echo base_url('static/kindeditor/lang/zh_CN.js'); ?>"></script>
<script type="text/javascript">
$(function(){
    var editor;
    KindEditor.ready(function(K) {
        editor = K.create('textarea[name="content"]',{
            uploadJson : '<?php echo site_url("upload/kindeditor"); ?>',
            autoHeightMode : true,
            afterCreate : function() {
                this.loadPlugin('autoheight');
            }
        });
    });

    $('#archive_form').bootstrapValidator(validate_rules.archive).on('success.form.bv', function(e) {
        $('#content').html(editor.html());
        e.preventDefault();
        var $form = $(e.target);
        var bv = $form.data('bootstrapValidator');

        $.post($form.attr('action'), $form.serialize(), function(rst_json) {
            if(rst_json.err_no != 0){
                $('#danger_alert').empty().text(rst_json.err_msg).show();
                return;
            } else {
                $('#success_alert').empty().text('添加成功！').show();
                window.setTimeout(function(){
                    window.location.href = "<?php echo site_url('archive/archive'); ?>";
                },2000);
            }
        }, 'json');
    });
});
</script>