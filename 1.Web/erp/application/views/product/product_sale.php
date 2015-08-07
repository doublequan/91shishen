<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('products/product'); ?>">商品管理</a></li>
            <li><a href="<?php echo site_url('products/product'); ?>">商品上下架</a></li>
            <li class="active">编辑商品</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-success" id="success_alert" role="alert" style="display:none;"></div>
        <div class="alert alert-danger" id="danger_alert" role="alert" style="display:none;"></div>
        <form action="<?php echo site_url('products/product/doSale'); ?>" method="post" id="product_sale_form" class="form-horizontal">
            <input type="hidden" name="product_id" value="<?php echo $single['id']; ?>">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading" style="height:40px;">
                            <b>
                                正在操作的商品： <?php echo $single['title']; ?>；&nbsp;&nbsp;
                                参考价格：￥<?php echo $single['price']; ?>；&nbsp;&nbsp;
                                市场价格：￥<?php echo $single['price_market']; ?>； 
                            </b>
                        </div>
                        <table class="table table-striped table-hover table-bordered text-center" id="info_table">
                            <thead>
                                <tr>
                                    <th style="width:10%;" class="text-center">
                                        上下架状态&nbsp;<input type="checkbox" id="check_all_chk">
                                    </th>
                                    <th style="width:15%;">网站名称</th>
                                    <th style="width:15%;">商品价格</th>
                                    <th style="width:15%;">上架价格</th>
                                    <th style="width:45%;">商品参考库存</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if( $sites ){ ?>
                                <?php foreach( $sites as $k=>$row ){ ?>
                                <tr>
                                    <td><input type="checkbox" name="sites[]" class="check_single_chk form-control" value="<?php echo $row['id']; ?>"<?php echo isset($curr[$row['id']]) ? ' checked' : ''; ?>></td>
                                    <td><p class="form-control-static"><?php echo $row['name']; ?></p></td>
                                    <td><p class="form-control-static"><?php echo $single['price']; ?></p></td>
                                    <td><input type="text" name="price[]" class="form-control input-sm text-center" value="<?php echo isset($curr[$row['id']]) ? $curr[$row['id']]['price'] : $single['price']; ?>"></td>
                                    <td row_id="<?php echo $row['id']; ?>">
                                        <label class="radio-inline">
                                            <input type="radio" name="stock_<?php echo $row['id']; ?>" class="stock" value="-1"<?php echo isset($curr[$row['id']]) ? ( $curr[$row['id']]['stock']==-1 ? ' checked' : '' ) : ' checked'; ?>> 不限库存
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="stock_<?php echo $row['id']; ?>" class="stock" value="0"<?php echo isset($curr[$row['id']]) && $curr[$row['id']]['stock']==0 ? ' checked' : ''; ?>> 零库存
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="stock_<?php echo $row['id']; ?>" class="stock" value="1"<?php echo isset($curr[$row['id']]) && $curr[$row['id']]['stock']>0 ? ' checked' : ''; ?>> 手工设置库存
                                        </label>
                                        <label class="radio-inline">
                                            <input type="text" name="stock_num_<?php echo $row['id']; ?>" value="<?php echo isset($curr[$row['id']]) ? $curr[$row['id']]['stock'] : -1; ?>" id="stock_num_<?php echo $row['id']; ?>" class="form-control input-sm text-center" style="width:100px;"<?php echo isset($curr[$row['id']]) && $curr[$row['id']]['stock']>0 ? '' : ' disabled'; ?>>
                                        </label>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="form-group form_btn_line">
                <div class="col-sm-3 col-sm-offset-5 text-center">
                    <input type="submit" class="btn btn-primary btn-block" id="submit_btn" value="提 交"> 
                </div>
            </div> 
        </form>
    </div>
</div>

<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
    $('.stock').change(function(){
        var v = parseInt($(this).val());
        var site_id = parseInt($(this).parent().parent().attr('row_id'));
        if( v>0 ){
            $('#stock_num_'+site_id).val('').removeAttr('disabled').focus();
        } else {
            $('#stock_num_'+site_id).attr('disabled','disabled').val(v);
        }
    });
    $('#product_sale_form').bootstrapValidator().on('success.form.bv', function(e) {
        e.preventDefault();
        var $form = $(e.target);
        var bv = $form.data('bootstrapValidator');

        $.post($form.attr('action'), $form.serialize(), function(rst_json) {
            if(rst_json.err_no != 0){
                $('#danger_alert').empty().text(rst_json.err_msg).show();
                return;
            }
            else{
                $('#success_alert').empty().text('上下架操作成功！').show();
                window.setTimeout(function(){
                    window.location.href = "<?php echo site_url('products/product'); ?>";
                },2000);
            }
        }, 'json');
    });
});
</script>