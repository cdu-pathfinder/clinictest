<?php defined('InclinicNC') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="alert alert-block mt10">
  <ul class="mt5">
    <li>1、可以对待发货的订单进行发货操作，发货时可以设置收货人和发货人信息，填写一些备忘信息，选择相应的物流服务，打印发货单。</li>
    <li>2、已经设置为发货中的订单，您还可以继续编辑上次的发货信息。</li>
    <li>3、如果因物流等原因造成买家不能及时收货，您可使用点击延迟收货按钮来延迟系统的自动收货时间。</li>
  </ul>
</div>
<form method="get" action="index.php" target="_self">
  <table class="search-form">
    <input type="hidden" name="act" value="clic_deliver" />
    <input type="hidden" name="op" value="index" />
    <?php if ($_GET['state'] !='') { ?>
    <input type="hidden" name="state" value="<?php echo $_GET['state']; ?>" />
    <?php } ?>
    <tr>
      <td></td>
      <th><?php echo $lang['clic_appointment_add_time'];?></th>
      <td class="w240"><input type="text" class="text w70" name="query_start_date" id="query_start_date" value="<?php echo $_GET['query_start_date']; ?>" /><label class="add-on"><i class="icon-calendar"></i></label>
        &nbsp;&#8211;&nbsp;
        <input id="query_end_date" class="text w70" type="text" name="query_end_date" value="<?php echo $_GET['query_end_date']; ?>" /><label class="add-on"><i class="icon-calendar"></i></label></td>
      <th><?php echo $lang['clic_appointment_buyer'];?></span></th>
      <td class="w100"><input type="text" class="text w80" name="buyer_name" value="<?php echo trim($_GET['buyer_name']); ?>" /></td>
      <th><?php echo $lang['clic_appointment_appointment_sn'];?></th>
      <td class="w160"><input type="text" class="text w150" name="appointment_sn" value="<?php echo trim($_GET['appointment_sn']); ?>" /></td>
      <td class="w70 tc"><label class="submit-bappointment">
          <input type="submit" class="submit"value="<?php echo $lang['clic_appointment_search'];?>" />
        </label></td>
    </tr>
  </table>
