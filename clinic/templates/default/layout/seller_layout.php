<?php defined('InclinicNC') or exit('Access Invalid!');?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>商家中心</title>
<link href="<?php echo clinic_TEMPLATES_URL?>/css/base.css" rel="stylesheet" type="text/css">
<link href="<?php echo clinic_TEMPLATES_URL?>/css/seller_center.css" rel="stylesheet" type="text/css">
<link href="<?php echo clinic_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
  <link rel="stylesheet" href="<?php echo clinic_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome-ie7.min.css">
<![endif]-->
<script>
var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';var _CHARSET = '<?php echo strtolower(CHARSET);?>';var SITEURL = '<?php echo clinic_SITE_URL;?>';var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';var clinic_RESOURCE_SITE_URL = '<?php echo clinic_RESOURCE_SITE_URL;?>';var clinic_TEMPLATES_URL = '<?php echo clinic_TEMPLATES_URL;?>';</script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo clinic_RESOURCE_SITE_URL;?>/js/seller.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/waypoints.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/member.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/html5shiv.js"></script>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/respond.min.js"></script>
<![endif]-->
<!--[if IE 6]>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/IE6_MAXMIX.js"></script>
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
<div id="append_parent"></div>
<div id="ajaxwaitid"></div><?php if (!empty($output['clic_closed'])) { ?>
  <div class="clic-closed"><i class="icon-warning-sign"></i>
    <dl>
      <dt>your clinic has been closed</dt>
      <dd>closed reason：<?php echo $output['clic_close_info'];?></dd>
      <dd>During this period, your clic and doctors will not be accessible. If you have any objection or complaint, please contact the platform management in time.</dd>
    </dl>
  </div>
  <?php } ?>
<header class="ncsc-head-layout w">
  <div class="wrapper">
    <div class="ncsc-admin">
      <dl class="ncsc-admin-info">
        <dt class="admin-avatar"><img src="<?php echo getMemberAvatarForID($_SESSION['member_id']);?>" width="32" class="pngFix" alt=""/></dt>
        <dd class="admin-permission">current user</dd>
        <dd class="admin-name"><?php echo $_SESSION['seller_name'];?></dd>
      </dl>
      <div class="ncsc-admin-function"><a href="<?php echo urlclinic('show_clic', 'index', array('clic_id'=>$_SESSION['clic_id']), $output['clic_info']['clic_domain']);?>" target="_blank" title="go to clinic" ><i class="icon-home"></i></a><a href="<?php echo urlclinic('home', 'message');?>" title="message from platform" class="pr" target="_blank"><i class="icon-envelope-alt"></i><em><?php echo $output['message_num'];?></em></a><a href="<?php echo urlclinic('home', 'passwd');?>" title="change password" target="_blank"><i class="icon-wrench"></i></a><a href="<?php echo urlclinic('seller_logout', 'logout');;?>" title="quit"><i class="icon-signout"></i></a></div>
    </div>
    <div class="center-logo">
        <a href="<?php echo clinic_SITE_URL;?>" target="_blank"><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('seller_center_logo');?>" class="pngFix" alt=""/></a>
      <h1>clinic center</h1>
    </div>
    <div class="index-search-container">
      <div class="index-sitemap"><a href="javascript:void(0);">Navigation manage <i class="icon-angle-down"></i></a>
        <div class="sitemap-menu-arrow"></div>
        <div class="sitemap-menu">
          <div class="title-bar">
            <h2>
            <i class="icon-sitemap"></i>manage Nav<em>Tip: add features you often use to the home page sidebar for easy access.</em>
            </h5>
            <span id="closeSitemap" class="close">X</span></div>
          <div id="quicklink_list" class="content">
            <?php if(!empty($output['menu']) && is_array($output['menu'])) {?>
            <?php foreach($output['menu'] as $menu_value) {?>
            <dl>
              <dt><?php echo $menu_value['name'];?></dt>
              <?php if(!empty($menu_value['child']) && is_array($menu_value['child'])) {?>
              <?php foreach($menu_value['child'] as $submenu_value) {?>
              <dd <?php if(!empty($output['seller_quicklink'])) {echo in_array($submenu_value['act'], $output['seller_quicklink'])?'class="selected"':'';}?>><i nctype="btn_add_quicklink" data-quicklink-act="<?php echo $submenu_value['act'];?>" class="icon-check" title="Add as common function menu"></i><a href="index.php?act=<?php echo $submenu_value['act'];?>&op=<?php echo $submenu_value['op'];?>"> <?php echo $submenu_value['name'];?> </a></dd>
              <?php } ?>
              <?php } ?>
            </dl>
            <?php } ?>
            <?php } ?>
          </div>
        </div>
      </div>
      <div class="search-bar">
        <form method="get" target="_blank">
          <input type="hidden" name="act" value="search">
          <input type="text" nctype="search_text" name="keyword" placeholder="doctor research" class="search-input-text">
          <input type="submit" nctype="search_submit" class="search-input-btn pngFix" value="">
        </form>
      </div>
    </div>
    <nav class="ncsc-nav">
      <dl class="<?php echo $output['current_menu']['model'] == 'index'?'current':'';?>">
        <dt><a href="index.php?act=seller_center&op=index">home</a></dt>
        <dd class="arrow"></dd>
      </dl>
      <?php if(!empty($output['menu']) && is_array($output['menu'])) {?>
      <?php foreach($output['menu'] as $key => $menu_value) {?>
      <dl class="<?php echo $output['current_menu']['model'] == $key?'current':'';?>">
        <dt><a href="index.php?act=<?php echo $menu_value['child'][0]['act'];?>&op=<?php echo $menu_value['child'][0]['op'];?>"><?php echo $menu_value['name'];?></a></dt>
        <dd>
          <ul>
            <?php if(!empty($menu_value['child']) && is_array($menu_value['child'])) {?>
            <?php foreach($menu_value['child'] as $submenu_value) {?>
            <li> <a href="index.php?act=<?php echo $submenu_value['act'];?>&op=<?php echo $submenu_value['op'];?>"> <?php echo $submenu_value['name'];?> </a> </li>
            <?php } ?>
            <?php } ?>
          </ul>
        </dd>
        <dd class="arrow"></dd>
      </dl>
      <?php } ?>
      <?php } ?>
    </nav>
  </div>
