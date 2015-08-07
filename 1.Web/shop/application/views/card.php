<?php
defined('BASEPATH') || exit('Access denied');
?>
<!DOCTYPE html>
<html>
<head lang="zh">
<meta charset="UTF-8" />
<meta name="renderer" content="webkit" />
<title>惠生活卡 - <?php echo $siteName; ?></title>
<link rel="stylesheet" href="<?=STATICURL?>css/reset.css"/>
<link rel="stylesheet" href="<?=STATICURL?>css/myhome.css"/>
<style type="text/css">
img{border:0;}
.shouka{width:1176px;margin:0 auto}
.kaheader{width:1176px;overflow:hidden;margin:0 auto;background-color:#f2f2f2;}
.kaheader h3{color:#009900;}
.first{background-image:url(http://nj.100hl.cn/views/default/skin/default/images/bakf_02.png);width:1176px;overflow:hidden;background-repeat:no-repeat;margin-left:10px;}
.first h4{color:#4ab54a;margin:0;padding:0;margin-left:70px;margin-top:10px;}
.fright{width:496px;height:187px;background-color:#fafafa;float:right;margin-right:150px;border:1px solid #CCC;margin-bottom:20px;}
.fx{height:40px;border-bottom:1px dashed #030203;margin-top:5px;}
.mianz{height:40px;line-height:40px;;margin-left:5px;float:left;}
.jsf{float:right;margin-right:10px;margin-top:5px;}
.jsf a{width:18px;height:18px;line-height:18px;text-align:center;margin-top:3px;margin-right:4px;color:#999;border:1px solid #999;text-decoration:none;display:block;float:right;}
.js fz{float:right;}
.jsf input{width:30px;height:18px;border:1px solid #999;;;float:right;text-align:center;margin-right:4px;color:#999;margin-top:3px;}
.second{background-image:url(/views/default/skin/default/images/sbak_03.png);width:1176px;overflow:hidden;background-repeat:no-repeat;margin-left:10px;}
.second h4{color:#4ab54a;margin:0;padding:0;margin-left:60px;}
</style>
</head>
<body>
<?php $this->load->view('common/header')?>
<div class="breadcrumb area">
	<a href="<?=base_url()?>">首页</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="<?=base_url('member/home/index')?>">个人中心</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;账号安全
</div>
<div class="Ucenter mt20 area">
	<div class="mylist" style="margin-left:0;">
		<div class="address">
			<div class="title"><a href="javascript:;">惠生活卡</a></div>
			<div class="shouka" >
				<div class="kaheader" >
					<div class="first">
					<h4>惠生活充值卡</h4>
					<div class="fright">
					<div class="fx">
					<div class="mianz"> 
					<span>惠生活充值卡</span><span style="color:#009900;">100元</span><span style="font-weight:bold;margin-left:242px;margin-bottom:24px;color:#666;">我要买：</span>
					</div>
					<div class="jsf">
					<a href="#" id="add_1">+</a>
					<input type="text" value="0"  id="number1" name="number1"/><label></label>
					<a href="#" id="reduce_1">-</a>
					</div>
					</div>  

					<div class="fx">
					<div class="mianz"> 
					<span>惠生活充值卡</span><span style="color:#009900;">300元</span><span style="font-weight:bold;margin-left:242px;margin-bottom:24px;color:#666;">我要买：</span>
					</div>
					<div class="jsf">
					<a href="#" id="add_2">+</a>
					<input type="text" value="0"  id="number2" name="number2"/>
					<a href="#" id="reduce_2">-</a>
					</div>
					</div> 

					<div class="fx">
					<div class="mianz"> 
					<span>惠生活充值卡</span><span style="color:#009900;">500元</span><span style="font-weight:bold;margin-left:242px;margin-bottom:24px;color:#666;">我要买：</span>
					</div>
					<div class="jsf">
					<a href="#" id="add_3">+</a>
					<input type="text" value="0"  id="number3" name="number3"/>
					<a href="#" id="reduce_3">-</a>
					</div>
					</div> 

					<div class="fx">
					<div class="mianz"> 
					<span>惠生活充值卡</span><span style="color:#009900;">1000元</span><span style="font-weight:bold;margin-left:234px;margin-bottom:24px;color:#666;">我要买：</span>
					</div>
					<div class="jsf">
					<a href="#" id="add_4">+</a>
					<input type="text" value="0" id="number4" name="number4"/>
					<a href="#" id="reduce_4">-</a>
					</div>
					</div>   
				</div>
			</div>
			<div class="add" style="padding-left:500px;">
				<ul>
					<li>
						<label><font color="red">*</font> 姓名：</label>
						<input type="text" class="name" id="username" />
						<span id="tip_username" style="display:none;"></span>
					</li>
					<li>
						<label><font color="red">*</font> 手机：</label>
						<input type="text" class="sj" id="mobile" maxlength="11" />
						<span id="tip_mobile" style="display:none;"></span>
					</li>
					<li>
						<label><font color="red">*</font> 性别：</label>
						<label><input type="radio" name="gender" class="gender" style="width:15px;" value="1" checked />先生</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="radio" name="gender" class="gender" style="width:15px;" value="2" />女士</label>
					</li>
					<li>
						<label><font color="red">*</font> 收货地址：</label>
						<input type="text" class="dq" id="address" />
						<span id="tip_address" style="display:none;"></span>
					</li>
					<li>
						<label><font color="red">*</font> 付款方式：</label>
						<label><input type="radio" name="pay_type" class="pay_type" style="width:15px;" value="1" checked />货到付款</label>
					</li>
					<li><input type="submit" id="submit" class="tj" value="提交" /></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('common/footer')?>
<script src="<?=STATICURL?>js/area.js"></script>
<script src="<?=STATICURL?>js/verify.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#submit').click( function(){
		var username = $.trim($("#username").val());
		var mobile = $.trim($("#mobile").val());
		var gender = $.trim($(".gender:checked").val());
		var address = $.trim($("#address").val());
		var number1 = $.trim($("#number1").val());
		var number2 = $.trim($("#number2").val());
		var number3 = $.trim($("#number3").val());
		var number4 = $.trim($("#number4").val());
		if( number1==0 && number2==0 && number3==0 && number4==0 ){
			alert('请至少选择一种惠生活充值卡');
			return false;
		}
		if( username=='' ){
			$("#tip_username").html("请填写收货人姓名").show();
			$("#username").focus();
			return false;
		}else{
			$("#tip_username").hide();
		}
		if( mobile=='' ){
			$("#tip_mobile").html("请填写联系手机号码").show();
			$("#mobile").focus();
			return false;
		}else{
			$("#tip_mobile").hide();
		}
		if( address=='' ){
			$("#tip_address").html("请填写收货地址").show();
			$("#address").focus();
			return false;
		}else{
			$("#tip_address").hide();
		}
		var ids = new Array();
		if( number1 ){
			ids.push('100:'+number1);
		}
		if( number2 ){
			ids.push('300:'+number2);
		}
		if( number3 ){
			ids.push('500:'+number3);
		}
		if( number4 ){
			ids.push('1000:'+number4);
		}
		var params = {
			'username'	: username,
			'mobile'	: mobile,
			'address'	: address,
			'gender'	: gender,
			'ids'		: ids.toString(),
		};
		layer.load("提交中...");
		$.ajax({
			url: '<?php echo base_url('card/add') ;?>',
			type: "post",
			dataType: "json",
			data: params,
			success: function(data){
				if(data.err_no == 0){
					layer.msg("您的购买请求已经成功提交，请等待客服联系！", 2, 1, function(){
						//location.reload();
					});
				}else{
					layer.msg("提交失败", 2, 5);
				}
			}
		});
	});
	//数量加
    $('#add_1').click(function(){
        var va = $(this).siblings("input").val();
        if(va == ""){$(this).siblings("input").val(0);}
        var num = parseInt(va)+1;
       // alert(num);
       $(this).siblings("input").val(num);

    })
    //数量减
    $('#reduce_1').click(function(){
        var va = $(this).siblings("input").val();
        
        var num = parseInt(va)-1;
       // alert(num);
       if(num <0){return false;}
       $(this).siblings("input").val(num);

    })
    
    //数量加
    $('#add_2').click(function(){
        var va = $(this).siblings("input").val();
        if(va == ""){$(this).siblings("input").val(0);}
        var num = parseInt(va)+1;
       // alert(num);
       $(this).siblings("input").val(num);
    })
    //数量减
    $('#reduce_2').click(function(){
        var va = $(this).siblings("input").val();
        
        var num = parseInt(va)-1;
       // alert(num);
       if(num <0){return false;}
       $(this).siblings("input").val(num);

    })
    //数量加
    $('#add_3').click(function(){
        var va = $(this).siblings("input").val();
        if(va == ""){$(this).siblings("input").val(0);}
        var num = parseInt(va)+1;
       // alert(num);
       $(this).siblings("input").val(num);
    })
    //数量减
    $('#reduce_3').click(function(){
        var va = $(this).siblings("input").val();
        
        var num = parseInt(va)-1;
       // alert(num);
       if(num <0){return false;}
       $(this).siblings("input").val(num);

    })
    //数量加
    $('#add_4').click(function(){
        var va = $(this).siblings("input").val();
        if(va == ""){$(this).siblings("input").val(0);}
        var num = parseInt(va)+1;
       // alert(num);
       $(this).siblings("input").val(num);

    })
    //数量减
    $('#reduce_4').click(function(){
        var va = $(this).siblings("input").val();
        
        var num = parseInt(va)-1;
       // alert(num);
       if(num <0){return false;}
       $(this).siblings("input").val(num);

    })
    //判断数值是否正确
    $("#number1").blur(function(){
        var n = $(this).val();
        //alert(n);
        var patrn=/^[0-9]{1,20}$/; 
        if (!patrn.exec(n)){
            $(this).val(0);
        }
    })
    $("#number2").blur(function(){
        var n = $(this).val();
        //alert(n);
        var patrn=/^[0-9]{1,20}$/; 
        if (!patrn.exec(n)){
            $(this).val(0);
        }
    })
    $("#number3").blur(function(){
        var n = $(this).val();
        //alert(n);
        var patrn=/^[0-9]{1,20}$/; 
        if (!patrn.exec(n)){
            $(this).val(0);
        }
    })
    $("#number4").blur(function(){
        var n = $(this).val();
        //alert(n);
        var patrn=/^[0-9]{1,20}$/; 
        if (!patrn.exec(n)){
            $(this).val(0);
        }
    })
});
</script>
</body>
</html>