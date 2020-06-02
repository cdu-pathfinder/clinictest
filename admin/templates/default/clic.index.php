<?php defined('InclinicNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['clic'];?></h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['manage'];?></span></a></li>
        <li><a href="index.php?act=clic&op=clic_joinin" ><span><?php echo $lang['pending'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch">
  <input type="hidden" value="clic" name="act">
  <input type="hidden" value="clic" name="op">
  <table class="tb-type1 nobappointment search">
  <tbody>
    <tr><th><label><?php echo $lang['belongs_level'];?></label></th>
      <td><select name="grade_id">
          <option value=""><?php echo $lang['nc_please_choose'];?>...</option>
          <?php if(!empty($output['grade_list']) && is_array($output['grade_list'])){ ?>
          <?php foreach($output['grade_list'] as $k => $v){ ?>
          <option value="<?php echo $v['sg_id'];?>" <?php if($output['grade_id'] == $v['sg_id']){?>selected<?php }?>><?php echo $v['sg_name'];?></option>
          <?php } ?>
          <?php } ?>
        </select></td><th><label for="owner_and_name"><?php echo $lang['clic_user'];?></label></th>
      <td><input type="text" value="<?php echo $output['owner_and_name'];?>" name="owner_and_name" id="owner_and_name" class="txt"></td><td></td><th><label>店铺类型</label></th>
        <td>
            <select name="clic_type">
                <option value=""><?php echo $lang['nc_please_choose'];?>...</option>
                <?php if(!empty($output['clic_type']) && is_array($output['clic_type'])){ ?>
                <?php foreach($output['clic_type'] as $k => $v){ ?>
                <option value="<?php echo $k;?>" <?php if($_GET['clic_type'] == $k){?>selected<?php }?>><?php echo $v;?></option>
                <?php } ?>
                <?php } ?>
            </select>
        </td>
      <th><label for="clic_name"><?php echo $lang['clic_name'];?></label></th>
      <td><input type="text" value="<?php echo $output['clic_name'];?>" name="clic_name" id="clic_name" class="txt"></td>
        <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
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
          </ul></td>
      </tr>
    </tbody>
  </table>
  <form method="post" id="clic_form">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th><?php echo $lang['clic_name'];?></th>
          <th><?php echo $lang['clic_user_name'];?></th>
          <th>clinic owner account</th>
          <th class="align-center"><?php echo $lang['belongs_level'];?></th>
          <th class="align-center"><?php echo $lang['period_to'];?></th>
          <th class="align-center"><?php echo $lang['state'];?></th>
          <th class="align-center"><?php echo $lang['operation'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['clic_list']) && is_array($output['clic_list'])){ ?>
        <?php foreach($output['clic_list'] as $k => $v){ ?>
        <tr class="hover edit <?php echo getclicStateClassName($v);?>">
          <td>
              <a href="<?php echo urlclinic('show_clic','index', array('clic_id'=>$v['clic_id']));?>" target="_blank">
                <?php echo $v['clic_name'];?>
                <?php if($v['clic_id'] == DEFAULT_PLATFORM_clic_ID) {echo '(平台)';}?>
          	</a></td>
          <td><?php echo $v['member_name'];?></td>
          <td><?php echo $v['clinicer_name'];?></td>
          <td class="align-center"><?php echo $output['search_grade_list'][$v['grade_id']];?></td>
          <td class="nowarp align-center"><?php echo $v['clic_end_time']?date('Y-m-d', $v['clic_end_time']):$lang['no_limit'];?></td>
          <td class="align-center w72"><?php echo $v['clic_state']?$lang['open']:$lang['close'];?></td>
        <td class="align-center w120">
            <a href="index.php?act=clic&op=clic_joinin_detail&member_id=<?php echo $v['member_id'];?>">view</a>&nbsp;&nbsp;<a href="index.php?act=clic&op=clic_edit&clic_id=<?php echo $v['clic_id']?>"><?php echo $lang['nc_edit'];?></a>&nbsp;&nbsp;
                <?php if($v['clic_id'] != DEFAULT_PLATFORM_clic_ID) {?>
                <a href="index.php?act=clic&op=clic_bind_class&clic_id=<?php echo $v['clic_id']?>">category</a>
                <?php } ?>
            </td>
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
          <td></td>
          <td colspan="16">
            <div class="pagination"><?php echo $output['page'];?></div></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script>
$(function(){
    $('#ncsubmit').click(function(){
    	$('input[name="op"]').val('clic');$('#formSearch').submit();
    });
});
</script>
