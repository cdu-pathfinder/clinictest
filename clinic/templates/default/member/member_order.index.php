<?php defined('InclinicNC') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<style type="text/css">
.clic-name {
	width: 130px;
	display: inline-block;
	overflow: hidden;
	white-space: nowrap;
	text-overflow: ellipsis;
}
</style>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <form method="get" action="index.php" target="_self">
    <table class="search-form">
      <input type="hidden" name="act" value="member_appointment" />
      <tr>
        <td></td>
        <th><?php echo $lang['member_appointment_time'].$lang['nc_colon'];?></th>
        <td class="w180"><input type="text" class="text" name="query_start_date" id="query_start_date" value="<?php echo $_GET['query_start_date']; ?>"/>
          &#8211;
          <input type="text" class="text" name="query_end_date" id="query_end_date" value="<?php echo $_GET['query_end_date']; ?>"/></td>
        <th><?php echo $lang['member_appointment_sn'].$lang['nc_colon'];?></th>
        <td class="w160"><input type="text" class="text" name="appointment_sn" value="<?php echo $_GET['appointment_sn']; ?>"></td>
        <th><?php echo $lang['member_appointment_state'].$lang['nc_colon'];?></th>
        <td class="w100"><select name="state_type">
            <option value="" <?php echo $_GET['state_type']==''?'selected':''; ?>><?php echo $lang['member_appointment_all'];?></option>
            <option value="state_new" <?php echo $_GET['state_type']=='state_new'?'selected':''; ?>>待付款</option>
            <option value="state_pay" <?php echo $_GET['state_type']=='state_pay'?'selected':''; ?>>待发货</option>
            <option value="state_send" <?php echo $_GET['state_type']=='state_send'?'selected':''; ?>>待收货</option>
            <option value="state_success" <?php echo $_GET['state_type']=='state_success'?'selected':''; ?>>已完成</option>
            <option value="state_noeval" <?php echo $_GET['state_type']=='state_noeval'?'selected':''; ?>>待评价</option>
            <option value="state_cancel" <?php echo $_GET['state_type']=='state_cancel'?'selected':''; ?>>已取消</option>
          </select></td>
        <td class="w90 tc"><input type="submit" class="submit" value="<?php echo $lang['member_appointment_search'];?>" /></td>
      </tr>
    </table>
  </form>
  <table class="appointment ncu-table-style">
    <?php if ($output['appointment_group_list']) { ?>
      <?php foreach ($output['appointment_group_list'] as $appointment_pay_sn => $group_info) { ?><?php $p = 0;?>
      <tbody <?php if (!empty($group_info['pay_amount']) && $p == 0) {?> class="pay" <?php }?>>
      <?php foreach($group_info['appointment_list'] as $appointment_id => $appointment_info) {?>
      <?php if (empty($group_info['pay_amount'])) {?>
        <tr><td colspan="19" class="sep-row"></td></tr>
      <?php }?>
      <?php if (!empty($group_info['pay_amount']) && $p == 0) {?><tr><td colspan="19" class="sep-row"></td></tr>
      <tr><td colspan="19" class="pay-td"><span class="mr50 ml15">下单时间：<time><?php echo date('Y-m-d H:i:s',$group_info['add_time']);?></time></span>

        <span>在线支付金额：<em>￥<?php echo ncPriceFormat($group_info['pay_amount']);?></em></span>
        <a class="ncu-btn7 fr mr15" href="index.php?act=buy&op=pay&pay_sn=<?php echo $appointment_pay_sn; ?>">订单支付</a></td></tr><?php }?>
		<?php $p++;?>
      <tr>
        <th colspan="19">
        <span class="fl ml10">
            <!-- appointment_sn -->
            <?php echo $lang['member_appointment_sn'].$lang['nc_colon'];?><span class="doctors-num"><em><?php echo $appointment_info['appointment_sn']; ?></em></span></span>

            <!-- appointment_time -->
            <span class="fl ml20"><?php echo $lang['member_appointment_time'].$lang['nc_colon'];?><em class="doctors-time"><?php echo date("Y-m-d H:i:s",$appointment_info['add_time']); ?></em></span>

            <!-- clic_name -->
            <span class="fl ml10">
            <a href="<?php echo urlclinic('show_clic','index',array('clic_id'=>$appointment_info['clic_id']), $appointment_info['extend_clic']['clic_domain']);?>" target="_blank" title="<?php echo $appointment_info['clic_name'];?>"><?php echo $appointment_info['clic_name']; ?></a></span>

            <!-- QQ -->
            <span class="fl" member_id="<?php echo $appointment_info['extend_clic']['member_id'];?>"><?php if(!empty($appointment_info['extend_clic']['clic_qq'])){?>
            <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $appointment_info['extend_clic']['clic_qq'];?>&site=qq&menu=yes" title="QQ: <?php echo $appointment_info['extend_clic']['clic_qq'];?>"><img bappointment="0" src="http://wpa.qq.com/pa?p=2:<?php echo $appointment_info['extend_clic']['clic_qq'];?>:52" style=" vertical-align: middle;"/></a>
            <?php }?>

            <!-- wang wang -->
            <?php if(!empty($appointment_info['extend_clic']['clic_ww'])){?>
            <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&uid=<?php echo $appointment_info['extend_clic']['clic_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>"  class="vm" ><img bappointment="0" src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo $appointment_info['extend_clic']['clic_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" alt="Wang Wang"  style=" vertical-align: middle;"/></a><?php }?></span>

            <!-- 订单查看 -->
           <span class="fl ml10"><a href="index.php?act=member_appointment&op=show_appointment&appointment_id=<?php echo $appointment_info['appointment_id']; ?>" target="_blank" class="nc-show-appointment"><i></i><?php echo $lang['member_appointment_view_appointment'];?></a></span>


          </th>
      </tr>

      <!-- S 商品列表 -->
      <?php foreach ((array)$appointment_info['extend_appointment_doctors'] as $k => $doctors_info) {?>
      <tr>
        <td class="w10 bdl"></td>
        <td class="w70">
        <div class="doctors-pic-small"><span class="thumb size60"><i></i><a href="<?php echo urlclinic('doctors','index',array('doctors_id'=>$doctors_info['doctors_id']));?>" target="_blank"><img src="<?php echo thumb($doctors_info,60);?>"/></a></span></div></td>
        <td>
        <dl class="doctors-name">
            <dt><a href="<?php echo urlclinic('doctors','index',array('doctors_id'=>$doctors_info['doctors_id']));?>" target="_blank"><?php echo $doctors_info['doctors_name']; ?></a></dt>
            <dd>
            <?php echo appointmentdoctorsType($doctors_info['doctors_type']);?>
            </dd>

          </dl></td>
        <td class="doctors-price w120"><i><?php echo $doctors_info['doctors_price'];?> x <?php echo $doctors_info['doctors_num']; ?></i><?php if ($doctors_info['refund'] == 1){?>
          <p><a href="javascript:void(0)" nc_type="dialog" dialog_title="退款退货" dialog_id="member_doctors_refund"
            dialog_width="480" uri="index.php?act=member_refund&op=add_refund&appointment_id=<?php echo $appointment_info['appointment_id']; ?>&doctors_id=<?php echo $doctors_info['rec_id']; ?>"
            id="appointment<?php echo $appointment_info['appointment_id'];?>_<?php echo $doctors_info['doctors_id']; ?>action_refund">
            <?php echo '退款退货';?></a></p>
        <?php }?>
        </td>

        <?php if ((count($appointment_info['extend_appointment_doctors']) > 1 && $k ==0) || (count($appointment_info['extend_appointment_doctors']) == 1)){?>
        <td class="w120 bdl" rowspan="<?php echo count($appointment_info['extend_appointment_doctors']);?>">
        <?php if ($appointment_info['payment_name']) { ?>
        <p class="doctors-pay" title="<?php echo $lang['member_appointment_pay_method'].$lang['nc_colon'];?><?php echo $appointment_info['payment_name']; ?>"><?php echo $appointment_info['payment_name']; ?></p>
        <?php } ?>
        <p class="doctors-price"><strong><?php echo $appointment_info['appointment_amount']; ?></strong></p>
        <p class="doctors-freight">
            <?php if ($appointment_info['shipping_fee'] > 0){?>
            (<?php echo $lang['member_appointment_shipping_han'];?>运费<?php echo $appointment_info['shipping_fee'];?>)
            <?php }else{?>
            <?php echo $lang['nc_common_shipping_free'];?>
            <?php }?>
        </p>
        </td>
        <td class="bdl bdr w120" rowspan="<?php echo count($appointment_info['extend_appointment_doctors']);?>">
        <p><?php echo $appointment_info['state_desc']; ?><br/><?php echo $appointment_info['evaluation_status'] ? $lang['member_appointment_evaluated'].'<br/>' : '';?></p>

          <!-- 取消订单 -->
          <?php if ($appointment_info['if_cancel']) { ?>
          <p><a href="javascript:void(0)" style="color:#F30; text-decoration:underline;" nc_type="dialog" dialog_width="480" dialog_title="<?php echo $lang['member_appointment_cancel_appointment'];?>" dialog_id="buyer_appointment_cancel_appointment" uri="index.php?act=member_appointment&op=change_state&state_type=appointment_cancel&appointment_id=<?php echo $appointment_info['appointment_id']; ?>"  id="appointment<?php echo $appointment_info['appointment_id']; ?>_action_cancel"><?php echo $lang['member_appointment_cancel_appointment'];?></a></p>
          <?php } ?>

          <!-- 物流跟踪 -->
          <?php if ($appointment_info['if_deliver']){ ?>
          <p><a href='index.php?act=member_appointment&op=search_deliver&appointment_id=<?php echo $appointment_info['appointment_id']; ?>&appointment_sn=<?php echo $appointment_info['appointment_sn']; ?>'><?php echo $lang['member_appointment_show_deliver']?></a></p>
          <?php } ?>

          <!-- 投诉 -->
          <?php if ($appointment_info['if_complain']){ ?>
          <p><a href='index.php?act=member_complain&op=complain_new&appointment_id=<?php echo $appointment_info['appointment_id']; ?>' target="_blank">投诉</a></p>
          <?php } ?>

          <!-- 取消订单 -->
          <?php if ($appointment_info['if_refund_cancel']){ ?>
          <p><a href="javascript:void(0)" style="color:#F30; text-decoration:underline;" nc_type="dialog" dialog_title="取消订单" dialog_id="member_appointment_refund"
            dialog_width="480" uri="index.php?act=member_refund&op=add_refund_all&appointment_id=<?php echo $appointment_info['appointment_id']; ?>" id="appointment<?php echo $appointment_info['appointment_id']; ?>_action_refund">取消订单</a></p>
          <?php } ?>

          <!-- 收货 -->
          <?php if ($appointment_info['if_receive']) { ?>
          <p><a href="javascript:void(0)" class="ncu-btn7 mt5" nc_type="dialog" dialog_id="buyer_appointment_confirm_appointment" dialog_width="480" dialog_title="<?php echo $lang['member_appointment_ensure_appointment'];?>" uri="index.php?act=member_appointment&op=change_state&state_type=appointment_receive&appointment_sn=<?php echo $appointment_info['appointment_sn']; ?>&appointment_id=<?php echo $appointment_info['appointment_id']; ?>" id="appointment<?php echo $appointment_info['appointment_id']; ?>_action_confirm"><?php echo $lang['member_appointment_ensure_appointment'];?></a></p>
          <?php } ?>

          <!-- 评价 -->
          <?php if ($appointment_info['if_evaluation']) { ?>
          <p><a class="ncu-btn6 mt5" href="index.php?act=member_evaluate&op=add&appointment_id=<?php echo $appointment_info['appointment_id']; ?>"><?php echo $lang['member_appointment_want_evaluate'];?></a></p>
          <?php } ?>

          <!-- 已经评价 -->
          <?php if (intval($appointment_info['evaluation_state'])) { echo $lang['appointment_state_eval'];} ?>

          <!-- 锁定-->
          <?php if ($appointment_info['if_lock']) { ?><p>退款退货中</p><?php } ?>

          <!-- 分享  -->
       <?php if ($appointment_info['if_share']) { ?>
           <p><a href="javascript:void(0)" class="ncu-btn2 mt5" nc_type="sharedoctors" data-param='{"gid":"<?php echo $appointment_info['extend_appointment_doctors'][0]['doctors_id'];?>"}'><i></i>分享商品</a></p>
       <?php } ?>
        </td>
      </tr>
      <?php } ?>
      <?php } ?>
      <?php } ?>
      </tbody>
      <?php } ?>
      <?php } else { ?>
      <tbody>
      <tr>
        <td colspan="19" class="norecord"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></td>
      </tr>
      </tbody>
      <?php } ?>

    <?php if($output['appointment_pay_list']) { ?>
    <tfoot>
      <tr>
        <td colspan="19"><div class="pagination"> <?php echo $output['show_page']; ?> </div></td>
      </tr>
    </tfoot>
    <?php } ?>
  </table>
</div>
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" ></script>
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/sns.js" ></script>
<script type="text/javascript">
$(function(){
    $('#query_start_date').datepicker({dateFormat: 'yy-mm-dd'});
    $('#query_end_date').datepicker({dateFormat: 'yy-mm-dd'});
});
</script>
