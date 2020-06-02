<?php defined('InclinicNC') or exit('Access Invalid!');?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<div class="container wrapper">
  <div class="sidebar">
    <h3>Information submission</h3>
    <ul>
      <li class="<?php echo $output['sub_step'] == 'step1' ? 'current' : '';?>"><i></i>Clinic information</li>
    <li class="<?php echo $output['sub_step'] == 'step2' ? 'current' : '';?>"><i></i>Financial information</li>
    <li class="<?php echo $output['sub_step'] == 'step3' ? 'current' : '';?>"><i></i>Admin information</li>
    <li class="<?php echo $output['sub_step'] == 'step4' ? 'current' : '';?>"><i></i>Application status</li>
    </ul>
  </div>
  <div class="main">
<?php require('clic_joinin_apply.'.$output['sub_step'].'.php'); ?>
</div>
</div>
