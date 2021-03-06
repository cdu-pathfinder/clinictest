$(function(){
	var key = getcookie('key');
	if(key==''){
		window.location.href = WapSiteUrl+'/tmpl/member/login.html';
	}
	var page = pagesize;
	var curpage = 1;
	var hasMore = true;
	function initPage(page,curpage){
		$.ajax({
			type:'post',
			url:ApiUrl+"/index.php?act=member_appointment&op=appointment_list&page="+page+"&curpage="+curpage,	
			data:{key:key},
			dataType:'json',
			success:function(result){
				checklogin(result.login);//检测是否login了
				var data = result.datas;
				data.hasmore = result.hasmore;//是不是可以用next page的功能，传到页面里去判断next page是否可以用
				data.WapSiteUrl = WapSiteUrl;//页面地址
				data.curpage = curpage;//当前页，判断是否previous page的disabled是否显示
				data.ApiUrl = ApiUrl;
				data.key = getcookie('key');
				template.helper('$getLocalTime', function (nS) {
					return new Date(parseInt(nS) * 1000).toLocaleString().replace(/:\d{1,2}$/,' ');  
				});
				var html = template.render('appointment-list-tmpl', data);
				$("#appointment-list").html(html);
				//取消订单
				$(".cancel-appointment").click(cancelappointment);
				//next page
				$(".next-page").click(nextPage);
				//previous page
				$(".pre-page").click(prePage);
				//确认订单
				$(".sure-appointment").click(sureappointment);
			}
		});
	}
	//初始化页面
	initPage(page,curpage);
	//取消订单
	function cancelappointment(){
		var self = $(this);
		var appointment_id = self.attr("appointment_id");
		$.ajax({
			type:"post",
			url:ApiUrl+"/index.php?act=member_appointment&op=appointment_cancel",
			data:{appointment_id:appointment_id,key:key},
			dataType:"json",
			success:function(result){
				if(result.datas && result.datas == 1){
					initPage(page,curpage);
				}
			}
		});
	}
	//next page
	function nextPage (){
		var self = $(this);
		var hasMore = self.attr("has_more");
		if(hasMore == "true"){
			curpage = curpage+1;
			initPage(page,curpage);
		}
	}
	//previous page
	function prePage (){
		var self = $(this);
		if(curpage >1){
			self.removeClass("disabled");
			curpage = curpage-1;
			initPage(page,curpage);
		}
	}
	//确认订单
	function sureappointment(){
		var self = $(this);
		var appointment_id = self.attr("appointment_id");
		$.ajax({
			type:"post",
			url:ApiUrl+"/index.php?act=member_appointment&op=appointment_receive",
			data:{appointment_id:appointment_id,key:key},
			dataType:"json",
			success:function(result){
				if(result.datas && result.datas == 1){
					initPage(page,curpage);
				}
			}
		});
	}
});