<?php defined('InclinicNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['return_manage'];?></h3>
      <ul class="tab-base">
        <li><a href="index.php?act=return&op=return_manage"><span><?php echo '待处理';?></span></a></li>
        <li><a href="index.php?act=return&op=return_all"><span><?php echo '所有记录';?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_view'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
    <table class="table tb-type2">
      <tbody>
        <tr class="nobappointment">
          <td colspan="2" class="required"><?php echo '商品名称'.$lang['nc_colon'];?></td>
        </tr>
        <tr class="nobappointment">
          <td class="vatop rowform"><?php echo $output['return']['doctors_name']; ?></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="nobappointment">
          <td colspan="2" class="required"><?php echo $lang['refund_appointment_refund'].$lang['nc_colon'];?></td>
        </tr>
        <tr class="nobappointment">
          <td class="vatop rowform"><?php echo ncPriceFormat($output['return']['refund_amount']); ?></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="nobappointment">
          <td colspan="2" class="required"><?php echo $lang['return_buyer_message'].$lang['nc_colon'];?></td>
        </tr>
        <tr class="nobappointment">
          <td class="vatop rowform"><?php echo $output['return']['buyer_message']; ?></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="nobappointment">
          <td colspan="2" class="required"><?php echo '卖家审核'.$lang['nc_colon'];?></td>
        </tr>
        <tr class="nobappointment">
          <td class="vatop rowform"><?php echo $output['state_array'][$output['return']['clinicer_state']];?></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="nobappointment">
          <td colspan="2" class="required"><?php echo $lang['refund_clinicer_message'].$lang['nc_colon'];?></td>
        </tr>
        <tr class="nobappointment">
          <td class="vatop rowform"><?php echo $output['return']['clinicer_message']; ?></td>
          <td class="vatop tips"></td>
        </tr>
        <?php if ($output['return']['clinicer_state'] == 2) { ?>
        <tr class="nobappointment">
          <td colspan="2" class="required"><?php echo '平台确认'.$lang['nc_colon'];?></td>
        </tr>
        <tr class="nobappointment">
          <td class="vatop rowform"><?php echo $output['admin_array'][$output['return']['refund_state']];?></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="nobappointment">
          <td colspan="2" class="required"><?php echo $lang['refund_message'].$lang['nc_colon'];?></td>
        </tr>
        <tr class="nobappointment">
          <td class="vatop rowform"><?php echo $output['return']['admin_message']; ?></td>
          <td class="vatop tips"></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="15" ><a href="JavaScript:void(0);" class="btn" onclick="history.go(-1)"><span><?php echo $lang['nc_back'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
</div>