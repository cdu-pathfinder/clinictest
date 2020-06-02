<?php defined('InclinicNC') or exit('Access Invalid!');?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/qtip/jquery.qtip.min.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/qtip/jquery.qtip.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo clinic_TEMPLATES_URL;?>/css/home_doctors.css" rel="stylesheet" type="text/css">
<style type="text/css">
.ncs-doctors-picture .levelB, .ncs-doctors-picture .levelC {
cursor: url(<?php echo clinic_TEMPLATES_URL;
?>/images/clinic/zoom.cur), pointer;
}
.ncs-doctors-picture .levelD {
cursor: url(<?php echo clinic_TEMPLATES_URL;
?>/images/clinic/hand.cur), move\9;
}
</style>

<div id="content" class="wrapper pr">
  <div class="ncs-detail"><!-- S 商品举报 -->
    <?php if(!$output['clic_self']) { ?>
    <div class="ncs-inform"><span><?php echo $lang['doctors_index_inform'];?><i></i></span><a href="index.php?act=member_inform&op=inform_submit&doctors_id=<?php echo $output['doctors']['doctors_id'];?>" title="<?php echo $lang['doctors_index_doctors_inform'];?>"><?php echo $lang['doctors_index_doctors_inform'];?></a> </div>
    <?php } ?>
    <!-- End --> 
    
    <!-- S 商品图片 -->
    <div id="ncs-doctors-picture" class="ncs-doctors-picture image_zoom"> </div>
    <!-- S 商品基本信息 -->
    <div class="ncs-doctors-summary">
      <div class="name">
        <h1><?php echo $output['doctors']['doctors_name']; ?></h1>
        <strong><?php echo $output['doctors']['doctors_jingle'];?></strong> </div>
      <div class="ncs-meta"> 
        <!-- S 商品参考价格 -->
        <!-- <dl>
          <dt><?php echo $lang['doctors_index_doctors_cost_price'];?><?php echo $lang['nc_colon'];?></dt>
          <dd class="cost-price"><strong><?php echo $lang['currency'].$output['doctors']['doctors_marketprice'];?></strong></dd>
        </dl> -->
        <!-- S 商品发布价格 -->
        <dl>
          <dt><?php echo $lang['doctors_index_doctors_price'];?><?php echo $lang['nc_colon'];?></dt>
          <dd class="price">
            <?php if ($output['doctors']['promotion_type'] == 'groupbuy') {?>
            <span class="tag">团购</span><strong><?php echo $lang['currency'].$output['doctors']['promotion_price'];?></strong><em>(原售价<?php echo $lang['nc_colon'];?><?php echo $lang['currency'].$output['doctors']['doctors_price'];?>)</em>
            <?php } elseif ($output['doctors']['promotion_type'] == 'xianshi') {?>
            <?php if ($output['xianshi_info']['xianshi_title'] != '') {?><span class="tag"><?php echo $output['xianshi_info']['xianshi_title'];?></span><?php }?><strong><?php echo $lang['currency'].$output['doctors']['promotion_price'];?></strong><em>(原售价<?php echo $lang['nc_colon'];?><?php echo $lang['currency'].$output['doctors']['doctors_price'];?>)</em>
            <?php } else {?>
            <strong><?php echo $lang['currency'].$output['doctors']['doctors_price'];?></strong>
            <?php }?>
          </dd>
        </dl>
        <!-- E 商品发布价格 -->
        <!-- S 限时优惠 -->
        <!-- <?php if ($output['doctors']['promotion_type'] == 'xianshi') {?>
        <dl>
          <dt>促销信息：</dt>
          <dd class="promotion-info">直降：<?php echo $lang['currency'].$output['doctors']['down_price'];?>
          <em>
          <?php if($output['doctors']['lower_limit']) {?>
          <?php echo sprintf('最低%s件起',$output['doctors']['lower_limit']);?>
          <?php } ?>
          </em>
          <span><?php echo $output['xianshi_info']['xianshi_explain'];?></span> </dd>
        </dl>
        <?php }?> -->
        <!-- E 限时优惠  -->
        <!-- S 团购-->
        <!-- <?php if ($output['doctors']['promotion_type'] == 'groupbuy') {?>
        <dl>
          <dt>促销信息：</dt>
          <dd class="promotion-info">
          <em>
          <?php if ($output['doctors']['upper_limit']) {?>
          <?php echo sprintf('最多限购%s件',$output['doctors']['upper_limit']);?>
          <?php } ?>
          </em>
          <span><?php echo $output['doctors']['remark'];?></span> </dd>
        </dl>
        <?php }?> -->
        <!-- E 团购 -->
        <!-- S 描述相符评分及评价数量 -->
        <dl>
          <dt><?php echo $lang['doctors_index_evaluation'];?><?php echo $lang['nc_colon'];?></dt>
          <dd><div class="raty" data-score="<?php echo $output['doctors_evaluate_info']['doctor_star'];?>"></div><a href="#ncdoctorsRate">(<?php echo $output['doctors_evaluate_info']['all'];?><?php echo $lang['doctors_index_number_of_consult'];?>)</a></dd>
        </dl>
        <!-- E 描述相符评分及评价数量 --> 
        <!-- S 物流运费 -->
        <dl class="ncs-freight">
          <!-- <dt>
            <?php if ($output['doctors']['doctors_transfee_charge'] == 1){?>
            <?php echo $lang['doctors_index_freight'].$lang['nc_colon'];?>
            <?php }else{?> -->
            <!-- 如果买家承担运费 --> 
            <!-- 如果使用了运费模板 -->
            <!-- <?php if ($output['doctors']['transport_id'] != '0'){?>
            <?php echo $lang['doctors_index_trans_to'];?><a href="javascript:void(0)" id="ncrecive"><?php echo $lang['doctors_index_trans_country'];?></a><?php echo $lang['nc_colon'];?>
            <div class="ncs-freight-box" id="transport_pannel">
              <?php if (is_array($output['area_list'])){?>
              <?php foreach($output['area_list'] as $k=>$v){?>
              <a href="javascript:void(0)" nctype="<?php echo $k;?>"><?php echo $v;?></a>
              <?php }?>
              <?php }?>
            </div>
            <?php }else{?>
            <?php echo $lang['doctors_index_trans_zcountry'];?><?php echo $lang['nc_colon'];?>
            <?php }?>
            <?php }?>
          </dt> -->
         <!--  <dd id="transport_price">
            <?php if($output['group']) { ?>
            <span><?php echo $lang['doctors_index_groupbuy_no_shipping_fee'];?></span>
            <?php } else { ?>
            <?php if ($output['doctors']['doctors_freight'] == 0){?>
            <?php echo $lang['doctors_index_trans_for_seller'];?>
            <?php }else{?> -->
            <!-- 如果买家承担运费 --> 
            <!-- <span>运费<?php echo $lang['nc_colon'];?><em id="nc_kd"><?php echo $output['doctors']['doctors_freight'];?></em><?php echo $lang['doctors_index_yuan'];?></span>
            <?php }?>
            <?php }?>
          </dd> -->
          <dd style="color:red;display:none" id="loading_price">loading.....</dd>
        </dl>
        <!-- E 物流运费 ---> 
        <!-- S 累计售出数量 -->
        <dl>
          <dt><?php echo $lang['doctors_index_sold'];?><?php echo $lang['nc_colon'];?></dt>
          <dd><strong><a href="#ncdoctorsTraded"><?php echo $output['doctors']['doctors_salenum']; ?></a></strong>&nbsp;<?php echo $lang['nc_jian'];?></dd>
        </dl>
        <!-- E 累计售出数量 --> 
        
      </div>
      <?php if($output['doctors']['doctors_state'] == 1 && $output['doctors']['doctors_verify'] == 1){?>
      <div class="ncs-key"> 
        <!-- S 商品规格值-->
        <?php if (is_array($output['doctors']['spec_name'])) { ?>
        <?php foreach ($output['doctors']['spec_name'] as $key => $val) {?>
        <dl nctype="nc-spec">
          <dt><?php echo $val;?><?php echo $lang['nc_colon'];?></dt>
          <dd>
            <?php if (is_array($output['doctors']['spec_value'][$key]) and !empty($output['doctors']['spec_value'][$key])) {?>
            <ul nctyle="ul_sign">
              <?php foreach($output['doctors']['spec_value'][$key] as $k => $v) {?>
              <?php if( $key == 1 ){?>
              <!-- 图片类型规格-->
              <li class="sp-txt"><a href="javascript:void(0);" class="<?php if (isset($output['doctors']['doctors_spec'][$k])) {echo 'hovered';}?>" data-param="{valid:<?php echo $k;?>}" title="<?php echo $v;?>"><i></i></a></li>
              <?php }else{?>
              <!-- 文字类型规格-->
              <li class="sp-txt"><a href="javascript:void(0)" class="<?php if (isset($output['doctors']['doctors_spec'][$k])) { echo 'hovered';} ?>" data-param="{valid:<?php echo $k;?>}"><?php echo $v;?><i></i></a></li>
              <?php }?>
              <?php }?>
            </ul>
            <?php }?>
          </dd>
        </dl>
        <?php }?>
        <?php }?>
        <!-- E 商品规格值--> 
        <!-- S 购买数量及库存 -->
        <dl>
          <dt><?php echo $lang['doctors_index_buy_amount'];?><?php echo $lang['nc_colon'];?></dt>
          <dd class="ncs-figure-input">
            <input type="text" name="" id="quantity" value="10" size="3" maxlength="6" class="text w30">
            <a href="javascript:void(0)" class="increase">+</a><a href="javascript:void(0)" class="decrease">-</a><em>(<?php echo $lang['doctors_index_stock'];?><strong nctype="doctors_stock"><?php echo $output['doctors']['doctors_storage']; ?></strong>)</em> </dd>
        </dl>
        <!-- E 购买数量及库存 --> 
        
        <!-- S 购买按钮 -->
        <div class="ncs-btn"><!-- S 提示已选规格及库存不足无法购买 -->
          <div nctype="doctors_prompt" class="ncs-point">
            <?php if (!empty($output['doctors']['doctors_spec'])) {?>
            <span class="yes"><?php echo $lang['doctors_index_you_choose'];?> <strong><?php echo implode($lang['nc_comma'], $output['doctors']['doctors_spec']);?></strong></span>
            <?php }?>
            <?php if ($output['doctors']['doctors_storage'] <= 0) {?>
            <span class="no"><i class="icon-exclamation-sign"></i>&nbsp;<?php echo $lang['doctors_index_understock_prompt'];?></span>
            <?php }?>
          </div>
          <!-- E 提示已选规格及库存不足无法购买 --> 
          <!-- 立即购买--> 
          <a href="javascript:void(0);" nctype="buynow_submit" class="buynow <?php if ($output['doctors']['doctors_storage'] <= 0) {?>no-buynow<?php }?>" title="<?php echo $lang['doctors_index_now_buy'];?>"><?php echo $lang['doctors_index_now_buy'];?></a> 
          <?php if ($output['doctors']['promotion_type'] != 'groupbuy') {?>
          <!-- 加入购物车-->
          <a href="javascript:void(0);" nctype="addcart_submit" class="addcart <?php if ($output['doctors']['doctors_storage'] <= 0) {?>no-addcart<?php }?>" title="<?php echo $lang['doctors_index_add_to_cart'];?>"><i class="icon-clinicping-cart"></i><?php echo $lang['doctors_index_add_to_cart'];?></a>
          <?php } ?>

          <!-- S 加入购物车弹出提示框 -->
          <div class="ncs-cart-popup">
            <dl>
              <dt><?php echo $lang['doctors_index_cart_success'];?><a title="<?php echo $lang['doctors_index_close'];?>" onClick="$('.ncs-cart-popup').css({'display':'none'});">X</a></dt>
              <dd><?php echo $lang['doctors_index_cart_have'];?> <strong id="bold_num"></strong> <?php echo $lang['doctors_index_number_of_doctors'];?> <?php echo $lang['doctors_index_total_price'];?><?php echo $lang['nc_colon'];?><em id="bold_mly" class="saleP"></em></dd>
              <dd class="btns"><a href="javascript:void(0);" class="ncs-btn-mini ncs-btn-green" onClick="location.href='<?php echo clinic_SITE_URL.DS?>index.php?act=cart'"><?php echo $lang['doctors_index_view_cart'];?></a> <a href="javascript:void(0);" class="ncs-btn-mini" value="" onClick="$('.ncs-cart-popup').css({'display':'none'});"><?php echo $lang['doctors_index_continue_clinicping'];?></a></dd>
            </dl>
          </div>
          <!-- E 加入购物车弹出提示框 -->

        </div>
        <!-- E 购买按钮 -->
        <!-- <div class="ncs_share"> <a href="javascript:void(0);" nc_type="sharedoctors" data-param='{"gid":"<?php echo $output['doctors']['doctors_id'];?>"}'><i class="icon-share"></i><?php echo $lang['doctors_index_snsshare_doctors'];?><em nc_type="sharecount_<?php echo $output['doctors']['doctors_id'];?>"><?php echo intval($output['doctors']['sharenum'])>0?intval($output['doctors']['sharenum']):0;?></em></a><a href="javascript:collect_doctors('<?php echo $output['doctors']['doctors_id']; ?>','count','doctors_collect');"><i class="icon-star-empty"></i><?php echo $lang['doctors_index_favorite_doctors'];?><em nctype="doctors_collect"><?php echo $output['doctors']['doctors_collect']?></em></a></div> -->
      </div>
      <?php }else{?>
      <div class="ncs-saleout">
      <dl>
        <dt><i class="icon-info-sign"></i><?php echo $lang['doctors_index_is_no_show'];?></dt>
        <dd><?php echo $lang['doctors_index_is_no_show_message_one'];?></dd>
        <dd><?php echo $lang['doctors_index_is_no_show_message_two_1'];?>&nbsp;<a href="<?php echo urlclinic('show_clic', 'index', array('clic_id'=>$output['doctors']['clic_id']), $output['clic_info']['clic_domain']);?>" class="ncs-btn-mini"><?php echo $lang['doctors_index_is_no_show_message_two_2'];?></a>&nbsp;<?php echo $lang['doctors_index_is_no_show_message_two_3'];?> </dd>
      </dl></div>
      <?php }?>
      <!--E 商品信息 --> 
      
    </div>
    <!-- E 商品图片及收藏分享 --> 
    <!--S 店铺信息-->
    <div class="ncg-info" style=" position: absolute; z-index: 1; top: 60px; right: 0;">
      <?php include template('clic/info');?>
    </div>
    <!--E 店铺信息 --> 
  </div>
  <div class="ncs-doctors-layout expanded" >
    <div class="ncs-doctors-main" id="main-nav-holder">
      <div class="ncs-promotion" style="display: none;">
        <div class="ncs-doctors-title-nav">
          <ul>
            <li class="current"><a href="javascript:void(0);">优惠套装</a></li>
          </ul>
        </div>
        <div class="ncs-doctors-info-content"><!--S 组合销售 -->
          <div class="ncs-bundling" id="nc-bundling"> </div>
          <!--E 组合销售 --></div>
      </div>
      <nav class="tabbar pngFix" id="main-nav">
        <div class="ncs-doctors-title-nav">
          <ul id="categorymenu">
            <li class="current"><a id="tabdoctorsIntro" href="#content"><?php echo $lang['doctors_index_doctors_info'];?></a></li>
            <li><a id="tabdoctorsRate" href="#content"><?php echo $lang['doctors_index_evaluation'];?></a></li>
            <!-- <li><a id="tabdoctorsTraded" href="#content"><?php echo $lang['doctors_index_sold_record'];?></a></li> -->
            <li><a id="tabGuestbook" href="#content"><?php echo $lang['doctors_index_doctors_consult'];?></a></li>
          </ul>
          <div class="switch-bar"><a href="javascript:void(0)" id="fold">&nbsp;</a></div>
        </div>
      </nav>
      <div class="ncs-intro">
        <div class="content bd" id="ncdoctorsIntro"> 
          
          <!--S 满就送 -->
          <?php if($output['mansong_info']) { ?>
          <div class="nc-mansong">
            <div class="nc-mansong-ico"></div>
            <dl class="nc-mansong-content">
              <dt><?php echo $output['mansong_info']['mansong_name'];?>
                <time>( <?php echo $lang['nc_promotion_time'];?><?php echo $lang['nc_colon'];?><?php echo date('Y/m/d',$output['mansong_info']['start_time']).'--'.date('Y/m/d',$output['mansong_info']['end_time']);?> )</time>
              </dt>
              <dd>
                <?php foreach($output['mansong_info']['rules'] as $rule) { ?>
                <span><?php echo $lang['nc_man'];?><em><?php echo ncPriceFormat($rule['price']);?></em><?php echo $lang['nc_yuan'];?>
                <?php if(!empty($rule['discount'])) { ?>
                ， <?php echo $lang['nc_reduce'];?><i><?php echo ncPriceFormat($rule['discount']);?></i><?php echo $lang['nc_yuan'];?>
                <?php } ?>
                <?php if(!empty($rule['doctors_id'])) { ?>
                ， <?php echo $lang['nc_gift'];?> <a href="<?php echo $rule['doctors_url'];?>" title="<?php echo $rule['mansong_doctors_name'];?>" target="_blank"> <img src="<?php echo cthumb($rule['doctors_image'], 60);?>" alt="<?php echo $rule['mansong_doctors_name'];?>"> </a>&nbsp;。
                <?php } ?>
                </span>
                <?php } ?>
              </dd>
              <dd class="nc-mansong-remark"><?php echo $output['mansong_info']['remark'];?></dd>
            </dl>
          </div>
          <?php } ?>
          <!--E 满就送 -->
          <?php if(is_array($output['doctors']['doctors_attr']) || isset($output['doctors']['brand_name'])){?>
          <ul class="nc-doctors-sort">
            <li>ID-Clinic：<?php echo $output['doctors']['doctors_serial'];?></li>
            <?php if(isset($output['doctors']['brand_name'])){echo '<li>'.$lang['doctors_index_brand'].$lang['nc_colon'].$output['doctors']['brand_name'].'</li>';}?>
            <?php if(is_array($output['doctors']['doctors_attr']) && !empty($output['doctors']['doctors_attr'])){?>
            <?php foreach ($output['doctors']['doctors_attr'] as $val){ $val= array_values($val);echo '<li>'.$val[0].$lang['nc_colon'].$val[1].'</li>'; }?>
            <?php }?>
          </ul>
          <?php }?>
          <div class="ncs-doctors-info-content">
            <?php if (isset($output['plate_array'][1])) {?>
            <div class="top-template"><?php echo $output['plate_array'][1][0]['plate_content']?></div>
            <?php }?>
            <div class="default"><?php echo $output['doctors']['doctors_body']; ?></div>
            <?php if (isset($output['plate_array'][0])) {?>
            <div class="bottom-template"><?php echo $output['plate_array'][0][0]['plate_content']?></div>
            <?php }?>
          </div>
        </div>
      </div>
      <div class="ncs-comment">
        <div class="ncs-doctors-title-bar hd">
          <h4><a href="javascript:void(0);"><?php echo $lang['doctors_index_evaluation'];?></a></h4>
        </div>
        
        <div class="ncs-doctors-info-content bd" id="ncdoctorsRate">
            <div class="top">
                <div class="rate">
                    <p><strong><?php echo $output['doctors_evaluate_info']['doctor_percent'];?></strong><sub>%</sub>satisfied</p>
              <span>total<?php echo $output['doctors_evaluate_info']['all'];?>people evaluated</span></div>
            <div class="percent">
              <dl>
                <dt>satisfied<em>(<?php echo $output['doctors_evaluate_info']['doctor_percent'];?>%)</em></dt>
                <dd><i style="width: <?php echo $output['doctors_evaluate_info']['doctor_percent'];?>%"></i></dd>
              </dl>
              <dl>
                <dt>average<em>(<?php echo $output['doctors_evaluate_info']['normal_percent'];?>%)</em></dt>
                <dd><i style="width: <?php echo $output['doctors_evaluate_info']['normal_percent'];?>%"></i></dd>
              </dl>
              <dl>
                <dt>unsatisfied<em>(<?php echo $output['doctors_evaluate_info']['bad_percent'];?>%)</em></dt>
                <dd><i style="width: <?php echo $output['doctors_evaluate_info']['bad_percent'];?>%"></i></dd>
              </dl>
            </div>
            <div class="btns"><span>evaluate dotor booked</span>
              <p><a href="<?php echo urlclinic('member_appointment', 'index');?>" class="ncs-btn ncs-btn-red" target="_blank"><i class="icon-comment-alt"></i>evaluate</a></p>
            </div>
          </div>
          <!-- <div class="ncs-doctors-title-nav">
        <ul id="comment_tab">
            <li data-type="all" class="current"><a href="javascript:void(0);"><?php echo $lang['doctors_index_evaluation'];?>(<?php echo $output['doctors_evaluate_info']['all'];?>)</a></li>
            <li data-type="1"><a href="javascript:void(0);">satisfied(<?php echo $output['doctors_evaluate_info']['doctor'];?>)</a></li>
            <li data-type="2"><a href="javascript:void(0);">average(<?php echo $output['doctors_evaluate_info']['normal'];?>)</a></li> 
            <li data-type="3"><a href="javascript:void(0);">unsatisfied(<?php echo $output['doctors_evaluate_info']['bad'];?>)</a></li>
        </ul></div> -->
          <!-- 商品评价内容部分 -->
          <div id="doctorseval" class="ncs-commend-main"></div>
        </div>
      </div>
      <!-- <div class="ncg-salelog">
        <div class="ncs-doctors-title-bar hd">
         <h4><a href="javascript:void(0);"><?php echo $lang['doctors_index_sold_record'];?></a></h4>
        </div>
        <div class="ncs-doctors-info-content bd" id="ncdoctorsTraded">
          <div class="top">
            <div class="price"><?php echo $lang['doctors_index_doctors_price'];?><strong><?php echo $output['doctors']['doctors_price'];?></strong><?php echo $lang['doctors_index_yuan'];?><span><?php echo $lang['doctors_index_price_note'];?></span></div>
          </div>
          <!-- 成交记录内容部分 
          <div id="salelog_demo" class="ncs-loading"> </div>
        </div>
      </div> -->
      <div class="ncs-consult">
        <div class="ncs-doctors-title-bar hd">
          <h4><a href="javascript:void(0);"><?php echo $lang['doctors_index_doctors_consult'];?></a></h4>
        </div>
        <div class="ncs-doctors-info-content bd" id="ncGuestbook"> 
          <!-- 咨询留言内容部分 -->
          <div class="ncs-guestbook">
            <div id="cosulting_demo" class="ncs-loading"> </div>
          </div>
        </div>
      </div>
      <?php if(!empty($output['doctors_commend']) && is_array($output['doctors_commend']) && count($output['doctors_commend'])>1){?>
      <!-- <div class="ncs-recommend">
        <div class="title">
          <h4><?php echo $lang['doctors_index_doctors_commend'];?></h4>
        </div>
        <div class="content">
          <ul>
            <?php foreach($output['doctors_commend'] as $doctors_commend){?>
            <?php if($output['doctors']['doctors_id'] != $doctors_commend['doctors_id']){?>
            <li>
              <dl>
                <dt class="doctors-name"><a href="<?php echo urlclinic('doctors', 'index', array('doctors_id' => $doctors_commend['doctors_id']));?>" target="_blank" title="<?php echo $doctors_commend['doctors_jingle'];?>"><?php echo $doctors_commend['doctors_name'];?><em><?php echo $doctors_commend['doctors_jingle'];?></em></a></dt>
                <dd class="doctors-pic"><a href="<?php echo urlclinic('doctors', 'index', array('doctors_id' => $doctors_commend['doctors_id']));?>" target="_blank" title="<?php echo $doctors_commend['doctors_jingle'];?>"><img src="<?php echo thumb($doctors_commend, 240);?>" alt="<?php echo $doctors_commend['doctors_name'];?>"/></a></dd>
                <dd class="doctors-price"><?php echo $lang['currency'];?><?php echo $doctors_commend['doctors_price'];?></dd>
              </dl>
            </li>
            <?php }?>
            <?php }?>
          </ul>
          <div class="clear"></div>
        </div>
      </div> -->
      <?php }?>
    </div>
    <div class="ncs-sidebar">
      <!-- <div class="nc-s-c-s1">
        <div class="title">
          <h4>商品二维码</h4>
        </div>
        <div class="content">
          <div class="ncs-doctors-code"><p><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_clic.DS.$output['doctors']['clic_id'].DS.$output['doctors']['doctors_id'].'.png';?>" onerror="this.src='<?php echo UPLOAD_SITE_URL.DS.ATTACH_clic.DS.'default_qrcode.png';?>'" title="商品原始地址：<?php echo urlclinic('doctors', 'index', array('doctors_id'=>$output['doctors']['doctors_id']));?>"></p> <span class="ncs-doctors-code-note"><i></i>扫描二维码，手机查看分享</span> </div>
         </div>
      </div> -->
      <?php include template('clic/callcenter');?>
      <?php include template('clic/left');?>
    </div>
  </div>