</header>
<div class="ncsc-layout wrapper">

  <div id="layoutLeft" class="ncsc-layout-left">
    <div id="sidebar" class="sidebar">
      <div class="column-title" id="main-nav"><span class="ico-<?php echo $output['current_menu']['model'];?>"></span>
        <h2><?php echo $output['current_menu']['model_name'];?></h2>
      </div>
      <div class="column-menu">
        <ul id="seller_center_left_menu">
          <?php if(!empty($output['left_menu']) && is_array($output['left_menu'])) {?>
          <?php foreach($output['left_menu'] as $submenu_value) {?>
          <li <?php echo $_GET['act'] == 'seller_center'?"id='quicklink_".$submenu_value['act']."'":'';?>class="<?php echo $submenu_value['act'] == $_GET['act']?'current':'';?>"> <a href="index.php?act=<?php echo $submenu_value['act'];?>&op=<?php echo $submenu_value['op'];?>"> <?php echo $submenu_value['name'];?> </a> </li>
          <?php } ?>          
          <?php } else { ?>
          <?php if ($_GET['act'] == 'seller_center') { ?>
          <?php } ?>
          <?php } ?><div class="add-quickmenu"><a href="javascript:void(0);"><i class="icon-plus"></i>Add menu of common functions</a></div>
        </ul>
      </div>
    </div>
  </div>
  <div id="layoutRight" class="ncsc-layout-right">
    <div class="ncsc-path"><i class="icon-desktop"></i>clinic manage center<i class="icon-angle-right"></i><?php echo $output['current_menu']['model_name'];?><i class="icon-angle-right"></i><?php echo $output['current_menu']['name'];?></div>
    <div class="main-content" id="mainContent">
      <?php
        require_once($tpl_file);
    ?>
    </div>
  </div>
</div>
<script type="text/javascript">
</script>
<script type="text/javascript">
$(document).ready(function(){
    //添加删除快捷操作
    $('[nctype="btn_add_quicklink"]').on('click', function() {
        var $quicklink_item = $(this).parent();
        var item = $(this).attr('data-quicklink-act');
        if($quicklink_item.hasClass('selected')) {
            $.post("<?php echo urlclinic('seller_center', 'quicklink_del');?>", { item: item }, function(data) {
                $quicklink_item.removeClass('selected');
                $('#quicklink_' + item).hide('fadeOut');
            }, "json");
        } else {
            var count = $('#quicklink_list').find('dd.selected').length;
            if(count >= 8) {
                showError('Add up to 8 shortcuts');
            } else {
                $.post("<?php echo urlclinic('seller_center', 'quicklink_add');?>", { item: item }, function(data) {
                    $quicklink_item.addClass('selected');
                    <?php if ($_GET['act'] == 'seller_center') { ?>
                        var $link = $quicklink_item.find('a');
                        var menu_name = $link.text();
                        var menu_link = $link.attr('href');
                        var menu_item = '<li id="quicklink_' + item + '"><a href="' + menu_link + '">' + menu_name + '</a></li>';
                        $(menu_item).appendTo('#seller_center_left_menu').hide().fadeIn();
                    <?php } ?>
                }, "json");
            }
        }
    });
    //浮动导航  waypoints.js
    $("#sidebar,#mainContent").waypoint(function(event, direction) {
        $(this).parent().toggleClass('sticky', direction === "down");
        event.stopPropagation();
        });
    });
    // 搜索商品不能为空
    $('input[nctype="search_submit"]').click(function(){
        if ($('input[nctype="search_text"]').val() == '') {
            return false;
        }
    });
</script>
<?php require_once template('footer');?>
<div id="tbox"><i id="gotop" class="icon-chevron-up" title="<?php echo $lang['go_top'];?>" style="display:none;"></i> </div>
</body>
</html>
