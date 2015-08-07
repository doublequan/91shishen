//商品搜索
function doSearch(){
	var keyword = $.trim($("#search_keyword").val());
	if(keyword == ""){
		layer.tips("提示：请输入要搜索的关键词！", $("#search_keyword"), {time:2});
		$("#search_keyword").focus();
		return false;
	}
}

//设置Cookie
function setCookie(name,value){
	var Days = 1;
	var exp = new Date();
	exp.setTime(exp.getTime() + Days*24*3600*1000);
	document.cookie = name + "="+ escape (value) + ";path=" + "/" + ";expires=" + exp.toGMTString();
}

//删除Cookie
function getCookie(name){
	var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
	if(arr=document.cookie.match(reg)) return unescape(arr[2]);
	else return null;
}

//切换站点
function switchSite(site_id){
	setCookie("SITEID", site_id);
	window.location.reload();
}

//加入收藏
function addFav(title, url){
    if(document.all){
        window.external.AddFavorite(url, title);
    }else{
        layer.msg("请使用Ctrl+D添加收藏！", 2, 5);
    }
}

//分类效果
$(function(){
	$("#leftnav li").hover(function(){
		$(this).addClass("active").siblings().removeClass("active");
	}, function(){
		$(this).removeClass("active");
	});
});
