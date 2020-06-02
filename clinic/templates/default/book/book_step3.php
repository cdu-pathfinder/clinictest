<?php defined('InclinicNC') or exit('Access Invalid!');?>

<div class="ncc-main">
  <div class="ncc-title">
    <h3><?php echo $lang['cart_index_buy_finish'];?></h3>
    <h5>The appointment has been paid. I wish you doctor health.</h5>
  </div>
  <div class="ncc-receipt-info mb30">
  <div class="ncc-finish-a"><i></i>appointment paid successfully! You have successfully paid the appointment amount<em>$<?php echo $_GET['pay_amount'];?></em>。</div>
  <div class="ncc-finish-b">Through the user center<a href="<?php echo clinic_SITE_URL?>/index.php?act=member_appointment">doctor booked</a>Check the appointment status.</div>
  <div class="ncc-finish-c mb30"><a href="<?php echo clinic_SITE_URL?>" class="ncc-btn-mini ncc-btn-green mr15"><i class="icon-clinicping-cart"></i>continue booking</a><a href="<?php echo clinic_SITE_URL?>/index.php?act=member_appointment" class="ncc-btn-mini ncc-btn-acidblue"><i class="icon-file-text-alt"></i>view appointment</a></div>
  </div>
</div>