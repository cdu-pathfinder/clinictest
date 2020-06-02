<div class="eject_con"><!-- onsubmit="ajaxpost('confirm_appointment_form','','','onerror')" -->
  <div id="warning"></div>
<?php if ($output['appointment_info']) {?>
  <form action="index.php?act=member_appointment&op=change_state&state_type=appointment_receive&appointment_id=<?php echo $output['appointment_info']['appointment_id']; ?>" method="post" id="confirm_appointment_form" onsubmit="ajaxpost('confirm_appointment_form','','','onerror')" >
    <input type="hidden" name="form_submit" value="ok" />
    <h2><?php echo $lang['member_change_ensure_receive1'];?>?</h2>
    <dl>
      <dt><?php echo $lang['member_change_appointment_no'].$lang['nc_colon'];?></dt>
      <dd><span class="num"><?php echo trim($_GET['appointment_sn']); ?></span></dd>
    </dl>
    <dl>
      <p class="hint pl10 pr10"><?php echo $lang['member_change_receive_tip'];?></p>
    </dl>
    <dl class="bottom">
      <dt>&nbsp;</dt>
      <dd>
        <input type="submit" class="submit" id="confirm_yes" value="<?php echo $lang['nc_ok'];?>" />
      </dd>
    </dl>
  </form>
<?php } else { ?>
<p style="line-height:80px;text-align:center">该订单并不存在，请检查参数是否正确!</p>
<?php } ?>
</div>