</form>
<table class="ncsc-table-style appointment deliver">
  <?php if (is_array($output['appointment_list']) and !empty($output['appointment_list'])) { ?>
  <?php foreach($output['appointment_list'] as $appointment_id => $appointment) {?>
  <tbody>
    <tr>
      <td colspan="21" class="sep-row"></td>
    </tr>
    <tr>
      <th colspan="21"><span class="ml5"><?php echo $lang['clic_appointment_appointment_sn'].$lang['nc_colon'];?><strong><?php echo $appointment['appointment_sn']; ?></strong></span><span><?php echo $lang['clic_appointment_add_time'].$lang['nc_colon'];?><em class="doctors-time"><?php echo date("Y-m-d H:i:s",$appointment['add_time']); ?></em></span>
        <?php if (!empty($appointment['extend_appointment_common']['shipping_time'])) {?>
        <span><?php echo '发货时间'.$lang['nc_colon'];?><em class="doctors-time"><?php echo date("Y-m-d H:i:s",$appointment['extend_appointment_common']['shipping_time']); }?></em></span> <span class="fr mr10">
        <?php if ($appointment['shipping_code'] != ''){?>
        <a href="index.php?act=clic_deliver&op=search_deliver&appointment_sn=<?php echo $appointment['appointment_sn']; ?>" class="ncsc-btn-mini"><i class="icon-compass"></i><?php echo $lang['clic_appointment_show_deliver'];?></a>
        <?php }?>
        <a href="index.php?act=clic_appointment_print&appointment_id=<?php echo $appointment['appointment_id'];?>" target="_blank"  class="ncsc-btn-mini" title="<?php echo $lang['clic_show_appointment_printappointment'];?>"/><i class="icon-print"></i><?php echo $lang['clic_show_appointment_printappointment'];?></a></span></th>
    </tr>
    <?php foreach($appointment['extend_appointment_doctors'] as $k => $doctors) { ?>
    <tr>
      <td class="bdl w10"></td>
      <td class="w50"><div class="pic-thumb"><a href="<?php echo urlclinic('doctors','index',array('doctors_id'=>$doctors['doctors_id']));?>" target="_blank"><img src="<?php echo cthumb($doctors['doctors_image'],60,$doctors['clic_id']); ?>" /></a></div></td>
      <td class="tl"><dl class="doctors-name">
          <dt><a target="_blank" href="<?php echo urlclinic('doctors','index',array('doctors_id'=>$doctors['doctors_id']));?>"><?php echo $doctors['doctors_name']; ?></a></dt>
          <dd><strong>￥<?php echo $doctors['doctors_price']; ?></strong>&nbsp;x&nbsp;<em><?php echo $doctors['doctors_num']; ?></em>件</dd>
        </dl></td>
      <?php if ((count($appointment['extend_appointment_doctors']) > 1 && $k == 0) || (count($appointment['extend_appointment_doctors']) == 1)){?>
      <td class="bdl bdr appointment-info w500" rowspan="<?php echo count($appointment['extend_appointment_doctors']);?>"><dl>
          <dt><?php echo $lang['clic_deliver_buyer_name'].$lang['nc_colon'];?></dt>
          <dd><?php echo $appointment['buyer_name']; ?> <span member_id="<?php echo $appointment['buyer_id'];?>"></span>
            <?php if(!empty($appointment['extend_member']['member_qq'])){?>
            <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $appointment['extend_member']['member_qq'];?>&site=qq&menu=yes" title="QQ: <?php echo $appointment['extend_member']['member_qq'];?>"><img bappointment="0" src="http://wpa.qq.com/pa?p=2:<?php echo $appointment['extend_member']['member_qq'];?>:52" style=" vertical-align: middle;"/></a>
            <?php }?>
            <?php if(!empty($appointment['extend_member']['member_ww'])){?>
            <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&uid=<?php echo $appointment['extend_member']['member_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" class="vm" ><img bappointment="0" src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo $appointment['extend_member']['member_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" alt="Wang Wang" style=" vertical-align: middle;"/></a>
            <?php }?>
          </dd>
        </dl>
        <dl>
          <dt><?php echo '收货人'.$lang['nc_colon'];?></dt>
          <dd>
            <div class="alert alert-info m0">
              <p><i class="icon-user"></i><?php echo $appointment['extend_appointment_common']['reciver_name']?><span class="ml30" title="<?php echo '电话';?>"><i class="icon-phone"></i><?php echo $appointment['extend_appointment_common']['reciver_info']['phone'];?></span></p>
              <p class="mt5" title="<?php echo $lang['clic_deliver_buyer_address'];?>"><i class="icon-map-marker"></i><?php echo $appointment['extend_appointment_common']['reciver_info']['address'];?></p>
              <?php if ($appointment['extend_appointment_common']['appointment_message'] != '') {?>
              <p class="mt5" title="<?php echo $lang['clic_deliver_buyer_address'];?>"><i class="icon-map-marker"></i><?php echo $appointment['extend_appointment_common']['appointment_message'];?></p>
              <?php } ?>
            </div>
          </dd>
        </dl>
        <dl>
          <dt><?php echo $lang['clic_deliver_shipping_amount'].$lang['nc_colon'];?> </dt>
          <dd>
            <?php if (!empty($appointment['shipping_fee']) && $appointment['shipping_fee'] != '0.00'){?>
            ￥<?php echo $appointment['shipping_fee'];?>
            <?php }else{?>
            <?php echo $lang['nc_common_shipping_free'];?>
            <?php }?>
            <?php if (empty($appointment['lock_state'])) {?>
            <?php if ($appointment['appointment_state'] == appointment_STATE_PAY) {?>
            <span><a href="index.php?act=clic_deliver&op=send&appointment_id=<?php echo $appointment['appointment_id'];?>" class="ncsc-btn-mini ncsc-btn-green fr"><i class="icon-truck"></i><?php echo $lang['clic_appointment_send'];?></a></span>
            <?php } elseif ($appointment['appointment_state'] == appointment_STATE_SEND){?>
            <span>
            <a href="javascript:void(0)" class="ncsc-btn-mini ncsc-btn-orange ml5 fr" uri="index.php?act=clic_deliver&op=delay_receive&appointment_id=<?php echo $appointment['appointment_id']; ?>" dialog_width="480" dialog_title="延迟收货" nc_type="dialog" dialog_id="seller_appointment_delay_receive" id="appointment<?php echo $appointment['appointment_id']; ?>_action_delay_receive" /><i class="icon-time"></i></i>延迟收货</a>
            <a href="index.php?act=clic_deliver&op=send&appointment_id=<?php echo $appointment['appointment_id'];?>" class="ncsc-btn-mini ncsc-btn-acidblue fr"><i class="icon-edit"></i><?php echo $lang['clic_deliver_modify_info'];?></a>
            </span>
            <?php }?>
            <?php }?>
          </dd>
        </dl></td>
      <?php }?>
    </tr>
    <?php }?>
    <?php } } else { ?>
    <tr>
      <td colspan="21" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php if (!empty($output['appointment_array'])) { ?>
    <tr>
      <td colspan="21"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" ></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
$(function(){
    $('#query_start_date').datepicker({dateFormat: 'yy-mm-dd'});
    $('#query_end_date').datepicker({dateFormat: 'yy-mm-dd'});
});
</script> 
