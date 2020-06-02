<?php defined('InclinicNC') or exit('Access Invalid!');?>
<?php if(!empty($output['list']) && is_array($output['list'])) {?>
<script type="text/javascript">
$(document).ready(function(){
    $("[nc_type=microclinic_like]").microclinic_like({type:'clic'});
});
</script>
<?php foreach($output['list'] as $key=>$value) {?>

<div class="microclinic-clic-list">
  <?php if($output['owner_flag'] === TRUE){ ?>
  <?php if($_GET['op'] == 'like_list') { ?>
  <!-- 喜欢删除按钮 -->
  <div class="del"><a nc_type="like_drop" like_id="<?php echo $output['like_clic_list'][$value['clic_id']]['like_id'];?>" href="javascript:void(0)" title="<?php echo $lang['nc_delete'];?>">&nbsp;</a></div>
  <?php } ?>
  <?php } ?>
  <div class="top"><span class="doctors-count"><strong><?php echo $value['doctors_count'];?></strong><?php echo $lang['microclinic_text_jian'].$lang['microclinic_text_doctors'];?></span>
    <h2><a href="<?php echo MICROclinic_SITE_URL.'/index.php?act=clic&op=detail&clic_id='.$value['microclinic_clic_id'];?>"><?php echo $value['clic_name'];?></a></h2>
  </div>
  <div style="zoom:1;">
    <div class="microclinic-clic-info">
      <dl>
        <dt><?php echo $lang['microclinic_text_clic_member_name'];?><?php echo $lang['nc_colon'];?></dt>
        <dd><?php echo $value['member_name'];?></dd>
      </dl>
      <dl>
        <dt><?php echo $lang['microclinic_text_clic_area'];?><?php echo $lang['nc_colon'];?></dt>
        <dd><?php echo $value['area_info'];?></dd>
      </dl>
      <dl>
        <dt><?php echo $lang['microclinic_text_clic_zy'];?><?php echo $lang['nc_colon'];?></dt>
        <dd><?php echo $value['clic_zy'];?></dd>
      </dl>
      <dl>
        <dt><?php echo $lang['microclinic_text_clic_favorites'];?><?php echo $lang['nc_colon'];?></dt>
        <dd><strong nctype="clic_collect"><?php echo $value['clic_collect']?></strong><?php echo $lang['nc_person'];?><?php echo $lang['nc_collect'];?></dd>
      </dl>
      <div class="handle"><span class="like-btn"><a nc_type="microclinic_like" like_id="<?php echo $value['microclinic_clic_id'];?>" href="javascript:void(0)"><i class="pngFix"></i><span><?php echo $lang['microclinic_text_like'];?></span><em><?php echo $value['like_count']<=999?$value['like_count']:'999+';?></em></a></span> <span class="comment"><a href="<?php echo MICROclinic_SITE_URL.'/index.php?act=clic&op=detail&clic_id='.$value['microclinic_clic_id'];?>"><i class="pngFix" title="<?php echo $lang['microclinic_text_comment'];?>">&nbsp;</i><em><?php echo $value['comment_count']<=999?$value['comment_count']:'999+';?></em></a></span> </div>
    </div>
    <?php if(!empty($value['hot_sales_list']) && is_array($value['hot_sales_list'])) { ?>
    <div class="microclinic-clic-info-image">
      <ul>
        <?php $i = 1;?>
        <?php foreach($value['hot_sales_list'] as $k=>$v){?>
        <li style="background-image: url(<?php echo thumb($v, 240);?>)" title="<?php echo $v['doctors_name'];?>"><a href="<?php echo urlclinic('doctors', 'index', array('doctors_id'=>$v['doctors_id']));?>" target="_blank">&nbsp;</a> <em><?php echo $v['doctors_clic_price'];?></em> </li>
        <?php if($i >=5) break; ?>
        <?php $i++; ?>
        <?php }?>
      </ul>
    </div>
    <?php } else {?>
    <div class="no-content">
        <p><?php echo $lang['microclinic_clic_commend_doctors_none'];?></p>
    </div>
    <?php }?>
</div>
</div>
<?php } ?>
<div class="pagination"> <?php echo $output['show_page'];?> </div>
<?php } else { ?>
<?php if($_GET['op'] == 'like_list') { ?>
<div class="no-content">
<i class="clic">&nbsp;</i>
<?php if($output['owner_flag'] === TRUE) { ?>
<p><?php echo $lang['microclinic_clic_like_list_none_owner'];?></p>
<?php } else { ?>
<p><?php echo $lang['nc_quote1'];?><?php echo $output['member_info']['member_name'];?><?php echo $lang['nc_quote2'];?><?php echo $lang['microclinic_clic_like_list_none'];?></p>
<?php } ?>
<?php } else { ?>
<div class="no-content">
<i class="clic">&nbsp;</i>
<p><?php echo $lang['microclinic_clic_list_none'];?></p>
<?php } ?>
<?php } ?>
