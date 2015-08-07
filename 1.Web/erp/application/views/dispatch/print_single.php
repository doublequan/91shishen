<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>惠生活调度单：<?php echo $single['id']; ?></title>
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
        }
        .btn_print{
            width: 100px;
            height: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="img"><img src="<?php echo base_url('static/images/logo.png'); ?>"></div>
            <div class="title">惠生活电子商务股份有限公司调度单 - <?php echo $single['id']; ?></div>
        </div>
        <div class="content">
            <div class="table-header">
                基本信息
            </div>
            <table class="table">
                <tbody>
                    <tr>
                        <th width="20%" class="text-right">调度单编号</th>
                        <td width="30%"><?php echo $single['id']; ?></td>
                        <th width="20%" class="text-right">调度单状态</th>
                        <td width="30%"><?php echo isset($statusMap[$single['status']]) ? $statusMap[$single['status']] : ''; ?></td>
                    </tr>
                    <tr>
                        <th width="20%" class="text-right">调出城市</th>
                        <td width="30%">
                            <?php echo isset($areas[$out_store_info['prov']]) ? $areas[$out_store_info['prov']]['name'] : ''; ?>
                            <?php echo isset($areas[$out_store_info['city']]) ? $areas[$out_store_info['city']]['name'] : ''; ?>
                        </td>
                        <th width="20%" class="text-right">调出门店</th>
                        <td width="30%"><?php echo $out_store_info['name']; ?></td>
                    </tr>
                    <tr>
                        <th width="20%" class="text-right">入库城市</th>
                        <td width="30%">
                            <?php echo isset($areas[$in_store_info['prov']]) ? $areas[$in_store_info['prov']]['name'] : ''; ?>
                            <?php echo isset($areas[$in_store_info['city']]) ? $areas[$in_store_info['city']]['name'] : ''; ?>
                        </td>
                        <th width="20%" class="text-right">入库门店</th>
                        <td width="30%"><?php echo $in_store_info['name']; ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="table-header">
                流程信息
            </div>
            <table class="table">
                <tbody>
                    <tr>
                        <th width="20%">类型</th>
                        <th width="40%">描述</th>
                        <th width="20%">操作人</th>
                        <th width="20%">操作时间</th>
                    </tr>
                    <?php if( $actions ){ ?>
                    <?php foreach( $actions as $row ){ ?>
                    <tr>
                        <td><?php echo isset($actionMap[$row['action']]) ? $actionMap[$row['action']] : ''; ?></td>
                        <td class="text-left"><?php echo $row['des']; ?></td>
                        <td><?php echo $row['create_name']; ?></td>
                        <td><?php echo $row['create_time'] ? date('Y-m-d H:i:s',$row['create_time']) : ''; ?></td>
                    </tr>
                    <?php } ?>
                    <?php } ?>
                </tbody>
            </table>

            <?php 
                $good_price_total = 0;
                if(is_array($dispatch_goods) && count($dispatch_goods) > 0){ 
            ?>
            <div class="table-header">
                原料列表
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="10%">原料编号</th>
                        <th width="30%">原料名称</th>
                        <th width="10%">计价方式</th>
                        <th width="10%">计价单位</th>
                        <th width="10%">单位计价量</th>
                        <th width="10%">参考采购价</th>
                        <th width="10%">调度数量</th>
                        <th width="10%">小计</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach ($dispatch_goods as $k=>$row) {
                            $single_good_price = sprintf('%.2f', $row['price'] * $amount_arr['good'][$row['id']]);
                            $good_price_total += $single_good_price;
                    ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo isset($good_method_types[$row['method']]) ? $good_method_types[$row['method']] : ''; ?></td>
                        <td><?php echo $row['unit']; ?></td>
                        <td><?php echo $row['amount']; ?></td>
                        <td>￥<?php echo $row['price']; ?></td>
                        <td><?php echo $amount_arr['good'][$row['id']]; ?></td>
                        <td>￥<?php echo $single_good_price; ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="7" style="text-align:right;"><font color="red"><?php echo count($dispatch_goods); ?></font> 件原料，总金额：</td>
                        <td colspan="1">￥<?php echo sprintf('%.2f', $good_price_total); ?></td>
                    </tr>
                </tbody>
            </table>
            <?php } ?>

            <?php 
                $product_price_total = 0;
                if(count($dispatch_products) > 0){
            ?>
            <div class="table-header">
                商品列表
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="15%">商品编号</th>
                        <th width="10%">商品货号</th>
                        <th width="35%">商品名称</th>
                        <th width="10%">商品规格</th>
                        <th width="10%">网站销售价</th>
                        <th width="10%">调度数量</th>
                        <th width="10%">小计</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach ($dispatch_products as $k=>$row) {
                            $single_product_price = sprintf('%.2f', $row['price'] * $amount_arr['product'][$row['id']]);
                            $product_price_total += $single_product_price;
                    ?>
                    <tr>
                        <td><?php echo $row['sku']; ?></td>
                        <td><?php echo $row['product_pin']; ?></td>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo isset($categorys[$row['category_id']]) ? $categorys[$row['category_id']]['name'] : ''; ?></td>
                        <td>￥<?php echo $row['price']; ?></td>
                        <td><?php echo $amount_arr['product'][$row['id']]; ?></td>
                        <td>￥<?php echo $single_product_price; ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="6" style="text-align:right;"><font color="red"><?php echo count($dispatch_products); ?></font> 件商品，总金额：</td>
                        <td colspan="1">￥<?php echo sprintf('%.2f', $product_price_total); ?></td>
                    </tr>
                </tbody>
            </table>
            <?php } ?>

            <div class="table-header">
                调度总价
            </div>
            <table class="table">
                <tbody>
                    <tr>
                        <th width="80%" style="text-align:right;">总金额：</th>
                        <td width="20%">￥<?php echo sprintf('%.2f', $good_price_total + $product_price_total); ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="btn-line">
                <input class="btn_print noprint" type="submit" onclick="window.print();" value="打 印">
            </div>

        </div>
    </div>
</body>
</html>
