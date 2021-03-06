<?php defined('InclinicNC') or exit('Access Invalid!');?>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js"></script> 
<script src="<?php echo RESOURCE_SITE_URL;?>/js/area_array.js"></script> 
<div class="ncc-main">
  <div class="ncc-title">
    <h3><?php echo $lang['cart_index_ensure_info'];?></h3>
    <h5>Please check and fill in the reservation, invoice and other information carefully.</h5>
  </div>
    <?php include template('buy/buy_address');?>
    <?php include template('buy/buy_payment');?>
    <?php include template('buy/buy_invoice');?>
    <form method="post" id="appointment_form" name="appointment_form" action="index.php">  
    <?php include template('buy/buy_doctors_list');?>
    <?php include template('buy/buy_amount');?>
    <input value="buy" type="hidden" name="act">
    <input value="buy_step2" type="hidden" name="op">
    <!-- 来源于购物车标志 -->
    <input value="<?php echo $output['ifcart'];?>" type="hidden" name="ifcart">

    <!-- offline/online -->
    <input value="online" name="pay_name" id="pay_name" type="hidden">

    <!-- 是否保存增值税发票判断标志 -->
    <input value="<?php echo $output['vat_hash'];?>" name="vat_hash" type="hidden">

    <!-- 收货地址ID -->
    <input value="<?php echo $output['address_info']['address_id'];?>" name="address_id" id="address_id" type="hidden">

    <!-- 城市ID(运费) -->
    <input value="" name="buy_city_id" id="buy_city_id" type="hidden">

    <!-- 记录所选地区是否支持货到付款 第一个前端JS判断 第二个后端PHP判断 -->
    <input value="" id="allow_offpay" name="allow_offpay" type="hidden">
    <input value="" id="offpay_hash" name="offpay_hash" type="hidden">

    <!-- 默认使用的发票 -->
    <input value="<?php echo $output['inv_info']['inv_id'];?>" name="invoice_id" id="invoice_id" type="hidden">

    <input value="<?php echo getReferer();?>" name="ref_url" type="hidden">
    </form>
</div>
<script type="text/javascript">
//计算部运费和每个店铺小计
function calcappointment() {
    var allTotal = 0;
    $('em[nc_type="eachclicTotal"]').each(function(){
        clic_id = $(this).attr('clic_id');
        var eachTotal = 0;
        if ($('#eachclicFreight_'+clic_id).length > 0) {
        	eachTotal += parseFloat($('#eachclicFreight_'+clic_id).html());
	    }
        if ($('#eachclicdoctorsTotal_'+clic_id).length > 0) {
        	eachTotal += parseFloat($('#eachclicdoctorsTotal_'+clic_id).html());
	    }
        if ($('#eachclicManSong_'+clic_id).length > 0) {
        	eachTotal += parseFloat($('#eachclicManSong_'+clic_id).html());
	    }
        if ($('#eachclicVoucher_'+clic_id).length > 0) {
        	eachTotal += parseFloat($('#eachclicVoucher_'+clic_id).html());
        }
        $(this).html(number_format(eachTotal,2));
        allTotal += eachTotal;
    });
    $('#appointmentTotal').html(number_format(allTotal,2));
}
$(function(){
    $.ajaxSetup({
        async : false
    });
    $('select[nctype="voucher"]').on('change',function(){
        if ($(this).val() == '') {
        	$('#eachclicVoucher_'+items[1]).html('-0.00');
        } else {
            var items = $(this).val().split('|');
            $('#eachclicVoucher_'+items[1]).html('-'+number_format(items[2],2));
        }
        calcappointment();
    });
    <?php if (!empty($output['available_pd_amount'])) { ?>
    $('input[name="pd_pay"]').on('change',function(){
        if ($(this).attr('checked')) {
        	$('#password').val('');
        	$('#password_callback').val('');
            $('#pd_password').show();
        } else {
        	$('#pd_password').hide();
        }
    });
    $('#pd_pay_submit').on('click',function(){
        if ($('#password').val() == '') {
        	showDialog('Please enter your login password', 'error','','','','','','','','',2);return false;
        }
        $('#password_callback').val('');
		$.get("index.php?act=buy&op=check_pd_pwd", {'password':$('#password').val()}, function(data){
            if (data == '1') {
            	$('#password_callback').val('1');
            	$('#pd_password').hide();
            } else {
            	$('#password').val('');
            	showDialog('password error', 'error','','','','','','','','',2);
            }
        });
    });
    <?php } ?>
});
function disableOtherEdit(showText){
	$('a[nc_type="buy_edit"]').each(function(){
	    if ($(this).css('display') != 'none'){
			$(this).after('<font color="#B0B0B0">' + showText + '</font>');
		    $(this).hide();		    
	    }
	});
	disableSubmitappointment();
}
function ableOtherEdit(){
	$('a[nc_type="buy_edit"]').show().next('font').remove();
	ableSubmitappointment();
	
}
function ableSubmitappointment(){
	$('#submitappointment').on('click',function(){submitNext()}).css('cursor','').addClass('ncc-btn-acidblue');
}
function disableSubmitappointment(){
	$('#submitappointment').unbind('click').css('cursor','not-allowed').removeClass('ncc-btn-acidblue');
}
</script>