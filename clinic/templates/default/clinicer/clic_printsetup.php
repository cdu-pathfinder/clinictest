<?php defined('InclinicNC') or exit('Access Invalid!');?>

  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <div class="ncsc-form-default">
    <form method="post" action="index.php?act=clic_printsetup&op=index" enctype="multipart/form-data" id="my_clic_form">
      <input type="hidden" name="form_submit" value="ok" />
      <dl class="setup">
        <dt><?php echo $lang['clic_printsetup_desc'].$lang['nc_colon'];?></dt>
        <dd><textarea name="clic_printdesc" cols="150" rows="3" class="textarea w400" id="clic_printdesc"><?php echo $output['clic_info']['clic_printdesc'];?></textarea>
          <p class="hint"><?php echo $lang['clic_printsetup_tip1'];?></p>
        </dd>
      </dl>
      <dl class="setup">
        <dt><?php echo $lang['clic_printsetup_stampimg'].$lang['nc_colon'];?></dt>
        <dd>
          <input type="hidden" name="clic_stamp_old" value="<?php echo $output['clic_info']['clic_stamp'];?>" />
          <p class="picture">
          	<span class="thumb size120"><i></i>
          	<img src="<?php if(!empty($output['clic_info']['clic_stamp'])){echo UPLOAD_SITE_URL.'/'.ATTACH_clic.'/'.$output['clic_info']['clic_stamp'];}?>"  onload="javascript:DrawImage(this,120,120);" nc_type="clic_stamp" /></span> </p>
          <p>
            <input name="clic_stamp" type="file"  hidefocus="true" nc_type="change_clic_stamp"/>
          </p>
          <p class="hint"><?php echo $lang['clic_printsetup_tip2'];?>
          </p>
        </dd>
      </dl>
      <div class="bottom">
          <label class="submit-bappointment"><input type="submit" class="submit" value="<?php echo $lang['clic_doctors_class_submit'];?>" /></label>
       </div>
    </form>
  </div>


<script type="text/javascript">
$(function(){
	$('input[nc_type="change_clic_stamp"]').change(function(){
		var src = getFullPath($(this)[0]);
		$('img[nc_type="clic_stamp"]').attr('src', src);
	});
	$('#my_clic_form').validate({
    	submitHandler:function(form){
    		ajaxpost('my_clic_form', '', '', 'onerror')
    	},
		rules : {
    		clic_printdesc: {
    			required: true,
    			rangelength:[0,100]
    	    },
        },
        messages : {
        	clic_printdesc: {
        		required: '<i class="icon-exclamation-sign"></i><?php echo $lang['clic_printsetup_desc_error'];?>',
		        rangelength:'<?php echo $lang['clic_printsetup_desc_error'];?>'
		    }
        }
    });
});
</script>