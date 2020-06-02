<?php defined('InclinicNC') or exit('Access Invalid!');?>
<!doctype html>
<html>
<head>
<meta content="IE=9" http-equiv="X-UA-Compatible">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo $output['html_title'];?></title>
<meta name="keywords" content="<?php echo $output['seo_keywords']; ?>" />
<meta name="description" content="<?php echo $output['seo_description']; ?>" />
<meta name="author" content="clinicNC">
<meta name="copyright" content="clinicNC Inc. All Rights Reserved">
<link href="<?php echo clinic_TEMPLATES_URL;?>/css/base.css" rel="stylesheet" type="text/css">
<link href="<?php echo clinic_TEMPLATES_URL;?>/css/clinic.css" rel="stylesheet" type="text/css">
<link href="<?php echo clinic_TEMPLATES_URL;?>/clic/style/<?php echo $output['clic_info']['clic_theme'];?>/style.css" rel="stylesheet" type="text/css">
<!--[if IE 6]><style type="text/css">
body {_behavior: url(<?php echo clinic_TEMPLATES_URL;?>/css/csshover.htc);}
</style>
<![endif]-->
<script>
COOKIE_PRE = '<?php echo COOKIE_PRE;?>';_CHARSET = '<?php echo strtolower(CHARSET);?>';SITEURL = '<?php echo clinic_SITE_URL;?>';var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';var clinic_TEMPLATES_URL = '<?php echo clinic_TEMPLATES_URL;?>';
</script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/member.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/clinic.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/qtip/jquery.qtip.min.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/qtip/jquery.qtip.min.css" rel="stylesheet" type="text/css">
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/html5shiv.js"></script>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/respond.min.js"></script>
<![endif]-->
<!--[if IE 6]>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/IE6_PNG.js"></script>
<script>
DD_belatedPNG.fix('.pngFix,.pngFix:hover');
</script>
<script>
// <![CDATA[
if((window.navigator.appName.toUpperCase().indexOf("MICROSOFT")>=0)&&(document.execCommand))
try{
document.execCommand("BackgroundImageCache", false, true);
   }
catch(e){}
// ]]>
</script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/IE6_MAXMIX.js"></script>
<![endif]-->
</head>
<body>
<?php require_once template('layout/layout_top');?>
<header id="header">
  <div class="layout">
    <h1 id="clinic-logo">
      <a class="mall-home" href="<?php echo urlclinic('show_clic', 'index', array('clic_id'=>$output['clic_info']['clic_id']));?>" title="<?php echo $GLOBALS['setting_config']['site_name']; ?>"><img src="<?php echo getclicLogo($output['clic_info']['clic_label']);?>"  alt="<?php echo $output['clic_info']['clic_name'];?>" title="<?php echo $output['clic_info']['clic_name'];?>" /></a>
    </h1>
    <div class="clinic-head-info" id="jsddm">
      
      <div class="search" id="clinic-info">
        <form method="get" action="<?php echo clinic_SITE_URL.'/';?>index.php" name="formSearch" id="formSearch">
          <input name="act" id="search_act" value="search" type="hidden" />
          <input name="keyword" id="keyword" type="text" class="ncs-search-input-text" value="<?php echo $_GET['keyword'];?>" x-webkit-speech lang="zh-CN" onwebkitspeechchange="foo()" x-webkit-grammar="builtin:search" placeholder="<?php echo $lang['nc_what_doctors'];?>" />
          <a href="javascript:void(0)" class="ncs-search-btn-mall" nctype="search_in_clinic"><span><?php echo $lang['nc_search_in_website'];?></span></a><a href="javascript:void(0)" class="ncs-search-btn-clinic" nctype="search_in_clic"><span><?php echo $lang['nc_search_in_clic'];?></span></a>
        </form>
      </div>
      <div class="service"><i></i><?php echo $lang['nc_service'];?><em></em>
        <div class="arrow"></div>
        <div class="sub">
          <?php include template('clic/callcenter');?>
        </div>
      </div>
      <!-- <div class="favorites"><i></i><?php echo $lang['nc_collect'];?><em></em>
        <div class="arrow"></div>
        <div class="sub">
          <div class="title-bar">
            <h3><?php echo $lang['nc_clinic_space'];?></h3>
          </div>
          <ul>
            <li><a href="javascript:collect_clic('<?php echo $output['clic_info']['clic_id'];?>','count','clic_collect')" class="btn"><i></i><?php echo $lang['nc_collect'];?></a></li>
            <li><a href="javascript:void(0);" nctype="clic_collect" class="no-url"><?php echo $output['clic_info']['clic_collect'];?></a><span><?php echo $lang['nc_collection_popularity'];?></span></li>
            <li><a href="index.php?act=clic_snshome&sid=<?php echo $output['clic_info']['clic_id'];?>" target="_blank">0</a><span><?php echo $lang['nc_clic_the_dynamic'];?></span></li>
            <li><a href="javascript:void(0);" class="share" nctype="share_clic"></a><span><?php echo $lang['nc_share'];?></span></li>
          </ul>
        </div>
      </div> -->
    </div>
  </div>
