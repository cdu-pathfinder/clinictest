<?php defined('InclinicNC') or exit('Access Invalid!');?>
<script type="text/javascript">
$(document).ready(function(){

    $(".btn_commend_dialog").click(function(){
        $("#commend_dialog_doctors_image").attr('src',$(this).attr("doctors_image"));
        $("#commend_dialog_doctors_image").attr('alt',$(this).attr("doctors_name"));
        $("#commend_doctors_id").val($(this).attr("doctors_id"));
        $("#commend_dialog_doctors_name").html($(this).attr("doctors_name"));
        $("#commend_dialog_doctors_price").html($(this).attr("doctors_price"));
        $("#div_commend_doctors").microclinic_form_show({width:480});
    });

    $("#div_commend_doctors").microclinic_form({title:'<?php echo $lang['microclinic_text_commend'].$lang['microclinic_text_doctors'];?>'});

    $("#commend_message").microclinic_publish({
        button_item:'#btn_publish',
        allow_null:'true'
    },function(){
        $("#div_commend_doctors").hide();
        ajaxpost('add_form', '', '', 'onerror'); 
    });

});
</script>

<div class="all-layout-box">
  <?php if($output['doctors_type'] == 'buy') { ?>
  <h1><?php echo $lang['microclinic_doctors_buy'];?></h1>
  <?php } else { ?>
  <h1><?php echo $lang['microclinic_doctors_favorite'];?></h1>
  <?php } ?>
  <?php if(!empty($output['list']) && is_array($output['list'])) {?>
  <div class="publish-doctors-list">
    <ul>
      <?php foreach($output['list'] as $key=>$val) {?>
      <li class="<?php echo in_array($val['doctors_id'],$output['commend_doctors_array'])?'selected':'select' ?>">
        <div class="picture"><span class="thumb size210"><i></i><a href="<?php echo urlclinic('doctors', 'index',array('doctors_id'=>$val['doctors_id']));?>" target="_blank"> <img src="<?php echo thumb($val, 240);?>" onload="javascript:DrawImage(this,210,210);" alt="<?php echo $val['doctors_name'];?>" title="<?php echo $val['doctors_name'];?>" /> </a></span></div>
        <dl>
          <dd class="fl"><?php echo $lang['currency'].ncPriceFormat($val['doctors_price']);?></dd>
        </dl>
        <div class="recommand-btn">
          <?php if(in_array($val['doctors_id'],$output['commend_doctors_array'])) { ?>
          <a href="javascript:void(0)"><?php echo $lang['microclinic_doctors_commend_already'];?></a>
          <?php } else { ?>
          <a href="javascript:void(0)" class="btn_commend_dialog" doctors_id="<?php echo $val['doctors_id'];?>" doctors_name="<?php echo $val['doctors_name'];?>" doctors_price="<?php echo $lang['currency'].$val['doctors_price'];?>" doctors_image="<?php echo thumb($val, 240);?>"><?php echo $lang['microclinic_doctors_commend_want'];?></a>
          <?php } ?>
        </div>
      </li>
      <?php } ?>
    </ul>
  </div>
  <div class="pagination"> <?php echo $output['show_page'];?> </div>
</div>

<!-- 弹出层开始 -->
<div id="div_commend_doctors" style="display:none;">
<form action="<?php echo MICROclinic_SITE_URL;?>/index.php?act=publish&op=doctors_save" method="post" id="add_form" class="feededitor">
  <input type="hidden" value="" name="commend_doctors_id" id="commend_doctors_id">
  </input>
  <div class="command-doctors">
    <div class="pic">
      <span class="thumb size100">
      <i></i><img alt="" onload="javascript:DrawImage(this,100,100);" src="" id="commend_dialog_doctors_image"></span></div>
    <dl class="intro">
      <dt id="commend_dialog_doctors_name"></dt>
      <dd id="commend_dialog_doctors_price"></dd>
    </dl>
  </div>
  <dl class="share">
    <dt><?php echo $lang['microclinic_doctors_publish_tile'];?></dt>
    <dd></dd>
    <textarea name="commend_message" id="commend_message" ></textarea>
  </dl>
  <div class="handle">
    <input id="btn_publish" type="button" value="<?php echo $lang['microclinic_text_commend'];?>" />
    <!-- 站外分享 -->
    <?php require('widget_share.php');?>
  </div>
</form>
</div>
<!-- 弹出层结束 -->
<?php } else { ?>
<div class="no-content"> <i class="buy pngFix">&nbsp;</i>
  <?php if($output['doctors_type'] == 'buy') { ?>
  <p><?php echo $lang['microclinic_doctors_buy_none'];?></p>
  <?php } else { ?>
  <p><?php echo $lang['microclinic_doctors_favorite_none'];?></p>
  <?php } ?>
</div>
<?php } ?>
