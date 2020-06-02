<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php defined('InclinicNC') or exit('Access Invalid!');?>
<link href="<?php echo clinic_TEMPLATES_URL;?>/css/base.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo clinic_TEMPLATES_URL;?>/css/seller_center.css".css" rel="stylesheet" type="text/css"/>
<style type="text/css">
body {
	background-color: #FFF;
	background-image: none;
}
</style>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.printarea.js" charset="utf-8"></script>
<title><?php echo $lang['member_printappointment_print'];?>--<?php echo $output['clic_info']['clic_name'];?><?php echo $lang['member_printappointment_title'];?></title>
</head>

<body>
<?php if (!empty($output['appointment_info'])){?>
<div class="print-layout">
  <div class="print-btn" id="printbtn" title="<?php echo $lang['member_printappointment_print_tip'];?>"><i></i><a href="javascript:void(0);"><?php echo $lang['member_printappointment_print'];?></a></div>
  <div class="a5-size"></div>
  <dl class="a5-tip">
    <dt>
      <h1>A5</h1>
      <em>Size: 210mm x 148mm</em></dt>
    <dd><?php echo $lang['member_printappointment_print_tip_A5'];?></dd>
  </dl>
  <div class="a4-size"></div>
  <dl class="a4-tip">
    <dt>
      <h1>A4</h1>
      <em>Size: 210mm x 297mm</em></dt>
    <dd><?php echo $lang['member_printappointment_print_tip_A4'];?></dd>
  </dl>
  <div class="print-page">
    <div id="printarea">
      <?php foreach ($output['doctors_list'] as $item_k =>$item_v){?>
      <div class="appointmentprint">
        <div class="top">
          <?php if (empty($output['clic_info']['clic_label'])){?>
          <div class="full-title"><?php echo $output['clic_info']['clic_name'];?> <?php echo $lang['member_printappointment_title'];?></div>
          <?php }else {?>
          <div class="logo" ><img src="<?php echo $output['clic_info']['clic_label']; ?>"/></div>
          <div class="logo-title"><?php echo $output['clic_info']['clic_name'];?><?php echo $lang['member_printappointment_title'];?></div>
          <?php }?>
        </div>
        <table class="buyer-info">
          <tr>
            <td class="w200"><?php echo $lang['member_printappointment_truename'].$lang['nc_colon']; ?><?php echo $output['appointment_info']['extend_appointment_common']['reciver_name'];?></td>
            <td><?php echo '电话'.$lang['nc_colon']; ?><?php echo @$output['appointment_info']['extend_appointment_common']['reciver_info']['phone'];?></td>
            <td></td>
          </tr>
          <tr>
            <td colspan="3"><?php echo $lang['member_printappointment_address'].$lang['nc_colon']; ?><?php echo @$output['appointment_info']['extend_appointment_common']['reciver_info']['address'];?></td>
          </tr>
          <tr>
            <td><?php echo $lang['member_printappointment_appointmentno'].$lang['nc_colon'];?><?php echo $output['appointment_info']['appointment_sn'];?></td>
            <td><?php echo $lang['member_printappointment_appointmentadddate'].$lang['nc_colon'];?><?php echo @date('Y-m-d',$output['appointment_info']['add_time']);?></td>
            <td><?php if ($output['appointment_info']['shippin_code']){?>
              <span><?php echo $lang['member_printappointment_shippingcode'].$lang['nc_colon']; ?><?php echo $output['appointment_info']['shipping_code'];?></span>
              <?php }?></td>
          </tr>
        </table>
        <table class="appointment-info">
          <thead>
            <tr>
              <th class="w40"><?php echo $lang['member_printappointment_serialnumber'];?></th>
              <th class="tl"><?php echo $lang['member_printappointment_doctorsname'];?></th>
              <th class="w70 tl"><?php echo $lang['member_printappointment_doctorsprice'];?>(<?php echo $lang['currency_zh'];?>)</th>
              <th class="w50"><?php echo $lang['member_printappointment_doctorsnum'];?></th>
              <th class="w70 tl"><?php echo $lang['member_printappointment_subtotal'];?>(<?php echo $lang['currency_zh'];?>)</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($item_v as $k=>$v){?>
            <tr>
              <td><?php echo $k;?></td>
              <td class="tl"><?php echo $v['doctors_name'];?></td>
              <td class="tl"><?php echo $lang['currency'].$v['doctors_price'];?></td>
              <td><?php echo $v['doctors_num'];?></td>
              <td class="tl"><?php echo $lang['currency'].$v['doctors_all_price'];?></td>
            </tr>
            <?php }?>
            <tr>
              <th></th>
              <th colspan="2" class="tl"><?php echo $lang['member_printappointment_amountto'];?></th>
              <th><?php echo $output['doctors_all_num'];?></th>
              <th class="tl"><?php echo $lang['currency'].$output['doctors_total_price'];?></th>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="10"><span><?php echo $lang['member_printappointment_totle'].$lang['nc_colon'];?><?php echo $lang['currency'].$output['doctors_total_price'];?></span><span><?php echo $lang['member_printappointment_freight'].$lang['nc_colon'];?><?php echo $lang['currency'].$output['appointment_info']['shipping_fee'];?></span><span><?php echo $lang['member_printappointment_privilege'].$lang['nc_colon'];?><?php echo $lang['currency'].$output['promotion_amount'];?></span><span><?php echo $lang['member_printappointment_appointmentamount'].$lang['nc_colon'];?><?php echo $lang['currency'].$output['appointment_info']['appointment_amount'];?></span><span><?php echo $lang['member_printappointment_clinic'].$lang['nc_colon'];?><?php echo $output['clic_info']['clic_name'];?></span>
                <?php if (!empty($output['clic_info']['clic_tel'])){?>
                <span><?php echo $lang['member_printappointment_clinictelephone'].$lang['nc_colon'];?><?php echo $output['clic_info']['clic_tel'];?></span>
                <?php }?>
                <?php if (!empty($output['clic_info']['clic_qq'])){?>
                <span>QQ：<?php echo $output['clic_info']['clic_qq'];?></span>
                <?php }elseif (!empty($output['clic_info']['clic_ww'])){?>
                <span><?php echo $lang['member_printappointment_clinicww'].$lang['nc_colon'];?><?php echo $output['clic_info']['clic_ww'];?></span>
                <?php }?></th>
            </tr>
          </tfoot>
        </table>
        <?php if (empty($output['clic_info']['clic_stamp'])){?>
        <div class="explain">
        	<?php echo $output['clic_info']['clic_printdesc'];?>
        </div>
        <?php }else {?>
        <div class="explain">
        	<?php echo $output['clic_info']['clic_printdesc'];?>
        </div>
        <div class="seal"><img src="<?php echo $output['clic_info']['clic_stamp'];?>" onload="javascript:DrawImage(this,120,120);"/></div>
        <?php }?>
        <div class="tc page"><?php echo $lang['member_printappointment_pagetext_1']; ?><?php echo $item_k;?><?php echo $lang['member_printappointment_pagetext_2']; ?>/<?php echo $lang['member_printappointment_pagetext_3']; ?><?php echo count($output['doctors_list']);?><?php echo $lang['member_printappointment_pagetext_2']; ?></div>
      </div>
      <?php }?>
    </div>
    <?php }?>
  </div>
</div>
</body>
<script>
$(function(){
	$("#printbtn").click(function(){
	$("#printarea").printArea();
	});
});

//打印提示
$('#printbtn').poshytip({
	className: 'tip-yellowsimple',
	showTimeout: 1,
	alignTo: 'target',
	alignX: 'center',
	alignY: 'bottom',
	offsetY: 5,
	allowTipHover: false
});
</script>
</html>