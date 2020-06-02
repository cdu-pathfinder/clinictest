<?php defined('InclinicNC') or exit('Access Invalid!');?>

<div class="wrap-shadow">
  <div class="wrap-all ncu-appointment-view">
    <h2><?php echo $lang['member_show_appointment_desc'];?></h2>
    <dl class="box">
      <dt><?php echo $lang['member_change_appointment_no'].$lang['nc_colon'];?></dt>
      <dd><?php echo $output['appointment_info']['appointment_sn']; ?> </dd>
      <dt><?php echo $lang['member_appointment_time'].$lang['nc_colon'];?></dt>
      <dd><?php echo date("Y-m-d H:i:s",$output['appointment_info']['add_time']); ?></dd>
    </dl>
  <div class="wrap-all ncu-appointment-view">
    <h3><?php echo $lang['member_show_appointment_seller_info'];?></h3>
    <dl>
      <dt><?php echo $lang['member_evaluation_clic_name'].$lang['nc_colon'];?></dt>
      <dd><?php echo $output['appointment_info']['extend_clic']['clic_name']; ?></dd>
      <dt><?php echo $lang['member_address_phone_num'].$lang['nc_colon'];?></dt>
      <dd><?php echo $output['appointment_info']['extend_clic']['clic_tel']; ?></dd>
      <dt><?php echo $lang['member_address_location'].$lang['nc_colon'];?></dt>
      <dd><?php echo $output['appointment_info']['extend_clic']['area_info'].'&nbsp;'.$output['appointment_info']['extend_clic']['clic_address']; ?></dd>
      <dt>QQ<?php echo $lang['nc_colon'];?></dt>
      <dd><?php echo $output['appointment_info']['extend_clic']['clic_qq']; ?></dd>
      <dt><?php echo $lang['member_show_appointment_wangwang'].$lang['nc_colon'];?></dt>
      <dd><?php echo $output['appointment_info']['extend_clic']['clic_ww']; ?></dd>
    </dl><div class="clear"></div>
    <!--订单信息-->
    <h3><?php echo $lang['member_show_appointment_info'];?></h3>
    <table class="ncu-table-style">
      <thead>
        <tr>
          <th class="w10"></th>
          <th class="w70"></th>
          <th><?php echo $lang['member_appointment_doctors_name'];?></th>
          <th><?php echo $lang['member_appointment_price'];?></th>
          <th><?php echo $lang['member_appointment_amount'];?></th>
          <th><?php echo $lang['member_appointment_doctors_price'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($output['appointment_info']['extend_appointment_doctors'] as $doctors) {?>
        <tr class="bd-line">
          <td></td>
          <td><div class="doctors-pic-small"><span class="thumb size60"><i></i><a target="_blank" href="<?php echo urlclinic('doctors','index',array('doctors_id'=>$doctors['doctors_id'])); ?>"><img src="<?php echo thumb($doctors,60); ?>" /></a></span></div></td>
          <td><dl class="doctors-name">
              <dt><a target="_blank" href="<?php echo urlclinic('doctors','index',array('doctors_id'=>$doctors['doctors_id'])); ?>"><?php echo $doctors['doctors_name']; ?></a></dt>
              <dd><?php echo appointmentdoctorsType($doctors['doctors_type']); ?></dd>
            </dl></td>
          <td><?php echo $doctors['doctors_price']; ?></td>
          <td><?php echo $doctors['doctors_num']; ?></td>
          <td><?php echo sprintf('%.2f',$doctors['doctors_num']*$doctors['doctors_price']); ?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20">
            &emsp;<?php echo $lang['member_appointment_sum'].$lang['nc_colon'];?><b><?php echo $lang['currency'];?><?php echo $output['appointment_info']['appointment_amount']; ?></b>
	<?php if($output['appointment_info']['refund_amount'] > 0) { ?>
	(<?php echo $lang['member_appointment_refund'];?>:<?php echo $lang['currency'].$output['appointment_info']['refund_amount'];?>)
	<?php } ?>
          <?php if(!empty($output['appointment_info']['shipping_fee']) && $output['appointment_info']['shipping_fee'] != '0.00'){ ?>
          <?php echo $lang['member_show_appointment_tp_fee'].$lang['nc_colon'];?><span><?php echo $lang['currency'];?><?php echo $output['appointment_info']['shipping_fee']; ?> <?php if ($output['appointment_info']['shipping_name'] != ''){echo '('.$output['appointment_info']['shipping_name'].')';};?></span>
          <?php }else{?>
          	<?php echo $lang['nc_common_shipping_free'];?>
          <?php }?>
           </td>
        </tr>
      </tfoot>
    </table>
    <ul class="appointment_detail_list">
      <?php if($output['appointment_info']['payment_name']) { ?>
      <li><?php echo $lang['member_appointment_pay_method'].$lang['nc_colon'];?><?php echo $output['appointment_info']['payment_name']; ?></li>
      <?php } ?>
      <li><?php echo $lang['member_appointment_time'].$lang['nc_colon'];?><?php echo date("Y-m-d H:i:s",$output['appointment_info']['add_time']); ?></li>
      <?php if(intval($output['appointment_info']['payment_time'])) { ?>
      <li><?php echo $lang['member_show_appointment_pay_time'].$lang['nc_colon'];?><?php echo date("Y-m-d H:i:s",$output['appointment_info']['payment_time']); ?></li>
      <?php } ?>
      <?php if($output['appointment_info']['shipping_time']) { ?>
      <li><?php echo $lang['member_show_appointment_send_time'].$lang['nc_colon'];?><?php echo date("Y-m-d H:i:s",$output['appointment_info']['shipping_time']); ?></li>
      <?php } ?>
      <?php if(intval($output['appointment_info']['finnshed_time'])) { ?>
      <li><?php echo $lang['member_show_appointment_finish_time'].$lang['nc_colon'];?><?php echo date("Y-m-d H:i:s",$output['appointment_info']['finnshed_time']); ?></li>
      <?php } ?>
      <?php if($output['appointment_info']['payment_code'] != 'offline' && !in_array($output['appointment_info']['appointment_state'],array(appointment_STATE_CANCEL,appointment_STATE_NEW))) { ?>
      <li><?php echo '付款单号'.$lang['nc_colon'];?><?php echo $output['appointment_info']['pay_sn']; ?></li>
      <?php } ?>
    </ul>

    <!-- S 促销信息 -->
    <?php if(!empty($output['appointment_info']['extend_appointment_common']['promotion_info']) || !empty($output['appointment_info']['extend_appointment_common']['voucher_code'])){ ?>
    <h3><?php echo $lang['nc_promotion'];?></h3>
    <div style="height:30px;line-height:30px;">
        <?php if(!empty($output['appointment_info']['extend_appointment_common']['promotion_info'])){ ?>
        <span style="color:red">满即送</span> <?php echo $output['appointment_info']['extend_appointment_common']['promotion_info'];?></a>
        <?php } ?>
        <?php if(!empty($output['appointment_info']['extend_appointment_common']['voucher_code'])){ ?>
        <span style="color:red">代金券</span> 面额 <?php echo $lang['nc_colon'];?> <?php echo $output['appointment_info']['extend_appointment_common']['voucher_price'];?>
         编码 : <?php echo $output['appointment_info']['extend_appointment_common']['voucher_code'];?></a>
        <?php } ?>
    </div>
    <?php } ?>
    <!-- E 促销信息 -->

    <!-- 物流信息 -->
    <h3><?php echo $lang['member_show_appointment_shipping_info'];?></h3>
    <dl class="logistics">
      <?php if (!empty($output['appointment_info']['shipping_code'])) { ?>
      <dt><?php echo $lang['member_show_appointment_shipping_no'].$lang['nc_colon'];?></dt>
      <dd><?php echo $output['appointment_info']['shipping_code'];?></dd>
      <?php } ?>
      <?php if($output['appointment_info']['extend_appointment_common']['appointment_message']) { ?>
      <dt><?php echo $lang['member_show_appointment_buyer_message'].$lang['nc_colon'];?></dt>
      <dd><?php echo $output['appointment_info']['extend_appointment_common']['appointment_message']; ?></dd>
      <?php } ?>
      <dt class="cb"><?php echo $lang['member_show_appointment_receiver'].$lang['nc_colon'];?></dt>
      <dd style="width:90%;">
      <?php echo $output['appointment_info']['extend_appointment_common']['reciver_name'];?>&nbsp;
      <?php echo @$output['appointment_info']['extend_appointment_common']['reciver_info']['phone'];?>&nbsp;
      <?php echo @$output['appointment_info']['extend_appointment_common']['reciver_info']['address'];?>
      </dd>
      <?php if(!empty($output['daddress_info'])) { ?>
      <dt>发货人<?php echo $lang['nc_colon'];?></dt>
      <dd style="width:90%"><?php echo $output['daddress_info']['seller_name']; ?>&nbsp;<?php echo $output['daddress_info']['telphone'];?>&nbsp;<?php echo $output['daddress_info']['area_info'];?>&nbsp;<?php echo $output['daddress_info']['address'];?>&nbsp;<?php echo $output['daddress_info']['company'];?></dd>
      <?php } ?>
    </dl>

    <!-- 发票信息 -->
    <h3>发票信息</h3>
    <dl class="logistics">
    <?php foreach ((array)$output['appointment_info']['extend_appointment_common']['invoice_info'] as $key => $value){?>
      <dt class = 'cb'><?php echo $key.$lang['nc_colon'];?></dt>
      <dd style="width:90%;"><?php echo $value;?></dd>
    <?php } ?>
    </dl>

    <!-- 操作历史 -->
    <?php if(is_array($output['appointment_log'])) { ?>
    <h3><?php echo $lang['member_show_appointment_handle_history'];?></h3>
    <ul class="log-list">
      <?php foreach($output['appointment_log'] as $val) { ?>
      <li> <?php echo $val['log_role'];?>&emsp;<?php echo $lang['member_show_appointment_at'];?>&emsp;<?php echo date("Y-m-d H:i:s",$val['log_time']); ?>&emsp;<?php echo $val['log_msg'];?></li>
      <?php } ?>
    </ul>
    <?php } ?>

    <!-- 退款记录 -->
    <?php if(is_array($output['refund_list']) and !empty($output['refund_list'])) { ?>
    <h3><?php echo $lang['member_appointment_refund'];?></h3>
    <ul class="log-list">
      <?php foreach($output['refund_list'] as $val) { ?>
      <li> 发生时间<?php echo $lang['nc_colon'];?><?php echo date("Y-m-d H:i:s",$val['admin_time']); ?>&emsp;&emsp;退款单号<?php echo $lang['nc_colon'];?><?php echo $val['refund_sn'];?>&emsp;&emsp;退款金额<?php echo $lang['nc_colon'];?><?php echo $lang['currency'];?><?php echo $val['refund_amount']; ?>&emsp;备注<?php echo $lang['nc_colon'];?><?php echo $val['doctors_name'];?></li>
      <?php } ?>
    </ul>
    <?php } ?>

    <!-- 退货记录 -->
    <?php if(is_array($output['return_list']) and !empty($output['return_list'])) { ?>
    <h3><?php echo $lang['member_appointment_return'];?></h3>
    <ul class="log-list">
      <?php foreach($output['return_list'] as $val) { ?>
      <li> 发生时间<?php echo $lang['nc_colon'];?><?php echo date("Y-m-d H:i:s",$val['admin_time']); ?>&emsp;&emsp;退款单号<?php echo $lang['nc_colon'];?><?php echo $val['refund_sn'];?>&emsp;&emsp;退款金额<?php echo $lang['nc_colon'];?><?php echo $lang['currency'];?><?php echo $val['refund_amount']; ?>&emsp;备注<?php echo $lang['nc_colon'];?><?php echo $val['doctors_name'];?></li>
      <?php } ?>
    </ul>
    <?php } ?>
  </div>
</div>
</div>