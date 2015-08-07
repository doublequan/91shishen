<link href="<?php echo base_url('static/css/bootstrap.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/datetimepicker.css'); ?>" rel="stylesheet" type="text/css">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                编辑用户
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <div class="alert alert-success" id="success_alert" role="alert" style="display:none;"></div>
            <div class="alert alert-danger" id="danger_alert" role="alert" style="display:none;"></div>
            <form action="<?php echo site_url('user/user/doEdit'); ?>" method="post" name="user" id="user_form" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $single['id']; ?>">
                <div class="form-horizontal">
                    <p class="bg-info form-square-title">基础信息</p>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="name" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                用户组
                            </label>
                            <div class="col-sm-9 form_input">
                                <select name="group_id" class="form-control input-sm input-sm" id="group_id">
                                <?php foreach ($groups as $group) { ?>
                                    <option value="<?php echo $group['id']; ?>"<?php echo $group['id']==$single['group_id'] ? ' selected="selected"' : ''; ?>><?php echo trim($group['name']); ?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="name" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                用户名
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="username" class="form-control" value="<?php echo $single['username']; ?>" id="username" readOnly>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="url" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                邮箱
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="email" class="form-control" value="<?php echo $single['email']; ?>" id="email" readOnly>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="logo" class="col-sm-3 control-label form_label">
                                <span><font color="red">*</font></span>
                                手机号码
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="mobile" class="form-control" value="<?php echo $single['mobile']; ?>" id="mobile" readOnly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="pass" class="col-md-3 control-label form_label">
                                登录密码
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="password" name="pass" class="form-control" id="pass" placeholder="不修改请留空">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="repass" class="col-md-3 control-label form_label">
                                重复密码
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="password" name="repass" class="form-control" id="repass">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="logo" class="col-sm-3 control-label form_label">
                                账户余额
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="balance" class="form-control" value="<?php echo $single['balance']; ?>" id="score" readOnly>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sort" class="col-sm-3 control-label form_label">
                                用户积分
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="score" class="form-control" value="<?php echo $single['score']; ?>" id="score" readOnly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="logo" class="col-sm-3 control-label form_label">
                                会员卡卡号
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="cardno" class="form-control" value="<?php echo $single['cardno']; ?>" id="cardno" />
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sort" class="col-sm-3 control-label form_label">
                                用户折扣率
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="discount" class="form-control" value="<?php echo $single['discount']; ?>" id="discount" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="" class="col-md-3 control-label form_label">
                                性别
                            </label>
                            <div class="col-md-9 form_input">
                                <label class="radio-inline">
                                    <input type="radio" name="gender" class="gender" value="0" <?php echo $single['gender']==0?'checked':''; ?>> 保密
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="gender" class="gender" value="1" <?php echo $single['gender']==1?'checked':''; ?>> 男
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="gender" class="gender" value="2" <?php echo $single['gender']==2?'checked':''; ?>> 女
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="idcard" class="col-md-3 control-label form_label">
                                生日
                            </label>
                            <div class="col-md-9 form_input">
                                <input type="text" name="birthday" class="form-control" id="birthday" value="<?php echo $single['birthday']; ?>" readOnly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="logo" class="col-sm-3 control-label form_label">
                                QQ号码
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="qq" class="form-control" value="<?php echo $single['qq']; ?>" id="qq" />
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sort" class="col-sm-3 control-label form_label">
                                联系电话
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="tel" class="form-control" value="<?php echo $single['tel']; ?>" id="tel" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="logo" class="col-sm-3 control-label form_label">
                                默认发票抬头
                            </label>
                            <div class="col-sm-9 form_input">
                                <input type="text" name="receipt_title" class="form-control" value="<?php echo $single['receipt_title']; ?>" id="receipt_title" />
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sort" class="col-sm-3 control-label form_label">
                                默认发票类型
                            </label>
                            <div class="col-sm-9 form_input">
                                <select name="receipt_des" class="form-control input-sm input-sm" id="receipt_des">
                                    <option value="">请选择发票类型</option>
                                <?php foreach ($invoice_types as $v) { ?>
                                    <option value="<?php echo $v; ?>"<?php echo $v==$single['receipt_des'] ? ' selected="selected"' : ''; ?>><?php echo $v; ?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="logo" class="col-sm-3 control-label form_label">
                                最后登录时间
                            </label>
                            <div class="col-sm-9 form_input">
                                <p class="form-control-static"><?php echo date('Y-m-d H:i:s',$single['login_time']); ?></p>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sort" class="col-sm-3 control-label form_label">
                                最后登录IP
                            </label>
                            <div class="col-sm-9 form_input">
                                <p class="form-control-static"><?php echo long2ip($single['login_ip']); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="logo" class="col-sm-3 control-label form_label">
                                注册时间
                            </label>
                            <div class="col-sm-9 form_input">
                                <p class="form-control-static"><?php echo date('Y-m-d H:i:s',$single['create_time']); ?></p>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sort" class="col-sm-3 control-label form_label">
                                注册IP
                            </label>
                            <div class="col-sm-9 form_input">
                                <p class="form-control-static"><?php echo long2ip($single['create_ip']); ?></p>
                            </div>
                        </div>
                    </div>
                    <p class="bg-info form-square-title">关联门店</p>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="logo" class="col-sm-3 control-label form_label">
                                所属省市
                            </label>
                            <div class="col-sm-4 form_input">
                                <select class="form-control" id="prov_id"></select>
                            </div>
                            <div class="col-sm-4 form_input">
                                <select class="form-control" id="city_id"></select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sort" class="col-sm-3 control-label form_label">
                                所属门店
                            </label>
                            <div class="col-sm-9 form_input">
                                <select name="store_id" class="form-control" id="store_id"></select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form_btn_line">
                        <div class="col-sm-2 col-sm-offset-5 text-center">
                            <input type="submit" class="btn btn-primary btn-block" id="btnSubmit" value="提 交" /> 
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?php echo base_url('static/js/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/bootstrap-datetimepicker.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
var provs = <?php echo $provs ? json_encode($provs) : '[]'; ?>;
var citys = <?php echo $citys ? json_encode($citys) : '[]'; ?>;
var stores = <?php echo $stores ? json_encode($stores) : '[]'; ?>;
$(document).ready(function($) {
    var initStore = function(prov_id, city_id, store_id){
        //初始化省份
        var options = [];
        options.push('<option value="">选择省份</option>');
        $.each(provs, function(i, prov){
            options.push('<option value="'+prov.id+'"'+( prov.id==prov_id ? ' selected' : '' )+'>'+prov.name+'</option>');
        });
        $('#prov_id').empty().append(options.join(''));
        //初始化城市
        var options = [];
        options.push('<option value="">选择城市</option>');
        var arr = citys[prov_id];
        if( arr!=undefined ){
            $.each(arr, function(i, city){
                options.push('<option value="'+city.id+'"'+( city.id==city_id ? ' selected' : '' )+'>'+city.name+'</option>');
            });
        }
        $('#city_id').empty().append(options.join(''));
        //初始化门店
        var options = [];
        options.push('<option value="">选择门店</option>');
        var arr = stores[city_id];
        if( arr!=undefined ){
            $.each(arr, function(i, store){
                options.push('<option value="'+store.id+'"'+( store.id==store_id ? ' selected' : '' )+'>'+store.name+'</option>');
            });
        }
        $('#store_id').empty().append(options.join(''));
    }
    initStore(<?php echo $store ? $store['prov'] : 320000; ?>,<?php echo $store ? $store['city'] : 320100; ?>,<?php echo $single['store_id']; ?>);
    $('#prov_id').change(function(){
        var prov_id = $(this).val();
        initStore(prov_id,0,0);
    });
    $('#city_id').change(function(){
        var prov_id = parseInt($('#prov_id').val());
        var city_id = $(this).val();
        initStore(prov_id,city_id,0);
    });

    $('#birthday').datetimepicker({
        language:  'zh',
        format: 'yyyy-mm-dd',
        autoclose: 1,
        todayHighlight: 0,
        startView:2,
        minView: 2,
        maxView: 4,
    });

    $('#user_form').bootstrapValidator(validate_rules.user_edit).on('success.form.bv', function(e) {
        $('#btnSubmit').removeAttr('disabled');
        var pass = $('#pass').val();
        var repass = $('#repass').val();
        if( pass ){
            var ret = confirm('您确定要重置用户的密码吗？');
            if( !ret ){
                return false;
            }
            if( pass.length<6 ){
                alert('新密码不能少于6位');
                return false;
            }
            if( pass!=repass ){
                alert('两次输入的密码不一致');
                return false;
            }
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
                    window.parent.location.reload();
                    window.parent.$.fancybox.close('fade');
                },2000);
            }
        }, 'json');
        
    });
});
</script>
