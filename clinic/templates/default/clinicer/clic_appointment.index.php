<?php defined('InclinicNC') or exit('Access Invalid!');?>
<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<form method="get" action="index.php" target="_self">
  <table class="search-form">
    <input type="hidden" name="act" value="clic_appointment" />
    <input type="hidden" name="op" value="index" />
    <?php if ($_GET['state_type']) { ?>
    <input type="hidden" name="state_type" value="<?php echo $_GET['state_type']; ?>" />
    <?php } ?>
    <tr>
      <td>&nbsp;</td>
      <th><?php echo $lang['clic_appointment_add_time'];?></th>
      <td class="w240"><input type="text" class="text w70" name="query_start_date" id="query_start_date" value="<?php echo $_GET['query_start_date']; ?>" /><label class="add-on"><i class="icon-calendar"></i></label>&nbsp;&#8211;&nbsp;<input id="query_end_date" class="text w70" type="text" name="query_end_date" value="<?php echo $_GET['query_end_date']; ?>" /><label class="add-on"><i class="icon-calendar"></i></label></td>
       <th><?php echo $lang['clic_appointment_buyer'];?></th>
      <td class="w100"><input type="text" class="text w80" name="buyer_name" value="<?php echo $_GET['buyer_name']; ?>" /></td>
      <th><?php echo $lang['clic_appointment_appointment_sn'];?></th>
      <td class="w160"><input type="text" class="text w150" name="appointment_sn" value="<?php echo $_GET['appointment_sn']; ?>" /></td><td class="w70 tc"><label class="submit-bappointment"><input type="submit" class="submit" value="<?php echo $lang['clic_appointment_search'];?>" /></label></td>
    </tr>
  </table>
