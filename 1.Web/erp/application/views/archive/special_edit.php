<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/datetimepicker.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('archive/archive'); ?>">内容管理</a></li>
            <li><a href="<?php echo site_url('archive/promotion'); ?>">促销管理</a></li>
            <li class="active">编辑促销活动专题</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12 form_body">
        <div class="alert alert-success" id="success_alert" role="alert" style="display:none;"></div>
        <div class="alert alert-danger" id="danger_alert" role="alert" style="display:none;"></div>
        <form action="<?php echo site_url('archive/special/doEdit'); ?>" method="post" id="special_form">
            <input type="hidden" name="id" value="<?php echo $single['id']; ?>">
            <div class="form-horizontal">
                <div class="form-group">
                    <label for="" class="col-md-1 control-label form_label" style="width: 130px;">
                        <span><font color="red">*</font></span>
                        活动名称
                    </label>
                    <div class="col-md-5">
                        <input type="text" name="name" class="form-control" id="name" value="<?php echo $single['name']; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-md-1 control-label form_label" style="width: 130px;">
                        <span><font color="red">*</font></span>
                        活动别名
                    </label>
                    <div class="col-md-2">
                        <input type="text" name="alias" class="form-control" id="alias" value="<?php echo $single['alias']; ?>">
                    </div>
                    <div class="col-md-8">
                        <p class="form-control-static">注：专题别名由2-20位的小写字母和数字组成，使用前请先上传以别名命名的专题模板</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-md-1 control-label form_label" style="width: 130px;">
                        <span><font color="red">*</font></span>
                        Banner Web
                    </label>
                    <div class="col-sm-3 form_input" id="banner_web_line">
                        <input type="file" class="form-control cate_img" id="banner_web" name="img">
                    </div>
                    <div class="col-sm-2">
                        <input type="hidden" name="banner_web" id="banner_web_hide" value="<?php echo $single['banner_web']; ?>">
                        <div class="alert alert-success cate_img_alert" id="banner_web_alert"<?php echo $single['banner_web']=='' ? ' style="display:none;"' : ''; ?>>已上传，<a target="_blank" href="<?php echo $single['banner_web']; ?>">查看图片</a>！</div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-md-1 control-label form_label" style="width: 130px;">
                        <span><font color="red">*</font></span>
                        Banner APP
                    </label>
                    <div class="col-sm-3 form_input" id="banner_app_line">
                        <input type="file" class="form-control cate_img" id="banner_app" name="img">
                    </div>
                    <div class="col-sm-2">
                        <input type="hidden" name="banner_app" id="banner_app_hide" value="<?php echo $single['banner_app']; ?>">
                        <div class="alert alert-success cate_img_alert" id="banner_app_alert"<?php echo $single['banner_app']=='' ? ' style="display:none;"' : ''; ?>>已上传，<a target="_blank" href="<?php echo $single['banner_app']; ?>">查看图片</a>！</div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="datetime" class="col-md-1 control-label form_label" style="width: 130px;">
                        活动开始日期
                    </label>
                    <div class="col-md-3">
                        <input type="text" name="day_start" class="form-control" id="day_start" value="<?php echo $single['day_start']; ?>" readOnly>
                    </div>
                    <div class="col-md-5">
                        <p class="form-control-static">不选择开始日期则为不限</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="datetime" class="col-md-1 control-label form_label" style="width: 130px;">
                        活动结束日期
                    </label>
                    <div class="col-md-3">
                        <input type="text" name="day_end" class="form-control" id="day_end" value="<?php echo $single['day_end']; ?>" readOnly>
                    </div>
                    <div class="col-md-5">
                        <p class="form-control-static">不选择结束日期则为不限</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="datetime" class="col-md-1 control-label form_label" style="width: 130px;">
                        <span><font color="red">*</font></span>
                        活动可用网站
                    </label>
                    <div class="col-md-10">
                        <?php if( isset($sites) && !empty($sites) ){ ?>
                        <?php foreach( $sites as $k=>$row ){ ?>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="sites[]" value="<?php echo $k; ?>"<?php echo in_array($k, $curr_sites) ? ' checked' : ''; ?>><?php echo $row['name']; ?>
                        </label>
                        <?php } ?>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-md-1 control-label form_label" style="width: 130px;">
                        活动商品
                    </label>
                    <div class="col-md-10">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <a href="#add_product_page" kesrc="#add_product_page" class="btn btn-primary btn-sm" id="add_product_btn">添加商品</a>
                            </div>
                            <table class="table table-striped table-hover table-center" id="products_table">
                                <thead>
                                    <tr>
                                        <th width="15%">
                                            商品编号
                                        </th>
                                        <th width="15%">
                                            商品货号
                                        </th>
                                        <th width="25%">
                                            商品名称
                                        </th>
                                        <th width="15%">
                                            所属分类
                                        </th>
                                        <th width="10%">
                                            销售价
                                        </th>
                                        <th width="10%">
                                            排序
                                        </th>
                                        <th width="10%">
                                            操作
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if( $curr_products ){ ?>
                                    <?php foreach( $curr_products as $row ){ ?>
                                    <tr product_id="<?php echo $row['id']; ?>">
                                        <td><?php echo $row['sku']; ?><input type="hidden" value="<?php echo $row['id']; ?>" name="products_id[]"></td>
                                        <td><?php echo $row['product_pin']; ?></td>
                                        <td><?php echo $row['title']; ?></td>
                                        <td><?php echo $row['category_name']; ?></td>
                                        <td>￥<?php echo $row['price']; ?></td>
                                        <td><input type="text" style="width:60px;text-align:center;" value="<?php echo $row['sort']; ?>" name="sorts[]"></td>
                                        <td><a class="delete_tr_link" href="javascript:void(0)">删除</a></td>
                                    </tr>
                                    <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            
                <div class="col-md-12 form-group">
                    <div class="col-md-2 col-md-offset-4 text-center">
                        <button type="submit" class="btn btn-primary btn-block" id="submit_btn">提 交</button>  
                    </div>
                    <div class="col-md-2 text-center">
                        <a class="btn btn-default btn-block" href="<?php echo site_url('archive/special'); ?>">返回列表</a>  
                    </div>
                </div>
            </div>               
        </form>
    </div>
</div>

<iframe src="<?php echo site_url('products/product/muti_select_dialog'); ?>" id="add_product_page" class="iframe_dialog" style="height:580px;"></iframe>
<script src="<?php echo base_url('static/js/jquery.fancybox.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/bootstrap-datetimepicker.js'); ?>" type="text/javascript"></script>
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
                $('#'+ input_id + '_alert').empty().append('上传成功，<a target="_blank" href="'+data.img+'">查看图片</a>！').show();
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
function generateProductTr(product_info, category_name){
    var product_info = $.parseJSON(product_info);
    var product_id = product_info.id;
    var product_sku = product_info.sku;
    var product_pin = product_info.product_pin;
    var product_title = product_info.title;
    var price = product_info.price;

    var tr_str = [];
    tr_str.push('<tr product_id="' + product_id + '">');
    tr_str.push('<td>' + product_sku + '<input type="hidden" name="products_id[]" value="' + product_id + '"></td>');
    tr_str.push('<td>' + product_pin + '</td>');
    tr_str.push('<td>' + product_title + '</td>');
    tr_str.push('<td>' + category_name + '</td>');
    tr_str.push('<td>￥' + price + '</td>');
    tr_str.push('<td><input type="text" name="sorts[]" value="50" style="width:60px;text-align:center;"></td>');
    tr_str.push('<td><a href="javascript:void(0)" class="delete_tr_link">删除</a></td>');
    return tr_str.join('');
}
$(document).ready(function($) {
    $(".cate_img").change(function(){
        var cate_img_id = $(this).attr('id');
        file_upload(cate_img_id);
    });

    $('#day_start,#day_end').datetimepicker({
        language:  'zh',
        format: 'yyyy-mm-dd',
        autoclose: 1,
        todayHighlight: 0,
        startView: 2,
        minView: 2,
        maxView: 3,
    });

    $('.delete_tr_link').click(function(){
        $(this).parent().parent('tr').remove();
    });

    $('a#add_product_btn').click(function(){
        $("a#add_product_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
        });
    });

    $('#special_form').bootstrapValidator(validate_rules.special).on('success.form.bv', function(e) {
        e.preventDefault();
        var $form = $(e.target);
        var bv = $form.data('bootstrapValidator');
        $.post($form.attr('action'), $form.serialize(), function(rst_json) {
            if( rst_json.err_no!=0 ){
                $('#danger_alert').empty().text(rst_json.err_msg).show();
                $('#submit_btn').removeAttr('disabled');
                return;
            } else {
                $('#success_alert').empty().text('修改成功！').show();
                window.setTimeout(function(){
                    window.location.href = "<?php echo site_url('archive/special'); ?>";
                },2000);
            }
        }, 'json');
    });
});

</script>
