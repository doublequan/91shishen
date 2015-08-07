<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>惠生活订单商品标签</title>
    <style media="print" type="text/css">
    *{
        margin: 0;
        padding: 0;
    }
    body{
        font:12px "黑体", Helvetica, Arial, sans-serif;
        color:#000;
        background-color:#fff;
    }
    .container{
        width:60mm;
    }
    .table {
        border-collapse: collapse!important;
        border: none;
        width: 60mm;
        height: 40mm;
    }
    .table>tbody>tr>td{
        line-height: 3mm;
        vertical-align: middle;
    }

    .table>tbody>tr>td:nth-child(1){
            text-align:right;
        }
    .noprint{
        display:none
    }
    </style>
    <style media="screen" type="text/css">
        * {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        body{
            font:16px/1.5 "黑体", Helvetica, Arial, sans-serif;
            color:#404040;
            background-color:#fff;
        }
        .container{
            width:960px;
            margin:20px auto;
            border-bottom: 1px solid #ccc;
        }
        .table {
            border-collapse: collapse!important;
            border: 0px solid #fff;
            width: 400px;
            margin-bottom: 20px;
            background-color: #fff
        }
        .table>tbody>tr>td{
            padding: 4px;
            line-height: 1.42857143;
            vertical-align: top;
        }

        .table>tbody>tr>td:nth-child(1){
            text-align:left;
            width: 80px;
        }
        .btn-line{
            line-height: 30px;
            height: 30px;
            margin-bottom: 40px;
            text-align: center;
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
    foreach ($label_list as $product_id => $single_info) { 
        $product = $single_info['product'];
        $order = $single_info['order'];
        for ($i=0; $i < (intval($order['amount'])); $i++) {
            $idx++;
    ?>
        <div class="container" <?php echo $idx==1?'':'style="page-break-before: always;"'; ?>>
            <table class="table">
                <tbody>
                    <tr>
                        <td>
                            品名：
                        </td>
                        <td colspan="3">
                            <?php echo iconv_substr($product['title'],0,14); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            规格：
                        </td>
                        <td>
                            <?php echo $product['spec']; ?>
                        </td>
                         <td>
                            质检员：
                        </td>
                        <td>
                           001
                        </td>
                    </tr>
                    <tr>
                        
                        <td>
                            价格：
                        </td>
                        <td>
                           ￥<?php echo $product['price']; ?>
                        </td>
                        <td>
                            品牌：
                        </td>
                        <td>
                            惠生活
                        </td>
                    </tr>
                    <tr>
                        <td>
                            日期：
                        </td>
                        <td>
                            <?php echo date("Y-m-d")?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: left;" >
                            <img src="/static/png/<?php echo $product['product_pin'];?>.png" style="width: 200px;height: 60px;border: 0px;padding-left: 10px;">
                        </td>

                    </tr>
                </tbody>
            </table>
        
        </div>
        <?php } ?>
    <?php } ?>
    <div class="btn-line">
        <input class="btn_print noprint" type="submit" onclick="window.print();" value="打 印">
    </div>
</body>
</html>
