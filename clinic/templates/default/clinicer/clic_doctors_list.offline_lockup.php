<?php defined('InclinicNC') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<form method="get" action="index.php">
  <table class="search-form">
    <input type="hidden" name="act" value="clic_doctors_offline" />
    <input type="hidden" name="op" value="index" />
    <input type="hidden" name="type" value="lock_up" />
    <tr>
      <td>&nbsp;</td>
      <th><?php echo $lang['clic_doctors_index_clic_doctors_class'];?></th>
      <td class="w160"><select name="stc_id" class="w150">
          <option value="0"><?php echo $lang['nc_please_choose'];?></option>
          <?php if(is_array($output['clic_doctors_class']) && !empty($output['clic_doctors_class'])){?>
          <?php foreach ($output['clic_doctors_class'] as $val) {?>
          <option value="<?php echo $val['stc_id']; ?>" <?php if ($_GET['stc_id'] == $val['stc_id']){ echo 'selected=selected';}?>><?php echo $val['stc_name']; ?></option>
          <?php if (is_array($val['child']) && count($val['child'])>0){?>
          <?php foreach ($val['child'] as $child_val){?>
          <option value="<?php echo $child_val['stc_id']; ?>" <?php if ($_GET['stc_id'] == $child_val['stc_id']){ echo 'selected=selected';}?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child_val['stc_name']; ?></option>
          <?php }?>
          <?php }?>
          <?php }?>
          <?php }?>
        </select></td>
      <th>
        <select name="search_type">
          <option value="0" <?php if ($_GET['type'] == 0) {?>selected="selected"<?php }?>><?php echo $lang['clic_doctors_index_doctors_name'];?></option>
          <option value="1" <?php if ($_GET['type'] == 1) {?>selected="selected"<?php }?>><?php echo $lang['clic_doctors_index_doctors_no'];?></option>
          <option value="2" <?php if ($_GET['type'] == 2) {?>selected="selected"<?php }?>>平台货号</option>
        </select>
      </th>
      <td class="w160"><input type="text" class="text" name="keyword" value="<?php echo $_GET['keyword']; ?>"/></td>
      <td class="tc w70"><label class="submit-bappointment"><input type="submit" class="submit" value="<?php echo $lang['nc_search'];?>" /></label></td>
    </tr>
  </table>
</form>
<table class="ncsc-table-style">
  <thead>
    <tr nc_type="table_header">
      <th class="w30"></th>
      <th class="w50"></th>
      <th><?php echo $lang['clic_doctors_index_doctors_name'];?></th>
      <th class="w180"><?php echo $lang['clic_doctors_index_close_reason'];?></th>
      <th class="w100"><?php echo $lang['clic_doctors_index_price'];?></th>
      <th class="w100"><?php echo $lang['clic_doctors_index_stock'];?></th>
      <th class="w100"><?php echo $lang['nc_handle'];?></th>
    </tr>
    <?php  if (!empty($output['doctors_list'])) { ?>
    <tr>
      <td class="tc"><input type="checkbox" id="all" class="checkall"/></td>
      <td colspan="10"><label for="all"><?php echo $lang['nc_select_all'];?></label>
        <a href="javascript:void(0);" class="ncsc-btn-mini" nc_type="batchbutton" uri="<?php echo urlclinic('clic_doctors_online', 'drop_doctors');?>" name="commonid" confirm="<?php echo $lang['nc_ensure_del'];?>"><i class="icon-trash"></i><?php echo $lang['nc_del'];?></a> 
    </tr>
    <?php } ?>
  </thead>
  <tbody>
    <?php if (!empty($output['doctors_list'])) { ?>
    <?php foreach ($output['doctors_list'] as $val) { ?>
    <tr>
      <th class="tc"><input type="checkbox" class="checkitem tc" value="<?php echo $val['doctors_commonid']; ?>"/></th>
      <th colspan="20">平台货号：<?php echo $val['doctors_commonid'];?></th>
    </tr>
    <tr>
      <td class="trigger"><i class="icon-plus-sign" nctype="ajaxdoctorsList" data-comminid="<?php echo $val['doctors_commonid'];?>"></i></td>
      <td><div class="pic-thumb">
        <a href="<?php echo urlclinic('doctors', 'index', array('doctors_id' => $output['storage_array'][$val['doctors_commonid']]['doctors_id']));?>" target="_blank"><img src="<?php echo thumb($val, 60);?>"/></a></div></td>
      <td class="tl"><dl class="doctors-name">
          <dt><a href="<?php echo urlclinic('doctors', 'index', array('doctors_id' => $output['storage_array'][$val['doctors_commonid']]['doctors_id']));?>" target="_blank"><?php echo $val['doctors_name']; ?></a></dt>
          <dd><?php echo $val['gc_name']; ?></dd>
          <dd><?php echo $lang['clic_doctors_index_doctors_no'].$lang['nc_colon'];?><?php echo $val['doctors_serial'];?></dd>
        </dl></td>
      <td><?php echo $val['doctors_stateremark'];?></td>
      <td><span><?php echo $lang['currency'].$val['doctors_price']; ?></span></td>
      <td><span><?php echo $output['storage_array'][$val['doctors_commonid']]['sum'].$lang['piece']; ?></span></td>
      <td class="nscs-table-handle"><span><a href="<?php echo urlclinic('clic_doctors_online', 'edit_doctors', array('commonid' => $val['doctors_commonid']));?>" class="btn-blue"><i class="icon-edit"></i><p><?php echo $lang['nc_edit'];?></p></a></span>
        <span><a href="javascript:void(0)" onclick="ajax_get_confirm('<?php echo $lang['nc_ensure_del'];?>', '<?php echo urlclinic('clic_doctors_online', 'drop_doctors', array('commonid' => $val['doctors_commonid']));?>');" class="btn-red"><i class="icon-trash"></i><p><?php echo $lang['nc_del'];?></p></a></span></td>
    </tr>
    <tr style="display:none;"><td colspan="20"><div class="ncsc-doctors-sku ps-container"></div></td></tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
    <?php  if (!empty($output['doctors_list'])) { ?>
  <tfoot>
    <tr>
      <th class="tc"><input type="checkbox" id="all2" class="checkall"/></th>
      <th colspan="10"><label for="all2"><?php echo $lang['nc_select_all'];?></label>
        <a href="javascript:void(0);" class="ncsc-btn-mini" nc_type="batchbutton" uri="<?php echo urlclinic('clic_doctors_online', 'drop_doctors');?>" name="commonid" confirm="<?php echo $lang['nc_ensure_del'];?>"><i class="icon-trash"></i><?php echo $lang['nc_del'];?></a> 
    </tr>
    <tr>
      <td colspan="20"><div class="pagination"> <?php echo $output['show_page']; ?> </div></td>
    </tr>
  </tfoot>
  <?php } ?>
</table>
<script type="text/javascript" src="<?php echo clinic_RESOURCE_SITE_URL;?>/js/clic_doctors_list.js" charset="utf-8"></script> 