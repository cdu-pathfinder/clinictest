<?php defined('InclinicNC') or exit('Access Invalid!');?>

<div class="page">
  <table class="table tb-type2 appointment">
    <tbody>
      <tr class="space">
        <th colspan="15"><?php echo $lang['appointment_state'];?></th>
      </tr>
      <tr>
        <td colspan="2"><ul>
            <li>
            <strong><?php echo $lang['appointment_number'];?>:</strong><?php echo $output['appointment_info']['appointment_sn'];?>
            ( 支付单号 <?php echo $lang['nc_colon'];?> <?php echo $output['appointment_info']['pay_sn'];?> )
            </li>
            <li><strong><?php echo $lang['appointment_state'];?>:</strong><?php echo appointmentState($output['appointment_info']);?></li>
            <li><strong><?php echo $lang['appointment_total_price'];?>:</strong><span class="red_common"><?php echo $lang['currency'].$output['appointment_info']['appointment_amount'];?> </span>
            	<?php if($output['appointment_info']['refund_amount'] > 0) { ?>
            	(<?php echo $lang['appointment_refund'];?>:<?php echo $lang['currency'].$output['appointment_info']['refund_amount'];?>)
            	<?php } ?></li>
            <li><strong><?php echo $lang['appointment_total_transport'];?>:</strong><?php echo $lang['currency'].$output['appointment_info']['shipping_fee'];?></li>
          </ul></td>
      </tr>
      <tr class="space">
        <th colspan="2"><?php echo $lang['appointment_detail'];?></th>
      </tr>
      <tr>
        <th><?php echo $lang['appointment_info'];?></th>
      </tr>
      <tr>
        <td><ul>
            <li><strong><?php echo $lang['buyer_name'];?><?php echo $lang['nc_colon'];?></strong><?php echo $output['appointment_info']['buyer_name'];?></li>
            <li><strong><?php echo $lang['clic_name'];?><?php echo $lang['nc_colon'];?></strong><?php echo $output['appointment_info']['clic_name'];?></li>
            <li><strong><?php echo $lang['payment'];?><?php echo $lang['nc_colon'];?></strong><?php echo appointmentPaymentName($output['appointment_info']['payment_code']);?></li>
            <li><strong><?php echo $lang['appointment_time'];?><?php echo $lang['nc_colon'];?></strong><?php echo date('Y-m-d H:i:s',$output['appointment_info']['add_time']);?></li>
            <?php if(intval($output['appointment_info']['payment_time'])){?>
            <li><strong><?php echo $lang['payment_time'];?><?php echo $lang['nc_colon'];?></strong><?php echo date('Y-m-d H:i:s',$output['appointment_info']['payment_time']);?></li>
            <?php }?>
            <?php if(intval($output['appointment_info']['shipping_time'])){?>
            <li><strong><?php echo $lang['ship_time'];?><?php echo $lang['nc_colon'];?></strong><?php echo date('Y-m-d H:i:s',$output['appointment_info']['shipping_time']);?></li>
            <?php }?>
            <?php if(intval($output['appointment_info']['finnshed_time'])){?>
            <li><strong><?php echo $lang['complate_time'];?><?php echo $lang['nc_colon'];?></strong><?php echo date('Y-m-d H:i:s',$output['appointment_info']['finnshed_time']);?></li>
            <?php }?>
            <?php if($output['appointment_info']['extend_appointment_common']['appointment_message'] != ''){?>
            <li><strong><?php echo $lang['buyer_message'];?><?php echo $lang['nc_colon'];?></strong><?php echo $output['appointment_info']['extend_appointment_common']['appointment_message'];?></li>
            <?php }?>
          </ul></td>
      </tr>
      <tr>
        <th><?php echo $lang['consignee_ship_appointment_info'];?></th>
      </tr>
      <tr>
        <td><ul>
            <li><strong><?php echo $lang['consignee_name'];?><?php echo $lang['nc_colon'];?></strong><?php echo $output['appointment_info']['extend_appointment_common']['reciver_name'];?></li>
            <li><strong><?php echo $lang['tel_phone'];?><?php echo $lang['nc_colon'];?></strong><?php echo @$output['appointment_info']['extend_appointment_common']['reciver_info']['phone'];?></li>
            <li><strong><?php echo $lang['address'];?><?php echo $lang['nc_colon'];?></strong><?php echo @$output['appointment_info']['extend_appointment_common']['reciver_info']['address'];?></li>
            <?php if($output['appointment_info']['shipping_code'] != ''){?>
            <li><strong><?php echo $lang['ship_code'];?><?php echo $lang['nc_colon'];?></strong><?php echo $output['appointment_info']['shipping_code'];?></li>
            <?php }?>
          <?php if (!empty($output['daddress_info'])) {?>
          <li><strong>发货人<?php echo $lang['nc_colon'];?></strong><?php echo $output['daddress_info']['clinicer_name']; ?></li>
          <li><strong><?php echo $lang['tel_phone'];?>:</strong><?php echo $output['daddress_info']['telphone'];?></li>
          <li><strong>发货地<?php echo $lang['nc_colon'];?></strong><?php echo $output['daddress_info']['area_info'];?>&nbsp;<?php echo $output['daddress_info']['address'];?>&nbsp;<?php echo $output['daddress_info']['company'];?></li>
          <?php } ?>
          </ul></td>
          </tr>
      <tr>
      	<th>发票信息</th>
      </tr>
      <tr>
          <td><ul>
    <?php foreach ((array)$output['appointment_info']['extend_appointment_common']['invoice_info'] as $key => $value){?>
      <li><strong><?php echo $key.$lang['nc_colon'];?></strong><?php echo $value;?></li>
    <?php } ?>
          </ul></td>
      </tr>
      <tr>
        <th><?php echo $lang['doc_info'];?></th>
      </tr>
      <tr>
        <td><table class="table tb-type2 doctors ">
            <tbody>
              <tr>
                <th></th>
                <th><?php echo $lang['doc_info'];?></th>
                <th class="align-center">单价</th>
                <th class="align-center">实际支付额</th>
                <th class="align-center"><?php echo $lang['doc_num'];?></th>
                <th class="align-center">佣金比例</th>
                <th class="align-center">收取佣金</th>
              </tr>
              <?php foreach($output['appointment_info']['extend_appointment_doctors'] as $doctors){?>
              <tr>
                <td class="w60 picture"><div class="size-56x56"><span class="thumb size-56x56"><i></i><a href="<?php echo clinic_SITE_URL;?>/index.php?act=doctors&doctors_id=<?php echo $doctors['doctors_id'];?>" target="_blank"><img alt="<?php echo $lang['doc_pic'];?>" src="<?php echo thumb($doctors, 60);?>" /> </a></span></div></td>
                <td class="w50pre"><p><a href="<?php echo clinic_SITE_URL;?>/index.php?act=doctors&doctors_id=<?php echo $doctors['doctors_id'];?>" target="_blank"><?php echo $doctors['doctors_name'];?></a></p><p><?php echo appointmentdoctorsType($doctors['doctors_type']);?></p></td>
                <td class="w96 align-center"><span class="red_common"><?php echo $lang['currency'].$doctors['doctors_price'];?></span></td>
                <td class="w96 align-center"><span class="red_common"><?php echo $lang['currency'].$doctors['doctors_pay_price'];?></span></td>
                <td class="w96 align-center"><?php echo $doctors['doctors_num'];?></td>
                <td class="w96 align-center"><?php echo $doctors['commis_rate'];?>%</td>
                <td class="w96 align-center"><?php echo ncPriceFormat($doctors['doctors_pay_price']*$doctors['commis_rate']/100);?></td>
              </tr>
              <?php }?>
            </tbody>
          </table></td>
      </tr>
    <!-- S 促销信息 -->
      <?php if(!empty($output['appointment_info']['extend_appointment_common']['promotion_info']) && !empty($output['appointment_info']['extend_appointment_common']['voucher_code'])){ ?>
      <tr>
      	<th><?php echo $lang['nc_promotion'];?></th>
      </tr>
      <tr>
          <td>
        <?php if(!empty($output['appointment_info']['extend_appointment_common']['promotion_info'])){ ?>
        <span style="color:red">满即送</span> <?php echo $output['appointment_info']['extend_appointment_common']['promotion_info'];?>
        <?php } ?>
        <?php if(!empty($output['appointment_info']['extend_appointment_common']['voucher_code'])){ ?>
        <span style="color:red">优惠券</span> 面额 <?php echo $lang['nc_colon'];?> <?php echo $output['appointment_info']['extend_appointment_common']['voucher_price'];?>
         编码 : <?php echo $output['appointment_info']['extend_appointment_common']['voucher_code'];?>
        <?php } ?>
          </td>
      </tr>
      <?php } ?>
    <!-- E 促销信息 -->

    <?php if(is_array($output['refund_list']) and !empty($output['refund_list'])) { ?>
      <tr>
      	<th>退款记录</th>
      </tr>
      <?php foreach($output['refund_list'] as $val) { ?>
      <tr>
        <td>发生时间<?php echo $lang['nc_colon'];?><?php echo date("Y-m-d H:i:s",$val['admin_time']); ?>&emsp;&emsp;退款单号<?php echo $lang['nc_colon'];?><?php echo $val['refund_sn'];?>&emsp;&emsp;退款金额<?php echo $lang['nc_colon'];?><?php echo $lang['currency'];?><?php echo $val['refund_amount']; ?>&emsp;备注<?php echo $lang['nc_colon'];?><?php echo $val['doctors_name'];?></td>
      </tr>
    <?php } ?>
    <?php } ?>
    <?php if(is_array($output['return_list']) and !empty($output['return_list'])) { ?>
      <tr>
      	<th>退货记录</th>
      </tr>
      <?php foreach($output['return_list'] as $val) { ?>
      <tr>
        <td>发生时间<?php echo $lang['nc_colon'];?><?php echo date("Y-m-d H:i:s",$val['admin_time']); ?>&emsp;&emsp;退货单号<?php echo $lang['nc_colon'];?><?php echo $val['refund_sn'];?>&emsp;&emsp;退款金额<?php echo $lang['nc_colon'];?><?php echo $lang['currency'];?><?php echo $val['refund_amount']; ?>&emsp;备注<?php echo $lang['nc_colon'];?><?php echo $val['doctors_name'];?></td>
      </tr>
    <?php } ?>
    <?php } ?>
    <?php if(is_array($output['appointment_log']) and !empty($output['appointment_log'])) { ?>
      <tr>
      	<th><?php echo $lang['appointment_handle_history'];?></th>
      </tr>
      <?php foreach($output['appointment_log'] as $val) { ?>
      <tr>
        <td>
          <?php echo $val['log_role']; ?> <?php echo $val['log_user']; ?>&emsp;<?php echo $lang['appointment_show_at'];?>&emsp;<?php echo date("Y-m-d H:i:s",$val['log_time']); ?>&emsp;<?php echo $val['log_msg']; ?>
        </td>
      </tr>
      <?php } ?>
    <?php } ?>
    </tbody>
    <tfoot>
      <tr class="tfoot">
        <td><a href="JavaScript:void(0);" class="btn" onclick="history.go(-1)"><span><?php echo $lang['nc_back'];?></span></a></td>
      </tr>
    </tfoot>
  </table>
</div>
