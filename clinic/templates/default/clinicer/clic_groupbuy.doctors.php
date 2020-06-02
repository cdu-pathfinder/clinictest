<?php defined('InclinicNC') or exit('Access Invalid!');?>
<?php if(!empty($output['doctors_list']) && is_array($output['doctors_list'])){?>
<ul class="doctors-list" style="width:760px;">
  <?php foreach($output['doctors_list'] as $key=>$val){?>
  <li>
    <div class="doctors-thumb"><img src="<?php echo thumb($val, 240);?>"/></div>
    <dl class="doctors-info">
      <dt><?php echo $val['doctors_name'];?></dt>
      <dd>销售价：<?php echo $lang['currency'].$val['doctors_price'];?>
    </dl>
    <a nctype="btn_add_groupbuy_doctors" data-doctors-commonid="<?php echo $val['doctors_commonid'];?>" href="javascript:void(0);" class="ncsc-btn-mini ncsc-btn-green"><i class="icon-ok-circle "></i>选择为团购商品</a> </li>
  <?php } ?>
</ul>
<div class="pagination"><?php echo $output['show_page']; ?></div>
<?php } else { ?>
<div><?php echo $lang['no_record'];?></div>
<?php } ?>