</div>
<form id="buynow_form" method="post" action="<?php echo clinic_SITE_URL;?>/index.php">
  <input id="act" name="act" type="hidden" value="buy" />
  <input id="op" name="op" type="hidden" value="buy_step1" />
  <input id="cart_id" name="cart_id[]" type="hidden"/>
</form>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.charCount.js"></script> 
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.ajaxContent.pack.js" type="text/javascript"></script> 
<script src="<?php echo RESOURCE_SITE_URL;?>/js/sns.js" type="text/javascript" charset="utf-8"></script> 
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.F_slider.js" type="text/javascript" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/waypoints.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/jquery.raty.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/styles/nyroModal.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript">
/** 辅助浏览 **/
jQuery(function($){
	//产品图片
	$.getScript('<?php echo clinic_RESOURCE_SITE_URL?>/js/ImageZoom.js', function(){
		var 
		zoomController,
		zoomControllerUl,
		zoomControllerUlLeft = 0,
		shell = $('#ncs-doctors-picture'),
		shellPanel = shell.parent().hide(),
		heightOffset = 80,
		minGallerySize = [380, shellPanel.height() - heightOffset],
		imageZoom = new ImageZoom({
			shell: shell,
			basePath: '',
			levelASize: [60, 60],
			levelBSize: [360, 360],
			gallerySize: minGallerySize,
			onBeforeZoom: function(index, level){
				if(!zoomController){
					zoomController = shell.find('div.controller');
				}

				var 
				self = this,
				duration = 320,
				width = minGallerySize[0], 
				height = minGallerySize[1],
				zoomFx = function(){
					self.ops.gallerySize = [width, height];
					self.galleryPanel.stop().animate({width:width, height:height}, duration);
					shellPanel.stop().animate({height:height + heightOffset}, duration);
					zoomController.animate({width:width-22}, duration);
					shell.stop().animate({width:width}, duration);
				};
				if(level !== this.level && this.level !== 0){
					if(this.level === 1 && level > 1){
						height = Math.max(520, shellPanel.height());
						width = shellPanel.width();
						zoomFx();
					}
					else if(level === 1){
						zoomFx();
					}
				}
			},
			onZoom: function(index, level, prevIndex){
				if(index !== prevIndex){
					if(!zoomControllerUl){
						zoomControllerUl = zoomController.find('ul').eq(0);
					}
					var 
					width = 76, 
					ops = this.ops,
					count = ops.items.length,
					panelVol = ~~((zoomController.width() + 10)/width),
					minLeft = width * (panelVol - count),
					left = Math.min(0, Math.max(minLeft, -width * ~~(index-panelVol/2)));

					if(zoomControllerUlLeft !== left){
						zoomControllerUl.stop().animate({left: left}, 320);
						zoomControllerUlLeft = left;
					}
				}
				shell.find('a.prev,a.next')[level<3 ? 'removeClass' : 'addClass']('hide');
				shell.find('a.close').css('display', [level>1 ? 'block' : 'none']);
			},
			items: [ 
	                <?php if (!empty($output['doctors_image'])) {?>
	                <?php echo implode(',', $output['doctors_image']);?>
	                <?php }?>
					]
		});
		shell.data('imageZoom', imageZoom);

		shellPanel.show();
	});

});
</script> 
<script>
    //收藏分享处下拉操作
    jQuery.divselect = function(divselectid,inputselectid) {
      var inputselect = $(inputselectid);
      $(divselectid).mouseover(function(){
          var ul = $(divselectid+" ul");
          ul.slideDown("fast");
          if(ul.css("display")=="none"){
              ul.slideDown("fast");
          }
      });
      $(divselectid).live('mouseleave',function(){
          $(divselectid+" ul").hide();
      });
    };
