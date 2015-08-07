<link href="<?php echo base_url('static/css/jquery.fancybox.css'); ?>" rel="stylesheet" type="text/css">
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo site_url('products/product'); ?>">商品管理</a></li>
            <li><a href="<?php echo site_url('products/good'); ?>">原料列表</a></li>
            <li class="active">原料加工</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-success" id="success_alert" role="alert" style="display:none;"></div>
        <div class="alert alert-danger" id="danger_alert" role="alert" style="display:none;"></div>
        <form action="<?php echo site_url('products/process/doAdd'); ?>" method="post" id="product_form" class="form-horizontal">
            <div class="row">
                <div class="col-sm-12">
                    <p class="bg-info form-square-title">1. 选择加工门店[加工后原料出库、商品入库]</p>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6">
                    <label for="" class="col-sm-3 control-label form_label" style="width:110px;">
                        <span><font color="red">*</font></span>
                        加工门店
                    </label>
                    <div class="col-sm-5">
                        <select name="city_id" class="form-control" id="city_id">
                            <option value="">选择城市</option>
                            <?php if( $citys ){ ?>
                            <?php foreach( $citys as $city_id=>$city_name ){ ?>
                            <option value="<?php echo $city_id; ?>"><?php echo $city_name; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <select name="store_id" class="form-control" id="store_id">
                            <option value="">选择门店</option>
                        </select>
                    </div>
                </div>
                <div class="form-group col-sm-6">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p class="bg-info form-square-title">2. 选择需要加工的原料</p>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6">
                    <label for="" class="col-sm-3 control-label form_label" style="width:110px;">
                        <span><font color="red">*</font></span>
                        选择原料
                    </label>
                    <div class="col-sm-6 form_input">
                        <input type="text" name="good_name" class="form-control" id="good_name" readonly>
                        <input type="hidden" name="good_id" id="good_id">
                    </div>
                    <div class="col-sm-3">
                        <a href="#select_dialog" class="btn btn-default btn-block" id="select_good_btn" kesrc="#select_dialog">选择原料</a>
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label for="loss_set" class="col-sm-3 control-label form_label">
                        计价单位
                    </label>
                    <div class="col-sm-3 form_input">
                        <input type="text" name="unit" class="form-control text-center" id="unit" value="" disabled>
                    </div>
                    <label class="col-sm-3 control-label form_label">
                        可用库存
                    </label>
                    <div class="col-sm-3 form_input">
                        <input type="text" name="stock" class="form-control text-center" id="stock" value="" disabled>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p class="bg-info form-square-title">3. 加工商品</p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <table class="table table-striped table-hover table-bordered text-center" id="products" style="display:none;">
                            <thead>
                                <tr>
                                    <th style="width:5%;">#</th>
                                    <th style="width:20%;">商品名称</th>
                                    <th style="width:12%;">商品编号</th>
                                    <th style="width:12%;">商品货号</th>
                                    <th style="width:12%;">规格</th>
                                    <th style="width:12%;">包装规格</th>
                                    <th style="width:12%;">计价单位</th>
                                    <th style="width:15%;">生产数</th>
                                </tr>
                            </thead>
                            <tbody>
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

<iframe src="" id="select_dialog" class="iframe_dialog" style="height:500px;"></iframe>
<script src="<?php echo base_url('static/js/jquery.fancybox.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('static/js/validator_rules.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
var stores = <?php echo $stores ? json_encode($stores) : '[]'; ?>;
$(function(){
    $('#city_id').change(function(){
        var html = '<option value="0">选择门店</option>';
        var city_id = $(this).val();
        if( city_id!='' ){
            var arr = stores[city_id];
            for( var i in arr ){
                var row = arr[i];
                html += '<option value="'+row.id+'">'+row.name+'</option>';
            }
        }
        $('#store_id').html(html);
    });

    $('#select_good_btn').click(function(){
        var store_id = parseInt($('#store_id').val());
        if( !store_id ){
            alert('请先选择所属加工中心或门店');
            return false;
        }
        var before_id = $('#good_id').val();
        $('iframe#select_dialog').attr('src', "<?php echo site_url('products/good/single_select_dialog'); ?>");
        $("#select_good_btn").fancybox({
            'hideOnContentClick': true,
            'padding':0,
            'afterClose': function(){
                var good_id = $('#good_id').val();
                if( good_id && good_id!=before_id ){
                    //加载商品信息
                    $.get("<?php echo site_url('products/process/getProducts'); ?>", {'store_id':store_id,'good_id':good_id}, function(data){
                        if( data.err_no==0 ){
                            $('#unit').val(data.unit);
                            $('#stock').val(data.stock);
                            var html = '';
                            for( var i in data.results ){
                                var row = data.results[i];
                                html += '<tr>';
                                html += '<td><p class="form-control-static">'+(parseInt(i)+1)+'</p></td>';
                                html += '<td><p class="form-control-static">'+row.title+'</p></td>';
                                html += '<td><p class="form-control-static">'+row.sku+'</p></td>';
                                html += '<td><p class="form-control-static">'+row.product_pin+'</p></td>';
                                html += '<td><p class="form-control-static">'+row.spec+'</p></td>';
                                html += '<td><p class="form-control-static">'+row.spec_packing+'</p></td>';
                                html += '<td><p class="form-control-static">'+row.unit+'</p></td>';
                                html += '<td><input type="text" name="amount[]" class="form-control input-sm text-center amount" good_num="'+row.good_num+'" title="'+row.title+'" style="width:150px;margin:0 auto;" value="0"><input type="hidden" name="product_id[]" value="'+row.id+'"></td>';
                                html += '</tr>';
                            }
                            $('#products tbody').html(html);
                            $('#products').show();
                        } else {
                            alert(data.err_msg);
                        }
                    },'json');
                }
            },
        });
    });

    $('#product_form').bootstrapValidator(validate_rules.process).on('success.form.bv', function(e){
        $('#submit_btn').removeAttr('disabled');
        var stock = $('#stock').val();
        if( stock==0 ){
            alert('当前原料在当前门店库存为0！');
            return false;
        }
        var total = 0;
        var selected = "";
        $('#products tbody .amount').each( function(){
            var amount = parseInt($(this).val());
            if( amount ){
                var good_num = parseFloat($(this).attr('good_num'));
                var title = $(this).attr('title');
                selected += '商品：'+title+"\t数量："+amount+"\n";
                total += amount*good_num;
            }
        });
        if( total==0 ){
            alert('请至少加工一种商品！');
            return false;
        }
        if( total>stock ){
            alert('需要加工的商品大于当前原料在门店的可用库存！');
            return false;
        }
        var str = "您确定把"+$('#good_name').val()+"加工为：\n"+selected;
        if( !confirm(str) ){
            return false;
        }
        e.preventDefault();
        var $form = $(e.target);
        var bv = $form.data('bootstrapValidator');
        $.post($form.attr('action'), $form.serialize(), function(rst_json) {
            if(rst_json.err_no != 0){
                $('#danger_alert').empty().text(rst_json.err_msg).show();
                return;
            }
            else{
                $('#success_alert').empty().text('加工成功！').show();
                window.setTimeout(function(){
                    window.location.href = "<?php echo site_url('products/good'); ?>";
                },2000);
            }
        }, 'json');
        return false;
    });
});
</script>