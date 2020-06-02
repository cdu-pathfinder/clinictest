<?php defined('InclinicNC') or exit('Access Invalid!');?>
<script type="text/javascript">
$(document).ready(function(){
	$("#gc-id1").show();
});
</script>

<div class="commend-class">
  <ul class="commend-class-root pngFix">
    <?php if(!empty($output['doctors_class_root']) && is_array($output['doctors_class_root'])) {?>
    <?php foreach($output['doctors_class_root'] as $key=>$val) {?>
    <li <?php if($output['doctors_class_root_id'] == $val['class_id'])  {echo "class='selected pngFix'";} else {echo "class='pngFix'";}?>>
   <a href="index.php?act=doctors&doctors_class_root_id=<?php echo $val['class_id'];?>" class="pngFix">
      <?php if(empty($val['class_image'])) $val['class_image'] = 'default_doctors_class_image.gif' ?>
      <div class="picture"><img src="<?php echo MICROclinic_IMG_URL.DS.$val['class_image'];?>" class="pngFix" alt="" onload="javascript:DrawImage(this,30,30);"/></div>
      <h3><?php echo $val['class_name'];?></h3>
      </a> </li>
    <?php } ?>
    <?php } ?>
    <div class="clear"></div>
  </ul>
  <ul class="commend-class-menu">
    <?php if(!empty($output['doctors_class_menu']) && is_array($output['doctors_class_menu'])) {?>
    <?php foreach($output['doctors_class_menu'] as $key=>$val) {?>
    <li>
      <div class="commend-class-menu-img"><span class="thumb size60"><i></i>
        <?php if(empty($val['class_image'])) $val['class_image'] = 'default_doctors_class_image.gif' ?>
        <a <?php if($output['doctors_class_menu_id'] == $val['class_id']) echo "class='selected'";?> href="index.php?act=doctors&doctors_class_root_id=<?php echo $output['doctors_class_root_id'];?>&doctors_class_menu_id=<?php echo $val['class_id'];?>"> <img src="<?php echo MICROclinic_IMG_URL.DS.$val['class_image'];?>" alt=""  onload="javascript:DrawImage(this,60,60);"/> </a></span> </div>
      <div class="commend-class-menu-item">
        <dt><a <?php if($output['doctors_class_menu_id'] == $val['class_id'] && empty($_GET['keyword'])) echo "class='selected'";?> href="index.php?act=doctors&doctors_class_root_id=<?php echo $output['doctors_class_root_id'];?>&doctors_class_menu_id=<?php echo $val['class_id'];?>"><?php echo $val['class_name'];?></a></dt>
        <?php if(!empty($val['class_keyword'])) {?>
        <?php $doctors_class_keyword_array = explode(',',$val['class_keyword']);?>
        <span class="cover ">&nbsp;</span>
        <?php foreach($doctors_class_keyword_array as $key1=>$val1) {?>
        <dd><a <?php if($_GET['keyword'] == ltrim($val1,'*')) { echo "class='selected'";} elseif(substr($val1,0,1) == '*') { echo "class='highlight'";}?> href="index.php?act=doctors&doctors_class_root_id=<?php echo $output['doctors_class_root_id'];?>&doctors_class_menu_id=<?php echo $val['class_id'];?>&keyword=<?php echo ltrim($val1,'*');?>"><?php echo ltrim($val1,'*');?></a></dd>
        <?php } ?>
        <?php } ?>
      </div>
    </li>
    <?php } ?>
    <?php } ?>
    <div class="clear"></div>
  </ul>
</div>
<!-- 排序 -->
<?php if(!empty($output['list']) && is_array($output['list'])) {?>
<div class="microclinic-appointment"><span><?php echo $lang['microclinic_text_appointment'].$lang['nc_colon'];?></span>
  <ul>
    <li class="l"><a <?php if($_GET['appointment'] == 'new' || empty($_GET['appointment'])) echo "class='selected'";?> href="index.php?act=doctors&doctors_class_root_id=<?php echo $output['doctors_class_root_id'];?>&doctors_class_menu_id=<?php echo $output['doctors_class_menu_id'];?>&appointment=new"><?php echo $lang['microclinic_text_new'];?></a></li>
    <li class="r"><a <?php if($_GET['appointment'] == 'hot') echo "class='selected'";?> href="index.php?act=doctors&doctors_class_root_id=<?php echo $output['doctors_class_root_id'];?>&doctors_class_menu_id=<?php echo $output['doctors_class_menu_id'];?>&appointment=hot"><?php echo $lang['microclinic_text_hot'];?></a></li>
  </ul>
</div>
<?php } ?>
<?php require('widget_doctors_list.php');






