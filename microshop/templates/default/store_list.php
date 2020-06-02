<?php defined('InclinicNC') or exit('Access Invalid!');?>
<!-- 引入幻灯片JS --> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.flexslider-min.js"></script> 
<div class="flexslider">
    <ul class="slides">
    <?php if(!empty($output['clic_list_adv_list']) && is_array($output['clic_list_adv_list'])) {?>
<!-- 绑定幻灯片事件 --> 
<script type="text/javascript">
    $(document).ready(function(){
        $('.flexslider').flexslider();
    });
</script>
    <?php foreach($output['clic_list_adv_list'] as $key=>$value) {?>
    <li>
    <a href="<?php echo $value['adv_url'];?>">
        <img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_MICROclinic.DS.'adv'.DS.$value['adv_image'];?>"/> 
    </a>
    </li>
    <?php } ?>
    <?php } else { ?>
        <img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_MICROclinic.DS.'default_clic_list_banner.jpg';?>"/> 
    <?php } ?>
</ul>
</div>
<?php
require("widget_clic_list.php");
?>
