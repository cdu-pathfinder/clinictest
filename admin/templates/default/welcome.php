<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['dashboard_wel_system_info'];?><!--<?php echo $lang['dashboard_wel_lase_login'].$lang['nc_colon'];?><?php echo $output['admin_info']['admin_login_time'];?>--></h3>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="info-panel">
    <dl class="member">
      <dt>
        <div class="ico"><i></i><sub title="<?php echo $lang['dashboard_wel_total_member'];?>"><span><em id="statistics_member"></em></span></sub></div>
        <h3><?php echo $lang['nc_member'];?></h3>
        <h5><?php echo $lang['dashboard_wel_member_des'];?></h5>
      </dt>
      <dd>
        <ul>
          <li class="w50pre normal"><a href="index.php?act=member&op=member"><?php echo $lang['dashboard_wel_new_add'];?><sub><em id="statistics_week_add_member"></em></sub></a></li>
          <li class="w50pre none"><a href="index.php?act=predeposit&op=pd_cash_list"><?php echo $lang['dashboard_wel_predeposit_get'];?><sub><em id="statistics_cashlist">0</em></sub></a></li>
        </ul>
      </dd>
    </dl>
    <dl class="clinic">
      <dt>
        <div class="ico"><i></i><sub title="<?php echo $lang['dashboard_wel_count_clic_add'];?>"><span><em id="statistics_clic"></em></span></sub></div>
        <h3><?php echo $lang['nc_clic'];?></h3>
        <h5><?php echo $lang['dashboard_wel_clic_des'];?></h5>
      </dt>
      <dd>
        <ul>
          <li class="w33pre none"><a href="index.php?act=clic&op=clic_joinin">clinic view<sub><em id="statistics_clic_joinin">0</em></sub></a></li>
          <li class="w33pre none"><a href="index.php?act=clic&op=clic&clic_type=expired"><?php echo $lang['dashboard_wel_expired'];?><sub><em id="statistics_clic_expired">0</em></sub></a></li>
          <li class="w34pre none"><a href="index.php?act=clic&op=clic&clic_type=expire"><?php echo $lang['dashboard_wel_expire'];?><sub><em id="statistics_clic_expire">0</em></sub></a></li>
        </ul>
      </dd>
    </dl>
    <dl class="doctors">
      <dt>
        <div class="ico"><i></i><sub title="<?php echo $lang['dashboard_wel_total_doctors'];?>"><span><em id="statistics_doctors"></em></span></sub></div>
        <h3><?php echo $lang['nc_doctors'];?></h3>
        <h5><?php echo $lang['dashboard_wel_doctors_des'];?></h5>
      </dt>
      <dd>
        <ul>
          <li class="w33pre normal"><a href="index.php?act=doctors&op=doctors"><?php echo $lang['dashboard_wel_new_add'];?><sub title="<?php echo $lang['dashboard_wel_count_doctors'];?>"><em id="statistics_week_add_doc"></em></sub></a></li>
          <li class="w33pre none"><a href="<?php echo urlAdmin('doctors','doctors',array('type'=>'waitverify', 'search_verify' => 10));?>">doctor view<sub><em id="statistics_doc_verify">0</em></sub></a></li>
          <li class="w33pre none"><a href="index.php?act=inform&op=inform_list"><?php echo $lang['dashboard_wel_inform'];?><sub><em id="statistics_inform_list">0</em></sub></a></li>
          <!-- <li class="w25pre none"><a href="index.php?act=brand&op=brand_apply"><?php echo $lang['dashboard_wel_brnad_applay'];?><sub><em id="statistics_brand_apply">0</em></sub></a></li> -->
        </ul>
      </dd>
    </dl>
    <dl class="trade">
      <dt>
        <div class="ico"><i></i><sub title="<?php echo $lang['dashboard_wel_total_appointment'];?>"><span><em id="statistics_appointment"></em></span></sub></div>
        <h3><?php echo $lang['nc_trade'];?></h3>
        <h5><?php echo $lang['dashboard_wel_trade_des'];?></h5>
      </dt>
      <dd>
        <ul>
          <li class="w25pre none"><a href="index.php?act=refund&op=refund_manage">refund<sub><em id="statistics_refund"></em></sub></a></li>
          <li class="w25pre none"><a href="index.php?act=return&op=return_manage">reback<sub><em id="statistics_return"></em></sub></a></li>
          <li class="w25pre none"><a href="index.php?act=complain&op=complain_new_list"><?php echo $lang['dashboard_wel_complain'];?><sub><em id="statistics_complain_new_list">0</em></sub></a></li>
          <li class="w25pre none"><a href="index.php?act=complain&op=complain_handle_list"><?php echo $lang['dashboard_wel_complain_handle'];?><sub><em id="statistics_complain_handle_list">0</em></sub></a></li>
        </ul>
      </dd>
    </dl>
    <!-- <dl class="operation">
      <dt>
        <div class="ico"><i></i></div>
        <h3><?php echo $lang['nc_operation'];?></h3>
        <h5><?php echo $lang['dashboard_wel_stat_des'];?></h5>
      </dt>
      <dd>
        <ul>
          <li class="w25pre none"><a href="index.php?act=groupbuy&op=groupbuy_verify_list"><?php echo $lang['dashboard_wel_groupbuy'];?><sub><em id="statistics_groupbuy_verify_list">0</em></sub></a></li>
          <li class="w25pre none"><a href="index.php?act=pointappointment&op=pointappointment_list"><?php echo $lang['dashboard_wel_point_appointment'];?><sub><em id="statistics_points_appointment">0</em></sub></a></li>
          <li class="w25pre none"><a href="index.php?act=bill&op=show_statis&os_month=&query_clic=&bill_state=2"><?php echo $lang['dashboard_wel_check_billno'];?><sub><em id="statistics_check_billno">0</em></sub></a></li>
          <li class="w25pre none"><a href="index.php?act=bill&op=show_statis&os_month=&query_clic=&bill_state=3"><?php echo $lang['dashboard_wel_pay_billno'];?><sub><em id="statistics_pay_billno">0</em></sub></a></li>
        </ul>
      </dd>
    </dl> -->
    <?php if (C('cms_isuse') != null) {?>
    <dl class="cms">
      <dt>
        <div class="ico"><i></i></div>
        <h3>CMS</h3>
        <h5>Information articles/pictorials/member reviews</h5>
      </dt>
      <dd>
        <ul>
          <li class="w33pre none"><a href="<?php echo urlAdmin('cms_article', 'cms_article_list_verify');?>">art-review<sub><em id="statistics_cms_article_verify">0</em></sub></a></li>
          <li class="w33pre none"><a href="<?php echo urlAdmin('cms_picture', 'cms_picture_list_verify');?>">Pic-review<sub><em id="statistics_cms_picture_verify">0</em></sub></a></li>
          <li class="w34pre none"><a href="<?php echo urlAdmin('cms_comment', 'comment_manage');?>">comments<sub></sub></a></li>
        </ul>
      </dd>
    </dl>
    <?php }?>
    <!-- <?php if (C('circle_isuse') != null) {?>
    <dl class="circle">
      <dt>
        <div class="ico"><i></i></div>
        <h3>圈子</h3>
        <h5>申请开通/圈内话题及举报</h5>
      </dt>
      <dd>
        <ul>
          <li class="w33pre none"><a href="<?php echo urlAdmin('circle_manage', 'circle_verify');?>">圈子申请<sub><em id="statistics_circle_verify">0</em></sub></a></li>
          <li class="w33pre none"><a href="<?php echo urlAdmin('circle_theme', 'theme_list');?>">话题</a></li>
          <li class="w34pre none"><a href="<?php echo urlAdmin('circle_inform', 'inform_list');?>">举报</a></li>
        </ul>
      </dd>
    </dl>
    <?php }?> -->
    <!-- <?php if (C('microclinic_isuse') != null){?>
    <dl class="microclinic">
      <dt>
        <div class="ico"><i></i></div>
        <h3>微商城</h3>
        <h5>随心看/个人秀/店铺街</h5>
      </dt>
      <dd>
        <ul>
          <li class="w33pre none"><a href="<?php echo urlAdmin('microclinic', 'doctors_manage');?>">随心看</a></li>
          <li class="w33pre none"><a href="<?php echo urlAdmin('circle_theme', 'theme_list');?>">个人秀</a></li>
          <li class="w34pre none"><a href="<?php echo urlAdmin('circle_inform', 'inform_list');?>">店铺街</a></li>
        </ul>
      </dd>
    </dl>
    <?php }?> -->
    <dl class="system">
      <dt>
        <div class="ico"><i></i><a id="UPDATE" style="visibility:hidden;" title="" target="_blank" href="javascript:void(0);"><sub><span>new</em></span></sub></a></div>
        <h3><?php echo $lang['dashboard_welcome_sys_info'];?></h3>
        <div id="system-info">
          <ul>
            <li>ClinicSys <?php echo $lang['dashboard_welcome_version'];?><span><?php echo $output['statistics']['clinic_version'];?></span></li>
            <!-- <li><?php echo $lang['dashboard_welcome_install_date'];?><span><?php echo $output['statistics']['setup_date'];?></span></li>
            <li><?php echo $lang['dashboard_welcome_server_os'];?><span><?php echo $output['statistics']['os'];?></span></li>
            <li>WEB <?php echo $lang['dashboard_welcome_server'];?><span><?php echo $output['statistics']['web_server'];?></span></li> -->
            <li>PHP <?php echo $lang['dashboard_welcome_version'];?><span><?php echo $output['statistics']['php_version'];?></span></li>
            <li>MYSQL <?php echo $lang['dashboard_welcome_version'];?><span><?php echo $output['statistics']['sql_version'];?></span></li>
          </ul>
        </div>
      </dt>
      <!-- <dd>
        <ul>
          <li class="w50pre none"><a href="http://www.clinicnc.net" target="_blank">官方网站<sub></sub></a></li>
          <li class="w50pre none"><a href="http://bbs.clinicnc.net" target="_blank">官方论坛<sub></sub></a></li>
        </ul>
      </dd> -->
    </dl>
    <div class=" clear"></div>
  </div>
</div>
<script type="text/javascript">
var normal = ['week_add_member','week_add_doc'];
var work = ['clic_joinin','clic_expired','clic_expire','brand_apply','cashlist','groupbuy_verify_list','points_appointment','complain_new_list','complain_handle_list', 'doc_verify','inform_list','refund','return','cms_article_verify','cms_picture_verify','circle_verify','check_billno','pay_billno'];
$(document).ready(function(){
	$.getJSON("index.php?act=dashboard&op=statistics", function(data){
	  $.each(data, function(k,v){
		  $("#statistics_"+k).html(v);
		  if (v!= 0 && $.inArray(k,work) !== -1){
			$("#statistics_"+k).parent().parent().parent().removeClass('none').addClass('high');
		  }else if (v == 0 && $.inArray(k,normal) !== -1){
			$("#statistics_"+k).parent().parent().parent().removeClass('normal').addClass('none');
		  }
	  });
	});
	//自定义滚定条
	$('#system-info').perfectScrollbar();
});
</script>
<script type="text/javascript" charset="utf-8" src="http://www.clinicnc.net/update/update2014.js"></script>
