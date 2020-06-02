<?php defined('InclinicNC') or exit('Access Invalid!');?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/qtip/jquery.qtip.min.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/qtip/jquery.qtip.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
$(document).ready(function(){
    //图片延迟加载
    $("img.lazy").microclinic_lazyload();

    //喜欢
    $("[nc_type=microclinic_like]").microclinic_like({type:'doctors'});

    $('a[nctype="mcard"]').membershipCard({type:"microclinic"});
});
</script>

<div class="commend-doctors">
  <div class="commend-doctors-info">
    <div class="user">
      <div class="user-face"><span class="thumb size60"><i></i><a href="<?php echo MICROclinic_SITE_URL;?>/index.php?act=home&member_id=<?php echo $output['detail']['commend_member_id'];?>" target="_blank"> <img src="<?php echo getMemberAvatar($output['detail']['member_avatar']);?>" alt="<?php echo $output['detail']['member_name'];?>" onload="javascript:DrawImage(this,60,60);" /> </a></span> </div>
      <dl>
        <dt><a href="<?php echo MICROclinic_SITE_URL;?>/index.php?act=home&member_id=<?php echo $output['detail']['commend_member_id'];?>" target="_blank" nctype="mcard" data-param="{'id':<?php echo $output['detail']['member_id'];?>}"> <?php echo $output['detail']['member_name'];?></a><?php echo $lang['microclinic_text_commend_doctors'];?><span class="add-time"><?php echo date('Y-m-d',$output['detail']['commend_time']);?></span></dt>
        <dd><i></i>
          <p><?php echo $output['detail']['commend_message'];?><i></i></p>
        </dd>
      </dl>
      <div class="arrow"></div>
    </div>
    <div class="doctors">
      <h3><a href="<?php echo urlclinic('doctors', 'index', array('doctors_id'=>$output['detail']['commend_doctors_id']));?>" target="_blank" title="<?php echo $output['detail']['commend_doctors_name'];?>"> <?php echo $output['detail']['commend_doctors_name'];?> </a></h3>
      <div class="handle-bar">
        <div class="buy-btn"><a href="javascript:void(0)"><span><?php echo $lang['microclinic_text_buy'];?></span><i></i></a>
          <div class="buy-info">
            <dl>
              <dt class="doctors-pic"><img src="<?php echo cthumb($output['detail']['commend_doctors_image'], 60,$output['detail']['commend_doctors_clic_id']);?>" alt="<?php echo $output['detail']['commend_doctors_name'];?>" /></dt>
              <dd><a href="<?php echo urlclinic('doctors', 'index', array('doctors_id'=>$output['detail']['commend_doctors_id']));?>" target="_blank" title="<?php echo $output['detail']['commend_doctors_name'];?>" class="doctors-name"><?php echo $output['detail']['commend_doctors_name'];?></a>
                <p class="doctors-price"><?php echo $lang['currency'].$output['detail']['commend_doctors_price'];?></p>
              </dd>
            </dl>
          </div>
        </div>
        <div class="buttons"><a nc_type="microclinic_like" like_id="<?php echo $output['detail']['commend_id'];?>" href="javascript:void(0)" class="like" title="<?php echo $lang['microclinic_text_like'];?>"><i></i><em><?php echo $output['detail']['like_count']<=999?$output['detail']['like_count']:'999+';?></em></a><a id="btn_sns_share" href="javascript:;" class="share" title="<?php echo $lang['microclinic_text_share'];?>"><i></i><?php echo $lang['microclinic_text_share'];?><em></em></a></div>
      </div>
      <div class="pic">
        <?php if(!empty($output['doctors_image_list'])) { ?>
        <?php foreach($output['doctors_image_list'] as $val) { ?>
        <?php if(!empty($val)) { ?>
        <img class="lazy" src="<?php echo MICROclinic_TEMPLATES_URL;?>/images/loading.gif" data-src="<?php echo cthumb($val['doctors_image'], 1280);?>" title="<?php echo $output['detail']['commend_doctors_name'];?>" alt="<?php echo $output['detail']['commend_doctors_name'];?>" />
        <?php } ?>
        <?php } ?>
        <?php } ?>
      </div>
      <?php require('widget_comment.php');?>
    </div>
    <div class="clear">&nbsp;</div>
  </div>
  <div class="commend-doctors-sidebar">
    <?php require('widget_sidebar.php');?>
  </div>
  <div class="clear">&nbsp;</div>
</div>
<div class="microclinic-clic-title">
  <h3><?php echo $lang['microclinic_text_doctors_clic'];?></h3>
</div>
<div class="microclinic-clic-list">
  <div class="top">
    <h2><a href="<?php echo urlclinic('show_clic', 'index', array('clic_id' => $output['clic_info']['clic_id']), $output['clic_info']['clic_domain']);?>" target="_blank"><?php echo $output['clic_info']['clic_name'];?></a></h2>
    <span class="doctors-count"><strong><?php echo $output['clic_info']['doctors_count'];?></strong><?php echo $lang['microclinic_text_jian'].$lang['microclinic_text_doctors'];?></span> </div>
  <div>
    <div class="microclinic-clic-info">
      <dl>
        <dt><?php echo $lang['microclinic_text_clic_member_name'].$lang['nc_colon'];?></dt>
        <dd><?php echo $output['clic_info']['member_name'];?></dd>
      </dl>
      <dl>
        <dt><?php echo $lang['microclinic_text_clic_area'].$lang['nc_colon'];?></dt>
        <dd><?php echo $output['clic_info']['area_info'];?></dd>
      </dl>
      <dl>
        <dt><?php echo $lang['microclinic_text_clic_zy'].$lang['nc_colon'];?></dt>
        <dd><?php echo $output['clic_info']['clic_zy'];?></dd>
      </dl>
      <dl>
        <dt><?php echo $lang['microclinic_text_clic_favorites'].$lang['nc_colon'];?></dt>
        <dd><strong nctype="clic_collect"><?php echo $output['clic_info']['clic_collect']?></strong><?php echo $lang['nc_person'];?><?php echo $lang['nc_collect'];?></dd>
      </dl>
    </div>
    <div class="microclinic-clic-info-image">
      <ul>
        <?php if(!empty($output['clic_info']['hot_sales_list']) && is_array($output['clic_info']['hot_sales_list'])) { ?>
        <?php $i = 1;?>
        <?php foreach($output['clic_info']['hot_sales_list'] as $k=>$v){?>
        <li style="background-image: url(<?php echo thumb($v, 240);?>)" title="<?php echo $v['doctors_name'];?>"><a href="<?php echo urlclinic('doctors', 'index', array('doctors_id'=>$v['doctors_id']));?>" target="_blank">&nbsp;</a> <em><?php echo $v['doctors_clic_price'];?></em> </li>
        <?php if($i >=5) break; ?>
        <?php $i++; ?>
        <?php }?>
        <?php }?>
      </ul>
    </div>
  </div>
</div>
<?php require('widget_sns_share.php');?>
