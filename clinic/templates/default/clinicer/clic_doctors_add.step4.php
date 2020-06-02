
<ul class="add-doctors-step">
  <li><i class="icon icon-list-alt"></i>
    <h6>STIP.1</h6>
    <h2>select classs</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li><i class="icon icon-edit"></i>
    <h6>STIP.2</h6>
    <h2>doctor details</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li><i class="icon icon-camera-retro "></i>
    <h6>STIP.3</h6>
    <h2>profile picture</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li class="current"><i class="icon icon-ok-circle"></i>
    <h6>STIP.4</h6>
    <h2>completed</h2>
  </li>
</ul>
<div class="alert alert-block hr32">
  <h2><i class="icon-ok-circle mr10"></i><?php echo $lang['clic_doctors_step3_doctors_release_success'];?>&nbsp;&nbsp;<?php if (C('doctors_verify')) {?>Wait for the administrator review the doctor!<?php }?></h2>
  <div class="hr16"></div>
  <strong>
    <a class="ml30 mr30" href="<?php echo urlclinic('doctors', 'index', array('doctors_id'=>$output['doctors_id']));?>"><?php echo $lang['clic_doctors_step3_viewed_doc'];?>&gt;&gt;</a>
    <a href="<?php echo urlclinic('clic_doctors_online', 'edit_doctors', array('commonid' => $_GET['commonid'], 'ref_url' => urlclinic('clic_doctors_online', 'index')));?>"><?php echo $lang['clic_doctors_step3_edit_doc'];?>&gt;&gt;</a>
  </strong>
  <div class="hr16"></div>
  <h4 class="ml10"><?php echo $lang['clic_doctors_step3_more_actions'];?></h4>
  <ul class="ml30">
    <li>1. <?php echo $lang['clic_doctors_step3_continue'];?> &quot; <a href="<?php echo urlclinic('clic_doctors_add', 'index');?>"><?php echo $lang['clic_doctors_step3_release_new_doctors'];?></a>&quot;</li>
    <li>2. <?php echo $lang['clic_doctors_step3_access'];?> &quot; <?php echo $lang['nc_user_center'];?>&quot; <?php echo $lang['clic_doctors_step3_manage'];?> &quot;<a href="<?php echo urlclinic('clic_doctors_online', 'index');?>"><?php echo $lang['nc_member_path_doctors_list'];?></a>&quot;</li>
    <!-- li>4. <?php echo $lang['clic_doctors_step3_choose_add'];?> &quot; <a href="index.php?act=clic_groupbuy&op=groupbuy_add"><?php echo $lang['clic_doctors_step3_groupbuy_activity'];?></a> &quot;</li -->
    <!-- <li>3. <?php echo $lang['clic_doctors_step3_participation'];?> &quot; <a href="<?php echo urlclinic('clic_activity', 'clic_activity');?>"><?php echo $lang['clic_doctors_step3_special_activities'];?></a> &quot;</li> -->
  </ul>
</div>