</form>
<table class="ncsc-table-style appointment">
  <thead>
    <tr>
      <th class="w10"></th>
      <th colspan="2"><?php echo $lang['clic_appointment_doctors_detail'];?></th>
      <th class="w70"><?php echo $lang['clic_appointment_doctors_single_price'];?></th>
      <th class="w50"><?php echo $lang['clic_show_appointment_amount'];?></th>
      <th class="w110"><?php echo $lang['clic_appointment_buyer'];?></th>
      <th class="w110"><?php echo $lang['clic_appointment_sum'];?></th>
      <th class="w110"><?php echo $lang['clic_appointment_appointment_stateop'];?></th>
    </tr>
  </thead>
  <?php if (is_array($output['appointment_list']) and !empty($output['appointment_list'])) { ?>
  <?php foreach($output['appointment_list'] as $appointment_id => $appointment) { ?>
  <tbody>
    <tr>
      <td colspan="20" class="sep-row"></td>
    </tr>
    <tr>
      <th colspan="20"><span class="fl ml10"><?php echo $lang['clic_appointment_appointment_sn'].$lang['nc_colon'];?><span class="doctors-num"><em><?php echo $appointment['appointment_sn']; ?></em>
        <?php if ($appointment['appointment_from'] == 2){?><i class="icon-mobile-phone"></i><?php }?>
        </span></span> <span class="fl ml20"><?php echo $lang['clic_appointment_add_time'].$lang['nc_colon'];?><em class="doctors-time"><?php echo date("Y-m-d H:i:s",$appointment['add_time']); ?></em></span>
<span class="fr mr5">
<?php if ($appointment['if_deliver']) { ?>
        <a href='index.php?act=clic_deliver&op=search_deliver&appointment_sn=<?php echo $appointment['appointment_sn']; ?>' class="ncsc-btn-mini"><i class="icon-compass"></i><?php echo $lang['clic_appointment_show_deliver'];?>
        </a>
        <?php } ?>
        <a href="index.php?act=clic_appointment&op=show_appointment&appointment_id=<?php echo $appointment_id;?>" target="_blank" class="ncsc-btn-mini"><i class="icon-file-text-alt"></i><?php echo $lang['clic_appointment_view_appointment'];?></a>
        <a href="index.php?act=clic_appointment_print&appointment_id=<?php echo $appointment_id;?>" class="ncsc-btn-mini" target="_blank" title="打印发货单"/><i class="icon-print"></i>打印发货单</a></span>

        </th>
    </tr>
    <?php foreach($appointment['extend_appointment_doctors'] as $k => $doctors) { ?>
    <tr>
      <td class="bdl"></td>
      <td class="w50"><div class="pic-thumb"><a href="<?php echo urlclinic('doctors','index',array('doctors_id'=>$doctors['doctors_id']));?>" target="_blank"><img src="<?php echo thumb($doctors,60);?>" /></a></div></td>
      <td class="tl"><dl class="doctors-name">
          <dt><a target="_blank" href="<?php echo urlclinic('doctors','index',array('doctors_id'=>$doctors['doctors_id']));?>"><?php echo $doctors['doctors_name']; ?></a></dt>
          <dd><?php if ($doctors['doctors_type'] != 1){?><span class="sale-type"><?php echo appointmentdoctorsType($doctors['doctors_type']);?></span><?php }?></dd>
          </dl></td>
      <td>￥<?php echo $doctors['doctors_price']; ?></td>
      <td><?php echo $doctors['doctors_num']; ?></td>
      <?php if ((count($appointment['extend_appointment_doctors']) > 1 && $k ==0) || (count($appointment['extend_appointment_doctors']) == 1)){?>
      <td class="bdl" rowspan="<?php echo count($appointment['extend_appointment_doctors']);?>">
        <div class="buyer"><?php echo $appointment['buyer_name'];?>
          <p member_id="<?php echo $appointment['buyer_id'];?>">
            <?php if(!empty($appointment['extend_member']['member_qq'])){?>
            <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $appointment['extend_member']['member_qq'];?>&site=qq&menu=yes" title="QQ: <?php echo $appointment['extend_member']['member_qq'];?>"><img bappointment="0" src="http://wpa.qq.com/pa?p=2:<?php echo $appointment['extend_member']['member_qq'];?>:52" style=" vertical-align: middle;"/></a>
            <?php }?>
            <?php if(!empty($appointment['extend_member']['member_ww'])){?>
            <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&uid=<?php echo $appointment['extend_member']['member_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" ><img bappointment="0" src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo $appointment['extend_member']['clic_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" alt="Wang Wang" style=" vertical-align: middle;" /></a>
            <?php }?>
          </p>
          <div class="buyer-info"> <em></em>
            <div class="con">
              <h3><i></i><span><?php echo $lang['clic_appointment_buyer_info'];?></span></h3>
              <dl>
                <dt><?php echo $lang['clic_appointment_receiver'].$lang['nc_colon'];?></dt>
                <dd><?php echo $appointment['extend_appointment_common']['reciver_name'];?></dd>
              </dl>
              <dl>
                <dt><?php echo $lang['clic_appointment_phone'].$lang['nc_colon'];?></dt>
                <dd><?php echo $appointment['extend_appointment_common']['reciver_info']['phone'];?></dd>
              </dl>
              <dl>
                <dt>地址<?php echo $lang['nc_colon'];?></dt>
                <dd><?php echo $appointment['extend_appointment_common']['reciver_info']['address'];?></dd>
              </dl>
            </div>
          </div>
        </div></td>
      <td class="bdl" rowspan="<?php echo count($appointment['extend_appointment_doctors']);?>"><p class="ncsc-appointment-amount">￥<?php echo $appointment['appointment_amount']; ?></p>
        <p class="doctors-pay"><?php echo $appointment['payment_name']; ?></p>
        <p class="doctors-freight">
          <?php if ($appointment['shipping_fee'] > 0){?>
          (<?php echo $lang['clic_show_appointment_shipping_han']?>运费<?php echo $appointment['shipping_fee'];?>)
          <?php }else{?>
          <?php echo $lang['nc_common_shipping_free'];?>
          <?php }?>
        </p>
        </td>
      <td class="bdl bdr" rowspan="<?php echo count($appointment['extend_appointment_doctors']);?>">
        <p><?php echo $appointment['state_desc']; ?>
          <?php if($appointment['evaluation_time']) { ?>
          <br/>
          <?php echo $lang['clic_appointment_evaluated'];?>
          <?php } ?>
        </p>

        <!-- 取消订单 -->
        <?php if($appointment['if_cancel']) { ?>
        <p><a href="javascript:void(0)" class="ncsc-btn-mini ncsc-btn-red mt5" nc_type="dialog" uri="index.php?act=clic_appointment&op=change_state&state_type=appointment_cancel&appointment_sn=<?php echo $appointment['appointment_sn']; ?>&appointment_id=<?php echo $appointment['appointment_id']; ?>" dialog_title="<?php echo $lang['clic_appointment_cancel_appointment'];?>" dialog_id="seller_appointment_cancel_appointment" dialog_width="400" id="appointment<?php echo $appointment['appointment_id']; ?>_action_cancel" /><i class="icon-remove-circle"></i><?php echo $lang['clic_appointment_cancel_appointment'];?></a></p>
        <?php } ?>

        <!-- 修改价格 -->
        <?php if ($appointment['if_modify_price']) { ?>
        <p><a href="javascript:void(0)" class="ncsc-btn-mini ncsc-btn-orange mt10" uri="index.php?act=clic_appointment&op=change_state&state_type=modify_price&appointment_sn=<?php echo $appointment['appointment_sn']; ?>&appointment_id=<?php echo $appointment['appointment_id']; ?>" dialog_width="480" dialog_title="<?php echo $lang['clic_appointment_modify_price'];?>" nc_type="dialog"  dialog_id="seller_appointment_adjust_fee" id="appointment<?php echo $appointment['appointment_id']; ?>_action_adjust_fee" /><i class="icon-pencil"></i>修改运费</a></p>
        <?php }?>

        <!-- 发货 -->
        <?php if ($appointment['if_send']) { ?>
        <p><a class="ncsc-btn-mini ncsc-btn-green mt10" href="index.php?act=clic_deliver&op=send&appointment_id=<?php echo $appointment['appointment_id']; ?>"/><i class="icon-truck"></i><?php echo $lang['clic_appointment_send'];?></a></p>
        <?php } ?>

        <!-- 锁定 -->
        <?php if ($appointment['if_lock']) {?>
        <p><?php echo '退款退货中';?></p>
        <?php }?>
        </td>
        <?php } ?>
    </tr>
    <?php }?>
    <?php } } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php if (is_array($output['appointment_list']) and !empty($output['appointment_list'])) { ?>
    <tr>
      <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
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
    $('.checkall_s').click(function(){
        var if_check = $(this).attr('checked');
        $('.checkitem').each(function(){
            if(!this.disabled)
            {
                $(this).attr('checked', if_check);
            }
        });
        $('.checkall_s').attr('checked', if_check);
    });
});
</script>
