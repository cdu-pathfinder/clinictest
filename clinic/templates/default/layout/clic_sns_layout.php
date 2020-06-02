<?php defined('InclinicNC') or exit('Access Invalid!');?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo ($lang['nc_member_path_'.$output['menu_sign']]==''?'':$lang['nc_member_path_'.$output['menu_sign']].'_').$output['html_title'];?></title>
<meta name="keywords" content="<?php echo C('site_keywords'); ?>" />
<meta name="description" content="<?php echo C('site_description'); ?>" />
<meta name="author" content="clinicNC">
<meta name="copyright" content="clinicNC Inc. All Rights Reserved">
<link href="<?php echo clinic_TEMPLATES_URL;?>/css/base.css" rel="stylesheet" type="text/css">
<link href="<?php echo clinic_TEMPLATES_URL;?>/css/sns_clic.css" rel="stylesheet" type="text/css">
<!--[if IE 6]><style type="text/css">
body {_behavior: url(<?php echo clinic_TEMPLATES_URL;?>/css/csshover.htc);}
</style>
<![endif]-->
<script type="text/javascript">
var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';var _CHARSET = '<?php echo strtolower(CHARSET);?>';var SITEURL = '<?php echo clinic_SITE_URL;?>';var MAX_RECORDNUM = <?php echo $output['max_recordnum'];?>;var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';var clinic_TEMPLATES_URL = '<?php echo clinic_TEMPLATES_URL;?>';
</script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.charCount.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/member.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/sns.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/sns_clic.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/smilies/smilies_data.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/smilies/smilies.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.caretInsert.js" charset="utf-8"></script>
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/html5shiv.js"></script>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/respond.min.js"></script>
<![endif]-->
<!--[if IE 6]>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/IE6_PNG.js"></script>
<script>
DD_belatedPNG.fix('.pngFix');
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
<![endif]-->

</head>
<body>
<?php require_once template('layout/layout_top');?>
<header id="header" class="pngFix">
  <div class="wrapper">
    <h1 id="logo" title="<?php echo C('site_name'); ?>"><a href="<?php echo clinic_SITE_URL;?>"><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('member_logo'); ?>" alt="<?php echo C('site_name'); ?>" class="pngFix"></a></h1>
  </div>
