<?php defined('InclinicNC') or exit('Access Invalid!');?>

<div class="ncsc-index">
  <div class="top-container">
    <div class="basic-info">
      <dl class="ncsc-seller-info">
        <dt class="seller-name">
          <h3><?php echo $_SESSION['seller_name']; ?></h3>
          <h5>(username：<?php echo $_SESSION['member_name']; ?>)</h5>
        </dt>
        <dd class="clic-logo"><p><img src="<?php echo getclicLogo($output['clic_info']['clic_label']);?>"/></p><a href="<?php echo urlclinic('clic_setting', 'clic_setting');?>"><i class="icon-edit"></i>Edit clinic Settings</a> </dd>
        <dd class="seller-permission">Authority management:<strong><?php echo $_SESSION['seller_group_name'];?></strong></dd>
        <dd class="seller-last-login">Last login:<strong><?php echo $_SESSION['seller_last_login_time'];?></strong>
        </dd>
        <dd class="clic-name"><?php echo $lang['clic_name'].$lang['nc_colon'];?><a href="<?php echo urlclinic('show_clic', 'index', array('clic_id' => $_SESSION['clic_id']), $output['clic_info']['clic_domain']);?>" target="_blank"><?php echo $output['clic_info']['clic_name'];?></a></dd>
        <dd class="clic-grade"><?php echo $lang['clic_clic_grade'].$lang['nc_colon'];?><strong><?php echo $output['clic_info']['grade_name']; ?></strong></dd>
        <dd class="clic-validity"><?php echo $lang['clic_validity'].$lang['nc_colon'];?><strong><?php echo $output['clic_info']['clic_end_time_text'];?></strong></dd>
      </dl>
      <div class="detail-rate">
      <h5>
