<?php defined('InclinicNC') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['nc_domain_manage'];?></h3>
      <ul class="tab-base">
     		<li><a href="index.php?act=domain&op=clic_domain_setting"><span><?php echo $lang['nc_config'];?></span></a></li>
        <li><a href="index.php?act=domain&op=clic_domain_list"><span><?php echo $lang['nc_domain_clinic'];?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_edit'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="clic_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="clic_id" value="<?php echo $output['clic_array']['clic_id'];?>" />
    <table class="table tb-type2">
      <tbody>
        <tr class="nobappointment">
          <td colspan="2" class="required"><label><?php echo $lang['clic_user_name'];?>:</label></td>
        </tr>
        <tr class="nobappointment">
          <td class="vatop rowform"><?php echo $output['clic_array']['member_name'];?></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label> <?php echo $lang['clic_name'];?>:</label></td>
        </tr>
        <tr class="nobappointment">
          <td class="vatop rowform"><?php echo $output['clic_array']['clic_name'];?></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label><?php echo $lang['clic_domain'];?>:</label></td>
        </tr>
        <tr class="nobappointment">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['clic_array']['clic_domain'];?>" id="clic_domain" name="clic_domain" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label><?php echo $lang['clic_domain_times'];?>:</label></td>
        </tr>
        <tr class="nobappointment">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['clic_array']['clic_domain_times'];?>" id="clic_domain_times" name="clic_domain_times" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="15"><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript">
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#clic_form").valid()){
     $("#clic_form").submit();
	}
	});
	jQuery.validator.addMethod("domain", function(value, element) {
			return this.optional(element) || /^[\w\-]+$/i.test(value);
		}, ""); 
	$('#clic_form').validate({
		errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },

		rules : {
			clic_domain: {
				domain: true,
        rangelength:[<?php echo $output['subdomain_length'][0];?>, <?php echo $output['subdomain_length'][1];?>]
			},
			clic_domain_times: {
				digits : true,
        max:<?php echo $GLOBALS['setting_config']['subdomain_times'];?>
			}
		},
		messages : {
			clic_domain: {
				domain: '<?php echo $lang['clic_domain_valid'];?>',
        rangelength:'<?php echo $lang['clic_domain_rangelength'];?>'
			},
			clic_domain_times: {
				digits: '<?php echo $lang['clic_domain_times_digits'];?>',
        max:'<?php echo $lang['clic_domain_times_max'];?>'
			}
		}
	});
});
</script>
