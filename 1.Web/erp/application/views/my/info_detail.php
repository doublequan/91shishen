<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('system/system'); ?>">系统设置</a></li>
            <li class="active">个人信息</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel-body" style="padding-top:0px;">
            <div class="panel-heading" style="height:50px;">
                <a class="btn btn-primary btn-sm" id="add_form_btn" href="#add_form_page" kesrc="#add_form_page">编辑个人信息</a>
            </div>
            <table class="table table-striped table-bordered table-hover">
                <tbody>
                    <tr>
                        <th width="15%" class="text-right">用户姓名：</th>
                        <td width="35%"><?php echo $single['username']; ?></td>
                        <th width="15%" class="text-right">登录名：</th>
                        <td width="35%"><?php echo $single['account']; ?></td>
                    </tr>
                    <tr>
                        <th class="text-right">性别：</th>
                        <td><?php echo $single['gender']==1 ? '男': ($single['gender']==2 ? '女' : '未知'); ?></td>
                        <th class="text-right">身份证号：</th>
                        <td><?php echo $single['idcard']; ?></td>
                    </tr>
                    <tr>
                        <th class="text-right">手机号：</th>
                        <td><?php echo $single['mobile']; ?></td>
                        <th class="text-right">生日：</th>
                        <td><?php echo $single['birthday']; ?></td>
                    </tr>
                    <tr>
                        <th class="text-right">邮箱：</th>
                        <td><?php echo $single['email']; ?></td>
                        <th class="text-right">住址：</th>
                        <td><?php echo $single['address']; ?></td>
                    </tr>
                    <tr>
                        <th class="text-right">所属公司：</th>
                        <td><?php echo $company ? $company['name'] : '未知公司'; ?></td>
                        <th class="text-right">所属部门：</th>
                        <td><?php echo $department ? $department['name'] : '未知部门'; ?></td>
                    </tr>
                    <tr>
                        <th class="text-right">入职日期：</th>
                        <td><?php echo $single['hire_date']; ?></td>
                        <th class="text-right">合同到期日：</th>
                        <td><?php echo $single['expire_date']; ?></td>
                    </tr>
                    <tr>
                        <th class="text-right">下线邀请码</th>
                        <td><?php echo $single['invite_code']; ?></td>
                        <th class="text-right"></th>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<iframe src="" id="add_form_page" style="height:450px;width:1000px;"></iframe>
<script src="<?php echo base_url('static/js/jquery.fancybox.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function($) {
    $('a#add_form_btn').click(function(){
        $('iframe#add_form_page').attr('src', "<?php echo site_url('my/edit'); ?>");
        $("a#add_form_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            'afterClose': function(){
                window.parent.location.reload();
            },
        });
    });
});
</script>