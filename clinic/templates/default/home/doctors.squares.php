<?php defined('InclinicNC') or exit('Access Invalid!');?>
<style type="text/css">
#box { background: #FFF; width: 238px; height: 410px; margin: -390px 0 0 0; display: block; bappointment: solid 4px #D93600; position: absolute; z-index: 999; opacity: .5}
.clinicMenu { position: fixed; z-index:1; right:25%; top: 0;}

</style>
<div class="squares" nc_type="current_display_mode">
  <?php if(!empty($output['doctors_list']) && is_array($output['doctors_list'])){?>
  <ul class="list_pic">
    <?php foreach($output['doctors_list'] as $value){?>
    <li class="item">
      <div class="doctors-content" nctype_doctors=" <?php echo $value['doctors_id'];?>" nctype_clic="<?php echo $value['clic_id'];?>">
        <div class="doctors-pic"><a href="<?php echo urlclinic('doctors','index',array('doctors_id'=>$value['doctors_id']));?>" target="_blank" title="<?php echo $value['doctors_name'];?>"><img src="<?php echo thumb($value, 240);?>" title="<?php echo $value['doctors_name'];?>" alt="<?php echo $value['doctors_name'];?>" /></a> </div>
        <?php if ($value['group_flag']) {?>
        <div class="doctors-promotion"><span>团购商品</span></div>
        <?php } elseif ($value['xianshi_flag'])  {?>
        <div class="doctors-promotion"><span>限时折扣</span></div>
        <?php }?>
        <div class="doctors-info">
          <div class="doctors-pic-scroll-show">
            <?php if(!empty($value['image'])) {?>
            <ul>
              <?php $i=0;foreach ($value['image'] as $val) {$i++?>
			  <?php if($i == 6){ break;}else{?>
              <li<?php if($i==1) {?> class="selected"<?php }?>><a href="javascript:void(0);"><img src="<?php echo thumb($val, 60);?>"/></a></li>
			  <?php }?>
              <?php }?>
            </ul>
            <?php }?>
          </div>
          <div class="doctors-name"><a href="<?php echo urlclinic('doctors','index',array('doctors_id'=>$value['doctors_id']));?>" target="_blank" title="<?php echo $value['doctors_jingle'];?>"><?php echo $value['doctors_name'];?><em><?php echo $value['doctors_jingle'];?></em></a></div>

          <div class="doctors-price">
          <em class="sale-price" title="<?php echo $lang['doctors_class_index_clic_doctors_price'].$lang['nc_colon'].$lang['currency'].$value['doctors_price'];?>"><?php echo ncPriceFormatForList($value['doctors_price']);?></em>
          <!-- <em class="market-price" title="市场价：<?php echo $lang['currency'].$value['doctors_marketprice'];?>"><?php echo ncPriceFormatForList($value['doctors_marketprice']);?></em> -->
          <span class="raty" data-score="<?php echo $value['evaluation_doctor_star'];?>"></span>
      </div>
          <div class="sell-stat">
            <ul>
              <li><a href="<?php echo urlclinic('doctors', 'index', array('doctors_id' => $value['doctors_id']));?>#ncdoctorsRate" target="_blank" class="status"><?php echo $value['doctors_salenum'];?></a><p>appointments</p></li>
              <li><a href="<?php echo urlclinic('doctors', 'comments_list', array('doctors_id' => $value['doctors_id']));?>" target="_blank"><?php echo $value['evaluation_count'];?></a><p>comments</p></li>
              <!-- <li><em member_id="<?php echo $value['member_id'];?>">&nbsp;</em></li> -->
            </ul>
          </div>
          <div class="clic"><a href="<?php echo urlclinic('show_clic','index',array('clic_id'=>$value['clic_id']), $value['clic_domain']);?>" title="<?php echo $value['clic_name'];?>" class="name"><?php echo $value['clic_name'];?></a></div>
         <div class="add-cart">
           <?php if ($value['group_flag']) {?>
           <a href="javascript:void(0);" nctype="buy_now" data-param="{doctors_id:<?php echo $value['doctors_id'];?>}"><i class="icon-clinicping-cart"></i>Booking now</a>
           <?php } else {?>
           <a href="javascript:void(0);" nctype="add_cart" data-param="{doctors_id:<?php echo $value['doctors_id'];?>}"><i class="icon-clinicping-cart"></i>select</a>
           <?php }?>
         </div>
        </div>
      </div>
    </li>
    <?php }?>
    <div class="clear"></div>
  </ul>
  <?php }else{?>
  <div id="no_results" class="no-results"><i></i><?php echo $lang['index_no_record'];?></div>
  <?php }?>
</div>
<form id="buynow_form" method="post" action="<?php echo clinic_SITE_URL;?>/index.php" target="_blank">
  <input id="act" name="act" type="hidden" value="buy" />
  <input id="op" name="op" type="hidden" value="buy_step1" />
  <input id="doctors_id" name="cart_id[]" type="hidden"/>
</form>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/jquery.raty.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.raty').raty({
            path: "<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/img",
            readOnly: true,
            width: 80,
            score: function() {
              return $(this).attr('data-score');
            }
        });
    });
</script>

