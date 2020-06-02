<?php defined('InclinicNC') or exit('Access Invalid!');?>
<!--店铺基本信息 S-->

<div class="ncs-info">
  <div class="title">
    <h4><?php echo $output['clic_info']['clic_name']; ?></h4>
  </div>
  <div class="content">
    <!-- <dl class="all-rate">
      <dt>Overall  :</dt>
      <dd>
        <div class="rating"><span style="width: <?php echo $output['clic_info']['clic_credit_percent'];?>%"></span></div>
        <em><?php echo $output['clic_info']['clic_credit_average'];?></em>分</dd>
    </dl> -->
    <!-- <div class="detail-rate">
      <h5><strong><?php echo $lang['nc_dynamic_evaluation'];?></strong>Compared</h5>
      <ul>
        <?php  foreach ($output['clic_info']['clic_credit'] as $value) {?>
        <li> <?php echo $value['text'];?><span class="credit"><?php echo $value['credit'];?> 分</span> <span class="<?php echo $value['percent_class'];?>"><i></i><?php echo $value['percent_text'];?><em><?php echo $value['percent'];?></em></span> </li>
        <?php } ?>
      </ul>
    </div> -->
    <?php if(defined('CHAT_SITE_URL') || !empty($output['clic_info']['clic_qq']) || !empty($output['clic_info']['clic_ww'])){?>
    <dl class="messenger">
      <dt><?php echo $lang['nc_contact_way'];?>：</dt>
      <dd><span member_id="<?php echo $output['clic_info']['member_id'];?>"></span>
        <?php if(!empty($output['clic_info']['clic_qq'])){?>
        <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $output['clic_info']['clic_qq'];?>&site=qq&menu=yes" title="QQ: <?php echo $output['clic_info']['clic_qq'];?>"><img bappointment="0" src="http://wpa.qq.com/pa?p=2:<?php echo $output['clic_info']['clic_qq'];?>:52" style=" vertical-align: middle;"/></a>
        <?php }?>
        <?php if(!empty($output['clic_info']['clic_ww'])){?>
        <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&amp;uid=<?php echo $output['clic_info']['clic_ww'];?>&site=cntaobao&s=1&charset=<?php echo CHARSET;?>" ><img bappointment="0" src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo $output['clic_info']['clic_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" alt="<?php echo $lang['nc_message_me'];?>" style=" vertical-align: middle;"/></a>
        <?php }?>
      </dd>
    </dl>
    <?php } ?>
    <dl class="no-bappointment">
      <dt>Name：</dt>
      <dd><?php echo $output['clic_info']['clic_company_name'];?></dd>
    </dl>
    <dl>
      <dt><?php echo $lang['nc_srore_location'];?></dt>
      <dd><?php echo $output['clic_info']['area_info'];?></dd>
    </dl>
    <div class="goto"><a href="<?php echo urlclinic('show_clic', 'index', array('clic_id' => $output['clic_info']['clic_id']), $output['clic_info']['clic_domain']);?>" target="_blank">enter the clinic</a></div>
  </div>
</div>
<script>
$(function(){
	var clic_id = "<?php echo $output['clic_info']['clic_id']; ?>";
	var doctors_id = "<?php echo $_GET['doctors_id']; ?>";
	var act = "<?php echo trim($_GET['act']); ?>";
	var op  = "<?php echo trim($_GET['op']) != ''?trim($_GET['op']):'index'; ?>";
	$.getJSON("index.php?act=show_clic&op=ajax_flowstat_record",{clic_id:clic_id,doctors_id:doctors_id,act_param:act,op_param:op},function(result){
	});
});
</script>
