<?php defined('InclinicNC') or exit('Access Invalid!');?>

<div class="nc-s-c-s1 ncs-class-bar">
  <div class="title">
    <h4><?php echo $lang['nc_doctors_class'];?></h4>
  </div>
  <div class="content">
    <!-- <p><span><a href="<?php echo urlclinic('show_clic', 'doctors_all', array('clic_id' => $output['clic_info']['clic_id'], 'key' => '1', 'appointment' => '2'));?>"><?php echo $lang['nc_by_new'];?></a></span><span><a href="<?php echo urlclinic('show_clic', 'doctors_all', array('clic_id' => $output['clic_info']['clic_id'], 'key' => '2', 'appointment' => '2'));?>"><?php echo $lang['nc_by_price'];?></a></span><span><a href="<?php echo urlclinic('show_clic', 'doctors_all', array('clic_id' => $output['clic_info']['clic_id'], 'key' => '3', 'appointment' => '2'));?>"><?php echo $lang['nc_by_sale'];?></a></span><span><a href="<?php echo urlclinic('show_clic', 'doctors_all', array('clic_id' => $output['clic_info']['clic_id'], 'key' => '5', 'appointment' => '2'));?>"><?php echo $lang['nc_by_click'];?></a></span></p> -->
    <div class="ncs-search">
      <form id="" name="searchclinic" method="get" action="index.php" >
        <input type="hidden" name="act" value="show_clic" />
        <input type="hidden" name="op" value="doctors_all" />
        <input type="hidden" name="clic_id" value="<?php echo $output['clic_info']['clic_id'];?>" />
        <input type="text" class="text w120" name="keyword" placeholder="search from clinic">
        <a href="javascript:document.searchclinic.submit();" class="ncs-btn"><?php echo $lang['nc_search'];?></a>
      </form>
    </div>
    <ul class="ncs-submenu">
      <li><span class="ico-none"><em>-</em></span><a href="<?php echo urlclinic('show_clic', 'doctors_all', array('clic_id' => $output['clic_info']['clic_id']));?>"><?php echo $lang['nc_whole_doctors'];?></a></li>
      <?php if(!empty($output['doctors_class_list']) && is_array($output['doctors_class_list'])){?>
      <?php foreach($output['doctors_class_list'] as $value){?>
      <?php if(!empty($value['children']) && is_array($value['children'])){?>
      <li><span class="ico-none" onclick="class_list(this);" span_id="<?php echo $value['stc_id'];?>" style="cursor: pointer;"><em>-</em></span><a href="<?php echo urlclinic('show_clic', 'doctors_all', array('clic_id' => $output['clic_info']['clic_id'], 'stc_id' => $value['stc_id']));?>"><?php echo $value['stc_name'];?></a>
        <ul id="stc_<?php echo $value['stc_id'];?>">
          <?php foreach($value['children'] as $value1){?>
          <li><span class="ico-sub">&nbsp;</span><a href="<?php echo urlclinic('show_clic', 'doctors_all', array('clic_id' => $output['clic_info']['clic_id'], 'stc_id' => $value1['stc_id']));?>"><?php echo $value1['stc_name'];?></a></li>
          <?php }?>
        </ul>
      </li>
      <?php }else {?>
      <li> <span class="ico-none"><em>-</em></span><a href="<?php echo urlclinic('show_clic', 'doctors_all', array('clic_id' => $output['clic_info']['clic_id'], 'stc_id' => $value['stc_id']));?>"><?php echo $value['stc_name'];?></a></li>
      <?php }?>
      <?php }?>
      <?php }?>
    </ul>
    <div class="clear mb10"></div>
  </div>
