<?php defined('InclinicNC') or exit('Access Invalid!');?>
<?php if(!empty($output['doctors_list']) && is_array($output['doctors_list'])){ ?>

<div class="doctors-select-box">
  <div class="arrow"></div>
  <ul id="doctors_search_list" class="doctors-search-list">
    <?php foreach($output['doctors_list'] as $value){ ?>
    <?php $doctors_info = array();?>
    <?php $doctors_info['url'] = getdoctorsUrl($value['doctors_id']);?>
    <?php $doctors_info['title'] = $value['doctors_name'];?>
    <?php $doctors_info['image'] = thumb($value, 240);?>
    <?php $doctors_info['price'] = $value['doctors_clic_price'];?>
    <?php $doctors_info['type'] = 'clic';?>
    <li nctype="btn_doctors_select" doctors_url="<?php echo $doctors_info['url'];?>" doctors_title="<?php echo $doctors_info['title'];?>" doctors_image="<?php echo $doctors_info['image'];?>" doctors_price="<?php echo $doctors_info['price'];?>" doctors_type="<?php echo $doctors_info['type'];?>">
      <dl>
        <dt class="name"><a href="<?php echo $doctors_info['url'];?>" target="_blank"> <?php echo $doctors_info['title'];?> </a></dt>
        <dd class="image"><img title="<?php echo $doctors_info['title'];?>" src="<?php echo $doctors_info['image'];?>" /></dd>
        <dd class="price"><?php echo $lang['nc_common_price'];?><?php echo $lang['nc_colon'];?><em><?php echo $doctors_info['price'];?></em></dd>
      </dl>
      <i><?php echo $lang['api_doctors_add'];?></i></li>
    <?php } ?>
  </ul>
  <div class="pagination"><?php echo $output['show_page'];?></div>
</div>
<?php }else { ?>
<div class="no-record"><?php echo $lang['no_record'];?></div>
<?php } ?>
