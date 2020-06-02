<?php defined('InclinicNC') or exit('Access Invalid!');?>
<link
	href="<?php echo RESOURCE_SITE_URL;?>/js/jcarousel/skins/tango/skin.css"
	rel="stylesheet" type="text/css">
<style type="text/css">
.jcarousel-skin-tango .jcarousel-clip-horizontal {
	width: 424px !important;
	height: 96px !important;
}

.jcarousel-skin-tango .jcarousel-item {
	width: 96px !important;
	height: 96px !important;
}

.jcarousel-skin-tango .jcarousel-container-horizontal {
	width: 424px !important;
}
</style>
<div class="choose-doctors">
	<ul class="tabs-nav">
		<li class="tabs-selected"><a href="javascript:void(0);"><?php echo $lang['circle_doctors_link'];?></a></li>
		<li><a href="javascript:void(0)"><?php echo $lang['circle_bought_doctors'];?></a></li>
		<li><a href="javascript:void(0)"><?php echo $lang['circle_favorite_doctors'];?></a></li>
	</ul>
	<div class="doctors-api tabs-panel">
		<label><i></i><?php echo $lang['circle_doctors_link_tips1'];if(C('taobao_api_isuse')){echo $lang['circle_doctors_link_tips2'];}echo $lang['circle_doctors_link_tips3'];?></label>
		<p>
			<input type="text" id="doctors_link" class="text w400" /> <a
				href="javascript:void(0);" nctype="doctorsLinkAdd"><?php echo $lang['nc_add'];?></a>
		</p>
	</div>
	<div class="tabs-panel tabs-hide">
		<!-- 商品图片start -->
    <?php if (!empty($output['appointment_doctors'])){?>
    <ul id="mycarousel1" class="jcarousel-skin-tango">
      <?php foreach ($output['appointment_doctors'] as $v) {?>
      <li><a href="javascript:void(0);"
				data-param="{'id':'<?php echo $v['doctors_id'];?>','name':'<?php echo $v['doctors_name'];?>','price':'<?php echo $v['doctors_price'];?>','clicid':'<?php echo $v['clic_id'];?>','img':'<?php echo thumb($v, 60);?>','image':'<?php echo $v['doctors_image'];?>','uri':'<?php echo urlclinic('doctors', 'index', array('doctors_id'=>$v['doctors_id']));?>','type':0}">
					<img title="<?php echo $v['doctors_name']?>"
					src="<?php echo thumb($v, 240);?>" />
					<p class="extra"><?php echo $lang['circle_selected'];?></p>
			</a></li>
      <?php }?>
    </ul>
    <?php } else{?>
    <div class="norecord"><?php echo $lang['circle_bought_doctors_null'];?></div>
    <?php }?>
  </div>
	<div class="tabs-panel tabs-hide">
		<!-- 商品图片start -->
    <?php if (!empty($output['favorites_doctors'])){?>
    <ul id="mycarousel2" class="snsdoctorsimglist jcarousel-skin-tango">
      <?php foreach ($output['favorites_doctors'] as $v) {?>
      <li><a href="javascript:void(0);"
				data-param="{'id':'<?php echo $v['doctors_id'];?>','name':'<?php echo $v['doctors_name'];?>','price':'<?php echo $v['doctors_price'];?>','clicid':'<?php echo $v['clic_id'];?>','img':'<?php echo thumb($v, 60);?>','image':'<?php echo $v['doctors_image'];?>','uri':'<?php echo urlclinic('doctors', 'index', array('doctors_id'=>$v['doctors_id']));?>','type':0}"><img
					title="<?php echo $v['doctors_name']?>"
					src="<?php echo thumb($v, 240);?>" />
					<p class="extra"><?php echo $lang['circle_selected'];?></p> </a></li>
      <?php }?>
    </ul>
    <?php } else{?>
    <div class="norecord"><?php echo $lang['circle_favorite_doctors_null'];?></div>
    <?php }?>
  </div>
	<div class="" nctype="cgError"></div>
	<dl class="selected-doctors">
		<dt><?php echo $lang['circle_selected_doctors'];?></dt>
	</dl>
	<div class="handle-bar">
		<a href="Javascript: void(0)" class="button" nctype="insertdoctors"><?php echo $lang['circle_insert_theme'];?></a>
	</div>