<strong><?php echo $lang['clic_dynamic_evaluation'].$lang['nc_colon'];?></strong>
Compared with the industry
</h5>
        <ul>
            <?php  foreach ($output['clic_info']['clic_credit'] as $value) {?>
            <li>
            <?php echo $value['text'];?><span class="credit"><?php echo $value['credit'];?> points</span>
            <span class="<?php echo $value['percent_class'];?>"><i></i><?php echo $value['percent_text'];?><em><?php echo $value['percent'];?></em></span>
            </li>
            <?php } ?>
        </ul>
      </div>
    </div>
  </div>
  <div class="seller-cont">
    <div class="container type-a">
      <div class="hd">
        <h3><?php echo $lang['clic_owner_info'];?></h3>
        <h5><?php echo $lang['clic_notice_info'];?></h5>
      </div>
      <div class="content">
        <dl class="focus">
          <dt>Clinic doctors release:</dt><dd title="published/<?php echo $lang['clic_publish_doctors'];?>"><em id="nc_doctorscount">0</em>&nbsp;/&nbsp;<?php if ($output['clic_info']['grade_doctorslimit'] != 0){ echo $output['clic_info']['grade_doctorslimit'];} else { echo 'unlimited';} ?></dd><dt><?php echo $lang['clic_publish_album'].$lang['nc_colon'];?></dt><dd><em id="nc_imagecount">0</em>&nbsp;/&nbsp;<?php echo $output['clic_info']['grade_albumlimit']; ?></dd>
        </dl>
        <ul>
          <li><a href="index.php?act=clic_doctors_online&op=index"><?php echo $lang['clic_doctors_selling'];?> <strong id="nc_online"></strong></a></li>
          <?php if (C('doctors_verify')) {?>
          <li><a href="index.php?act=clic_doctors_offline&op=index&type=wait_verify&verify=10" title="<?php echo $lang['clic_inform30'];?>">released, wait for reviewing <strong id="nc_waitverify"></strong></a></li>
          <li><a href="index.php?act=clic_doctors_offline&op=index&type=wait_verify&verify=0" title="<?php echo $lang['clic_inform30'];?>">Platform view failed <strong id="nc_verifyfail"></strong></a></li>
          <?php }?>
          <li><a href="index.php?act=clic_doctors_offline&op=index"><?php echo $lang['clic_doctors_storage'];?> <strong id="nc_offline"></strong></a></li>
          <li><a href="index.php?act=clic_doctors_offline&op=index&type=lock_up"><?php echo $lang['clic_doctors_show0'];?> <strong id="nc_lockup"></strong></a></li>
          <li><a href="index.php?act=clic_consult&op=consult_list&type=to_reply"><?php echo $lang['clic_to_consult'];?> <strong id="nc_consult"></strong></a></li>
        </ul>
      </div>
    </div>
    <div class="container type-b">
      <div class="hd">
        <h3><?php echo $output['article_class_info']['ac_name'];?></h3>
        <h5></h5>
      </div>
      <div class="content">
        <ul>
          <?php
			if(is_array($output['show_article']) && !empty($output['show_article'])) {
				foreach($output['show_article'] as $val) {
			?>
          <li><a target="_blank" href="<?php if(!empty($val['article_url']))echo $val['article_url'];else{ echo urlclinic('article', 'show',array('article_id'=>$val['article_id']));}?>" title="<?php echo $val['article_title']; ?>"><?php echo str_cut($val['article_title'],24);?></a></li>
          <?php
				}
			}
			 ?>
        </ul>
        <dl>
          <dt><?php echo $lang['clic_site_info'];?></dt>
          <?php
			if(is_array($output['phone_array']) && !empty($output['phone_array'])) {
				foreach($output['phone_array'] as $key => $val) {
			?>
          <dd><?php echo $lang['clic_site_phone'].($key+1).$lang['nc_colon'];?><?php echo $val;?></dd>
          <?php
				}
			}
			 ?>
          <dd><?php echo $lang['clic_site_email'].$lang['nc_colon'];?><?php echo C('site_email');?></dd>
        </dl>
      </div>
    </div>
    <div class="container type-a">
      <div class="hd">
        <h3><?php echo $lang['clic_business'];?></h3>
        <h5><?php echo $lang['clic_business_info'];?></h5>
      </div>
      <div class="content">
        <dl class="focus">
          <dt><?php echo $lang['clic_appointment_info'].$lang['nc_colon'];?></dt>
          <dd><a href="index.php?act=clic_appointment"><?php echo $lang['clic_appointment_progressing'];?> <strong id="nc_progressing"></strong></a></dd>
          <dt><?php echo $lang['clic_complain_info'].$lang['nc_colon'];?></dt>
          <dd><a href="index.php?act=clic_complain&select_complain_state=1"><?php echo $lang['clic_complain'];?> <strong id="nc_complain"></strong></a></dd>
        </dl>
        <ul>
          <li><a href="index.php?act=clic_appointment&op=index&state_type=state_new"><?php echo $lang['clic_appointment_pay'];?> <strong id="nc_payment"></strong></a></li>
          <li><a href="index.php?act=clic_appointment&op=index&state_type=state_pay"><?php echo $lang['clic_shipped'];?> <strong id="nc_delivery"></strong></a></li>
          <li><a href="index.php?act=clic_refund&lock=2"> <?php echo 'Pre-sale refund';?> <strong id="nc_refund_lock"></strong></a></li>
          <li><a href="index.php?act=clic_refund&lock=1"> <?php echo 'After-sales refund';?> <strong id="nc_refund"></strong></a></li>
          <li><a href="index.php?act=clic_return&lock=2"> <?php echo 'Pre-sale cancel';?> <strong id="nc_return_lock"></strong></a></li>
          <li><a href="index.php?act=clic_return&lock=1"> <?php echo 'After-sale cancel';?> <strong id="nc_return"></strong></a></li>
          <li><a href="index.php?act=clic_bill&op=index&bill_state=1"> <?php echo 'Bill to be confirmed';?> <strong id="nc_bill_confirm"></strong></a></li>
        </ul>
      </div>
    </div>
    <div class="container type-c">
      <div class="hd">
        <h3>Booking statistics</h3>
        <h5>Statistics of clinic appointment quantity and appointment amount by cycle</h5>
      </div>
      <div class="content">
        <table class="ncsc-table-style">
          <thead>
            <tr>
              <th class="w50">items</th>
              <th>appointments</th>
              <th class="w100">appointment amount</th>
            </tr>
          </thead>
          <tbody>
            <tr class="bd-line">
              <td>Daily appointments</td>
              <td><?php echo empty($output['daily_sales']['count']) ? '--' : $output['daily_sales']['count'];?></td>
              <td><?php echo empty($output['daily_sales']['sum']) ? '--' : $lang['currency'].$output['daily_sales']['sum'];?></td>
            </tr>
            <tr class="bd-line">
              <td>monthly appointments</td>
              <td><?php echo empty($output['monthly_sales']['count']) ? '--' : $output['monthly_sales']['count'];?></td>
              <td><?php echo empty($output['monthly_sales']['sum']) ? '--' : $lang['currency'].$output['monthly_sales']['sum'];?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="container type-c h500">
      <div class="hd">
        <h3>Doctor's appointments rank</h3>
        <h5>Master the hottest doctors</h5>
      </div>
      <div class="content">
        <table class="ncsc-table-style">
          <thead>
            <tr>
              <th class="w50">rank</th>
              <th class="w70"></th>
              <th class="tl">doctor information</th>
              <th class="w60">appointments</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($output['doctors_list']) {?>
            <?php  $i = 0;foreach ($output['doctors_list'] as $val) {$i++?>
            <tr class="bd-line">
              <td><strong><?php echo $i;?></strong></td>
              <td><div class="pic-thumb"><a href="<?php echo urlclinic('doctors', 'index', array('doctors_id' => $val['doctors_id']))?>" target="_blank"><img alt="<?php echo $val['doctors_name'];?>" src="<?php echo thumb($val, '60');?>"></a></div></td>
              <td><dl class="doctors-name">
                  <dt><a href="<?php echo urlclinic('doctors', 'index', array('doctors_id' => $val['doctors_id']))?>" target="_blank"><?php echo $val['doctors_name'];?></a></dt>
                </dl></td>
              <td><?php echo $val['doctors_salenum'];?></td>
            </tr>
            <?php }?>
            <?php }?>
          </tbody>
        </table>
      </div>
    </div>
    <!-- <div class="container type-d h500">
      <div class="hd">
        <h3><?php echo $lang['clic_market_info'];?></h3>
        <h5>合理参加促销活动可以有效提升商品销量</h5>
      </div>
      <div class="content">
        <?php if (C('groupbuy_allow') == 1){ ?>
        <dl class="tghd">
          <dt class="p-name"> <a href="index.php?act=clic_groupbuy"><?php echo $lang['clic_groupbuy'];?></a></dt>
          <dd class="p-ico"></dd>
          <dd class="p-hint">
            <?php if (!empty($output['groupquota_info'])) {?>
            <i class="icon-ok-sign"></i>已开通
            <?php } else {?>
            <i class="icon-minus-sign"></i>未开通
            <?php }?>
          </dd>
          <dd class="p-info"><?php echo $lang['clic_groupbuy_info'];?></dd>
          <?php if (!empty($output['groupquota_info'])) {?>
          <dd class="p-point">(续费至：<?php echo date('Y-m-d', $output['groupquota_info']['end_time']);?>)</dd>
          <?php }?>
        </dl>
        <?php } ?>
        <?php if (intval(C('promotion_allow')) == 1){ ?>
        <dl class="xszk">
          <dt class="p-name"><a href="index.php?act=clic_promotion_xianshi&op=xianshi_list"><?php echo $lang['clic_xianshi'];?></a></dt>
          <dd class="p-ico"></dd>
          <dd class="p-hint"><span>
            <?php if (!empty($output['xianshiquota_info'])) {?>
            <i class="icon-ok-sign"></i>已开通
            <?php } else {?>
            <i class="icon-minus-sign"></i>未开通
            <?php }?>
            </span></dd>
          <dd class="p-info"><?php echo $lang['clic_xianshi_info'];?></dd>
          <?php if (!empty($output['xianshiquota_info'])) {?>
          <dd class="p-point">(续费至：<?php echo date('Y-m-d', $output['xianshiquota_info']['end_time']);?>)</dd>
          <?php }?>
        </dl>
        <dl class="mjs">
          <dt class="p-name"><a href="index.php?act=clic_promotion_mansong&op=mansong_list"><?php echo $lang['clic_mansong'];?></a></dt>
          <dd class="p-ico"></dd>
          <dd class="p-hint"><span>
            <?php if (!empty($output['mansongquota_info'])) {?>
            <i class="icon-ok-sign"></i>已开通
            <?php } else {?>
            <i class="icon-minus-sign"></i>未开通
            <?php }?>
            </span></dd>
          <dd class="p-info"><?php echo $lang['clic_mansong_info'];?></dd>
          <?php if (!empty($output['mansongquota_info'])) {?>
          <dd class="p-point">(续费至：<?php echo date('Y-m-d', $output['mansongquota_info']['end_time']);?>)</dd>
          <?php }?>
        </dl>
        <dl class="zhxs">
          <dt class="p-name"><a href="index.php?act=clic_promotion_bundling&op=bundling_list"><?php echo $lang['clic_bundling'];?></a></dt>
          <dd class="p-ico"></dd>
          <dd class="p-hint"><span>
            <?php if (!empty($output['binglingquota_info'])) {?>
            <i class="icon-ok-sign"></i>已开通
            <?php } else {?>
            <i class="icon-minus-sign"></i>未开通
            <?php }?>
            </span></dd>
          <dd class="p-info"><?php echo $lang['clic_bundling_info'];?></dd>
          <?php if (!empty($output['binglingquota_info'])) {?>
          <dd class="p-point">(续费至：<?php echo date('Y-m-d', $output['binglingquota_info']['bl_quota_endtime']);?>)</dd>
          <?php }?>
        </dl>
        <dl class="tjzw">
          <dt class="p-name"><a href="index.php?act=clic_promotion_booth&op=booth_list">推荐展位</a></dt>
          <dd class="p-ico"></dd>
          <dd class="p-hint"><span>
            <?php if (!empty($output['boothquota_info'])) {?>
            <i class="icon-ok-sign"></i>已开通
            <?php } else {?>
            <i class="icon-minus-sign"></i>未开通
            <?php }?>
            </span></dd>
          <dd class="p-info"><?php echo $lang['clic_activity_info'];?></dd>
          <?php if (!empty($output['boothquota_info'])) {?>
          <dd class="p-point">(续费至：<?php echo date('Y-m-d', $output['boothquota_info']['booth_quota_endtime']);?>)</dd>
          <?php }?>
        </dl>
        <?php } ?>
        <?php if (C('voucher_allow') == 1){?>
        <dl class="djq">
          <dt class="p-name"><a href="index.php?act=clic_voucher"><?php echo $lang['clic_voucher'];?></a></span></dt>
          <dd class="p-ico"></dd>
          <dd class="p-hint"><span>
            <?php if (!empty($output['voucherquota_info'])) {?>
            <i class="icon-ok-sign"></i>已开通
            <?php } else {?>
            <i class="icon-minus-sign"></i>未开通
            <?php }?>
            </span></dd>
          <dd class="p-info"><?php echo $lang['clic_voucher_info'];?></dd>
          <?php if (!empty($output['voucherquota_info'])) {?>
          <dd class="p-point">(续费至：<?php echo date('Y-m-d', $output['voucherquota_info']['quota_endtime']);?>)</dd>
          <?php }?>
        </dl>
        <?php }?>
      </div>
    </div> -->
  </div>
</div>
<script>
$(function(){
	var timestamp=Math.round(new Date().getTime()/1000/60);//异步URL一分钟变化一次
    $.getJSON('index.php?act=seller_center&op=statistics&rand='+timestamp, null, function(data){
        if (data == null) return false;
        for(var a in data) {
            if(data[a] != 'undefined' && data[a] != 0) {
                var tmp = '';
                if (a != 'doctorscount' && a != 'imagecount') {
                    $('#nc_'+a).parents('a').addClass('num');
                }
                $('#nc_'+a).html(data[a]);
            }
        }
    });
});
</script>
