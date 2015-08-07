<link href="<?php echo base_url('static/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('static/css/base.css'); ?>" rel="stylesheet" type="text/css">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 form_header bg-primary">
            <h4>
                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                大客户定制商品信息
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form_body">
            <input type="hidden" name="id" value="<?php echo $single['id']; ?>">
            <div class="form-horizontal">
                <div class="form-group">
                    <label for="" class="col-md-2 control-label form_label">
                        <span><font color="red">*</font></span>
                        大客户公司
                    </label>
                    <div class="col-md-4 form_input">
                        <p class="form-control-static input-sm"><?php echo $single['company_name']; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-md-2 control-label form_label">
                        <span><font color="red">*</font></span>
                        大客户用户
                    </label>
                    <div class="col-md-4 form_input">
                        <p class="form-control-static input-sm"><?php echo $single['user_name']; ?></p>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <b>需求商品信息</b>
                        </div>
                        <table class="table table-striped table-hover table-bordered text-center" style="font-size:14px;" id="info_table">
                            <thead>
                                <tr>
                                    <th style="width:5%;">
                                        #
                                    </th>
                                    <th style="width:30%;">
                                        商品名称
                                    </th>
                                    <th style="width:15%;">
                                        数量
                                    </th>
                                    <th style="width:15%;">
                                        单位
                                    </th>
                                    <th style="width:35%;">
                                        备注(特殊要求)
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($single['detail'] as $idx => $value) { ?>
                                <tr>
                                    <td>
                                        <?php echo ++$idx; ?>
                                    </td>
                                    <td>
                                        <?php echo $value['name']; ?>
                                    </td>
                                    <td>
                                        <?php echo $value['amount']; ?>
                                    </td>
                                    <td>
                                        <?php echo $value['unit']; ?>
                                    </td>
                                    <td>
                                        <?php echo $value['note']; ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-2 col-sm-offset-5 text-center">
                            <input type="button" class="btn btn-default btn-block" onclick="javascript:history.back(-1);" value="返回上一页">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

