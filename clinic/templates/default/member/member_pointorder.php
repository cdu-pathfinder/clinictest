<?php defined('InclinicNC') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <table class="ncu-table-style appointment">
    <thead>
      <tr>
        <th class="w10"></th>
        <th class="w70"></th>
        <th class="tl"><?php echo $lang['member_pointappointment_info_prodinfo'];?></th>
        <th class="w90"><?php echo $lang['member_pointappointment_exchangepoints'];?></th>
        <th class="w90"><?php echo $lang['member_pointappointment_exnum'];?></th>
        <th class="w110"><?php echo $lang['member_pointappointment_exchangepoints_shippingfee'];?></th>
        <th class="w110"><?php echo $lang['member_pointappointment_appointmentstate_handle'];?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(count($output['appointment_list'])>0){ ?>
      <?php foreach ($output['appointment_list'] as $val) { ?>
      <tr>
        <td colspan="19" class="sep-row"></td>
      </tr>
      <tr>
        <th colspan="20"><span class="fl ml10"><?php echo $lang['member_pointappointment_appointmentsn'].$lang['nc_colon'];?><em class="doctors-num"><?php echo $val['point_appointmentsn']; ?></em></span><span class="fl ml20"><?php echo $lang['member_pointappointment_addtime'].$lang['nc_colon'];?><em class="doctors-time"><?php echo @date("Y-m-d H:i:s",$val['point_addtime']); ?></em></span><span class="fl ml20"><i></i><a href="index.php?act=member_pointappointment&op=appointment_info&appointment_id=<?php echo $val['point_appointmentid']; ?>" target="_blank" class="nc-show-appointment" ><?php echo $lang['member_pointappointment_viewinfo'];?></a></span></th>
      </tr>
      <?php foreach($val['prodlist'] as $k=>$v) {?>
      <tr>
        <td class="bdl"></td>
        <td><div class="doctors-pic-small"> <span class="thumb size60"> <i></i><a href="<?php echo urlclinic('pointprod', 'pinfo', array('id' => $v['point_doctorsid']));?>" target="_blank"> <img src="<?php echo UPLOAD_SITE_URL.DS.$v['point_doctorsimage']; ?>" onerror="this.src='<?php echo UPLOAD_SITE_URL.DS.defaultdoctorsImage(60);?>'" onload="javascript:DrawImage(this,60,60);"/></a></span></div></td>
        <td class="tl"><dl class="doctors-name">
            <dt><a href="<?php echo urlclinic('pointprod', 'pinfo', array('id' => $v['point_doctorsid']));?>" target="_blank"><?php echo $v['point_doctorsname']; ?></a></dt>
          </dl></td>
        <td><?php echo $v['point_doctorspoints']; ?></td>
        <td><?php echo $v['point_doctorsnum']; ?></td>
        <?php if ((count($val['prodlist']) > 1 && $k ==0) || (count($val['prodlist']) == 1)){?>
        <td class="bdl" rowspan="<?php echo count($val['prodlist']);?>">
        <p class="price"><strong><?php echo $val['point_allpoint']; ?></strong></p>
          </td>
        <td class="bdl bdr" rowspan="<?php echo count($val['prodlist']);?>"><p><?php echo $val['point_appointmentstatetext']['appointment_state']; ?><?php echo $val['point_appointmentstatetext']['change_state'] == ''?'':','.$val['point_appointmentstatetext']['change_state']; ?></p>
          
          <?php if ($val['point_shippingcharge'] == 1 && $val['point_appointmentstate'] == 11) { ?>
          <p><?php echo $val['point_paymentname']; ?></p>
          <?php } ?>
          <?php if ($val['point_appointmentstate'] == 30) { ?>
          <p><a href="javascript:void(0)" onclick="drop_confirm('<?php echo $lang['member_pointappointment_confirmreceivingtip']; ?>','index.php?act=member_pointappointment&op=receiving_appointment&appointment_id=<?php echo $val['point_appointmentid']; ?>');" class="ncu-btn7 mt5" ><?php echo $lang['member_pointappointment_confirmreceiving']; ?></a></p>
          <?php } ?>
          <?php if (($val['point_shippingcharge'] == 1 && $val['point_appointmentstate'] == 10) || ($val['point_shippingcharge'] != 1 && $val['point_appointmentstate'] == 20)) { ?>
          <p><a href="javascript:void(0)" onclick="drop_confirm('<?php echo $lang['member_pointappointment_cancel_confirmtip']; ?>','index.php?act=member_pointappointment&op=cancel_appointment&appointment_id=<?php echo $val['point_appointmentid']; ?>');" style="color:#F30; text-decoration:underline;"><?php echo $lang['member_pointappointment_cancel_title']; ?></a></p>
          <?php } ?><?php if ($val['point_shippingcharge'] == 1 && $val['point_appointmentstate'] == 10) { ?>
          <p><a href="index.php?act=pointcart&op=step3&appointment_id=<?php echo $val['point_appointmentid']; ?>" class="ncu-btn6 mt5" ><?php echo $lang['member_pointappointment_pay']; ?></a> </p>
          <?php } ?></td>
        <?php } ?>
      </tr>
      <?php } ?>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td colspan="20" class="norecord"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php if(count($output['appointment_list'])>0){ ?>
      <tr>
        <td colspan="20"><div class="pagination"><?php echo $output['page']; ?></div></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
</div>
