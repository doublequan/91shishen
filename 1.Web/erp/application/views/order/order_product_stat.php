<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>惠生活商品汇总单</title>
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
            <div class="title">惠生活电子商务股份有限公司 - 惠生活商品汇总单</div>
        </div>
        <div class="content">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="10%">商品编号</th>
                        <th width="10%">商品货号</th>
                        <th width="30%">商品名称</th>
                        <th width="15%">规格</th>
                        <th width="10%">包装规格</th>
                        <th width="8%">数量</th>
                        <th width="8%">单价</th>
                        <th width="9%">小计</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if( $products ){ ?>
                    <?php
                        $total = 0;
                        $amount = 0;
                    ?>
                    <?php foreach ($products as $k=>$row) { ?>
                    <?php
                        $total += $row['price_total'];
                        $amount += $row['amount'];
                    ?>
                    <tr>
                        <td><?php echo $row['sku']; ?></td>
                        <td><?php echo $row['product_pin']; ?></td>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['spec']; ?></td>
                        <td><?php echo $row['spec_packing']; ?></td>
                        <td><?php echo $row['amount']; ?></td>
                        <td>￥<?php echo $row['price_single']; ?></td>
                        <td>￥<?php echo $row['price_total']; ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="9" style="text-align:right;">
                            总份数：<?php echo $amount; ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            ￥<?php echo $total; ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <div class="btn-line">
                <input class="btn_print noprint" type="submit" onclick="window.print();" value="打 印">
            </div>

        </div>
    </div>
</body>
</html>
