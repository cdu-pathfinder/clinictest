<?php defined('InclinicNC') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="ncsc-form-default">
  <div class="alert"><strong><?php echo $lang['nc_explain'].$lang['nc_colon'];?></strong><?php echo $lang['clic_callcenter_notes'];?>
    </li>
  </div>
  <form method="post" action="index.php?act=clic_callcenter&op=save" id="callcenter_form" onsubmit="ajaxpost('callcenter_form','','','onerror')" class="ncs-message">
    <input type="hidden" name="form_submit" value="ok" />
    <dl nctype="pre">
      <dt><?php echo $lang['clic_callcenter_presales_service'].$lang['nc_colon']?></dt>
      <dd>
        <div class="ncs-message-title"><span class="name"><?php echo $lang['clic_callcenter_service_name'];?></span><span class="tool"><?php echo $lang['clic_callcenter_service_tool'];?></span><span class="number"><?php echo $lang['clic_callcenter_service_number'];?></span></div>
        <?php if(empty($output['clicinfo']['clic_presales'])){?>
        <div class="ncs-message-list"><span class="name tip" title="<?php echo $lang['clic_callcenter_name_title'];?>">
          <input type="text" class="text w60" value="<?php echo $lang['clic_callcenter_presales'];?>1" name="pre[1][name]" maxlength="10" />
          </span><span class="tool tip" title="<?php echo $lang['clic_callcenter_tool_title'];?>">
          <select name="pre[1][type]">
            <option value="0"><?php echo $lang['clic_callcenter_please_choose'];?></option>
            <option value="1">QQ</option>
            <option value="2"><?php echo $lang['clic_callcenter_wangwang'];?></option>
          </select>
          </span><span class="number tip" title="<?php echo $lang['clic_callcenter_number_title'];?>">
          <input name="pre[1][num]" type="text" class="text w180" maxlength="25" />
          </span><span class="del"><a nctype="del" href="javascript:void(0);" class="ncsc-btn"><i class="icon-trash"></i><?php echo $lang['nc_delete'];?></a></span></div>
        <?php }else{?>
        <?php foreach ($output['clicinfo']['clic_presales'] as $key=>$val){?>
        <div class="ncs-message-list"><span class="name tip" title="<?php echo $lang['clic_callcenter_name_title'];?>">
          <input type="text" class="text w60" value="<?php echo $val['name'];?>" name="pre[<?php echo $key;?>][name]" maxlength="10" />
          </span><span class="tool tip" title="<?php echo $lang['clic_callcenter_tool_title'];?>">
          <select name="pre[<?php echo $key;?>][type]">
            <option value="1" <?php if($val['type'] == 1){?>selected="selected"<?php }?>>QQ</option>
            <option value="2" <?php if($val['type'] == 2){?>selected="selected"<?php }?>><?php echo $lang['clic_callcenter_wangwang'];?></option>
          </select>
          </span><span class="number tip" title="<?php echo $lang['clic_callcenter_number_title'];?>">
          <input name="pre[<?php echo $key;?>][num]" type="text" class="text w180" value="<?php echo $val['num'];?>" maxlength="25" />
          </span><span class="del"><a nctype="del" href="javascript:void(0);" class="ncsc-btn"><i class="icon-trash"></i><?php echo $lang['nc_delete'];?></a></span> </div>
        <?php }?>
        <?php }?>
        <p><span><a href="javascript:void(0);" onclick="add_service('pre');" class="ncsc-btn ncsc-btn-acidblue mt10"><i class="icon-plus"></i><?php echo $lang['clic_callcenter_add_service'];?></a></span></p>
      </dd>
    </dl>
    <dl nctype="after" >
      <dt><?php echo $lang['clic_callcenter_aftersales_service'].$lang['nc_colon'];?></dt>
      <dd>
        <div class="ncs-message-title"><span class="name"><?php echo $lang['clic_callcenter_service_name'];?></span><span class="tool"><?php echo $lang['clic_callcenter_service_tool'];?></span><span class="number"><?php echo $lang['clic_callcenter_service_number'];?></span></div>
        <?php if(empty($output['clicinfo']['clic_aftersales'])){?>
        <div class="ncs-message-list"><span class="name tip" title="<?php echo $lang['clic_callcenter_name_title'];?>">
          <input type="text" class="text w60" value="<?php echo $lang['clic_callcenter_aftersales'];?>1" name="after[1][name]" maxlength="10" />
          </span><span class="tool tip" title="<?php echo $lang['clic_callcenter_tool_title'];?>">
          <select name="after[1][type]">
            <option value="0"><?php echo $lang['clic_callcenter_please_choose'];?></option>
            <option value="1">QQ</option>
            <option value="2"><?php echo $lang['clic_callcenter_wangwang'];?></option>
          </select>
          </span><span class="number tip" title="<?php echo $lang['clic_callcenter_number_title'];?>">
          <input type="text" class="text w180" name="after[1][num]" maxlength="25" />
          </span><span><a nctype="del" href="javascript:void(0);" class="ncsc-btn"><i class="icon-trash"></i><?php echo $lang['nc_delete'];?></a></span> </div>
        <?php }else{?>
        <?php foreach($output['clicinfo']['clic_aftersales'] as $key=>$val){?>
        <div class="ncs-message-list"><span class="name tip" title="<?php echo $lang['clic_callcenter_name_title'];?>">
          <input type="text" class="text w60" value="<?php echo $val['name'];?>" name="after[<?php echo $key;?>][name]" maxlength="10" />
          </span><span class="tool tip" title="<?php echo $lang['clic_callcenter_tool_title'];?>">
          <select name="after[<?php echo $key;?>][type]">
            <option value="1" <?php if($val['type'] == 1){?>selected="selected"<?php }?>>QQ</option>
            <option value="2" <?php if($val['type'] == 2){?>selected="selected"<?php }?>><?php echo $lang['clic_callcenter_wangwang'];?></option>
          </select>
          </span><span class="number tip" title="<?php echo $lang['clic_callcenter_number_title'];?>">
          <input type="text" class="text w180" name="after[<?php echo $key;?>][num]" maxlength="25" value="<?php echo $val['num'];?>" />
          </span><span class="del"><a nctype="del" href="javascript:void(0);" class="ncsc-btn"><i class="icon-trash"></i><?php echo $lang['nc_delete'];?></a></span> </div>
        <?php }?>
        <?php }?>
        <p><span><a href="javascript:void(0);" onclick="add_service('after');" class="ncsc-btn ncsc-btn-acidblue mt10"><i class="icon-plus"></i><?php echo $lang['clic_callcenter_add_service'];?></a></span></p>
      </dd>
    </dl>
    <dl >
      <dt><em class="pngFix"><?php echo $lang['clic_callcenter_working_time'].$lang['nc_colon'];?></em></dt>
      <dd>
        <div class="ncs-message-title"><span><?php echo $lang['clic_callcenter_working_time_title'];?></span></div>
        <div>
          <textarea name="working_time" class="textarea w500 h50"><?php echo $output['clicinfo']['clic_workingtime'];?></textarea>
        </div>
      </dd>
    </dl>
    <div class="bottom">
      <label class="submit-bappointment"><input type="submit" class="submit" value="<?php echo $lang['nc_common_button_submit'];?>" /></label>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script> 
