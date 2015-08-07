<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>惠生活ERP管理系统</title>
<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">
<script src="<?php echo base_url('static/js/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/bootstrap.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/common.js'); ?>" type="text/javascript"></script>
<link rel="shortcut icon" href="<?php echo FILE_DOMAIN ?>static/favicon.ico" />
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                编辑原料信息
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert" style="display:none;"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert" style="display:none;"></div>

            <form action="<?php echo site_url('products/good/doEdit'); ?>" method="post" id="good_form">
                <div class="form-horizontal">
                    <input type="hidden" name="id" value="<?php echo $single['id']; ?>">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            原料名称
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="name" class="form-control" id="name" value="<?php echo $single['name']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="category_id" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            所属分类
                        </label>
                        <div class="col-sm-9 form_input">
                            <select name="category_id" class="form-control input-sm input-sm" id="category_id">
                                <option value="">请选择所属分类</option>
                                <?php echo getTreeOptions($category_list); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="method" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            计价方式
                        </label>
                        <div class="col-sm-9 form_input">
                            <?php if( $methodMap ) { ?>
                            <?php foreach( $methodMap as $k=>$v ) { ?>
                            <label class="radio-inline">
                                <input type="radio" name="method" class="method" value="<?php echo $k; ?>"<?php echo $k==$single['method'] ? ' checked' : ''; ?>> <?php echo $v; ?>
                            </label>
                            <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="unit" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            计价单位
                        </label>
                        <?php if( $unitMap ) { ?>
                        <?php foreach( $unitMap as $method=>$units ) { ?>
                        <div class="col-sm-9 form_input units" id="units_<?php echo $method; ?>"<?php echo $method==$single['method'] ? '' : ' style="display:none;"'; ?>>
                            <?php foreach( $units as $k=>$v ) { ?>
                            <label class="radio-inline">
                                <input type="radio" name="unit" class="unit" value="<?php echo $v; ?>"<?php echo $v==$single['unit'] ? ' checked' : ''; ?>> <?php echo $v; ?>
                            </label>
                            <?php } ?>
                        </div>
                        <?php } ?>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label for="amount" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            单位数量
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="amount" class="form-control" id="amount" value="<?php echo $single['amount']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label form_label">
                            原料图片
                        </label>
                        <div class="col-sm-4 form_input" id="thumb_line">
                            <input type="file" class="form-control cate_img" id="thumb" name="img">
                        </div>
                        <div class="col-sm-5">
                            <input type="hidden" name="thumb" id="thumb_hide" value="<?php echo $single['thumb']; ?>">
                            <div class="alert alert-success cate_img_alert" id="thumb_alert" style="display:none;">已上传图片！</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="brand_id" class="col-sm-2 control-label form_label">
                            所属品牌
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="brand" class="form-control" id="brand" value="<?php echo $single['brand']; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group form_btn_line">
                        <div class="col-sm-2 col-sm-offset-5 text-center">
                            <input type="submit" class="btn btn-primary btn-block" value="提 交"> 
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
    var input_content = '<input type="file" name="img" class="form-control cate_img" id="'+ input_id + '" onchange="file_upload(this.id);" />';
    $('#'+ input_id + '_line').prepend(input_content);
}

$(document).ready(function($) {
    var category_selected = "<?php echo isset($single['category_id'])?$single['category_id']:0; ?>";
    $('#category_id option.cate_ops[value="' + category_selected + '"]').attr('selected', true);

    var thumb_value = "<?php echo isset($single['thumb'])?$single['thumb']:''; ?>";
    if(thumb_value.length > 1){
        $('#thumb_alert').show();
    }

    $(".cate_img").change(function(){
        var cate_img_id = $(this).attr('id');
        file_upload(cate_img_id);
    });

    $('.method').change(function(){
        var method = $(this).val();
        $('.units').hide();
        $('#units_'+method).show();
    });

    $('#good_form').bootstrapValidator(validate_rules.good).on('success.form.bv', function(e) {
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
</body>
</html>