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
        <li><a href="<?php echo urlAdmin('doctors', 'doctors');?>" ><span><?php echo $lang['doctors_index_all_doctors'];?></span></a></li>
        <li><a href="<?php echo urlAdmin('doctors', 'doctors', array('type' => 'lockup'));?>"><span><?php echo $lang['doctors_index_lock_doctors'];?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span>等待审核</span></a></li>
        <li><a href="<?php echo urlAdmin('doctors', 'doctors_set');?>"><span><?php echo $lang['nc_doctors_set'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch">
    <input type="hidden" name="act" value="doctors" />
    <input type="hidden" name="op" value="doctors" />
    <input type="hidden" name="type" value="waitverify" />
    <table class="tb-type1 nobappointment search">
      <tbody>
        <tr>
          <th> <label for="search_doctors_name"><?php echo $lang['doctors_index_name'];?></label></th>
          <td><input type="text" value="<?php echo $output['search']['search_doctors_name'];?>" name="search_doctors_name" id="search_doctors_name" class="txt"></td>
          <th>审核状态</th>
          <td><select name="search_verify">
            <option><?php echo $lang['nc_please_choose'];?>...</option>
            <option value="10" <?php if ($output['search']['search_verify'] != '' && $output['search']['search_verify'] == 10) {?>selected="selected"<?php }?>>等待审核</option>
            <option value="0" <?php if ($output['search']['search_verify'] != '' && $output['search']['search_verify'] == 0) {?>selected="selected"<?php }?>>未通过</option>
          </select></td>
          <th><label><?php echo $lang['doctors_index_class_name'];?></label></th>
          <td id="gcategory"><input type="hidden" id="cate_id" name="cate_id" value="" class="mls_id" />
            <input type="hidden" id="cate_name" name="cate_name" value="" class="mls_names" />
            <select>
              <option><?php echo $lang['nc_please_choose'];?>...</option>
              <?php foreach($output['doctors_class'] as $val) { ?>
              <option value="<?php echo $val['gc_id']; ?>" <?php if($output['search']['cate_id'] == $val['gc_id']){?>selected<?php }?>><?php echo $val['gc_name']; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <th><label for="search_clic_name"><?php echo $lang['doctors_index_clic_name'];?></label></th>
          <td><input type="text" value="<?php echo $output['search']['search_clic_name'];?>" name="search_clic_name" id="search_clic_name" class="txt"></td>
          <th><label><?php echo $lang['doctors_index_brand'];?></label></th>
          <td><select name="search_brand_id">
              <option value=""><?php echo $lang['nc_please_choose'];?>...</option>
              <?php if(is_array($output['brand_list'])){ ?>
              <?php foreach($output['brand_list'] as $k => $v){ ?>
              <option value="<?php echo $v['brand_id'];?>" <?php if($output['search']['search_brand_id'] == $v['brand_id']){?>selected<?php }?>><?php echo $v['brand_name'];?></option>
              <?php } ?>
              <?php } ?>
            </select>
            <a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
            
            </td>
        </tr>
      </tbody>
    </table>
  </form>
  <form method='post' id="form_doctors" action="<?php echo urlAdmin('doctors', 'doctors_del');?>">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <thead>
        <tr class="space">
          <th colspan="15"><?php echo $lang['nc_list'];?></th>
        </tr>
        <tr class="thead">
          <th></th>
          <th class="w24"></th>
          <th>平台货号</th>
          <th colspan="2"><?php echo $lang['doctors_index_name'];?></th>
          <th><?php echo $lang['doctors_index_brand'];?>&<?php echo $lang['doctors_index_class_name'];?></th>
          <th class="align-center">价格</th>
          <th class="align-center">库存</th>
          <th class="align-center">审核状态</th>
          <th class="align-center"><?php echo $lang['nc_handle'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['doctors_list']) && is_array($output['doctors_list'])){ ?>
        <?php foreach($output['doctors_list'] as $k => $v){  ?>
        <tr class="hover edit">
          <td class="w24"><input type="checkbox" name="id[]" value="<?php echo $v['doctors_commonid'];?>" class="checkitem"></td>
          <td><i class="icon-plus-sign" nctype="ajaxdoctorsList" data-comminid="<?php echo $v['doctors_commonid'];?>" style="cursor: pointer;"></i></td>
          <td><?php echo $v['doctors_commonid'];?></td>
          <td class="w60"><div class="doctors-picture"><span class="thumb size-doctors"><i></i><img src="<?php echo thumb($v, 60);?>" onload="javascript:DrawImage(this,56,56);"/></span></div></td>
          <td class="doctors-name w270"><p><span><?php echo $v['doctors_name'];?></span></p>
            <p class="clic"><?php echo $lang['doctors_index_clic_name'];?>:<?php echo $v['clic_name'];?></p></td>
          <td class="w200">
            <p><?php echo $v['brand_name'];?></p>
            <p><?php echo $v['gc_name'];?></p>
          </td>
          <td class="align-center"><?php echo $lang['currency'].$v['doctors_price']?></td>
          <td class="align-center"><?php echo $output['storage_array'][$v['doctors_commonid']]['sum']?></td>
          <td class="align-center">
            <p><?php echo $output['verify'][$v['doctors_verify']];?></p>
            <?php if ($v['doctors_verify'] == 0) {?><p><?php echo $v['doctors_verifyremark'];?></p><?php }?>
          </td>
          <td class="w48 align-center">
            <p><a href="<?php echo urlclinic('doctors', 'index', array('doctors_id' => $output['storage_array'][$v['doctors_commonid']]['doctors_id']));?>" target="_blank"><?php echo $lang['nc_view'];?></a></p>
            <p><a href="javascript:void(0);" onclick="doctors_verify(<?php echo $v['doctors_commonid'];?>);">审核</a></p>
          </td>
        </tr>
        <tr style="display:none;"><td colspan="20"><div class="ncsc-doctors-sku ps-container"></div></td></tr>
        <?php } ?>
        <?php } else { ?>
        <tr class="no_data">
          <td colspan="10"><?php echo $lang['nc_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td><input type="checkbox" class="checkall" id="checkallBottom"></td>
          <td colspan="16"><label for="checkallBottom"><?php echo $lang['nc_select_all']; ?></label>
            &nbsp;&nbsp;<a href="javascript:void(0);" class="btn" nctype="verify_batch"><span>审核</span></a>
            <a href="JavaScript:void(0);" class="btn" nctype="del_batch"><span><?php echo $lang['nc_del'];?></span></a>
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
    
    // 批量删除
    $('a[nctype="del_batch"]').click(function(){
        ajaxpost('form_doctors', '', '', 'onerror');
    });

    // 审核批量处理
    $('a[nctype="verify_batch"]').click(function(){
        str = getId();
        if (str) {
            doctors_verify(str);
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
                            $('<li><div class="doctors-thumb" title="商家货号：' + o.doctors_serial + '"><a href="' + o.url + '" target="_blank"><image src="' + o.doctors_image + '" ></a></div>' + o.doctors_spec + '<div class="doctors-price">价格：<em title="￥' + o.doctors_price + '">￥' + o.doctors_price + '</em></div><div class="doctors-storage">库存：<em title="' + o.doctors_storage + '">' + o.doctors_storage + '</em></div><a href="' + o.url + '" target="_blank" class="ncsc-btn-mini">查看商品详情</a></li>').appendTo(_ul);
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

// 获得选中哎
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
function doctors_verify(ids) {
    _uri = "<?php echo ADMIN_SITE_URL;?>/index.php?act=doctors&op=doctors_verify&id=" + ids;
    CUR_DIALOG = ajax_form('doctors_verify', '审核商品', _uri, 350);
}
</script>