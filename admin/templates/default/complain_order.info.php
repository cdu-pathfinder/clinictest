<table class="table tb-type2 appointment mtw">
  <thead class="thead">
    <tr class="space">
      <th><?php echo $lang['complain_progress'];?></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="progress"><span id="state_new" class="text"><?php echo $lang['complain_state_new'];?></span> <span class="next-step"></span> <span id="state_appeal" class="text"><?php echo $lang['complain_state_appeal'];?></span> <span class="next-step"></span> <span id="state_talk" class="text"><?php echo $lang['complain_state_talk'];?></span>
          <span class="next-step">
          </span> <span id="state_handle" class="text"><?php echo $lang['complain_state_handle'];?></span> <span class="next-step"></span> <span id="state_finish" class="text"><?php echo $lang['complain_state_finish'];?></span></td>
    </tr>
  </tbody>
</table>
<table class="table tb-type2 appointment">
  <thead class="thead">
    <tr class="space">
      <th><?php echo $lang['appointment_detail'];?></th>
    </tr></thead>
    <tbody>
    <tr class="nobappointment">
      <td><ul>
          <li><strong><?php echo $lang['appointment_clinic_name'];?>:</strong><a href="<?php echo urlclinic('show_clic','index', array('clic_id'=>$output['appointment_info']['clic_id']));?>" target="_blank">
            <?php echo $output['appointment_info']['clic_name'];?> </a> </li>
          <li><strong><?php echo $lang['appointment_state'];?>:</strong><b><?php echo $output['appointment_info']['appointment_state_text'];?></b></li>
          <li><strong>订单号:</strong><a href="index.php?act=appointment&op=show_appointment&appointment_id=<?php echo $output['appointment_info']['appointment_id'];?>" target="_blank">
            <?php echo $output['appointment_info']['appointment_sn'];?></a> </li>
          <li><strong><?php echo $lang['appointment_datetime'];?>:</strong><?php echo date('Y-m-d H:i:s',$output['appointment_info']['add_time']);?></li>
          <li><strong><?php echo $lang['appointment_price'];?>:</strong><?php echo $lang['currency'].$output['appointment_info']['appointment_amount'];?>
            <?php if($output['appointment_info']['refund_amount'] > 0) { ?>
            (退款:<?php echo $lang['currency'].$output['appointment_info']['refund_amount'];?>)
            <?php } ?>
            </li>
          <?php if(!empty($output['appointment_info']['voucher_price'])) { ?>
          <li><strong><?php echo $lang['appointment_voucher_price'];?>:</strong><?php echo $lang['currency'].$output['appointment_info']['voucher_price'].'.00';?></li>
          <li><strong><?php echo $lang['appointment_voucher_sn'];?>:</strong><?php echo $output['appointment_info']['voucher_code'];?></li>
          <?php } ?>
        </ul></td>
    </tr>
  </tbody>
</table>
<script type="text/javascript">
$(document).ready(function(){
    var state = <?php echo empty($output['complain_info']['complain_state'])?0:$output['complain_info']['complain_state'];?>;
    if(state <= 10) {
        $("#state_new").addClass('red');
    }
    if(state == 20 ){
        $("#state_new").addClass('green');
        $("#state_appeal").addClass('red');
    }
    if(state == 30 ){
        $("#state_new").addClass('green');
        $("#state_appeal").addClass('green');
        $("#state_talk").addClass('red');
    }
    if(state == 40 ){
        $("#state_new").addClass('green');
        $("#state_appeal").addClass('green');
        $("#state_talk").addClass('green');
        $("#state_handle").addClass('red');
    }
    if(state == 99 ){
        $("#state_new").addClass('green');
        $("#state_appeal").addClass('green');
        $("#state_talk").addClass('green');
        $("#state_handle").addClass('green');
        $("#state_finish").addClass('green');
    }
});
</script>
