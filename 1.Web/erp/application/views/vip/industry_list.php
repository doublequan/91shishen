<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('vip/product'); ?>">大客户管理</a></li>
            <li><a href="<?php echo site_url('vip/industry'); ?>">大客户行业</a></li>
            <li class="active">行业列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <form class="form-inline" action="<?php echo site_url('vip/industry/doAdd'); ?>" method="post" id="industry_form">
                    <div class="form-group">
                        <p class="form-control-static input-sm">新增行业：</p>
                    </div>
                    <div class="form-group">
                         <input type="text" class="form-control input-sm" name="name">
                    </div>
                    <div class="form-group" style="width:80px;">
                         <input type="submit" class="btn btn-primary btn-sm btn-block" value="添 加">
                    </div>
                    <div class="form-group" style="width:200px;">
                         <div class="alert alert-success cate_img_alert_sm" id="success_alert" role="alert"></div>
                         <div class="alert alert-danger cate_img_alert_sm" id="danger_alert" role="alert"></div>
                    </div>
                </form>
            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th style="width:40px;">
                            #
                        </th>
                        <th style="width:200px;">
                            行业名称
                        </th>
                        <th style="width:80px;">
                            操作
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $idx = 0;
                        foreach ($results as $single_info) {
                    ?>
                    <tr>
                        <td>
                            <?php echo ++$idx; ?>
                        </td>
                        <td class="edit_name_square">
                            <?php echo $single_info['name']; ?>
                        </td>
                        <td single_id="<?php echo $single_info['id']; ?>" single_name="<?php echo $single_info['name']; ?>" style="padding:5px;">
                            <a href="javascript:void(0);" class="btn btn-xs btn-success edit_link">修改行业名称</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php }else{ ?>
            <div class="alert alert-warning col-md-12 text-center" role="alert">查询结果为空！</div>
            <?php } ?>
        </div>
    </div>
</div>

<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
    $('.cate_img_alert_sm').hide();

    $('.edit_link').click(function(event) {
        var single_id = $(this).parent('td').attr('single_id');
        var single_name = $(this).parent('td').attr('single_name');

        var edit_str = '';
        edit_str += '<div class="col-md-offset-3 col-md-6"><input type="text" class="form-control input-sm single_name_edit" name="name" value="' + single_name + '">';
        edit_str += '<input type="hidden" class="single_id_edit" value="' + single_id + '"></div>';
        edit_str += '<div class="col-md-2"><button type="button" class="btn btn-primary btn-sm btn-block edit_btn">确 定</button></div>';

        $(this).parent('td').prevAll('td.edit_name_square').css('padding','2px').empty().append(edit_str);

        $('.edit_btn').click(function(event) {
            var edit_name = $(this).parent('div').prev('div').children('.single_name_edit').val();
            var edit_id = $(this).parent('div').prev('div').children('.single_id_edit').val();

            if(edit_name){
                $.post("<?php echo site_url('vip/industry/doEdit'); ?>", {'name': edit_name, 'id': edit_id}, function(data) {
                    var rst_data = $.parseJSON(data);

                    if(rst_data.err_no != 0){
                        alert('修改失败！原因：' + rst_data.err_msg);
                        return;
                    }
                    else{
                        alert('修改成功！');
                        window.location.reload();
                    }
                });
            }
            else{
                return alert('损耗类型名称不能为空！');
            }
            
        });
    });

    $('#industry_form')
        .bootstrapValidator(validate_rules.vip_industry)
        .on('success.form.bv', function(e) {
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
                        window.location.reload();
                    },1500);
                }
            }, 'json');
        });

    $(".delete_link").click(function(){
        var industry_id = $(this).parent('td').attr('industry_id');

        if(industry_id && confirm('确认删除行业信息？')){
            $.get("<?php echo site_url('products/industry/delete'); ?>", {'industry_id': industry_id}, function(data){
                var data = $.parseJSON(data);

                if(data && data.error){
                    alert(data.msg);
                }
                else{
                    alert("删除成功！");
                }
                window.location.reload();
            });
        }
    });
    
});

</script>