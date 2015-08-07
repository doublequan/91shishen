<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('enterprise/company'); ?>">企业管理</a></li>
            <li><a href="<?php echo site_url('enterprise/site'); ?>">网站</a></li>
            <li class="active">网站列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading" style="height:50px;">
                <a class="btn btn-primary btn-sm" id="add_form_btn" href="#add_form_page" kesrc="#add_form_page">添加网站信息</a>
            </div>
            <?php 
                if(!empty($results)){ 
            ?>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th width="10%">网站编号</th>
                        <th width="20%">网站名称</th>
                        <th width="15%">所在省份</th>
                        <th width="15%">所在城市</th>
                        <th width="30%">默认门店</th>
                        <th width="10%">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach( $results as $k=>$row ){ ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['prov']; ?></td>
                        <td><?php echo $row['city']; ?></td>
                        <td><?php echo $row['store']; ?></td>
                        <td row_id="<?php echo $row['id']; ?>" style="padding:6px;">
                            <a href="#add_form_page" kesrc="#add_form_page" class="btn btn-xs btn-success check_form_link">查看/编辑</a>
                            <a href="#" class="btn btn-xs btn-danger delete_link">删除</a>
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

<iframe src="" id="add_form_page" style="height:500px;width:1000px;"></iframe>
<script src="<?php echo base_url('static/js/jquery.fancybox.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
    var form_url = "<?php echo site_url('enterprise/site/add'); ?>";
    $('a#add_form_btn').click(function(){
        $('iframe#add_form_page').attr('src', form_url);
        $("a#add_form_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            'afterClose': function(){
                window.parent.location.reload();
            },
        });
    });
        
    $("a.check_form_link").click(function() {
        var id = $(this).parent('td').attr('row_id');
        if( id ){
            $('iframe#add_form_page').attr('src',"<?php echo site_url('enterprise/site/edit?id='); ?>"+id);
            $("a.check_form_link").fancybox({
                'hideOnContentClick': true,
                'padding':0,
                'afterClose': function(){
                    window.parent.location.reload();
                },
            });
        }
    });

    $(".delete_link").click(function(){
        var id = $(this).parent('td').attr('row_id');
        if( id ){
            $.get("<?php echo site_url('enterprise/site/delete'); ?>", {'id': id}, function(data){
                var data = $.parseJSON(data);
                if(data && data.error){
                    alert(data.msg);
                } else {
                    alert("删除成功！");
                }
                window.location.reload();
            });
        }
    });
});
</script>