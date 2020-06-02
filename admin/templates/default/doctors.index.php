<?php defined('InclinicNC') or exit('Access Invalid!');?>
<link href="<?php echo ADMIN_TEMPLATES_URL;?>/css/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
  <link rel="stylesheet" href="<?php echo ADMIN_TEMPLATES_URL;?>/css/font/font-awesome/css/font-awesome-ie7.min.css">
<![endif]-->
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['doctors_index_doctors'];?></h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['doctors_index_all_doctors'];?></span></a></li>
        <li><a href="<?php echo urlAdmin('doctors', 'doctors', array('type' => 'lockup'));?>" ><span><?php echo $lang['doctors_index_lock_doctors'];?></span></a></li>
        <li><a href="<?php echo urlAdmin('doctors', 'doctors', array('type' => 'waitverify'));?>"><span>Waiting for audit</span></a></li>
        <li><a href="<?php echo urlAdmin('doctors', 'doctors_set');?>"><span><?php echo $lang['nc_doctors_set'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch">
    <input type="hidden" name="act" value="doctors">
    <input type="hidden" name="op" value="doctors">
    <table class="tb-type1 nobappointment search">
      <tbody>
        <tr>
          <th><label for="search_doctors_name"> <?php echo $lang['doctors_index_name'];?></label></th>
          <td><input type="text" value="<?php echo $output['search']['search_doctors_name'];?>" name="search_doctors_name" id="search_doctors_name" class="txt"></td>
          <th><label for="search_commonid">Platform  ID</label></th>
          <td><input type="text" value="<?php echo $output['search']['search_commonid']?>" name="search_commonid" id="search_commonid" class="txt" /></td>
          <th><label><?php echo $lang['doctors_index_class_name'];?></label></th>
          <td id="gcategory" colspan="8"><input type="hidden" id="cate_id" name="cate_id" value="" class="mls_id" />
            <input type="hidden" id="cate_name" name="cate_name" value="" class="mls_names" />
            <select class="querySelect">
              <option><?php echo $lang['nc_please_choose'];?>...</option>
              <?php if(!empty($output['doctors_class']) && is_array($output['doctors_class'])){ ?>
              <?php foreach($output['doctors_class'] as $val) { ?>
              <option value="<?php echo $val['gc_id']; ?>" <?php if($output['search']['cate_id'] == $val['gc_id']){?>selected<?php }?>><?php echo $val['gc_name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
          
        </tr>
        <tr>
          <th><label for="search_clic_name"><?php echo $lang['doctors_index_clic_name'];?></label></th>
          <td><input type="text" value="<?php echo $output['search']['search_clic_name'];?>" name="search_clic_name" id="search_clic_name" class="txt"></td>
          <th><label><?php echo $lang['doctors_index_brand'];?></label></th>
          <td><select name="search_brand_id">
              <option value=""><?php echo $lang['nc_please_choose'];?>...</option>
              <?php if(!empty($output['brand_list']) && is_array($output['brand_list'])){ ?>
              <?php foreach($output['brand_list'] as $k => $v){ ?>
              <option value="<?php echo $v['brand_id'];?>" <?php if($output['search']['search_brand_id'] == $v['brand_id']){?>selected<?php }?>><?php echo $v['brand_name'];?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
          <th><label><?php echo $lang['doctors_index_show'];?></label></th>
          <td><select name="search_state">
              <option value=""><?php echo $lang['nc_please_choose'];?>...</option>
              <?php foreach ($output['state'] as $key => $val){?>
              <option value="<?php echo $key;?>" <?php if($output['search']['search_state'] != '' && $output['search']['search_state'] == $key){?>selected<?php }?>><?php echo $val;?></option>
              <?php }?>
            </select></td>
         <th><label>Waiting for audit</label></th>
          <td><select name="search_verify">
              <option value=""  ><?php echo $lang['nc_please_choose'];?>...</option>
              <?php foreach ($output['verify'] as $key => $val){?>
              <option value="<?php echo $key;?>" <?php if($output['search']['search_verify'] != '' && $output['search']['search_verify'] == $key){?>selected<?php }?>><?php echo $val;?></option>
              <?php }?>
            </select></td> <td ><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a></td>
          <td class="w120">&nbsp;</td>
        </tr>
      </tbody>
    </table>
  </form>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title">
            <h5><?php echo $lang['nc_prompts'];?></h5>
            <span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td><ul>
            <li><?php echo $lang['doctors_index_help1'];?></li>
            <li><?php echo $lang['doctors_index_help2'];?></li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <form method='post' id="form_doctors" action="<?php echo urlAdmin('doctors', 'doctors_del');?>">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th class="w24"></th>
          <th class="w24"></th>
          <th class="align-center">Platform ID</th>
          <th colspan="2"><?php echo $lang['doctors_index_name'];?></th>
          <th><?php echo $lang['doctors_index_brand'];?>&<?php echo $lang['doctors_index_class_name'];?></th>
          <th class="align-center">price</th>
          <!-- <th class="align-center">库存</th> -->
          <th class="align-center">Doctor state</th>
          <th class="align-center">Review state</th>
          <th class="w48 align-center"><?php echo $lang['nc_handle'];?> </th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($output['doctors_list']) && is_array($output['doctors_list'])) { ?>
        <?php foreach ($output['doctors_list'] as $k => $v) {?>
        <tr class="hover edit">
          <td><input type="checkbox" name="id[]" value="<?php echo $v['doctors_commonid'];?>" class="checkitem"></td>
          <td><i class="icon-plus-sign" style="cursor: pointer;" nctype="ajaxdoctorsList" data-comminid="<?php echo $v['doctors_commonid'];?>" title="点击展开查看此商品全部规格；规格值过多时请横向拖动区域内的滚动条进行浏览。"></i></td>
          <td class="w60 align-center"><?php echo $v['doctors_commonid'];?></td>
          <td class="w60 picture"><div class="size-56x56"><span class="thumb size-56x56"><i></i><img src="<?php echo thumb($v, 60);?>" onload="javascript:DrawImage(this,56,56);"/></span></div></td>
          <td class="doctors-name w270"><p><span><?php echo $v['doctors_name'];?></span></p>
            <p class="clic"><?php echo $lang['doctors_index_clic_name'];?>:<?php echo $v['clic_name'];?></p></td>
          <td><p><?php echo $v['brand_name'];?></p>
            <p><?php echo $v['gc_name'];?></p></td>
          <td class="align-center"><?php echo $lang['currency'].$v['doctors_price']?></td>
          <!-- <td class="align-center"><?php echo $output['storage_array'][$v['doctors_commonid']]['sum']?></td> -->
          <td class="align-center"><?php echo $output['state'][$v['doctors_state']];?></td>
          <td class="align-center"><?php echo $output['verify'][$v['doctors_verify']];?></td>
          <td class="align-center"><p><a href="<?php echo urlclinic('doctors', 'index', array('doctors_id' => $output['storage_array'][$v['doctors_commonid']]['doctors_id']));?>" target="_blank"><?php echo $lang['nc_view'];?></a></p>
            <p><a href="javascript:void(0);" onclick="doctors_lockup(<?php echo $v['doctors_commonid'];?>);">illegal removal</a></p></td>
        </tr>
        <tr style="display:none;">
          <td colspan="20"><div class="ncsc-doctors-sku ps-container"></div></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr class="no_data">
          <td colspan="15"><?php echo $lang['nc_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td><input type="checkbox" class="checkall" id="checkallBottom"></td>
          <td colspan="16"><label for="checkallBottom"><?php echo $lang['nc_select_all']; ?></label>
            &nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn" nctype="lockup_batch"><span>illegal removal</span></a> <a href="JavaScript:void(0);" class="btn" nctype="del_batch"><span><?php echo $lang['nc_del'];?></span></a>
            <div class="pagination"> <?php echo $output['page'];?> </div></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script> 
<script type="text/javascript">
var SITEURL = "<?php echo clinic_SITE_URL; ?>";
$(function(){
    gcategoryInit("gcategory");
    $('#ncsubmit').click(function(){
        $('input[name="op"]').val('doctors');$('#formSearch').submit();
    });

    // 违规下架批量处理
    $('a[nctype="lockup_batch"]').click(function(){
        str = getId();
        if (str) {
            doctors_lockup(str);
        }
    });

    // 批量删除
    $('a[nctype="del_batch"]').click(function(){
        if(confirm('<?php echo $lang['nc_ensure_del'];?>')) {
        ajaxpost('form_doctors', '', '', 'onerror');
        }
    });
    
    // ajax获取商品列表
    $('i[nctype="ajaxdoctorsList"]').toggle(
        function(){
            $(this).removeClass('icon-plus-sign').addClass('icon-minus-sign');
            var _parenttr = $(this).parents('tr');
            var _commonid = $(this).attr('data-comminid');
            var _div = _parenttr.next().find('.ncsc-doctors-sku');
            if (_div.html() == '') {
                $.getJSON('index.php?act=doctors&op=get_doctors_list_ajax' , {commonid : _commonid}, function(date){
                    if (date != 'false') {
                        var _ul = $('<ul class="ncsc-doctors-sku-list"></ul>');
                        $.each(date, function(i, o){
                            $('<li><div class="doctors-thumb" title="Platform ID：' + o.doctors_serial + '"><a href="' + o.url + '" target="_blank"><image src="' + o.doctors_image + '" ></a></div>' + o.doctors_spec + '<div class="doctors-price">price：<em title="￥' + o.doctors_price + '">$' + o.doctors_price + '</em></div><a href="' + o.url + '" target="_blank" class="ncsc-btn-mini">Check details</a></li>').appendTo(_ul);
                            });
                        _ul.appendTo(_div);
                        _parenttr.next().show();
                        // 计算div的宽度
                        _div.css('width', document.body.clientWidth-54);
                        _div.perfectScrollbar();
                    }
                });
            } else {
            	_parenttr.next().show()
            }
        },
        function(){
            $(this).removeClass('icon-minus-sign').addClass('icon-plus-sign');
            $(this).parents('tr').next().hide();
        }
    );
});

// 获得选中ID
function getId() {
    var str = '';
    $('#form_doctors').find('input[name="id[]"]:checked').each(function(){
        id = parseInt($(this).val());
        if (!isNaN(id)) {
            str += id + ',';
        }
    });
    if (str == '') {
        return false;
    }
    str = str.substr(0, (str.length - 1));
    return str;
}

// 商品下架
function doctors_lockup(ids) {
    _uri = "<?php echo ADMIN_SITE_URL;?>/index.php?act=doctors&op=doctors_lockup&id=" + ids;
    CUR_DIALOG = ajax_form('doctors_lockup', 'Reasons for illegal removal', _uri, 350);
}
</script> 
