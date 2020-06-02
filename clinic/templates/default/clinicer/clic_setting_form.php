<?php defined('InclinicNC') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="ncsc-form-default">
  <form method="post"  action="index.php?act=clic_setting&op=clic_setting" enctype="multipart/form-data" id="my_clic_form">
    <input type="hidden" name="form_submit" value="ok" />
    <dl>
      <dt><?php echo $lang['clic_setting_grade'].$lang['nc_colon'];?></dt>
      <dd>
        <p><?php echo $output['clic_grade']['sg_name']; ?></p>
        </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['clic_setting_clic_zy'].$lang['nc_colon']; ?></dt>
      <dd>
          <textarea name="clic_zy" rows="2" class="textarea w400"  maxlength="50" ><?php echo $output['clic_info']['clic_zy'];?></textarea>
        
        <p class="hint"><?php echo $lang['clic_create_clic_zy_hint'];?></p>
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['clic_setting_change_label'].$lang['nc_colon'];?></dt>
      <dd>
        <input type="hidden" name="clic_old_label" value="<?php echo $output['clic_info']['clic_label'];?>" />
        <div class="ncsc-upload-thumb clic-logo">
          <p><?php if(empty($output['clic_info']['clic_label'])){ ?>
          <i class="icon-picture"></i>
          <?php } else {?>
          <img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_clic.'/'.$output['clic_info']['clic_label'];?>" nc_type="clic_label" />
          <?php }?></p>
        </div>
        <div class="ncsc-upload-btn"> <a href="javascript:void(0);"><span>
          <input type="file" hidefocus="true" size="1" class="input-file" name="clic_label" id="clicLablePic" nc_type="change_clic_label"/>
          </span>
          <p><i class="icon-upload-alt"></i>图片上传</p>
          </a> </div>
        <p class="hint"><?php echo $lang['clic_setting_label_tip'];?></p>
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['clic_setting_change_banner'].$lang['nc_colon'];?> </dt>
      <dd>
        <input type="hidden" name="clic_old_banner" value="<?php echo $output['clic_info']['clic_banner'];?>" />
        <div class="ncsc-upload-thumb clic-banner">
          <p><?php if(empty($output['clic_info']['clic_banner'])){?>
          <i class="icon-picture"></i>
          <?php }else{?>
          <img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_clic.'/'.$output['clic_info']['clic_banner'];?>" nc_type="clic_banner" />
          <?php }?></p>
        </div>
        <div class="ncsc-upload-btn"> <a href="javascript:void(0);"><span>
          <input type="file" hidefocus="true" size="1" class="input-file" name="clic_banner" id="clicBannerPic" nc_type="change_clic_banner"/>
          </span>
          <p><i class="icon-upload-alt"></i>图片上传</p>
          </a> </div>
        <p class="hint"><?php echo $lang['clic_setting_banner_tip'];?></p>
      </dd>
    </dl>
    <?php if($output['subdomain'] == '1'){?>
    <dl>
      <dt><?php echo $lang['clic_setting_uri'].$lang['nc_colon'];?></dt>
      <dd>
        <?php if($output['subdomain_edit'] == '1' || empty($output['clic_info']['clic_domain'])){?>
        <p>
          <input type="text" class="text"  name="clic_domain" value="<?php echo $output['clic_info']['clic_domain'];?>"  />
          &nbsp;<?php echo '.'.SUBDOMAIN_SUFFIX;?> &nbsp;</p>
        <p class="hint"><?php echo $lang['clic_setting_uri_tip'];?>: <?php echo $GLOBALS['setting_config']['subdomain_length'];?>
          <?php if($output['subdomain_edit'] == '1'){?>
          &nbsp; &nbsp;<?php echo $lang['clic_setting_domain_times'];?>: <?php echo $output['clic_info']['clic_domain_times'];?> &nbsp; &nbsp;<?php echo $lang['clic_setting_domain_times_max'];?>: <?php echo $output['subdomain_times'];?>
          <?php }else {?>
          &nbsp; &nbsp;<?php echo $lang['clic_setting_domain_notice'];?>
          <?php }?>
        </p>
        <?php }else {?>
        <p><?php echo $output['clic_info']['clic_domain'];?><?php echo '.'.SUBDOMAIN_SUFFIX;?> &nbsp;</p>
        <p class="hint"><?php echo $lang['clic_setting_domain_tip'];?>
          <?php if($GLOBALS['setting_config']['subdomain_edit'] == '1'){?>
          &nbsp; &nbsp;<?php echo $lang['clic_setting_domain_times'];?>: <?php echo $output['clic_info']['clic_domain_times'];?> &nbsp; &nbsp;<?php echo $lang['clic_setting_domain_times_max'];?>: <?php echo $output['subdomain_times'];?>
          <?php }?>
        </p>
        <?php }?>
      </dd>
    </dl>
    <?php }?>
    <dl>
      <dt>QQ<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <input class="w200 text" name="clic_qq" type="text"  id="clic_qq" value="<?php echo $output['clic_info']['clic_qq'];?>" />
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['clic_setting_wangwang'].$lang['nc_colon'];?></dt>
      <dd>
        <input class="text w200" name="clic_ww" type="text"  id="clic_ww" value="<?php echo $output['clic_info']['clic_ww'];?>" />
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['clic_setting_seo_keywords'].$lang['nc_colon']; ?></dt>
      <dd>
        <p>
          <input class="text w400" name="seo_keywords" type="text"  value="<?php echo $output['clic_info']['clic_keywords'];?>" />
        </p>
        <p class="hint"><?php echo $lang['clic_setting_seo_keywords_help']; ?></p>
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['clic_setting_seo_description'].$lang['nc_colon']; ?></dt>
      <dd>
        <p>
          <textarea name="seo_description" rows="3" class="textarea w400" id="remark_input" ><?php echo $output['clic_info']['clic_description'];?></textarea>
        </p>
        <p class="hint"><?php echo $lang['clic_setting_seo_description_help']; ?></p>
      </dd>
    </dl>
    <div class="bottom">
        <label class="submit-bappointment"><input type="submit" class="submit" value="<?php echo $lang['clic_doctors_class_submit'];?>" /></label>
      </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript">
