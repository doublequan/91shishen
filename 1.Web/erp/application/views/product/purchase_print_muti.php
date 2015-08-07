<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>惠生活采购单</title>
    <style media="print" type="text/css">.noprint{display:none}</style>
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
    foreach ($purchase_list as $single_purchase_id => $single_purchase) { 
        $idx++;
        $single = $single_purchase['single'];
        $purchase_goods = $single_purchase['purchase_goods'];
        $purchase_products = $single_purchase['purchase_products'];
        $amount_arr = $single_purchase['amount_arr'];
    ?>
    <div class="container" <?php echo $idx==1?'':'style="page-break-before: always;"'; ?>>
        <div class="header">
            <div class="img"><img src="<?php echo base_url('static/images/logo.png'); ?>"></div>
            <div class="title">惠生活电子商务股份有限公司采购单 - <?php echo $single['id']; ?></div>
        </div>
        <div class="content">
            <div class="table-header">
                基本信息
            </div>
            <table class="table">
                <tbody>
                    <tr>
                        <th width="20%" class="text-right">采购单编号</th>
                        <td width="30%"><?php echo $single['id']; ?></td>
                        <th width="20%" class="text-right">采购单状态</th>
                        <td width="30%">
                            <?php 
                            switch($single['status']){
                                case 0:
                                    echo '新建采购单';
                                    break;
                                case 1:
                                    echo '已确认';
                                    break;
                                case 2:
                                    echo '采购中';
                                    break;
                                case 3:
                                    echo '采购已交货';
                                    break;
                                default:
                                    echo '结算完成';
                                    break;
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <tr>
                        <th width="20%" class="text-right">创建员工</th>
                        <td width="30%"><?php echo $single['create_name']; ?></td>
                        <th width="20%" class="text-right">创建时间</th>
                        <td width="30%"><?php echo empty($single['create_time'])?'':date('Y-m-d', strtotime($single['create_time'])); ?></td>
                    </tr>
                    <tr>
                        <th width="20%" class="text-right">采购类型</th>
                        <td width="30%">
                            <?php 
                            if($single['type']==1) 
                                echo '自主采购';
                            elseif($single['type']==2)
                                echo '供应商采购';
                            ?>
                        </td>
                        <th width="20%" class="text-right">供应商</th>
                        <td width="30%"><?php echo empty($supplier_info['sup_name'])?'':$supplier_info['sup_name']; ?></td>
                    </tr>
                    <tr>
                        <th width="20%" class="text-right">入库城市</th>
                        <td width="30%"><?php echo $single_purchase['store_prov'].' '.$single_purchase['store_city']; ?></td>
                        <th width="20%" class="text-right">入库门店</th>
                        <td width="30%"><?php echo $single_purchase['store_info']['name']; ?></td>
                    </tr>
                    <tr>
                        <th width="20%" class="text-right">支付方式</th>
                        <td width="30%">
                            <?php 
                            if($single['checkout_type']==1) 
                                echo '转账';
                            elseif($single['checkout_type']==2)
                                echo '现金';
                            elseif($single['checkout_type']==3)
                                echo '支票';
                            elseif($single['checkout_type']==0)
                                echo '其他';
                            ?>
                        </td>
                        <th width="20%" class="text-right">预借金额</th>
                        <td width="30%">
                            ￥<?php echo empty($single['price_borrow'])?'':$single['price_borrow']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th width="20%" class="text-right">运费金额</th>
                        <td width="30%">
                            ￥<?php echo empty($single['price_fee'])?'':$single['price_fee']; ?>
                        </td>
                        <th width="20%" class="text-right">实际金额</th>
                        <td width="30%">
                            ￥<?php echo empty($single['price_real'])?'':$single['price_real']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th width="20%" class="text-right">快递公司</th>
                        <td width="30%">
                            <?php echo empty($single['express_id'])?'':$single['express_id']; ?>
                        </td>
                        <th width="20%" class="text-right">物流编号</th>
                        <td width="30%">
                            <?php echo $single['express_no']; ?>
                        </td>
                    </tr>
                    <?php if($single['express_id']==1) { ?>
                    <tr>
                        <th width="20%" class="text-right">发票抬头</th>
                        <td width="30%">
                            <?php echo $single['invoice_title']; ?>
                        </td>
                        <th width="20%" class="text-right">发票类型</th>
                        <td width="30%">
                            <?php echo $single['invoice_content']; ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <?php 
            $good_price_total = 0;
            if(is_array($purchase_goods) && count($purchase_goods) > 0){ 
            ?>
            <div class="table-header">
                原料列表
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="5%">序号</th>
                        <th width="25%">原料名称</th>
                        <th width="10%">规格</th>
                        <th width="10%">计价方式</th>
                        <th width="10%">计价单位</th>
                        <th width="10%">单位计价量</th>
                        <th width="10%">采购价</th>
                        <th width="10%">调度数量</th>
                        <th width="10%">小计</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($purchase_goods as $k=>$row) {
                            $single_good_price = sprintf('%.2f', $row['price'] * $amount_arr['good'][$row['id']]);
                            $good_price_total += $single_good_price;
                    ?>
                    <tr>
                        <td><?php echo ($k+1); ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['spec']; ?></td>
                        <td><?php echo isset($good_method_types[$row['method']]) ? $good_method_types[$row['method']] : ''; ?></td>
                        <td><?php echo $row['unit']; ?></td>
                        <td><?php echo $row['amount']; ?></td>
                        <td>￥<?php echo $row['price']; ?></td>
                        <td><?php echo $amount_arr['good'][$row['id']]; ?></td>
                        <td>￥<?php echo $single_good_price; ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="8" style="text-align:right;"><font color="red"><?php echo count($purchase_goods); ?></font> 件原料，总金额：</td>
                        <td colspan="1">￥<?php echo sprintf('%.2f', $good_price_total); ?></td>
                    </tr>
                </tbody>
            </table>
            <?php } ?>

            <?php 
            $product_price_total = 0;
            if(count($purchase_products) > 0){ 
            ?>
            <div class="table-header">
                商品列表
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="5%">序号</th>
                        <th width="15%">商品编号</th>
                        <th width="10%">商品货号</th>
                        <th width="20%">商品名称</th>
                        <th width="12%">所属分类</th>
                        <th width="8%">规格</th>
                        <th width="10%">销售价</th>
                        <th width="10%">采购数量</th>
                        <th width="10%">小计</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach ($purchase_products as $k=>$row) {
                            $single_product_price = sprintf('%.2f', $row['price'] * $amount_arr['product'][$row['id']]);
                            $product_price_total += $single_product_price;
                    ?>
                    <tr>
                        <td><?php echo ($k+1); ?></td>
                        <td><?php echo $row['sku']; ?></td>
                        <td><?php echo $row['product_pin']; ?></td>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo isset($categorys[$row['category_id']]) ? $categorys[$row['category_id']]['name'] : ''; ?></td>
                        <td><?php echo $row['spec']; ?></td>
                        <td>￥<?php echo $row['price']; ?></td>
                        <td><?php echo $amount_arr['product'][$row['id']]; ?></td>
                        <td>￥<?php echo $single_product_price; ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="8" style="text-align:right;"><font color="red"><?php echo count($purchase_products); ?></font> 件原料，总金额：</td>
                        <td colspan="1">￥<?php echo sprintf('%.2f', $product_price_total); ?></td>
                    </tr>
                </tbody>
            </table>
            <?php } ?>

            <div class="table-header">
                采购总价
            </div>
            <table class="table">
                <tbody>
                    <tr>
                        <th width="88%" style="text-align:right;">总金额：</th>
                        <td width="12%">￥<?php echo sprintf('%.2f', $good_price_total + $product_price_total); ?></td>
                    </tr>
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
