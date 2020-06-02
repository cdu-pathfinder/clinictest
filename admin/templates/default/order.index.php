<?php defined('InclinicNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['appointment_manage'];?></h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['manage'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" action="index.php" name="formSearch" id="formSearch">
    <input type="hidden" name="act" value="appointment" />
    <input type="hidden" name="op" value="index" />
    <table class="tb-type1 nobappointment search">
      <tbody>
        <tr>
         <th><label><?php echo $lang['appointment_number'];?></label></th>
         <td><input class="txt2" type="text" name="appointment_sn" value="<?php echo $_GET['appointment_sn'];?>" /></td>
         <th><?php echo $lang['clic_name'];?></th>
         <td><input class="txt-short" type="text" name="clic_name" value="<?php echo $_GET['clic_name'];?>" /></td>
         <th><label><?php echo $lang['appointment_state'];?></label></th>
          <td colspan="4"><select name="appointment_state" class="querySelect">
              <option value=""><?php echo $lang['nc_please_choose'];?></option>
              <option value="10"<?php if($_GET['appointment_state'] == '10'){?>selected<?php }?>><?php echo $lang['appointment_state_new'];?></option>
              <option value="20"<?php if($_GET['appointment_state'] == '20'){?>selected<?php }?>><?php echo $lang['appointment_state_pay'];?></option>
              <option value="30"<?php if($_GET['appointment_state'] == '30'){?>selected<?php }?>><?php echo $lang['appointment_state_send'];?></option>
              <option value="40"<?php if($_GET['appointment_state'] == '40'){?>selected<?php }?>><?php echo $lang['appointment_state_success'];?></option>
              <option value="0"<?php if($_GET['appointment_state'] == '0'){?>selected<?php }?>><?php echo $lang['appointment_state_cancel'];?></option>
            </select></td>
        
        </tr>
        <tr>
          <th><label for="query_start_time"><?php echo $lang['appointment_time_from'];?></label></th>
          <td><input class="txt date" type="text" value="<?php echo $_GET['query_start_time'];?>" id="query_start_time" name="query_start_time">
            <label for="query_start_time">~</label>
            <input class="txt date" type="text" value="<?php echo $_GET['query_end_time'];?>" id="query_end_time" name="query_end_time"/></td>
         <th><?php echo $lang['buyer_name'];?></th>
         <td><input class="txt-short" type="text" name="buyer_name" value="<?php echo $_GET['buyer_name'];?>" /></td> <th>付款方式</th>
         <td>
            <select name="payment_code" class="w100">
            <option value=""><?php echo $lang['nc_please_choose'];?></option>
            <?php foreach($output['payment_list'] as $val) { ?>
            <option value="<?php echo $val['payment_code']; ?>"><?php echo $val['payment_name']; ?></option>
            <?php } ?>
            </select>
         </td>
          <td><a href="javascript:viod(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
            
            </td>
        </tr>
      </tbody>
    </table>
  </form>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title"><h5><?php echo $lang['nc_prompts'];?></h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
        <ul>
            <li><?php echo $lang['appointment_help1'];?></li>
            <li><?php echo $lang['appointment_help2'];?></li>
            <li><?php echo $lang['appointment_help3'];?></li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <div style="text-align:right;"><a class="btns" target="_blank" href="index.php?<?php echo $_SERVER['QUERY_STRING'];?>&op=export_step1"><span><?php echo $lang['nc_export'];?>Excel</span></a></div>
  <table class="table tb-type2 nobdb">
    <thead>
      <tr class="thead">
        <th><?php echo $lang['appointment_number'];?></th>
        <th><?php echo $lang['clic_name'];?></th>
        <th><?php echo $lang['buyer_name'];?></th>
        <th class="align-center"><?php echo $lang['appointment_time'];?></th>
        <th class="align-center"><?php echo $lang['appointment_total_price'];?></th>
        <th class="align-center"><?php echo $lang['payment'];?></th>
        <th class="align-center"><?php echo $lang['appointment_state'];?></th>
        <th class="align-center"><?php echo $lang['nc_handle'];?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(count($output['appointment_list'])>0){?>
      <?php foreach($output['appointment_list'] as $appointment){?>
      <tr class="hover">
        <td><?php echo $appointment['appointment_sn'];?></td>
        <td><?php echo $appointment['clic_name'];?></td>
        <td><?php echo $appointment['buyer_name'];?></td>
        <td class="nowrap align-center"><?php echo date('Y-m-d H:i:s',$appointment['add_time']);?></td>
        <td class="align-center"><?php echo $appointment['appointment_amount'];?></td>
        <td class="align-center"><?php echo appointmentPaymentName($appointment['payment_code']);?></td>
        <td class="align-center"><?php echo appointmentState($appointment);?></td>
        <td class="w144 align-center"><a href="index.php?act=appointment&op=show_appointment&appointment_id=<?php echo $appointment['appointment_id'];?>"><?php echo $lang['nc_view'];?></a>

        <!-- 取消订单 -->
    		<?php if($appointment['if_cancel']) {?>
        	| <a href="javascript:void(0)" onclick="if(confirm('<?php echo $lang['appointment_confirm_cancel'];?>')){location.href='index.php?act=appointment&op=change_state&state_type=cancel&appointment_id=<?php echo $appointment['appointment_id']; ?>'}">
        	<?php echo $lang['appointment_change_cancel'];?></a>
        	<?php }?>

        	<!-- 收款 -->
    		<?php if($appointment['if_system_receive_pay']) {?>
	        	| <a href="index.php?act=appointment&op=change_state&state_type=receive_pay&appointment_id=<?php echo $appointment['appointment_id']; ?>">
	        	<?php echo $lang['appointment_change_received'];?></a>
    		<?php }?>
        	</td>
      </tr>
      <?php }?>
      <?php }else{?>
      <tr class="no_data">
        <td colspan="15"><?php echo $lang['nc_no_record'];?></td>
      </tr>
      <?php }?>
    </tbody>
    <tfoot>
      <tr class="tfoot">
        <td colspan="15" id="dataFuncs"><div class="pagination"> <?php echo $output['show_page'];?> </div></td>
      </tr>
    </tfoot>
  </table>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
$(function(){
    $('#query_start_time').datepicker({dateFormat: 'yy-mm-dd'});
    $('#query_end_time').datepicker({dateFormat: 'yy-mm-dd'});
    $('#ncsubmit').click(function(){
    	$('input[name="op"]').val('index');$('#formSearch').submit();
    });
});
</script> 
