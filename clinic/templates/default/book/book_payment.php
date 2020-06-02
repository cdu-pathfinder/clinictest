<?php defined('InclinicNC') or exit('Access Invalid!');?>

<div class="ncc-receipt-info" id="paymentCon">
  <div class="ncc-receipt-info-title">
    <h3>payment method</h3>
    <?php if (!$output['deny_edit_payment']) {?>
    <a href="javascript:void(0)" nc_type="buy_edit" id="edit_payment">[change]</a>
    <?php }?>
  </div>
  <div class="ncc-candidate-items">
    <ul>
      <li>pay online</li>
    </ul>
  </div>
  <div id="payment_list" class="ncc-candidate-items" style="display:none">
    <ul>
      <li>
        <input type="radio" value="online" name="payment_type" id="payment_type_online">
        <label for="payment_type_online">pay online</label>
      </li>
      <li>
        <?php if ($output['ifshow_offpay'] === true) {?>
        <input type="radio" value="offline" name="payment_type" id="payment_type_offline">
        <label for="payment_type_offline">cash</label>
        <?php if (count($output['pay_doctors_list']['online']) > 0) {?>
        <a id="show_doctors_list" style="display: none" class="ncc-payment-showdoctors" href="javascript:void(0);"><i class="icon-truck"></i>cash (<?php echo count($output['pay_doctors_list']['offline']);?>doctors) + <i class="icon-credit-card"></i>pay online (<?php echo count($output['pay_doctors_list']['online']);?>doctors)</a>
        <?php } ?>
        <?php } ?>
      </li>
    </ul>
    <div class="hr16"> <a href="javascript:void(0);" class="ncc-btn ncc-btn-red" id="hide_payment_list">Save payment method</a></div>
  </div>
  <?php if ($output['ifshow_offpay']) {?>
  <div id="ncc-payment-showdoctors-list" class="ncc-payment-showdoctors-list">
    <dl>
      <?php foreach ($output['pay_doctors_list'] as $type => $data) {?>
      <!-- 如果没有在线支付的商品，都是货到付款的，则就不再显示两种支付方式的商品数量了 -->
      <?php if (count($output['pay_doctors_list']['online']) > 0 && !empty($data) && is_array($data)) {?>
      <dt><?php echo $type == 'offline' ? 'cash' : 'pay online';?></dt>
      <dd>
        <?php foreach($data as $value) {?>
        <div class="doctors-thumb"><span><img src="<?php echo thumb($value,60);?>"></span></div>
        <?php } ?>
      </dd>
      <?php } ?>
      <?php } ?>
    </dl>
  </div>
  <?php } ?>
</div>

<!-- 在线支付和货到付款组合时，显示弹出确认层内容 -->
<?php if ($output['ifshow_offpay']) {?>
<div id="confirm_offpay_doctors_list" style="display: none;">
  <?php foreach ($output['pay_doctors_list'] as $type => $data) {?>
  <?php if (!empty($data) && is_array($data)) {?>
  <dl class="ncc-offpay-list">
    <dt>appointment support<strong><?php echo $type == 'offline' ? 'cash' : 'pay online';?></strong></dt>
    <dd>
      <ul>
        <?php foreach($data as $value) {?>
        <li><span title="<?php echo $value['doctors_name'];?>"><img src="<?php echo thumb($value,60);?>"></span></li>
        <?php } ?>
      </ul>
      <label>
        <input type="radio" value="" checked="checked">
        <?php echo $type == 'offline' ? 'cash' : 'pay online';?></label>
    </dd>
  </dl>
  <?php } ?>
  <?php } ?>
  <div class="tc mt10 mb10"><a href="javascript:void(0);" class="ncc-btn ncc-btn-orange" id="close_confirm_button">confirm pay method</a></div>
</div>
<?php } ?>
<script type="text/javascript">
$(function(){
	//点击修改支付方式
    $('#edit_payment').on('click',function(){
        $('#edit_payment').parent().next().remove();
        $(this).hide();
        $('#paymentCon').addClass('current_box');
        $('#payment_list').show();
        disableOtherEdit('If you need to modify, please save the payment method first');
    });
    //保存支付方式
    $('#hide_payment_list').on('click',function(){
        var payment_type = $('input[name="payment_type"]:checked').val();
        if ($('input[name="payment_type"]:checked').size() == 0) return;
        //判断该地区(县ID)是否能货到付款
        if (payment_type == 'offline' && $('#allow_offpay').val() == '0') {
            showDialog('您目前选择的收货地区不支持货到付款', 'error','','','','','','','','',2);return;
        }
        $('#payment_list').hide();
        $('#edit_payment').show();
		$('.current_box').removeClass('current_box');
        var content = (payment_type == 'online' ? '在线支付' : '货到付款');
        $('#pay_name').val(payment_type);

        if (payment_type == 'offline'){
            //如果混合支付（在线+货到付款）
            <?php if ($output['ifshow_offpay'] === true && count($output['pay_doctors_list']['online']) > 0) {?>
            content = $('#show_doctors_list').clone().html();
            $('#edit_payment').parent().after('<div class="ncc-candidate-items"><ul><li>您选择货到付款 + 在线支付完成此订单<br/><a href="javsacript:void(0);" id="show_doctors_list" class="ncc-payment-showdoctors">'+content+'</a></li></ul></div>');
            $('#show_doctors_list').hover(function(){showPaydoctorsList(this)},function(){$('#ncc-payment-showdoctors-list').fadeOut()});
            <?php }else{?>
                $('#edit_payment').parent().after('<div class="ncc-candidate-items"><ul><li>'+content+'</li></ul></div>');
            <?php }?>
        }else{
            $('#edit_payment').parent().after('<div class="ncc-candidate-items"><ul><li>'+content+'</li></ul></div>');
        }
        ableOtherEdit();
    });
    $('#show_doctors_list').hover(function(){showPaydoctorsList(this)},function(){$('#ncc-payment-showdoctors-list').fadeOut()});
    function showPaydoctorsList(item){
		var pos = $(item).position();
		var pos_x = pos.left+0;
		var pos_y = pos.top+25;
		$("#ncc-payment-showdoctors-list").css({'left' : pos_x, 'top' : pos_y,'position' : 'absolute','display' : 'block'});        
        $('#ncc-payment-showdoctors-list').addClass('ncc-payment-showdoctors-list').fadeIn();
    }
    $('input[name="payment_type"]').on('change',function(){
        if ($(this).val() == 'online'){
            $('#show_doctors_list').hide();
        } else {
            //判断该地区(县ID)是否能货到付款
            if ($('#allow_offpay').val() == '0') {
                $('#payment_type_online').attr('checked',true);
                showDialog('您目前选择的收货地区不支持货到付款', 'error','','','','','','','','',2);return;
            }
        	html_form('confirm_pay_type', '请确认支付方式', $('#confirm_offpay_doctors_list').html(), 500,1);
            $('#show_doctors_list').show();
        }
    });

    $('body').on('click','#close_confirm_button',function(){
        DialogManager.close('confirm_pay_type');
    });
})
</script>