</header>
<div id="container" class="ssns-layout-bg">
  <div class="ssns-layout">
    <div class="sidebar">
      <div class="keynote">
        <h3><?php echo $lang['clic_sns_popularity'].$lang['nc_colon'].$output['clic_info']['clic_collect'];?></h3>
      </div>
      <div class="clic-intro">
        <h3><?php echo $lang['nc_clic_information'];?></h3>
        <dl>
          <dt><?php echo $lang['nc_srore_location'];?></dt>
          <dd><?php echo $output['clic_info']['area_info'];?></dd>
        </dl>
        <dl class="tc">
          <a href="<?php echo urlclinic('show_clic', 'index', array('clic_id'=>$output['clic_info']['clic_id']));?>" class="btn"><?php echo $lang['clic_sns_enter_the_clic'];?></a>
        </dl>
        <dl>
          <dt><?php echo $lang['clic_sns_clinickeeper'].$lang['nc_colon']?></dt>
          <dd><?php echo $output['clic_info']['member_name'];?><em member_id="<?php echo $output['clic_info']['member_id'];?>"></em>
             <?php if(!empty($output['clic_info']['clic_qq'])){?>
               <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $output['clic_info']['clic_qq'];?>&site=qq&menu=yes" title="QQ: <?php echo $output['clic_info']['clic_qq'];?>"><img bappointment="0" src="http://wpa.qq.com/pa?p=2:<?php echo $output['clic_info']['clic_qq'];?>:52"/></a>
             <?php }?>
             <?php if(!empty($output['clic_info']['clic_ww'])){?>
               <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&amp;uid=<?php echo $output['clic_info']['clic_ww'];?>&site=cntaobao&s=1&charset=<?php echo CHARSET;?>" ><img bappointment="0" src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo $output['clic_info']['clic_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" alt="<?php echo $lang['nc_message_me'];?>"/></a>
             <?php }?>
          </dd>
        </dl>
        <dl>
          <dt><?php echo $lang['nc_doctors_amount'];?></dt>
          <dd><?php echo $output['clic_info']['doctors_count'];?></dd>
        </dl>
        <dl>
          <dt><?php echo $lang['clic_sns_main_operate'].$lang['nc_colon'];?></dt>
          <dd><?php echo $output['clic_info']['clic_zy'];?></dd>
        </dl>
        <dl>
          <dt><?php echo $lang['clic_sns_share'].$lang['nc_colon'];?></dt>
          <dd><a nc_type="shareclic" data-param='{"sid":"<?php echo $output['clic_info']['clic_id'];?>"}' href="javascript:void(0);"><?php echo $lang['clic_sns_share_clic'];?></a></dd>
        </dl>
      </div>
    </div>
    <div class="left-content">
      <div class="clic-info">
          <div class="picture"><span class="thumb size160"><i></i><img src="<?php echo getclicLogo($output['clic_info']['clic_label']);?>" onload="javascript:DrawImage(this,160,160);" /></span></div>
        <dl class="clic-name">
          <dt>
            <h2><?php echo $output['clic_info']['clic_name'];?></h2>
            <span>
              <a class="btn" href="javascript:collect_clic('<?php echo $output['clic_info']['clic_id'];?>','count','clic_collect')"><?php echo $lang['clic_sns_will_collect'];?></a>
            </span>
          </dt>
          <dd>
          <span id="sCopyContent">
          <?php echo urlclinic('show_clic', 'index', array('clic_id'=>$output['clic_info']['clic_id']));?>
          </span>
          	<a href="javascript:void(0);" id="sCopy"><?php echo $lang['clic_sns_copy'];?></a>
          </dd>
        </dl>
      </div>
      <div class="clic-sns-tab">
        <ul>
          <li><a href="index.php?act=clic_snshome&sid=<?php echo $output['clic_info']['clic_id'];?>" <?php if($_GET['type'] == ''){?>class="selected"<?php }?>><?php echo $lang['clic_sns_all_trends'];?></a></li>
          <li><a href="index.php?act=clic_snshome&sid=<?php echo $output['clic_info']['clic_id'];?>&type=promotion" <?php if($_GET['type'] == 'promotion'){?>class="selected"<?php }?>><?php echo $lang['clic_sns_sales_promotion'];?></a></li>
          <li><a href="index.php?act=clic_snshome&sid=<?php echo $output['clic_info']['clic_id'];?>&type=new" <?php if($_GET['type'] == 'new'){?>class="selected"<?php }?>><?php echo $lang['clic_sns_new_doctors'];?></a></li>
          <li><a href="index.php?act=clic_snshome&sid=<?php echo $output['clic_info']['clic_id'];?>&type=hotsell" <?php if($_GET['type'] == 'hotsell'){?>class="selected"<?php }?>><?php echo $lang['clic_sns_hot_sale'];?></a></li>
          <li><a href="index.php?act=clic_snshome&sid=<?php echo $output['clic_info']['clic_id'];?>&type=recommend" <?php if($_GET['type'] == 'recommend'){?>class="selected"<?php }?>><?php echo $lang['clic_sns_recommended'];?></a></li>
        </ul>
      </div>
      <div class="main">
        <?php
		require_once($tpl_file);
		?>
      </div>
    </div>
    <div class="clear"></div>
  </div>
</div>
<?php
require_once template('footer');
?>
<script type="text/javascript" language="javascript">
$(function(){	
	$('#sCopy').click(function() {
        var copyContent = $("#sCopyContent").html();
        copyToClipBoard(copyContent);
    });
});

//复制到剪切板js代码
function copyToClipBoard(s) {
  //alert(s);
  if (window.clipboardData) {
      window.clipboardData.setData("Text", s);
      alert("<?php echo $lang['clic_sns_has_not_copied'];?>"+ "\n" + s);
  } else if (navigator.userAgent.indexOf("Opera") != -1) {
      window.location = s;
  } else {
      alert("<?php echo $lang['clic_sns_copy_error'];?>");
  }
}
</script>
</body>
</html>
