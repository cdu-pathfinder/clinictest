<?php defined('InclinicNC') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>店铺统计</h3>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div style="width:100%; text-align:right;padding-top:10px;">
  	<input type="hidden" id="export_type" name="export_type" data-param='{"url":"<?php echo $output['actionurl']; ?>&exporttype=excel"}' value="excel"/>
  	<a class="btns" href="javascript:void(0);" id="export_btn"><span>导出Excel</span></a>
  </div>
  <form method="post" id="form_member">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th class="align-center">店铺名称</th>
          <th class="align-center">店主账号</th>
          <th class="align-center">店主卖家账号</th>
          <th class="align-center">所属等级</th>
          <th class="align-center">有效期至</th>
          <th class="align-center">开店时间</th>
        </tr>
      <tbody id="datatable">
        <?php if(!empty($output['clic_list']) && is_array($output['clic_list'])){ ?>
        <?php foreach($output['clic_list'] as $k => $v){ ?>
        <tr class="hover member">
          <td class="align-center"><?php echo $v['clic_name']; ?></td>
          <td class="align-center"><?php echo $v['member_name']; ?></td>
          <td class="align-center"><?php echo $v['clinicer_name']; ?></td>
          <td class="align-center"><?php echo $output['search_grade_list'][$v['grade_id']]; ?></td>
          <td class="align-center"><?php echo $v['clic_end_time']?date('Y-m-d', $v['clic_end_time']):'无限制'; ?></td>
          <td class="align-center"><?php echo date('Y-m-d', $v['clic_time']); ?></td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td colspan="11"><?php echo $lang['nc_no_record']?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot class="tfoot">
        <?php if(!empty($output['clic_list']) && is_array($output['clic_list'])){ ?>
        <tr>
          <td colspan="16">
            <div class="pagination"> <?php echo $output['show_page'];?> </div></td>
        </tr>
        <?php } ?>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/statistics.js"></script>
<script>
$(function(){
	//导出图表
    $("#export_btn").click(function(){
        var item = $("#export_type");
        var type = $(item).val();
        if(type == 'excel'){
        	download_excel(item);
        }
    });
});
</script>
