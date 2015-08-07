/**
 * 功能函数库
 * author LiuPF<mail@phpha.com>
 * date 2014-8-7
 */

//获取省份
function getProvince(province_id){
	$.ajax({
		url: "/member/area/ajax_province",
		async: false,
		type: "post",
		dataType: "json",
		success: function(data){
			$.each(data, function(i, item){
				if(item.id == province_id){
					$("#province_id").append('<option value="'+item.id+'" selected>'+item.name+'</option>');
				}else{
					$("#province_id").append('<option value="'+item.id+'">'+item.name+'</option>');
				}
			});
		}
	});
}

//获取城市
function getCity(city_id){
	var province_id = $("#province_id option:selected").val();
	if(province_id == ""){
		$("#city_id").html('<option value="">请选择</option>');
		$("#area_id").html('<option value="">请选择</option>');
		return false;
	}
	$.ajax({
		url: "/member/area/ajax_city",
		async: false,
		type: "post",
		dataType: "json",
		data: {"province_id":province_id},
		success: function(data){
			$("#city_id").html('<option value="">请选择</option>');
			$("#area_id").html('<option value="">请选择</option>');
			$.each(data, function(i, item){
				if(item.id == city_id){
					$("#city_id").append('<option value="'+item.id+'" selected>'+item.name+'</option>');
				}else{
					$("#city_id").append('<option value="'+item.id+'">'+item.name+'</option>');
				}
			});
		}
	});
}

//获取地区
function getArea(area_id){
	var city_id = $("#city_id option:selected").val();
	if(city_id == ""){
		return false;
	}
	$.ajax({
		url: "/member/area/ajax_area",
		async: false,
		type: "post",
		dataType: "json",
		data: {"city_id":city_id},
		success: function(data){
			$("#area_id").html('<option value="">请选择</option>');
			$.each(data, function(i, item){
				if(item.id == area_id){
					$("#area_id").append('<option value="'+item.id+'" selected>'+item.name+'</option>');
				}else{
					$("#area_id").append('<option value="'+item.id+'">'+item.name+'</option>');
				}
			});
		}
	});
}

//获取街道
function getStreet(street_id){
	var area_id = $("#area_id option:selected").val();
	if(area_id == ""){
		return false;
	}
	$.ajax({
		url: "/member/area/ajax_street",
		async: false,
		type: "post",
		dataType: "json",
		data: {"area_id":area_id},
		success: function(data){
			$("#street_id").html('<option value="">请选择</option>');
			$.each(data, function(i, item){
				if(item.id == street_id){
					$("#street_id").append('<option value="'+item.id+'" selected>'+item.name+'</option>');
				}else{
					$("#street_id").append('<option value="'+item.id+'">'+item.name+'</option>');
				}
			});
		}
	});
}