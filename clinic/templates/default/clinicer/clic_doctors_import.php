<?php defined('InclinicNC') or exit('Access Invalid!');?>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.ajaxContent.pack.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js"></script>
<script src="<?php echo clinic_RESOURCE_SITE_URL;?>/js/clic_doctors_add.step2.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/swfupload/swfupload.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo RESOURCE_SITE_URL;?>/js/swfupload/js/handlers.js"></script>
<link type="text/css" rel="stylesheet" href="<?php echo RESOURCE_SITE_URL;?>/js/swfupload/css/default.css"/>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<!-- S setp -->
<ul class="add-doctors-step">
  <li class="<?php $output['step']=="1" ? print "current" : print "";?>"><i class="icon icon-list-alt"></i>
    <h6>STIP.1</h6>
    <h2><?php echo $lang['clic_doctors_import_step1'];?></h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li class="<?php $output['step']=="2" ? print "current" : print "";?>"><i class="icon icon-camera-retro "></i>
    <h6>STIP.2</h6>
    <h2><?php echo $lang['clic_doctors_import_step2'];?></h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li class="<?php $output['step']=="3" ? print "current" : print "";?>"><i class="icon icon-ok-circle"></i>
    <h6>STIP.3</h6>
    <h2><?php echo $lang['clic_doctors_import_step3'];?></h2>
  </li>
</ul>
<!--S 分类选择区域-->
<!--S 分类选择区域-->
<div class="alert mt15 mb5"><strong>操作提示：</strong>
  <ul>
    <li><?php echo $lang['clic_doctors_import_csv_desc'];?></li>
  </ul>