</div>
<script
	src="<?php echo RESOURCE_SITE_URL;?>/js/jcarousel/jquery.jcarousel.min.js"></script>
<script>
var insertAllowedCount = <?php echo intval($_GET['count']);?>;		// 允许插入商品数量
$(function(){
    
	//帖子列表隔行变色
	$(".group-theme-list li:odd").css("background-color","#F8F9FA");
	$(".group-theme-list li:even").css("background-color","#FCFCFC");

	//侧边栏tab切换
	$(".tabs-nav > li > a").click(function(e) {
		var tabs = $(this).parent().parent().children("li");
		var panels = $(this).parent().parent().parent().children(".tabs-panel");
		var index = $.inArray(this, $(this).parent().parent().find("a"));
		tabs.removeClass("tabs-selected")
			.eq(index).addClass("tabs-selected");
		panels.addClass("tabs-hide")
			.eq(index).removeClass("tabs-hide");
		if(index != 0){
			//图片轮换
			$('#mycarousel'+index).unbind().jcarousel({visible: 4,itemFallbackDimension: 300});
		}
	}); 

	// 选项商品事件触发
	$('.tabs-panel').find('li > a').click(function(){
		choosedoctors($(this));
	});


	// According to the doc link to add doctors
	$('a[nctype="doctorsLinkAdd"]').click(function(){

		// Validation choose doctors quantity
		var count = insertAllowedCount - $('.selected-doctors > dd').length;
		if(count <= 0){
			// 这里怎么加提示？ 不能在继续选择商品
			$('div[nctype="cgError"]').html('<span><?php echo $lang['circle_doctors_error1'];?></span>');
    		window.setTimeout(hideError,5000);	// 5 seconds after the hidden message
			return false;
		}
		
		var link = encodeURIComponent($('#doctors_link').val());
		var _uri = CIRCLE_SITE_URL+'/index.php?act=theme&op=check_link&link='+link+'&c_id='+c_id;
		$.getJSON(_uri, function(data){
			if (data){
				$('<dd data-param="{\'id\':\''+data.id+'\',\'name\':\''+data.title+'\',\'price\':\''+data.price+'\',\'clicid\':\''+data.clicid+'\',\'image\':\''+data.img+'\',\'img\':\''+data.image+'\',\'uri\':\''+data.url+'\',\'type\':\''+data.type+'\'}"><em></em><p><img src="'+data.image+'" /></p></dd>')
					.appendTo('.selected-doctors').click( function(){ $(this).remove(); });
				return;
            }else{
        		// 这里怎么加提示？ 不能在继续选择商品
        		$('div[nctype="cgError"]').html('<span><?php echo $lang['circle_doctors_error2'];?></span>');
        		window.setTimeout(hideError,5000);	// 5 seconds after the hidden message
        		return;
            }
		});
	});

});
// 选择商品js
function choosedoctors(o){
	// 验证选择商品数量
	var count = insertAllowedCount - $('.selected-doctors > dd').length;
	if(count <= 0){
		// 这里怎么加提示？ 不能在继续选择商品
		$('div[nctype="cgError"]').html('<?php echo $lang['circle_doctors_error1'];?>');
		return false;
	}
	var str = o.attr('data-param'); var data_str; eval( "data_str = " + str);
	$('<dd data-param="'+str+'"><em></em><p><img src="'+data_str.img+'" /></p></dd>').appendTo('.selected-doctors')
		.click(function(){
			$(this).remove();
			o.removeClass('selected').click(function(){
				choosedoctors($(this));
			});
		});
	o.addClass('selected').unbind();

}
// Hide the error message
function hideError(){
	$('div[nctype="cgError"]').hide("slow", function(){
		$(this).html('').show();
	});
}
</script>