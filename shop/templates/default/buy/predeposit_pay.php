<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="ncc-main">
  <div class="ncc-title">
    <h3><?php echo $lang['cart_index_payment'];?></h3>
    <h5>View your top-up history can get through <a href="index.php?act=predeposit&op=index" target="_blank">My top up list </a>to look.</h5>
  </div>
  <form action="index.php?act=payment" method="POST" id="buy_form">
    <input type="hidden" name="pdr_sn" value="<?php echo $output['pdr_info']['pdr_sn'];?>">
    <input type="hidden" id="payment_code" name="payment_code" value="">
    <input type="hidden" name="order_type" value="pd_rechange">
    <div class="ncc-receipt-info">
    <div>Recharge number : <?php echo $output['pdr_info']['pdr_sn'];?></div>
      <div class="ncc-receipt-info-title">
        <h3>You have applied for account balance recharge, please pay online immediately!
          Recharge amount:<strong>$<?php echo $output['pdr_info']['pdr_amount'];?></strong> </h3>
      </div>
    </div>
    <div class="ncc-receipt-info">
      <?php if (!isset($output['payment_list'])) {?>
      <?php }else if (empty($output['payment_list'])){?>
      <div class="nopay"><?php echo $lang['cart_step2_paymentnull_1']; ?> <a href="index.php?act=home&op=sendmsg&member_id=<?php echo $output['order']['seller_id'];?>"><?php echo $lang['cart_step2_paymentnull_2'];?></a> <?php echo $lang['cart_step2_paymentnull_3'];?></div>
      <?php } else {?>
      <div class="ncc-receipt-info-title">
        <h3>Payment options</h3>
      </div>
      <ul class="ncc-payment-list">
        <?php foreach($output['payment_list'] as $val) { ?>
        <li payment_code="<?php echo $val['payment_code']; ?>">
          <label for="pay_<?php echo $val['payment_code']; ?>">
          <i></i>
          <div class="logo" for="pay_<?php echo $val['payment_id']; ?>"> <img src="<?php echo SHOP_TEMPLATES_URL?>/images/payment/<?php echo $val['payment_code']; ?>_logo.gif" /> </div>
          <div class="predeposit" nc_type="predeposit" style="display:none">
            <?php if ($val['payment_code'] == 'predeposit') {?>
                <?php if ($output['available_predeposit']) {?>
                <p>Current pre-deposit balance<br/>￥<?php echo $output['available_predeposit'];?><br/>Not enough to pay for the order<br/><a href="<?php echo SHOP_SITE_URL.'/index.php?act=predeposit';?>">Recharge now</a></p>
                <?php } else {?>
                <input type="password" class="text w120" name="password" maxlength="40" id="password" value="">
                <p>Please enter your login password for security verification when using the pre-deposit in the station for payment.</p>
                <?php } ?>
            <?php } ?>
          </div>
          </label>
        </li>
        <?php } ?>
      </ul>
      <?php } ?>
    </div>
    <div class="ncc-bottom tc mb50"><a href="javascript:void(0);" id="next_button" class="ncc-btn ncc-btn-green"><i class="icon-shield"></i>Confirm and submit payment</a></div>
  </form>
</div>
<script type="text/javascript">
$(function(){
    $('.ncc-payment-list > li').on('click',function(){
    	$('.ncc-payment-list > li').removeClass('using');
        $(this).addClass('using');
        $('#payment_code').val($(this).attr('payment_code'));
    });
    $('#next_button').on('click',function(){
        if ($('#payment_code').val() != '') {
            $('#buy_form').submit();
        }
    });
});
</script>