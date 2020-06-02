<?php defined('InclinicNC') or exit('Access Invalid!');?>
<style type="text/css">
.d_inline {
	display:inline;
}
</style>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['clic'];?></h3>
      <ul class="tab-base">
        <li><a href="index.php?act=clic&op=clic"><span><?php echo $lang['manage'];?></span></a></li>
        <li><a href="index.php?act=clic&op=clic_joinin"><span><?php echo $lang['pending'];?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_edit'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="clic_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="clic_id" value="<?php echo $output['clic_array']['clic_id'];?>" />
    <table class="table tb-type2">
      <tbody>
        <tr class="nobappointment">
          <td colspan="2" class="required"><label><?php echo $lang['clic_user_name'];?>:</label></td>
        </tr>
        <tr class="nobappointment">
          <td class="vatop rowform"><?php echo $output['clic_array']['member_name'];?></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="clic_name"> <?php echo $lang['clic_name'];?>:</label></td>
        </tr>
        <tr class="nobappointment">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['clic_array']['clic_name'];?>" id="clic_name" name="clic_name" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label><?php echo $lang['belongs_class'];?>:</label></td>
        </tr>
        <tr class="nobappointment">
          <td class="vatop rowform"><select name="sc_id">
              <option value="0"><?php echo $lang['nc_please_choose'];?>...</option>
              <?php if(is_array($output['class_list'])){ ?>
              <?php foreach($output['class_list'] as $k => $v){ ?>
              <option <?php if($output['clic_array']['sc_id'] == $v['sc_id']){ ?>selected="selected"<?php } ?> value="<?php echo $v['sc_id']; ?>"><?php echo $v['sc_name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
          <td class="vatop tips"></td>
        </tr>
      </tbody>
      <tbody>
        <tr>
          <td colspan="2" class="required"><label>
            <label for="grade_id"> <?php echo $lang['belongs_level'];?>: </label>
            </label></td>
        </tr>
        <tr class="nobappointment">
          <td class="vatop rowform"><select id="grade_id" name="grade_id">
              <?php if(is_array($output['grade_list'])){ ?>
              <?php foreach($output['grade_list'] as $k => $v){ ?>
              <option <?php if($output['clic_array']['grade_id'] == $v['sg_id']){ ?>selected="selected"<?php } ?> value="<?php echo $v['sg_id']; ?>"><?php echo $v['sg_name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label><?php echo $lang['period_to'];?>:</label></td>
        </tr>
        <tr class="nobappointment">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['clic_array']['clic_end_time'];?>" id="end_time" name="end_time" class="txt date"></td>
          <td class="vatop tips"><?php echo $lang['formart'];?></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>
            <label for="state"><?php echo $lang['state'];?>:</label>
            </label></td>
        </tr>
        <tr class="nobappointment">
          <td class="vatop rowform onoff"><label for="clic_state1" class="cb-enable <?php if($output['clic_array']['clic_state'] == '1'){ ?>selected<?php } ?>" ><span><?php echo $lang['open'];?></span></label>
            <label for="clic_state0" class="cb-disable <?php if($output['clic_array']['clic_state'] == '0'){ ?>selected<?php } ?>" ><span><?php echo $lang['close'];?></span></label>
            <input id="clic_state1" name="clic_state" <?php if($output['clic_array']['clic_state'] == '1'){ ?>checked="checked"<?php } ?> onclick="$('#tr_clic_close_info').hide();" value="1" type="radio">
            <input id="clic_state0" name="clic_state" <?php if($output['clic_array']['clic_state'] == '0'){ ?>checked="checked"<?php } ?> onclick="$('#tr_clic_close_info').show();" value="0" type="radio"></td>
          <td class="vatop tips"></td>
        </tr>
      </tbody>
      <tbody id="tr_clic_close_info">
        <tr >
          <td colspan="2" class="required"><label for="clic_close_info"><?php echo $lang['close_reason'];?>:</label></td>
        </tr>
        <tr class="nobappointment">
          <td class="vatop rowform"><textarea name="clic_close_info" rows="6" class="tarea" id="clic_close_info"><?php echo $output['clic_array']['clic_close_info'];?></textarea></td>
          <td class="vatop tips"></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="15"><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script src="<?php echo RESOURCE_SITE_URL."/js/jquery-ui/i18n/zh-CN.js";?>" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
var SITEURL = "<?php echo clinic_SITE_URL; ?>";
function del_auth(key){
var clic_id='<?php echo $output['clic_array']['clic_id'];?>';
	$.get("index.php?act=clic&&op=del_auth",{'key':key,'clic_id':clic_id},function(date){
		if(date){
			$("#"+key).remove();
			$("#"+key+"_del").remove();
			alert('<?php echo $lang['certification_del_success'];?>');
		}
		else{
			alert('<?php echo $lang['certification_del_fail'];?>');
		}
	});
}
$(function(){

	$('#end_time').datepicker();
	$('input[name=clic_state][value=<?php echo $output['clic_array']['clic_state'];?>]').trigger('click');
	regionInit("region");
	$('input[class="edit_region"]').click(function(){
		$(this).css('display','none');
		$(this).parent().children('select').css('display','');
		$(this).parent().children('span').css('display','none');
	});
//按钮先执行验证再提交表单

	$("#submitBtn").click(function(){
    	if($("#clic_form").valid()){
    		$("#clic_form").submit();
		}
	});

//
	$('#clic_form').validate({
		errorPlacement: function(error, element){
			error.appendTo(element.parentsUntil('tr').parent().prev().find('td:first'));
        },

		rules : {
			clic_name: {
				required : true
			}
		},
		messages : {
			clic_name: {
				required: '<?php echo $lang['please_input_clic_name'];?>'
			}
		}
	});
});
</script>
