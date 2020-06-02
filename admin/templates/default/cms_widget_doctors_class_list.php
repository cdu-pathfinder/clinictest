<?php defined('InclinicNC') or exit('Access Invalid!');?>
<dl>
    <dt>
    <i title="<?php echo $lang['cms_del'];?>"></i>
    <a href="<?php echo clinic_SITE_URL.DS;?>index.php?act=search&cate_id=<?php echo $output['gc_parent']['gc_id'];?>" target="_blank"><?php echo $output['gc_parent']['gc_name'];?></a>
    </dt>
    <div class="clear"></div>
    <?php if(!empty($output['doctors_class']) && is_array($output['doctors_class'])){ ?>
    <?php foreach($output['doctors_class'] as $k => $v){ ?>
    <dd> 
    <i title="<?php echo $lang['cms_del'];?>"></i>
    <a href="<?php echo clinic_SITE_URL.DS;?>index.php?act=search&cate_id=<?php echo $v['gc_id'];?>" target="_blank"><?php echo $v['gc_name'];?></a>
    </dd>
    <?php } ?>
    <?php } ?>
</dl>
