<?php defined('InclinicNC') or exit('Access Invalid!');?>
<!--店铺街推荐排行-->
<?php $clic_list_count = count($output['clic_list']);?>

<div class="title-bar">
  <h3><?php echo $lang['nc_microclinic_clic'];?></h3>
  <a href="<?php echo MICROclinic_SITE_URL.DS;?>index.php?act=clic" class="more" target="_blank"><?php echo $lang['nc_more'];?></a> </div>
<div class="contnet-box">
  <ol nc_type="index_clic" class="microclinic-clic-list">
      <?php $i = 1;?>
    <?php if(!empty($output['clic_list']) && is_array($output['clic_list'])) {?>
    <?php foreach($output['clic_list'] as $key=>$value) {?>
    <li class="overall" style="display:none;"><i><?php echo $i;?></i>
      <dl class="clic-intro">
        <dt><?php echo $value['clic_name'];?></dt>
        <dd><?php echo $lang['microclinic_text_doctors'];?><?php echo $lang['nc_colon'];?><em><?php echo $value['doctors_count'];?></em><?php echo $lang['piece'];?></dd>
        <dd><a href="<?php echo MICROclinic_SITE_URL.DS;?>index.php?act=clic&op=detail&clic_id=<?php echo $value['microclinic_clic_id'];?>" target="_blank"><?php echo $lang['micro_api_clic_info'];?></a></dd>
      </dl>
    </li>
    <li class="simple"><i><?php echo $i++;?></i><a href=""><?php echo $value['clic_name'];?></a></li>
    <?php } ?>
    <?php } ?>
  </ol>
</div>
