<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('vip/product'); ?>">大客户管理</a></li>
            <li><a href="<?php echo site_url('vip/order'); ?>">大客户订单</a></li>
            <li class="active">大客户订单详情</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12 form_body">
        <div class="panel-heading form-inline" style="padding-top:0;">
            <div class="form-group">
                订单状态：<?php echo isset($statusMap[$single['order_status']]) ? $statusMap[$single['order_status']] : '未知订单状态'; ?>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <?php if( $single['order_status']==0 ){ ?>
                <a class="btn btn-warning btn-sm dealOrder" status="21">确认订单</a> 
                <?php } else if( $single['order_status']==21 ){ ?>
                <a class="btn btn-info btn-sm dealOrder" status="22">订单采购</a> 
                <?php } else if( $single['order_status']==22 ){ ?>
                <a class="btn btn-success btn-sm dealOrder" status="20">订单完成</a>  
                <?php } ?>
                <?php if( $single['order_status']!=20 ){ ?>
                <a class="btn btn-danger btn-sm dealOrder" status="10">删除订单</a>
                <?php } ?>
            </div>
        </div>
        <p class="bg-info form-square-title">订单基础信息</p>
        <table class="table table-bordered table-hover">
            <tbody>
                <tr>
                    <th width="11%" class="text-right">订单编号</th>
                    <td width="22%"><?php echo $single['id']; ?></td>
                    <th width="11%" class="text-right">订单总价</th>
                    <td width="22%">￥<?php echo $single['price']; ?></td>
                    <th width="11%" class="text-right">配送时间</th>
                    <td width="23%"><?php echo date('Y-m-d H:i:s',$single['delivery_time']); ?></td>
                </tr>
                <tr>
                    <th class="text-right">省份</th>
                    <td><?php echo $single['prov']; ?></td>
                    <th class="text-right">城市</th>
                    <td><?php echo $single['city']; ?></td>
                    <th class="text-right">区县</th>
                    <td><?php echo $single['district']; ?></td>
                </tr>
                <tr>
                    <th class="text-right">详细地址</th>
                    <td colspan="3"><?php echo $single['address']; ?></td>
                    <th class="text-right">邮编</th>
                    <td><?php echo $single['zip']; ?></td>
                </tr>
                <tr>
                    <th class="text-right">收货人</th>
                    <td><?php echo $single['receiver']; ?></td>
                    <th class="text-right">手机号码</th>
                    <td><?php echo $single['mobile']; ?></td>
                    <th class="text-right">座机号码</th>
                    <td><?php echo $single['tel']; ?></td>
                </tr>
                <tr>
                    <th class="text-right">是否开发票</th>
                    <td><?php echo $single['is_receipt'] ? '是' : '否'; ?></td>
                    <th class="text-right">发票抬头</th>
                    <td><?php echo $single['receipt_title']; ?></td>
                    <th class="text-right">发票类型</th>
                    <td><?php echo $single['receipt_des']; ?></td>
                </tr>
            </tbody>
        </table>
        <p class="bg-info form-square-title">订单产品详情</p>
        <table class="table table-bordered table-hover table-center text-center">
            <thead>
                <tr>
                    <th width="5%">序号</th>
                    <th width="8%">产品编号</th>
                    <th width="35%">产品名称</th>
                    <th width="8%">规格</th>
                    <th width="8%">单位</th>
                    <th width="8%">包装规格</th>
                    <th width="8%">数量</th>
                    <th width="10%">单价</th>
                    <th width="10%">总价</th>
                </tr>
            </thead>
            <tbody>
                <?php if( $details ){ ?>
                <?php foreach ($details as $k=>$row) { ?>
                <tr>
                    <td><?php echo ($k+1); ?></td>
                    <td><?php echo $row['product_no']; ?></td>
                    <td><?php echo $row['product_name']; ?></td>
                    <td><?php echo $row['spec']; ?></td>
                    <td><?php echo $row['unit']; ?></td>
                    <td><?php echo $row['spec_packing']; ?></td>
                    <td><?php echo $row['amount']; ?></td>
                    <td>￥<?php echo $row['price_single']; ?></td>
                    <td>￥<?php echo $row['price_total']; ?></td>
                </tr>
                <?php } ?>
                <?php } ?>
            </tbody>
        </table>
        <?php if( $vip_industry && $vip_company ){ ?>
        <p class="bg-info form-square-title">大客户公司信息</p>
        <table class="table table-bordered table-hover table-center text-center">
            <tbody>
                <tr>
                    <th width="11%" class="text-right">所属行业</th>
                    <td width="22%"><?php echo $vip_industry['name']; ?></td>
                    <th width="11%" class="text-right">公司名称</th>
                    <td width="22%"><?php echo $vip_company['name']; ?></td>
                    <th width="11%" class="text-right">公司规模</th>
                    <td width="23%"><?php echo $vip_company['scale']; ?></td>
                </tr>
                <tr>
                    <th class="text-right">所在省份</th>
                    <td><?php echo $vip_company['prov']; ?></td>
                    <th class="text-right">所在城市</th>
                    <td><?php echo $vip_company['city']; ?></td>
                    <th class="text-right">联系电话</th>
                    <td><?php echo $vip_company['tel']; ?></td>
                </tr>
                <tr>
                    <th class="text-right">详细地址</th>
                    <td colspan="5"><?php echo $vip_company['address']; ?></td>
                </tr>
            </tbody>
        </table>
        <?php } ?>
        <?php if( $vip_user ){ ?>
        <p class="bg-info form-square-title">大客户用户信息</p>
        <table class="table table-bordered table-hover table-center text-center">
            <tbody>
                <tr>
                    <th width="11%" class="text-right">用户名</th>
                    <td width="22%"><?php echo $vip_user['username']; ?></td>
                    <th width="11%" class="text-right">手机号码</th>
                    <td width="22%"><?php echo $vip_user['mobile']; ?></td>
                    <th width="11%" class="text-right">折扣率</th>
                    <td width="23%"><?php echo $vip_user['discount'].'%'; ?></td>
                </tr>
                <tr>
                    <th class="text-right">职务</th>
                    <td><?php echo $vip_user['position']; ?></td>
                    <th class="text-right">最后登录时间</th>
                    <td><?php echo date('Y-m-d H:i:s',$vip_user['login_time']); ?></td>
                    <th class="text-right">最后登录IP</th>
                    <td><?php echo long2ip($vip_user['login_ip']); ?></td>
                </tr>
                <tr>
                    <th class="text-right">负责员工</th>
                    <td><?php echo $vip_user['charge_name']; ?></td>
                    <th class="text-right">招商员工</th>
                    <td><?php echo $vip_user['deal_name']; ?></td>
                    <th class="text-right">录入员工</th>
                    <td><?php echo $vip_user['create_name']; ?></td>
                </tr>
            </tbody>
        </table>
        <?php } ?>
        <a class="btn btn-default btn-block" href="<?php echo site_url('vip/order'); ?>">返回大客户订单列表</a>      
    </div>
</div>

<script type="text/javascript">
$(document).ready(function($) {
    $('.dealOrder').click(function(){
        var order_id = '<?php echo $single["id"]; ?>';
        var status = $(this).attr('status');
        if( confirm('确定您的操作？') ){
            $.post("<?php echo site_url('vip/order/doAction'); ?>", {'order_id':order_id,'status':status}, function(data){
                var data = $.parseJSON(data);
                if(data && data.err_no==0){
                    alert('操作成功！');
                    window.location.reload();
                } else {
                    alert('操作失败！');
                }
            });
        }
    });
});
</script>
