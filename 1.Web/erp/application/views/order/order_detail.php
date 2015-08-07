<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb" style="margin-bottom:0;">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('order/order'); ?>">订单管理</a></li>
            <li><a href="<?php echo site_url('order/order?status='.$single['order_status']); ?>"><?php echo isset($order_status_types[$single['order_status']]) ? $order_status_types[$single['order_status']] : '订单'; ?>列表</a></li>
            <li class="active">订单详情，订单编号：<?php echo $single['id']; ?></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12 form_body" style="padding-top:0px;">
        <div class="panel-body">
            <ul class="nav nav-tabs" role="tablist">
                <li class="active"><a href="#tab_content_0" role="tab" data-toggle="tab">订单基础信息</a></li>
                <li><a href="#tab_content_1" role="tab" data-toggle="tab">订单财务信息</a></li>
                <li><a href="#tab_content_2" role="tab" data-toggle="tab">订单操作历史</a></li>
            </ul>
            <?php if( !($single['order_status']==10 || $single['order_status']==11) ) { ?>
            <div class="panel-heading form-inline" style="padding-bottom:0px;padding-left:0px;">
                操作：
            <!--货到付款-->
            <?php if( $single['order_status']<10 && ($single['pay_type']==1 || ($single['pay_type']>1 && $single['pay_status']==1)) ){ ?>
                <div class="form-group">          
                    <a class="btn btn-info btn-sm dealOrder" status="21">确认订单</a> 
                </div>
            <?php } elseif( $single['order_status']==21 ) { ?>
                <div class="form-group">
                <?php if( $single['deal_store_id'] ) { ?>
                    <a class="btn btn-info btn-sm dealOrder" status="27">订单出库</a>
                <?php } else { ?>
                    <a class="btn btn-default btn-sm" onclick="javascript:alert('未设置出库仓库，不能出库');">未设置出库仓库，不能出库</a>
                <?php } ?>
                </div>
            <?php } elseif( $single['order_status']==27 ) { ?>
                <div class="form-group">          
                    <a class="btn btn-info btn-sm dealOrder" status="20">完成订单</a> 
                </div>
            <?php } ?>
                <?php if( $single['order_status']<27 ) { ?>
                <div class="form-group">
                    <a class="btn btn-warning btn-sm dealOrder" status="11">取消订单</a>
                </div>
                <?php } ?>
                <div class="form-group">
                    <a class="btn btn-danger btn-sm dealOrder" status="10">删除订单</a>
                </div>
            <?php if( $single['order_status']<10 && $single['pay_status']==0 ){ ?>
                <div class="form-group text-right" style="float:right;">
                    手工指定减免金额：
                    ￥<input type="text" id="price_discount" class="form-control text-center" style="width:80px;" value="0">   
                    <a class="btn btn-info btn-sm" id="doDiscount">确认减免</a> 
                </div>
            <?php } ?>
            </div>
            <?php } ?>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_content_0">
                    <p class="bg-info form-square-title">订单出门店</p>
                    <table class="table table-bordered table-hover table-center text-center">
                        <tbody>
                            <tr>
                                <th width="11%" class="text-right">已设置仓库</th>
                                <td width="39%" style="text-align:left;padding-left:20px;">
                                    <?php if( $single['deal_store_id'] && $deal_store ){ ?>
                                        <?php echo $deal_store['prov']; ?> -
                                        <?php echo $deal_store['city']; ?> -
                                        <?php echo $deal_store['district']; ?> -
                                        <?php echo $deal_store['name']; ?>
                                    <?php } else { ?>
                                    暂无
                                    <?php } ?>
                                </td>
                                <th width="11%" class="text-right" style="vertical-align: middle;">新设置仓库</th>
                                <td width="39%" style="text-align:left;padding-left:20px;">
                                    <select id="deal_store_id" class="form-control input-sm" style="float:left;width:150px;">
                                        <?php if( $deal_stores ) { ?>
                                        <?php foreach( $deal_stores as $row ) { ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <a class="btn btn-info btn-sm" id="doSetDealStore" style="float:left;margin-left:10px;">分配到此仓库</a> 
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="bg-info form-square-title">订单基础信息</p>
                    <table class="table table-bordered table-hover">
                        <tbody>
                            <tr>
                                <th width="11%" class="text-right">订单编号</th>
                                <td width="22%"><?php echo $single['id']; ?></td>
                                <th width="11%" class="text-right">订单状态</th>
                                <td width="22%"><?php echo isset($order_status_types[$single['order_status']]) ? $order_status_types[$single['order_status']] : '未知订单状态'; ?></td>
                                <th width="11%" class="text-right">下单时间</th>
                                <td width="23%"><?php echo date('Y-m-d H:i:s',$single['create_time']); ?></td>
                            </tr>
                            <tr>
                                <th class="text-right">订单总价</th>
                                <td>￥<?php echo $single['price']; ?></td>
                                <th class="text-right">支付方式</th>
                                <td><?php echo isset($pay_types[$single['pay_type']]) ? $pay_types[$single['pay_type']] : ''; ?></td>
                                <th class="text-right">支付状态</th>
                                <td><?php echo isset($pay_status_types[$single['pay_status']]) ? $pay_status_types[$single['pay_status']] : ''; ?></td>
                            </tr>
                            <tr>
                                <th class="text-right">配送时间类型</th>
                                <td><?php echo isset($order_date_types[$single['date_type']]) ? $order_date_types[$single['date_type']] : '未知类型'; ?></td>
                                <th class="text-right">预估配送日期</th>
                                <td><?php echo $single['date_day']; ?></td>
                                <th class="text-right">配送日期详情</th>
                                <td><?php echo $single['date_noon']==1 ? '上午' : ($single['date_noon']==2 ? '下午' : '上下午均可'); ?></td>
                            </tr>
                            <tr>
                                <th class="text-right">订单备注</th>
                                <td colspan="5"><?php echo $single['note']; ?></td>
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
                    <p class="bg-info form-square-title">订单物流信息</p>
                    <table class="table table-bordered table-hover table-center text-center">
                        <tbody>
                            <?php if( $single['delivery_type']==0 && $store ){ ?>
                            <tr>
                                <th width="11%" class="text-right">物流类型</th>
                                <td width="22%">用户自提</td>
                                <th width="11%" class="text-right">门店名称</th>
                                <td width="22%"><?php echo $store['name']; ?></td>
                                <th width="11%" class="text-right">门店营业时间</th>
                                <td width="23%"><?php echo $store['open_time']; ?></td>
                            </tr>
                            <tr>
                                <th class="text-right">所在省份</th>
                                <td><?php echo $store['prov']; ?></td>
                                <th class="text-right">所在城市</th>
                                <td><?php echo $store['city']; ?></td>
                                <th class="text-right">所在区域</th>
                                <td><?php echo $store['district']; ?></td>
                            </tr>
                            <tr>
                                <th class="text-right">详细地址</th>
                                <td colspan="3"><?php echo $store['address']; ?></td>
                                <th class="text-right">联系电话</th>
                                <td><?php echo $store['tel']; ?></td>
                            </tr>
                            <?php } else { ?>
                            <tr>
                                <th width="11%" class="text-right">物流类型</th>
                                <td width="22%">惠生活物流</td>
                                <th width="11%" class="text-right">收货人</th>
                                <td width="22%"><?php echo $single['receiver']; ?></td>
                                <th width="11%" class="text-right">联系电话</th>
                                <td width="23%"><?php echo $single['mobile'].'&nbsp;&nbsp;'.$single['tel']; ?></td>
                            </tr>
                            <tr>
                                <th class="text-right">省、市</th>
                                <td><?php echo $single['prov']; ?> - <?php echo $single['city']; ?></td>
                                <th class="text-right">区/县</th>
                                <td><?php echo $single['district']; ?></td>
                                <th class="text-right">街道</th>
                                <td><?php echo $single['street']; ?></td>
                            </tr>
                            <tr>
                                <th class="text-right">详细地址</th>
                                <td colspan="3"><?php echo $single['address']; ?></td>
                                <th class="text-right">邮政编码</th>
                                <td><?php echo $single['zip']; ?></td>
                            </tr>
                          
                            <tr>
                                <th class="text-right" style="vertical-align: middle;">设置配送点</th>
                                <td colspan="5" style="text-align:left;padding-left:20px;">
                                    <label style="float:left;padding:7px 7px 0 0;"><?php echo $label; ?>可用配送点：</label>
                                    <select id="store_id" class="form-control input-sm" style="float:left;width:150px;">
                                        <?php if( $stores ) { ?>
                                        <?php foreach( $stores as $row ) { ?>
                                        <option value="<?php echo $row['id']; ?>"<?php echo $row['id']==$single['store_id'] ? ' selected' : ''; ?>><?php echo $row['name']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <a class="btn btn-info btn-sm" id="doSetStore" style="float:left;margin-left:10px;">分配到此门店</a> 
                                </td>
                            </tr>
                            
                            <?php if( isset($store) && $store ){ ?>
                            <tr>
                                <th class="text-right">已设置配送点</th>
                                <td colspan="5" style="text-align:left;padding-left:20px;">
                                    <?php echo $store['prov']; ?> -
                                    <?php echo $store['city']; ?> -
                                    <?php echo $store['district']; ?> -
                                    <?php echo $store['name']; ?>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="tab_content_1">
                    <p class="bg-info form-square-title">支付和发票信息</p>
                    <table class="table table-bordered table-hover">
                        <tbody>
                            <tr>
                                <th width="11%" class="text-right">订单总价</th>
                                <td width="22%">￥<?php echo $single['price']; ?></td>
                                <th width="11%" class="text-right">支付方式</th>
                                <td width="22%"><?php echo isset($pay_types[$single['pay_type']]) ? $pay_types[$single['pay_type']] : '未知支付方式'; ?></td>
                                <th width="11%" class="text-right">支付状态</th>
                                <td width="23%"><?php echo isset($pay_status_types[$single['pay_status']]) ? $pay_status_types[$single['pay_status']] : '未知支付状态'; ?></td>
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
                    <p class="bg-info form-square-title">订单价格信息</p>
                    <table class="table table-bordered table-hover">
                        <tbody>
                            <tr>
                                <th width="11%" class="text-right">产品总金额</th>
                                <td width="22%">￥<?php echo $single['price_total']; ?></td>
                                <th width="11%" class="text-right">用户实付金额</th>
                                <td width="22%">￥<?php echo $single['price_pay']; ?></td>
                                <th width="11%" class="text-right">运费</th>
                                <td width="23%">￥<?php echo $single['price_shipping']; ?></td>
                            </tr>
                            <tr>
                                <th class="text-right">折扣金额</th>
                                <td>￥<?php echo $single['price_discount']; ?></td>
                                <th class="text-right">代金券减免金额</th>
                                <td>￥<?php echo $single['price_cash']; ?></td>
                                <th class="text-right">优惠减免金额</th>
                                <td>￥<?php echo $single['price_minus']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="tab_content_2">
                    <table class="table table-bordered table-hover table-center text-center">
                        <thead>
                            <tr>
                                <th width="5%">序号</th>
                                <th width="30%">当前状态</th>
                                <th width="30%">描述</th>
                                <th width="10%">是否对用户展示</th>
                                <th width="10%">操作人</th>
                                <th width="15%">操作时间</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if( $actions ){ ?>
                            <?php foreach ($actions as $k=>$row) { ?>
                            <tr>
                                <td><?php echo ($k+1); ?></td>
                                <td><?php echo isset($order_status_types[$row['status']]) ? $order_status_types[$row['status']] : '未知订单状态'; ?></td>
                                <td><?php echo $row['des']; ?></td>
                                <td>
                                    <?php if( $row['is_show'] ){ ?>
                                    <span class="label label-primary">是</span>
                                    <?php } else { ?>
                                    <span class="label label-default">否</span>
                                    <?php } ?>
                                </td>
                                <td><?php echo $row['create_name']; ?></td>
                                <td><?php echo date('Y-m-d H:i:s',$row['create_time']); ?></td>
                            </tr>
                            <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function($) {
    var order_id = '<?php echo $single["id"]; ?>';

    $('.dealOrder').click(function(){
        var status = $(this).attr('status');
        if( confirm('请确认分配仓库和配送点，确定您的操作？') ){
            $.post("<?php echo site_url('order/order/doAction'); ?>", {'order_id':order_id,'status':status}, function(data){
                var data = $.parseJSON(data);
                if(data && data.err_no==0){
                    alert('操作成功！');
                    window.location.reload();
                } else {
                    alert('操作失败，'+data.err_msg+'！');
                }
            });
        }
    });

    $('#doDiscount').click(function(){
        var discount = parseFloat($('#price_discount').val());
        if( isNaN(discount) ){
            alert('请输入合法的金额');
            $('#price_discount').focus();
            return false;
        }
        if( discount<=0 ){
            alert('请输入大于0的数字');
            $('#price_discount').focus();
            return false;
        }
        if( discount><?php echo $single['price']; ?> ){
            alert('减去的金额不能大于订单总金额');
            $('#price_discount').focus();
            return false;
        }
        if( confirm('确定为此订单手工减去'+discount+'元？') ){
            $.post("<?php echo site_url('order/order/doDiscount'); ?>", {'order_id':order_id,'discount':discount}, function(data){
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

    $('#doSetDealStore').click(function(){
        var deal_store_id = parseInt($('#deal_store_id').val());
        var name = $('#deal_store_id option:selected').text();
        if( isNaN(deal_store_id) ){
            alert('请选择正确的仓库');
            return false;
        }
        if( confirm('确定将此订单从'+name+'出库？') ){
            $.post("<?php echo site_url('order/order/doSetDealStore'); ?>", {'order_id':order_id,'deal_store_id':deal_store_id}, function(data){
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

    $('#doSetStore').click(function(){
        var store_id = parseInt($('#store_id').val());
        var name = $('#store_id option:selected').text();
        if( isNaN(store_id) ){
            alert('请选择正确的门店');
            return false;
        }
        if( confirm('确定将此订单分配到'+name+'？') ){
            $.post("<?php echo site_url('order/order/doSetStore'); ?>", {'order_id':order_id,'store_id':store_id}, function(data){
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
