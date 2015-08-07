<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                新增分类
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert"></div>

            <form action="<?php echo site_url('products/category/doAdd'); ?>" id="category_form" method="post">
                <div class="form-horizontal">
                    <div class="form-group" id="dept_name_line">
                        <label for="name" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            分类名称
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="name" class="form-control input-sm" id="name">
                        </div>
                    </div>

                    <div class="form-group select_line">
                        <label for="father_id" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            上级分类
                        </label>
                        <div class="col-sm-9 form_input">
                            <select name="father_id" class="form-control input-sm input-sm" id="father_id">
                                <option value="0">顶级分类</option>
                                <?php echo getTreeOptions($category_list); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sort" class="col-sm-2 control-label form_label">
                            排序
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="sort" class="form-control input-sm" id="sort">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label form_label">
                            类型
                        </label>
                        <div class="col-sm-9 form_input">
                            <label class="radio-inline">
                            <input type="radio" name="type" value="1" checked> 普通分类
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="type" value="2"> 积分分类
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label form_label">
                            Web版本图标
                        </label>
                        <div class="col-sm-6 form_input" id="thumb_web_line">
                            <input type="file" class="form-control input-sm cate_img" id="thumb_web" name="img">
                        </div>
                        <div class="col-sm-3">
                            <input type="hidden" name="thumb_web" id="thumb_web_hide">
                            <div class="alert alert-success cate_img_alert_sm" id="thumb_web_alert">上传成功</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label form_label">
                           客户端图标
                        </label>
                        <div class="col-sm-6 form_input" id="thumb_app_line">
                            <input type="file" class="form-control input-sm cate_img" id="thumb_app" name="img">
                        </div>
                        <div class="col-sm-3">
                            <input type="hidden" name="thumb_app" id="thumb_app_hide">
                            <div class="alert alert-success cate_img_alert_sm" id="thumb_app_alert"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label form_label">
                            Wap版本图标
                        </label>
                        <div class="col-sm-6 form_input" id="thumb_wap_line">
                            <input type="file" class="form-control input-sm cate_img" id="thumb_wap" name="img">
                        </div>
                        <div class="col-sm-3">
                            <input type="hidden" name="thumb_wap" id="thumb_wap_hide">
                            <div class="alert alert-success cate_img_alert_sm" id="thumb_wap_alert"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label form_label">
                            SEO标题
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="seo_title" class="form-control input-sm" id="seo_title">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label form_label">
                            SEO关键词
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="seo_keywords" class="form-control input-sm" id="seo_keywords">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label form_label">
                            SEO描述
                        </label>
                        <div class="col-sm-9 form_input">
                            <textarea name="seo_description" id="seo_description" rows="3" class="form-control input-sm"></textarea>
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

<script src="<?php echo base_url('static/js/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/ajaxfileupload.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">

function file_upload(input_id)
{
    if(!input_id){
        return alert('系统错误，请刷新页面！');
    }
    var link = "<?php echo site_url('upload/img'); ?>";
    $.ajaxFileUpload({
        url:           link,
        secureuri:     false,
        fileElementId: input_id,
        dataType:      'json',
        success:       function(data){
            if(data.img){
                $('#'+ input_id + '_hide').attr("value", data.img);
                $('#'+ input_id + '_alert').text("上传成功！").show();
            }
            else{
                alert("无法储存文件，请重试！");
            }  
        },
        error: function(data, status, e){
            alert("上传文件失败，请重试！");
        }
    });

    $('#'+ input_id).remove();
    var input_content = '<input type="file" name="img" class="form-control input-sm cate_img" id="'+ input_id + '"onchange="file_upload(this.id);" />';
    $('#'+ input_id + '_line').prepend(input_content);
}

$(document).ready(function($) {
    $('.alert').hide();

    $('#site_id').change(function(event) {
        var site_id = $(this).val();
        if(site_id){
            $('#father_id option.cate_ops').removeAttr('selected').hide();
            $('#father_id option.cate_ops[site_id="'+ site_id + '"]').show();
        }
    });

    $(".cate_img").change(function(){
        var cate_img_id = $(this).attr('id');
        file_upload(cate_img_id);
    });

    $('#category_form')
        .bootstrapValidator(validate_rules.category)
        .on('success.form.bv', function(e) {
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');

            $('.alert').hide();
            $.post($form.attr('action'), $form.serialize(), function(rst_json) {
                if(rst_json.err_no != 0){
                    $('#danger_alert').empty().text(rst_json.err_msg).show();
                    return;
                }
                else{
                    $('#success_alert').empty().text('添加成功！').show();
                    window.setTimeout(function(){
                        window.parent.location.reload();
                        window.parent.$.fancybox.close('fade');
                    },2000);
                }
            }, 'json');
        });
});

</script>
