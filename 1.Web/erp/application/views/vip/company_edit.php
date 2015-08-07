<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                编辑大客户公司信息
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert"></div>

            <form action="<?php echo site_url('vip/company/doEdit'); ?>" method="post" id="company_form">
                <input type="hidden" name="id" value="<?php echo $single['id']; ?>">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            公司名称
                        </label>
                        <div class="col-sm-9">
                            <input type="text" name="name" class="form-control" id="name" value="<?php echo $single['name']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tel" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            公司电话
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="tel" class="form-control" id="tel" value="<?php echo $single['tel']; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            所属城市
                        </label>
                        <div class="col-sm-9 form_input">
                            <div class="row">
                                <div class="col-sm-6">
                                    <select name="prov" class="form-control" id="province_select">
                                        <option value="">请选择省区</option>
                                    <?php foreach ($province_list as $province) { ?>
                                        <option value="<?php echo $province['id']; ?>" <?php echo $province['id']==$single['prov']?'selected':''; ?>><?php echo $province['name']; ?></option>
                                    <?php } ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <select name="city" id="city_select" class="form-control">
                                        <option value="">请选择</option>
                                    <?php 
                                    if(!empty($city_list)){
                                        foreach ($city_list as $city) {
                                    ?>
                                        <option value="<?php echo $city['id']; ?>" <?php echo $city['id']==$single['city']?'selected':''; ?>><?php echo $city['name']; ?></option>
                                    <?php }} ?>    
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            公司地址
                        </label>
                        <div class="col-sm-9 form_input">
                            <input type="text" name="address" class="form-control" id="address" value="<?php echo $single['address']; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            所属行业
                        </label>
                        <div class="col-sm-9 form_input">
                            <select name="industry_id" class="form-control" id="industry_id">
                                <option value="">请选择所属行业</option>
                            <?php foreach ($industrys as $value) { ?>
                                <option value="<?php echo $value['id']; ?>" <?php echo $value['id']==$single['industry_id']?'selected':''; ?>><?php echo $value['name']; ?></option>
                            <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label form_label">
                            <span><font color="red">*</font></span>
                            公司规模
                        </label>
                        <div class="col-sm-9 form_input">
                            <select name="scale" class="form-control" id="scale">
                                <option value="">请选择所属行业</option>
                            <?php foreach ($scaleMap as $key => $value) { ?>
                                <option value="<?php echo $key; ?>" <?php echo $key==$single['scale']?'selected':''; ?>><?php echo $value; ?></option>
                            <?php } ?>
                            </select>
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
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function($) {
    window.setTimeout(function(){ 
        $('#success_alert, #danger_alert').hide(); 
    },4000);

    $('#success_alert, #danger_alert').hide();

    $('#province_select').change(function(){
        var prov = $(this).val();
        var area_control = "<?php echo site_url('area/getCityList'); ?>";
        $.get(area_control, {'province_id': prov}, function(data){
            var data = $.parseJSON(data);
            var options = [];
            options.push('<option value="">选择城市</option>')
            $.each(data, function(idx, city){
                options.push('<option value="' + city.id + '">' + city.name + '</option>');
            });
            $('#city_select').empty().append(options.join('')).parent('div').show();
        });
    });

    $('#company_form')
        .bootstrapValidator(validate_rules.vip_company)
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
