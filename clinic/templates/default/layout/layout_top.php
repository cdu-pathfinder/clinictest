<?php defined('InclinicNC') or exit('Access Invalid!');?>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="public-top-layout w">
  <div class="topbar wrapper">  
    <div class="user-entry">
    <?php if($_SESSION['is_login'] == '1'){?>
      <?php echo $lang['nc_hello'];?><span><a href="<?php echo urlclinic('member_snsindex');?>"><?php echo $_SESSION['member_name'];?></a></span><?php echo $lang['nc_comma'],$lang['welcome_to_site'];?>
      <a href="<?php echo clinic_SITE_URL;?>"  title="<?php echo $lang['homepage'];?>" alt="<?php echo $lang['homepage'];?>"><span><?php echo $GLOBALS['setting_config']['site_name']; ?></span></a>
      <span>[<a href="<?php echo urlclinic('login','logout');?>"><?php echo $lang['nc_logout'];?></a>]</span>
    <?php }else{?>
      <?php echo $lang['nc_hello'].$lang['nc_comma'].$lang['welcome_to_site'];?>
      <a href="<?php echo clinic_SITE_URL;?>" title="<?php echo $lang['homepage'];?>" alt="<?php echo $lang['homepage'];?>"><?php echo $GLOBALS['setting_config']['site_name']; ?></a>
       <span>[<a href="<?php echo urlclinic('login');?>"><?php echo $lang['nc_login'];?></a>]</span>
        <span>[<a href="<?php echo urlclinic('login','register');?>"><?php echo $lang['nc_register'];?></a>]</span>
    <?php }?><span class="seller-login"><a href="<?php echo urlclinic('seller_login','show_login');?>" target="_blank" title="login Clinic management center"><i class="icon-signin"></i>Clinic management center</a></span></div>
    
    <div class="quick-menu">
      <dl>
        <dt><a href="<?php echo clinic_SITE_URL;?>/index.php?act=member_appointment">my appointment</a><i></i></dt>
        <dd>
          <ul>
            <li><a href="<?php echo clinic_SITE_URL;?>/index.php?act=member_appointment&state_type=state_new">To pay</a></li>
            <li><a href="<?php echo clinic_SITE_URL;?>/index.php?act=member_appointment&state_type=state_send">To confirm</a></li>
            <li><a href="<?php echo clinic_SITE_URL;?>/index.php?act=member_appointment&state_type=state_noeval">To evaluate</a></li>
          </ul>
        </dd>
      </dl>
      <dl>
        <dt><a href="<?php echo clinic_SITE_URL;?>/index.php?act=member_favorites&op=fglist"><?php echo $lang['nc_favorites'];?></a><i></i></dt>
        <dd>
          <ul>
            <li><a href="<?php echo clinic_SITE_URL;?>/index.php?act=member_favorites&op=fglist">doctors</a></li>
            <li><a href="<?php echo clinic_SITE_URL;?>/index.php?act=member_favorites&op=fslist">clinics</a></li>
          </ul>
        </dd>
      </dl>
      <dl>
        <dt>service<i></i></dt>
        <dd>
          <ul>
            <li><a href="<?php echo urlclinic('article', 'article', array('ac_id' => 2));?>">help center</a></li>
            <li><a href="<?php echo urlclinic('article', 'article', array('ac_id' => 5));?>">back-serve</a></li>
            <li><a href="<?php echo urlclinic('article', 'article', array('ac_id' => 6));?>">serve-center</a></li>
          </ul>
        </dd>
      </dl>
      <?php 
      if(!empty($output['nav_list']) && is_array($output['nav_list'])){
	      foreach($output['nav_list'] as $nav){
	      if($nav['nav_location']<1){
	      	$output['nav_list_top'][] = $nav;
	      }
	      }
      }
      if(!empty($output['nav_list_top']) && is_array($output['nav_list_top'])){
      	?>
      <dl>
        <dt>站点导航<i></i></dt>
        <dd>
          <ul>
              <?php foreach($output['nav_list_top'] as $nav){?>
              <li><a 
        <?php 
        if($nav['nav_new_open']) {
            echo ' target="_blank"'; 
        }
        echo ' href="'; 
        switch($nav['nav_type']) {
        	case '0':echo $nav['nav_url'];break;
        	case '1':echo urlclinic('search', 'index', array('cate_id'=>$nav['item_id']));break;
        	case '2':echo urlclinic('article', 'article', array('ac_id'=>$nav['item_id']));break;
        	case '3':echo urlclinic('activity', 'index', array('activity_id'=>$nav['item_id']));break;
        }
        echo '"'; 
        ?>><?php echo $nav['nav_title'];?></a></li>
              <?php }?>
          </ul>
        </dd>
      </dl>
      <?php }?>
    </div>
  </div>
</div>
<script type="text/javascript">
$(function(){
	$(".quick-menu dl").hover(function() {
		$(this).addClass("hover");
	},
	function() {
		$(this).removeClass("hover");
	});

});
</script>