</div>
<!-- <div class="nc-s-c-s1 ncs-top-bar">
  <div class="title">
    <h4><?php echo $lang['nc_doctors_rankings'];?></h4>
  </div>
  <div class="content">
    <ul class="ncs-top-tab pngFix">
      <li id="hot_sales_tab" class="current"><a href="<?php echo urlclinic('show_clic', 'doctors_all', array('clic_id' => $output['clic_info']['clic_id'], 'key' => '3', 'appointment' => '2'));?>"><?php echo $lang['nc_hot_doctors_rankings'];?></a></li>
      <li id="hot_collect_tab"><a href="<?php echo urlclinic('show_clic', 'doctors_all', array('clic_id' => $output['clic_info']['clic_id'], 'key' => '4', 'appointment' => '2'));?>"><?php echo $lang['nc_hot_collect_rankings'];?></a></li>
    </ul>
    <div id="hot_sales_list" class="ncs-top-panel">
      <?php if(is_array($output['hot_sales']) && !empty($output['hot_sales'])){?>
      <ol>
        <?php foreach($output['hot_sales'] as $val){?>
        <li>
          <dl>
            <dt><a href="<?php echo urlclinic('doctors', 'index',array('doctors_id'=>$val['doctors_id']));?>"><?php echo $val['doctors_name']?></a></dt>
            <dd class="doctors-pic"><a href="<?php echo urlclinic('doctors', 'index', array('doctors_id'=>$val['doctors_id']));?>"><span class="thumb size40"><i></i><img src="<?php echo thumb($val, 60);?>"  onload="javascript:DrawImage(this,40,40);"></span></a>
              <p><span class="thumb size100"><i></i><img src="<?php echo thumb($val, 240);?>" onload="javascript:DrawImage(this,100,100);" title="<?php echo $val['doctors_name']?>"><big></big><small></small></span></p>
            </dd>
            <dd class="price pngFix"><?php echo $val['doctors_price']?></dd>
            <dd class="selled pngFix"><?php echo $lang['nc_sell_out'];?><strong><?php echo $val['doctors_salenum'];?></strong><?php echo $lang['nc_bi'];?></dd>
          </dl>
        </li>
        <?php }?>
      </ol>
      <?php }?>
    </div>
    <div id="hot_collect_list" class="ncs-top-panel hide">
      <?php if(is_array($output['hot_collect']) && !empty($output['hot_collect'])){?>
      <ol>
        <?php foreach($output['hot_collect'] as $val){?>
        <li>
          <dl>
            <dt><a href="<?php echo urlclinic('doctors', 'index', array('doctors_id'=>$val['doctors_id']));?>"><?php echo $val['doctors_name']?></a></dt>
            <dd class="doctors-pic"><a href="<?php echo urlclinic('doctors', 'index', array('doctors_id'=>$val['doctors_id']));?>" title=""><span class="thumb size40"><i></i> <img src="<?php echo thumb($val, 60);?>" onload="javascript:DrawImage(this,40,40);"></span></a>
              <p><span class="thumb size100"><i></i><img src="<?php echo thumb($val, 240);?>" onload="javascript:DrawImage(this,100,100);" title="<?php echo $val['doctors_name']?>"><big></big><small></small></span></p>
            </dd>
            <dd class="price pngFix"><?php echo $val['doctors_price']?></dd>
            <dd class="collection pngFix"><?php echo $lang['nc_collection_popularity'].$lang['nc_colon'];?><strong><?php echo $val['doctors_collect'];?></strong></dd>
          </dl>
        </li>
        <?php }?>
      </ol>
      <?php }?>
    </div>
    <p><a href="<?php echo urlclinic('show_clic', 'doctors_all', array('clic_id' => $output['clic_info']['clic_id']));?>"><?php echo $lang['nc_look_more_clic_doctors'];?></a></p>
  </div>
</div> -->
<script type="text/javascript">
    $(document).ready(function(){
        //热销排行切换
        $('#hot_sales_tab').on('mouseenter', function() {
            $(this).addClass('current');
            $('#hot_collect_tab').removeClass('current');
            $('#hot_sales_list').removeClass('hide');
            $('#hot_collect_list').addClass('hide');
        });
        $('#hot_collect_tab').on('mouseenter', function() {
            $(this).addClass('current');
            $('#hot_sales_tab').removeClass('current');
            $('#hot_sales_list').addClass('hide');
            $('#hot_collect_list').removeClass('hide');
        });
    });
    /** left.php **/
    // 商品分类
    function class_list(obj){
    	var stc_id=$(obj).attr('span_id');
    	var span_class=$(obj).attr('class');
    	if(span_class=='ico-block') {
    		$("#stc_"+stc_id).show();
    		$(obj).html('<em>-</em>');
    		$(obj).attr('class','ico-none');
    	}else{
    		$("#stc_"+stc_id).hide();
    		$(obj).html('<em>+</em>');
    		$(obj).attr('class','ico-block');
    	}
    }
</script> 
