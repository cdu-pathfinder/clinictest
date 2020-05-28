<?php defined('InShopNC') or exit('Access Invalid!');?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script>
<script type="text/javascript">
$(document).ready(function(){
	gcategoryInit("gcategory");

    jQuery.validator.addMethod("seller_name_exist", function(value, element, params) { 
        var result = true;
        $.ajax({  
            type:"GET",  
            url:'<?php echo urlShop('store_joinin', 'check_seller_name_exist');?>',  
            async:false,  
            data:{seller_name: $('#seller_name').val()},  
            success: function(data){  
                if(data == 'true') {
                    $.validator.messages.seller_name_exist = "clinic account already exists";
                    result = false;
                }
            }  
        });  
        return result;
    }, '');

    $('#form_store_info').validate({
        errorPlacement: function(error, element){
            element.nextAll('span').first().after(error);
        },
        rules : {
            seller_name: {
                required: true,
                maxlength: 50,
                seller_name_exist: true
            },
            store_name: {
                required: true,
                maxlength: 50 
            },
            sg_name: {
                required: true
            },
            sc_name: {
                required: true
            },
            store_class: {
                required: true,
                min: 1
            }
        },
        messages : {
            seller_name: {
                required: 'Please fill in the clinic user name',
                maxlength: jQuery.validator.format("less than {0} words")
            },
            store_name: {
                required: 'Please fill clinic name',
                maxlength: jQuery.validator.format("less than {0} words")
            },
            sg_name: {
                required: 'Please select the clinic level'
            },
            sc_name: {
                required: 'Please select the clnic category'
            },
            store_class: {
                required: 'Please select business category',
                min: 'Please select business category'
            }
        }
    });

    $('#btn_select_category').on('click', function() {
        $('#gcategory').show();
        $('#btn_select_category').hide();
        $('#gcategory_class1').val(0).nextAll("select").remove();
    });

    $('#btn_add_category').on('click', function() {
        var tr_category = '<tr class="store-class-item">';
        var category_id = '';
        var category_name = '';
        var class_count = 0;
        var validation = true;
        $('#gcategory').find('select').each(function() {
            if(parseInt($(this).val(), 10) > 0) {
                var name = $(this).find('option:selected').text();
                tr_category += '<td>';
                tr_category += name;
                tr_category += '</td>';
                category_id += $(this).val() + ',';
                category_name += name + ',';
                class_count++;
            } else {
                validation = false;
            }
        });
        if(validation) {
            for(; class_count < 3; class_count++) {
                tr_category += '<td></td>';
            }
            tr_category += '<td><a nctype="btn_drop_category" href="javascript:;">delete</a></td>';
            tr_category += '<input name="store_class_ids[]" type="hidden" value="' + category_id + '" />';
            tr_category += '<input name="store_class_names[]" type="hidden" value="' + category_name + '" />';
            tr_category += '</tr>';
            $('#table_category').append(tr_category);
            $('#gcategory').hide();
            $('#btn_select_category').show();
            select_store_class_count();
        } else {
            showError('Please select category');
        }
    });

    $('#table_category').on('click', '[nctype="btn_drop_category"]', function() {
        $(this).parent('td').parent('tr').remove();
        select_store_class_count();
    });

    // 统计已经选择的经营类目
    function select_store_class_count() {
        var store_class_count = $('#table_category').find('.store-class-item').length;
        $('#store_class').val(store_class_count);
    }

    $('#btn_cancel_category').on('click', function() {
        $('#gcategory').hide();
        $('#btn_select_category').show();
    });

    $('#sg_id').on('change', function() {
        if($(this).val() > 0) {
            $('#grade_explain').text($(this).find('option:selected').attr('data-explain'));
            $('#sg_name').val($(this).find('option:selected').text());
        } else {
            $('#sg_name').val('');
        }
    });

    $('#sc_id').on('change', function() {
        if($(this).val() > 0) {
            $('#sc_name').val($(this).find('option:selected').text());
        } else {
            $('#sc_name').val('');
        }
    });


    $('#btn_apply_store_next').on('click', function() {
        if($('#form_store_info').valid()) {
            $('#form_store_info').submit();
        }
    });
});
</script>
<!-- 店铺信息 -->