<script>
$(function(){
//	$('input[type="submit"]').click(function(){
//		ajaxpost('callcenter_form', '', '', 'onerror');
//		return false;
//	});
	$('#callcenter_form').find('a[nctype="del"]').live('click', function(){
		$(this).parents('div:first').remove();
	});
	titleTip();
});
function add_service(param){
	if(param == 'pre'){
		var text = '<?php echo $lang['clic_callcenter_presales'];?>';
	}else if(param == 'after'){
		var text = '<?php echo $lang['clic_callcenter_aftersales'];?>';
	}
	obj = $('dl[nctype="'+param+'"]').children('dd').find('p');
	len = $('dl[nctype="'+param+'"]').children('dd').find('div').length;
	key = 'k'+len+Math.floor(Math.random()*100);
	$('<div class="ncs-message-list"></div>').append('<span class="name tip" title="<?php echo $lang['clic_callcenter_name_title'];?>"><input type="text" class="text w60" value="'+text+len+'" name="'+param+'['+key+'][name]" /></span>')
					.append('<span class="tool tip" title="<?php echo $lang['clic_callcenter_tool_title'];?>"><select name="'+param+'['+key+'][type]"><option class="" value="0"><?php echo $lang['clic_callcenter_please_choose'];?></option><option value="1">QQ</option><option value="2"><?php echo $lang['clic_callcenter_wangwang'];?></option></select></span>')
					.append('<span class="number tip" title="<?php echo $lang['clic_callcenter_number_title'];?>"><input class="text w180" type="text" name="'+param+'['+key+'][num]" /></span>')
					.append('<span class="del"><a nctype="del" href="javascript:void(0);" class="ncsc-btn"><i class="icon-trash"></i><?php echo $lang['nc_delete'];?></a></span>')
					.insertBefore(obj);
	titleTip();
}
function titleTip(){
	//title提示
	$('.tip').unbind().poshytip({
		className: 'tip-yellowsimple',
		showTimeout: 1,
		alignTo: 'target',
		alignX: 'center',
		alignY: 'top',
		offsetX: 5,
		offsetY: 0,
		allowTipHover: false
	});
}
</script>