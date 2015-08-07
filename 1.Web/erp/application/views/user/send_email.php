<link href="<?php echo base_url('static/css/fuelux.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/datetimepicker.css'); ?>" rel="stylesheet" type="text/css">
<style>
.dropdown-menu{
    padding: 5px;
}
</style>

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('user/user'); ?>">用户管理</a></li>
            <li><a href="<?php echo site_url('user/send/push'); ?>">用户营销</a></li>
            <li class="active">邮件群发</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12 form_body" style="padding-top:0px;">
        <div class="alert alert-success" id="success_alert" role="alert"></div>
        <div class="alert alert-danger" id="danger_alert" role="alert"></div>

        <form action="<?php echo site_url('user/send/doAddSMS'); ?>" method="post" id="dispatch_form">
            <div class="form-horizontal">
                <div class="form-group">
                    <label for="datetime" class="col-md-1 control-label form_label">
                        <span><font color="red">*</font></span>
                        用户范围
                    </label>
                    <div class="col-md-11">
                        <label class="radio-inline"><input type="radio" name="type" class="type" value="0" checked>全部用户</label>&nbsp;&nbsp;
                        <label class="radio-inline"><input type="radio" name="type" class="type" value="1">按用户组</label>&nbsp;&nbsp;
                        <label class="radio-inline"><input type="radio" name="type" class="type" value="2">按具体用户</label>
                    </div>
                </div>

                <div class="form-group typeArea" id="type_0">
                    <label for="" class="col-md-1 control-label form_label">
                        <span><font color="red">*</font></span>
                        全部用户
                    </label>
                    <div class="col-md-11">
                        <p class="form-control-static">使用全部用户有风险，操作需谨慎</p>
                    </div>
                </div>

                <div class="form-group typeArea hidden" id="type_1">
                    <label for="" class="col-md-1 control-label form_label">
                        <span><font color="red">*</font></span>
                        选择用户组
                    </label>
                    <div class="col-sm-2 form_input">
                        <select name="group_id" class="form-control input-sm input-sm" id="group_id">
                        <?php foreach ($groups as $group) { ?>
                            <option value="<?php echo $group['id']; ?>"><?php echo trim($group['name']); ?></option>
                        <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group typeArea hidden" id="type_2">
                    <label for="" class="col-md-1 control-label form_label">
                        <span><font color="red">*</font></span>
                        用户筛选
                    </label>
                    <div class="col-md-11 fuelux">
                        <div class="pillbox" data-initialize="pillbox" id="myPillbox">
                          <ul class="clearfix pill-group">
                            <li class="pillbox-input-wrap btn-group">
                              <a class="pillbox-more">and <span class="pillbox-more-count"></span> more...</a>
                              <input type="text" class="form-control dropdown-toggle pillbox-add-item" id="search_user_ipt" placeholder="输入用户名并搜索">
                              <button type="button" class="dropdown-toggle sr-only">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                              </button>
                              <ul class="suggest dropdown-menu" role="menu" data-toggle="dropdown" data-flip="auto"></ul>
                            </li>
                          </ul>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-1 control-label form_label">
                        <span><font color="red">*</font></span>
                        邮件主题
                    </label>
                    <div class="col-md-5 form_input">
                        <input type="text" name="subject" class="form-control" value="" id="subject">
                    </div>
                </div>

                <div class="row">
                    <label for="" class="col-md-1 control-label form_label">
                        <span><font color="red">*</font></span>
                        邮件内容
                    </label>
                </div>
                <div class="row">
                    <div class="ueditor_container col-sm-12">
                        <script id="content_container" name="content" type="text/plain"></script>
                    </div>
                </div>
            
                <div class="col-md-12 form-group">
                    <div class="col-md-2 col-md-offset-1 text-center">
                        <input type="hidden" name="uids" id="uids" value="">
                        <button type="button" class="btn btn-primary btn-block" id="submit_btn">提 交</button>  
                    </div>
                </div>
            </div>               
        </form>
    </div>
</div>


<input type="hidden" id="info_hidden">

<script src="<?php echo base_url('static/js/fuelux.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/ueditor/ueditor.config.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/ueditor/ueditor.all.min.js'); ?>" type="text/javascript"></script>

<script type="text/javascript">
$('#myPillbox').pillbox({
    edit: true,
    onKeyDown: function( date, callback ){
        var username = $('#search_user_ipt').val();
        if(username.length > 0){
            var op_url = "<?php echo site_url('user/send/searchUsers'); ?>";
            $.get(op_url, { keyword : username}, function(rst_data){
                if(rst_data.data) {
                    callback({data:rst_data.data});
                }
                else{
                    callback({});
                }
            }, 'json');
        }
    }
});

$(document).ready(function($) {
    var ue = UE.getEditor('content_container');

    $('.alert').hide();

    $('.type').change( function(){
        var type = $(this).val();
        $('.typeArea').addClass('hidden');
        $('#type_'+type).removeClass('hidden');
    });

    $('#submit_btn').click( function(){
        //get type
        var type = $('.type:checked').val();
        //get group
        var group_id = $('#group_id').val();
        //get uids
        var uids = new Array();
        if( type==2 ){
            $('#myPillbox ul li.pill').each(function(){
                uids.push($(this).attr('data-value'));
            });
            if( false==uids ){
                $('#danger_alert').empty().text('请至少选择一个用户').show(500);
                return false;
            }
            $('#danger_alert').empty().hide();
        }
        //get subject
        var subject = $('#subject').val();
        if( subject.length==0 ){
            $('#danger_alert').empty().text('请填写邮件主题').show(500);
            return false;
        }
        $('#danger_alert').empty().hide();
        //get content
        var content = ue.getContent();
        if( content.length==0 ){
            $('#danger_alert').empty().text('请填写邮件内容').show(500);
            return false;
        }
        $('#danger_alert').empty().hide();
        //send request
        $.ajax({
            type: "POST",
            url:  "<?php echo site_url('user/send/doSendEmail'); ?>",
            data: {'type':type,'group_id':group_id,'uids':uids.toString(),'subject':subject,'content':content},
            dataType: "json",
            success: function(msg){
                if( msg.err_no==0 ){
                    $('#submit_btn').addClass('disabled');
                    $('#success_alert').empty().text('邮件群发任务已经全部添加到队列').show(500);
                } else {
                    $('#danger_alert').empty().text(msg.err_msg).show(500);
                }
            }
        });
        return false;
    });
});

</script>