</header>
<div class="background clearfix">
<div class="ncsl-nav">
  <div class="banner"><a href="<?php echo urlclinic('show_clic', 'index', array('clic_id'=>$output['clic_info']['clic_id']));?>" class="img">
    <?php if(!empty($output['clic_info']['clic_banner'])){?>
    <img src="<?php echo getclicLogo($output['clic_info']['clic_banner']);?>" alt="<?php echo $output['clic_info']['clic_name']; ?>" title="<?php echo $output['clic_info']['clic_name']; ?>" class="pngFix">
    <?php }else{?>
    <div class="ncs-default-banner pngFix"></div>
    <?php }?>
    </a></div>
  <nav id="nav" class="pngFix">
    <ul class="pngFix">
      <li class="<?php if($output['page'] == 'index'){?>active<?php }else{?>normal<?php }?>"><a href="<?php echo urlclinic('show_clic', 'index', array('clic_id'=>$output['clic_info']['clic_id']));?>"><span><?php echo $lang['nc_clic_index'];?><i></i></span></a></li>
      <?php if($output['page'] == 'doctors'){?>
      <li class="active"><a href="JavaScript:void(0);"><span><?php echo $lang['nc_doctors_info'];?><i></i></span></a></li>
      <?php }?>
      <?php if($output['page'] == 'bundling'){?>
      <li class="active"><a href="JavaScript:void(0);"><span><?php echo $lang['nc_bundling'];?><i></i></span></a></li>
      <?php }?>
      <?php if(!empty($output['clic_navigation_list'])){
      		foreach($output['clic_navigation_list'] as $value){
                if($value['sn_if_show']) {
      			if($value['sn_url'] != ''){?>
      			<li class="<?php if($output['page'] == $value['sn_id']){?>active<?php }else{?>normal<?php }?>"><a href="<?php echo $value['sn_url']; ?>" <?php if($value['sn_new_open']){?>target="_blank"<?php }?>><span><?php echo $value['sn_title'];?><i></i></span></a></li>
      			<?php }else{ ?>
                <li class="<?php if($output['page'] == $value['sn_id']){?>active<?php }else{?>normal<?php }?>"><a href="<?php echo urlclinic('show_clic', 'show_article', array('clic_id' => $output['clic_info']['clic_id'], 'sn_id' => $value['sn_id']));?>"><span><?php echo $value['sn_title'];?><i></i></span></a></li>
      <?php }}}} ?>
    </ul>
  </nav>
</div>
<?php require_once($tpl_file); ?>
</div>
<?php include template('footer');?>
<script type="text/javascript">
$(function(){
	$('a[nctype="search_in_clic"]').click(function(){
		if ($('#keyword').val() == '') {
			return false;
		}
		$('#search_act').val('show_clic');
		$('<input type="hidden" value="<?php echo $output['clic_info']['clic_id'];?>" name="clic_id" /> <input type="hidden" name="op" value="doctors_all" />').appendTo("#formSearch");
		$('#formSearch').submit();
	});
	$('a[nctype="search_in_clinic"]').click(function(){
		if ($('#keyword').val() == '') {
			return false;
		}
		$('#formSearch').submit();
	});
	$('#keyword').css("color","#999999");

	var clicTrends	= true;
	$('.favorites').mouseover(function(){
		var $this = $(this);
		if(clicTrends){
			$.getJSON('index.php?act=show_clic&op=ajax_clic_trend_count&clic_id=<?php echo $output['clic_info']['clic_id'];?>', function(data){
				$this.find('li:eq(2)').find('a').html(data.count);
				clicTrends = false;
			});
		}
	});

	$('a[nctype="share_clic"]').click(function(){
		<?php if ($_SESSION['is_login'] !== '1'){?>
		login_dialog();
		<?php } else {?>
		ajax_form('shareclic', '分享店铺', 'index.php?act=member_snsindex&op=shareclic_one&inajax=1&sid=<?php echo $output['clic_info']['clic_id'];?>');
		<?php }?>
	});
});
</script>
</body>
</html>
