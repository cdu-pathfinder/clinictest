<table class="table tb-type2 appointment mtw">
  <thead class="thead">
    <tr class="space">
      <th><?php echo $lang['complain_message'];?></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th><?php echo $lang['final_handle_message'];?></th>
    </tr>
    <tr class="nobappointment hover">
      <td><ul>
          <li><strong><?php echo $lang['complain_state'];?>:</strong><b><?php echo $output['complain_info']['complain_state_text'];?></b></li>
          <li><strong><?php echo $lang['complain_subject_content'];?>:</strong><?php echo $output['complain_info']['complain_subject_content'];?></li>
          <li><strong><?php echo $lang['complain_accuser'];?>:</strong><?php echo $output['complain_info']['accuser_name'];?></li>
          <li><strong><?php echo $lang['complain_evidence'];?>:</strong>
            <?php
                        if(empty($output['complain_info']['complain_pic1'])&&empty($output['complain_info']['complain_pic2'])&&empty($output['complain_info']['complain_pic3'])) {
                            echo $lang['complain_pic_none'];
                        }
                        else {
                            $pic_link = 'index.php?act=show_pics&type=complain&pics=';
                            if(!empty($output['complain_info']['complain_pic1'])) {
                                $pic_link .= $output['complain_info']['complain_pic1'].'|';
                            }
                            if(!empty($output['complain_info']['complain_pic2'])) {
                                $pic_link .= $output['complain_info']['complain_pic2'].'|';
                            }
                            if(!empty($output['complain_info']['complain_pic3'])) {
                                $pic_link .= $output['complain_info']['complain_pic3'].'|';
                            }
                            $pic_link = rtrim($pic_link,'|');
                    ?>
            <a href="<?php echo $pic_link;?>" target="_blank"><?php echo $lang['complain_pic_view'];?></a>
            <?php } ?>
          </li>
          <li><strong><?php echo $lang['complain_datetime'];?>:</strong><?php echo date('Y-m-d H:i:s',$output['complain_info']['complain_datetime']);?></li>
        </ul></td>
    </tr>
    <tr>
      <th><?php echo $lang['complain_doctors'];?></th>
    </tr>
    <tr class="nobappointment">
      <td><table class="table tb-type2 doctors ">
          <tr>
            <th colspan="2"><?php echo $lang['complain_doctors_name'];?></th>
            <th><?php echo $lang['complain_text_num'];?></th>
            <th><?php echo $lang['complain_text_price'];?></th>
          </tr>
          <?php foreach((array)$output['complain_doctors_list'] as $complain_doctors) { ?>
          <tr>
            <td width="65" align="center" valign="middle"><a style="text-decoration:none;" href="<?php echo urlclinic('doctors','index',array('doctors_id'=> $complain_doctors['doctors_id']));?>" target="_blank">
              <img width="50" src="<?php echo cthumb($complain_doctors['doctors_image'], 60,$output['appointment_info']['clic_id']);?>" />
              </a></td>
            <td class="intro">
                <p><a href="<?php echo urlclinic('doctors','index',array('doctors_id'=> $complain_doctors['doctors_id']));?>" target="_blank"><?php echo $complain_doctors['doctors_name'];?> </a></p>
                <p><?php echo appointmentdoctorsType($complain_doctors['appointment_doctors_type']); ?></p>
              </td>
            <td width="10%"><?php echo $complain_doctors['doctors_num'];?></td>
            <td width="10%"><?php echo $lang['currency'].$complain_doctors['doctors_price'];?></td>
          </tr>
          <tr class="problem_desc">
            <td colspan="4"><div class="complain-tit"><span><?php echo $lang['complain_text_problem'];?>:</span><?php echo $complain_doctors['complain_message'];?></div></td>
          </tr>
          <?php } ?>
        </table></td>
    </tr>
    <tr>
      <th><?php echo $lang['complain_content'];?></th>
    </tr>
    <tr class="nobappointment">
      <td><div class="complain-intro" style=" color: #06C; bappointment-color: #A7CAED; "><?php echo $output['complain_info']['complain_content'];?></div></td>
    </tr>
  </tbody>
</table>
<?php if(!empty($output['refund_list']) && is_array($output['refund_list'])) { ?>
<table class="table tb-type2 appointment mtw">
  <thead class="thead">
    <tr class="space">
      <th>退款信息</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>
        <?php if($output['appointment_info']['refund_amount'] > 0) { ?>
        <p><?php echo $lang['refund_appointment_refund'];?>:<b><?php echo $lang['currency'].$output['appointment_info']['refund_amount'];?></b></p>
        <?php } ?>
        <p> 注：下表中订单商品退款在处理中的或已经确认，不能再次退款。</p>
        </td>
    </tr>
    <tr class="nobappointment">
      <td>
        <table class="table tb-type2 doctors ">
          <tr>
            <th colspan="2"><?php echo $lang['complain_doctors_name'];?></th>
            <th>退款金额</th>
            <th>实际支付额</th>
            <th>商家审核</th>
            <th>平台确认</th>
            <th>购买数量</th>
            <th><?php echo $lang['complain_text_price'];?></th>
          </tr>
        <?php foreach ($output['refund_list'] as $key => $val) { ?>
          <tr>
            <td width="65" align="center" valign="middle"><a style="text-decoration:none;" href="<?php echo urlclinic('doctors','index',array('doctors_id'=> $val['doctors_id']));?>" target="_blank">
              <img width="50" src="<?php echo thumb($val,60);?>" />
              </a></td>
            <td class="intro">
                <p><a href="<?php echo urlclinic('doctors','index',array('doctors_id'=> $val['doctors_id']));?>" target="_blank"><?php echo $val['doctors_name'];?> </a></p>
                <p><?php echo appointmentdoctorsType($val['doctors_type']); ?></p>
              </td>
            <td width="10%"><?php echo $lang['currency'].$val['extend_refund']['refund_amount'];?></td>
            <td width="10%"><?php echo $lang['currency'].$val['doctors_pay_price'];?></td>
            <td width="10%"><?php echo $output['state_array'][$val['extend_refund']['clinicer_state']];?></td>
            <td width="10%"><?php echo $val['extend_refund']['clinicer_state']==2 ? $output['admin_array'][$val['extend_refund']['refund_state']]:'无'; ?></td>
            <td width="10%"><?php echo $val['doctors_num'];?></td>
            <td width="10%"><?php echo $lang['currency'].$val['doctors_price'];?></td>
          </tr>
        <?php } ?>
        </table>
        </td>
    </tr>
  </tbody>
</table>
<?php } ?>