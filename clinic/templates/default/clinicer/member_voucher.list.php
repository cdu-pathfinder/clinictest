<?php defined('InclinicNC') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<form id="voucher_list_form" method="get">
  <table class="search-form">
    <input type="hidden" id='act' name='act' value='member_voucher' />
    <input type="hidden" id='op' name='op' value='voucher_list' />
    <tr>
      <td>&nbsp;</td>
      <td class="w100 tr"><select name="select_detail_state">
          <option value="0" <?php if (!$_GET['select_detail_state'] == '0'){echo 'selected=true';}?>> <?php echo $lang['voucher_voucher_state']; ?> </option>
          <?php if (!empty($output['voucherstate_arr'])){?>
          <?php foreach ($output['voucherstate_arr'] as $k=>$v){?>
          <option value="<?php echo $v[0];?>" <?php if ($_GET['select_detail_state'] == $v[0]){echo 'selected=true';}?>> <?php echo $v[1];?> </option>
          <?php }?>
          <?php }?>
        </select></td>
      <td class="w70 tc"><label class="submit-bappointment"><input type="submit" class="submit" onclick="submit_search_form()" value="<?php echo $lang['nc_search'];?>" /></label></td>
    </tr>
  </table>
</form>
<table class="ncsc-table-style">
  <thead>
    <tr>
      <th></th>
      <th class="w150"><?php echo $lang['voucher_voucher_code'];?></th>
      <th class="w60"><?php echo $lang['voucher_voucher_price'];?></th>
      <th><?php echo $lang['voucher_voucher_clicname'];?></th>
      <th class="w150"><?php echo $lang['voucher_voucher_indate'];?></th>
      <th class="w150"><?php echo $lang['voucher_voucher_usecondition'];?></th>
      <th class="w60"><?php echo $lang['voucher_voucher_state'];?></th>
      <th class="w60"><?php echo $lang['nc_handle'];?></th>
    </tr>
  </thead>
  <tbody>
    <?php  if (count($output['list'])>0) { ?>
    <?php foreach($output['list'] as $val) { ?>
    <tr class="bd-line">
      <td><img src="<?php echo $val['voucher_t_customimg'];?>" /></td>
      <td><?php echo $val['voucher_code'];?></td>
      <td><?php echo $val['voucher_price'].$lang['currency_zh'];?></td>
      <td class="doctors-price"><a href="<?php echo urlclinic('show_clic', 'index', array('clic_id'=>$val['clic_id']));?>" target="_blank"><?php echo $val['clic_name'];?></a></td>
      <td class="doctors-time"><?php echo date("Y-m-d",$val['voucher_start_date']).'~'.date("Y-m-d",$val['voucher_end_date']);?></td>
      <td><?php echo $lang['voucher_voucher_usecondition_desc'].$val['voucher_limit'].$lang['currency_zh'];?></td>
      <td><?php foreach ((array)$output['voucherstate_arr'] as $k=>$v){?>
        <?php if ($v[0] == $val['voucher_state']){ echo $v[1];break;}?>
        <?php }?></td>
      <td><?php if ($val['voucher_state'] == '1'){?>
        <a href="<?php echo urlclinic('show_clic', 'index', array('clic_id'=>$val['clic_id']));?>" target="_blank"><?php echo $lang['voucher_voucher_readytouse'];?></a>
        <?php } elseif ($val['voucher_state'] == '2'){?>
        <a href="index.php?act=member_appointment&op=show_appointment&appointment_id=<?php echo $val['voucher_appointment_id'];?>"><?php echo $lang['voucher_voucher_viewappointment'];?></a>
        <?php }?></td>
    </tr>
    <?php }?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <?php  if (count($output['list'])>0) { ?>
  <tfoot>
    <tr>
      <td colspan="20"><div class="pagination"><?php echo $output['show_page'];?></div></td>
    </tr>
  </tfoot>
  <?php } ?>
</table>
