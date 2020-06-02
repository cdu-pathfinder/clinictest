<?php defined('InclinicNC') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="ncsc-form-default">
  <form id="add_form" action="<?php echo urlclinic('clic_account', 'account_save');?>" method="post">
    <dl>
      <dt><i class="required">*</i>前台用户名<?php echo $lang['nc_colon'];?></dt>
      <dd><input class="w120 text" name="member_name" type="text" id="member_name" value="" />
          <span></span>
        <p class="hint"></p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>用户密码<?php echo $lang['nc_colon'];?></dt>
      <dd><input class="w120 text" name="password" type="password" id="password" value="" />
          <span></span>
        <p class="hint"></p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>登录帐号<?php echo $lang['nc_colon'];?></dt>
      <dd><input class="w120 text" name="seller_name" type="text" id="seller_name" value="" />
          <span></span>
        <p class="hint">新帐号登录商家中心的用户名，密码与该帐号前台密码相同</p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>帐号组<?php echo $lang['nc_colon'];?></dt>
      <dd><select name="group_id">
            <?php foreach($output['seller_group_list'] as $value) { ?>
            <option value="<?php echo $value['group_id'];?>"><?php echo $value['group_name'];?></option>
            <?php } ?>
          </select>
          <span></span>
        <p class="hint"></p>
      </dd>
    </dl>
    <div class="bottom">
      <label class="submit-bappointment">
        <input type="submit" class="submit" value="<?php echo $lang['nc_submit'];?>">
      </label>
    </div>
  </form>
</div>
<script>
$(document).ready(function(){
    jQuery.validator.addMethod("seller_name_exist", function(value, element, params) { 
        var result = true;
        $.ajax({  
            type:"GET",  
            url:'<?php echo urlclinic('clic_account', 'check_seller_name_exist');?>',  
            async:false,  
            data:{seller_name: $('#seller_name').val()},  
            success: function(data){  
                if(data == 'true') {
                    $.validator.messages.seller_name_exist = "卖家帐号已存在";
                    result = false;
                }
            }  
        });  
        return result;
    }, '');

    jQuery.validator.addMethod("check_member_password", function(value, element, params) { 
        var result = true;
        $.ajax({  
            type:"GET",  
            url:'<?php echo urlclinic('clic_account', 'check_seller_member');?>',  
            async:false,  
            data:{member_name: $('#member_name').val(), password: $('#password').val()},  
            success: function(data){  
                if(data != 'true') {
                    $.validator.messages.check_member_password = "前台用户验证失败";
                    result = false;
                }
            }  
        });  
        return result;
    }, '');

    $('#add_form').validate({
        onkeyup: false,
        errorPlacement: function(error, element){
            element.nextAll('span').first().after(error);
        },
    	submitHandler:function(form){
    		ajaxpost('add_form', '', '', 'onerror');
    	},
        rules: {
            member_name: {
                required: true
            },
            password: {
                required: true,
                check_member_password: true
            },
            seller_name: {
                required: true,
                maxlength: 50, 
                seller_name_exist: true
            },
            group_id: {
                required: true
            }
        },
        messages: {
            member_name: {
                required: '<i class="icon-exclamation-sign"></i>前台用户名不能为空'
            },
            password: {
                required: '<i class="icon-exclamation-sign"></i>用户密码不能为空',
                remote: '<i class="icon-exclamation-sign"></i>用户名密码错误'
            },
            seller_name: {
                required: '<i class="icon-exclamation-sign"></i>卖家帐号不能为空',
                maxlength: '<i class="icon-exclamation-sign"></i>卖家帐号最多50个字'
            },
            group_id: {
                required: '<i class="icon-exclamation-sign"></i>请选择帐号组'
            }
        }
    });
});
</script> 