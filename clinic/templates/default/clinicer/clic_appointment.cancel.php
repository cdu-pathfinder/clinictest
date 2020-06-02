<?php defined('InclinicNC') or exit('Access Invalid!');?>
<div class="eject_con">
  <div id="warning"></div>
  <form method="post" id="appointment_cancel_form" onsubmit="ajaxpost('appointment_cancel_form', '', '', 'onerror');return false;" action="index.php?act=clic_appointment&op=change_state&state_type=appointment_cancel&appointment_id=<?php echo $output['appointment_id']; ?>">
    <input type="hidden" name="form_submit" value="ok" />
    <dl>
      <dt><?php echo $lang['clic_appointment_appointment_sn'].$lang['nc_colon'];?></dt>
      <dd><span class="num"><?php echo trim($_GET['appointment_sn']); ?></span></dd>
    </dl>
    <dl>
      <dt><?php echo $lang['clic_appointment_cancel_reason'].$lang['nc_colon'];?></dt>
      <dd>
        <ul class="checked">
          <li>
            <input type="radio" checked name="state_info" id="d1" value="<?php echo $lang['clic_appointment_lose_doctors'];?>" />
            <label for="d1"><?php echo $lang['clic_appointment_lose_doctors'];?></label>
          </li>
          <li>
            <input type="radio" name="state_info" id="d2" value="<?php echo $lang['clic_appointment_invalid_appointment'];?>" />
            <label for="d2"><?php echo $lang['clic_appointment_invalid_appointment'];?></label>
          </li>
          <li>
            <input type="radio" name="state_info" id="d3" value="<?php echo $lang['clic_appointment_buy_apply'];?>" />
            <label for="d3"><?php echo $lang['clic_appointment_buy_apply'];?></label>
          </li>
          <li>
            <input type="radio" name="state_info" flag="other_reason" id="d4" value="" />
            <label for="d4"><?php echo $lang['clic_appointment_other_reason'];?></label>
          </li>
          <li id="other_reason" style="display:none; height:48px;">
            <textarea name="state_info1" rows="2"  id="other_reason_input" style="width:200px;"></textarea>
          </li>
        </ul>
      </dd>
    </dl>
    <dl class="bottom">
      <dt>&nbsp;</dt>
      <dd>
        <input type="submit" class="submit" id="confirm_button" value="<?php echo $lang['nc_ok'];?>" />
      </dd>
    </dl>
  </form>
</div>
<script type="text/javascript">
$(function(){
        $('#cancel_button').click(function(){
            DialogManager.close('seller_appointment_cancel_appointment');
         });
       $("input[name='state_info']").click(function(){
        if ($(this).attr('flag') == 'other_reason')
        {
            $('#other_reason').show();
        }
        else
        {
            $('#other_reason').hide();
        }
    });
});
</script> 