<div id="apply_store_info" class="apply-store-info">
  <div id="apply_company_info" class="apply-company-info">
    <div class="note"><i></i>The operating categories of clinics are classified as doctors. Please add one or more operating categories according to the actual operating conditions.</div>
    <form id="form_store_info" action="index.php?act=store_joinin&op=step4" method="post" >
      <table border="0" cellpadding="0" cellspacing="0" class="all">
        <thead>
          <tr>
            <th colspan="20">clinic operation information</th>
          </tr>
        </thead>
        <tbody>
        <tr>
            <th class="w150"><i>*</i>Merchant account:</th>
            <td><input id="seller_name" name="seller_name" type="text" class="w200"/>
              <span></span>
              <p class="emphasis">This account will be used for future login and administration of the clinic center. It cannot be modified after registration. Please keep in mind.</p></td>
          </tr>
          <tr>
            <th class="w150"><i>*</i>clinic name:</th>
            <td><input name="store_name" type="text" class="w200"/>
              <span></span>
              <p class="emphasis">The name of the clinic cannot be modified after registration, please fill in carefully.</p></td>
          </tr>
          <tr>
            <th><i>*</i>clinic level:</th>
            <td><select name="sg_id" id="sg_id">
                <option value="0">please choose</option>
                <?php if(!empty($output['grade_list']) && is_array($output['grade_list'])){ ?>
                <?php foreach($output['grade_list'] as $k => $v){ ?>
                <?php $goods_limit = empty($v['sg_goods_limit'])?'no limit':$v['sg_goods_limit'];?>
                <?php $explain = 'doctors：'.$goods_limit.' templates：'.$v['sg_template_number'].' Charge standard:'.$v['sg_price'].' Additional features:'.$v['function_str'];?>
                <option value="<?php echo $v['sg_id'];?>" data-explain="<?php echo $explain;?>"><?php echo $v['sg_name'];?></option>
                <?php } ?>
                <?php } ?>
              </select>
              <input id="sg_name" name="sg_name" type="hidden" />
              <span></span>
              <div id="grade_explain" class="grade_explain"></div></td>
          </tr>
          <tr>
            <th><i>*</i>clinic classification:</th>
            <td><select name="sc_id" id="sc_id">
                <option value="0">please choose</option>
                <?php if(!empty($output['store_class']) && is_array($output['store_class'])){ ?>
                <?php foreach($output['store_class'] as $k => $v){ ?>
                <option value="<?php echo $v['sc_id'];?>"><?php echo $v['sc_name'];?></option>
                <?php } ?>
                <?php } ?>
              </select>
              <input id="sc_name" name="sc_name" type="hidden" />
              <span></span>
              <p class="emphasis">Please carefully select the clinic classification according to the content of your business. After registration, the business cannot modify by itself.</p></td>
          </tr>
          <tr>
            <th><i>*</i>Business category:</th>
            <td><a href="###" id="btn_select_category" class="btn">+Select add category</a>
              <div id="gcategory" style="display:none;">
                <select id="gcategory_class1">
                  <option value="0">please choose</option>
                  <?php if(!empty($output['gc_list']) && is_array($output['gc_list']) ) {?>
                  <?php foreach ($output['gc_list'] as $gc) {?>
                  <option value="<?php echo $gc['gc_id'];?>"><?php echo $gc['gc_name'];?></option>
                  <?php }?>
                  <?php }?>
                </select>
                <input id="btn_add_category" type="button" value="comfirm" />
                <input id="btn_cancel_category" type="button" value="cancel" />
            </div>
              <input id="store_class" name="store_class" type="hidden" />
              <span></span>
          </td>
          </tr>
          <tr>
            <td colspan="2"><table border="0" cellpadding="0" cellspacing="0" id="table_category" class="type">
                <thead>
                  <tr>
                    <th>The primary category</th>
                    <th>The secondary category</th>
                    <th>The tertiary category</th>
                    <th>operation</th>
                  </tr>
                </thead>
              </table></td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="20">&nbsp;</td>
          </tr>
        </tfoot>
      </table>
    </form>
    <div class="bottom"><a id="btn_apply_store_next" href="javascript:;" class="btn">Next</a></div>
  </div>
</div>