$(function(){
    // 加入购物车
    $('a[nctype="addcart_submit"]').click(function(){
        addcart(<?php echo $output['doctors']['doctors_id'];?>, checkQuantity());
    });
    // 立即购买
    $('a[nctype="buynow_submit"]').click(function(){
        buynow(<?php echo $output['doctors']['doctors_id']?>,checkQuantity());
    });

    //浮动导航  waypoints.js
    $('#main-nav').waypoint(function(event, direction) {
        $(this).parent().parent().parent().toggleClass('sticky', direction === "down");
        event.stopPropagation();
    });

    // 分享收藏下拉操作
    $.divselect("#handle-l");
    $.divselect("#handle-r");

    // 规格选择
    $('dl[nctype="nc-spec"]').find('a').each(function(){
        $(this).click(function(){
            if ($(this).hasClass('hovered')) {
                return false;
            }
            $(this).parents('ul:first').find('a').removeClass('hovered');
            $(this).addClass('hovered');
            checkSpec();
        });
    });

});

function checkSpec() {
    var spec_param = <?php echo $output['spec_list'];?>;
    var spec = new Array();
    $('ul[nctyle="ul_sign"]').find('.hovered').each(function(){
        var data_str = ''; eval('data_str =' + $(this).attr('data-param'));
        spec.push(data_str.valid);
    });
    spec1 = spec.sort(function(a,b){
        return a-b;
    });
    var spec_sign = spec1.join('|');
    $.each(spec_param, function(i, n){
        if (n.sign == spec_sign) {
            window.location.href = n.url;
        }
    });
}

