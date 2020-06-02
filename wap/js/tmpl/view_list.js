$(function(){
	var doctors = getcookie('doctors');
	var doctors_info = doctors.split('@');
	
	if(doctors_info.length>0){
		for(var i=0;i<doctors_info.length;i++){
			AddViewdoctors(doctors_info[i]);
		}
	}else{
		var html = '<li>没有符合条件的记录</li>';
		$('#viewlist').append(html);
	}	
});

function AddViewdoctors(doctors_id){
	$.ajax({
		type:'get',
		url:ApiUrl+'/index.php?act=doctors&op=doctors_detail&doctors_id='+doctors_id,
		dataType:'json',
		success:function(result){
			var pic = result.datas.doctors_image.split(',');
			var html = '<li>'
						+'<a href="'+WapSiteUrl+'/tmpl/doc_detail.html?doctors_id='+result.datas.doctors_info.doctors_id+'" class="mf-item clearfix">'
							+'<span class="mf-pic">'
								+'<img src="'+pic[0]+'"/>'
							+'</span>'
							+'<div class="mf-infor">'
							+'<p class="mf-pd-name">'+result.datas.doctors_info.doctors_name+'</p>'
							+'<p class="mf-pd-price">$'+result.datas.doctors_info.doctors_price+'</p></div>';
						+'</a></li>';
			$('#viewlist').append(html);
		}
	});
}