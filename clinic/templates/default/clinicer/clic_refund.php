<?php defined('InclinicNC') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<form method="get" action="index.php">
  <input type="hidden" name="act" value="clic_refund" />
  <input type="hidden" name="lock" value="<?php echo $_GET['lock']; ?>" />
  <table class="search-form">
    <tr>
      <td>&nbsp;</td>
      <th><?php echo $lang['refund_appointment_add_time'];?></th>
      <td class="w240"><input name="add_time_from" id="add_time_from" type="text" class="text w70" value="<?php echo $_GET['add_time_from']; ?>" /><label class="add-on"><i class="icon-calendar"></i></label> &#8211; <input name="add_time_to" id="add_time_to" type="text" class="text w70" value="<?php echo $_GET['add_time_to']; ?>" /><label class="add-on"><i class="icon-calendar"></i></label></td>
      <th class="w60">state</th>
      <td class="w80"><select name="state">
          <option value="" <?php if($_GET['state'] == ''){?>selected<?php }?>>all</option>
          <option value="1" <?php if($_GET['state'] == '1'){?>selected<?php }?>><?php echo $lang['refund_state_confirm']; ?></option>
          <option value="2" <?php if($_GET['state'] == '2'){?>selected<?php }?>><?php echo $lang['refund_state_yes']; ?></option>
          <option value="3" <?php if($_GET['state'] == '3'){?>selected<?php }?>><?php echo $lang['refund_state_no']; ?></option>
        </select></td>
      <th class="w120"><select name="type">
          <option value="appointment_sn" <?php if($_GET['type'] == 'appointment_sn'){?>selected<?php }?>><?php echo $lang['refund_appointment_appointmentsn']; ?></option>
          <option value="refund_sn" <?php if($_GET['type'] == 'refund_sn'){?>selected<?php }?>><?php echo $lang['refund_appointment_refundsn']; ?></option>
          <option value="buyer_name" <?php if($_GET['type'] == 'buyer_name'){?>selected<?php }?>><?php echo $lang['refund_appointment_buyer']; ?></option>
        </select></th>
      <td class="w160"><input type="text" class="text" name="key" value="<?php echo trim($_GET['key']); ?>" /></td>

      <td class="w70 tc"><label class="submit-bappointment"><input type="submit" class="submit" value="<?php echo $lang['nc_search'];?>" /></label></td>
    </tr>
  </table>
</form>
<table class="ncsc-table-style">
  <thead>
    <tr>
      <th class="w10"></th>
      <th colspan="2">Doctor appointment number/return number</th>
      <th class="w70"><?php echo $lang['refund_appointment_refund'];?></th>
      <th class="w90"><?php echo $lang['refund_appointment_buyer'];?></th>
      <th class="w120"><?php echo $lang['refund_appointment_add_time'];?></th>
      <th class="w80">state</th>
      <th class="w80">confirm</th>
      <th class="w90"><?php echo $lang['nc_handle'];?></th>
    </tr>
  </thead>
  <tbody>
    <?php if (is_array($output['refund_list']) && !empty($output['refund_list'])) { ?>
    <?php foreach ($output['refund_list'] as $key => $val) { ?>
    <tr class="bd-line" >
        <td></td>
		    <?php if ($val['doctors_id'] > 0) { ?>
        <td class="w50"><div class="pic-thumb">
            <a href="<?php echo urlclinic('doctors','index',array('doctors_id'=> $val['doctors_id']));?>" target="_blank"><img src="<?php echo thumb($val,60);?>"/></a></div></td>
        <td class="tl" title="<?php echo $val['clic_name']; ?>">
		<dl class="doctors-name">
		    <dt><a href="<?php echo urlclinic('doctors','index',array('doctors_id'=> $val['doctors_id']));?>" target="_blank"><?php echo $val['doctors_name']; ?></a></dt>
        <dd><?php echo $lang['refund_appointment_appointmentsn'].$lang['nc_colon'];?><a href="index.php?act=clic_appointment&op=show_appointment&appointment_id=<?php echo $val['appointment_id']; ?>" target="_blank"><?php echo $val['appointment_sn'];?></a></dd>
        <dd><?php echo $lang['refund_appointment_refundsn'].$lang['nc_colon'];?><?php echo $val['refund_sn']; ?></dd></dl></td>
		    <?php } else { ?>
        <td class="tl" title="<?php echo $val['clic_name']; ?>" colspan="2">
		<dl class="doctors-name">
		    <dt><?php echo $val['doctors_name']; ?></dt>
        <dd><?php echo $lang['refund_appointment_appointmentsn'].$lang['nc_colon'];?><a href="index.php?act=clic_appointment&op=show_appointment&appointment_id=<?php echo $val['appointment_id']; ?>" target="_blank"><?php echo $val['appointment_sn'];?></a></dd>
        <dd><?php echo $lang['refund_appointment_refundsn'].$lang['nc_colon'];?><?php echo $val['refund_sn']; ?></dd></dl></td>
		    <?php } ?>
        <td><?php echo $lang['currency'];?><?php echo $val['refund_amount'];?></td>
      <td><?php echo $val['buyer_name']; ?></td>
      <td><?php echo date("Y-m-d H:i:s",$val['add_time']);?></td>
      <td><?php echo $output['state_array'][$val['seller_state']]; ?></td>
      <td><?php echo $val['seller_state']==2 ? $output['admin_array'][$val['refund_state']]:'无'; ?></td>
      <td class="nscs-table-handle"><?php if ($val['seller_state'] == 1) { ?>
    	<span><a href="javascript:void(0)" class="btn-blue" nc_type="dialog" dialog_title="处理" dialog_id="refund_edit" dialog_width="480" uri="index.php?act=clic_refund&op=edit&refund_id=<?php echo $val['refund_id']; ?>"><i class="icon-edit"></i><p>处理</p></a></span>
    	<?php } else { ?>
    	<span><a href="javascript:void(0)" class="btn-orange" nc_type="dialog" dialog_title="<?php echo $lang['nc_view'];?>" dialog_id="refund_appointment" dialog_width="480" uri="index.php?act=clic_refund&op=view&refund_id=<?php echo $val['refund_id']; ?>"><i class="icon-eye-open"></i><p><?php echo $lang['nc_view'];?></p></a></span><?php } ?>
       </td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign">&nbsp;</i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php if (is_array($output['refund_list']) && !empty($output['refund_list'])) { ?>
    <tr>
      <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<script>
	$(function(){
	    $('#add_time_from').datepicker({dateFormat: 'yy-mm-dd'});
	    $('#add_time_to').datepicker({dateFormat: 'yy-mm-dd'});
	});
</script>