</div>
<form method="post" action="index.php?act=taobao_import&op=index" enctype="multipart/form-data" id="doctors_form">
  <div class="ncsc-form-doctors"  <?php if($output['step'] != '1'){?> style="display:none"<?php }?>>
    <dl>
      <dt><i class="required">*</i><?php echo $lang['clic_doctors_album_doctors_pic'].$lang['nc_colon'];?></dt>
      <dd>
        <div class="handle">
        <div class="ncsc-upload-btn"> <a href="javascript:void(0);"><span>
          <input type="file" hidefocus="true" size="15"  name="csv" id="csv">
          </span></a></div>
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['clic_doctors_index_doctors_class'].$lang['nc_colon'];?></dt>
      <dd id="gcategory"> <span nctype="gc1">
        <?php if (!empty($output['gc_list'])) {?>
        <select nctype="gc" data-param="{deep:1}">
          <option><?php echo $lang['nc_please_choose'];?></option>
          <?php foreach ($output['gc_list'] as $val) {?>
          <option value="<?php echo $val['gc_id']?>"><?php echo $val['gc_name'];?></option>
          <?php }?>
        </select>
        <?php }?>
        </span> <span nctype="gc2"></span> <span nctype="gc3"></span>
        <p>请选择商品分类（必须选到最后一级）</p>
        <input type="hidden" id="gc_id" name="gc_id" value="" class="mls_id" />
        <input type="hidden" name="cate_name" value="" class="mls_names"/>
        </dd>
    </dl>
    
    <!--transport info begin-->
    
    <dl>
      <dt><?php echo $lang['clic_doctors_index_doctors_szd'].$lang['nc_colon']?></dt>
      <dd>
        <p id="region">
          <select class="d_inline" name="province_id" id="province_id">
          </select>
        </p>
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['clic_doctors_index_clic_doctors_class'].$lang['nc_colon'];?></dt>
      <dd><span class="new_add"><a href="javascript:void(0)" id="add_sgcategory" class="ncsc-btn"><?php echo $lang['clic_doctors_index_new_class'];?></a> </span>
        <?php if (!empty($output['clic_class_doctors'])) { ?>
        <?php foreach ($output['clic_class_doctors'] as $v) { ?>
        <select name="sgcate_id[]" class="sgcategory">
          <option value="0"><?php echo $lang['nc_please_choose'];?></option>
          <?php foreach ($output['clic_doctors_class'] as $val) { ?>
          <option value="<?php echo $val['stc_id']; ?>" <?php if ($v==$val['stc_id']) { ?>selected="selected"<?php } ?>><?php echo $val['stc_name']; ?></option>
          <?php if (is_array($val['child']) && count($val['child'])>0){?>
          <?php foreach ($val['child'] as $child_val){?>
          <option value="<?php echo $child_val['stc_id']; ?>" <?php if ($v==$child_val['stc_id']) { ?>selected="selected"<?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child_val['stc_name']; ?></option>
          <?php }?>
          <?php }?>
          <?php } ?>
        </select>
        <?php } ?>
        <?php } else { ?>
        <select name="sgcate_id[]" class="sgcategory">
          <option value="0"><?php echo $lang['nc_please_choose'];?></option>
          <?php if (!empty($output['clic_doctors_class'])){?>
          <?php foreach ($output['clic_doctors_class'] as $val) { ?>
          <option value="<?php echo $val['stc_id']; ?>"><?php echo $val['stc_name']; ?></option>
          <?php if (is_array($val['child']) && count($val['child'])>0){?>
          <?php foreach ($val['child'] as $child_val){?>
          <option value="<?php echo $child_val['stc_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child_val['stc_name']; ?></option>
          <?php }?>
          <?php }?>
          <?php } ?>
          <?php } ?>
        </select>
        <?php } ?>
        <p class="hint"><?php echo $lang['clic_doctors_index_belong_multiple_clic_class'];?></p>
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['clic_doctors_import_unicode'];?></dt>
      <dd>
        <p>unicode </p>
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['clic_doctors_import_file_type'];?></dt>
      <dd>
        <p><?php echo $lang['clic_doctors_import_file_csv'];?></p>
      </dd>
    </dl>
    <dl>
      <dt>&nbsp;</dt>
      <dd>
        <input type="submit" class="submit" value="<?php echo $lang['clic_doctors_import_submit'];?>" />
      </dd>
    </dl>
    </ul>
  </div>
  <div class="ncsc-form-doctors" id="step2" <?php if($output['step'] != '2'){?> style="display:none"<?php }?>>
    </ul>
    <!--stpe2-->
    <dl>
      <dt><?php echo $lang['clic_doctors_import_upload_album'].$lang['nc_colon'];?></dt>
      <dd>
        <p>
          <select id="sel">
            <?php if(!empty($output['aclass_info']) && is_array($output['aclass_info'])){?>
            <?php foreach ($output['aclass_info'] as $v){?>
            <option value='<?php echo $v['aclass_id']?>'  style="width:80px;"><?php echo $v['aclass_name']?></option>
            <?php }?>
            <?php }?>
          </select>
        </p>
        <p class="hint"><?php echo $lang['clic_doctors_import_tbi_desc'];?></p>
      </dd>
    </dl>
    <dl id="trUploadContainer" >
      <dt>&nbsp;</dt>
      <dd style="height:80px;">
        <div id="divSwfuploadContainer">
      <div id="divButtonContainer"> <span id="spanButtonPlaceholder"></span> </div>
          <div id="divFileProgressContainer"></div>
        </div>
        <div id="warning"></div>
      </dd>
    </dl>
    </ul>
  </div>
  <div class="ncsc-form-doctors"  id="step3"  style="display:none"> 
    <!--step3-->
   
    <dl>
      <dt><?php echo $lang['clic_doctors_import_step3'];?></dt>
      <dd><span><?php echo $lang['clic_doctors_import_remind'];?>?</span><span class="red"><?php echo $lang['clic_doctors_import_remind2'];?></span></dd>
    </dl>
    <dl>
      <dt>&nbsp;</dt>
      <dd>
        <input name=""  class="submit" value="<?php echo $lang['clic_doctors_import_pack'];?>" onclick="window.location.href='index.php?act=taobao_import&op=date_pack&doctors_id_str='+'<?php echo $output['doctors_id_str'];?>';"/>
      </dd>
    </dl>
    </ul>
  </div>
</form>
<script>
function add_uploadedfile(file_data){}
$(function() {

	function sgcInit(){
	var sgcate	= $("select[name='stc_id[]']").clone();
	$("select[name='stc_id[]']").remove();
	sgcate.clone().appendTo('#div_sgcate');
	$("#add_sgcategory").click(function(){
		sgcate.clone().appendTo('#div_sgcate');
	});
}
		sgcInit();
	doctorS_SWFU = new SWFUpload({
		upload_url: "<?php echo clinic_SITE_URL;?>/index.php?act=iswfupload&op=import_pic_upload",
		flash_url: "<?php echo RESOURCE_SITE_URL;?>/js/swfupload/swfupload.swf",
		post_params: {
            'sid': <?php echo $_SESSION['clic_id']?>,
            "HTTP_USER_AGENT":"Mozilla/5.0 (Windows; U; Windows NT 6.1; zh-CN; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 GTB7.1",
            'category_id': <?php echo $output['aclass_info']['0']['aclass_id']?>
		},
		file_size_limit: "2 MB",
		file_types: "*.tbi",
		custom_settings: {
			upload_target: "divFileProgressContainer",
			if_multirow: 0
		},
	
		// Button Settings
		button_image_url: "<?php echo RESOURCE_SITE_URL;?>/js/swfupload/images/SmallSpyGlassWithTransperancy_17x18.png",
		button_width: 86,
		button_height: 18,
		button_text: '<span class="button">批量上传</span>',
		button_text_style: '.button {font-family: Helvetica, Arial, sans-serif; font-size: 12pt; font-weight: bold; color: #3F3D3E;} .buttonSmall {font-size: 10pt;}',
		button_text_top_padding: 0,
		button_text_left_padding: 18,
		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor: SWFUpload.CURSOR.HAND,
	
		// The event handler functions are defined in handlers.js
		file_queue_error_handler: fileQueueError,
		file_dialog_complete_handler: fileDialogComplete,
		upload_progress_handler: uploadProgress,
		upload_error_handler: uploadError,
		upload_success_handler: uploadSuccess,
		upload_complete_handler: uploadComplete,
		button_placeholder_id: "spanButtonPlaceholder",
		file_queued_handler : fileQueued
	});
    // 查询下级分类，分类不存在显示当前分类绑定的规格
    $('select[nctype="gc"]').change(function(){
        $(this).parents('td:first').nextAll().html('');

        getClassSpec($(this));
    });
});

function upload_complete(){

	$('#stpe2li').attr("class",'normal')
	$('#stpe3li').attr("class",'active')
	$('#step2').css('display','none');
	$('#step3').css('display','block');
}

// ajax选择商品分类
function getClassSpec($this) {
    var id = parseInt($this.val());
    var data_str = ''; eval('data_str =' + $this.attr('data-param'));
    var deep = data_str.deep;
    if (isNaN(id)) {
        // 清理分类
        clearClassHtml(parseInt(deep)+1);
    }
	document.getElementById('gc_id').value=id;
    $.getJSON('index.php?act=clic_spec&op=ajax_class&id=' + id + '&deep=' + deep, function(data){
  
        if (data) {
            if (data.type == 'class') {
                nextClass(data.data, data.deep);
            } 
        }
    });
	
}

// 下一级商品分类
function nextClass(data, deep) {
    $('span[nctype="gc' + deep + '"]').html('').append('<select data-param="{deep:' + deep + '}"></select>')
        .find('select').change(function(){
            getClassSpec($(this));
        }).append('<option><?php echo $lang['nc_please_choose'];?></option>');
    $.each(data, function(i, n){
        if (n != null) {
            $('span[nctype="gc' + deep + '"] > select').append('<option value="' + n.gc_id + '">' + n.gc_name + '</option>');
		
        }
			  document.getElementById('gc_id').value=n.gc_id;
    });
    // 清理分类
    clearClassHtml(parseInt(deep)+1);
	
}

// 清理二级分类信息
function clearClassHtml(deep) {
    switch (deep) {
        case 2:
            $('span[nctype="gc2"]').empty();
        case 3:
            $('span[nctype="gc3"]').empty();
            break;
    }
}

</script> 
