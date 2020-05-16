<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="clear">&nbsp;</div>
<div id="footer">
  <p><a href="<?php echo SHOP_SITE_URL;?>"><?php echo $lang['nc_index'];?></a>
    <?php if(!empty($output['nav_list']) && is_array($output['nav_list'])){?>
    <?php foreach($output['nav_list'] as $nav){?>
    <?php if($nav['nav_location'] == '2'){?>
    | <a  <?php if($nav['nav_new_open']){?>target="_blank" <?php }?>href="<?php switch($nav['nav_type']){
    	case '0':echo $nav['nav_url'];break;
    	case '1':echo urlShop('search', 'index', array('cate_id'=>$nav['item_id']));break;
    	case '2':echo urlShop('article', 'article',array('ac_id'=>$nav['item_id']));break;
    	case '3':echo urlShop('activity', 'index',array('activity_id'=>$nav['item_id']));break;
    }?>"><?php echo $nav['nav_title'];?></a>
    <?php }?>
    <?php }?>
    <?php }?>
  </p>
  <center><div style=line-height:21px>资源提供：<a href=http://www.gope.cn target=_blank><font color=red>狗扑源码社区</font></a>
<br>&nbsp;
<a href=http://www.gope.cn target=_blank>狗扑源码社区</a> | <a href=http://www.gope.cn target=_blank>极品商业源码</a> | <a href=http://zhuji.gope.cn target=_blank>狗扑源码社区空间、域名</a> | <a href=http://www.gope.cn/ target=_blank>90G韩国豪华商业模版</a> | <a href=http://www.gope.cn/ target=_blank>站长工具箱</a>
<br>
更多精品商业资源，就在<a href=http://www.gope.cn target=_blank>狗扑源码社区</a></font>
</div></center>
  <?php echo html_entity_decode($GLOBALS['setting_config']['statistics_code'],ENT_QUOTES); ?> </div>
<?php if (C('debug') == 1){?>
<div id="think_page_trace" class="trace">
  <fieldset id="querybox">
    <legend><?php echo $lang['nc_debug_trace_title'];?></legend>
    <div>
      <?php print_r(Tpl::showTrace());?>
    </div>
  </fieldset>
</div>
<?php }?>
</body>
</html>