// 验证购买数量
function checkQuantity(){
    var quantity = parseInt($("#quantity").val());
    if (quantity < 1) {
        alert("<?php echo $lang['doctors_index_pleaseaddnum'];?>");
        $("#quantity").val('1');
        return false;
    }
    max = parseInt($('[nctype="doctors_stock"]').text());
    if(quantity > max){
        alert("<?php echo $lang['doctors_index_add_too_much'];?>");
        return false;
    }
    return quantity;
}

// 规格页面跳转
// function 

// 立即购买js
function buynow(doctors_id,quantity){
<?php if ($_SESSION['is_login'] !== '1'){?>
	login_dialog();
<?php }else{?>
    if (!quantity) {
        return;
    }
    $("#cart_id").val(doctors_id+'|'+quantity);
    $("#buynow_form").submit();
<?php }?>
}
$(function(){
    //选择地区查看运费
    $('#transport_pannel>a').click(function(){
    	var id = $(this).attr('nctype');
    	if (id=='undefined') return false;
    	var _self = this,tpl_id = '<?php echo $output['doctors']['transport_id'];?>';
	    var url = 'index.php?act=doctors&op=calc&rand='+Math.random();
	    $('#transport_price').css('display','none');
	    $('#loading_price').css('display','');
	    $.getJSON(url, {'id':id,'tid':tpl_id}, function(data){
	    	if (data == null) return false;
	        if(data != 'undefined') {$('#nc_kd').html(data);}else{$('#nc_kd').html('');}
	        $('#transport_price').css('display','');
	    	$('#loading_price').css('display','none');
	        $('#ncrecive').html($(_self).html());
	    });
    });
    <?php if($output['doctors']['doctors_show'] == '1'){?>
    $("#nc-bundling").load('index.php?act=doctors&op=get_bundling&doctors_id=<?php echo $output['doctors']['doctors_id'];?>&clic_id=<?php echo $output['doctors']['clic_id'];?>', function(){
        if($(this).html() != '') {
            $(this).parents('.ncs-promotion:first').show();
        }
    });
    <?php }?>
    $("#salelog_demo").load('index.php?act=doctors&op=salelog&doctors_id=<?php echo $output['doctors']['doctors_id'];?>&clic_id=<?php echo $output['doctors']['clic_id'];?>', function(){
        // Membership card
        $(this).find('[nctype="mcard"]').membershipCard({type:'clinic'});
    });
	$("#cosulting_demo").load('index.php?act=doctors&op=cosulting&doctors_id=<?php echo $output['doctors']['doctors_id'];?>&clic_id=<?php echo $output['doctors']['clic_id'];?>', function(){
		// Membership card
		$(this).find('[nctype="mcard"]').membershipCard({type:'clinic'});
	});
});

