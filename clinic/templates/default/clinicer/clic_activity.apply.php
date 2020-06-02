<?php defined('InclinicNC') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
  <div class="text-intro"> <?php echo $lang['clic_activity_theme'].$lang['nc_colon'];//'活动主题';?> <?php echo $output['activity_info']['activity_title'];?></div>
</div>
<form method="GET">
  <input type="hidden" name="act" value="clic"/>
  <input type="hidden" name="op" value="activity_apply"/>
  <input type="hidden" value="<?php echo intval($_GET['activity_id']);?>" name="activity_id"/>
  <table class="ncsc-table-style" >
    <thead>
      <tr>
        <th class="w50"></th>
        <th class="w300 tl"><?php echo $lang['clic_activity_doctors_name'];?></th>
        <th>售价</th>
        <th class="w120"><?php echo $lang['clic_activity_confirmstatus']; //审核状态?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($output['list']) and is_array($output['list'])){?>
      <?php foreach ($output['list'] as $k=>$v){ ?>
      <tr class="bd-line">
        <td><div class="pic-thumb"><a href="index.php?act=doctors&doctors_id=<?php echo $v['doctors_id']; ?>" target="_blank"><img src="<?php echo cthumb($v['doctors_image'], 60,$_SESSION['clic_id']);?>"></a></div></td>
        <td class="tl"><dl class="doctors-name">
            <dt><a target="_blank" href="<?php echo urlclinic('doctors', 'index', array('doctors_id' => $v['doctors_id']));?>"><?php echo $v['doctors_name'];?></a></dt>
            <dd><?php echo $v['gc_name'];?></dd>
          </dl></td>
        <td>￥<?php echo $v['doctors_price'];?></td>
        <td><?php if($v['activity_detail_state']=='1'){
          			echo $lang['clic_activity_pass'];
          		  }elseif(in_array($v['activity_detail_state'],array('0','3'))){
          		  	echo $lang['clic_activity_audit'];
          		  }
          	?></td>
      </tr>
      <?php }?>
      <?php }else{?>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
      </tr>
      <?php }?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="20"></td>
      </tr>
    </tfoot>
  </table>
</form>

  <div class="div-doctors-select">
    <form method="GET">
      <input type="hidden" name="act" value="clic_activity"/>
      <input type="hidden" name="op" value="activity_apply"/>
      <input type="hidden" name="activity_id" value="<?php echo $_GET['activity_id'];?>"/>
      <table class="search-form">
        <tr>
          <th class="w250"><strong>选择参加活动的商品，勾选并提交平台审核</strong></th>
          <td class="w160"><input type="text" class="text w150" name="name" value="<?php echo $output['search']['name'];?>" placeholder="搜索商品名称"/></td>
          <td class="w70 tc"><label class="submit-bappointment">
              <input type="submit" class="submit" value="<?php echo $lang['clic_activity_search'];?>"/>
            </label></td><td></td>
        </tr>
      </table>
    </form>
    <form method="POST" id="apply_form" onsubmit="ajaxpost('apply_form','','','onerror');" action="index.php?act=clic_activity&op=activity_apply_save">
      <input type="hidden" name="activity_id" value="<?php echo $_GET['activity_id'];?>"/>
      <?php if(!empty($output['doctors_list']) and is_array($output['doctors_list'])){?>
      <div class="search-result">
        <ul class="doctors-list">
          <?php foreach ($output['doctors_list'] as $doctors){?>
          <li>
            <div class="doctors-thumb"><a href="<?php echo urlclinic('doctors', 'index', array('doctors_id' => $doctors['doctors_id']));?>" target="_blank"><img alt="<?php echo $doctors['doctors_name'];?>" title="<?php echo $doctors['doctors_name'];?>" src="<?php echo cthumb($doctors['doctors_image'], 240, $_SESSION['clic_id']);?>"/></a></div>
            <dl class="doctors-info">
              <dt>
                <input type="checkbox" value="<?php echo $doctors['doctors_id'];?>" class="vm" name="item_id[]"/>
                <label><a href="<?php echo urlclinic('doctors', 'index', array('doctors_id' => $doctors['doctors_id']));?>" target="_blank"><?php echo $doctors['doctors_name'];?></a></label>
              </dt>
              <dd>销售价格：￥<?php echo $doctors['doctors_price'];?></dd>
            </dl>
          </li>
          <?php }?>
          <div class="clear"></div>
        </ul>
      </div>
      <div class="pagination"><?php echo $output['show_page'];?></div>
      <?php }else{?>
      <div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];//您尚未发布任何商品?></span></div>
      <?php }?>
      <?php if(!empty($output['doctors_list']) and is_array($output['doctors_list'])){?>
      <div class="bottom tc p10">
        <input type="submit" class="submit" style="display: inline; *display: inline; zoom: 1;" value="<?php echo $lang['clic_activity_join_now'];?>"/>
      </div>
      <?php }?>
    </form>
  </div>
