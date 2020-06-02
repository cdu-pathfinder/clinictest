<?php defined('InclinicNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['clic'];?></h3>
      <ul class="tab-base">
        <li><a href="index.php?act=clic&op=clic"><span><?php echo $lang['manage'];?></span></a></li>
        <li><a href="index.php?act=clic&op=clic_add" ><span><?php echo $lang['nc_new'];?></span></a></li>
        <li><a href="index.php?act=clic&op=clic_audit" ><span><?php echo $lang['pending'];?></span></a></li>
        <li><a href="index.php?act=clic&op=clic_auth"  class="current"><span><?php echo $lang['clic_auth_verify'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch">
  <input type="hidden" value="clic" name="act">
  <input type="hidden" value="clic" name="op">
  <table class="tb-type1 nobappointment search">
  <tbody>
    <tr>
      <th><label for="clic_name"><?php echo $lang['clic_name'];?></label></th>
      <td><input type="text" value="<?php echo $output['clic_name'];?>" name="clic_name" id="clic_name" class="txt"></td>
      <th><label for="owner_and_name"><?php echo $lang['clic_user'];?></label></th>
      <td><input type="text" value="<?php echo $output['owner_and_name'];?>" name="owner_and_name" id="owner_and_name" class="txt"></td>
      <td><a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
        <?php if($output['owner_and_name'] != '' or $output['clic_name'] != '' or $output['grade_id'] != ''){?>
        <a href="index.php?act=clic&op=clic" class="btns " title="<?php echo $lang['nc_cancel_search'];?>"><span><?php echo $lang['nc_cancel_search'];?></span></a>
        <?php }?></td>
    </tr></tbody>
  </table>
  </form>
   <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title">
            <h5><?php echo $lang['nc_prompts'];?></h5>
            <span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td><ul>
            <li><?php echo $lang['clic_help1'];?></li>
            <li><?php echo $lang['clic_help2'];?></li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <form method="post" id="clic_form">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th class="w24"></th>
          <th><?php echo $lang['clic_name'];?></th>
          <th><?php echo $lang['clic_user_name'];?></th>
          <th class="align-center"><?php echo $lang['period_to'];?></th>
          <th class="align-center"><?php echo $lang['clic_auth'];?></th>
          <th class="align-center"><?php echo $lang['member_auth'];?></th>
          <th class="align-center"><?php echo $lang['operation'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['clic_list']) && is_array($output['clic_list'])){ ?>
        <?php foreach($output['clic_list'] as $k => $v){ ?>
        <tr class="hover edit">
          <td><input type="checkbox" value="<?php echo $v['clic_id'];?>" name="del_id[]" class="checkitem"></td>
          <td>
              <a href="<?php echo urlclinic('show_clic','index', array('clic_id'=>$v['clic_id']));?>" target="_blank">
          	<?php echo $v['clic_name'];?>
          	</a>
          	&nbsp;
            <?php if($v['name_auth'] == 2 || $v['clic_auth'] == 2){?>
            (<?php echo $lang['authing'];?>)
            <?php }?></td>
          <td><?php echo $v['member_name'];?></td>
          <td class="nowarp align-center"><?php echo $v['clic_end_time'];?></td>
          <td class="align-center"><?php echo $v['clic_authS'];?></td>
          <td class="align-center"><?php echo $v['name_authS'];?></td>
          <td class="align-center"><a href="index.php?act=clic&op=clic_edit&clic_id=<?php echo $v['clic_id']?>&type=auth"><?php echo $lang['clic_verify'];?></a></td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td colspan="15"><?php echo $lang['nc_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td><input type="checkbox" class="checkall" id="checkallBottom"></td>
          <td colspan="16"><label for="checkallBottom"><?php echo $lang['nc_select_all']; ?></label>
            &nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn" onclick="batchAuth();"><span><?php echo $lang['clic_pass_auth'];?></span></a>
            <div class="pagination"><?php echo $output['page'];?></div></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script> 
<script>
function batchAuth(){
	var id='';
	$('input[type=checkbox]:checked').each(function(){
		if(!isNaN($(this).val())){
			id += $(this).val()+'|';
		}
	});
	if(id == ''){
		alert('<?php echo $lang['please_sel_edit_clic'];?>');
		return false;
	}
	location.href='index.php?act=clic&op=clic_batch_auth&id='+id;
	return true;
}
</script> 