/** doctors.php **/
$(function(){	
	// 商品内容部分折叠收起侧边栏控制
	$('#fold').click(function(){
  		$('.ncs-doctors-layout').toggleClass('expanded');
	});
	// 商品内容介绍Tab样式切换控制
	$('#categorymenu').find("li").click(function(){
		$('#categorymenu').find("li").removeClass('current');
		$(this).addClass('current');
	});
	// 商品详情默认情况下显示全部
	$('#tabdoctorsIntro').click(function(){
		$('.bd').css('display','');
		$('.hd').css('display','');	
	});
	// 点击评价隐藏其他以及其标题栏
	$('#tabdoctorsRate').click(function(){
		$('.bd').css('display','none');
		$('#ncdoctorsRate').css('display','');
		$('.hd').css('display','none');
	});
	// 点击成交隐藏其他以及其标题
	$('#tabdoctorsTraded').click(function(){
		$('.bd').css('display','none');
		$('#ncdoctorsTraded').css('display','');
		$('.hd').css('display','none');
	});
	// 点击咨询隐藏其他以及其标题
	$('#tabGuestbook').click(function(){
		$('.bd').css('display','none');
		$('#ncGuestbook').css('display','');
		$('.hd').css('display','none');
	});
	//商品排行Tab切换
	$(".ncs-top-tab > li > a").mouseover(function(e) {
		if (e.target == this) {
			var tabs = $(this).parent().parent().children("li");
			var panels = $(this).parent().parent().parent().children(".ncs-top-panel");
			var index = $.inArray(this, $(this).parent().parent().find("a"));
			if (panels.eq(index)[0]) {
				tabs.removeClass("current ").eq(index).addClass("current ");
				panels.addClass("hide").eq(index).removeClass("hide");
			}
		}
	});
	//信用评价动态评分打分人次Tab切换
	$(".ncs-rate-tab > li > a").mouseover(function(e) {
		if (e.target == this) {
			var tabs = $(this).parent().parent().children("li");
			var panels = $(this).parent().parent().parent().children(".ncs-rate-panel");
			var index = $.inArray(this, $(this).parent().parent().find("a"));
			if (panels.eq(index)[0]) {
				tabs.removeClass("current ").eq(index).addClass("current ");
				panels.addClass("hide").eq(index).removeClass("hide");
			}
		}
	});
		
//触及显示缩略图	
	$('.doctors-pic > .thumb').hover(
		function(){
			$(this).next().css('display','block');
		},
		function(){
			$(this).next().css('display','none');
		}
	);
	
	/* 商品购买数量增减js */
	// 增加
	$('.increase').click(function(){
		num = parseInt($('#quantity').val());
	    <?php if (!empty($output['doctors']['upper_limit'])) {?>
	    max = <?php echo $output['doctors']['upper_limit'];?>;
	    if(num >= max){
	        alert('最多限购'+max+'件');
	        return false;
	    }
	    <?php } ?>
		max = parseInt($('[nctype="doctors_stock"]').text());
		if(num < max){
			$('#quantity').val(num+1);
		}
	});
	//减少
	$('.decrease').click(function(){
		num = parseInt($('#quantity').val());
		if(num > 1){
			$('#quantity').val(num-1);
		}
	});
	
	// 搜索价格不能填写非数字。
	var re = /^[1-9]+[0-9]*(\.\d*)?$|^0(\.\d*)?$/;
	$('input[name="start_price"]').change(function(){
		if(!re.test($(this).val())){
			$(this).val('');
		}
	});
	$('input[name="end_price"]').change(function(){
		if(!re.test($(this).val())){
			$(this).val('');
		}
	});
});

