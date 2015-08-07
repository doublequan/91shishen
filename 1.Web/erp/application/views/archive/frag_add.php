<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                添加碎片
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert"></div>
            <p class="bg-info form-square-title">
                碎片位置： 
                <?php echo isset($sites[$place['site_id']]) ? $sites[$place['site_id']]['name'] : '未知网站'; ?>
                -->
                <?php echo isset($os_types[$place['os']]) ? $os_types[$place['os']] : '未知系统'; ?>
                -->
                <?php echo $place['name']; ?>
            </p>
            <form action="<?php echo site_url('archive/frag/doAddFrag'); ?>" id="form" method="post">
                <input type="hidden" name="place_id" value="<?php echo $place['id']; ?>">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="name" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                碎片类型
                            </label>
                            <div class="col-sm-9 form_input">
                                <select name="type" class="form-control" id="type">
                                <?php foreach ($frag_types as $k=>$v) { ?>
                                    <option value="<?php echo $k; ?>"><?php echo trim($v); ?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="name" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                碎片名称
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="name" class="form-control" id="name" placeholder="请填写此碎片的名称">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="name" class="col-sm-3 control-label form_label">
                                显示标题
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="title" class="form-control" id="title">
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="name" class="col-sm-3 control-label form_label">
                                跳转链接
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="url" class="form-control" id="url">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="name" class="col-sm-3 control-label form_label">
                                显示图片
                            </label>
                            <div class="col-sm-9 form_input">
                                <label class="radio-inline">
                                    <input type="radio" name="img_type" class="img" value="1" checked> 图片地址
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="img_type" class="img" value="0"> 上传图片
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-sm-6" id="img_1">
                            <label for="name" class="col-sm-3 control-label form_label">
                                图片地址
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="img_url" class="form-control" id="img_url">
                            </div>
                        </div>
                        <div class="form-group col-sm-6" id="img_0" style="display:none;">
                            <label for="name" class="col-sm-3 control-label form_label">
                                上传图片
                            </label>
                            <div class="col-sm-5 form_input" id="thumb_line">
                                <input type="hidden" name="img_upload" id="thumb_hide">
                                <input type="file" class="form-control cate_img" id="thumb" name="img">
                            </div>
                            <div class="col-sm-4 form_input">
                                <div class="alert alert-success cate_img_alert" id="thumb_alert">上传成功</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="name" class="col-sm-3 control-label form_label">
                                描述文字
                            </label>
                            <div class="col-sm-9 form_input">
                                <textarea name="des" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="name" class="col-sm-3 control-label form_label">
                                扩展文字
                            </label>
                            <div class="col-sm-9 form_input">
                                <textarea name="extend" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="name" class="col-sm-3 control-label form_label">
                                排序
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="sort" class="form-control">
                            </div>
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
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/ajaxfileupload.js'); ?>" type="text/javascript"></script>
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
                $('#'+ input_id + '_alert').html("上传成功，<a target=\"_blank\" href=\""+data.img+"\">点击查看图片</a>！").show();
            }
            else{
                alert("无法储存文件，请重试！");
            }
        },
        error: function(data, status, e){
            alert("上传文件失败，请重试！");
        }
    });
}
$(document).ready(function($) {
    window.setTimeout(function(){ 
        $('#success_alert, #danger_alert').hide(); 
    },4000);
    
    $('.alert').hide();

    $('.img').change(function(){
        var id = parseInt($(this).val());
        var t = 1-id;
        $('#img_'+t).hide();
        $('#img_'+id).show();
    });

    $(".cate_img").change(function(){
        var cate_img_id = $(this).attr('id');
        file_upload(cate_img_id);
    });

    $('#form').bootstrapValidator(validate_rules.frag).on('success.form.bv', function(e) {
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
                    window.parent.location.reload();
                    window.parent.$.fancybox.close('fade');
                },1000);
            }
        }, 'json');
    });
});

</script>
