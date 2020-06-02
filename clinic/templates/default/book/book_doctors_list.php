<?php defined('InclinicNC') or exit('Access Invalid!');?>
<style>
.ncc-table-style tbody tr.item_disabled td {
	background: none repeat scroll 0 0 #F9F9F9;
	height: 30px;
	padding: 10px 0;
	text-align: center;
}
</style>
<div class="ncc-receipt-info">
  <div class="ncc-receipt-info-title">
    <h3>Appointment list</h3>
    <?php if(!empty($output['ifcart'])){?>
    <a href="index.php?act=cart"><?php echo $lang['cart_step1_back_to_cart'];?></a>
    <?php }?>
  </div>
  <table class="ncc-table-style">
    <thead>
      <tr>
        <th class="w20"></th>
        <th></th>
        <th><?php echo $lang['cart_index_clic_doctors'];?></th>
        <th class="w120"><?php echo $lang['cart_index_price'].'('.$lang['currency_zh'].')';?></th>
        <th class="w120"><?php echo $lang['cart_index_amount'];?></th>
        <th class="w120"><?php echo $lang['cart_index_sum'];?></th>
      </tr>
    </thead>
    <?php foreach($output['clic_cart_list'] as $clic_id => $cart_list) {?>
    <tbody>
      <tr>
        <th colspan="20"><i class="icon-home"></i><a target="_blank" href="<?php echo urlclinic('show_clic','index',array('clic_id'=>$clic_id));?>"><?php echo $cart_list[0]['clic_name']; ?></a>
          <div class="clic-sale">
            <?php if (!empty($output['cancel_calc_sid_list'][$clic_id])) {?>
            <em><i class="icon-gift"></i>免运费</em><?php echo $output['cancel_calc_sid_list'][$clic_id]['desc'];?>
            <?php } ?>
            <?php if (!empty($output['clic_mansong_rule_list'][$clic_id])) {?>
            <em><i class="icon-gift"></i>满即送</em><?php echo $output['clic_mansong_rule_list'][$clic_id]['desc'];?>
            <?php } ?>
            &emsp;</div>
        </th>
      </tr>
      <?php foreach($cart_list as $cart_info) {?>
      <tr id="cart_item_<?php echo $cart_info['cart_id'];?>" class="clinic-list <?php echo ($cart_info['state'] && $cart_info['storage_state']) ? '' : 'item_disabled';?>">
        <td><?php if ($cart_info['state'] && $cart_info['storage_state']) {?>
          <input type="hidden" value="<?php echo $cart_info['cart_id'].'|'.$cart_info['doctors_num'];?>" name="cart_id[]">
          <?php } ?></td>
        <?php if ($cart_info['bl_id'] == '0') {?>
        <td class="w60"><a href="<?php echo urlclinic('doctors','index',array('doctors_id'=>$cart_info['doctors_id']));?>" target="_blank" class="ncc-doctors-thumb"><img src="<?php echo thumb($cart_info,60);?>" alt="<?php echo $cart_info['doctors_name']; ?>" /></a></td>
        <?php } ?>
        <td class="tl" <?php if ($cart_info['bl_id'] != '0') {?>colspan="2"<?php }?>><dl class="ncc-doctors-info">
            <dt><a href="<?php echo urlclinic('doctors','index',array('doctors_id'=>$cart_info['doctors_id']));?>" target="_blank"><?php echo $cart_info['doctors_name']; ?></a></dt>
            <dd>
              <?php if ($cart_info['ifxianshi']) {?>
              <span class="xianshi">限时折扣</span>
              <?php }?>
              <?php if ($cart_info['ifgroupbuy']) {?>
              <span class="groupbuy">团购</span>
              <?php }?>
              <?php if ($cart_info['bl_id'] != '0') {?>
              <span class="buldling">优惠套装</span>
              <?php }?>
            </dd>
          </dl></td>
        <td class="w120">￥<em><?php echo $cart_info['doctors_price']; ?></em></span></td>
        <td class="w60"><?php echo $cart_info['state'] ? $cart_info['doctors_num'] : ''; ?></td>
        <td class="w120"><?php if ($cart_info['state'] && $cart_info['storage_state']) {?>
          ￥<em id="item<?php echo $cart_info['cart_id']; ?>_subtotal" nc_type="eachdoctorsTotal"><?php echo $cart_info['doctors_total']; ?></em>
          <?php } elseif (!$cart_info['storage_state']) {?>
          <span style="color: #F00;">库存不足</span>
          <?php }elseif (!$cart_info['state']) {?>
          <span style="color: #F00;">已下架</span>
          <?php }?></td>
      </tr>
      
      <!-- S bundling doctors list -->
      <?php if (is_array($cart_info['bl_doctors_list'])) {?>
      <?php foreach ($cart_info['bl_doctors_list'] as $doctors_info) { ?>
      <tr class="clinic-list <?php echo $cart_info['state'] && $cart_info['storage_state'] ? '' : 'item_disabled';?>">
        <td></td>
        <td class="w60"><a href="<?php echo urlclinic('doctors','index',array('doctors_id'=>$doctors_info['doctors_id']));?>" target="_blank" class="ncc-doctors-thumb"><img src="<?php echo cthumb($doctors_info['doctors_image'],60,$clic_id);?>" alt="<?php echo $doctors_info['doctors_name']; ?>" /></a></td>
        <td class="tl"><dl class="ncc-doctors-info">
            <dt><a href="<?php echo urlclinic('doctors','index',array('doctors_id'=>$doctors_info['doctors_id']));?>" target="_blank"><?php echo $doctors_info['doctors_name']; ?></a> </dt>
          </dl></td>
        <td>￥<em><?php echo $doctors_info['bl_doctors_price'];?></em></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <?php } ?>
      <?php  } ?>
      <!-- E bundling doctors list -->
      <?php } ?>
      
      <!-- S zengpin list -->
      <?php if (is_array($output['clic_premiums_list'][$clic_id])) {?>
      <?php foreach ($output['clic_premiums_list'][$clic_id] as $doctors_info) { ?>
      <tr class="clinic-list">
        <td></td>
        <td class="w60"><a href="<?php echo urlclinic('doctors','index',array('doctors_id'=>$doctors_info['doctors_id']));?>" target="_blank" class="ncc-doctors-thumb"><img src="<?php echo cthumb($doctors_info['doctors_image'],60,$clic_id);?>" alt="<?php echo $doctors_info['doctors_name']; ?>" /></a></td>
        <td class="tl"><dl class="ncc-doctors-info">
            <dt><a href="<?php echo urlclinic('doctors','index',array('doctors_id'=>$doctors_info['doctors_id']));?>" target="_blank" ><?php echo $doctors_info['doctors_name']; ?></a></dt>
            <dd><span class="zengpin">赠品</span></dd>
          </dl></td>
        <td>￥0.00</td>
        <td>1</td>
        <td> ￥0.00</td>
        <td></td>
      </tr>
      <?php } ?>
      <?php  } ?>
      <!-- E zengpin list -->
      
      <tr>
        <td class="w10"></td>
        <td class="tl" colspan="2">买家留言：
          <input type="text" value="" class="text w340" name="pay_message[<?php echo $clic_id;?>]" maxlength="150">
          &nbsp;</td>
        <td class="tl" colspan="10"><div class="ncc-form-default"> </div></td>
      </tr>
      <tr>
        <td class="tr" colspan="20"><div class="ncc-clic-account">
            <!-- <dl class="freight">
              <dt>运费：</dt>
              <dd>￥<em id="eachclicFreight_<?php echo $clic_id;?>">0.00</em></dd>
            </dl> -->
            <dl>
              <dt>appointmnet price：</dt>
              <dd>$<em id="eachclicdoctorsTotal_<?php echo $clic_id;?>"><?php echo $output['clic_doctors_total'][$clic_id];?></em></dd>
            </dl>
            <?php if (!empty($output['clic_mansong_rule_list'][$clic_id]['discount'])) {?>
            <dl class="mansong">
              <dt>满即送-<?php echo $output['clic_mansong_rule_list'][$clic_id]['desc'];?>：</dt>
              <dd>￥<em id="eachclicManSong_<?php echo $clic_id;?>">-<?php echo $output['clic_mansong_rule_list'][$clic_id]['discount'];?></em></dd>
            </dl>
            <?php } ?>

            <!-- S voucher list -->

            <?php if (!empty($output['clic_voucher_list'][$clic_id]) && is_array($output['clic_voucher_list'][$clic_id])) {?>
            <dl class="voucher">
              <dt>
                <select nctype="voucher" name="voucher[<?php echo $clic_id;?>]">
                  <option value="<?php echo $voucher['voucher_t_id'];?>|<?php echo $clic_id;?>|0.00">选择代金券</option>
                  <?php foreach ($output['clic_voucher_list'][$clic_id] as $voucher) {?>
                  <option value="<?php echo $voucher['voucher_t_id'];?>|<?php echo $clic_id;?>|<?php echo $voucher['voucher_price'];?>"><?php echo $voucher['desc'];?></option>
                  <?php } ?>
                </select> ：
              <dd>￥<em id="eachclicVoucher_<?php echo $clic_id;?>">-0.00</em></dd>
            </dl>
            <?php } ?>

            <!-- E voucher list -->

            <dl class="total">
              <dt>Total of this clinic:</dt>
              <dd>$<em clic_id="<?php echo $clic_id;?>" nc_type="eachclicTotal"></em></dd>
            </dl>
          </div></td>
      </tr>
      <?php }?>

     <!-- S 预存款 -->
     <?php if (!empty($output['available_pd_amount'])) { ?>
     <tr>
        <td class="pd-account" colspan="20"><div class="ncc-pd-account">
            <div class="mt5 mb5"><label><input type="checkbox" checked class="vm mr5" value="1" name="pd_pay">Pay by deposit（Currently available balance：<em>$<?php echo $output['available_pd_amount'];?></em>）</label></div>
            <div id="pd_password">login password：<input type="password" class="text w120" value="" name="password" id="password" maxlength="35">
            <input type="hidden" value="" name="password_callback" id="password_callback">
              <a class="ncc-btn-mini ncc-btn-orange" id="pd_pay_submit" href="javascript:void(0)">pay</a>
             </div>
          </div></td>
      </tr>
     <?php } ?>
     <!-- E 预存款 -->

    </tbody>
    <tfoot>
      <tr>
        <td colspan="20"><div class="ncc-all-account">Total appointment amount：$<em id="appointmentTotal"></em></div></td>
      </tr>
    </tfoot>
  </table>
</div>
