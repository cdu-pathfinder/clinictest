<?php defined('InclinicNC') or exit('Access Invalid!');?>
<style type="text/css">
#container .layout {
	background: none !important;
	min-height: 100px!important;
}
</style>

<div class="wrap-shadow">
  <div class="wrap-all ncu-appointment-view">
    <h2><?php echo $lang['member_show_express_detail'];?></h2>
    <dl class="express-info">
      <dt><span><?php echo $lang['member_change_appointment_no'].$lang['nc_colon'];?><?php echo $output['appointment_info']['appointment_sn']; ?></span><span class="ml30"><?php echo $lang['member_appointment_time'].$lang['nc_colon'];?><?php echo date("Y-m-d H:i:s",$output['appointment_info']['add_time']); ?></span><span class="ml30"><?php echo $output['clic_info']['clic_name']; ?></span></dt>
      <dd><?php echo $lang['member_show_expre_my_fdback'].$lang['nc_colon'];?><?php echo $output['clic_info']['appointment_message']; ?></dd>
    </dl>
    <div class="express-detail">
      <div class="sidebar">
        <p><?php echo $lang['member_show_expre_type'];?></p>
        <p><?php echo $lang['member_show_express_ship_code'].$lang['nc_colon'];?><?php echo $output['appointment_info']['shipping_code']; ?></p>
        <p><?php echo $lang['member_show_expre_company'].$lang['nc_colon'];?><a target="_blank" href="<?php echo $output['e_url'];?>"><?php echo $output['e_name'];?></a></p>
      </div>
      <div class="wrap">
        <div class="tabmenu">
          <ul class="tab pngFix">
            <li class="active"><a><?php echo $lang['member_show_express_ship_dstatus'];?></a></li>
          </ul>
        </div>
        <ul class="express-log" id="express_list">
          <?php if($output['appointment_info']['shipping_time']) { ?>
          <li class="loading"><?php echo $lang['nc_common_loading'];?></li>
          <?php } ?>
        </ul>
        <div class="ncm-notes" style="display:none"><?php echo $lang['member_show_express_ship_tips'];?></div>
        <div class="express-oredr">
          <h4><?php echo $lang['member_show_appointment_info'];?></h4>
          <ul>
            <?php if(is_array($output['appointment_doctors_list']) and !empty($output['appointment_doctors_list'])) {
					foreach($output['appointment_doctors_list'] as $val) {
				?>
            <li>
              <div class="doctors-pic-small"><span class="thumb size60 tip" title="<?php echo $val['doctors_name']; ?>"><i></i><a target="_blank" href="index.php?act=doctors&doctors_id=<?php echo $val['doctors_id']; ?>"><img src="<?php echo thumb($val, 60); ?>" onload="javascript:DrawImage(this,60,60);"/></a></span> </div>
              <div class="doctors-name"><?php echo $val['doctors_name']; ?></div>
              <div class="doctors-price"><i class="mr5"><?php echo $val['doctors_price']; ?></i>x <?php echo $val['doctors_num']; ?></div>
            </li>
            <?php } } ?>
          </ul>
        </div>
        <div class="express-add">
          <p><strong><?php echo $lang['member_show_receive_info'].$lang['nc_colon'];?></strong><?php echo $output['appointment_info']['area_info'];?>&nbsp;<?php echo $output['appointment_info']['address'];?>&nbsp;<?php echo $output['appointment_info']['zip_code']?>&nbsp;<?php echo $output['appointment_info']['true_name'];?>&nbsp;<?php echo $output['appointment_info']['tel_phone'];?>&nbsp;<?php echo $output['appointment_info']['mob_phone']; ?></p>
          <p><strong><?php echo $lang['member_show_deliver_info'].$lang['nc_colon'];?></strong><?php echo $output['daddress_info']['area_info']; ?>&nbsp;<?php echo $output['daddress_info']['address'];?>&nbsp;<?php echo $output['daddress_info']['zip_code'];?>&nbsp;<?php echo $output['daddress_info']['seller_name'];?>&nbsp;<?php echo $output['daddress_info']['tel_phone'];?>&nbsp;<?php echo $output['daddress_info']['mob_phone'];?></p>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script> 

<script>
$(function(){
	//Ajax提示
	$('.tip').poshytip({
		className: 'tip-yellowsimple',
		showTimeout: 1,
		alignTo: 'target',
		alignX: 'center',
		alignY: 'bottom',
		offsetX: 5,
		offsetY: 0,
		allowTipHover: false
	});
      var_send = '<li><?php echo date("Y-m-d H:i:s",$output['appointment_info']['shipping_time']); ?>&nbsp;&nbsp;<?php echo $lang['member_show_seller_has_send'];?></li>';
	$.getJSON('index.php?act=member&op=get_express&e_code=<?php echo $output['e_code']?>&shipping_code=<?php echo $output['shipping_code']?>&t=<?php echo random(7);?>',function(data){
		if(data){
			data = var_send+data;
			$('#express_list').html(data).next().css('display','');
		}else{
			$('#express_list').html(var_send);
		}
	});
});
</script>