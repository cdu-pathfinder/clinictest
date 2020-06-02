<?php defined('InclinicNC') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
  <a href="<?php echo urlclinic('clic_doctors_add');?>" class="ncsc-btn ncsc-btn-green" title="<?php echo $lang['clic_doctors_index_add_doctors'];?>"> <?php echo $lang['clic_doctors_index_add_doctors'];?></a> </div>
<form method="get" action="index.php">
  <table class="search-form">
    <input type="hidden" name="act" value="clic_doctors_online" />
    <input type="hidden" name="op" value="index" />
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
      <td class="w160"><input type="text" class="text w150" name="keyword" value="<?php echo $_GET['keyword']; ?>"/></td>
      <td class="tc w70"><label class="submit-bappointment"><input type="submit" class="submit" value="<?php echo $lang['nc_search'];?>" /></label></td>
    </tr>
  </table>
</form>
<table class="ncsc-table-style">
  <thead>
    <tr nc_type="table_header">
      <th class="w30">&nbsp;</th>
      <th class="w50">&nbsp;</th>
      <th coltype="editable" column="doctors_name" checker="check_required" inputwidth="230px"><?php echo $lang['clic_doctors_index_doctors_name'];?></th>
      <th class="w100"><?php echo $lang['clic_doctors_index_price'];?></th>
      <th class="w100"><?php echo $lang['clic_doctors_index_stock'];?></th>
      <th class="w100"><?php echo $lang['clic_doctors_index_add_time'];?></th>
      <th class="w100"><?php echo $lang['nc_handle'];?></th>
    </tr>
    <?php if (!empty($output['doctors_list'])) { ?>
    <tr>
      <td class="tc"><input type="checkbox" id="all" class="checkall"/></td>
      <td colspan="20"><label for="all" ><?php echo $lang['nc_select_all'];?></label>
        <a href="javascript:void(0);" class="ncsc-btn-mini" nc_type="batchbutton" uri="<?php echo urlclinic('clic_doctors_online', 'drop_doctors');?>" name="commonid" confirm="<?php echo $lang['nc_ensure_del'];?>"><i class="icon-trash"></i><?php echo $lang['nc_del'];?></a>
        <a href="javascript:void(0);" class="ncsc-btn-mini" nc_type="batchbutton" uri="<?php echo urlclinic('clic_doctors_online', 'doctors_unshow');?>" name="commonid"><i class="icon-level-down"></i><?php echo $lang['clic_doctors_index_unshow'];?></a>
        <a href="javascript:void(0);" class="ncsc-btn-mini" nctype="batch" data-param="{url:'<?php echo urlclinic('clic_doctors_online', 'edit_jingle');?>', sign:'jingle'}"><i></i>设置广告词</a>
        <a href="javascript:void(0);" class="ncsc-btn-mini" nctype="batch" data-param="{url:'<?php echo urlclinic('clic_doctors_online', 'edit_plate');?>', sign:'plate'}"><i></i>设置关联版式</a>
      </td>
    </tr>
    <?php } ?>
  </thead>
  <tbody>
    <?php if (!empty($output['doctors_list'])) { ?>
    <?php foreach ($output['doctors_list'] as $val) { ?>
    <tr>
      <th class="tc"><input type="checkbox" class="checkitem tc" <?php if ($val['doctors_lock'] == 1) {?>disabled="disabled"<?php }?> value="<?php echo $val['doctors_commonid']; ?>"/></th>
      <th colspan="20">平台货号：<?php echo $val['doctors_commonid'];?></th>
    </tr>
    <tr>
      <td class="trigger"><i class="tip icon-plus-sign" nctype="ajaxdoctorsList" data-comminid="<?php echo $val['doctors_commonid'];?>" title="点击展开查看此商品全部规格；规格值过多时请横向拖动区域内的滚动条进行浏览。"></i></td>
      <td><div class="pic-thumb"><a href="<?php echo urlclinic('doctors', 'index', array('doctors_id' => $output['storage_array'][$val['doctors_commonid']]['doctors_id']));?>" target="_blank"><img src="<?php echo thumb($val, 60);?>"/></a></div></td>
      <td class="tl"><dl class="doctors-name">
          <dt><?php if ($val['doctors_commend']) { echo '<span>荐</span>';}?><a href="<?php echo urlclinic('doctors', 'index', array('doctors_id' => $output['storage_array'][$val['doctors_commonid']]['doctors_id']));?>" target="_blank"><?php echo $val['doctors_name']; ?></a></dt>
          <dd><?php echo $val['gc_name']; ?></dd>
          <dd><?php echo $lang['clic_doctors_index_doctors_no'].$lang['nc_colon'];?><?php echo $val['doctors_serial'];?></dd>
        </dl></td>
      <td><span><?php echo $lang['currency'].$val['doctors_price']; ?></span></td>
      <td><span <?php if ($output['storage_array'][$val['doctors_commonid']]['alarm']) { echo 'style="color:red;"';}?>><?php echo $output['storage_array'][$val['doctors_commonid']]['sum'].$lang['piece']; ?></span></td>
      <td class="doctors-time"><?php echo @date('Y-m-d',$val['doctors_addtime']);?></td>
      <td class="nscs-table-handle">
      <?php if ($val['doctors_lock'] == 0) {?>
        <span><a href="<?php echo urlclinic('clic_doctors_online', 'edit_doctors', array('commonid' => $val['doctors_commonid']));?>" class="btn-blue"><i class="icon-edit"></i><p><?php echo $lang['nc_edit'];?></p></a></span>
        <span><a href="javascript:void(0);" onclick="ajax_get_confirm('<?php echo $lang['nc_ensure_del'];?>', '<?php echo urlclinic('clic_doctors_online', 'drop_doctors', array('commonid' => $val['doctors_commonid']));?>');" class="btn-red"><i class="icon-trash"></i><p><?php echo $lang['nc_del'];?></p></a></span>
      <?php } else {?>
        <span class="tip" title="该商品参加团购活动期间不能进行编辑及删除等操作"><a href="javascript:void(0);" class="btn-orange-current" style="cursor: default;"><i class="icon-lock"></i><p>锁定</p></a></span>
      <?php }?>
      </td>
    </tr>
    <tr style="display:none;"><td colspan="20"><div class="ncsc-doctors-sku ps-container"></div></td></tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php  if (!empty($output['doctors_list'])) { ?>
    <tr>
      <th class="tc"><input type="checkbox" id="all2" class="checkall"/></th>
      <th colspan="10"><label for="all2"><?php echo $lang['nc_select_all'];?></label>
        <a href="javascript:void(0);" nc_type="batchbutton" uri="<?php echo urlclinic('clic_doctors_online', 'drop_doctors');?>" name="commonid" confirm="<?php echo $lang['nc_ensure_del'];?>" class="ncsc-btn-mini"><i class="icon-trash"></i><?php echo $lang['nc_del'];?></a>
        <a href="javascript:void(0);" nc_type="batchbutton" uri="<?php echo urlclinic('clic_doctors_online', 'doctors_unshow');?>" name="commonid" class="ncsc-btn-mini"><i class="icon-level-down"></i><?php echo $lang['clic_doctors_index_unshow'];?></a>
        <a href="javascript:void(0);" class="ncsc-btn-mini" nctype="batch" data-param="{url:'<?php echo urlclinic('clic_doctors_online', 'edit_jingle');?>', sign:'jingle'}"><i></i>设置广告词</a>
        <a href="javascript:void(0);" class="ncsc-btn-mini" nctype="batch" data-param="{url:'<?php echo urlclinic('clic_doctors_online', 'edit_plate');?>', sign:'plate'}"><i></i>设置关联版式</a>
      </th>
    </tr>
    <tr>
      <td colspan="20"><div class="pagination"> <?php echo $output['show_page']; ?> </div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js"></script> 
<script src="<?php echo clinic_RESOURCE_SITE_URL;?>/js/clic_doctors_list.js"></script> 
<script>
$(function(){
    //Ajax提示
    $('.tip').poshytip({
        className: 'tip-yellowsimple',
        showTimeout: 1,
        alignTo: 'target',
        alignX: 'center',
        alignY: 'top',
        offsetY: 5,
        allowTipHover: false
    });
    $('a[nctype="batch"]').click(function(){
        if($('.checkitem:checked').length == 0){    //没有选择
            return false;
        }
        var _items = '';
        $('.checkitem:checked').each(function(){
            _items += $(this).val() + ',';
        });
        _items = _items.substr(0, (_items.length - 1));

        var data_str = '';
        eval('data_str = ' + $(this).attr('data-param'));

        if (data_str.sign == 'jingle') {
            ajax_form('ajax_jingle', '设置广告词', data_str.url + '&commonid=' + _items + '&inajax=1', '480');
        } else if (data_str.sign == 'plate') {
            ajax_form('ajax_plate', '设置关联版式', data_str.url + '&commonid=' + _items + '&inajax=1', '480');
        }
    });
});
</script>