/* add cart */
function addcart(doctors_id, quantity)
{
	if (!quantity) return false;
    var url = 'index.php?act=cart&op=add';
    $.getJSON(url, {'doctors_id':doctors_id, 'quantity':quantity}, function(data){
    	if(data != null){
    		if (data.state)
            {
                $('#bold_num').html(data.num);
                $('#bold_mly').html(price_format(data.amount));
                $('.ncs-cart-popup').fadeIn('fast');
//                 setTimeout(slideUp_fn, 5000);
                // 头部加载购物车信息
                load_cart_information();
            }
            else
            {
                alert(data.msg);
            }
    	}
    });
}
// 显示举报下拉链接
$(document).ready(function() {
	$(".ncs-inform").hover(function() {
		$(this).addClass("hover");
	},
	function() {
		$(this).removeClass("hover");
	});
})

//评价列表
$(document).ready(function(){
    $('#comment_tab').on('click', 'li', function() {
        $('#comment_tab li').removeClass('current');
        $(this).addClass('current');
        load_doctorseval($(this).attr('data-type'));
    });

    load_doctorseval('all');

    function load_doctorseval(type) {
        var url = '<?php echo urlclinic('doctors', 'comments', array('doctors_id' => $output['doctors']['doctors_id']));?>';
        url += '&type=' + type;
        $("#doctorseval").load(url, function(){
            $(this).find('[nctype="mcard"]').membershipCard({type:'clinic'});
        });
    }
});
</script> 
