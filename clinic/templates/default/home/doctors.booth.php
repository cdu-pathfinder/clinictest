<?php defined('InclinicNC') or exit('Access Invalid!');?>
<div class="title">
<h3>推广商品</h3></div>
<div class="content" nc_type="current_display_mode">
  <?php if(!empty($output['doctors_list']) && is_array($output['doctors_list'])){?>
  <ul class="nch-booth-list squares">
    <?php foreach($output['doctors_list'] as $value){?>
    <li nctype_doctors="<?php echo $value['doctors_id'];?>" nctype_clic="<?php echo $value['clic_id'];?>">
        <div class="doctors-pic"><a href="<?php echo urlclinic('doctors','index',array('doctors_id'=>$value['doctors_id']));?>" target="_blank" title="<?php echo $value['doctors_name'];?>"><img src="<?php echo thumb($value, 240);?>" title="<?php echo $value['doctors_name'];?>" alt="<?php echo $value['doctors_name'];?>" /></a> </div>
        <?php if ($value['group_flag']) {?>
        <div class="doctors-promotion"><span>团购商品</span></div>
        <?php } elseif ($value['xianshi_flag']) {?>
        <div class="doctors-promotion"><span>限时折扣</span></div>
        <?php }?>
          <div class="doctors-name"><a href="<?php echo urlclinic('doctors','index',array('doctors_id'=>$value['doctors_id']));?>" target="_blank" title="<?php echo $value['doctors_jingle'];?>"><?php echo $value['doctors_name'];?></a></div>
          <div class="doctors-price" title="商品价格<?php echo $lang['nc_colon'].$lang['currency'].$value['doctors_price'];?>"><?php echo $lang['currency'];?><?php echo $value['doctors_price'];?></div>
    </li>
    <?php }?>
  </ul>
  <?php }?>
</div>