var SITEURL = "<?php echo clinic_SITE_URL; ?>";
//裁剪图片后返回接收函数
function call_back(picname){
	$('#clic_logo').val(picname);
	$('img[nc_type="clic_logo"]').attr('src','<?php echo UPLOAD_SITE_URL.'/'.ATTACH_clic;?>/'+picname);
	$('#_pic').val('');
}
$(function(){
	$('#_pic').change(uploadChange);
	function uploadChange(){
		var filepatd=$(this).val();
		var extStart=filepatd.lastIndexOf(".");
		var ext=filepatd.substring(extStart,filepatd.lengtd).toUpperCase();		
		if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
			alert("file type error");
			$(this).attr('value','');
			return false;
		}
		if ($(this).val() == '') return false;
		ajaxFileUpload();
	}	
	function ajaxFileUpload()
	{
		$.ajaxFileUpload
		(
			{
				url:'index.php?act=cut&op=pic_upload&form_submit=ok&uploadpath=<?php echo ATTACH_clic;?>',
				secureuri:false,
				fileElementId:'_pic',
				dataType: 'json',
				success: function (data, status)
				{
					if (data.status == 1){
						ajax_form('cutpic','<?php echo $lang['nc_cut'];?>','index.php?act=cut&op=pic_cut&x=100&y=100&resize=1&url='+data.url,680);
					}else{
						alert(data.msg);$('#_pic').bind('change',uploadChange);
					}
				},
				error: function (data, status, e)
				{
					alert('upload failed');$('#_pic').bind('change',uploadChange);
				}
			}
		)
	};	
	regionInit("region");
	$('input[nc_type="change_clic_banner"]').change(function(){
		var src = getFullPath($(this)[0]);
		$('img[nc_type="clic_banner"]').attr('src', src);
	});
	$('input[nc_type="change_clic_label"]').change(function(){
		var src = getFullPath($(this)[0]);
		$('img[nc_type="clic_label"]').attr('src', src);
	});
	$('input[class="edit_region"]').click(function(){
		$(this).css('display','none');
		$('#area_id').val('');
		$(this).parent().children('select').css('display','');
		$(this).parent().children('span').css('display','none');
	});
	jQuery.validator.addMethod("domain", function(value, element) {
			return this.optional(element) || /^[\w\-]+$/i.test(value);
		}, ""); 
	$('#my_clic_form').validate({
    	submitHandler:function(form){
    		ajaxpost('my_clic_form', '', '', 'onerror')
    	},
		rules : {
        	<?php if(($output['subdomain'] == '1') && ($output['subdomain_edit'] == '1' || empty($output['clic_info']['clic_domain']))){?>
					clic_domain: {
						domain: true,
		        rangelength:[<?php echo $output['subdomain_length'][0];?>, <?php echo $output['subdomain_length'][1];?>]
					}
          <?php }?>
        },
        messages : {
        	<?php if(($output['subdomain'] == '1') && ($output['subdomain_edit'] == '1' || empty($output['clic_info']['clic_domain']))){?>
					clic_domain: {
						domain: '<?php echo $lang['clic_setting_domain_valid'];?>',
		        rangelength:'<?php echo $lang['clic_setting_domain_rangelength'];?>'
					}
          <?php }?>
        }
    });
    
    // ajax 修改店铺二维码
    $('#a_clic_code').click(function(){
    	$('#img_clic_code').attr('src','');
		$.getJSON($(this).attr('href'),function(data){
			$('#img_clic_code').attr('src','<?php echo UPLOAD_SITE_URL.'/'.ATTACH_clic.DS;?>'+data);
		});
		return false;
    });    
    
});
</script> 
