<?php defined('InclinicNC') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="ncsc-form-default">
  <form method="post"  action="index.php?act=clic_free_freight&op=clic_setting" id="my_clic_form">
    <input type="hidden" name="form_submit" value="ok" />
    <dl>
      <dt>免运费额度<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <input class="text w60" name="clic_free_price" maxlength="10" type="text"  id="clic_free_price" value="<?php echo $output['clic_free_price'];?>" /><em class="add-on">
<i class="icon-renminbi"></i>
</em>
        <p class="hint">默认为 0，表示不设置免运费额度，大于0表示购买金额超出该值后将免运费</p>
      </dd>
    </dl>
    <div class="bottom">
        <label class="submit-bappointment"><input type="submit" class="submit" value="<?php echo $lang['nc_common_button_save'];?>" /></label>
      </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script> 
<script type="text/javascript">
var SITEURL = "<?php echo clinic_SITE_URL; ?>";
$(function(){
	$('#my_clic_form').validate({
    	submitHandler:function(form){
    		ajaxpost('my_clic_form', '', '', 'onerror')
    	},
		rules : {
			clic_free_price: {
			required : true,
			number : true
			}
        },
        messages : {
        	clic_free_price: {
				required : '请填写金额',
				number : '请正确填写'
			}
        }
    });    
    
});
</script> 
