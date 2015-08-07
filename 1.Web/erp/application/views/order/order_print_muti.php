<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>惠生活配送单</title>
    <style media="print" type="text/css">
    .noprint{display:none}
    </style>
    <style media="screen,print" type="text/css">
        * {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        body{
            font:16px/1.5 "宋体", Helvetica, Arial, sans-serif;
            color:#404040;
            background-color:#fff;
            text-align:center;
        }
        .container{
            width:1100px;
            margin:20px auto;
        }
        .header{
            text-align: left;
            margin-bottom: 10px;
            vertical-align: top;
            position: relative;
        }
        .header>div{
            display: inline-block;
        }
        .header .img img{
            height: 50px;
        } 
        .header .title{
            font-size: 24px;
            position: relative;
            bottom: 6px;
        }
        .content{
            padding-bottom: 30px;
        }
        .table-header{
            height: 32px;
            line-height: 32px;
            background-color: #ececec;
            font-size: 14px;
            text-align: left;
            padding-left: 20px;
            font-weight: bold;
        }
        .table {
            border-collapse: collapse!important;
            border: 1px solid #ddd;
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
            background-color: #fff
        }
        .table>thead>tr>th,.table>tbody>tr>th,.table>tfoot>tr>th,.table>thead>tr>td,.table>tbody>tr>td,.table>tfoot>tr>td {
            padding: 8px;
            line-height: 1.42857143;
            vertical-align: top;
            border: 1px solid #ddd;
        }
        .table-striped>tbody>tr:nth-child(odd)>td,.table-striped>tbody>tr:nth-child(odd)>th {
            background-color: #f7f7f7;
        }
        .btn-line{
            line-height: 30px;
            height: 30px;
            margin-bottom: 40px;
        }
        .btn_print{
            width: 100px;
            height: 30px;
        }
    </style>
</head>
<body>
    <?php 
    $idx = 0;
    foreach ($order_list as $single_order_id => $single_order) { 
        $idx++;
        $single = $single_order['single'];
        $details = $single_order['details'];
        $store = $single_order['store'];
        $current_user = $single_order['current_user'];
    ?>
    <div class="container" <?php echo $idx==1?'':'style="page-break-before: always;"'; ?>>
        <div class="header">
            <div class="img"><img src="<?php echo base_url('static/images/logo.png'); ?>"></div>
            <div class="title">惠生活电子商务股份有限公司配送单 - <?php echo $single_order_id; ?></div>
        </div>
        <div class="content">
            <div class="table-header">
                订单物流信息
            </div>
            <table class="table">
                <tbody>
                    <tr>
                        <th width="11%" class="text-right">订单编号</th>
                        <td width="22%"><?php echo $single['id']; ?></td>
                        <th width="11%" class="text-right">下单用户</th>
                        <td width="22%"><?php echo $current_user['username']; ?></td>
                        <th width="11%" class="text-right">下单时间</th>
                        <td width="23%"><?php echo $single['create_time'] ? date('Y-m-d H:i:s',$single['create_time']) : ''; ?></td>
                    </tr>
                    <tr>
                        <th class="text-right">物流类型</th>
                        <td><?php echo $single['delivery_type']==0 ? '用户自提' : '惠生活物流'; ?></td>
                        <th class="text-right">收货人</th>
                        <td><?php echo $single['receiver']; ?></td>
                        <th class="text-right">联系电话</th>
                        <td><?php echo $single['mobile'].'&nbsp;&nbsp;'.$single['tel']; ?></td>
                    </tr>
                    <?php if( $single['delivery_type']==1 ){ ?>
                    <tr>
                        <th class="text-right">省市地区</th>
                        <td><?php echo $single['prov'].' '.$single['city'].' '.$single['district']; ?></td>
                        <th class="text-right">详细地址</th>
                        <td colspan="3"><?php echo $single['address']; ?></td>
                    </tr>
                    <?php } ?>
                    <?php if( $store ){ ?>
                    <tr>
                        <th class="text-right">门店名称</th>
                        <td><?php echo $store['name']; ?></td>
                        <th class="text-right">所在地区</th>
                        <td><?php echo $store['prov'].' '.$store['city'].' '.$store['district']; ?></td>
                        <th class="text-right">营业时间</th>
                        <td><?php echo $store['open_time']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <div class="table-header">
                订单支付和发票信息
            </div>
            <table class="table">
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
                        <th class="text-right">商品总金额</th>
                        <td>￥<?php echo $single['price_total']; ?></td>
                        <th class="text-right">运费</th>
                        <td>￥<?php echo $single['price_shipping']; ?></td>
                        <th class="text-right">下单时间</th>
                        <td><?php echo date('Y-m-d H:i:s',$single['create_time']); ?></td>
                    </tr>
                    <tr>
                        <th class="text-right">是否开发票</th>
                        <td><?php echo $single['is_receipt'] ? '是' : '否'; ?></td>
                        <th class="text-right">发票抬头</th>
                        <td><?php echo $single['receipt_title']; ?></td>
                        <th class="text-right">发票类型</th>
                        <td><?php echo $single['receipt_des']; ?></td>
                    </tr>
                    <tr>
                        <th class="text-right">订单备注</th>
                        <td colspan="5"><?php echo $single['note']; ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="table-header">
                订单商品信息
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="10%">编号</th>
                        <th width="5%">货号</th>
                        <th width="30%">商品名称</th>
                        <th width="10%">规格</th>
                        <th width="10%">单位</th>
                        <th width="10%">包装规格</th>
                        <th width="5%">数量</th>
                        <th width="10%">单价</th>
                        <th width="10%">小计</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if( $details ){ ?>
                    <?php foreach ($details as $k=>$row) { ?>
                    <tr>
                        <td><?php echo $row['sku']; ?></td>
                        <td><?php echo $row['product_pin']; ?></td>
                        <td><?php echo $row['product_name']; ?></td>
                        <td><?php echo $row['spec']; ?></td>
                        <td><?php echo $row['unit']; ?></td>
                        <td><?php echo $row['spec_packing']; ?></td>
                        <td><?php echo $row['amount']; ?></td>
                        <td>￥<?php echo $row['price_single']; ?></td>
                        <td>￥<?php echo $row['price_total']; ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="8" style="text-align:right;"><font color="red"><?php echo count($details); ?></font> 件商品，总商品金额：</td>
                        <td colspan="1">￥<?php echo $single['price_total']; ?></td>
                    </tr>
                    <tr>
                        <td colspan="8" style="text-align:right;">折扣金额：</td>
                        <td colspan="1">-￥<?php echo $single['price_discount']; ?></td>
                    </tr>
                    <tr>
                        <td colspan="8" style="text-align:right;">代金券减免金额：</td>
                        <td colspan="1">-￥<?php echo $single['price_cash']; ?></td>
                    </tr>
                    <tr>
                        <td colspan="8" style="text-align:right;">优惠减免金额：</td>
                        <td colspan="1">-￥<?php echo $single['price_minus']; ?></td>
                    </tr>
                    <tr>
                        <td colspan="8" style="text-align:right;">运费：</td>
                        <td colspan="1">+￥<?php echo $single['price_shipping']; ?></td>
                    </tr>
                    <tr>
                        <!-- <td>大写：</td><td align="left" colspan="4"><span>贰拾陆圆参角</span>整</td> -->
                        <td colspan="8" style="text-align:right;">订单应支付金额：</td>
                        <td>￥<span><?php echo $single['price']; ?></span>元</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
    <?php } ?>
    <div class="btn-line">
        <input class="btn_print noprint" type="submit" onclick="window.print();" value="打 印">
    </div>
</body>
</html>
