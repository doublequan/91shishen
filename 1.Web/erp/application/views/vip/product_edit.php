<link href="<?php echo base_url('static/uploadify/uploadify.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('vip/product'); ?>">大客户管理</a></li>
            <li><a href="<?php echo site_url('vip/product'); ?>">大客户商品</a></li>
            <li class="active">编辑商品</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-success" id="success_alert" role="alert"></div>
        <div class="alert alert-danger" id="danger_alert" role="alert"></div>

        <form action="<?php echo site_url('vip/product/doEdit'); ?>" method="post" id="product_form" class="form-horizontal">
            <input type="hidden" name="id" value="<?php echo $single['id']; ?>">
            <div class="row">
                <div class="col-sm-12">
                    <p class="bg-info form-square-title">
                       商品基本信息
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6">
                    <label for="sku" class="col-sm-3 control-label form_label">
                        <span><font color="red">*</font></span>
                        商品编码
                    </label>
                    <div class="col-sm-9 form_input">
                        <input type="text" name="sku" class="form-control" id="sku" value="<?php echo $single['sku']; ?>">
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label for="title" class="col-sm-3 control-label form_label">
                        <span><font color="red">*</font></span>
                        商品名称
                    </label>
                    <div class="col-sm-9 form_input">
                        <input type="text" name="title" class="form-control" id="title" value="<?php echo $single['title']; ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6">
                    <label for="category_id" class="col-sm-3 control-label form_label">
                        <span><font color="red">*</font></span>
                        所属分类
                    </label>
                    <div class="col-sm-9 form_input">
                        <select name="category_id" class="form-control" id="category_id">
                            <option value="">选择所属分类</option>
                        <?php foreach ($categorys as $value) { ?>
                            <option value="<?php echo $value['id']; ?>" <?php echo $single['category_id']==$value['id']?'selected':''; ?>><?php echo trim($value['name']); ?></option>
                        <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label for="price" class="col-sm-3 control-label form_label">
                        <span><font color="red">*</font></span>
                        销售价(元)
                    </label>
                    <div class="col-sm-9 form_input">
                        <input type="text" name="price" class="form-control" id="price" value="<?php echo $single['price']; ?>" readOnly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6">
                    <label for="spec" class="col-sm-3 control-label form_label">
                        <span><font color="red">*</font></span>
                        商品规格
                    </label>
                    <div class="col-sm-9 form_input">
                        <select name="spec" class="form-control" id="spec">
                            <option value="">选择商品规格</option>
                        <?php foreach ($specs as $spec) { ?>
                            <option value="<?php echo $spec['id']; ?>" <?php echo $spec['id']==$single['spec']?'selected':''; ?>><?php echo trim($spec['name']); ?></option>
                        <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label for="spec_packing" class="col-sm-3 control-label form_label">
                        <span><font color="red">*</font></span>
                        包装规格
                    </label>
                    <div class="col-sm-9 form_input">
                        <select name="spec_packing" class="form-control" id="spec_packing">
                            <option value="">选择包装规格</option>
                        <?php foreach ($specs as $spec) { ?>
                            <option value="<?php echo $spec['id']; ?>" <?php echo $spec['id']==$single['spec_packing']?'selected':''; ?>><?php echo trim($spec['name']); ?></option>
                        <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6">
                    <label for="unit" class="col-sm-3 control-label form_label">
                        <span><font color="red">*</font></span>
                        计价单位
                    </label>
                    <div class="col-sm-9 form_input">
                        <select name="unit" class="form-control" id="unit">
                            <option value="">选择计价单位</option>
                        <?php foreach ($specs as $spec) { ?>
                            <option value="<?php echo $spec['id']; ?>" <?php echo $spec['id']==$single['unit']?'selected':''; ?>><?php echo trim($spec['name']); ?></option>
                        <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label for="" class="col-sm-3 control-label form_label">
                        <span><font color="red">*</font></span>
                        列表图片
                    </label>
                    <div class="col-sm-6 form_input" id="thumb_line">
                        <input type="file" class="form-control cate_img" id="thumb" name="img">
                    </div>
                    <div class="col-sm-3">
                        <input type="hidden" name="thumb" id="thumb_hide" value="<?php echo $single['thumb']; ?>">
                        <div class="alert alert-success cate_img_alert" id="thumb_alert">已上传！</div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="form-group col-sm-6">
                    <label for="brand_id" class="col-sm-3 control-label form_label">
                        所属品牌
                    </label>
                    <div class="col-sm-9 form_input">
                        <select name="brand_id" class="form-control" id="brand_id">
                            <option value="">选择所属品牌</option>
                        <?php foreach ($brands as $brand) { ?>
                            <option value="<?php echo $brand['id']; ?>" <?php echo $brand['id']==$single['brand_id']?'selected':''; ?>><?php echo trim($brand['name']); ?></option>
                        <?php } ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <p class="bg-info form-square-title">
                       商品图片
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-12">
                    <label for="delivery_time" class="col-sm-1 control-label form_label">
                        <span><font color="red">*</font></span>
                        商品图片
                    </label>
                    <div class="col-sm-11">
                        <div class="row">
                            <div class="col-sm-3">
                                <input type="button" id="fileUpload">
                            </div>
                            <div class="col-sm-8">
                                <!-- <a href="javascript:$('#fileUpload').uploadify('upload','*')" class="btn btn-default btn-sm">开始上传</a> -->
                                <p class="form-control-static form-help-p">请使用300x300或者等比例的图片，图片最多上传10张</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="upload_process"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <ul id="fileArea">
                                    <?php 
                                    $fileArea_str = '';
                                    if(!empty($products_img) && is_array($products_img)){
                                        foreach ($products_img as $single_pro_img) {
                                            $fileArea_str .= '<li><div class="thumbnail">';
                                            $fileArea_str .= '<img src="'.$single_pro_img['img'].'">';
                                            $fileArea_str .= '<input type="hidden" name="images[]" value="'.$single_pro_img['img'].'">';
                                            $fileArea_str .= '<input type="hidden" name="thumbs[]" value="'.$single_pro_img['thumb'].'">';
                                            $fileArea_str .= '<div class="caption"><input type="text" class="form-control input-sm" name="sorts[]" value="'.$single_pro_img['sort'].'" style="width:70px;"> ';
                                            $fileArea_str .= '<button type="button" class="btn btn-default btn-sm img_del" onClick="delete_single_img(this)">删 除</button></div></div></li>';
                                        }
                                    }
                                    echo $fileArea_str;
                                    ?>
                                </ul>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <p class="bg-info form-square-title">
                        商品详细描述
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="ueditor_container col-sm-12">
                    <script id="content_container" name="content" type="text/plain"></script>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <p class="bg-info form-square-title">
                       营销选项
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6">
                    <label for="seo_title" class="col-sm-2 control-label form_label">
                        SEO标题
                    </label>
                    <div class="col-sm-10 form_input">
                        <input type="text" name="seo_title" class="form-control" id="seo_title" value="<?php echo $single['seo_title']; ?>">
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label for="seo_keywords" class="col-sm-3 control-label form_label">
                        SEO关键词
                    </label>
                    <div class="col-sm-9 form_input">
                        <input type="text" name="seo_keywords" class="form-control" id="seo_keywords" value="<?php echo $single['seo_keywords']; ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-12">
                    <label for="seo_description" class="col-sm-1 control-label form_label">
                        SEO描述
                    </label>
                    <div class="col-sm-11 form_input">
                        <textarea name="seo_description" id="seo_description" rows="3" class="form-control"><?php echo $single['seo_description']; ?></textarea>
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

<script src="<?php echo base_url('static/js/ajaxfileupload.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/uploadify/jquery.uploadify.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/ueditor/ueditor.config.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/ueditor/ueditor.all.min.js'); ?>" type="text/javascript"></script>

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
    var input_content = '<input type="file" name="img" class="form-control cate_img" value="' + input_id + '" id="'+ input_id + '" onchange="file_upload(this.id);" />';
    $('#'+ input_id + '_line').prepend(input_content);
}

function delete_single_img(event){
    $(event).parent().parent().parent().remove();
};

$(function(){
    window.setTimeout(function(){ 
        $('#success_alert, #danger_alert').hide(); 
    },4000);

    $('.alert').hide();

    var thumb_value = "<?php echo isset($single['thumb'])?$single['thumb']:''; ?>";
    if(thumb_value.length > 1){
        $('#thumb_alert').show();
    }

    $(".cate_img").change(function(){
        var cate_img_id = $(this).attr('id');
        file_upload(cate_img_id);
    });

    var ue = UE.getEditor('content_container');
    <?php if(!empty($single['content'])){ ?>
    ue.ready(function() {
        ue.setContent(<?php echo "'".$single['content']."'"; ?>);
    });
    <?php } ?>

    $("#fileUpload").uploadify({
        'auto'              : true,
        'buttonText'        : '请选择图片',
        'fileObjName'       : 'img',
        'fileSizeLimit'     : '1000KB',
        'fileTypeExts'      : '*.jpg; *.jpeg; *.gif; *.png; *.bmp;',
        'formData'          : {'is_thumb':1,'thumb_width':300,'thumb_height':300,'is_resize':0,'resize_width':1000,'resize_height':1000},
        'multi'             : true,
        'queueID'           : 'upload_process',
        'queueSizeLimit'    : 10,
        'removeCompleted'   : true,
        'removeTimeout'     : 2,
        'swf'               : "<?php echo base_url('static/uploadify/uploadify.swf'); ?>",
        'uploader'          : "<?php echo site_url('upload/img'); ?>",
        'width'             : 200,
        'onUploadSuccess' : function( file, data, response ) {
            var msg = $.parseJSON(data);
            var html = '<li><div class="thumbnail">';
            html += '<img src="'+msg.img+'">';
            html += '<input type="hidden" name="images[]" value="'+msg.img+'">'
            html += '<input type="hidden" name="thumbs[]" value="'+msg.thumb+'">';
            html += '<div class="caption">';
            html += '<input type="text" class="form-control" name="sorts[]" value="50" style="width:70px;"> ';
            html += '<button type="button" class="btn btn-default btn-sm img_del">删 除</button>';
            html += '</div></div></li>';
            $('#fileArea').append(html);
            $('.img_del').click(function(){
                $(this).parent().parent().parent().remove();
            });
        }
    });
    
    $('#product_form')
        .bootstrapValidator(validate_rules.vip_product)
        .on('success.form.bv', function(e) {
            if(!$('#thumb_hide').val()){
                alert('请上传商品列表图片！');
                $('#submit_btn').removeAttr('disabled');
                return false;
            }
            if($('ul#fileArea').children('li').length == 0){
                alert('请上传商品图片！');
                $('#submit_btn').removeAttr('disabled');
                return false;
            }
            if($('ul#fileArea').children('li').length > 10){
                alert('最多上传10张商品列表图片！');
                $('#submit_btn').removeAttr('disabled');
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
                    $('#success_alert').empty().text('修改成功！').show();
                    window.setTimeout(function(){
                        window.location.href = "<?php echo site_url('vip/product'); ?>";
                    },2000);
                }
            }, 'json');
            
        });


});

</script>

