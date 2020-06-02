<?php defined('InclinicNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['doctors_index_doctors']?></h3>
      <ul class="tab-base">
        <li><a href="<?php echo urlAdmin('doctors', 'doctors');?>" ><span><?php echo $lang['doctors_index_all_doctors'];?></span></a></li>
        <li><a href="<?php echo urlAdmin('doctors', 'doctors', array('type' => 'lockup'));?>"><span><?php echo $lang['doctors_index_lock_doctors'];?></span></a></li>
        <li><a href="<?php echo urlAdmin('doctors', 'doctors', array('type' => 'waitverify'));?>"><span>等待审核</span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_doctors_set']?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="post" name="form_doctorsverify">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
        <tr class="nobappointment">
          <td colspan="2" class="required"><label><?php echo $lang['doctors_is_verify']?>:</label></td>
        </tr>
        <tr class="nobappointment">
          <td class="vatop rowform onoff"><label for="rewrite_enabled"  class="cb-enable <?php if($output['list_setting']['doctors_verify'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_yes'];?>"><span><?php echo $lang['nc_yes'];?></span></label>
            <label for="rewrite_disabled" class="cb-disable <?php if($output['list_setting']['doctors_verify'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_no'];?>"><span><?php echo $lang['nc_no'];?></span></label>
            <input id="rewrite_enabled" name="doctors_verify" <?php if($output['list_setting']['doctors_verify'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="rewrite_disabled" name="doctors_verify" <?php if($output['list_setting']['doctors_verify'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio"></td>
          <td class="vatop tips">
            <?php echo $lang['open_rewrite_tips'];?></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2" ><a href="JavaScript:void(0);" class="btn" onclick="document.form_doctorsverify.submit()"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>