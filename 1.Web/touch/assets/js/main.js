var hsh = function(){

	var reqPath = 'http://m.100hl.com/api.php';
	var categoryPath = 'http://m.100hl.com/page/category.php';
	var goodsPath = 'http://m.100hl.com/page/goods.php';
	var siteUrl = 'http://m.100hl.com'; //最后面不能加“/”
	var session_id = '';
    var site_name = ' « 惠生活';
	return {

		init: function(){

			$.base64.is_unicode = true;

			$("form").submit( function(){ return true;});

			//alert($(window).width() +"///////"+ $(window).height());
			if(! $.cookie('site_id'))
			{
				$.cookie('site_id',1);
				$.cookie('site_name',"南京");
			}
			else
			{
				$("#location-name").text($.cookie("site_name"));
			}


			//文本框获取焦点 隐藏菜单
			$("input").focus(function(){

					$(".index-nav").slideUp();
			});
			$("input").blur(function(){

					$(".index-nav").slideDown();
			});


			//返回上一页

			$("#go-history").click(function(){

				window.history.go(-1);
			});

			//跳转按钮 data-url 
			$("#go-path").click(function(){

				window.location.href = $(this).data('url');
			});


			//seach 搜索按钮

			$("#btn-search").click(function(){

				var words = encodeURI($("#search-words").val());
				if(words != "")
				{
					window.location.href =  siteUrl +"/page/search_list.php?words="+words;
					return false;
				}
				else
				{
					layer.open({

						content: '要搜什么东西？您最好给个提示！',
						btn: ['好的！']
					});
				}
				
			});

			$("#download_app").attr('href', siteUrl+ "/page/app.php");


			// a[data-id] 点击

			$(".box").on('click','a[data-id]',function(){

				var id = $(this).data('id');

				var mold = $(this).data('mold');
				var name = $(this).data('name');

				if(typeof(mold) == 'undefined'){ 
					layer.open({
						type: 0,
						content: '[data-mold] 未定义！',
						btn: ['好的'],
					});

					return false;
				}
				if(typeof(name) == 'undefined'){

					name = "未知名称";
				}

				var moldPath = '';
				switch(mold){

				 	case 'goods':
				 	moldPath = goodsPath;
				 	break;

				 	case 'category':
				 	moldPath = categoryPath;
				 	break;
                        
				}

				layer.open({
					type: 0,
					content: '正在为了您跳转...',
					time: 1,
					end: function(elem){
						var params = $.param({"id":id, "name": name});
						
						window.location.href = moldPath +"?"+ params;
					},
				});
			
			});
            
            //四宫格 碎片
            $("#market-box, #banner-box").on('click','a',function(){
                
                if($(this).data('type') == 'undefined')
                {
                    layer.open({
                        content: '不知道是什么标签!',
                        btn:['好的'],
                    });
                    
                    return false;
                }
                
                var typeid = $(this).data('type');                
                var url = $(this).data('url');
                var name = $(this).data('name');
                var path = '';

                switch(typeid){
                        
                        case 0:
                        path = url;
                        break;
                        
                        case 1:
                        
                        path = categoryPath +"?"+ $.param({ "id": url,"name": name});
                        break;
                        
                        case 2:
                        path = goodsPath +"?"+ $.param({ "id":url, "name": name });
                        break;
                        
                        case 3:
                        
                        path = url;
                        break;
                        
                        case 4:
                        path = "http://m.100hl.com/page/activity/special.php?id=" + url;
                        break;
                }
                
                layer.open({
                   
                    type: 0,
                    time: 1,
                    content: '正在为了您跳转...',
                    end: function(){
                        window.location.href = path;
                    },
                    
                });
            });
            
			$("#search-words").keydown(function(event){

				if(event.keyCode == 13)
				{

					var words = encodeURI($("#search-words").val());
				if(words != "")
				{
					window.location.href =  siteUrl +"/page/search_list.php?words="+words;
					return false;
				}
				else
				{
					layer.open({

						content: '要搜什么东西？您最好给个提示！',
						btn: ['好的！']
					});
					return false;
				}
				}
			});

			// 全局 $.ajax 初始值
			$.ajaxSetup({
			  url: reqPath,
			  async: true,
			  global: true,
			  type: "post",
			  dataType: "json",
			  timeout: 10000,


			  error: function(err_data){

			  		if(err_data.statusText == 'timeout')
						{
							layer.open({
								content: '请求服务超时！',
								btn:['好的'],
							});
						}
						else{
							layer.open({
								content: '未知错误',
								btn:['好的'],
							});
						}
			  },
			});

			//百度统计

			var _hmt = _hmt || [];
			(function() {
			  var hm = document.createElement("script");
			  hm.src = "//hm.baidu.com/hm.js?2123c8400ca60adf0dc8617761dfa16e";
			  var s = document.getElementsByTagName("script")[0]; 
			  s.parentNode.insertBefore(hm, s);
			})();
		},

		//Home 主页============================================================================================
		home: function(){

			/*
			var timespan = new Date().getTime() - $.cookie('prompt');
			
			// 显示顶部注释 
			// if((timespan/(24*3600*1000)) < 7)
			// {//点击下载后7天不显示
			// 	$('#download-box').slideUp('slow');
			// }
			// else
			// {
			// 	$('#download-box').slideDown('slow');
			// }

			$('#download-box').slideDown('slow');

			$("#download-close").on('click',function(){

				$('#download-box').slideUp('slow',function(){

					$.cookie('prompt',new Date().getTime());
				});
			});

			$("#download-btn").on('click',function(){

				$.cookie('prompt',new Date().getTime());
				window.location.href = $(this).data('url');
			});
			*/

			$(".banner").slideDown('slow');
		var params = $.param({qt: "/common/data/site"});
		
		$.ajax({

			data: params,
			async: false,
			beforeSend: function(){
	  		layer.open({
				type: 2,
				content: '正在加载可用站点...',
			});
	  		},
			complete: function(){

	  			setTimeout( function(){ layer.closeAll();}, 1000);
	 		},
			success: function(data){

				if(data.err_no == 0)
				{
					var tpl = $("#location-temp").html();
					var results = data.results;

					laytpl(tpl).render(results, function(html){
						$("#location-box").html(html);
					})
				}
			},
		});


			$("#location-btn").on("click",function(){
				
				$("#location-box").slideToggle()
			});


			//记录地址

			$("#location-box").on('click','a',function(){

				
				$.cookie('site_id',$(this).data('id'));
				$.cookie('site_name',$(this).data('name'));

				location.reload();

			});

			//market

			var marketData = $.param({
				qt: "/frag/lists",
				must: '{"site_id":'+$.cookie('site_id')+'}',
			});
			$.ajax({
				data: marketData,
				beforeSend: function(){
			  		layer.open({
						type: 2,
						content: '正在为您加载首页...',
					});
			  		},
					complete: function(){
			  		setTimeout( function(){ layer.closeAll();}, 1000);
			 		},
				success: function(data){

					

					if(data.err_no == 0)
					{
						var banner_tpl = $("#banner-temp").html();

						var banner_results = data.results['1'];
						laytpl(banner_tpl).render(banner_results,function(html){

							$("#banner-box").html(html);
						});

						var tpl = $("#market-temp").html();
						var results = data.results['2'];
						laytpl(tpl).render(results, function(html){
							$("#market-box").html(html);
						});

						var goods_tpl = $("#goods-new-temp").html();
						var goods_result = data.results['3'];

						laytpl(goods_tpl).render(goods_result,function(html){
							$("#goods-new-box").html(html);
						});
					}
					else
					{
						layer.open({
								content: data.err_msg,
								btn:['好的'],
							});
					}
				},
			});
			
		
		//HOme.End
		},

		// category 类别明细 ======================================================================================
		category_list: function(){

			var site_id = $.cookie('site_id');
			var params = $.param({
				qt: '/product/category',
				must: '{"site_id":'+ site_id +'}'
			});
			$.ajax({

				type: "post",
				url: reqPath,
				data: params,
				async: true,
				dataType: "json",
				beforeSend: function(){

					layer.open({
						type: 2,
						content: '正在加载类别明细..',
					});
				},
				complete: function(){

					setTimeout( function(){ layer.close(0)}, 1000);
				},
				success: function(data){

					if(data.err_no == 0)
					{
						var tpl = $("#category-list-temp").html();
						var results = data.results;
						laytpl(tpl).render(results, function(html){
							$("#category-list-box").html(html);
						})
					}
					else
					{
						layer.open({

							content: data.err_msg,
							btn: ['好的'],
						})
					}
				},
			});
		},
		//category 类别 =====================================================================================
		category: function(){

			var category_id = $(".category").data('category-id'), page = $(".category").data('page-num');

			var site_id = $.cookie('site_id');
			var params = $.param({
				"qt": '/product/main/lists',
				"must": JSON.stringify({site_id:site_id}),
				"fields":JSON.stringify({
					category_id	: category_id,
					orderby		: 1,
					sortby		: -1,
					page 		: page,
					size		: 11,
				}),
			});
			$.ajax({
				data: params,
				beforeSend: function(){
			  		layer.open({
						type: 2,
						content: '正在加载类别...',
					});
			  		},
				complete: function(){
		  		setTimeout( function(){ layer.close(0);}, 1000);
		 		},
				success: function(data){

					if(data.err_no == 0){

						var tpl = $("#category-temp").html();
						var results = data.results.list;
						laytpl(tpl).render(results, function(html){
							$("#category-box").html(html);
						});

						var pager_tpl = $("#pager-tpl").html();
						var pager_results = data.results.pager;
						laytpl(pager_tpl).render(pager_results, function(html){
							$("#pager-box").html(html);
						});

					}
					else{

						layer.open({
							content: data.err_msg,
							btn: ['好的'],
						});
					}
				},
			});

		//分类商品分页	
		$(".pager").on('click','a',function(){

			var category_id = $(this).data('category-id'), category_name = $(this).data('category-name'), page_num = $(this).data('page-num');
			
			window.location.href = siteUrl + "/page/category.php?id="+ category_id +"&name="+ category_name +"&page="+ page_num;
		});

		},
		//goods 商品 =========================================================================================
		goods: function(){

			var goods_id = $(".goods").data('goods-id');
			var site_id = $.cookie('site_id');

			var params = $.param({
				"qt": '/product/main/detail',
				"must": JSON.stringify({
					'site_id':site_id,
					'product_id':goods_id,
				}),
			});
			$.ajax({
				data: params,
				beforeSend: function(){
			  		layer.open({
						type: 2,
						content: '正在加载商品信息...',
					});
			  		},
				complete: function(){
		  		setTimeout( function(){ layer.close(0);}, 1000);
		 		},
				success: function(data){

					if(data.err_no == 0){
                        $(document).attr('title',data.results.title + site_name);
						var tpl = $("#goods-temp").html();
						var results = data.results;
						laytpl(tpl).render(results, function(html){
							$("#goods-box").html(html);
						});
					}
					else{

						layer.open({
							content: data.err_msg,
							btn: ['好的'],
						});
					}
				}
			});
			
			//添加收藏

			$("#add-favor").on('click',function(){



				if(typeof($.cookie('site_login')) == 'undefined' || $.cookie('site_login') == 'null' || typeof($.cookie('user_info')) == 'undefined' || $.cookie('user_info') == 'null' ){

					layer.open({

						content: '未登录，是否登录？',
						btn: ['登录','取消'],
						yes: function(){

							window.location.href = siteUrl + "/page/login.php";
						},
					});

				}else{

						var goodsId = $(".goods").data('goods-id');
						var sessionId =$.base64.decode($.cookie("site_login"));
						var params = $.param({
							qt:'/user/fav/add',
							must: JSON.stringify({"sessionid":sessionId,"product_id":goodsId}),
						});

						$.ajax({
							data: params,
							async: false,
							success: function(data){

								if(data.err_no == 0){

									layer.open({

										type: 2,
										content: '收藏成功！',
										time: 1,
									});
								}
								else{
									var err_var = '';

									switch(data.err_no){

										case 3001:
										err_var = '用户未登陆!'
										break;
										case 3002:
										err_var = '产品不存在！';
										break;
										default:
										err_var = '未知错误！';
										break;
									}

									layer.open({

										btn:['确定'],
										content: err_var,
									});

								}
							},
						});

					
				}
			});
			
			//减数量
			$("#goods-box").on('click','#sub-amount',function(){

				var goodsAmount =parseInt( $("#goods-detail-amount").val());
				goodsAmount -= 1;

				if( goodsAmount > 0 )
				{
					$("#goods-detail-amount").val(goodsAmount);
				}
				else
				{
					layer.open({
						content: '你见过买负数的？',
						btn: ['好的']
					});
				}
				
			});

			//加数量
			$("#goods-box").on('click','#add-amount',function(){

				var goodsAmount =parseInt( $("#goods-detail-amount").val());
				goodsAmount += 1;
				

				$("#goods-detail-amount").val(goodsAmount);
		

			});

			//添加购物车
			$("#goods-box").on('click','#add-cart',function(even){


				if(typeof($.cookie('site_login')) == 'undefined' || $.cookie('site_login') == 'null' || typeof($.cookie('user_info')) == 'undefined' || $.cookie('user_info') == 'null' ){

					layer.open({

						content: '未登录，是否登录？',
						btn: ['登录','取消'],
						yes: function(){

							window.location.href = siteUrl + "/page/login.php";
						},
					});

				}else{

				 var sessionId =$.base64.decode($.cookie("site_login"));
				 var goodsId = $(this).data("goods-id");
				 var goodsAmount = parseInt($("#goods-detail-amount").val());

				 if(!$.isNumeric(goodsAmount))
				 {

				 	layer.open({

				 		content:'购买数量必须为数字',
				 		btn:['确定'],
				 	});
				 	return false;

				 }
				 else if(goodsAmount < 1)
				 {
				 	$("#goods-detail-amount").val(1);
				 	goodsAmount = 1;
				 	return false;
				 }

				 var params = $.param({
				 	qt: '/cart/add',
				 	must:JSON.stringify({
				 		sessionid : sessionId,
				 		product_id: goodsId,
				 		amount	: goodsAmount,
				 	}),
				 });



				 $.ajax({
				 	data: params,
				 	async: false,

				 	success: function(data){

				 		if(data.err_no == 0){

				 			layer.open({
								content: '成功加入 [ "'+ goodsAmount +'" ] 件商品！',
								btn:['结账?','继续购物'],
								yes: function(){

									window.location.href = siteUrl +"/page/cart.php";
								},	
							});
				 		}
				 		else
				 		{

				 			var err_var = '';

									switch(data.err_no){

										case 3001:
										err_var = '您未登录!'
										break;
										case 3002:
										err_var = '产品不存在！';
										break;
										default:
										err_var = '未知错误！';
										break;
									}

									layer.open({

										btn:['确定'],
										content: err_var,
									});
				 		}
				 	}

				 });
				}

				});

		},
		//search 搜索 ========================================================================================
		search: function(){

				var site_id = $.cookie('site_id');


				var search_params = $.param({

					qt: '/product/search/rand',
					must:JSON.stringify({

						'site_id':site_id
					}),
				});



				$.ajax({
					data:search_params,
					success:function(data){


						if(data.err_no == 0)
						{
							var product_title = data.results.title;

							$("#search-words").attr('placeholder',product_title); 

						}
					}
				})
		},
		search_list: function(){
			var site_id = $.cookie('site_id');
			var serach_words = $(".search_list").data("search-words");
			var page_num = $(".search_list").data("page-num");
			var search_params = $.param({

				qt:'/product/search',
				must: JSON.stringify({
					site_id: site_id,
					keyword: serach_words,
				}),
				fields: JSON.stringify({
					orderby: 2,
					sortby: -1,
					page: page_num,
					size: 11,
				}),
			});

			$.ajax({
				data: search_params,
				success: function(data){

					if(data.err_no == 0)
					{

						var tpl = $("#search_list-tpl").html();
						var results = data.results.list;
						laytpl(tpl).render(results, function(html){
							$("#search_list-box").html(html);
						});

						var pager_tpl = $("#pager-tpl").html();
						var pager_results = data.results.pager;
						laytpl(pager_tpl).render(pager_results, function(html){
							$("#pager-box").html(html);
						});
					}
					
				}
			});

			//结果分页	
		$(".pager").on('click','a',function(){

			var search_words = $(this).data('search-words'), page_num = $(this).data('page-num');
			
			window.location.href = siteUrl + "/page/search_list.php?words="+ search_words +"&page="+ page_num;
		});

		},
		my: function(){
			
			if( typeof($.cookie('site_login')) == 'undefined' || $.cookie('site_login') == 'null' )
			{
				layer.open({
					type: 2,
					content: '您还未登录，现在带您去登录！',
					time: 1,
					end: function(){
						window.location.href = siteUrl + "/page/login.php";
					}
				});	
			}
			else{

				var user_info = JSON.parse($.cookie('user_info'));
				$(".profile-avatar img").attr({src:user_info.avatar, alt:user_info.username});
				$("#username").text(user_info.username);
				$("#cardno").text(user_info.cardno);
			}

			$("#do-logout").click(function(){

				layer.open({

					btn:['是','否'],
					content: '是否确定退出?',
					shadeClose: false,
					yes:function(){
						$.cookie('site_login',null);
						$.cookie('user_info',null);
						location.reload();
					},

				});
				
			});// 退出按钮


			$("#get-more").click(function(){

	

				if($(".profile-more").data('show') == 'no'){
				var site_login = $.cookie('site_login');
				session_id = $.base64.decode(site_login);

				var params = {
					qt: '/user/account/detail',
					must: JSON.stringify({"sessionid":session_id}),
				}

				$.ajax({	

					data:params,
					async: false,
					success: function(data) {

						if(data.err_no == 0) {
							$("#mobile").text(data.results.mobile);
							$("#email").text(data.results.email);
							$("#qq").text(data.results.qq);
							$("#tel").text(data.results.tel);
							$("#create_ip").text(data.results.create_ip);
							$("#create_time").text(data.results.create_time);
							$("#login_ip").text(data.results.login_ip);
							$("#login_time").text(data.results.login_time);

							if(! data.results.email == ""){
								if( data.results.email_status == 1){

									$("#email_status").text('已验证');
									$("#email_status").addClass('status-yes');
								}else{
									$("#email_status").text('未验证');
									$("#email_status").addClass('status-no');
								}
							}
							if(! data.results.mobile == ""){

								if( data.results.mobile_status == 1){

									$("#mobile_status").text('已验证');
									$("#mobile_status").addClass('status-yes');
								}else{
									$("#mobile_status").text('未验证');
									$("#mobile_status").addClass('status-no');
								}
							}
						}

						
						$(".profile-more").slideDown('slow','swing',function(){
							$(".profile-more").data('show','yes');
						});
						
					}
				});
				} else{
					
					$(".profile-more").slideUp('slow','swing',function(){
						$(".profile-more").data('show','no');
					});	
				}
				
			}); //获取更多用户信息

			
			//点击订单状态
			$(".pay-status").click(function(){

				var status_id = $(this).data('pay-status');

				layer.open({
					type:2,
					content: '正在跳转！',
					time:1,
					end: function(){

						window.location.href = siteUrl + "/page/order.php?pay_status="+ status_id;
					}
				});
			});
		},

		//登录 ===================================================================================
		login: function(){

			if( typeof($.cookie('site_login')) != 'undefined' && $.cookie('site_login') != 'null')
			{
				layer.open({

					type: 2,
					content: '您已登录，现在带您去用户中心！',
					time: 1,
					end: function(){
						window.location.href = siteUrl + "/page/profile.php";
					}
				});
			}

			$("#password").keyup(function(event){

				if(event.keyCode == 13)
				{
					$("#btn-login").click();
				}
			});
			$("#btn-login").click(function(){


				var password = $("#password").val() , username = $("#username").val() , form_path = $("#form-path").val();


				if(password == "" || username == "") {

					layer.open({
						content: '账号或密码不能为空！',
						btn: ['确定'],
					});
				}else {

					var params = $.param({
						qt: "/user/account/login",
						must: JSON.stringify({"account":username,"pass":password})
					})

					$.ajax({
						async: false,
						data: params,
						beforeSend: function(){
				  		layer.open({
							type: 2,
							content: '正在登录...',
						});
				  		},
						complete: function(){
				  		setTimeout( function(){ layer.close(0);}, 1000);
				 		},
						success: function(data){

							if(data.err_no == 0)
							{

								var site_login = $.base64.encode(data.results.sessionid);
								$.cookie('site_login',site_login,{ expires: 7 });
								var username = data.results.username , cardno=data.results.cardno, discount= data.results.discount, avatar=data.results.avatar;
								var user_info = JSON.stringify({username:username, avatar:avatar, discount:discount, cardno:cardno});
								$.cookie('user_info',user_info,{ expires: 7 });
								layer.open({
									type: 2,
									content:'登录成功，即将跳转..',
									time: 2,
									end: function(){

										if(form_path == "")
										{
											window.location.href = siteUrl+"/page/profile.php";
										}
										else{

											window.location.href = form_path;
										}
									}
								});

							}else{

								var err_str = '';
								switch(data.err_no){

									case 3001:
										err_str = "10分钟内超过5次登陆失败";
									break;
									case 3002:
										err_str = "登陆账号不合法";
									break;
									case 3003:
										err_str = "登陆密码不合法";
									break;
									case 3004:
										err_str = "登陆失败，账号或密码错误";
									break;
									case 3005:
										err_str = "账号被锁定";
									break;
									
									default:
										err_str = "未知错误";
									break;

								}

								layer.open({
									btn: ['好的'],
									content: err_str,
								});
							}

						},
					});
				}
			});

		},

		//注册===================================================================================
		register: function(){

			if( typeof($.cookie('site_login')) != 'undefined' && $.cookie('site_login') != 'null')
			{
				layer.open({

					type: 2,
					content: '您已登录，现在带您去用户中心！',
					time: 1,
					end: function(){
						window.location.href = siteUrl + "/page/profile.php";

					}
				});
			}
            
            if(typeof($.cookie('site_invite')) != 'undefined' && $.cookie('site_invite') != 'null')
            {
                var site_invite = $.base64.decode($.cookie('site_invite'));
                $("#inviter").val(site_invite);
            }
            
            
			// 登录按钮方法
			$("#btn-register").click(function(){
                 
				// var username = $("#username").val().trim();
				var mobile = $("#mobile").val().trim(),
				username = mobile,

				password = $("#password").val().trim(), 
				repeat_password = $("#repeat_password").val(),
				card = ""; //$("#card").val()
				//mobile_verify = $("#mobile_verify").val().trim();
				//email = $("#email").val().trim(),;
                var inviter_code  = $("#inviter").val().trim();
                
				if( "" == username || mobile == "" || password == "" || repeat_password == ""){

					layer.open({
						btn:['确定'],
						content: '除"会员卡"以外，您还有地方没填？',
					});

					return false;
				}else{

					var regMobile = new RegExp(/^1[3578]+\d{9}$/);
					var isMobile = regMobile.test(mobile);
					//var regEmail = new RegExp(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/);
					//var isEmail = regEmail.test(email);
					var isCard = (/^\d{16}$/).test(card);

					if(! isMobile)
					{
						layer.open({
							btn:['确定'],
							content: '手机号码格式不正确！',
						});
						return false;
					}
					// if(! isEmail)
					// {
					// 	layer.open({
					// 	btn:['确定'],
					// 	content: '邮箱格式不正确！',
					// 	});
					// 	return false;
					// }

					if(card != "")
					{
						if(! isCard)
						{
							layer.open({
							btn:['确定'],
							content: '会员卡格式空 必须为16为数字！',
							});
							return false;
						}
					}


					var params = $.param({

						qt: '/user/account/register',
						must: JSON.stringify({"username":username, "pass":password, "repass":repeat_password, "mobile": mobile, }), //"verify": mobile_verify
						fields: JSON.stringify({"cardno":card,'invite_code':inviter_code}),
					});

					$.ajax({
						url: reqPath,
						data: params,
						beforeSend: function(){
				  		layer.open({
							type: 2,
							content: '正在注册...',
						});
				  		},
						complete: function(){
				  			setTimeout( function(){ layer.closeAll();}, 1000);
				 		},
						success: function(data){

							if(data.err_no == 0){

								var session_id = $.base64.encode(data.results.sessionid);
								$.cookie('site_login', session_id,{ expires: 7 });
								var username = data.results.username , cardno=data.results.cardno == "" ? 'empty' : data.results.cardno, discount= data.results.discount, avatar=data.results.avatar;
								var user_info =JSON.stringify({username:username, avatar:avatar, discount:discount, cardno:cardno});
								
								$.cookie('user_info',user_info,{ expires: 7 });
								layer.open({
									type: 2,
									content: '注册成功!正在跳转...',
									time: 2,
									end: function(){
										window.location.href = siteUrl+"/page/profile.php";
									}
								})

							}else {
								var err_str = '';
								switch(data.err_no){

									case 3001:
									err_str = '用户名不合法';
									break;
									case 3002:

									err_str = '手机号码不合法';
									break;
									case 3003:

									err_str = '登陆密码不合法';
									break;
									case 3004:
									err_str = '两次输入的密码不匹配';
									break;
									case 3005:
									err_str = '用户名已经存在';
									break;
									case 3006:
									err_str = '手机号已经存在';
									break;
									default:
									err_str = '未知错误';
									break;
								}

								layer.open({
									btn: ['确定'],
									content: err_str,
								});
							}
						},

					});
				}
				
			}); 
			// 发送验证码按钮
			$("#btn-verify").click(function(){
				var username = $("#username").val().trim();
				var mobile = $("#mobile").val().trim();
				var regMobile = new RegExp(/^1[3578]+\d{9}$/);
				var isMobile = regMobile.test(mobile);

				if(! isMobile)
				{
						layer.open({
							btn:['确定'],
							content: '手机号码格式不正确！',
						});
						return false;
				}

				var params = $.param({
					qt: '/common/sms/send',
					must: JSON.stringify({"mobile":mobile}),
				});

				$.ajax({
					data: params,
					async: false,
					beforeSend: function(){
						$("#verify-text").text("正在发送");
					},
					complete: function(){
						$("#verify-text").text("发送完成");
					},
					success: function(data){

						if(data.err_no == 0)
						{
							$("#verify-text").text("发送成功");
							$("#btn-verify").addClass("pointer");
							setTimeout(function(){$("#btn-verify").removeClass("pointer")},180000);
						}
					}
				});
			}); 
		},
		//cart 购物车===================================================================================================

		cart: function(){

			if(typeof($.cookie("site_login")) == 'undefined' || $.cookie("site_login") == 'null' || typeof($.cookie("user_info")) == 'undefined' || $.cookie("user_info") == 'null'){

				layer.open({
					content: '您还未登录，现在带您去登录！',
					time: 1,
					type: 2,
					end: function(){
						
						window.location.href = siteUrl+"/page/login.php";
					}
				});
			} else{

				var site_id = $.cookie('site_id');

				var sessionId = $.base64.decode($.cookie("site_login"));
				var params = $.param({
					qt : '/cart/get',
					must: JSON.stringify({"sessionid":sessionId}),
					});

				$.ajax({

					data: params,
					beforeSend:function(){

						layer.open({

							type:2,
							content:'正在检查购物车！',
						});
					},
					complete:function(){

						setTimeout(function(){ layer.closeAll()},1000);
					},
					success: function(data){


						if(data.err_no == 0){

					
							var tpl = $("#cart-temp").html();
							var results = data.results;
							laytpl(tpl).render(results, function(html){
								$("#cart-box").html(html);
							});

						}
						else{


						}

					}
				});

				//修改数量
					//减少数量
				$("#cart-box").on('click','.sub-amount',function(){

					var amount = parseInt($(this).next('.goods-amount').val());
					var cart_amount = parseInt($(".cart-buy-amount label").text());
					amount -= 1;
					cart_amount -= 1;

					if(amount < 0)
					{
						layer.open({

							content: '您是准备买负数商品，还是咋地？',
							btn: ['不逗你了！'],
						});
					}
					else
					{
						$(this).next('.goods-amount').val(amount);
						$(".cart-buy-amount label").text(cart_amount);
					}
				});

				//增加数量

				$("#cart-box").on('click','.add-amount',function(){

					var amount = parseInt($(this).prev('.goods-amount').val());
					var price = parseFloat($(this).data('product-price'));
					var cart_amount = parseInt($(".cart-buy-amount label").text());

					cart_amount += 1;
					amount += 1;
			
					$(this).prev('.goods-amount').val(amount);
					$(".cart-buy-amount label").text(cart_amount);
				});


				//删除商品
				$("#cart-box").on('click','.del-product',function(){

					var product_id = $(this).data('product-id');
					var site_login = $.cookie('site_login');
					var session_id = $.base64.decode(site_login);
					var params = $.param({

						qt : '/cart/remove',
						must:  JSON.stringify({"sessionid":session_id}),
						fields: JSON.stringify({"products":product_id}),
					});

					layer.open({

						content:'确定删除？',

						btn: ['确定','取消'],
						yes: function(){

							$.ajax({

								data: params,
								async: false,
								success: function(data){


									if (data.err_no == 0) {

										layer.open({

											content:'删除成功！',
											time:1,
											end: function(){

												window.location.reload();
											}
										})
									};
								}
							});
						}
					});

				});
				
				//确认订单 (同步订单)

				$("#cart-box").on('click',"#firm-order",function(){
					var site_login = $.cookie('site_login');
					var session_id = $.base64.decode(site_login);
					var arr_product = new Array();
					$(".goods-amount").each(function(i,val){
						var regtext = /^\d+$/;
						var amount = $(this).val();
						if(amount < 1 || !$.isNumeric(amount) || !regtext.test(amount))
						{
							layer.open({
								content: '某个给买数量似乎有问题！',
								btn: ['确定'],
							});
							return false;
						}
						var product_id = $(this).data('product-id');

						arr_product[i] = {"product_id":product_id,"amount":amount};
					});

					var params = $.param({

						qt: '/cart/sync',
						must: JSON.stringify({"products": JSON.stringify(arr_product),
											  "sessionid":session_id}),
					});

					$.ajax({

						data: params,
						success:function(data){

							var price = data.results.price;
							var amount = data.results.amount;

							var req_parame
							if(data.err_no == 0){

								layer.open({
									type:2,
									content:'提交成功,正在跳转！',
									time:1,
									end:function(){

										window.location.href = siteUrl + '/page/pay.php';
									}
								});
							}
						},
					});
				});
			}

		},
		//支付订单===================================================================================

		pay:function(){

			if(typeof($.cookie("site_login")) == 'undefined' || $.cookie("site_login") == 'null' || typeof($.cookie("user_info")) == 'undefined' || $.cookie("user_info") == 'null'){

				layer.open({
					content: '您还未登录，现在带您去登录！',
					time: 1,
					type: 2,
					end: function(){
						
						window.location.href = siteUrl+"/page/login.php";
					}
				});
			} else{

				var sessionId = $.base64.decode($.cookie("site_login"));
				
				//获取用户地址
				var params = $.param({
					qt: '/user/address/lists',
					must: JSON.stringify({"sessionid":sessionId}),
				});

				$.ajax({

					data: params,
					success: function(data){
						var tpl = $("#address-temp").html();
						var results = data.results;
						laytpl(tpl).render(results, function(html){
							$("#address-box").html(html);
						});
					}
				});

				//获取购物车列表

				var cart_params = $.param({

					qt : '/cart/get',
					must: JSON.stringify({'sessionid':sessionId}),
				});

				$.ajax({

					data: cart_params,
					success: function(data){

						var tpl = $("#product-temp").html();

						var results = data.results;
						laytpl(tpl).render(results, function(html){
							$("#product-box").html(html);
						})
					}
				});

				//获取优惠券列表
				var cart_params = $.param({

					qt : '/user/coupon/lists',
					must: JSON.stringify({'sessionid':sessionId,
						'status':'1',
					}),
					fields: JSON.stringify({
						'type':'1',
					}),
				});

				$.ajax({

					data: cart_params,
					success: function(data){

						var tpl = $("#coupon-tpl").html();

						var results = data.results;
						laytpl(tpl).render(results, function(html){
							$("#coupon-box").html(html);
						})
					}
				});

				//后获取网点信息
				var site_id = $.cookie('site_id');
				var store_params = $.param({
					qt:'/common/data/site',
				});

				$.ajax({

					data: store_params,
					success: function(data){

						if(data.err_no == 0){

							var results = data.results;

							for(var i in results)
							{
								if( results[i].id == site_id )
								{	

									var store_result = results[i]; 
									var tpl = $("#store-tpl").html();
									laytpl(tpl).render(store_result, function(html){
										$("#store-box").html(html);
									})
								}
								
							}
								
							
						}
					}
				});

			// 收货方式改变

				$(".pay-delivery_type input[type='radio']").change(function(){


					if( $(this).val() == 0)
					{
						$(".pay-store").slideDown();
					}
					else
					{
						$(".pay-store").slideUp();
					}
				});

			if($(".pay-delivery_type:checked").val() == 0)
			{
				$(".pay-store").slideDown();
			}


				//支付按钮
			$("#pay-btn").click(function(){


				var arr_product = new Array();

				$("#product-box tbody tr").each(function(i,val){

						var amount = $.trim($(this).children('.product-amount').text());
						var product_id = $(this).data('product-id');

						arr_product[i] = {"product_id":product_id,"amount":amount};
					});

				var address = $(".pay-address-list:checked").val();

				if(typeof(address) == 'undefined')
				{
					layer.open({

					content:'请选择收货地址',
					btn:['确定']
					});
					return false;
				}


				var delivery_type = $(".pay-delivery_type:checked").val();

				var store_id = 0;
				if(delivery_type == 0)
				{

					var store_id = $(".store-list:checked").val();

					if(typeof(store_id) == 'undefined')
					{

						layer.open({

							content:'请选择"就近门店"',
							btn:['确定']
						});
						return false;
					}
				}

				var pay_type = $(".pay-type:checked").val();

				var receipt_datetime = $(".receipt_datetime:checked").val();
				var coupon = $(".coupon-list:checked").val();



				var pay_parames = $.param({


					qt:'/order/add',
					must: JSON.stringify({
						sessionid: sessionId,
						products: JSON.stringify(arr_product),
						site_id: site_id,
						pay_type: pay_type,
						delivery_type: delivery_type,
						store_id:store_id,
						address_id: address,
					}),
					fields: JSON.stringify({
						cash_id:coupon,
						date_type:receipt_datetime,
					}),
				});


				$.ajax({

					data: pay_parames,
					async: false,
					success: function(data){

						if(data.err_no == 0){

							var order_id = data.results.id;

							layer.open({

								content: '正在跳转到确认页面！',
								time:2,
								end:function(){
									window.location.href= siteUrl +"/page/pay_show.php?order_id="+ $.base64.encode(order_id);
								},

							});
						}
						else{

							var err_var = '';

							switch(data.err_no){


								case 3003:
								err_var = '产品不能为空！';
								break;
								default:
								err_var = '未知错误！';
								break;
							}


							layer.open({

								content: err_var,
								btn:['确定'],
							});
							return false;
						}
					}
				});
			});

			}

		},

		//address 地址列表=====================================================================================

		address: function(){

			if(typeof($.cookie("site_login")) == 'undefined' || $.cookie("site_login") == 'null' || typeof($.cookie("user_info")) == 'undefined' || $.cookie("user_info") == 'null'){

				layer.open({
					content: '您还未登录，现在带您去登录！',
					time: 1,
					type: 2,
					end: function(){
						
						window.location.href = siteUrl+"/page/login.php";
					}
				});
			} else {

						var sessionId = $.base64.decode($.cookie("site_login"));

						var address_params = $.param({
							qt:'/user/address/lists',
							must: JSON.stringify({'sessionid':sessionId}),
						});

						$.ajax({

							data: address_params,
							success: function(data){

								if(data.err_no == 0){

									var tpl = $("#address-temp").html();
									var results = data.results;
									laytpl(tpl).render(results, function(html){
										$("#address-box").html(html);
									});
								} 
							}
						});




					}
		},

		//add-address 添加地址============================================================================================
	
		address_add: function(){

			var params = {
				qt:'/common/data/area',
			}
			$.ajax({

				data: params,
				async: false,
				success: function(data){

					if(data.err_no == 0)
					{
						var province = data.results.prov;
						var city = data.results.city;
						var district = data.results.areas.district;
						var street = data.results.areas.street;
						
						$("#address-add-box-province").append("<option value='"+ province.id +"'>"+ province.name +"</option>");
					
						$("#address-add-box-city").append("<option value='"+ city.id +"'>"+ city.name +"</option>");
						
						for(var x in district) {

							$("#address-add-box-area").append("<option value='"+ district[x].id +"'>"+ district[x].name +"</option>");
						}

						for(var x in street['320102']) {
							$("#address-add-box-street").append("<option value='"+ street['320102'][x].id +"'>"+ street['320102'][x].name +"</option>");

						}

						// 改变下拉框值
						$("#address-add-box-area").change(function(){

							var district_id = $(this).val();
							$("#address-add-box-street").empty();
							for(var x in street[district_id]) {
	
								$("#address-add-box-street").append("<option value='"+ street[district_id][x].id +"'>"+ street[district_id][x].name +"</option>");
							}

						});
					}
					
				}
			});

			//添加地址
			$("#address-add-btn").click(function(){
				var sessionId = $.base64.decode($.cookie("site_login"));

				var is_default = $(".address-add-box-default:checked").val();
				var prov = $("#address-add-box-province").val(), city=$("#address-add-box-city").val(),district = $("#address-add-box-area").val(),street = $("#address-add-box-street").val();
				
				var address = $(".address-add-box-address").val();
				if(address == '')
				{
					layer.open({
						content: '地址不能为空！',
						btn:['确定'],
					});
					return false;
				}
				var postcode = $(".address-add-box-postcode").val();
				if(postcode == '')
				{
					layer.open({
						content: '邮编不能为空！',
						btn:['确定'],
					});
					return false;
				}
				var name = $(".address-add-box-name").val();
				if(name == '')
				{
					layer.open({
						content: '收货人不能为空！',
						btn:['确定'],
					});
					return false;
				}
				var tel = $(".address-add-box-tel").val(), mobile = $(".address-add-box-mobile").val();

				if(tel == '' && mobile == '')
				{
					layer.open({
						content: '座机和手机号不能都为空！',
						btn:['确定'],
					});
					return false;
				}

				var params = $.param({

					qt: '/user/address/add',
					must: JSON.stringify({
						sessionid: sessionId,
						is_default: is_default,
						prov: prov,
						city: city,
						district: district,
						street: street,
						zip: postcode,
						address: address,
						receiver: name,

					}),
					fields: JSON.stringify({
						tel: tel,
						mobile: mobile,
					}),
				});

				$.ajax({

					data: params,
					async: false,
					success:function(data){

						if(data.err_no == 0){

							layer.open({

								content: '添加成功，正在跳转！',
								time:2,
								end: function(){
                                    
									window.history.go(-1);
								},
							});
						}
						else
						{
							var err_var = '';

							switch(data.err_no){

								case 3001:
								err_var = '用户未登录';
								break
								case 3002:
								err_var = '该用户已经包含10个地址';
								break;
								case 3003:
								err_var = 'tel和mobile不能都为空';
								break;
								default:
								err_var='未知错误';
								break;
							}

							layer.open({

								content: err_var,
								btn: ['确定'],
							});
						}
					}
				});

			});

		},

		//cash 收银===================================================================================

		cash: function(){

			if(typeof($.cookie("site_login")) == 'undefined' || $.cookie("site_login") == 'null' || typeof($.cookie("user_info")) == 'undefined' || $.cookie("user_info") == 'null'){

				layer.open({
					content: '您还未登录，现在带您去登录！',
					time: 1,
					type: 2,
					end: function(){
						
						window.location.href = siteUrl+"/page/login.php";
					}
				});
			} else {

				var sessionId = $.base64.decode($.cookie("site_login"));
				var order_id =$.base64.decode( $(".cash").data('order-id'));
				var order_price = 0;
				var order_info = new Array();
				var order_params = $.param({

					qt:'/user/order/detail',
					must: JSON.stringify({
						'sessionid': sessionId,
						'order_id': order_id,
					}),
				});

				$.ajax({

					data: order_params,
					success:function(data){

						if(data.err_no == 0)
						{

							order_price = parseFloat(data.results.price);

							var pay_type = data.results.pay_type;
							if( pay_type == 3)
							{
								$(".card-box").slideDown();

								
								var user_info = JSON.parse($.cookie("user_info"));
								var card_number = $.trim(user_info['cardno']);
								if(card_number != "")
								{
							
									$("#card_number").val(card_number);
								}
							}
							else if( pay_type == 2)
							{

								$.cookie('teade_price',$.base64.encode(String(order_price)));
								$("#alipay-form input[name='trade_no']").val(order_id);
								$("#alipay-form input[name='total_fee']").val(order_price);
								$(".alipay-box").slideDown();
							}
							else if( pay_type ==1 )
							{
								$(".common-box").slideDown("slow");
							}

							// var tpl = $("#cash-tpl").html();
							// var results = data.results;
							// laytpl(tpl).render(results, function(html){
							// 	$("#cash-box").html(html);
							// });
						}
					},
					beforeSend: function(){

						layer.open({
							type:2,
							content: '正在获取订单信息！',
						});
					},
					complete: function(){

						setTimeout(function(){ layer.closeAll(); },1000);
					},

				});
				
				//会员卡支付按钮

				$("#card-btn").click(function(){

					var card_number = $.trim($("#card_number").val()), card_password = $.trim($("#card_password").val());

					if(card_number == '')
					{
						layer.open({

							content: '卡号，您是不是要填一下！',
							btn:['确定'],
						});
						return false;
					}
					if(card_password == '')
					{
						layer.open({

							content: '密码，您是不是也填一下！',
							btn:['确定'],
						});
						return false;
					}

					card_password = $.base64.encode(card_password);
					var card_params = $.param({
						qt:'/pay/yeepay_card',
						must:JSON.stringify({

							order_id: order_id,
							card_num: card_number,
							card_pass: card_password,
							pay_price: order_price,
						}),
					});


					$.ajax({

						data: card_params,
						async: false,
						success: function(data){

							if(data.err_no  == 0){

								$(".card-box").slideUp('fast',function(){

									$(".cash-result label").text("支付成功！");
									$(".common-box").slideDown("slow");
								});
							
							}
							else
							{
								layer.open({

									content:'支付失败！\t'+ data.err_msg,
									btn: ['确定'],
								});
							}
						},

					});
				});
			

				$("#alipay-btn").click(function(){

					$("#alipay-form").submit(function(e){

						return true;
						alert("111111111");
					});
				});	
			}

		},

		// order 订单列表==============================================================================================
		order: function(){

			if(typeof($.cookie("site_login")) == 'undefined' || $.cookie("site_login") == 'null' || typeof($.cookie("user_info")) == 'undefined' || $.cookie("user_info") == 'null'){

				layer.open({
					content: '您还未登录，现在带您去登录！',
					time: 1,
					type: 2,
					end: function(){
						
						window.location.href = siteUrl+"/page/login.php";
					}
				});
			} else {
				var sessionId = $.base64.decode($.cookie("site_login"));
				var status_id =$(".order").data('status-id');
				var arr_order = new Array();
				var page = $(".order").data('page_num');
				var params = $.param({

					qt:'/user/order/lists',
					must: JSON.stringify({
						sessionid: sessionId,
					}),
					fields: JSON.stringify({
						page:page,
						size:10,
						pay_status:status_id,
					}),
				});

				$.ajax({

					data: params,
					success: function(data){

						if( data.err_no == 0)
						{	

							var order_tpl = $("#order-tpl").html();
							var order_results = data.results.list;
							laytpl(order_tpl).render(order_results, function(html){
								$("#order-box").html(html);
							});

							var page_tpl = $("#pager-tpl").html();
							var page_results = data.results.pager;
							laytpl(page_tpl).render(page_results, function(html){
								$("#pager-box").html(html);
							});

						}


					}
				});

				//订单分页	
			$(".pager").on('click','a',function(){

				var page_num = $(this).data('page-num'), pay_status = $(".order").data('status-id');
				
				window.location.href = siteUrl + "/page/order.php?pay_status="+ pay_status +"&page="+ page_num;
			});	

				
			}
		},
		//收藏
		fav_list: function(){

			if(typeof($.cookie('site_login')) == 'undefined' || $.cookie('site_login') == 'null' ){

					layer.open({
					type: 2,
					content: '您还未登录，现在带您去登录！',
					time: 1,
					end: function(){
						window.location.href = siteUrl + "/page/login.php";
					}
					});	
				}else{
						var sessionId = $.base64.decode($.cookie("site_login")),
							page_num = $(".fav_list").data('page-num');

						var fav_params = $.param({

							qt:'/user/fav/lists',
							must: JSON.stringify({
								sessionid : sessionId,
							}),
							fields: JSON.stringify({
								page: page_num,
								size: 9,
							}),
						});

						$.ajax({

							data: fav_params,
							success: function(data){



								if(data.err_no == 0)
								{

									var fav_list_tpl = $("#fav_list-tpl").html();
									var fav_list_results = data.results.list;
									laytpl(fav_list_tpl).render(fav_list_results, function(html){
										$("#fav_list-box").html(html);
									});
								
									var page_tpl = $("#pager-tpl").html();
									var page_results = data.results.pager;
									laytpl(page_tpl).render(page_results, function(html){
										$("#pager-box").html(html);
									});
								}
							
							},
						});
					}

		//收藏商品分页	
		$(".pager").on('click','a',function(){

			var page_num = $(this).data('page-num');
			
			window.location.href = siteUrl + "/page/fav_list.php?page="+ page_num;
		});			

			//删除收藏

			$(".fav_list").on('click',"#del-favor",function(){

				var sessionId = $.base64.decode($.cookie("site_login")),

				product_id = $(this).data('product-id');

				var del_params = $.param({
					qt: '/user/fav/remove',
					must: JSON.stringify({
						sessionid: sessionId,
						id:product_id
					}),
				});

				$.ajax({

					data: del_params,
					async: false,
					success: function(data){


						if(data.err_no == 0)
						{
							layer.open({

								content: '删除收藏成功！',
								type:2,
								time:1,
								end:function(){
									location.reload();
								},
							})
						}
					},

				});
			});
		},
		// 摇一摇
		shake: function(){

			if(window.DeviceMotionEvent) {
				var speed = 25;
				var x = y = z = lastX = lastY = lastZ = 0;
				window.addEventListener('devicemotion', function(){
					var acceleration =event.accelerationIncludingGravity;
					x = acceleration.x;
					y = acceleration.y;
					if(Math.abs(x-lastX) > speed || Math.abs(y-lastY) > speed) {

						var site_id = $.cookie('site_id');

						var search_params = $.param({

							qt: '/product/search/rand',
							must:JSON.stringify({

								'site_id':site_id
							}),
						});



						$.ajax({
							data:search_params,
							success:function(data){


								if(data.err_no == 0)
								{

									$("#search-words").val(data.results.title); 
									$(".shake-img img").attr("src",data.results.thumb)
									$(".shake-desc").empty();
									$(".shake-desc").append(
										"<a data-mold='goods' data-name='"+data.results.title+"' data-id='"+ data.results.id+"' href='javascript:;'> <label class='text-green'>"+data.results.title+"</label></a> [ &yen; "+ data.results.price +" ]");
								}
							}
						});
						
					}
					lastX = x;
					lastY = y;
				}, false);
			}
			else{

				layer.open({
					content:'您的手机不支持“摇动”事件',
					btn:['确定'],
				});
			}			
		},
		//支付宝付款结果
		alipay: function(){

			var pay_static = $(".alipay").data('pay-static');

			if(pay_static == "0")
			{
				$(".well label").text("支付成功！");
				$(".well i").removeClass('icon-roundclosefill');
				$(".well i").addClass('icon-roundcheckfill');
				$(".well").removeClass('text-red');
				$(".well").addClass('text-green');
				$(".well").slideDown();

				var sessionId = $.base64.decode($.cookie("site_login"));
				var date = $(".alipay").data("date");
				var order_id = $(".alipay").data("order-id");
				var teade_no = $(".alipay").data("teade-no");
				var pay_type = 2;


				var teade_price = $.base64.decode($.cookie('teade_price'));

				var teade_params = $.param({
					qt:'/pay/success',
					must: JSON.stringify({
						order_id: order_id,
						pay_type: pay_type,
						pay_price: teade_price,
						pay_no: teade_no,
						pay_time: date,
					}),

				});
			$.ajax({

				data: teade_params,
				async: false,
				success: function(data){
					console.log(data);

				}
			});
				//setTimeout(function(){window.location= siteUrl + "/page/profile.php"},3000);
			}
			else
			{

				$(".well label").text("支付失败！");
				$(".well").slideDown();
			}
		},

		//点单确认
		pay_show:function(){


			var order_id = $.base64.decode($(".pay_show").data('order-id'));

			var sessionId = $.base64.decode($.cookie("site_login"));


			var params = $.param({
				qt:'/user/order/detail',
				must: JSON.stringify({
					sessionid : sessionId,
					order_id:order_id,
				}),
			});


			$.ajax({
				data: params,
				success: function(data){

					if(data.err_no == 0){
					var page_tpl = $("#pay_show-tpl").html();
					var page_results = data.results;
					laytpl(page_tpl).render(page_results, function(html){
						$("#pay_show-box").html(html);
					});
				}
				}
			});


			$("#btn-cancel").click(function(){

				layer.open({

					content: '确定取消？',
					btn:['是','否'],
					yes: function(){


						var params = $.param({

							qt:'/user/order/cancel',
							must: JSON.stringify({
								order_id: order_id,
								sessionid : sessionId,
							}),
						});


						$.ajax({
							data: params,
							success: function(data){

								if(data.err_no == 0)
								{

								}
								else
								{
									var err_var = '';

									switch(data.err_no)
									{
										case 3001:
										err_var = '用户未登录';
										break;
										case 3002:
										err_var = '订单不存在';
										break;
										case 3003:
										err_var = '订单用户所属不匹配';
										break;
										case 3004:
										err_var = '订单不允许被取消';
										break;
										default:
										err_var = '未知错误';
										break;
									}

									layer.open({


										content: err_var,
										btn:['确定'],
									});
								}
							}
						});
					}
				});
			});
		},
        
        //摇一摇零元购
        shake_free: function(){
            
            $("#shake_btn").click(function(){
                
                shake_free_fun();
            });
            
            function shake_free_fun()
            {
            
                var before = $(".shake_free-audio-before")[0];
                var end = $(".shake_free-audio-end")[0];
             var free_params = $.param({

                            qt:'/product/promotion/free',
                        });

                        $.ajax({
                            data:free_params,
                            beforeSend: function(){
                                

                                before.play();

                                
                            },
                            success:function(data){

                               if(data.err_no == 0)
                               {
                                   
                                   $(".shake_free-title").text(data.results.title);
                                   $(".shake_free-image img").attr({src:data.results.thumb, alt:data.results.des});
                                   $(".shake_free-cart-btn").data("goods-id", data.results.id);
                                    $(".shake_free-cart").slideDown();
                                   
                               }
                            },
                            complete: function(){
                                
                                setTimeout(function(){
                                    before.pause();
                                    end.play();
                                },1000);
                                
                            }
                        });
            }
            
            if(window.DeviceMotionEvent) {
                var speed = 25;
                var x = y = z = lastX = lastY = lastZ = 0;
                window.addEventListener('devicemotion', function(){
                    var acceleration =event.accelerationIncludingGravity;
                    x = acceleration.x;
                    y = acceleration.y;
                    if(Math.abs(x-lastX) > speed || Math.abs(y-lastY) > speed) {

                        shake_free_fun();

                    }
                    lastX = x;
                    lastY = y;
                }, false);
            }
            else{

                layer.open({
                    content:'您的手机不支持“摇动”事件',
                    btn:['确定'],
                });
            }
            
            $(".shake_free-cart-btn").on('click',function(even){


				if(typeof($.cookie('site_login')) == 'undefined' || $.cookie('site_login') == 'null' || typeof($.cookie('user_info')) == 'undefined' || $.cookie('user_info') == 'null' ){

					layer.open({

						content: '未登录，是否登录？',
						btn: ['登录','取消'],
						yes: function(){

							window.location.href = siteUrl + "/page/login.php";
						},
					});

				}else{

				 var sessionId =$.base64.decode($.cookie("site_login"));
				 var goodsId = $(this).data("goods-id");

				 var params = $.param({
				 	qt: '/cart/add',
				 	must:JSON.stringify({
				 		sessionid : sessionId,
				 		product_id: goodsId,
				 		amount	: 1,
				 	}),
				 });



				 $.ajax({
				 	data: params,
				 	async: false,

				 	success: function(data){

				 		if(data.err_no == 0){

				 			layer.open({
								content: '成功加入成功！',
								btn:['结账?','继续购物'],
								yes: function(){

									window.location.href = siteUrl +"/page/cart.php";
								},	
							});
				 		}
				 		else
				 		{

				 			var err_var = '';

									switch(data.err_no){

										case 3001:
										err_var = '您未登录!'
										break;
										case 3002:
										err_var = '产品不存在！';
										break;
										default:
										err_var = '未知错误！';
										break;
									}

									layer.open({

										btn:['确定'],
										content: err_var,
									});
				 		}
				 	}
                 });
                }
            })
        },
 		// getup
 		special: function(){

 			var special_id = $(".special").data('special-id');

 			special_id = special_id !=  'undefined' ? special_id : 0;

 			var params = $.param({
 				qt: '/product/special/detail',
 				must: JSON.stringify({
 					'special_id':special_id,
 				}),
 			});
 			$.ajax({

 				data: params,
 				success: function(data){
 					if(data.err_no == 0){
                    
                    $(document).attr('title',data.results.name + site_name);
                        
					var page_tpl = $("#special-tpl").html();
					var page_results = data.results;
					laytpl(page_tpl).render(page_results, function(html){
						$("#special-box").html(html);
					});
				}
 				},
 			});

 		//getup.End
 		},

 		app: function(){
            
            
            var ua = navigator.userAgent.toLowerCase();
            var weixin_ios = "http://mp.weixin.qq.com/mp/redirect?url=" + $("#ios-app").attr('href');
            var weixin_android = "http://mp.weixin.qq.com/mp/redirect?url=http://m.100hl.com/page/app.php";
            
            if(/micromessenger/.test(ua))
            {  
                    $("#ios-app").attr('href',weixin_ios );
             
                    $("#android-app").attr('href',weixin_android );
            }

            $("#android-app").click(function(){

            	//if(/micromessenger/.test(ua)){
            		layer.open({
            			content: "由于微信限制跳转，推荐您使用系统浏览器打开本页面\n http://m.100hl.com！",
            			btn:['好的'],
            		});
            		return false;
            	//}
            	
            })

 			//app.End
 		},
        invite: function(){
            
            
            $("#btn-invite").on('click',function(){
                
                var invite_code = $("#text-invite").val();
                
                if(invite_code == "")
                {
                    layer.open({
                        
                        content: '邀请码不能为空!',
                        btn: ['确定'],
                    });
                    
                }
                else{
                    
                    $.cookie('site_invite',$.base64.encode(invite_code));

                    if(typeof($.cookie('site_login')) == 'undefined' || $.cookie('site_login') == 'null' || typeof($.cookie('user_info')) == 'undefined' || $.cookie('user_info') == 'null' ){

                        layer.open({

                            content: '是否注册？',
                            btn: ['注册','取消'],
                            yes: function(){

                                window.location.href = siteUrl + "/page/register.php";
                            },
                        });

                    }else{

                        layer.open({

                            content: '只有新用户才能参与！',
                            btn: ['登录'],
                        });
                    }
                }
            });
            //invite.End
        }
	}
}();