<div class="eject_con">
  <div id="warning"></div>
<?php if ($output['appointment_info']) {?>
  <form method="post" action="index.php?act=member_appointment&op=change_state&state_type=appointment_cancel&appointment_id=<?php echo $output['appointment_info']['appointment_id']; ?>" id="appointment_cancel_form" onsubmit="ajaxpost('appointment_cancel_form','','','onerror')">
    <input type="hidden" name="form_submit" value="ok" />
    <h2><?php echo $lang['member_change_ensure_cancel'];?>?</h2>
    <dl>
      <dt><?php echo $lang['member_appointment_sn'].$lang['nc_colon'];?></dt>
      <dd><span class="num"><?php echo $output['appointment_info']['appointment_sn']; ?></span></dd>
    </dl>
    <dl>
      <dt><?php echo $lang['member_change_cancel_reason'].$lang['nc_colon'];?></dt>
      <dd>
        <ul class="checked">
          <li>
            <input type="radio" checked name="state_info" id="d1" value="<?php echo $lang['member_change_other_doctors'];?>" />
            <label for="d1"><?php echo $lang['member_change_other_doctors'];?></label>
          </li>
          <li>
            <input type="radio" name="state_info" id="d2" value="<?php echo $lang['member_change_other_shipping'];?>" />
            <label for="d2"><?php echo $lang['member_change_other_shipping'];?></label>
          </li>
          <li>
            <input type="radio" name="state_info" id="d3" value="<?php echo $lang['member_change_other_clic'];?>" />
            <label for="d3"><?php echo $lang['member_change_other_clic'];?></label>
          </li>
          <li>
            <input type="radio" name="state_info" flag="other_reason" id="d4" value="" />
            <label for="d4"><?php echo $lang['member_change_other_reason'];?></label>
          </li>
          <li id="other_reason" style="display:none;">
            <textarea name="state_info1" rows="2" id="other_reason_input"></textarea>
          </li>
        </ul>
      </dd>
    </dl>
    <dl class="bottom">
      <dt>&nbsp;</dt>
      <input type="submit" id="confirm_button" class="submit" value="<?php echo $lang['nc_ok'];?>" />
    </dl>
  </form>
<?php } else { ?>
<p style="line-height:80px;text-align:center">该订单并不存在，请检查参数是否正确!</p>
<?php } ?>
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