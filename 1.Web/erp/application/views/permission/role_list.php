<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('enterprise/company'); ?>">企业管理</a></li>
            <li><a href="<?php echo site_url('permission/permission'); ?>">权限管理</a></li>
            <li class="active">角色列表</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading" style="height:50px;">
                <a class="btn btn-primary btn-sm" id="add_form_btn" href="<?php echo site_url('permission/role/add'); ?>">添加角色</a>
            </div>
            <?php  if(!empty($results)){ ?>
            <div class="panel-body">
                <table class="table table-striped table-hover text-center" style="margin-bottom:0;">
                    <thead>
                        <tr>
                            <th width="5%">角色编号</th>
                            <th width="25%">角色名称</th>
                            <th width="50%">权限数量</th>
                            <th width="20%">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach( $results as $row ){ ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo count(json_decode($row['permissions'],true)); ?></td>
                            <td row_id="<?php echo $row['id']; ?>" style="padding:6px;">
                                <a href="<?php echo site_url('permission/role/edit?id='.$row['id']); ?>" class="btn btn-xs btn-success">查看/编辑</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php }else{ ?>
            <div class="alert alert-warning col-md-12 text-center" role="alert">查询结果为空！</div>
            <?php } ?>
        </div>
    </div>
</div>