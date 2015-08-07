<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                添加大客户定制商品
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert"></div>
            <form action="<?php echo site_url('vip/custom/doAdd'); ?>" method="post" id="custom_form">
                <div class="form">
                    <div class="form-group col-md-6">
                        <label for="" class="control-label form_label">
                            <span><font color="red">*</font></span>
                            大客户公司
                        </label>
                        <div class="form_input">
                            <select name="company_id" class="form-control input-sm" id="company_id">
                                <option value="">选择大客户公司</option>
                            <?php foreach ($companys as $value) { ?>
                                <option value="<?php echo $value['id']; ?>"><?php echo trim($value['name']); ?></option>
                            <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="" class="control-label form_label">
                            <span><font color="red">*</font></span>
                            大客户用户
                        </label>
                        <div class="form_input">
                            <select name="uid" class="form-control input-sm" id="uid">
                                <option value="">选择大客户用户</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading" style="height:40px;">
                                <b>需求商品信息</b>
                                <a class="btn btn-success btn-xs pull-right" id="add_line_btn" href="javascript:void(0);">新增一行</a>
                            </div>
                            <table class="table table-striped table-hover table-bordered text-center" style="font-size:14px;" id="info_table">
                                <thead>
                                    <tr>
                                        <!-- <th style="width:30px;">
                                            #
                                        </th> -->
                                        <th style="width:30%;">
                                            <span><font color="red">*</font></span>
                                            商品名称
                                        </th>
                                        <th style="width:10%;">
                                            <span><font color="red">*</font></span>
                                            数量
                                        </th>
                                        <th style="width:10%;">
                                            <span><font color="red">*</font></span>
                                            单位
                                        </th>
                                        <th style="width:10%;">
                                            <span><font color="red"></font></span>
                                            单价
                                        </th>
                                        <th style="width:30%;">
                                            备注(特殊要求)
                                        </th>
                                        <th style="width:10%;">
                                            操作
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <!-- <td>
                                            <p class="form-control-static input-sm">1</p>
                                        </td> -->
                                        <td>
                                            <div class="form-group">
                                                <input type="text" name="name[]" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" name="amount[]" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" name="unit[]" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" name="price_single[]" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" name="note[]" class="form-control input-sm">
                                            </div>
                                        </td>
                                        <td>
                                            <p class="form-control-static input-sm">
                                                <a href="javascript:void(0);" class="delete_line_link">删除</a>
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="form-group">
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
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function($) {
    window.setTimeout(function(){ 
        $('#success_alert, #danger_alert').hide(); 
    },4000);
    
    $('.alert').hide();

    $('#company_id').change(function(){
        var company_id = $(this).val();
        if(company_id){
            var op_url = "<?php echo site_url('vip/user/getUserByCompany'); ?>";
            $.get(op_url, {'company_id': company_id}, function(data){
                var data = $.parseJSON(data);
                if(data.err_no > 0){
                    $('#danger_alert').empty().text(data.err_msg).show();
                    return false;
                }
                else{
                    var options = [];
                    options.push('<option value="">选择大客户用户</option>');
                    $.each(data.info_list, function(idx, value){
                        options.push('<option value="' + value.id + '">' + value.username + '</option>');
                    });
                    $('#uid').empty().append(options.join(''));
                }
            });
        }
        else{
            $('#uid').empty().append('<option value="">选择大客户用户</option>');
        }
    });

    $(".delete_line_link").live("click",function () {  
        if(confirm('确认删除该定制商品信息？')){
            $(this).parent().parent().parent().remove();
        }        
    });

    $('#add_line_btn').click(function(event) {
        var $info_table = $('#info_table');

        var row_id = $info_table.children('tbody').children('tr').length + 1;

        // if(row_id > 5){
        //     $('#danger_alert').empty().text("抱歉：最多添加5件定制商品！").show();
        //     return;
        // }
        var tr_line = [];
        tr_line.push('<tr row_idx="' + row_id + '">');
        //tr_line.push('<td><p class="form-control-static input-sm">' + row_id + '</p></td>');
        tr_line.push('<td><div class="form-group"><input type="text" name="name[]" class="form-control input-sm"></div></td>');
        tr_line.push('<td><div class="form-group"><input type="text" name="amount[]" class="form-control input-sm"></div></td>');
        tr_line.push('<td><div class="form-group"><input type="text" name="unit[]" class="form-control input-sm"></div></td>');
        tr_line.push('<td><div class="form-group"><input type="text" name="price_single[]" class="form-control input-sm"></div></td>');
        tr_line.push('<td><div class="form-group"><input type="text" name="note[]" class="form-control input-sm"></div></td>');
        tr_line.push('<td><p class="form-control-static input-sm"><a href="javascript:void(0);" class="delete_line_link">删除</a></p></td>');
        tr_line.push('</tr>');

        $info_table.children('tbody').append(tr_line.join(''));       
    });

    $('#custom_form')
        .bootstrapValidator(validate_rules.vip_custom_product)
        .on('success.form.bv', function(e) {
            e.preventDefault();

            var custom_num = $('#info_table tbody tr').length;
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
