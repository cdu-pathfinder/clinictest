<?php defined('InclinicNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['nc_domain_manage'];?></h3>
      <ul class="tab-base">
     	<li><a href="index.php?act=domain&op=clic_domain_setting"><span><?php echo $lang['nc_config'];?></span></a></li>
      	<li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_domain_clinic'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch">
  <input type="hidden" value="domain" name="act">
  <input type="hidden" value="clic_domain_list" name="op">
  <table class="tb-type1 nobappointment search">
  <tbody>
    <tr>
      <th><label for="clic_name"><?php echo $lang['clic_name'];?></label></th>
      <td><input type="text" value="<?php echo $_GET['clic_name'];?>" name="clic_name" id="clic_name" class="txt"></td>
      <th><label for="owner_and_name"><?php echo $lang['clic_domain'];?></label></th>
      <td><input type="text" value="<?php echo $_GET['clic_domain'];?>" name="clic_domain" id="clic_domain" class="txt"></td>
      <td><a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a></td>
    </tr></tbody>
  </table>
  </form>
  <form method="post" id="clic_form">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th class="w24"></th>
          <th class="align-center"><?php echo $lang['clic_domain'];?></th>
          <th class="align-center"><?php echo $lang['clic_name'];?></th>
          <th class="align-center"><?php echo $lang['clic_domain_times'];?></th>
          <th class="align-center"><?php echo $lang['clic_user_name'];?></th>
          <th class="align-center"><?php echo $lang['operation'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['clic_list']) && is_array($output['clic_list'])){ ?>
        <?php foreach($output['clic_list'] as $k => $v){ ?>
        <tr class="hover edit">
          <td></td>
          <td class="align-center"><?php echo $v['clic_domain'];?></td>
          <td class="align-center"><?php echo $v['clic_name'];?>&nbsp;</td>
          <td class="align-center"><?php echo $v['clic_domain_times'];?></td>
          <td class="align-center"><?php echo $v['member_name'];?></td>
          <td class="w150 align-center"><a href="index.php?act=domain&op=clic_domain_edit&clic_id=<?php echo $v['clic_id']?>"><?php echo $lang['nc_edit'];?></a>
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
          <td colspan="16">
            <div class="pagination"><?php echo $output['page'];?></div></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
